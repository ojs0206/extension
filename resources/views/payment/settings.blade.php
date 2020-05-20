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

        .list-title {
            font-weight: bold;
            font-size: 1vw;
            color: #000;
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
            <p class="subtitle">Settings</p>
        </div>
        {{--Content--}}
        <div class="row" style="padding-top: 5%">
            <div class="col-sm-3"></div>
            <form method="post" action="<?=url('/payment/settings')?>">
            <div class="col-sm-7" style="border: 2px solid whitesmoke;">
                <div class="row" style="padding-top: 5%;">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                        <p class="list-title">Select your currency</p>
                    </div>
                    <div class="col-sm-6">
                        <select id="currency" name="currency">
                            <option value="AUD">AUD</option>
                            <option value="NZD">NZD</option>
                            <option value="USD">USD</option>
                            <option value="GBP">GBP</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="padding-top: 5%;">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                        <p class="list-title">Select your Payment Frequency</p>
                    </div>
                    <div class="col-sm-6">
                        <select id="frequency" name="frequency">
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="padding-top: 5%; padding-bottom: 3%;">
                    <div class="col-sm-8"></div>
                    <div class="col-sm-4">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js4page')
    <script>
        var data = {currency: "{{$setting[0]->currency}}", frequency: "{{$setting[0]->frequency}}"};
        document.getElementById('currency').value = data['currency'];
        document.getElementById('frequency').value = data['frequency'];
    </script>
@endsection

@section('js4event')
    <script>


    </script>
@endsection
