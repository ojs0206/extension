@extends('layout.master')

@section('title')
    Budget Setting
@endsection

@section('styles')
    <link rel="stylesheet" href="<?=asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')?>">
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

        .search_title h5 {
            font-weight: bold;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var height = document.getElementsByClassName('navbar')[0].clientHeight;
            console.log(height);
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
                        <p>Billing Rate Setting</p>
                    </a>
                </div>
                <div class="row" style="padding-left: 5%; display: flex">
                    <img src="<?=asset('/assets/icon/card.png');?>">
                    <a href="<?=url('/payment/budget_setting');?>">
                        <p>Budget Setting</p>
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
                    <a href="">
                        <p>Forex Rates</p>
                    </a>
                </div>
                <div class="row" style="padding-left: 5%; display: flex">
                    <img src="<?=asset('/assets/icon/group.png');?>">
                    <a href="">
                        <p>Reporting</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="" style="margin: auto !important;">
                <div class="row" style="margin-left: 0; margin-right: 0;">
                    <div class="col-sm-8">
                        <h1>Budget Setting </h1>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-arrow-left"></i>
                            Back</a>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-refresh"></i>
                            Refresh</a>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-export"><i class="fa fa-save"></i>
                            Export</a>
                    </div>
                </div>
                <div class="row" style="margin: 15px 0 0 0;">
                    <div class="col-sm-12 col-md-9">
                        <div class="row search_title">
                            <div class="col-sm-2 col-sm-offset-1">
                                <h5>User profile</h5>
                            </div>
                            <div class="col-sm-3">
                                <h5>Billing Profile ID</h5>
                            </div>
                            <div class="col-sm-3">
                                <h5>Item ID#</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin: 0 0 25px 0;">
                    <div class="col-sm-12 col-md-9"
                         style="background-color: #efeff0; padding-top: 5px; padding-bottom: 5px">
                        <div class="row">
                            <div class="col-sm-1" style="text-align: right">
                                <h5 style="margin: 10px 0">Search</h5>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" id="user_profile" name="user_profile"/>
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" id="bill_id" name="bill_id"/>
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" id="item_id" name="item_id"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3" style="padding: 5px">
                        <button type="button" class="btn btn-wide btn-primary" onclick="filterData()">SEARCH</button>
                        <button type="button" class="btn btn-wide btn-primary" onclick="initializeData()">CLEAR</button>
                    </div>
                </div>
                <div class="row" style="margin-left: 0; margin-right: 0;position: relative">
                    <table class="table table-striped table-hover" id="billing-table" style="width: 98%; margin: auto;">
                        <thead>
                        <tr>
                            <th class="">#</th>
                            <th class="">User Profile</th>
                            <th class="">Billing Profile ID</th>
                            <th class="">Description</th>
                            <th class="">Item ID#</th>
                            <th class="">Billing Currency</th>
                            <th class="">Billing Profile Budget</th>
                            <th class="">Item ID# Budget</th>
                            <th class="">Set Budget</th>
                            <th class="">Edit/Detail</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js4page')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
    <script src="<?=asset('js/moment.js');?>"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="<?=asset('bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
@endsection

@section('js4event')
    <script>
        let usertable = null;
        jQuery(document).ready(function () {
            usertable = $("#billing-table").DataTable({
                "ajax": {
                    type: "POST",
                    url: "<?=url('/get/budget-setting');?>",
                    data: function (d) {
                        let _val = "{{csrf_token()}}";
                        let user_profile = $('#user_profile').val();
                        let bill_id = $('#bill_id').val();
                        let item_id = $('#item_id').val();
                        d.user_profile = user_profile;
                        d.bill_id = bill_id;
                        d.item_id = item_id;
                        d._token = _val;
                    },
                },
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [5, 25, 50, 100],
                language: {
                    "search": "Filter Search: "
                },
                columns: [
                    {name: "no", data: "no", defaultContent: "", orderable: false},
                    {name: "username", data: "username", defaultContent: ""},
                    {name: "billing_profile_id", data: "billing_profile_id", defaultContent: ""},
                    {name: "hint", data: "hint", defaultContent: ""},
                    {
                        name: "item_id",
                        data: "item_id",
                        defaultContent: "",
                        render: dtRender_item,
                        "className": "editCell center"
                    },
                    {name: "currency", data: "currency", defaultContent: ""},
                    {
                        name: "monthly_threshold",
                        data: "monthly_threshold",
                        defaultContent: "",
                        render: dt_Render_budget,
                        "className": "editCell center"
                    },
                    {
                        name: "budget",
                        data: "budget",
                        defaultContent: "",
                        render: dt_Render_item_budget,
                        "className": "editCell center"
                    },
                    {
                        name: "monthly_threshold",
                        data: "monthly_threshold",
                        defaultContent: "",
                        render: dt_Render_set_budget,
                        "className": "editCell center"
                    },
                    {
                        name: "tools",
                        data: "no",
                        defaultContent: "",
                        render: dt_Render_rate,
                        "className": "editCell center"
                    }
                ],
                order: [[1, 'asc']]
            });

            usertable.on("draw.dt", function () {

                $("a[type=deactive-url]").off("click").on("click", function () {
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Deactive Budget Setting", "Are you sure you want to deactive current budget setting", null, null, function () {
                        console.log(url_id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/budget_setting/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'DeActive'
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status == "ok") {
                                    // usertable.draw();
                                    filterData();
                                } else if (response.status == "fail") {
                                    toastr.error(response.msg);
                                }
                            },
                            error: function () {
                                toastr.error('Server connection error');
                            }
                        });
                    });
                });

                $("a[type=active-url]").off("click").on("click", function () {
                    event.preventDefault();
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Active Budget Setting", "Are you sure you want to Active current budget setting", null, null, function () {
                        console.log(url_id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/budget_setting/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'Active'
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status == "ok") {
                                    // usertable.draw();
                                    filterData();
                                } else if (response.status == "fail") {
                                    toastr.error(response.msg);
                                }
                            },
                            error: function () {
                                toastr.error('Server connection error');
                            }
                        });
                    });
                });

                $("#id-refresh").off("click").on("click", function () {
                    usertable.draw();
                    filterData();
                });
            });

        });

        function filterData() {
            usertable.ajax.reload();
        }

        $(document).on('change','input[type=radio]',function(){
            let name = $(this).prop('name');
            let index = name.substr(name.indexOf("_")+1);
            let value = $(this).val();

            $.ajax({
                type: "POST",
                url: '<?=url('/budget_setting/savetype')?>',
                dataType: "json",
                data: {
                    store_id: index,
                    type: value
                },
                success: function (resp) {
                    if(resp.result){
                        if(!resp.is_set)
                            toastr.error('Cannot find record');
                    }
                    else{
                        toastr.error('Server connection error');
                    }
                },
                error: function () {
                    toastr.error('Server connection error');
                }
            });
        });

        function check_radio(storeID, index) {
            $('#set'+index).prop('checked',true);
        }

    </script>
@endsection
