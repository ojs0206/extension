<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
define('SESS_UID',                  'SESS_CMS_ADMIN_UID');
define('SESS_USERNAME',             'SESS_CMS_ADMIN_USERNAME');
define('SESS_USERTYPE',             'SESS_CMS_ADMIN_USERTYPE');

Route::get('/', function () {
    $uid = session() -> get(SESS_UID);
    if(isset($uid) && $uid != NULL) {
        return redirect('/home');
    }
    return redirect('/login');
});


Route::get('/test',  function () {
    return view('test');
});


Route::get('/login',                                    'Auth\LoginController@showLoginPage');
Route::post('/sign-in',                                 'Auth\LoginController@signIn');
Route::post('/sendEmail',                                    'Auth\LoginController@sendEmail');
Route::post('paypal',                                      'PaymentsController@payWithpaypal');
Route::get('status',                                        'PaymentsController@getPaymentStatus');
Route::get('verify/{email}/{token}',                    'Auth\LoginController@verifyEmail');

Route::group(['middleware' => ['cors']], function () {
    Route::get('/sign-in-extension', 'Auth\LoginController@signInExtension');
    Route::post('/save/cell', 'Auth\LoginController@saveCells');
    Route::post('/save/redirect', 'Auth\LoginController@saveRedirect');
    Route::post('/click', 'Auth\LoginController@click');

    Route::get('/get/cell-info', 'Auth\LoginController@getCellInfo');
    Route::get('/get/check-url', 'Auth\LoginController@checkURLRedirect');
});

