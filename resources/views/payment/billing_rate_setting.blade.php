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
            width: 30%;
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
                <div class="col-sm-8">
                    <h1>Redirect Settings and Statistics </h1>
                </div>
                <div class="col-sm-2 text-right">
                    <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                </div>
                <div class="col-sm-2 text-right">
                    <a class="btn btn-wide btn-primary" href="#" id="id-export"><i class="fa fa-save"></i> Export</a>
                </div>
            </div>
            <div class="row" style="margin-left: 0; margin-right: 0;">
                <table class="table table-striped table-hover" id="user-table" style="width: 98%; margin: auto;">
                    <thead>
                        <tr>
                            <th class="">#</th>
                            <th class="">User Profile</th>
                            <th class="">Billing Profile ID</th>
                            <th class="url-width">Source URL</th>
                            <th class="">Description</th>
                            <th class="">Item ID#</th>
                            <th class="">Billing Currency</th>
                            <th class="">Budget</th>
                            <th class="">Default Rate Type</th>
                            <th class="">Default Rate per Clicks($)</th>
                            <th class="">Custom Rate per Clicks($)</th>
                            <th class="center">Modify</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close" id="close_button">&times;</span>
        <div class="panel-body" style="margin: auto; width: 60%; padding-top: 45px;">
            <select id="rate_type" >
                @foreach ($rate as $rate_type)
                    <option value="'{{$rate_type->rate_type}}'">{{$rate_type->rate_type}}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-3">
                <p class="cancelModal" id="cancel_button">Cancel</p>
            </div>
            <div class="col-sm-3">
                <p class="saveModal" id="ok_button">Ok</p>
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
        let textarea_id;
        jQuery(document).ready(function() {
            var usertable = $("#user-table").DataTable({
                "ajax": {
                    url: "<?=url('/get/billing-rate-setting');?>",
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
                    { name:"username", 	            data: "username",	 		        defaultContent:""},
                    { name:"billing_profile_id", 	data: "billing_profile_id",	 		defaultContent:""},
                    { name:"source", 	            data: "source",	 	                defaultContent:"",      render: dtRender_redirect},
                    { name:"hint", 	                data: "hint",	 	                defaultContent:""},
                    { name:"item_id", 	            data: "item_id",	 	            defaultContent:"",       render: dtRender_item},
                    { name:"currency", 	            data: "currency",	 	            defaultContent:""},
                    { name:"monthly_threshold", 	data: "monthly_threshold",	 	    defaultContent:""},
                    { name:"rate_type", 	        data: "rate_type",	 	            defaultContent:"",      render: dtRender_rate_type},
                    // { name:"rate_type", 	        data: "rate_type",	 	            defaultContent:""},
                    { name:"rate_per_click", 	    data: "rate_per_click",	 	        defaultContent:""},
                    { name:"click_cut", 	        data: "click_cut",	 	            defaultContent:"",       render:dtRender_click_rate},
                    { name:"tools", 			    data: "no",	 		                defaultContent:"",      render: dtRender_Edit_button_new}
                ],
                order: [[1, 'asc']],
                fnCreatedRow: function (row, data, index){
                    $(row).attr('id', data["store_id"]);
                }
            });



            usertable.on("draw.dt", function() {
                $("a[type=delete-url]").off("click").on("click", function() {
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Delete Redirect Url", "Are you sure you want to remove current redirect url", null, null, function() {
                        console.log(url_id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/redirect/delete')?>',
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
                    showConfirmMessage(null, "Deactive Redirect Url", "Are you sure you want to deactive current redirect url", null, null, function() {
                        console.log(url_id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/redirect/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'DeActive'
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

                $("a[type=active-url]").off("click").on("click", function() {
                    event.preventDefault();
                    var url_id = $(this).attr('url-id');
                    showConfirmMessage(null, "Active Redirect Url", "Are you sure you want to Active current redirect url", null, null, function() {
                        console.log(url_id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/redirect/active')?>',
                            data: {
                                store_id: url_id,
                                active: 'Active'
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
            });
            // $('#user-table tbody').on('click', 'tr', function () {
            //     let data = usertable.row( this ).data();
            //     console.log(data);
            // } );

            $('#user-table tbody').on('click', 'tr', function (){
                let data = usertable.row( this ).data();
                console.log(data);
                textarea_id = data.no;
                if($(".ratetype").is(":focus")){
                    $('#editModal').css('display', 'block');
                    $('#close_button').on('click', function(){
                        $('#editModal').css('display', 'none');
                    });

                    $('#cancel_button').on('click', function(){
                        $('#editModal').css('display', 'none');
                    });

                    let changed_value;
                    $('#ok_button').on('click', function(){
                        $('#editModal').css('display', 'none');
                        changed_value = $('#rate_type').val();
                        console.log(changed_value);
                        $('.ratetype').eq(textarea_id - 1).val(changed_value.substr(1, changed_value.length - 2));
                    });
                }
                // console.log(this.$('button'));

                // $('button', $(this)).on('click', function(){
                //     let rate_type = $('.ratetype').eq(textarea_id - 1).val();
                //     let click_cut = $('.click_cut').eq(textarea_id - 1).val();
                //
                //     $.ajax({
                //         type: 'post',
                //         url: '/billing_rate_setting/edit',
                //         data: {
                //             data: data,
                //         },
                //         dataType: "json",
                //         success: function (response) {
                //
                //         },
                //         error: function () {
                //             toastr.error('Server connection error');
                //         }
                //     });
                // });
            });

        });
        function save_bill(id) {
            let rate_type = $('.ratetype').eq(textarea_id - 1).val();
            let click_cut = $('.click_cut').eq(textarea_id - 1).val();
            console.log("teste");
            console.log(rate_type);
            console.log(click_cut);
            let formData = new FormData();
            let _token = "<?php echo e(csrf_token()); ?>";
            formData.append('_token',_token);
            formData.append('rate_type',rate_type);
            formData.append('click_cut',click_cut);
            formData.append('id',id);
            $.ajax({
                type: 'post',
                url: '/billing_rate_setting/edit',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if(response==1)
                        toastr.success('Data Saved');
                },
                error: function () {
                    toastr.error('Server connection error');
                }
            });
        }
    </script>

@endsection