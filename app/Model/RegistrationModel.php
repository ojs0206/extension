<?php
/**
 * Created by PhpStorm.
 * User: Pang
 * Date: 6/28/2017
 * Time: 10:21 AM
 */

namespace App\Model;

use App\Transaction;
use DB;
use Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegistrationModel extends BaseModel
{
    public function createUser($username, $password) {
        $id = DB::table('t_user')->insertGetId([
            'username'      => $username,
            'password'      => ($password)
        ]);
        return $id;
    }

    public function createNewUser($username, $password, $email, $company, $type, $parent_id) {
        $id = DB::table('t_user')->insertGetId([
            'username'      => $username,
            'password'      => $password,
            'email'         => $email,
            'type'          => $type,
            'parent_id'     => $parent_id,
            'company'     => $company
        ]);
        return $id;
    }


    public function getAllCell($id) {
        $allCell =  DB::select("SELECT t_store_.*, t_user.parent_id FROM t_store_ LEFT JOIN t_user ON t_store_.user_id = t_user.id WHERE url_id = '$id' AND active = 'Active' ORDER BY id ASC ");
        /*DB::table('t_store')
            ->where('url', '=', $url);*/
        return $allCell;
    }

    public function saveCell($start_x, $start_y, $end_x, $end_y, $url_id, $redirect_url, $cur) {
        $item_id = $this -> generateItemID();
        $item_id = 'AUR' + $item_id;

        $id = DB::table('t_store')->insertGetId([
            'start_x'      => $start_x,
            'start_y'      => $start_y,
            'end_x'      => $end_x,
            'end_y'      => $end_y,
            'url_id'      => $url_id,
            'redirect_url'      => $redirect_url,
            'create_date'   => $cur,
            'item_id'     => $item_id
        ]);
    }

    public function saveRedirect($points, $source, $hint, $url_id, $redirect_url, $cur, $user_id) {
        $item_id = $this -> generateItemID();
        $item_id = 'AUR'.strval($item_id);
        $id = DB::table('t_store_')->insertGetId([
            'points'                => $points,
            'source'                => $source,
            'hint'                  => $hint,
            'url_id'                => $url_id,
            'user_id'               => $user_id,
            'redirect_url'          => $redirect_url,
            'create_date'           => $cur,
            'rate_type'             => 1,
            'item_id'               => $item_id
        ]);
    }
    public function getClasses($user) {
        $classes = DB::select(
            "SELECT
              t_class.id,
              t_class.classname
            FROM t_class_teachers as t0
            LEFT JOIN t_class
            ON t0.class_id = t_class.id
            WHERE t0.teacher_id = '$user->id'
            "
        );
        Log::info($classes);

        return $classes;
    }

    public function getURLInforById($id)
    {
        $url = DB::select(
            "SELECT
                t_url_setting.*, t_user.username
             FROM t_url_setting
             LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id
             WHERE t_url_setting.id = '$id'
            "
        );
        return $url;
    }

    public function getUrlStoreInfo($store_id) {
        return $url = DB::table('t_store_')
            ->where('id', '=', $store_id)
            ->first();
        return $url;
    }

    public function getURL($url) {
        $url_info = DB::select(
            "SELECT
                t_url_setting.*
             FROM t_url_setting
             WHERE INSTR('$url', t_url_setting.url)
            "
        );
        return $url_info;
    }

    public function getAllURL() {
        $url_info = DB::select(
            "SELECT
                t_url_setting.*
             FROM t_url_setting
            "
        );
        return $url_info;
    }

    public function getAllRateType() {
        $rate_type = DB::select(
            "SELECT
                t_rate.*
             FROM t_rate
            "
        );
        return $rate_type;
    }


    public function getClickInfo($store_id) {
        $url_info = DB::select(
            "SELECT
                t_click.*
             FROM t_click
             WHERE t_click.store_id = '$store_id'
            "
        );
        return $url_info;
    }

    public function getUserId($name) {
        $user = DB::table('t_user')
            ->where('username', '=', $name)
            ->first();
        return $user -> id;
    }

    public function getAllURLInfo($id, $type, $params) {
        $clause1 = $this->clause('url', $params);
        $clause2 = $this->clause('username', $params);
        $tmp = $this->prepareOr($clause1, $clause2);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
        if($type == 'Admin') {
            $where = $this->where($tmp);
            $urls = DB::select(
                "SELECT
                t_url_setting.*, t_user.username
             FROM t_url_setting
             LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id 
            ".$where . $orderby . $limit
            );

            return $urls;
        } else if($type == 'Manager') {
            $clause1 = "t_user.id = '$id'";
            $clause2 = "t_user.parent_id = '$id'";
            $tmp1 = $this->prepareOr($clause1, $clause2);
            $tmp2 = $this->prepareAnd($tmp, $tmp1);
            $where = $this->where($tmp2);
            $urls = DB::select(
                "SELECT
            t_url_setting.*, t_user.username
         FROM t_url_setting
         LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id 
        ".$where . $orderby . $limit
            );
            return $urls;
        }
        $clause1 = "t_user.id = '$id'";
        $tmp2 = $this->prepareAnd($tmp, $clause1);
        $where = $this->where($tmp2);
        $urls = DB::select(
            "SELECT
            t_url_setting.*, t_user.username
         FROM t_url_setting
         LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id
         
        ".$where . $orderby . $limit
        );
        return $urls;
    }

    public function getAllURLInfoCount($id, $type, $params) {
        $clause1 = $this->clause('url', $params);
        $clause2 = $this->clause('username', $params);
        $tmp = $this->prepareOr($clause1, $clause2);
        if($type == 'Admin') {
            $where = $this->where($tmp);
            $urls = DB::select(
                "SELECT COUNT(t_url_setting.id) AS total
             FROM t_url_setting
             LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id 
            ".$where
            );

            return $urls[0]->total;
        } else if($type == 'Manager') {
            $clause1 = "t_user.id = '$id'";
            $clause2 = "t_user.parent_id = '$id'";
            $tmp1 = $this->prepareOr($clause1, $clause2);
            $tmp2 = $this->prepareAnd($tmp, $tmp1);
            $where = $this->where($tmp2);
            $urls = DB::select(
                "SELECT COUNT(t_url_setting.id) AS total
         FROM t_url_setting
         LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id 
        ".$where
            );
            return $urls[0]->total;
        }
        $clause1 = "t_user.id = '$id'";
        $tmp2 = $this->prepareAnd($tmp, $clause1);
        $where = $this->where($tmp2);
        $urls = DB::select(
            "SELECT COUNT(t_url_setting.id) AS total
         FROM t_url_setting
         LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id
         
        ".$where
        );
        return $urls[0]->total;
    }

    public function getAllRateInfo($id, $type, $params) {
        $clause1 = $this->clause('rate_type', $params);
        $clause2 = $this->clause('username', $params);
        $tmp = $this->prepareOr($clause1, $clause2);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
//        if($type == 'Admin') {
//            $where = $this->where($tmp);
//            $urls = DB::select(
//                "SELECT
//                    t_rate.*
//                 FROM t_rate
//                ".$where . $orderby . $limit
//            );
//
//            return $urls;
//        } else if($type == 'Manager') {
//            $clause1 = "t_user.id = '$id'";
//            $clause2 = "t_user.parent_id = '$id'";
//            $tmp1 = $this->prepareOr($clause1, $clause2);
//            $tmp2 = $this->prepareAnd($tmp, $tmp1);
//            $where = $this->where($tmp2);
//            $urls = DB::select(
//                "SELECT
//                    t_rate.*
//                 FROM t_rate
//                ".$where . $orderby . $limit
//            );
//            return $urls;
//        }
        $clause1 = "t_user.id = '$id'";
        $tmp2 = $this->prepareAnd($tmp, $clause1);
        $where = $this->where($tmp2);
        $urls = DB::select(
            "SELECT
                t_rate.*
             FROM t_rate
            "
        );
        $currency = array("AUD"=>"$","USD"=>"$","EUR"=>"€","NZD"=>"$","CNY"=>"¥","CAD"=>"$","GBP"=>"£","JPY"=>"¥");
        foreach ($urls as $url){
            $url->rate_per_click_symbol = $currency[$url->currency].$url->rate_per_click;
            $url->monthly_threshold_symbol = $currency[$url->currency].number_format($url->monthly_threshold);
        }

        return $urls;
    }

    public function getAllRateInfoCount($id, $type, $params) {
        $clause1 = $this->clause('url', $params);
        $clause2 = $this->clause('username', $params);
        $tmp = $this->prepareOr($clause1, $clause2);
        if($type == 'Admin') {
            $where = $this->where($tmp);
            $urls = DB::select(
                "SELECT COUNT(t_rate.id) AS total
                 FROM t_rate
                ".$where
            );

            return $urls[0]->total;
        } else if($type == 'Manager') {
            $clause1 = "t_user.id = '$id'";
            $clause2 = "t_user.parent_id = '$id'";
            $tmp1 = $this->prepareOr($clause1, $clause2);
            $tmp2 = $this->prepareAnd($tmp, $tmp1);
            $where = $this->where($tmp2);
            $urls = DB::select(
                "SELECT COUNT(t_rate.id) AS total
                 FROM t_rate
                ".$where
            );
            return $urls[0]->total;
        }
        $clause1 = "t_user.id = '$id'";
        $tmp2 = $this->prepareAnd($tmp, $clause1);
        $where = $this->where($tmp2);
        $urls = DB::select(
            "SELECT COUNT(t_rate.id) AS total
             FROM t_rate             
            "
        );
        return $urls[0]->total;
    }

    public function getAllRedirectURLInfo($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        $tmp_1 = $this->clause('redirect_url', $params);
        $tmp_2 = $this->clause('source', $params);
        $tmp_3 = $this->clause('hint', $params);
        $tmp  = $this->prepareOr($tmp, $tmp_1);
        $tmp  = $this->prepareOr($tmp, $tmp_2);
        $tmp  = $this->prepareOr($tmp, $tmp_3);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
        if($type == 'Admin') {
            $where = $this->where($tmp);
            $urls = DB::select(
                "SELECT
                t_store_.*, t_url_setting.url, t_user.username
                FROM t_store_
                LEFT JOIN t_url_setting ON t_store_.url_id = t_url_setting.id
                LEFT JOIN t_user ON t_store_.user_id = t_user.id
            ".$where . $orderby . $limit
            );
            foreach ($urls as $url) {
                $count_urls = DB::table('t_click')->select('id')->where('store_id', $url->id)->get();
                $url->count = sizeof($count_urls);
            }
            return $urls;
        }
        if($type == 'Manager') {
            $clause1 = "t_user.id = '$id'";
            $clause2 = "t_user.parent_id = '$id'";
            $tmp1 = $this->prepareOr($clause1, $clause2);
            $tmp2 = $this->prepareAnd($tmp, $tmp1);
            $where = $this->where($tmp2);
            $urls = DB::select(
                "SELECT
                t_store_.*,  t_url_setting.url, t_user.username
                FROM t_store_
                LEFT JOIN t_url_setting ON t_store_.url_id = t_url_setting.id
                LEFT JOIN t_user ON t_store_.user_id = t_user.id
            ".$where . $orderby . $limit
            );
            foreach ($urls as $url) {
                $count_urls = DB::table('t_click')->select('id')->where('store_id', $url->id)->get();
                $url->count = sizeof($count_urls);
            }
            return $urls;
        }
        $clause1 = "t_user.id = '$id'";
        $tmp2 = $this->prepareAnd($tmp, $clause1);
        $where = $this->where($tmp2);
        $urls = DB::select(
            "SELECT
                t_store_.*, t_url_setting.url, t_user.username
                FROM t_store_
                LEFT JOIN t_url_setting ON t_store_.url_id = t_url_setting.id
                LEFT JOIN t_user ON t_store_.user_id = t_user.id
            ".$where . $orderby . $limit
        );
        foreach ($urls as $url) {
            $count_urls = DB::table('t_click')->select('id')->where('store_id', $url->id)->get();
            $url->count = sizeof($count_urls);
        }
        return $urls;
    }

    public function getAllRedirectURLInfoCount($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        if($type == 'Admin') {
            $where = $this->where($tmp);
            $urls = DB::select(
                "SELECT COUNT(t_store_.id) AS total
                FROM t_store_
                LEFT JOIN t_url_setting ON t_store_.url_id = t_url_setting.id
                LEFT JOIN t_user ON t_store_.user_id = t_user.id
            ".$where
            );
            return $urls[0]->total;
        }
        if($type == 'Manager') {
            $clause1 = "t_user.id = '$id'";
            $clause2 = "t_user.parent_id = '$id'";
            $tmp1 = $this->prepareOr($clause1, $clause2);
            $tmp2 = $this->prepareAnd($tmp, $tmp1);
            $where = $this->where($tmp2);
            $urls = DB::select(
                "SELECT COUNT(t_store_.id) AS total
                FROM t_store_
                LEFT JOIN t_url_setting ON t_store_.url_id = t_url_setting.id
                LEFT JOIN t_user ON t_store_.user_id = t_user.id
            ".$where
            );
            return $urls[0]->total;
        }
        $clause1 = "t_user.id = '$id'";
        $tmp2 = $this->prepareAnd($tmp, $clause1);
        $where = $this->where($tmp2);
        $urls = DB::select(
            "SELECT COUNT(t_store_.id) AS total
                FROM t_store_
                LEFT JOIN t_url_setting ON t_store_.url_id = t_url_setting.id
                LEFT JOIN t_user ON t_store_.user_id = t_user.id
            ".$where
        );
        return $urls[0]->total;
    }

    public function getAllBillingInfo($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT
                t_billing.*, t_rate.rate_type
                FROM t_billing
                LEFT JOIN t_user ON t_billing.profile_name = t_user.username
                LEFT JOIN t_rate ON t_billing.rate_type = t_rate.id
            ".$where . $orderby . $limit
        );
        return $urls;
    }

    public function getAllBillingInfoCount($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT COUNT(t_billing.id) AS total
                FROM t_billing
                LEFT JOIN t_user ON t_billing.profile_name = t_user.username
                LEFT JOIN t_rate ON t_billing.rate_type = t_rate.id
            ".$where
        );
        return $urls[0]->total;
    }

    public function getUserAllBillingInfo($id, $type, $params, $user_list) {
        $tmp = $this->clause('username', $params);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT
                t_billing.*, t_rate.rate_type
                FROM t_billing
                LEFT JOIN t_user ON t_billing.profile_name = t_user.username
                LEFT JOIN t_rate ON t_billing.rate_type = t_rate.id
                WHERE t_user.id IN ($user_list)               
            ".$where . $orderby . $limit
        );
        return $urls;
    }

    public function getUserAllBillingInfoCount($id, $type, $params, $user_list) {
        $tmp = $this->clause('username', $params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT COUNT(t_billing.id) AS total
                FROM t_billing
                LEFT JOIN t_user ON t_billing.profile_name = t_user.username
                LEFT JOIN t_rate ON t_billing.rate_type = t_rate.id
                WHERE t_user.id IN ($user_list)                
            "
        );
        return $urls[0]->total;
    }

    public function getBudgetSetting($id, $type, $params, $user_profile, $bill_id, $item_id){
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
        $tmp = $this->clause('username', $params);
        $where = "";
        if ($user_profile != null){
            $tmp = "username like '%".$user_profile."%' ";
        }
        if($bill_id != null){
            $clause = "billing_profile_id like '%".$bill_id."%' ";
            $tmp = $this->prepareAnd($tmp,$clause);
        }
        if($item_id != null){
            $clause = "item_id like '%".$item_id."%' ";
            $tmp = $this->prepareAnd($tmp,$clause);
        }

        $where = $this->where($tmp);
        $data = DB::select(
            "SELECT
            t_store_.*, t_store_.id as store_id, t_user.username, t_rate.rate_type, t_rate.currency, t_billing.billing_profile_id
            FROM t_store_
            LEFT JOIN t_user ON t_store_.user_id = t_user.id
            LEFT JOIN t_rate ON t_store_.rate_type = t_rate.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
        ".$where);
        return $data;
    }


    public function getBudgetSettingCount($id, $type, $params, $user_profile, $bill_id, $item_id){
        $tmp = $this->clause('username', $params);
        if ($user_profile != null){
            $tmp = "t_user.username = '$user_profile'";
        }
        if($bill_id != null){
            $clause = "t_billing.billing_profile_id = '$bill_id'";
            $tmp = $this->prepareAnd($tmp,$clause);
        }
        if($item_id != null){
            $clause = "t_store_.item_id = '$item_id'";
            $tmp = $this->prepareAnd($tmp, $clause);
        }

        $where = $this->where($tmp);
        $data = DB::select(
            "SELECT COUNT(t_store_.id) AS total
            FROM t_store_
            LEFT JOIN t_user ON t_store_.user_id = t_user.id
            LEFT JOIN t_rate ON t_store_.rate_type = t_rate.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
        ".$where);
        return $data[0] -> total;
    }

    public function getBillingRateSetting($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT
            t_store_.*, t_user.username, t_billing.billing_profile_id, t_rate.*, t_store_.id AS store_id
            FROM t_store_
            LEFT JOIN t_user ON t_store_.user_id = t_user.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
            LEFT JOIN t_rate ON t_store_.rate_type = t_rate.id
        ".$where . $orderby . $limit
        );
        $currency = array("AUD"=>"$","USD"=>"$","EUR"=>"€","NZD"=>"$","CNY"=>"¥","CAD"=>"$","GBP"=>"£","JPY"=>"¥");
        foreach ($urls as $url){
            $url->rate_per_click_symbol = $currency[$url->currency].$url->rate_per_click;
            $url->budget_symbol = $currency[$url->currency].$url->budget;
            if ($url->click_cut == 0){
                $url->click_cut = $url->rate_per_click;
                DB::table('t_store_')
                    ->where('item_id', $url->item_id)
                    ->update(['click_cut' => $url->rate_per_click]);
            }
        }
        return $urls;
    }

    public function getBillingRateSettingCount($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT COUNT(t_store_.id) AS total
            FROM t_store_
            LEFT JOIN t_user ON t_store_.user_id = t_user.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
            LEFT JOIN t_rate ON t_store_.rate_type = t_rate.id
        ".$where
        );
        return $urls[0]->total;
    }

    public function getAllowUserList($id) {
        $users = DB::table('t_user') -> select('id')
            -> where('id', $id)
            -> orwhere('parent_id', $id)
            -> get();
//            -> keyBy('id');
        $ids = array();
        foreach($users as $user) {
            array_push($ids, $user->id);
        }
//        $ids[] = $users['id'];
        $ids = implode(',', $ids);
        return $ids;
    }

    public function getAllowUserNameList($id) {
        $users = DB::table('t_user') -> select('username')
            -> where('id', $id)
            -> orwhere('parent_id', $id)
            -> get();
        return $users;
    }

    public function getTokenUserList($id) {
        $users = DB::table('t_user') -> select('username', 'email')
            -> where('id', $id)
            -> orwhere('parent_id', $id)
            -> whereNotNull('verification_date')
            -> get();
        return $users;
    }

    public function getUserBillingRateSetting($id, $type, $params, $user_list) {
        $tmp = $this->clause('username', $params);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);

        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT
            t_store_.*, t_user.username, t_user.id, t_billing.billing_profile_id, t_rate.*, t_store_.id AS store_id
            FROM t_store_
            LEFT JOIN t_user ON t_store_.user_id = t_user.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
            LEFT JOIN t_rate ON t_store_.rate_type = t_rate.id
            WHERE t_user.id IN ($user_list)
        ".$where . $orderby . $limit
        );
        $currency = array("AUD"=>"$","USD"=>"$","EUR"=>"€","NZD"=>"$","CNY"=>"¥","CAD"=>"$","GBP"=>"£","JPY"=>"¥");
        foreach ($urls as $url){
            $url->rate_per_click_symbol = $currency[$url->currency].$url->rate_per_click;
            $url->budget_symbol = $currency[$url->currency].$url->budget;
            if ($url->click_cut == 0){
                $url->click_cut = $url->rate_per_click;
                DB::table('t_store_')
                    ->where('item_id', $url->item_id)
                    ->update(['click_cut' => $url->rate_per_click]);
            }
        }
        return $urls;
    }

    public function getUserBillingRateSettingCount($id, $type, $params, $user_list) {
        $tmp = $this->clause('username', $params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT COUNT(t_store_.id) AS total
            FROM t_store_
            LEFT JOIN t_user ON t_store_.user_id = t_user.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
            LEFT JOIN t_rate ON t_store_.rate_type = t_rate.id
            WHERE t_user.id IN ($user_list)
        "
        );
        return $urls[0]->total;
    }

    public function getAllInvoice($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        $clause = "t_user.username = t_billing.profile_name";
        $tmp2 = $this->prepareAnd($tmp, $clause);
        $limit = $this->limit($params);
        $orderby = $this->orderby($params);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT
            t_transaction.*, t_billing.*, t_user.username, t_rate.*
            FROM t_transaction
            LEFT JOIN t_user ON t_transaction.user_id = t_user.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
            LEFT JOIN t_rate ON t_billing.rate_type = t_rate.id
        ".$where . $orderby . $limit
        );
        return $urls;
    }

    public function getAllInvoiceCount($id, $type, $params) {
        $tmp = $this->clause('username', $params);
        $clause = "t_user.username = t_billing.profile_name";
        $tmp2 = $this->prepareAnd($tmp, $clause);
        $where = $this->where($tmp);
        $urls = DB::select(
            "SELECT COUNT(t_transaction.ID) AS total
            FROM t_transaction
            LEFT JOIN t_user ON t_transaction.user_id = t_user.id
            LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
            LEFT JOIN t_rate ON t_billing.rate_type = t_rate.id
        ".$where
        );
        return $urls[0]->total;
    }

    public function getClickDetail($store_id, $id, $type) {

        $urls = DB::select(
            "SELECT
                t_click.*, t_store_.redirect_url
             FROM t_click
             LEFT JOIN t_store_ ON t_click.store_id = t_store_.id
             WHERE t_click.store_id = '$store_id'
            "
        );

        return $urls;
    }

    public function getURLInfo($id) {
        $urls = DB::select(
            "SELECT
                t_url_setting.*, t_user.username
             FROM t_url_setting
             LEFT JOIN t_user ON t_url_setting.manager_id = t_user.id
             WHERE t_user.id = '$id'
            "
        );

        return $urls;
    }

    public function getAllManager() {
        $managers = DB::select(
            "SELECT t_user.*, t_user_p.username as parent_name,t_billing.account_id
                FROM t_user
                LEFT JOIN (SELECT * FROM t_user) as t_user_p ON t_user.parent_id = t_user_p.id
                LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
                WHERE t_user.id <> 1
            "
        );
        return $managers;
    }

    public function getUserProfile($id) {
        $user_list = $this->getAllowUserList($id);
        $managers = DB::select(
            "SELECT t_user.*, t_user_p.username as parent_name,t_billing.account_id
                FROM t_user
                LEFT JOIN (SELECT * FROM t_user) as t_user_p ON t_user.parent_id = t_user_p.id
                LEFT JOIN t_billing ON t_user.username = t_billing.profile_name
                WHERE t_user.id in ($user_list)
            "
        );
        return $managers;
    }

    public function getChildUsers($id) {
        $managers = DB::select(
            "SELECT *
                FROM t_user
                WHERE parent_id = '$id'
            "
        );
        return $managers;
    }

    public function addURL($url, $manager_id, $image_path, $width, $height) {
        $id = DB::table('t_url_setting')->insertGetId([
            'url'             => $url,
            'manager_id'      => ($manager_id),
            'image_path'      => ($image_path),
            'width'           => ($width),
            'height'          => ($height)
        ]);
        return $id;
    }

    public function getIdFromName($name) {
        $user = DB::table('t_user')
            ->where('username', '=', $name)
            ->first();
        if($user == null) return 0;
        $id = $user -> id;
        return $id;
    }

    public function deleteUrl($url_id) {
        DB::table('t_url_setting')->where('id', '=', $url_id)->delete();
    }

    public function deleteRedirectUrl($store_id) {
        DB::table('t_store_')->where('id', '=', $store_id)->delete();
    }

    public function deleteBilling($store_id) {
        DB::table('t_billing')->where('id', '=', $store_id)->delete();
    }

    public function deleteRate($store_id) {
        DB::table('t_rate')->where('id', '=', $store_id)->delete();
    }