Route::group(['middleware' => ['authenticate']], function () {
    Route::post('/get/all-rate-type',                         'Auth\LoginController@getAllRateType');
    Route::get('/registration',                             'Auth\LoginController@showRegistrationPage');
    Route::get('/get/all-urls',                             'Auth\LoginController@getAllUrls');
    Route::get('/get/all-rate',                             'Auth\LoginController@getAllRates');
    Route::get('/get/all-redirect',                          'Auth\LoginController@getAllRedirectURLInfo');
    Route::get('/get/all-billing',                          'Auth\LoginController@getAllBillingInfo');
    Route::get('/get/all-invoice',                          'Auth\LoginController@getAllInvoice');
    Route::post('/get/budget-setting',                          'Auth\LoginController@getBudgetSetting');
    Route::get('/get/billing-rate-setting',                 'Auth\LoginController@getBillingRateSetting');
    Route::post('/get/invoice',                             'Auth\LoginController@getInvoice');

    Route::post('/registration/CreateUser',                 'Auth\LoginController@createUser');

    Route::get('/home',                                     'Auth\LoginController@showHome');
    Route::get('/payment',                                  'Auth\LoginController@showPayment');
    Route::post('/payment/billing_rate_setting',            'Auth\LoginController@saveBudget');
    Route::post('/new_billing',                             'PaymentsController@createNewBilling');
    Route::post('/update_billing',                          'PaymentsController@updateBilling');
    Route::get('/payment/graph',                            'Auth\LoginController@showAdminGraph');
    Route::post('/payment/save',                             'Auth\LoginController@saveCut');
    Route::get('/payment/report',                             'Auth\LoginController@exportPaymentCollection');
    Route::get('/payment/paypal',                           'PaymentsController@showPayment');
    Route::get('/payment/newBillingSetup',                 'Auth\LoginController@showNewBillingSetup');
    Route::get('/payment/getClientToken',                  'PaymentsController@getClientToken');
    Route::get('/payment/summary',                          'PaymentsController@showSummary');
    Route::get('/payment/createBilling',                    'PaymentsController@createBilling');
    Route::get('/payment/editBilling',                      'PaymentsController@editBilling');
    Route::post('/payment/deleteBilling',                      'PaymentsController@deleteBilling');
    Route::get('/payment/transaction',                      'PaymentsController@showTransaction');
    Route::get('/payment/method',                           'PaymentsController@showMethod');
    Route::get('/payment/settings',                         'PaymentsController@showSettings');
    Route::get('/payment/billing_rate_setting',             'PaymentsController@billingRateSetting');
    Route::get('/payment/billing_rate_setting/report',      'PaymentsController@billingRateSettingReport');
    Route::post('/billing_rate_setting/edit',             'PaymentsController@editBillingRateSetting');
    Route::get('/payment/budget_setting',                   'PaymentsController@budgetSetting');
    Route::get('/payment/default_rate',                     'PaymentsController@defaultRate');
    Route::get('/payment/default_rate/report',               'PaymentsController@defaultRateReport');
    Route::get('/payment/invoice',                          'PaymentsController@invoice');
    Route::get('/payment/forex',                            'PaymentsController@forex');
    Route::post('/payment/invoice/generatePDF',              'PaymentsController@invoice_pdf');
    Route::post('/payment/settings',                        'PaymentsController@updateSettings');
    Route::post('/payment/updateMembership',               'PaymentsController@processPayment');
    Route::get('/item_id/statistics/{id}',               'PaymentsController@itemStatistic');
    Route::get('/description/statistics/{hint}',               'Auth\LoginController@descriptionShow');

    Route::get('/payment/view',                             'BillController@view');
    Route::get('/payment/check_paypal/{id}',                          'BillController@check_paypal');
    Route::post('/payment/creditcard',                        'BillController@creditcard');
    Route::get('/payment/autopay',                          'BillController@autopay');
    Route::post('/payment/paypal',                        'BillController@paypal');
    Route::get('/payment/back',                        'BillController@back');
    Route::get('/payment/cancel',                        'BillController@cancel');
    Route::post('/pay_invoice',                        'BillController@pay_invoice');
    Route::get('/payment/success',                        'BillController@success');
    Route::get('/payment/check_success',                          'BillController@check_success');

    Route::get('/count',                                     'Auth\LoginController@showCount');
    Route::get('/count/detail',                              'Auth\LoginController@showDetailClickInfo');
    Route::get('/count/detail/report',                       'Auth\LoginController@reportClickInfo');
    Route::post('/redirect/delete',                          'Auth\LoginController@deleteRedirectUrl');
    Route::get('/redirect/report',                          'Auth\LoginController@exportRedirectURL');
    Route::post('/redirect/active',                         'Auth\LoginController@activeRedirectUrl');
    Route::post('/redirect/edit-setting',                    'Auth\LoginController@editRedirectSetting');
    Route::get('/setting',                                  'Auth\LoginController@showSetting');
    Route::get('/setting/report',                          'Auth\LoginController@exportURlCollection');
    Route::get('/sign-out',                                 'Auth\LoginController@singOut');
    Route::get('/get/url-info',                             'Auth\LoginController@getUrlInfo');
    Route::get('/get/image-path',                            'Auth\LoginController@getImage');
    Route::post('/setting/edit',                             'Auth\LoginController@editUrl');
    Route::post('/setting/add',                             'Auth\LoginController@addUrl');
    Route::post('/setting/delete',                          'Auth\LoginController@deleteUrl');
    Route::get('/child',                                    'Auth\LoginController@showChild');
    Route::post('/child/update',                            'Auth\LoginController@updateChild');
    Route::post('/child/add',                               'Auth\LoginController@addChild');
    Route::post('/billing/active',                          'PaymentsController@activeBill');
    Route::post('/rate/create',                          'PaymentsController@createDefaultRate');
    Route::post('/rate/active',                          'PaymentsController@activeDefaultRate');
    Route::post('/rate/delete',                          'PaymentsController@deleteDefaultRate');
    Route::post('/rate/getone',                          'Auth\LoginController@getOneRate');
    Route::post('/budget_setting/active',                          'PaymentsController@activeBudgetSetting');
    Route::post('/budget_setting/delete',                          'PaymentsController@deleteBudgetSetting');
    Route::post('/budget_setting/savetype',                          'PaymentsController@saveBudgetType');


});







