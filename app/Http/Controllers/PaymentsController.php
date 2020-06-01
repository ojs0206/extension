<?php


namespace App\Http\Controllers;


use App\Model\RegistrationModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use DB;
use Carbon;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Transaction;

class PaymentsController extends Controller
{
//    public function __construct(){
//        Braintree_Configuration::environment("sandbox");//sandbox
//        Braintree_Configuration::merchantId("pbdg3p5wzvztt72p");//
//        Braintree_Configuration::publicKey("rmqxrqdd3hjmbkqx");//
//        Braintree_Configuration::privateKey("0cd4e7de0071a41a78276a2d4a09dc24");//
//    }

    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function payWithpaypal(Request $request)
    {
        $user_id = session() -> get(SESS_UID);
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $currency = $request->get('currency');

        $item_1 = new Item();

        $item_1->setName('Item 1') /** item name **/
        ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($request->get('amount')); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($request->get('amount'));
        \Session::put('amount', $request->get('amount'));
        \Session::put('currency', $currency);
        \Session::put('user_id', $user_id);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL('status')) /** Specify return URL **/
        ->setCancelUrl(URL('status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {

            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {

                \Session::put('error', 'Connection timeout');
                return Redirect::route('paywithpaypal');

            } else {

                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('paywithpaypal');

            }

        }

        foreach ($payment->getLinks() as $link) {

            if ($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();
                break;

            }
        }

        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {

            /** redirect to paypal **/
            return Redirect::away($redirect_url);

        }

        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal');

    }

    public function getPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            \Session::put('error', 'Payment failed');
            return Redirect::route('/payment/summary');

        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            \Session::put('success', 'Payment success');
            $amount_ = session()->get('amount');
            $user_id = session()->get('user_id');
            $currency = session()->get('currency');
            $mytime = Carbon\Carbon::now();
            DB::table("t_transaction")
                ->insert(['user_id' => $user_id, 'income' => $amount_, 'income_date' => $mytime->toDateTimeString(), 'currency' => $currency]);
            return Redirect::route('/payment/summary');

        }

        \Session::put('error', 'Payment failed');
        return Redirect::route('/payment/summary');

    }

