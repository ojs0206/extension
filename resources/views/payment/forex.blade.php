@extends('layout.master')

@section('title')
    Billing & Payments
@endsection

@section('styles')
    <style>
        .url-width {
            width: 20% !important;
        }

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
            padding: 10px;
            border-right: 2px solid lightgrey;
        }

        .sidenav a {
            padding: 18px 8px 18px 15px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            color: black;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: blue;
        }

        .sidenav img {
            width: 30px;
            height: 30px;
            margin-right: 0;
            margin-bottom: auto;
            margin-top: auto;
            margin-left: 15px;
        }

        .sidenav p {
            font-size: 15px;
            margin: auto;
            font-weight: 400;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }

    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var height = document.getElementsByClassName('navbar')[0].clientHeight;
            var sidenav = document.getElementById('sidenav');
            sidenav.style.paddingTop = height + 5 + "px";
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-2">
            <div id="sidenav" class="sidenav">
                <div class="row" style="padding-left: 5%; padding-top: 8%; display: flex">
                    <img src="<?=asset('/assets/icon/home.png');?>">
                    <a href="<?=url('/payment');?>">
                        <p>Billing Profiles</p>
                    </a>
                </div>
                <div class="row" style="padding-left: 5%; display: flex">
                    <img src="<?=asset('/assets/icon/payment.png');?>">
                    <a href="<?=url('/payment/default_rate');?>">
                        <p>Default Rates</p>
                    </a>
                </div>
                <div class="row" style="padding-left: 5%; display: flex">
                    <img src="<?=asset('/assets/icon/payment_1.png');?>">
                    <a href="<?=url('/payment/billing_rate_setting');?>">
                        <p>Billing Rate Setting & Stats</p>
                    </a>
                </div>
                <div class="row" style="padding-left: 5%; display: flex">
                    <img src="<?=asset('/assets/icon/redirect.png');?>">
                    <a href="<?=url('/payment/invoice');?>">
                        <p>Invoice & Payments</p>
                    </a>
                </div>
                <div class="row" style="padding-left: 5%; display: flex">
                    <img src="<?=asset('/assets/icon/contact.png');?>">
                    <a href="<?=url('/payment/forex');?>">
                        <p>Forex Rates</p>
                    </a>
                </div>
                <div class="row" style="padding-left: 5%; display: flex">
                    <img src="<?=asset('/assets/icon/group.png');?>">
                    <a href="<?=url('/payment/forex');?>">
                        <p>Reporting</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
        </div>
    </div>


@endsection

@section('js4page')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
@endsection

@section('js4event')



@endsection