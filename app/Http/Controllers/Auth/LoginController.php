<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\InvoicesExport;
use App\Model\RegistrationModel;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailSender;
use PhpParser\Node\Expr\Cast\Object_;
use Maatwebsite\Excel\Facades\Excel;
use DateInterval;
use \DateTime;
use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    const api_key = '60b58720-2525-4180-80a4-d50341b1f7f8';
    const api_secret = '62670b10-28f9-403e-9f8b-bf11ed3ce84b';
    const API_URL = 'https://cdn.capture.techulus.in/';
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showLoginPage() {
        return view('login');
    }



    public function signIn() {

        $username = request('username');
        $password = request('password');
        $registrationModel = new RegistrationModel();
        $user = $registrationModel -> getUserFromName($username);
        $password_db = "";
        if($user != null) $password_db = $user-> password;
        if($password == $password_db) {
            session()->put(SESS_UID,        $user->id);
            session()->put(SESS_USERNAME,   $user->username);
            session()->put(SESS_USERTYPE,   $user->type);

            if ($user->type == "Admin"){

            }

            $current_budget = $registrationModel -> getTransactionData($user->id);
            if ($current_budget < 0) {
                $registrationModel->updateUrlStatus($user->id);
            }
            return redirect('/home');
        }
        else {

            return view('login', [
                'error' => 'Invalid Password'
            ]);
        }
    }

    public function signInExtension() {

        $username = request('username');
        $password = request('password');
        Log::info($username);
        $registrationModel = new RegistrationModel();
        $user = $registrationModel -> getUserFromName($username);
        $password_db = "";
        if($user != null) $password_db = $user-> password;
        if($password == $password_db) {
            return json_encode([
                'status' => true,
                'user_id' => $user->id,
                'msg' => "Successfully"
            ]);
        }
        else {
            return json_encode([
                'status' => false,
                'msg' => "User Does not exist"
            ]);

        }
    }

    public function showPayment() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();
        $status = 0;
        $payments = $registrationModel -> getPaymentStatus($id);
        if($payments != null) {
            $date = strtotime($payments -> date);
            $cur = strtotime("today");
            $diff_date = (int)abs((strtotime($cur) - strtotime($date))/(60*60*24));
            if($diff_date < 30) {
                if($payments -> payment == 1) {
                    $status = 1;
                } else {
                    $status = 2;
                }
            }
        }
        $click_list = $registrationModel -> getClickCount($id);
        $admin_management = $registrationModel -> getAllRedirects();
        $month_amount = $registrationModel -> getMonthlyMaximum($id);
        Log::info("Success");
        return view('payment/payment', [
            'admin' => $name,
            'type' => $type,
            'status' => $status,
            'click' => $click_list,
            'monthly' => $month_amount,
            'control' => $admin_management
            ]);
    }

    public function showAdminGraph() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();
        $status = 0;
        $payments = $registrationModel -> getPaymentStatus($id);
        if($payments != null) {
            $date = strtotime($payments -> date);
            $cur = strtotime("today");
            $diff_date = (int)abs((strtotime($cur) - strtotime($date))/(60*60*24));
            if($diff_date < 30) {
                if($payments -> payment == 1) {
                    $status = 1;
                } else {
                    $status = 2;
                }
            }
        }
        $click_list = $registrationModel -> getAdminClickCount();
        $admin_management = $registrationModel -> getAllRedirects();
        $month_amount = $registrationModel -> getMonthlyMaximum($id);
        Log::info("Success");
        return view('payment/admin_graph', [
            'admin' => $name,
            'type' => $type,
            'status' => $status,
            'click' => $click_list,
            'monthly' => $month_amount,
            'control' => $admin_management
        ]);
    }

    public function descriptionShow($hint){
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        $click_list = $registrationModel -> getDescriptionClicks($hint);
        $count = $registrationModel ->getDescriptionClickCount($hint);
        $budget_type = $registrationModel -> getBudgetType($hint);
        return view('description', [
            'admin' => $name,
            'type' => $type,
            'hint' => $hint,
            'click' => $click_list,
            'count' => $count,
            'budget_type' => $budget_type
        ]);
    }

    public function showNewBillingSetup() {
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();
        $status = 0;
        $payments = $registrationModel -> getPaymentStatus($id);
        if($payments != null) {
            $date = strtotime($payments -> date);
            $cur = strtotime("today");
            $diff_date = (int)abs((strtotime($cur) - strtotime($date))/(60*60*24));
            if($diff_date < 30) {
                if($payments -> payment == 1) {
                    $status = 1;
                } else {
                    $status = 2;
                }
            }
        }
        $click_list = $registrationModel -> getClickCount($id);
        $month_amount = $registrationModel -> getMonthlyMaximum($id);
        $payment_setting = $registrationModel -> getPaymentSetting($id);
        if ($month_amount != null){
            return view('/payment/new_billing_setup', [
                'admin' => $name,
                'type' => $type,
                'status' => $status,
                'click' => $click_list,
                'monthly' => $month_amount,
                'setting' => $payment_setting
            ]);
        }
        Log::info("Success");
    }

    public function saveBudget(){
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $budget = request('editBudget');
        $hint = request('urlName');
        Log::debug('hello');
        Log::debug($hint);
        $registrationModel = new RegistrationModel();

        $registrationModel -> updateBudget($budget, $hint);

        $status = 0;
        $payments = $registrationModel -> getPaymentStatus($id);
        if($payments != null) {
            $date = strtotime($payments -> date);
            $cur = strtotime("today");
            $diff_date = (int)abs((strtotime($cur) - strtotime($date))/(60*60*24));
            if($diff_date < 30) {
                if($payments -> payment == 1) {
                    $status = 1;
                } else {
                    $status = 2;
                }
            }
        }
        $click_list = $registrationModel -> getClickCount($id);
        $admin_management = $registrationModel -> getAllRedirects();
        $month_amount = $registrationModel -> getMonthlyMaximum($id);
        Log::info("Success");
        return view('payment/budget_setting', [
            'type' => $type,
            'admin' => $name,
        ]);
    }


    public function saveCut(Request $request){
        $name = session() -> get(SESS_USERNAME);
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $user_id = $request -> get("id");
        $cut = $request -> get("value");
        $registrationModel = new RegistrationModel();

        $registrationModel -> updateCut($user_id, $cut);

        $status = 0;
        $payments = $registrationModel -> getPaymentStatus($id);
        if($payments != null) {
            $date = strtotime($payments -> date);
            $cur = strtotime("today");
            $diff_date = (int)abs((strtotime($cur) - strtotime($date))/(60*60*24));
            if($diff_date < 30) {
                if($payments -> payment == 1) {
                    $status = 1;
                } else {
                    $status = 2;
                }
            }
        }
        $click_list = $registrationModel -> getClickCount($id);
        $admin_management = $registrationModel -> getAllRedirects();
        $month_amount = $registrationModel -> getMonthlyMaximum($id);
        Log::info("Success");
        return view('payment/payment', [
            'admin' => $name,
            'type' => $type,
            'status' => $status,
            'click' => $click_list,
            'monthly' => $month_amount,
            'control' => $admin_management
        ]);
    }


    public function singOut()
    {
        session()->forget(SESS_UID);
        session()->forget(SESS_USERNAME);

        return redirect("/login");
    }

    public function showRegistrationPage() {
        return view('registration');
    }

    public function saveRedirect() {
        $source = request('source');
        $hint = request('hint');
        $points = request('points');
        $user_id = request('user_id');

        $registrationModel = new RegistrationModel();

        $url = request('url');
        $url_info = $registrationModel -> getURL($url);
        if($url_info == null) {
            return ;
        }

        $url_info = $url_info[0];
        $url_id = $url_info -> id;
        $redirect_url = request('redirect_url');
        $cur = date('Y-m-d H:i:s');

        //$id = $registrationModel -> createUrl($url_id, $redirect);
        $registrationModel -> saveRedirect($points, $source, $hint, $url_id, $redirect_url, $cur, $user_id);
        return json_encode([
            'status' => true,
            'msg' => "Success save"
        ]);
    }

    public function saveCells() {
        $start_x = request('start_x');
        $start_y = request('start_y');
        $end_x = request('end_x');
        $end_y = request('end_y');
        $registrationModel = new RegistrationModel();
        Log::info($start_x." ".$end_x);
        $url = request('url');
        $url_info = $registrationModel -> getURL($url);
        if($url_info == null) {
            return ;
        }
        Log::info($url_info);
        $url_info = $url_info[0];
        $url_id = $url_info -> id;

        $redirect_url = request('redirect_url');
        $cur = date('Y-m-d H:i:s');

        $registrationModel -> saveCell($start_x, $start_y, $end_x, $end_y, $url_id, $redirect_url, $cur);
    }

    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function click() {
        $store_id = request('store_id');
        $source = request('source');
        Log::info($store_id);
        Log::info($source);
        $registrationModel = new RegistrationModel();
        $cur = date('Y-m-d H:i:s');
        $ip = $this->getRealIpAddr();

        $registrationModel -> clickUrl($store_id, $source, $cur, $ip);
        return json_encode('');
    }

    public function isContain($source, $url) {
        Log::info($source.":".$url);
        if(strpos($url, $source) !== false) {
            Log::info("TRUE");
            return true;
        }
        return false;
    }

    public function getRedirect($url) {
        $registrationModel = new RegistrationModel();
        $url_info = $registrationModel -> getAllURL();


        if($url_info == null) {
            return null;
        }
        $answer = [];
        foreach($url_info as $url_value) {
            $url_id = $url_value -> id;
            if($this->isContain($url_value -> url, $url) == true) {
                $allCell = $registrationModel -> getAllCell($url_id);
                foreach($allCell as $cell) {
                    //$cell -> width = $url_value -> width;
                    $cell -> urlSource = $url_value -> url;
                    array_push($answer, $cell);
//                    if($user->type == 'Admin') {
//                        array_push($answer, $cell);
//                    } else if($cell -> user_id == $user_id) {
//                        array_push($answer, $cell);
//                    } else if($user -> type == 'Manager' && $user_id == $cell -> parent_id) {
//                        array_push($answer, $cell);
//                    }

                }
            }

        }
        return $answer;

    }
    public function getCellInfo() {
        $url = request('url');

        $answer = $this->getRedirect($url);


        if($answer == null) {
            return "";
        }

        return json_encode($answer);
    }

    public function checkURLRedirect() {
        Log::info("Come in Redirect");
        $url = request('url');
        $user_id = request('user_id');
        Log::info($url);
        $registrationModel = new RegistrationModel();
        $user = $registrationModel -> getUserFromId($user_id);
        if($user == null) {
            return json_encode([[
                'status' => false,
                'msg' => "User does not exist"
            ]]);
        }
        $url_info = $registrationModel -> getAllURLInfo($user_id, $user->type, null);
        if($url_info == null) {
            return json_encode([[
                'status' => false,
                'msg' => "Non url exist"
            ]]);
        }
        
        $answer = $this->getRedirect($url);
        foreach($url_info as $url_value) {
            if($this->isContain($url_value -> url, $url) == true) {
                return json_encode([[
                    'status' => true,
                    'data' => $answer,
                    'msg' => "Exist in url list"
                ]]);
            }
        }

        return json_encode([[
            'status' => false,
            'msg' => "Not exist in url list"
        ]]);
    }

    public function editUrl() {
        $url_id = request('url_id');

        $select_manager = request('select_manager');
        $width = request('width');
        $height= request('height');
        $url = request('url');
        $image_path = request('image_path');

        $registrationModel = new RegistrationModel();
        $manager_id = $registrationModel -> getUserId($select_manager);
        $registrationModel -> updateURL($url_id, $url, $manager_id);

        return response()->json([
            'status' => 'ok',
            'msg' => 'Success.'
        ]);
    }

    public function sendEmail(){
        $userid = request('userid');
        $user = $user = DB::table('t_user')
            ->where('id', '=', $userid)
            ->first();
        $email = $user->email;
        $success = false;
        try{
            Mail::to($email)->send(new MailSender("Hello", "This is test email", "thanks"));
            $success = true;
        }catch (\Exception $e){

        }
        return response()->json(["result"=>true,"success"=>$success]);
    }

    public function addUrl() {
        $select_manager = request('select_manager');
        $url = request('url');
        $image_path = '';

        $width = 0;
        $height = 0;

        $registrationModel = new RegistrationModel();
        $url_info = $registrationModel -> getURL($url);
        if($url_info != null) {
            return response()->json([
                'status' => 'fail',
                'msg' => 'URL is alreay exist.'
            ]);
        }

        $manager_id = $registrationModel -> getUserId($select_manager);
        $url_id = $registrationModel -> addURL($url, $manager_id, $image_path, $width, $height);
        return response()->json([
            'status' => 'ok',
            'msg' => 'Success.'
        ]);
    }

    public function deleteUrl() {
        $url_id = request('url_id');
        $registrationModel = new RegistrationModel();
        $registrationModel -> deleteUrl($url_id);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function deleteRedirectUrl() {
        $store_id = request('store_id');
        $registrationModel = new RegistrationModel();
        $registrationModel -> deleteRedirectUrl($store_id);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function activeRedirectUrl() {
        $store_id = request('store_id');
        $active = request('active');
        $registrationModel = new RegistrationModel();
        $registrationModel -> activeRedirectUrl($store_id, $active);
        return response()->json([
            'status' => 'ok'
        ]);
    }



    public function getUrlInfo() {
        $url_id = request('url_id');
        Log::info($url_id);
        $registrationModel = new RegistrationModel();
        $url = $registrationModel->getURLInforById($url_id);

        if ($url == null) {
            return response()->json([
                'status' => 'fail',
                'msg' => 'URL not found.'
            ]);
        }


        return response()->json([
            'status' => 'ok',
            'data' => $url
        ]);
    }

    public function getImageAPI($url) {
        //$url = "http://".$url;
        //var input_url = encodeURIComponent(url);
        $options = 'full=true&delay=3';
        $full_url = $url.'&'.$options;
        $hash = md5(self::api_secret.'url='.$full_url);
        $result_img_url = self::API_URL.self::api_key.'/'.$hash.'/image?url='.$full_url;
        return $result_img_url;
    }

    public function getCorrectImageAPI($url, $width, $height) {
        //$url = "http://".$url;
        //var input_url = encodeURIComponent(url);
        $options = 'full=true&vw='.$width.'&vh='.$height;
        $full_url = $url.'&'.$options;
        $hash = md5(self::api_secret.'url='.$full_url);
        $result_img_url = self::API_URL.self::api_key.'/'.$hash.'/image?url='.$full_url;
        return $result_img_url;
    }

    public function getImage() {
        set_time_limit(0);
        $source_url = request('url');
        $url = $this->getImageAPI($source_url);
        $user_id = request('user_id');
        $storage_path = config("filesystems.disks.image_storage");


        if (!file_exists($storage_path))
            mkdir($storage_path, 0777, true);
        $registrationModel = new RegistrationModel();
        $registrationModel -> captureImage($user_id);
        $image_path = time() . ".jpg";
        $img = $storage_path.$image_path;
        Log::info($img);
        file_put_contents($img, file_get_contents($url));
        $size = getimagesize($img);
        $width = $size[0];
        $height = $size[1];
        Log::info($width);
        Log::info($height);
        $url = $this->getCorrectImageAPI($source_url, $width, $height);
        Log::info($url);
        $image_path = time() . ".jpg";
        $img = $storage_path.$image_path;
        Log::info($img);
        file_put_contents($img, file_get_contents($url));

        return response()->json([
            'status' => 'ok',
            'image_path' => $image_path
        ]);
    }

    public function showHome() {

        /*if($name != 'admin') {
            return view('home', [
                'admin'  => $name,
                'urls' => $urls
            ]);
        }
        else */return $this -> showSetting();
    }

    public function showSetting() {
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        if($type == 'Admin') {
            $users = $registrationModel -> getAllManager();
        } else {
            $users = $registrationModel -> getChildUsers($id);
        }
        Log::info("Success");
        return view('setting', [
            'admin' => $name,
            'type' => $type,
            'managers' => $users
        ]);
    }

    public function getAllUrls(Request $request) {
        $params = $this->getDataTableParams($request);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        $total = $registrationModel -> getAllURLInfoCount($id, $type, $params);
        $urls = $registrationModel -> getAllURLInfo($id, $type, $params);
        $result = $this->dataTableFormat($urls, $total);
        return response()->json($result);
    }

    public function getAllRates(Request $request) {
        $params = $this->getDataTableParams($request);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        $total = $registrationModel -> getAllRateInfoCount($id, $type, $params);
        $urls = $registrationModel -> getAllRateInfo($id, $type, $params);
        $result = $this->dataTableFormat($urls, $total);
        return response()->json($result);
    }

    public function getAllRateType(){
        $registrationModel = new RegistrationModel();
        $rate_type = $registrationModel -> getAllRateType();

        return $rate_type;
    }

    public function exportURlCollection() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();
        $urls = $registrationModel -> getAllURLInfo($id, $type, null);
        $curtime = time();
        $filename = $curtime."_collection.xlsx";
        $arr = array();
        $arr[0] = array(
            'NO', 'URL Path', 'User Profile'
        );
        for($i = 0; $i < count($urls); $i ++) {
            $arr[$i + 1] = array(
                $i + 1, $urls[$i] -> url, $urls[$i] -> username
            );
        }
        Log::info($filename);
        return Excel::download(new InvoicesExport($arr), $filename);
//        return response()->json([
//            'status' => 'ok',
//            'msg' => 'Success.'
//        ]);

    }

    public function showCount() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $registrationModel = new RegistrationModel();
        return view('count', [
            'type' => $type,
            'admin' => $name,
        ]);
    }

    public function getAllRedirectURLInfo(Request $request) {
        $params = $this->getDataTableParams($request);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        $total = $registrationModel -> getAllRedirectURLInfoCount($id, $type, $params);
        $urls = $registrationModel -> getAllRedirectURLInfo($id, $type, $params);
        $result = $this->dataTableFormat($urls, $total);
        return response()->json($result);
    }

    public function getAllBillingInfo(Request $request) {
        $params = $this->getDataTableParams($request);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        $total = $registrationModel -> getAllBillingInfoCount($id, $type, $params);
        $urls = $registrationModel -> getAllBillingInfo($id, $type, $params);
        $result = $this->dataTableFormat($urls, $total);
        return response()->json($result);
    }

    public function getAllInvoice(Request $request) {
        $params = $this->getDataTableParams($request);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        $total = $registrationModel -> getAllInvoiceCount($id, $type, $params);
        $urls = $registrationModel -> getAllInvoice($id, $type, $params);
        $result = $this->dataTableFormat($urls, $total);
        return response()->json($result);
    }

    public function getInvoice(Request $request){
        $user_profile = $request->user_profile;
        $bill_id = $request->bill_id;
        $invoice_name = $request->invoice_name;
        $start = $request->start;
        $end = $request->end;

        $registerModel = new RegistrationModel();
        $where = "";
        if ($user_profile != null){
            $where = "username like '%".$user_profile."%' ";
        }
        if($bill_id != null){
            $clause = "billing_profile_id like '%".$bill_id."%' ";
            $where = $registerModel->prepareAnd($where,$clause);
        }
        if($invoice_name != null){
            $clause = "invoice like '%".$invoice_name."%' ";
            $where = $registerModel->prepareAnd($where,$clause);
        }
        if($start != null){
            $array = explode('/', $start);

            $from_date = $array[2].'-'.trim($array[1]).'-'.trim($array[0]);
            $array = explode('/', $end);
            $to_date = $array[2].'-'.trim($array[1]).'-'.trim($array[0]);
            $cal = new DateTime($to_date);
            $interval = new DateInterval('P1D');
            $cal->add($interval);
            $to_date = $cal->format('Y-m-d');
            $clause = "income_date BETWEEN '".$from_date."' AND '".$to_date."'";
            $where = $registerModel->prepareAnd($where,$clause);
        }
        $where = $registerModel->where($where);
        $data = DB::select(
            "SELECT
            t_transaction.*, t_billing.*, t_user.username, t_rate.*
            FROM t_transaction
            INNER JOIN t_user ON t_transaction.user_id = t_user.id
            INNER JOIN t_billing ON t_user.username = t_billing.profile_name
            INNER JOIN t_rate ON t_billing.rate_type = t_rate.id
        ".$where);

        //get latest records for each user
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
        foreach ($recent_records as $one)
            array_push($pay_list, $one->ID);

        //make datatable data
        $label = ['index','User Profile','Account','Billing Currency','Invoice Month','Invoice Value','Payment Method','Payment Date','Payment Due Date','Pay','Receipt','Statement'];
        $result = array();
        $result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 1;
        $result['recordsTotal'] = count($data);
        $result['recordsFiltered'] = count($data);
        $result['data'] = array();
        $num = 1;
        $currency = array("AUD"=>"$","USD"=>"$","EUR"=>"€","NZD"=>"$","CNY"=>"¥","CAD"=>"$","GBP"=>"£","JPY"=>"¥");
        foreach ($data as $one){
            foreach ($label as $index => $item){
                //check payment status
                $compare = 1;
                if(in_array($one->ID,$pay_list)){
                    $cal = new DateTime($one->income_date);
                    $interval = new DateInterval('P'.$one->frequency.'D');
                    $cal->add($interval);
                    $now = new DateTime();
                    $compare = $cal>$now?1:0;
                }
                //check payment method
                if($one->payment_method == "PayPal"){
                    $pp = 1;
                }
                else{
                    $pp = 0;
                }
                //input data
                if ($item == 'index')
                    $obj[$index] = $num;
                elseif ($item == 'User Profile')
                    $obj[$index] = $one->profile_name;
                elseif ($item == 'Account')
                    $obj[$index] = $one->account_id;
                elseif ($item == 'Billing Currency')
                    $obj[$index] = $one->currency;
                elseif ($item == 'Invoice Month')
                    $obj[$index] = date("F Y",strtotime($one->income_date));
                elseif ($item == 'Invoice Value')
                    $obj[$index] = $currency[$one->currency].number_format($one->monthly_threshold);
                elseif ($item == 'Payment Method')
                    $obj[$index] = $one->payment_method;
                elseif ($item == 'Payment Date')
                    $obj[$index] = date("d.m.Y",strtotime($one->income_date));
                elseif ($item == 'Payment Due Date'){
                    $date = date('Y-m-d', strtotime('+1 month', strtotime($one->income_date)));
                    $obj[$index] = date("01/m/Y",strtotime($date));
                }
                elseif ($item == 'Pay'){
                    if($compare==0)
                        $obj[$index] = "<button type='button' class='btn btn-primary' onclick='payInvoice(".$one->ID.",".$pp.")'>PAY</button>";
                    else
                        $obj[$index] = "<button type='button' class='btn btn-primary'>PAID</button>";
                }
                elseif ($item == 'Receipt'){
                    if($compare==0)
                        $obj[$index] = "";
                    else
                        $obj[$index] = $one->invoice;
                }
                elseif ($item == 'Statement')
                    $obj[$index] = "<a onclick='generatePDF(".$one->ID.")' style='text-decoration: underline !important;'>".date("F Y",strtotime($one->income_date))."</a>";
            }
            $result['data'][] = $obj;
            $num++;
        }
        return response()->json($result);
    }

    public function getBudgetSetting(Request $request){
        $params = $this->getDataTableParams($request);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $user_profile = $request->user_profile;
        $bill_id = $request->bill_id;
        $item_id = $request->item_id;

        $registrationModel = new RegistrationModel();
        $total = $registrationModel -> getBudgetSettingCount($id, $type, $params, $user_profile, $bill_id, $item_id);
        $urls = $registrationModel -> getBudgetSetting($id, $type, $params, $user_profile, $bill_id, $item_id);
        $result = $this->dataTableFormat($urls, $total);
        return response()->json($result);

    }

    public function getBillingRateSetting(Request $request) {
        $params = $this->getDataTableParams($request);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        $total = $registrationModel -> getBillingRateSettingCount($id, $type, $params);
        $urls = $registrationModel -> getBillingRateSetting($id, $type, $params);
        $result = $this->dataTableFormat($urls, $total);
        return response()->json($result);
    }

    public function exportRedirectURL() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $registrationModel = new RegistrationModel();
        $urls = $registrationModel -> getAllRedirectURLInfo($id, $type, null);
        $curtime = time();
        $filename = $curtime."_redirect.xlsx";
        $arr = array();
        $arr[0] = array(
            'NO', 'URL Profile', 'Source URL', 'Image URL', 'Redirect URL', 'Hint', 'Created Date'
        );
        for($i = 0; $i < count($urls); $i ++) {
            $arr[$i + 1] = array(
                $i + 1, $urls[$i] -> username, $urls[$i] -> url, $urls[$i] -> source, $urls[$i] -> redirect_url, $urls[$i] -> hint, $urls[$i] -> create_date
            );
        }
        Log::info($filename);
        return Excel::download(new InvoicesExport($arr), $filename);
    }

    public function showDetailClickInfo() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $store_id = request('store_id');
        $registrationModel = new RegistrationModel();
        $urls = $registrationModel -> getClickDetail($store_id, $id, $type);
        return view('detail', [
            'type' => $type,
            'admin' => $name,
            'urls'  => $urls,
            'store_id' => $store_id
        ]);
    }

    public function reportClickInfo() {
        $id = session() -> get(SESS_UID);
        $type = session() -> get(SESS_USERTYPE);
        $name = session() -> get(SESS_USERNAME);
        $store_id = request('store_id');
        $registrationModel = new RegistrationModel();
        $urls =  $registrationModel -> getClickDetail($store_id);
        $curtime = time();
        $filename = $curtime."_click.xlsx";
        $arr = array();
        $arr[0] = array(
            'NO', 'Source', 'IP Address', 'Redirect', 'Click Date/Time'
        );
        for($i = 0; $i < count($urls); $i ++) {
            $arr[$i + 1] = array(
                $i + 1, $urls[$i] -> source, $urls[$i] -> source_ip, $urls[$i] -> redirect_url, $urls[$i] -> click_time
            );
        }
        Log::info($filename);
        return Excel::download(new InvoicesExport($arr), $filename);
    }


    public function createUser() {
        $username = request('username');
        $password = request('password');
        $repeatpassword = request('repeat_password');
        if($password != $repeatpassword || $password == "") {
            $string = "Input Correct Password";
            return response()->json($this->configFailArray($string));
        }
        else if($username == "") {
            $string = "Input Username";
            return response()->json($this->configFailArray($string));
        }
        $registrationModel = new RegistrationModel();
        $id = $registrationModel -> getIdFromName($username);
        if($id > 0) {
            Log::info("fasfa");
            $string = "Duplicated User";
            return response()->json($this->configFailArray($string));
        }
        $user_id = $registrationModel -> createUser($username, $password);
        $registrationModel -> $this->makeCapture($user_id);
        $string = "Created User";
        return response()->json($this->configSuccessArray($string));
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showChild() {
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);
        $registrationModel = new RegistrationModel();
        if($type == 'Admin') {
            $users = $registrationModel -> getAllManager();
        } else {
            $users = $registrationModel -> getChildUsers($id);
        }
        return view('child', [
            'users'  => $users,
            'type' => $type,
            'admin' => $name
        ]);
    }

    public function updateChild() {
        $user_id = request('user_id');
        $name = request('username');
        $password = request('password');
        $email = request('email');
        $company = request('company');
        $type = request('select_type');
        $registrationModel = new RegistrationModel();

        $userInfo = $registrationModel -> getUserFromId($user_id);
        $parent_id = $userInfo -> parent_id;
        if($type != $userInfo -> type) {
            $parent_id = 1;
        }

        $registrationModel -> updateUser($user_id, $name, $password, $email, $company, $type, $parent_id);

        return response()->json([
            'status' => 'ok',
            'msg' => 'Success.'
        ]);
    }

    public function addChild() {
        $name = request('username');
        $password = request('password');
        $type = request('select_type');
        $email = request('email');
        $company = request('company');
        $user_id = session() -> get(SESS_UID);

        $registrationModel = new RegistrationModel();

        $id = $registrationModel -> getIdFromName($name);
        if($id > 0) {
            return response()->json([
                'status' => 'fail',
                'msg' => 'Duplicated User name.'
            ]);
        }

        $id = $registrationModel -> createNewUser($name, $password, $email, $company, $type, $user_id);
        return response()->json([
            'status' => 'ok',
            'msg' => 'Success.'
        ]);
    }

    public function editRedirectSetting(){
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);
        $id = session() -> get(SESS_UID);

        Log::debug(request('click_cut'));
    }
}