//    // process payment with user's paypal or credit card number and id
//    public function processPayment(){
//        $nonce = request('payment_method_nonce');
//        Log::info($nonce);
//        if(empty($nonce)){
//            return "error";
//        }
//        $amount = request('amount');
//        Log::info("Nonce is ".$nonce);
//
//        $result = Braintree_Transaction::sale([
//            'amount' => 1,
//            'paymentMethodNonce' => $_POST['payment_method_nonce'],
//            'options' => [
//                'submitForSettlement' => true
//            ]
//        ]);
//        Log::info("Result is ".$result);
//        if ($result->success || !is_null($result->transaction)) {
//            $transaction = $result->transaction;
//            $id = session() -> get(SESS_UID);
//            $cur = date('Y-m-d H:i:s');
//            $registrationModel = new RegistrationModel();
//            $registrationModel -> userPayment($id, $amount, $cur);
//
//            return redirect('/home');
//        }
//        return "error";
//
//
//    }
//

    public function generateInvoice(){
        $check_list = DB::table("t_transaction")
            ->select("invoice")
            ->get();
        $index = 0;
        $random_invoice = "";
        $length = count($check_list);
        while($index < $length){
            $random_invoice = $this->generateRandomInvoice();
            $index = $this->checkUniqueInvoice($check_list, $random_invoice);
        }
        return $random_invoice;
    }

    public function checkUniqueInvoice($check_list, $random_invoice){
        $index = 0;
        foreach ($check_list as $exist)
        {
            if ($exist != $random_invoice){
                $index++;
            }
        }
        return $index;
    }

    public function generateRandomInvoice(){
        $list = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S",
            "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d",
            "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $random_invoice = $list[rand(0,61)].$list[rand(0,61)].$list[rand(0,61)].$list[rand(0,61)].$list[rand(0,61)];
        return $random_invoice;
    }

    public function showPayment() {
        $name = session() -> get(SESS_USERNAME);
        $usertype = session() -> get(SESS_USERTYPE);
        $type = request('type');
        $amount = 1;
        if($type != 'basic') {
            $amount = 2;
        }
        return view('paypal_payment', [
            'admin' => $name,
            'type' => $usertype,
            'amount' => $amount
        ]);
    }

    public function showSummary() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();

        $current_value = $registrationModel -> getTransactionData($id);
        $trans_hist = $registrationModel -> getTransactionList($id);
        $payment_setting = $registrationModel -> getPaymentSetting($id);
        return view('payment/summary', [
            'admin' => $name,
            'type' => $type,
            'value' => $current_value,
            'history' => $trans_hist,
            'setting' => $payment_setting
        ]);
    }

    public function showTransaction() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();

        $current_value = $registrationModel -> getTransactionData($id);
        $trans_hist = $registrationModel -> getTransactionList($id);
        return view('payment/transaction', [
            'admin' => $name,
            'type' => $type,
            'value' => $current_value,
            'history' => $trans_hist
        ]);
    }

    public function showMethod() {
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();

        return view('payment/payment_method', [
            'admin' => $name,
            'type' => $type
        ]);
    }

    public function showSettings() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();

        $payment_setting = $registrationModel -> getPaymentSetting($id);

        return view('payment/settings', [
            'admin' => $name,
            'type' => $type,
            'setting' => $payment_setting
        ]);
    }

    public function billingRateSetting() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        return view('payment/billing_rate_setting', [
            'type' => $type,
            'admin' => $name,
        ]);
    }

    public function updateSettings() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $currency = request('currency');
        $frequency = request('frequency');
        $registrationModel = new RegistrationModel();

        $registrationModel -> updatePaymentSetting($id, $currency, $frequency);
        $payment_setting = $registrationModel -> getPaymentSetting($id);

        return view('payment/settings', [
            'admin' => $name,
            'type' => $type,
            'setting' => $payment_setting
        ]);
    }


    public function createBilling() {
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);

        $registrationModel = new RegistrationModel();
        $user_list = $registrationModel -> getAllUserName();
        $info = null;

        return view('payment/create_billing', [
            'type' => $type,
            'admin' => $name,
            'users' => $user_list,
            'info' => $info
        ]);
    }

    public function createNewBilling(){
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $userProfileName = request('user_profile_name');
        $primaryEmailAddress = request('primary_email_address');
        $paymentMethod = request('payment_method');
        $country = request('country');
        $state = request('state');
        $phone = request('phone');
        $bpId = request('bp_id');
        $billingFrequency = request('billing_frequency');
        $date = date("Y.m.d");

        $registrationModel = new RegistrationModel();

        $registrationModel -> createNewBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
            $state, $phone, $bpId, $billingFrequency, $date);

        $status = 0;

        return redirect('payment');
    }

    public function updateBilling(){
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $userProfileName = request('user_profile_name');
        $primaryEmailAddress = request('primary_email_address');
        $paymentMethod = request('payment_method');
        $country = request('country');
        $state = request('state');
        $phone = request('phone');
        $bpId = request('bp_id');
        $billingFrequency = request('billing_frequency');
        $date = date("Y.m.d");

        $registrationModel = new RegistrationModel();

        $registrationModel -> updateBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
            $state, $phone, $bpId, $billingFrequency, $date);

        $status = 0;

        return redirect('payment');
    }

    public function editBilling(){
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $info_id = request('url_id');
//        $userProfileName = request('user_profile_name');
//        $primaryEmailAddress = request('primary_email_address');
//        $paymentMethod = request('payment_method');
//        $country = request('country');
//        $state = request('state');
//        $phone = request('phone');
//        $bpId = request('bp_id');
//        $billingFrequency = request('billing_frequency');
//
        $registrationModel = new RegistrationModel();

        $info = $registrationModel -> getBillingInfo($info_id);
        $user_list = $registrationModel -> getAllUserName();
//
//        $registrationModel -> createNewBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
//            $state, $phone, $bpId, $billingFrequency);
//
//        $status = 0;
//
//        return redirect('payment');
        return view("payment/create_billing", [
            'type' => $type,
            'admin' => $name,
            'users' => $user_list,
            'info' => $info
        ]);
    }

    public function deleteBilling() {
        $store_id = request('store_id');
        Log::debug($store_id);
        $registrationModel = new RegistrationModel();
        $registrationModel -> deleteBilling($store_id);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function activeBill() {
        $store_id = request('store_id');
        $active = request('active');
        $registrationModel = new RegistrationModel();
        $registrationModel -> activeBilling($store_id, $active);
        return response()->json([
            'status' => 'ok'
        ]);
    }
}