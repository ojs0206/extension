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
             background-color: #fefefe;
             margin: auto;
             padding: 20px;
             border: 1px solid #888;
             width: 80%;
         }

         /* The Close Button */
         .close {
             color: #aaaaaa;
             float: right;
             font-size: 28px;
             font-weight: bold;
         }

         .close:hover,
         .close:focus {
             color: #000;
             text-decoration: none;
             cursor: pointer;
         }

         .cancelModal {
             color: dimgrey;
             font-size: 1vw;
             text-align: center;
             font-weight: bold;
         }

         .cancelModal:hover,
         .cancelModal:focus {
             color: blue;
         }

         .saveModal {
             color: dimgrey;
             font-size: 1vw;
             text-align: center;
             font-weight: bold;
         }

         .saveModal:hover,
         .saveModal:focus {
             color: blue;
         }

         /*The slider*/
         .slidecontainer {
             width: 100%;
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

         /*Table*/
         th {
             font-size: 1vw;
             color: black;
         }

         tr {
             border: 1px solid whitesmoke;
             padding-bottom: 1%;
         }

         .billing-form {
             padding-top: 5%;
             padding-bottom: 2%;
             width: 100%;
         }

        .billing-form-text {
            padding-top: 5%;
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
    @if($type != 'Admin')
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
                        <img src="<?=asset('/assets/icon/group.png');?>">
                        <a href="<?=url('/payment/forex');?>">
                            <p>Reporting</p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="" style="margin: auto !important;">
                    <div class="row" style="margin-left: 0; margin-right: 0;">
                        <div class="col-sm-6">
                            <h1>Billing Profiles </h1>
                        </div>
                        <div class="col-sm-1 text-right">
                            <a class="btn btn-wide btn-primary" href="<?=url('/payment/createBilling');?>" id="id-create"><i class="fa fa-plus"></i> Create</a>
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
                                <th class="">User Profile</th>
                                <th class="">Account #</th>
                                <th >Primary Email Address</th>
                                <th class="">Country</th>
                                <th class="">Phone Number</th>
                                <th class="">Payment Method</th>
                                <th class="">Default Rate Type</th>
                                <th class="">Edit/Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    @else
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
            <div class="" style="margin: auto !important;">
                <div class="row" style="margin-left: 0; margin-right: 0;">
                    <div class="col-sm-6">
                        <h1>Billing Profiles </h1>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="<?=url('/payment/createBilling');?>" id="id-create"><i class="fa fa-plus"></i> Create</a>
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
                            <th class="">User Profile</th>
                            <th class="">Account #</th>
                            <th >Primary Email Address</th>
                            <th class="">Country</th>
                            <th class="">Phone Number</th>
                            <th class="">Payment Method</th>
                            <th class="">Default Rate Type</th>
                            <th class="">Edit/Detail</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('js4page')
{{--Chart draw Canvas--}}
    <script>
        jQuery(document).ready(function() {
            var usertable = $("#billing-table").DataTable({
                "ajax": {
                    url: "<?=url('/get/all-billing');?>"
                },
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [5, 25, 50, 100],
                language: {
                    "search": "Filter Search: "
                },
                columns: [
                    { name:"no", 				    data: "no",	 				        defaultContent:"",      orderable: false},
                    { name:"profile_name", 	        data: "profile_name",	 		    defaultContent:""},
                    { name:"account_id", 	        data: "account_id",	 	            defaultContent:""},
                    { name:"email", 	            data: "email",	 		            defaultContent:""},
                    { name:"country", 	            data: "country",	 	            defaultContent:""},
                    { name:"phone", 	            data: "phone",	 	                defaultContent:""},
                    { name:"payment_method", 	    data: "payment_method",	 	        defaultContent:""},
                    { name:"rate_type", 	        data: "rate_type",	 	            defaultContent:""},
                    { name:"tools", 			    data: "no",	 		                defaultContent:"",      render: dt_Render_billing,  "className" : "editCell center"}
                ],
                order: [[1, 'asc']]
            });

            usertable.on("draw.dt", function() {
                $("a[type=delete-url]").off("click").on("click", function() {
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Delete Billing Profile", "Are you sure you want to remove current billing profile", null, null, function() {
                        console.log(url_id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/payment/deleteBilling')?>',
                            data: {
                                store_id: url_id
                            },
                            dataType: "json",
                            success: function (response) {
                                console.log(response);

                                if(response.status == "ok") {
                                    usertable.draw();
                                } else if(response.status == "fail") {
                                    toastr.error(response.msg);
                                }
                            },
                            error: function () {
                                toastr.error('Server connection error');
                            }
                        });
                    });
                });

                $("a[type=deactive-url]").off("click").on("click", function() {
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Deactive Billing Profile", "Are you sure you want to deactive current billing profile", null, null, function() {
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/billing/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'DeActive'
                            },
                            dataType: "json",
                            success: function (response) {
                                if(response.status == "ok") {
                                    usertable.draw();
                                } else if(response.status == "fail") {
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

                $("#id-export").off("click").on("click", function() {
                    window.location = "<?=url('/payment/report')?>";
                });

                $("a[type=more-url]").off("click").on("click", function() {
                    var url_id = $(this).attr('url-id');
                    window.location.href = "<?=url('/payment/editBilling?url_id=')?>" + '' + url_id;
                });

                $("a[type=active-url]").off("click").on("click", function() {
                    event.preventDefault();
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Active Billing Profile", "Are you sure you want to Active current billing profile", null, null, function() {
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/billing/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'Active'
                            },
                            dataType: "json",
                            success: function (response) {
                                if(response.status == "ok") {
                                    usertable.draw();
                                } else if(response.status == "fail") {
                                    toastr.error(response.msg);
                                }
                            },
                            error: function () {
                                toastr.error('Server connection error');
                            }
                        });
                    });
                });
            });
            $('#billing-table tbody').on('click', 'tr', function () {
                var data = usertable.row( this ).data();
                console.log(data);
            } );

        });

    </script>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://www.paypalobjects.com/api/checkout.js" data-version-4 log-level="warn"></script>

    <!-- Load the client component. -->
    <script src="https://js.braintreegateway.com/web/3.54.2/js/client.min.js"></script>

    <!-- Load the PayPal Checkout component. -->
    <script src="https://js.braintreegateway.com/web/3.54.2/js/paypal-checkout.min.js"></script>
@endsection

@section('js4event')
    <script class="iti-load-utils" async="" src="<?=asset('build/js/utils.js?1575016932390');?>"></script>
    <script src="<?=asset('js/phone/prism.js');?>"></script>
    <script src="<?=asset('js/phone/intlTelInput.js?1575016932390');?>"></script>
    <script src="<?=asset('js/phone/nationalMode.js?1575016932390');?>"></script>
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
@endsection