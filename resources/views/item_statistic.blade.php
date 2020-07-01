@extends('layout.master')

@section('title')
    Item Statistic
@endsection

@section('description')

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

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-2">
            <div id="sidenav" class="sidenav">
            </div>
        </div>
        <div class="col-sm-10">
            <div class="" style="margin: auto !important;">
                <div class="row" style="margin-left: 0; margin-right: 0;">
                    <div class="col-sm-8">
                        <h1>Item Statistic - {{$id}} </h1>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-export"><i class="fa fa-save"></i> Export</a>
                    </div>
                </div>
                <div class="row" style="padding-left: 10%; padding-top: 10px;" id="summary">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4" style="border: 7px solid whitesmoke; padding-top: 1%; padding-bottom: 1%;">
                        <div id="chartClicks" style="height: 300px; width: 100%;"></div>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4" style="border: 7px solid whitesmoke; padding-top: 1%; padding-bottom: 1%;">
                        <div id="chartSpent" style="height: 300px; width: 100%;"></div>
                    </div>
                </div>

                <div class="row" style="padding-left: 10%; padding-top: 5%;" id="">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4" style="border: 7px solid whitesmoke; padding-top: 1%; padding-bottom: 1%;">
                        <p style="padding-bottom: 10%;"><font size="5" color="black">Budget</font>
                            <br><strong style="color: black; font-size: 3vw;">A$</strong><strong style="color: black; font-size: 3vw;" id="dailyBudget_1"></strong><font size="3" color="black">    daily average</font>
                            <br><strong style="color: black; font-size: 3vw;">A$</strong><strong style="color: black; font-size: 3vw;" id="monthlyBudget"></strong><font size="3" color="black">    monthly maximum</font>
                        </p>
                        <hr>
                        <button class="btn btn-primary" id="budgetEdit">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js4page')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
@endsection

@section('js4event')
    <script>

    </script>
@endsection
