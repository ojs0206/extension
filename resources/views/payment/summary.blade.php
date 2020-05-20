@extends('layout.master')

@section('title')
    Billing & Payments
@endsection

@section('css4page')
    <style>
        .sidenav {
            height: 100%;
            width: 15%;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: whitesmoke;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 5%;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 15px;
            color: black;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: blue;
        }

        .subtitle {
            border: 2px solid whitesmoke;
            color: #000;
            padding-left: 10%;
            margin: auto;
            font-size: 20px;
            font-weight: bold;
        }

        .subcontent {
            border: 2px solid whitesmoke;
            margin: auto;
            font-weight: bold;
            color: #000;
            padding-top: 1%;
            height: 200px;
        }

        .row-padding {
            padding-top: 1%;
            padding-bottom: 2%;
        }

        .slider {
            -webkit-appearance: none;
            width: 100%;
            height: 5px;
            background: #d3d3d3;
            outline: none;
            opacity: 0.7;
            -webkit-transition: .2s;
            transition: opacity .2s;
        }

        .slider:hover {
            opacity: 1;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 15px;
            height: 15px;
            background: blue;
            cursor: default;
        }

        .slider::-moz-range-thumb {
            width: 15px;
            height: 15px;
            background: blue;
            cursor: default;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-2">
        {{--Side Bar--}}
        <div id="sidenav" class="sidenav">
            <a href="<?=url('/payment/summary')?>">Summary</a>
            <a href="<?=url('/payment/transaction')?>">Transaction</a>
            <a href="<?=url('/payment/settings')?>">Settings</a>
        </div>
    </div>
    <div class="col-sm-10">
        {{--Title--}}
        <div class="row subtitle">
            <p class="subtitle">Summary</p>
        </div>

        {{--Content--}}
        <div class="row row-padding">
            <div class="col-sm-3"></div>
            <div class="col-sm-7 subcontent">
                <p style="font-size: 18px;">Your current Balance</p>
                <p style="text-align: right; font-size: 25px;">A${{$value}}</p>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <input type="range" value="{{$value}}" class="slider" id="currentValue" name="currentValue" min="1" max="10000">
                    </div>
                </div>
                <div class="row" style="padding-top: 5%;">
                    <form class="" method="POST" id="payment-form" action="{!! URL::to('paypal') !!}">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-3">
                            Enter amount to pay:<input class="" id="amount" type="text" name="amount" value="10">
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-2" style="padding-top: 1%;">
                            <button class="btn btn-primary">Submit Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row row-padding">
            <div class="col-sm-3"></div>
            <div class="col-sm-3 subcontent" id="transaction">
                <p style="font-size: 18px;">Transactions</p>
                <div class="row" style="padding-top: 2%">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8"></div>
                    <div class="col-sm-2">
                        <a href="<?=url('payment/transaction')?>">More</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-3 subcontent" id="settings">
                <p style="font-size: 18px;">Settings</p>
                <div class="row" style="padding-top: 3%;">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">Your Currency:</div>
                    <div class="col-sm-4">{{$setting[0]->currency}}</div>
                </div>
                <div class="row" style="padding-top: 3%;">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6">Payment Frequency:</div>
                </div>
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-4">{{$setting[0]->frequency}}</div>
                </div>
                <div class="row" style="padding-top: 10%;">
                    <div class="col-sm-8"></div>
                    <div class="col-sm-2">
                        <a href="<?=url('payment/settings')?>">More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js4page')
    <script>
        var transaction = document.getElementById('transaction');
        transaction.onclick = function () {
            window.location.href = "/payment/transaction";
        };
        // var method = document.getElementById('method');
        // method.onclick = function () {
        //     window.location.href = "/payment/method";
        // };
        var settings = document.getElementById('settings');
        settings.onclick = function () {
            window.location.href = "/payment/settings";
        };

        $('form').submit(function () {

            // Get the Login Name value and trim it
            var amount = $.trim($('#amount').val());

            // Check if empty of not
            if (amount  === '') {
                // alert('Please input your payment amount.');
                console.log(amount);
                $.trim($('#amount').focus());
                return false;
            }
        });

        {{--var transaction_history = "{{json_encode($history)}}";--}}
        var transaction_history = @json($history);

        var history_table = document.getElementsByTagName('tbody')[0];
        var history_inner = "";
        for (var i = 0; i < 2; i ++){
            if (transaction_history[i]['money'] != 0){
                history_inner += "<tr><td>" + (i+1) + "</td>" + "<td>" + transaction_history[i]['money'] + "</td>" + "<td>" + transaction_history[i]['date'] + "</td></tr>"
            }
        }
        history_table.innerHTML = history_inner;
    </script>
@endsection

@section('js4event')
    <script>


    </script>
@endsection
