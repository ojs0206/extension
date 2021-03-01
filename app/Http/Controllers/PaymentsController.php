<?php


namespace App\Http\Controllers;


use App\BillingRate;
use App\Model\InvoicesExport;
use App\Model\RegistrationModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
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
use Barryvdh\DomPDF\Facade as PDF;
use DB;
use Carbon;
use DateInterval;
use \DateTime;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Transaction;
use function MongoDB\BSON\toJSON;

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

    public function itemStatistic($id){
//        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        $url_list = $registrationModel -> getItemIdClicks($id);
        return view('item_statistic', [
            'type' => $type,
            'admin' => $name,
            'id' => $id,
            'url_list' => $url_list
        ]);
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
        $rate = $registrationModel -> getAllRateType();
        return view('payment/billing_rate_setting', [
            'type' => $type,
            'admin' => $name,
            'rate' => $rate
        ]);
    }

    public function billingRateSettingReport() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        $urls = $registrationModel -> getExportBillingRateSettingInfo($id, $type);

        $curtime = time();
        $filename = $curtime."_collection.xlsx";
        $arr = array();
        $arr[0] = array(
            'NO', 'User Profile', 'Billing Profile ID', 'Source URL', 'Description', 'Item ID', 'Billing Currency',
            'Budget', 'Default Rate Type', 'Rate Per Click'
        );
        for($i = 0; $i < count($urls); $i ++) {
            $arr[$i + 1] = array(
                $i + 1, $urls[$i] -> username, $urls[$i] -> billing_profile_id, $urls[$i] -> source, $urls[$i] -> hint,
                $urls[$i] -> item_id, $urls[$i] -> currency, $urls[$i] -> budget, $urls[$i] -> rate_type,
                $urls[$i] -> rate_per_click
            );
        }
        Log::info($filename);
        return Excel::download(new InvoicesExport($arr), $filename);
    }

    public function editBillingRateSetting(Request $request) {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $id = $request->id;
        $rate_type = $request->rate_type;
        $click_cut = $request->click_cut;
        $custom_budget = $request -> custom_budget;

        if($rate_type=="undefined" && $click_cut=="undefined")
            return 1;
        else{
            $record = BillingRate::where('id',$id)->first();
            if ($custom_budget != "undefined") {
                $record->budget = $custom_budget;
            }
            if($rate_type!="undefined"){
                $type = DB::table('t_rate')->where('rate_type',$rate_type)->first()->id;
                $record->rate_type = $type;
            }
            if($click_cut!="undefined"){
                $record->click_cut = $click_cut;
            }
            $record->save();
        }
        return 1;
    }

    public function budgetSetting() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        return view('payment/budget_setting', [
            'type' => $type,
            'admin' => $name,
        ]);
    }

    public function saveBudgetType(){
        $store_id = request('store_id');
        $type = request('type');
        $is_set = false;
        try{
            DB::table("t_store_")->where("id", $store_id)
                ->update([
                    'budget_type'  => $type
                ]);
            $is_set = true;
        } catch (\Exception $e){

        }
        return response()->json(['result'=>true,'is_set'=>$is_set]);
    }

    public function defaultRate() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);

        $rate = DB::table("t_rate")->select("rate_type", "rate_name")->get();

        return view('payment/default_rate', [
            'type' => $type,
            'admin' => $name,
            'rate' => $rate
        ]);
    }

    public function defaultRateReport() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        $urls = $registrationModel -> getExportRateInfo($id, $type);

        $curtime = time();
        $filename = $curtime."_collection.xlsx";
        $arr = array();
        $arr[0] = array(
            'NO', 'Rate Type', 'Rate Name', 'Description', 'Country', 'Currency', 'Rate per Click', 'Monthly Threshold'
        );
        for($i = 0; $i < count($urls); $i ++) {
            $arr[$i + 1] = array(
                $i + 1, $urls[$i] -> rate_type, $urls[$i] -> rate_name, $urls[$i] -> description, $urls[$i] -> country,
                $urls[$i] -> currency, $urls[$i] -> rate_per_click, $urls[$i] -> monthly_threshold
            );
        }
        Log::info($filename);
        return Excel::download(new InvoicesExport($arr), $filename);
    }

    public function invoice() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        return view('payment/invoice', [
            'type' => $type,
            'admin' => $name,
        ]);
    }

    public function forex() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        return view('payment/forex', [
            'type' => $type,
            'admin' => $name,
        ]);
    }

    public function invoice_pdf(){
        $id = request('id');
        $where = " where t_transaction.ID = ".$id;
        $result = DB::select(
            "SELECT
            t_transaction.*, t_billing.*, t_user.username, t_user.company, t_rate.*
            FROM t_transaction
            INNER JOIN t_user ON t_transaction.user_id = t_user.id
            INNER JOIN t_billing ON t_user.username = t_billing.profile_name
            INNER JOIN t_rate ON t_billing.rate_type = t_rate.id
        ".$where);
        $one = end($result);

        $recent_records = DB::select(
            "SELECT tt.ID
            FROM t_transaction tt
            INNER JOIN
                (SELECT user_id, MAX(income_date) AS MaxDateTime
                FROM t_transaction
                GROUP BY user_id) groupedtt 
            ON tt.user_id = groupedtt.user_id 
            AND tt.income_date = groupedtt.MaxDateTime"
        );
        $pay_list = array();
        foreach ($recent_records as $value)
            array_push($pay_list, $value->ID);

        $compare = 1;
        if(in_array($one->ID,$pay_list)){
            $cal = new DateTime($one->income_date);
            $interval = new DateInterval('P'.$one->frequency.'D');
            $cal->add($interval);
            $now = new DateTime();
            $compare = $cal>$now?1:0;
        }
        $status = $compare==0?'Unpaid':'Paid';

        $store_id = DB::table('t_store_') -> where('user_id', $one -> user_id) -> get();

        $click_list = array();

        $click_count = 0;
        $total_click_cut = 0;
        if (count($store_id) > 0){
            foreach ($store_id as $store){
                $clicks = DB::table('t_click')
                    -> select("t_click.*", "t_store_.click_cut", "t_store_.hint")
                    -> leftJoin("t_store_", "t_store_.id", "t_click.store_id")
                    -> where('store_id', $store -> id) -> get();
                $item_id = $store -> item_id;
                $click_cut = $store -> click_cut;
                $description = $store -> hint;
                $click_count += count($clicks);
                $total_click_cut += $click_cut * count($clicks);
//                if (count($clicks) != 0) {
                array_push($click_list, array('item_id' => $item_id,
                    'click_cut' => $click_cut,
                    'description' => $description,
                    'click_count' => count($clicks),
                    'total_cut' => $click_cut * count($clicks)));
//                }
            }
        }

        $invoice_number = "INV - ";
        $country_code = strtoupper(substr($one -> country, 0, 3));
        $invoice_number .= $country_code;
        $invoice_number .= " - ";
        $invoice_number .= strval(date('Y'));
        $invoice_number .= " - ";
        $invoice_number .= strval(mt_rand(100000, 999999));
        //pdf data
        $data = array();
        $data = ['user_profile' => $one -> profile_name,
            'billing_id' => $one -> billing_profile_id,
            'account_id' => $one -> account_id,
            'country_code' => $country_code,
            'state' => $one -> state,
            'item_id' => $item_id,
            'currency' => $one -> currency,
            'suburb' => $one -> suburb,
            'address' => $one -> address,
            'company' => $one -> company,
            'click_count' => $click_count,
            'total_click_cut' => $total_click_cut,
            'click_detail_list' => $click_list,
            'invoice_number' => $invoice_number,
            'invoice_month' => date("F Y", strtotime($one -> income_date)),
            'invoice_value' => $one -> monthly_threshold,
            'payment_method' => $one -> payment_method,
            'payment_date' => date("d.m.Y", strtotime($one -> income_date)),
            'finish_date' => date('t.m.Y', strtotime($one -> income_date)),
            'status' => $status,
            'receipt' => $one -> invoice
            ];

        $filename = "invoice".date('Ymd_His').".pdf";
        set_time_limit(200);
        $pdf = PDF::loadView('invoice_pdf',compact('data'));
        Storage::disk('pdf_storage')->put('pdf/'.$filename,$pdf->output());
        return response()->json(['result'=>true,'url'=>asset("pdf/".$filename)]);
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
        $id = session() -> get(SESS_UID);
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);

        $registrationModel = new RegistrationModel();
        $user_list = $registrationModel -> getTokenUserList($id);
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
        $suburb = request('suburb');
        $address = request('address');
        $phone = request('phone');
        $bpId = request('bp_id');
        $billingFrequency = request('billing_frequency');
        $rateType = request('rate_type');
        $date = date("Y.m.d");
        $countryCode = request('countryCode');
        if(strpos($phone,"+") !== false)
            $fullphone = $phone;
        else
            $fullphone = "+".$countryCode." ".$phone;

        $registrationModel = new RegistrationModel();

        $registrationModel -> createNewBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
            $state, $fullphone, $bpId, $billingFrequency, $date, $rateType, $suburb, $address);

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
        $suburb = request('suburb');
        $address = request('address');
        $bpId = request('bp_id');
        $billingFrequency = request('billing_frequency');
        $rateType = request('rate_type');
        $date = date("Y.m.d");
        $countryCode = request('countryCode');
        if(strpos($phone,"+") !== false)
            $fullphone = $phone;
        else
            $fullphone = "+".$countryCode." ".$phone;

        $registrationModel = new RegistrationModel();

        $registrationModel -> updateBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
            $state, $fullphone, $bpId, $billingFrequency, $date, $rateType, $suburb, $address);

        $status = 0;

        return redirect('payment');
    }

    public function editBilling(){
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $info_id = request('url_id');

        $registrationModel = new RegistrationModel();

        $info = $registrationModel -> getBillingInfo($info_id);
        $user_list = $registrationModel -> getTokenUserList($id);
        return view("payment/create_billing", [
            'type' => $type,
            'admin' => $name,
            'users' => $user_list,
            'info' => $info
        ]);
    }

    public function deleteBilling() {
        $store_id = request('store_id');
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

    public function activeBudgetSetting(){
        $store_id = request('store_id');
        $active = request('active');
        Log::debug($store_id);
        Log::debug($active);
        $registrationModel = new RegistrationModel();
        $registrationModel -> activeBudgetSetting($store_id, $active);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function deleteBudgetSetting(){
        $store_id = request('store_id');
        $registrationModel = new RegistrationModel();
        $registrationModel -> deleteBudgetSetting($store_id);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function createDefaultRate() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);

        $ratetype = request('ratetype');
        $ratename = request('ratename');
        $description = request('description');
        $country = request('country');
        $currency = request('currency');
        $rateperclick = request('rateperclick');
        $monthlythreshold = request('monthlythreshold');
        $rate_id = request('rate_id');

        $registrationModel = new RegistrationModel();
        $registrationModel -> createNewRate($ratetype, $ratename, $description, $country, $currency, $rateperclick, $monthlythreshold,$rate_id);

        return redirect('payment/default_rate');
    }

    public function activeDefaultRate(){
        $store_id = request('store_id');
        $active = request('active');
        $registrationModel = new RegistrationModel();
        $registrationModel -> activeRate($store_id, $active);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function deleteDefaultRate(){
        $store_id = request('store_id');
        $registrationModel = new RegistrationModel();
        $registrationModel -> deleteRate($store_id);
        return response()->json([
            'status' => 'ok'
        ]);
    }
}