//    public function deleteBudgetSetting($store_id) {
//        DB::table('t_store_')->where('id', '=', $store_id)->delete();
//    }

    public function activeRedirectUrl($store_id, $active) {
        DB::select(
            "UPDATE `t_store_` SET `active` = '$active' WHERE id = '$store_id'"
        );
    }

    public function activeBilling($store_id, $active) {
        DB::select(
            "UPDATE `t_billing` SET `active` = '$active' WHERE id = '$store_id'"
        );
    }

    public function activeRate($store_id, $active) {
        DB::select(
            "UPDATE `t_rate` SET `active` = '$active' WHERE id = '$store_id'"
        );
    }

    public function activeBudgetSetting($store_id, $active) {
        DB::select(
            "UPDATE `t_store_` SET `active` = '$active' WHERE id = '$store_id'"
        );
//        return DB::table('t_store_')
//            ->where('id', $store_id)
//            ->update('active', $active);
    }

    public function captureImage($user_id) {
        DB::select(
            "UPDATE `t_capture` SET `time` = `time` + 1 WHERE user_id = '$user_id'"
        );
    }

    public function makeCapture($user_id) {
        DB::table('t_capture')->insertGetId([
            'user_id'   => $user_id,
            'time'      => 0
        ]);
    }

    public function createClick($store_id) {
        return DB::table('t_click')->insertGetId([
            'store_id'   => $store_id,
            'time'      => 0
        ]);
    }

    public function clickUrl($store_id, $source, $cur, $ip) {
        return DB::table('t_click')->insertGetId([
            'store_id'   => $store_id,
            'source'     => $source,
            'click_time' => $cur,
            'source_ip'  => $ip
        ]);
    }

    public function getUrlId($url) {
        $urls = DB::table('t_url_setting')
            ->where('url', '=', $url)
            ->first();
        if($urls == null) return 0;
        $id = $urls -> id;
        return $id;
    }

    public function updateURL($url_id, $url, $manager_id) {
        DB::table('t_url_setting')
            ->where('id', $url_id)
            ->update([
                'url'        =>  $url,
                'manager_id' => $manager_id
            ]);
    }

    public function  updateImage($url_id, $image_path, $width, $height) {
        DB::table('t_url_setting')
            ->where('id', $url_id)
            ->update([
                'image_path'        =>  $image_path,
                'width'             =>  $width,
                'height'            =>  $height,
            ]);
    }

    public function updateIsVerified($id) {
        DB::table('t_user')
            ->where('id', $id)
            ->update([
                'is_verified'        => 'YES'
            ]);
    }

    public function updateVCode($id, $vcode) {
        DB::table('t_user')
            ->where('id', $id)
            ->update([
                'vcode'        => $vcode
            ]);
    }

    public function getIdFromVCode($vcode) {
        $user = DB::table('t_user')
            ->where('vcode', '=', $vcode)
            ->first();
        if($user == null) return 0;
        $id = $user -> id;
        return $id;
    }

    public function getUserFromName($name) {
        $user = DB::table('t_user')
            ->where('username', '=', $name)
            ->first();
        return $user;
    }

    public function getUserFromId($id) {
        $user = DB::table('t_user')
            ->where('id', '=', $id)
            ->first();
        return $user;
    }

    public function updateUser($user_id, $name, $password, $email, $company, $type, $parent_id) {
        DB::table('t_user')
            ->where('id', $user_id)
            ->update([
                'id'          => $user_id,
                'username'    => $name,
                'password'    => $password,
                'email'       => $email,
                'company'       => $company,
                'type'        => $type,
                'parent_id'   => $parent_id
            ]);
    }

    public function updateUrlStatus ($user_id) {
        DB::table('t_store_')
            ->where('user_id', $user_id)
            ->update([
                'active' => 'DeActive'
            ]);
    }

    public function userPayment($user_id, $amount, $date) {
        return DB::table('t_payment')->insertGetId([
            'user_id'   => $user_id,
            'payment'     => $amount,
            'date' => $date
        ]);
    }

    public function getPaymentStatus($user_id) {
        $user = DB::select(
            "SELECT * FROM `t_payment` WHERE `user_id` = '$user_id' ORDER BY date DESC"
        );
        if($user == null) {
            return null;
        }
        return $user[0];
    }

    public function getItemIdClicks($item_id){
        $url_list = DB::table('t_store_')
            ->select('t_store_.hint', 't_store_.source', 't_store_.budget_type', 't_rate.rate_type')
            ->leftJoin("t_rate", "t_rate.id", "t_store_.rate_type")
            ->where('item_id', $item_id)
            ->get();
//        Log::debug($url_list);
//        $url = $url_list[0]->id;
//        $sql_query = "SELECT `click_time` FROM `t_click` WHERE `store_id` = '$url'";
//        foreach ($url_list as $url)
//        {
//            $sql_query .= "OR `store_id` = '$url->id'";
//        }
//        $clicks = DB::select($sql_query);
        return $url_list;
    }

    public function getDescriptionClicks($hint){
        $budget_type = DB::table('t_store_')
            ->select('t_store_.budget_type', 't_store_.rate_type')
            ->where('hint', $hint)
            ->get();
        if ($budget_type[0] -> budget_type == 0) {
            $budget_info = DB::table('t_rate')
                ->select('currency', 'rate_per_click', 'monthly_threshold')
                ->where('id', $budget_type[0] -> rate_type)
                ->get();
        }
        else {
            $budget_info = DB::table('t_store_')
                ->select('t_store_.budget', 't_store_.click_cut', 't_rate.currency')
                ->leftJoin('t_rate', 't_rate.id', 't_store_.rate_type')
                ->where('hint', $hint)
                ->get();
        }
        return $budget_info[0];

    }

    public function getBudgetType($hint){
        $budget_type = DB::table('t_store_')
            ->select('t_store_.budget_type', 't_store_.rate_type')
            ->where('hint', $hint)
            ->get();
        return $budget_type[0];
    }

    public function getDescriptionClickCount($hint){
        $store_id = DB::table('t_store_')
            ->select('id')
            ->where('hint', $hint)
            ->get();
        return DB::table('t_click')
            ->select('t_click.click_time')
            ->where('store_id', $store_id[0] -> id)
            ->get();

    }

    public function getClickCount($user_id) {
        $click_list = DB::table("t_store_")
            ->select("t_click.source", "t_click.click_time", "hint", "click_cut", "budget")
            ->leftJoin("t_click", "t_click.store_id", "t_store_.id")
            ->where("t_store_.user_id", $user_id)
            ->get();
        return $click_list;
    }

    public function getAdminClickCount() {
        $click_list = DB::table("t_store_")
            ->select("t_click.source", "t_click.click_time", "hint", "click_cut", "budget")
            ->leftJoin("t_click", "t_click.store_id", "t_store_.id")
            ->get();
        return $click_list;
    }

    public function getMonthlyMaximum($user_id)
    {
        $month_amount = DB::table("t_store_")
            ->select("budget", "hint")
            ->where("user_id", $user_id)
            ->get();
        return $month_amount;
    }

    public function getTransactionData($user_id)
    {
        $income = $this->currentRemain($user_id);
        return $income;
    }

    public function getTransactionList($user_id)
    {
        $income = DB::table("t_transaction")
            ->select("income", "income_date", "invoice", "currency")
            ->where("user_id", $user_id)
            ->get();
        $outcome = DB::table("t_store_")
            ->select("t_click.click_time", "click_cut")
            ->leftJoin("t_click", "t_click.store_id", "t_store_.id")
            ->where("t_store_.user_id", $user_id)
            ->get();
        $transaction_list = [];
        foreach ($income as $income_)
        {
            $transaction_list[] = ['money' => $income_->income, 'date' => $income_->income_date, 'invoice' => $income_->invoice, 'currency' => $income_->currency];
        }
        foreach ($outcome as $outcome_)
        {
            if ($outcome_->click_time != null) {
                $transaction_list[] = ['money' => -($outcome_->click_cut), 'date' => $outcome_->click_time];
            }
        }
//        $transaction_list = json_encode($transaction_list);
        return $transaction_list;
    }

    public function getPaymentSetting($user_id){
        return DB::table('t_user')
            ->select('currency', 'frequency')
            ->where('id', $user_id)
            ->get();
    }

    public function updatePaymentSetting($user_id, $currency, $frequency){
        DB::table("t_user")
            ->where("id", $user_id)
            ->update([
                "currency" => $currency,
                "frequency" => $frequency
            ]);
    }

    public function currentRemain($user_id)
    {
        $income = DB::table("t_transaction")
            ->select("current")
            ->where("user_id", $user_id)
            ->orderBy('ID', 'desc')
            ->first();
        if ($income == null) {
            $income_value = 0;
        }
        else {
            $income_value = $income->current;
        }
        $outcome_list = DB::table("t_store_")
            ->select("t_click.source", "t_click.click_time", "click_cut")
            ->leftJoin("t_click", "t_click.store_id", "t_store_.id")
            ->where("t_store_.user_id", $user_id)
            ->get();
        $total_out = 0;
        foreach ($outcome_list as $out)
        {
            if ($out->source != null) {
                $total_out += $out->click_cut;
            }
        }
        $current_remain = $income_value - $total_out;

        return $current_remain;
    }

    public function updateBudget($budget, $user_id){
        DB::table("t_store_")
            ->where("hint", $user_id)
            ->update([
                'budget'   => $budget,
                'budget_type' => 1
            ]);
    }

    public function getAllRedirects(){
        return DB::table("t_store_")
            ->select("t_user.username", "redirect_url", "source", "hint", "budget", "click_cut")
            ->leftJoin("t_user", "t_user.id", "t_store_.user_id")
            ->get();
    }

    public function updateCut($user_id, $cut){
        DB::table("t_store_")
            ->where("hint", $user_id)
            ->update([
                'click_cut'   => $cut
            ]);
    }

    public function createNewBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
                     $state, $phone, $bpId, $billingFrequency, $date, $rate_type, $suburb, $address){
        //check user role and assign account #
        $user = DB::table('t_user')->where('username',$userProfileName)->first();
        $type = $user->type;
        $click_cut = DB::table('t_rate')->select('rate_per_click')->where('rate_type', $rate_type)->first();
        if($type != "User")
            $account_id = strval(mt_rand(100000, 999999)).strval(mt_rand(100000, 999999));
        else{
            $parent = DB::table('t_user')->where('id',$user->parent_id)->first();
            $parent_billing = DB::table('t_billing')->where('profile_name',$parent->username)->first();
            if($parent_billing != null)
                $account_id = $parent_billing->account_id;
            else
                $account_id = null;
        }

        DB::table("t_billing")
            ->insertGetID([
                'profile_name' => $userProfileName,
                'email'   => $primaryEmailAddress,
                'payment_method'   => $paymentMethod,
                'country'   => $country,
                'state'   => $state,
                'frequency'   => $billingFrequency,
                'phone'   => $phone,
                'suburb'  => $suburb,
                'address'  => $address,
                'billing_profile_id'   => $bpId,
//                'click_cut' => $click_cut,
                'created_date'   => $date,
                'rate_type'  => $rate_type,
                'active'  => 'Active',
                'account_id' => $account_id
            ]);
//
//        $user_id = DB::table("t_user")
//            ->select("id")
//            ->where("username", $userProfileName)
//            ->get();
//
//        foreach ($user_id as $user_id_each){
//            $item_id = DB::table("t_store_")
//                ->select("item_id")
//                ->where("user_id", $user_id_each->id)
//                ->get();
//
//            foreach ($item_id as $item_id_each) {
//                $item_auto_id = strtoupper(substr($country, 0, 2)).substr($item_id_each->item_id, 2, 11);
//                DB::table("t_store_")
//                    ->where("user_id", $user_id_each->id)
//                    ->update([
//                        'item_id'  => $item_auto_id
//                    ]);
//            }
//        }
    }

    public function updateBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
                                     $state, $phone, $bpId, $billingFrequency, $date, $rate_type, $suburb, $address){
        $user_list = DB::table("t_billing")
            ->select("profile_name")
            ->where("profile_name", $userProfileName)
            ->get();

        $click_cut = DB::table('t_rate')->select('rate_per_click')->where('rate_type', $rate_type)->first();

        if (count($user_list) == 0) {
            $this -> createNewBilling($userProfileName, $primaryEmailAddress, $paymentMethod, $country,
                $state, $phone, $bpId, $billingFrequency, $date, $rate_type, $suburb, $address);
        }

        else {
            DB::table("t_billing")
                ->where("profile_name", $userProfileName)
                ->update([
                    'email'  => $primaryEmailAddress,
                    'payment_method'  => $paymentMethod,
                    'country'  => $country,
                    'state'  => $state,
                    'phone'  => $phone,
                    'suburb'  => $suburb,
                    'address'  => $address,
                    'billing_profile_id'  => $bpId,
                    'frequency'  => $billingFrequency,
//                    'click_cut' => $click_cut,
                    'rate_type'  => $rate_type,
                    'created_date'  => $date
                ]);

//            $user_id = DB::table("t_user")
//                ->select("id")
//                ->where("username", $userProfileName)
//                ->get();
//
//            foreach ($user_id as $user_id_each){
//                $item_id = DB::table("t_store_")
//                    ->select("item_id")
//                    ->where("user_id", $user_id_each->id)
//                    ->get();
//
//                foreach ($item_id as $item_id_each) {
//                    $item_auto_id = strtoupper(substr($country, 0, 2)).substr($item_id_each->item_id, 2, 11);
//                    DB::table("t_store_")
//                        ->where("user_id", $user_id_each->id)
//                        ->update([
//                            'item_id'  => $item_auto_id
//                        ]);
//                }
//            }
        }
    }

    public function getBillingInfo($id) {
        return DB::table("t_billing")
            ->select("profile_name", "email", "payment_method", "country", "state", "frequency", "phone", "billing_profile_id", "rate_type", "suburb", "address")
            ->where("id", $id)
            ->get();
    }

    public function getAllUserName() {
        return DB::table("t_user")
            ->select("username")
            ->get();
    }

    public function generateItemID() {
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $strength = 10;
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[intval(mt_rand(0, $input_length - 1))];
            $random_string .= $random_character;
        }
        
        if ($this->check_item_ID($random_string))
            $random_string = $this->generateItemID();
        return $random_string;
    }

    public function createNewRate($ratetype, $ratename, $description, $country, $currency, $rateperclick, $monthlythreshold,$rate_id){
        $country = strtoupper($country);
        if($rate_id > 0){
            DB::table("t_rate")->where('id',$rate_id)->update([
                'rate_type' => $ratetype,
                'rate_name'   => $ratename,
                'description'   => $description,
                'country'   => $country,
                'currency'   => $currency,
                'rate_per_click'   => $rateperclick,
                'monthly_threshold'   => $monthlythreshold,
                'active'  => 'Active']);
        }
        else{
            DB::table("t_rate")
                ->insertGetID([
                    'rate_type' => $ratetype,
                    'rate_name'   => $ratename,
                    'description'   => $description,
                    'country'   => $country,
                    'currency'   => $currency,
                    'rate_per_click'   => $rateperclick,
                    'monthly_threshold'   => $monthlythreshold,
                    'active'  => 'Active'
                ]);
        }
    }

    public function check_item_ID($id) {
        return property_exists(DB::table('t_store_')->select('item_id')->get(), $id);
    }

    public function createReceipt($user_id){
        $user = \Illuminate\Support\Facades\DB::table('t_user')->find($user_id);
        $profile_name = $user->username;
        $txt = strtoupper(substr($profile_name,0,3));
        $txt = str_pad($txt,3,0,STR_PAD_RIGHT);
        $transaction_record = Transaction::where('user_id',$user_id)->orderBy('income_date','DESC')->first();
        if($transaction_record != null){
            $invoice = $transaction_record->invoice;
            $record = substr($invoice,0, 6);
            $last_index = intval(substr($invoice,-2));
            $last_index = str_pad($last_index+1, 2, '0', STR_PAD_LEFT);
            return $record.date('dmY').$last_index;
        }
        else{
            $history = Transaction::where('invoice','like','%'.$txt.'%')->orderBy('invoice','DESC')->orderBy('income_date','DESC')->first();
            if($history == null){
                $txt = $txt."001".date('dmY')."01";
            }
            else{
                $se_txt = intval(substr($history->invoice, 3, 3));
                $se_txt = str_pad($se_txt+1, 3, '0', STR_PAD_LEFT);
                $txt = $txt.$se_txt.date('dmY')."01";
            }
            return $txt;
        }
    }
}