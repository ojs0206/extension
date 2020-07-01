@extends('layout.master')

@section('title')
    Invoice & Payments
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
        .search_title h5{
            font-weight: bold;
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
                    <div class="col-sm-8">
                        <h1>Invoice and Payments </h1>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                    <div class="col-sm-1 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-export"><i class="fa fa-save"></i> Export</a>
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
                                <h5>Invoice period</h5>
                            </div>
                            <div class="col-sm-3">
                                <h5>Invoice #</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin: 0 0 25px 0;">
                    <div class="col-sm-12 col-md-9" style="background-color: #efeff0; padding-top: 5px; padding-bottom: 5px">
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
                                <input class="form-control date_ranger" type="text" id="period" name="period"/>
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" id="invoice_name" name="invoice_name"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3" style="padding: 5px">
                        <button type="button" class="btn btn-wide btn-primary" onclick="filterData()">SEARCH</button>
                        <button type="button" class="btn btn-wide btn-primary" onclick="initializeData()">CLEAR</button>
                    </div>
                </div>
                <div class="row" style="margin-left: 0; margin-right: 0;position: relative">
                    <table class="table table-striped table-hover" id="user-table" style="width: 98%; margin: auto;">
                        <thead>
                        <tr>
                            <th class="">#</th>
                            <th class="">User Profile</th>
                            <th class="">Billing Profile ID</th>
                            <th class="">Billing Currency</th>
                            <th class="">Invoice Month</th>
                            <th class="">Invoice Value</th>
                            <th class="">Payment Method</th>
                            <th class="">Payment Date</th>
                            <th class="">Pay</th>
                            <th class="">Receipt #</th>
                            <th class="">Statement</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="" style="position: absolute;top:-16px;right: 0.5rem;width: 8rem;text-align: center">
                        <p style="font-size: 10px;margin-bottom: 0">Adobe Reader is required to view these statements.</p>
                        <img src="{{asset('/images/Adobe-reader.png')}}" style="width: 80px; height: 30px">
                    </div>
                </div>
                <form action="/pay_invoice" method="post" id="frm-invoice" name="frm-invoice" target="TheWindow">
                    {{csrf_field()}}
                    <input type="hidden" name="transaction_id" id="transaction_id"/>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js4page')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
    <script src="<?=asset('js/moment.js');?>"></script>
    <script src="<?=asset('bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
@endsection


@section('js4event')
    <script>
        let usertable = null;
        jQuery(document).ready(function() {
            $('.date_ranger').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                startDate: moment('2015-01-01'),
                endDate:moment(),
                "locale": {
                    "format": "DD/MM/YYYY",
                    "separator": " - ",
                    "applyLabel": "Apply",
                    "cancelLabel": "Cancel",
                    "fromLabel": "From",
                    "toLabel": "To",
                    "customRangeLabel": "Custom",
                    "daysOfWeek": [
                        "Su",
                        "Mo",
                        "Tu",
                        "We",
                        "Th",
                        "Fr",
                        "Sa"
                    ],
                    "monthNames": [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December"
                    ],
                    "firstDay": 1
                },
                ranges: {
                    'Most recent':[moment('2015-01-01'),moment()],
                    'Last 3 Months': [moment().subtract(3,'months'), moment()],
                    'Last 6 Months': [moment().subtract(6,'months'), moment()],
                    'Last 9 Months': [moment().subtract(9,'months'), moment()],
                    'Last 12 Months': [moment().subtract(12,'months'), moment()],
                    'This financial year': [moment().startOf('year'), moment()],
                    'Last financial year': [moment().subtract(1,'years').startOf('year'), moment().subtract(1,'years').endOf('year')],
                }
            });

            usertable = $("#user-table").DataTable({
                ajax: {
                    type: "POST",
                    url: "<?=url('/get/invoice');?>",
                    data: function (d) {
                        let _val = "{{csrf_token()}}";
                        let user_profile = $('#user_profile').val();
                        let bill_id = $('#bill_id').val();
                        let invoice_name = $('#invoice_name').val();
                        let date = $("#period").val();
                        let values = date.split('-');
                        let start = values[0];
                        let end = values[1];
                        d.user_profile = user_profile;
                        d.bill_id = bill_id;
                        d.invoice_name = invoice_name;
                        d.start =  start;
                        d.end = end;
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
                order: [[1, 'asc']]
            });

            usertable.on("draw.dt", function () {

            });
        });
        function filterData() {
            usertable.ajax.reload();
        }
        function initializeData() {
            $('#user_profile').val("");
            $('#bill_id').val("");
            $('#invoice_name').val("");
            $('.date_ranger').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                startDate: moment('2015-01-01'),
                endDate:moment(),
                "locale": {
                    "format": "DD/MM/YYYY",
                    "separator": " - ",
                    "applyLabel": "Apply",
                    "cancelLabel": "Cancel",
                    "fromLabel": "From",
                    "toLabel": "To",
                    "customRangeLabel": "Custom",
                    "daysOfWeek": [
                        "Su",
                        "Mo",
                        "Tu",
                        "We",
                        "Th",
                        "Fr",
                        "Sa"
                    ],
                    "monthNames": [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December"
                    ],
                    "firstDay": 1
                },
                ranges: {
                    'Most recent':[moment('2015-01-01'),moment()],
                    'Last 3 Months': [moment().subtract(3,'months'), moment()],
                    'Last 6 Months': [moment().subtract(6,'months'), moment()],
                    'Last 9 Months': [moment().subtract(9,'months'), moment()],
                    'Last 12 Months': [moment().subtract(12,'months'), moment()],
                    'This financial year': [moment().startOf('year'), moment()],
                    'Last financial year': [moment().subtract(1,'years').startOf('year'), moment().subtract(1,'years').endOf('year')],
                }
            });
            usertable.ajax.reload();
        }

        function payInvoice(id,pp) {
            if(pp==1){
                $('#transaction_id').val(id);
                window.open('/pay_invoice', 'TheWindow', 'width=900,height=900');
                document.getElementById('frm-invoice').submit();
            }
            else{
                let formData = new FormData();
                let _token = "{{ csrf_token() }}";
                formData.append('_token',_token);
                formData.append('transaction_id',id);
                $.ajax({
                    type: "POST",
                    url: '<?=url('/pay_invoice')?>',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (resp) {
                        if(resp!=1)
                            window.location.href = resp;
                        else
                            swal("Done", "You paid the invoice.", "success");
                        swal({
                            title: "Done",
                            text: "You paid the invoice.",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#00dd57",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }, function(isConfirm) {
                            if(isConfirm) {
                                window.location.reload();
                            }
                        });
                    },
                    error: function () {
                        swal("Notice", "Server Error.", "error");
                    }
                });
            }
        }

        function generatePDF(id) {
            let formData = new FormData();
            let _val = "{{csrf_token()}}";
            formData.append('_tocken',_val);
            formData.append('id',id);
            $.ajax({
                url:'{{url("/payment/invoice/generatePDF")}}',
                type:"POST",
                data:formData,
                processData:false,
                contentType:false,
                success:function (resp) {
                    if(resp.result){
                        if(resp.url != null)
                            window.open(resp.url,"_blank");
                    }
                    else{
                        alert("Server Error");
                    }
                }
            })
        }
    </script>
@endsection