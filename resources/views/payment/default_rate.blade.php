@extends('layout.master')

@section('title')
    Default Rate
@endsection

@section('css4page')
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
                    <div class="col-sm-6">
                        <h1>Default Rates </h1>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-create"><i class="fa fa-plus"></i> Create</a>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-export"><i class="fa fa-save"></i> Export</a>
                    </div>
                </div>
                <div class="row" style="margin-left: 0; margin-right: 0;">
                    <table class="table table-striped table-hover" id="billing-table" style="width: 98%; margin: auto;">
                        <thead>
                        <tr>
                            <th class="">#</th>
                            <th class="">Rate Type</th>
                            <th class="">Rate Name</th>
                            <th class="">Description</th>
                            <th class="">Country</th>
                            <th class="">Currency</th>
                            <th class="">Rate Per Click</th>
                            <th class="">Monthly Threshold</th>
                            <th class="">Edit/Detail</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade in" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="Quiz" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" id="id-user-form" action="/rate/create">
                {{csrf_field()}}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="cursor: move">
                            <button type="button" class="close" style="color: black !important;" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                            </button>
                            <h4 class="modal-title" id="id-modal-title"></h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label class="control-label" for="id-name"> Rate Type: </label>
                                <input type="text" class="form-control" id="id-name" name="ratetype"  required="true">
                                <label class="control-label" for="id-name"> Rate Name: </label>
                                <input type="text" class="form-control" id="id-name" name="ratename"  required="true">
                                <label class="control-label" for="id-name"> Description: </label>
                                <input type="text" class="form-control" id="id-name" name="description"  required="true">
                                <label class="control-label" for="id-name"> Country: </label>
                                <input type="text" class="form-control" id="id-name" name="country"  required="true">
                                <label class="control-label" for="id-name"> Currency: </label>
                                <select class="form-control custom-select" name="currency">
                                    <option value="AUD">AUD</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="NZD">NZD</option>
                                    <option value="CNY">CNY</option>
                                    <option value="CAD">CAD</option>
                                    <option value="GBP">GBP</option>
                                    <option value="JPY">JPY</option>
                                </select>
                                <label class="control-label" for="id-name"> Rate Per Click: </label>
                                <input type="text" class="form-control" id="id-name" name="rateperclick"  required="true">
                                <label class="control-label" for="id-name"> Monthly Threshold: </label>
                                <input type="text" class="form-control" id="id-name" name="monthlythreshold"  required="true">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btn-o" data-dismiss="modal" id="cancel-modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="id-btn-submit">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
@endsection

@section('js4event')
    <script>
        jQuery(document).ready(function() {
            var usertable = $("#billing-table").DataTable({
                "ajax": {
                    url: "<?=url('/get/all-rate');?>"
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
                    {name: "rate_type", data: "rate_type", defaultContent: ""},
                    {name: "rate_name", data: "rate_name", defaultContent: ""},
                    {name: "description", data: "description", defaultContent: ""},
                    {name: "country", data: "country", defaultContent: ""},
                    {name: "currency", data: "currency", defaultContent: ""},
                    {name: "rate_per_click", data: "rate_per_click", defaultContent: ""},
                    {name: "monthly_threshold", data: "monthly_threshold", defaultContent: ""},
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
                $("a[type=delete-url]").off("click").on("click", function () {
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Delete Default Rate", "Are you sure you want to remove current rate", null, null, function () {
                        console.log(url_id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/rate/delete')?>',
                            data: {
                                store_id: url_id
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status == "ok") {
                                    usertable.draw();
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

                $("a[type=deactive-url]").off("click").on("click", function () {
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Deactive Default Rate", "Are you sure you want to deactive current billing profile", null, null, function () {
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/rate/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'DeActive'
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status == "ok") {
                                    usertable.draw();
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
                    showConfirmMessage(null, "Active Default Rate", "Are you sure you want to Active current billing profile", null, null, function () {
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/rate/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'Active'
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status == "ok") {
                                    usertable.draw();
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

                $("#id-refresh").off("click").on("click", function() {
                    usertable.draw();
                });

                // $("a[type=more-url]").off("click").on("click", function() {
                //     var url_id = $(this).attr('url-id');
                //
                // });
            });

            $('#id-create').on("click", function (event) {
                $('#create-modal').css('display', 'block');
                $('.modal').modal({ keyboard: false,
                    show: true
                });
                // Jquery draggable
                $('.modal-content').draggable({
                    handle: ".modal-header"
                });
            });

            $('#cancel-modal').on("click", function (event) {
                $('#create-modal').css('display', 'none');
            });

            $('.close').on("click", function (event) {
                $('#create-modal').css('display', 'none');
            });
        });
    </script>
@endsection

@section('js4page')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
@endsection
