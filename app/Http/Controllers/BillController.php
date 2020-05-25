<?php

namespace App\Http\Controllers;

use App\Model\RegistrationModel;
use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class BillController extends Controller
{
    protected $client;

    public function __construct(){
        // Creating an environment
        $clientId = config('app.CLIENT_ID', 'true');// ?: "PAYPAL-SANDBOX-CLIENT-ID";
        $clientSecret = config('app.CLIENT_SECRET', 'true');// ?: "PAYPAL-SANDBOX-CLIENT-SECRET";

        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($environment);
    }

    public function view(){
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);

        $registrationModel = new RegistrationModel();
        $user_list = $registrationModel -> getAllUserName();
        return view("payment/view", [
            'type' => $type,
            'admin' => $name,
            'users' => $user_list,
        ]);
    }
    public function creditcard(Request $request){
        $user_id = session()->get(SESS_UID);
        if($user_id == null)
            abort(401,'Check you login status');
        $currencyid = $request->currency;
        $currency = ["AUD","USD","EUR","NZD","CNY","CAD","GBP","JPY"];
        $price = 100;
        $cardEmail = $request->email;

        $stripe_token = $request->card_token;
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $cus_id = null;
        $error = null;

        try{
            $customer = Customer::create([
                'email' => $cardEmail,
                'source' => $stripe_token
            ]);
            $cus_id = $customer->id;

        } catch (CardException $e){
            $body = $e->getJsonBody();
            $err = $body['error'];
            $error = 'Customer ' . $cardEmail . ': error: ' . $err['code'];
        } catch (\Exception $e){
            $error = 'Stripe Api Issue :' . $e->getCode();
        }
        if($error != null){
            //return back()->with('error', $error);
            abort(500,'Wrong to payment!');
        }

        try{
            $customerInfo = Customer::retrieve($cus_id);
            $cardinfo = $customerInfo->sources->data;
            $card_number = $cardinfo[0]->id;
            $countryCode = $cardinfo[0]->country;
            $card = $customerInfo->sources->retrieve($card_number);
            $card->name = session() -> get(SESS_USERNAME);
            $card->save();
        } catch (CardException $e){
            $body = $e->getJsonBody();
            $err = $body['error'];
            $error = 'Customer ' . $cardEmail . ': error: ' . $err['code'];
        } catch (\Exception $e){
            $error = 'Stripe Api Issue :' . $e->getCode();
        }
        if($error != null){
            $cu = Customer::retrieve($cus_id);
            $cu->delete();
            abort(500,'Wrong to payment!');
        }

        try{
            $charge = Charge::create([
                "amount" => $price,
                "currency" => $currency[$currencyid],
                "customer" => $cus_id,
                "capture" => false,
                "description" => "Usage Price",
            ]);

            $source = $charge->source;
            $ch_id = $charge->id;
            $out = $charge->outcome;

            if($out->reason == 'elevated_risk_level'){
                $error = 'Your card was declined. Please try with another card';
            } elseif ($out->reason == 'highest_risk_level'){
                $error = 'Your card was declined. Please try with another card';
            } elseif ($out->reason == 'merchant_blacklis'){
                $error = 'Your card was declined. Please try with another card';
            } elseif ($source->funding == 'prepaid'){
                $error = 'Sorry, but we dont allow prepaid Cards. Please use a credit / debit valid card';
            }

            if (strpos($ch_id, 'ch_') === false || $charge->failure_message != ''){
                $error = $charge->failure_message;
            }
            if($error != null){
                $cu = Customer::retrieve($cus_id);
                $cu->delete();
                abort(500,'Wrong to payment!');
            }
        } catch (CardException $e){
            $body = $e->getJsonBody();
            $err = $body['error'];
            $error = 'Customer ' . $cardEmail . ': error: ' . $err['code'];
            if($err['decline_code'] == 'do_not_honor'){
                $error = 'Your card don\'t have funds or isn\'t active';
            }
        } catch (\Exception $e){
            $error = 'The card validation cant be executed at this moment. Please retry later';
        }
        if($error != null){
            $cu = Customer::retrieve($cus_id);
            $cu->delete();
            abort(500,'Wrong to payment!');
        }

        $cardInfo = [
            'user_id' => $user_id,
            'stripe_cus_id' => $cus_id,
            'email' => $cardEmail,
            'currency' => $currency[$currencyid]
        ];
        Card::updateOrCreate(['id' => $user_id],$cardInfo);

        return redirect("/payment/view");
    }

    public function autopay(){
        $plans = DB::table('plan')->get();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        foreach ($plans as $plan){
            $id = $plan->user_id;
            $price = $plan->price;
            $price = floatval($price) * 100;
            $cardInfo = Card::where('user_id',$id)->first();
            if($cardInfo==null)
                continue;
            $error = null;

            try {
                $charge = Charge::create([
                    "amount" => $price,
                    "currency" => $cardInfo->currency,
                    "customer" => $cardInfo->stripe_cus_id,
                    "capture" => false,
                    'description' => "Membership fee"
                ]);
                $source = $charge->source;
                $ch_id = $charge->id;
                $out = $charge->outcome;
                if ($out->reason == 'elevated_risk_level') {
                    $error = 'Your card was declined. Please try with another card';
                } else if ($out->reason == 'highest_risk_level') {
                    $error = 'Your card was declined. Please try with another card';
                } else if ($out->reason == 'merchant_blacklis') {
                    $error = 'Your card was declined. Please try with another card';
                } else if ($source->funding == 'prepaid') {
                    $error = 'Sorry, but we dont allow prepaid Cards. Please use a credit / debit valid card';
                }
                if (strpos($ch_id, 'ch_') === false || $charge->failure_message != null) {
                    $error = $charge->failure_message;
                }

            } catch (CardException $e) {
                $body = $e->getJsonBody();
                $err = $body['error'];
                if ($err['decline_code'] == 'do_not_honor') {
                    $error = 'Your card don\'t have funds or isn\'t active';
                }
            } catch (\Exception $e) {
                //$error = $e->getMessage();
                Log::info($e->getMessage());
                $error = 'The card validation cant be executed at this moment. Please retry later';
            }
            if($error != null) {
                abort(500,"Payment failed");
            }
        }
        return 1;
    }

    public function paypal(Request $request){
        $user_id = session()->get(SESS_UID);
        if($user_id == null)
            abort(401,'Check you login status');
        $price = $request->price;
        $currencyid = $request->currency;
        $currency = ["AUD","USD","EUR","NZD","CNY","CAD","GBP","JPY"];

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = array(
            "intent" => "CAPTURE",
            "purchase_units" => array(
                array(
                    "reference_id" => $user_id . '_' . time(),
                    "amount" => array(
                        "value" => $price,
                        "currency_code" => $currency[$currencyid]
                    )
                ),
            ),
            "application_context" => array(
                "cancel_url" => url('/payment/cancel'),
                "return_url" => url('/payment/back')
            )
        );

        try{
            $response = $this->client->execute($request);
            $redirect_url = $response->result->links[1]->href;

            $item = new \App\Transaction();
            $item->user_id = $user_id;
            $item->transaction_name = "membership payment";
            $item->transaction_type = "paypal";
            $item->orderid = $response->result->id;
            $item->status = 'pending';
            $item->amount = $price;
            $item->currency = $currency[$currencyid];
            $item->save();
            return redirect()->to($redirect_url);
        } catch (HttpException $ex) {
            echo $ex->statusCode;
            dd($ex->getMessage());
        }
    }

    public function back(Request $request){
        $_request = new OrdersCaptureRequest($request->token);
        $_request->prefer('return=representation');
        try{
            $response = $this->client->execute($_request);
            $transactionid = $response->result->purchase_units[0]->payments->captures[0]->id;
            $status = $response->result->purchase_units[0]->payments->captures[0]->status;

            if ($status=="COMPLETED"){
                $item = \App\Transaction::where('orderid', $response->result->id)->first();
                $item->paypal_transactionid = $transactionid;
                $item->status = $status;
                $item->paypal_description = json_encode($response);
                $item->save();
                return redirect()->to('/payment/view');
            }
            else{
                abort(500,'Wrong to payment!');
            }
        } catch (HttpException $ex) {
            echo $ex->statusCode;
            dd($ex->getMessage());
        }
    }

    public function cancel(){
        dd('Cancel');
    }
}
