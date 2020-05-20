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
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-2">
        {{--Side Bar--}}
        <div id="sidenav" class="sidenav">
            <a href="<?=url('/payment/summary')?>">Summary</a>
            <a href="<?=url('/payment/transaction')?>">Transaction</a>
            <a href="<?=url('/payment/method')?>">Payment Methods</a>
            <a href="<?=url('/payment/settings')?>">Settings</a>
        </div>
    </div>
    <div class="col-sm-10">
        {{--Title--}}
        <div class="row subtitle">
            <p class="subtitle">Payment Methods</p>
        </div>
    </div>
</div>
    {{--Content--}}

@endsection

@section('js4page')
    <script>

    </script>
@endsection

@section('js4event')
    <script>


    </script>
@endsection
