<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\CardPayment;
use App\Model\RegistrationModel;
use App\Card;
use App\Paypal;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    public function autopay(){
        $plans = DB::table('t_plan')->get();
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
                    "currency" => $plan->currency,
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

            //save payment history
            $card_payment = new CardPayment();
            $card_payment->user_id = $id;
            $card_payment->amount = $plan->price;
            $card_payment->currency = $plan->currency;
            $card_payment->save();
        }
        return 1;
    }


    protected function schedule(Schedule $schedule)
    {
         $schedule->call($this -> autopay())
                  ->monthlyOn(2, '00:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
