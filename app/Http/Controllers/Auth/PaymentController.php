<?php

namespace App\Http\Controllers;


use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Transaction;



class PaymentController extends Controller
{
    // constructor
    public function __construct(){
        Braintree_Configuration::environment("sandbox");//sandbox
        Braintree_Configuration::merchantId("pbdg3p5wzvztt72p");//
        Braintree_Configuration::publicKey("rmqxrqdd3hjmbkqx");//
        Braintree_Configuration::privateKey("0cd4e7de0071a41a78276a2d4a09dc24");//
    }

    // process payment with user's paypal or credit card number and id
    public function processPayment(){
        if(empty($_POST['payment_method_nonce'])){
            return "error";
        }

        $result = Braintree_Transaction::sale([
            'amount' => 1,
            'paymentMethodNonce' => $_POST['payment_method_nonce'],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        return $_POST['payment_method_nonce'];


        return "error";
    }

    // returns client token for item to sale
    public function getClientToken(){
        Log::info("Come in get Client Token");
        return json_encode(Braintree_ClientToken::generate());
    }
}
