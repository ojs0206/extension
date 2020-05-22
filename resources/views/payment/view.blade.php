@extends('layout.master')

@section('title')
    Payment
@endsection

@section('css4page')
    <link rel="stylesheet" href="<?=asset('css/paymentfont.min.css')?>">
    <style>
        body {
            background: #dddddd !important;
        }
        .panel-body{
            background-color: white;
        }
        .field{
            background-color: white;
            border: 1px solid darkgrey;
            padding-left: 10px;
        }
        .b-t-l-r-5{
            border-top-left-radius: 5px;
        }
        .b-t-r-5{
            border-top-right-radius: 5px;
            border-bottom-right-radius: 0px;
        }
        .b-b-r-r-5{
            border-bottom-right-radius: 5px;
            border-top-right-radius: 0px;
        }
        .b-b-l-r-5{
            border-bottom-left-radius: 5px;
        }
        .div_canrdInfo span{
            background-color: white;
            border: 1px solid darkgrey;
            padding: 5px;
        }
        .div_canrdInfo span img{
            width: 75px;
            height: 20px;
        }
        .d-flex{
            display: flex;
        }
        .w-50{
            width: 50%;
        }
        .error {
            display: none;
            color: #E4584C;
            margin-top: 5px;
        }
        .error.visible {
            display: inline;
        }
        .pf{
            line-height: 30px;
            color: grey;
            font-size: 16px;
        }
        .pf-mastercard-alt{
            color: #0a3a82;
        }
        .pf-american-express{
            color: #007bc1;
        }
        .pf-discover{
            color: #f68121;
        }
        .pf-diners{
            color: #004A97;
        }
        .pf-jcb{
            color: #c3000d;
        }
        .pf-visa{
            color: #0157a2;
        }
        .form-group{
            margin-bottom: 5px;
        }
        .custom-select{
            border-radius: 5px !important;
            height: 42px;
        }
        .btn-pay{
            width: 60%;
            font-size: 20px;
            height: 42px;
            border-radius: 5px;
        }
        .form-control{
            height: 42px;
        }
        input.form-control{
            padding-left: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title">Credit Card Payment</h3>
                    </div>
                    <div class="panel-body">
                        <form action="/payment/creditcard" method="post" id="frm-award" name="frm-award">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-12 margin-top-10">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email"/>
                                </div>
                                <div class="col-xs-12 div_canrdInfo margin-top-20">
                                    <div class="form-group">
                                        <label for="cardNumber">Card details</label>
                                    </div>
                                    <div class="input-group">
                                        <div id="card-number-element" class="field b-t-l-r-5"></div>
                                        <span class="input-group-addon b-t-r-5"><img src="{{asset('/images/credit.png')}}" alt="img"></span>
                                    </div>
                                    <div class="d-flex">
                                        <div class="w-50">
                                            <div id="card-expiry-element" class="field b-b-l-r-5"></div>
                                        </div>
                                        <div class="d-flex w-50">
                                            <div id="card-cvc-element" class="field" style="width: 75%"></div>
                                            <span class="brand input-group-addon b-b-r-r-5 text-center" style="width: 25%"><i class="pf pf-credit-card" id="brand-icon"></i></span>
                                        </div>
                                    </div>
                                    <div class="error"></div>
                                </div>
                                <div class="col-xs-12 margin-top-20">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                    </div>
                                    <div>
                                        <select class="form-control custom-select" name="currency">
                                            <option value="0">AUD</option>
                                            <option value="1">USD</option>
                                            <option value="2">EUR</option>
                                            <option value="3">NZD</option>
                                            <option value="4">CNY</option>
                                            <option value="5">CAD</option>
                                            <option value="6">GBP</option>
                                            <option value="7">JPY</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 margin-top-40 text-center">
                                    <button type="button" class="btn btn-dark-orange btn-pay" id="btn_submit" onclick="checkValidStripe()">Pay</button>
                                </div>
                                <input type="hidden" name="card_token" id="card_token">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title">PayPal Payment</h3>
                    </div>
                    <div class="panel-body">
                        <form action="/payment/paypal" method="post" id="frm-paypal" name="frm-paypal">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-12 margin-top-10">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                    </div>
                                    <input type="number" class="form-control" id="price" name="price" value="1" readonly/>
                                </div>
                                <div class="col-xs-12 margin-top-20">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                    </div>
                                    <div>
                                        <select class="form-control custom-select" name="currency">
                                            <option value="0">AUD</option>
                                            <option value="1">USD</option>
                                            <option value="2">EUR</option>
                                            <option value="3">NZD</option>
                                            <option value="4">CNY</option>
                                            <option value="5">CAD</option>
                                            <option value="6">GBP</option>
                                            <option value="7">JPY</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 margin-top-40 text-center">
                                    <button type="submit" class="btn btn-dark-orange btn-pay">PayPal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js4page')
    <script>

    </script>
@endsection

@section('js4event')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
    <!-- stripe -->
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/stripe.js') }}"></script>
@endsection