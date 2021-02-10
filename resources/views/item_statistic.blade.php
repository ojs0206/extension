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

        table {
            border: 1px solid black;
            margin-top: 30px;
        }

        table th {
            text-align: center;
            font-size: 20px;
            padding: 10px;
            border: 1px solid black;
        }
        table tr td {
            text-align: center;
            font-size: 15px;
            padding: 7px;
            border: 1px solid black;
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
                        <a class="btn btn-wide btn-primary" href="#" id="id-back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <p style="margin-top: 50px; font-size: 35px;">Redirect URL List and Statistics</p>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Description</th>
                                    <th>Source URL</th>
                                    <th>Rate Type</th>
                                </tr>
                            </thead>
                            <tbody>
                            <p style="display: none;">{{$count = 0}}</p>
                            @foreach ($url_list as $url)
                                <tr>
                                    <td>{{++ $count}}</td>
                                    <td><a href="<?=url('/payment/billing_rate_setting');?>" style="color: blue;">{{$url -> hint}}</a></td>
                                    <td style="text-align: left !important;">{{$url -> source}}</td>
                                    <td>
                                        @if ($url -> budget_type == 0)
                                            {{$url -> rate_type}}
                                        @else
                                            Custom
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
        jQuery(document).ready(function() {
            $("#id-back").on("click", function (event) {
                window.history.back();
            });
        });
    </script>
@endsection
