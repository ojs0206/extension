@extends('layout.master')

@section('title')
    New Billing Setup
@endsection

@section('css4page')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
         .country-select {
             padding-top: 2%;
             border: 2px solid whitesmoke;
             padding-left: 3%;
             padding-bottom: 2%;
             margin-bottom: 4%;
         }

         .payment-method{
             padding-top: 2%;
             border: 2px solid whitesmoke;
             padding-left: 3%;
             padding-bottom: 2%;
         }

         .buttons {
             padding-top: 2%;
         }

         .button-align {
             margin-left: 2%;
         }

         .selector {
             padding-top: 2%;
             padding-left: 2%;
         }
/*------------------Radio Button-----------------------*/
         /* The container */
         .container {
             display: block;
             position: relative;
             padding-left: 35px;
             margin-bottom: 12px;
             cursor: pointer;
             font-size: 22px;
             -webkit-user-select: none;
             -moz-user-select: none;
             -ms-user-select: none;
             user-select: none;
         }

         /* Hide the browser's default radio button */
         .container input {
             position: absolute;
             opacity: 0;
             cursor: pointer;
         }

         /* Create a custom radio button */
         .checkmark {
             position: absolute;
             top: 0;
             left: 0;
             height: 25px;
             width: 25px;
             background-color: #eee;
             border-radius: 50%;
         }

         /* On mouse-over, add a grey background color */
         .container:hover input ~ .checkmark {
             background-color: #ccc;
         }

         /* When the radio button is checked, add a blue background */
         .container input:checked ~ .checkmark {
             background-color: #2196F3;
         }

         /* Create the indicator (the dot/circle - hidden when not checked) */
         .checkmark:after {
             content: "";
             position: absolute;
             display: none;
         }

         /* Show the indicator (dot/circle) when checked */
         .container input:checked ~ .checkmark:after {
             display: block;
         }

         /* Style the indicator (dot/circle) */
         .container .checkmark:after {
             top: 9px;
             left: 9px;
             width: 8px;
             height: 8px;
             border-radius: 50%;
             background: white;
         }
/*------------------Radio Button End-----------------------*/

/*------------------Modal-----------------------*/
         /* The Modal (background) */
         .modal {
             display: none; /* Hidden by default */
             position: fixed; /* Stay in place */
             z-index: 1; /* Sit on top */
             padding-top: 100px; /* Location of the box */
             left: 0;
             top: 0;
             width: 100%; /* Full width */
             height: 100%; /* Full height */
             overflow: auto; /* Enable scroll if needed */
             background-color: rgb(0,0,0); /* Fallback color */
             background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
         }

         /* Modal Content */
         .modal-content {
             background-color: white;
             margin: auto;
             padding: 20px;
             border: 1px solid #888;
             width: 40%;
             vertical-align: center;
         }

         /* The Close Button */
         .close {
             color: black;
             float: right;
             font-size: 28px;
             font-weight: bold;
             padding-right: 5%;
         }

         .link_cont {
             font-size: 20px;
             font-weight: bold;
             color: black;
             padding-left: 5%;
         }
/*------------------Modal-----------------------*/
    </style>

@endsection

@section('content')
    <form method="POST" id="payment-form" action="<?=url('paypal')?>">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            {{--<form action="" method="get" id="payment_method" name="payment_method"></form>--}}
                <div class="row">
                    <p style="font-weight: bold; font-size: 20px; color: black;">Payment Setup</p>
                </div>
                <div class="row country-select">
                    <p style="font-weight: bold; font-size: 17px; color: black;">Billing Country</p>
                    <div class="selector">
                        <select id="country" name="country">
                            <option value="AU">Australia</option>
                            <option value="NZ">New Zealand</option>
                            <option value="US">United States</option>
                            <option value="UK">United Kingdom</option>
                        </select>
                    </div>
                    <div class="selector">
                        <select id="currency" name="currency">
                            <option value="AUD">AUD</option>
                            <option value="NZD">NZD</option>
                            <option value="USD">USD</option>
                            <option value="GBP">GBP</option>
                        </select>
                    </div>
                </div>
                <div class="row payment-method">
                    <p style="font-weight: bold; font-size: 17px; color: black; padding-bottom: 2%;">Payment Method</p>
                    <label class="container">PayPal  <font size="2">   (You can also use your credit card here.)</font>
                        <input type="radio" name="payment" id="paypal" checked>
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="row buttons">
                    <button class="btn button-align btn-primary" id="submitbtn">Submit</button>
                    <button class="btn button-align btn-primary" id="cancelbtn">Cancel</button>
                </div>

        </div>
    </div>

    <!-- The Modal -->
    <div id="editModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="row">
                <span class="close">&times;</span>
                <span class="link_cont">Pay with PayPal</span>
            </div>

            <div class="row" style="padding-top: 5%">
                <div class="col-sm-2"></div>
                <div class="col-sm-6">
                    {{ csrf_field() }}
                    <label class="w3-text-blue">
                        <strong>Enter Amount</strong>
                        <input class="w3-input" id="amount" type="text" name="amount" value="10" style="width: 80%">
                    </label>
                </div>
                {{--<button class="w3-btn w3-blue">Pay with PayPal</button>--}}
            </div>
            <div class="row" style="padding-left: 70%;">
                <button class="btn btn-primary" id="continue">continue</button>
            </div>

        </div>
    </div>
    </form>
@endsection

@section('js4page')
    <script>
        var country_currency = {AUD:"AU", NZD:"NZ", USD:"US", GBP:"UK"};
        var setting = "{{$setting[0]->currency}}";
        document.getElementById('country').value = country_currency[setting];
        document.getElementById('currency').value = setting;
        document.getElementsByClassName("close")[0].onclick = function() {
            document.getElementById("editModal").style.display = "none";
        };

        // document.getElementById('continue').onclick = function() {
        //     window.open("https://www.paypal.com/signin", '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
        // };

        document.getElementById('submitbtn').onclick = function() {
            // if (document.getElementById('paypal').checked == true || document.getElementById('visa').checked == true){
            //     document.forms["payment_method"].submit();
            // }
            // else {
            //     alert("Please Select Payment Method");
            // }
            if (document.getElementById('paypal').checked === true){
                document.getElementById("editModal").style.display = "block";
            }
        };

        document.getElementById('cancelbtn').onclick = function(){
            document.getElementById('country').value = country_currency[setting];
            document.getElementById('currency').value = setting;
            document.getElementById('paypal').checked = false;
            document.getElementById('visa').checked = false;
        };


        $('form').submit(function () {

            // Get the Login Name value and trim it
            var amount = $.trim($('#amount').val());

            // Check if empty of not
            if (amount === '') {
                // alert('Please input your payment amount.');
                console.log(amount);
                $.trim($('#amount').focus());
                return false;
            }
        });
    </script>
    <script src="https://www.paypalobjects.com/api/checkout.js" data-version-4 log-level="warn"></script>

    <!-- Load the client component. -->
    <script src="https://js.braintreegateway.com/web/3.54.2/js/client.min.js"></script>

    <!-- Load the PayPal Checkout component. -->
    <script src="https://js.braintreegateway.com/web/3.54.2/js/paypal-checkout.min.js"></script>
@endsection

@section('js4event')
    <script>


    </script>
@endsection