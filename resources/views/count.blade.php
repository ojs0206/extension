@extends('layout.master')

@section('title')
    COUNT
@endsection

@section('page-summary')
    COUNT
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
                            <th class="url-width">Source URL</th>
                            <th class="center">Image Source</th>
                            <th class="url-width">Redirect URL</th>
                            <th class="">Description</th>
                            <th class="">Item ID#</th>
                            <th class="">Created Date <img src="<?=asset('images/sort.png');?>" style="width: 30%; height: 60%;"> </th>
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
    



@endsection

@section('js4page')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
@endsection

@section('js4event')

    <script>
        jQuery(document).ready(function() {
            var usertable = $("#user-table").DataTable({
                "ajax": {
                    url: "<?=url('/get/all-redirect');?>",
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
                    { name:"url", 	                data: "url",	 		            defaultContent:"",      render: dtRender_redirect},
                    { name:"source", 	            data: "source",	 	                defaultContent:"",      render: dtRender_img, "className" : "center"},
                    { name:"redirect_url", 	        data: "redirect_url",	 	        defaultContent:"",      render: dtRender_redirect},
                    { name:"hint", 	                data: "hint",	 	                defaultContent:""},
                    { name:"item_id", 	            data: "item_id",	 	            defaultContent:"",       render: dtRender_item},
                    { name:"create_date", 	        data: "create_date",	 	        defaultContent:""},
                    { name:"tools", 			    data: "no",	 		                defaultContent:"",      render: dtRender_count,  "className" : "editCell center"}
                ],
                order: [[1, 'asc']]
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

                $("#id-refresh").off("click").on("click", function() {
                    usertable.draw();
                });

                $("#id-export").off("click").on("click", function() {
                    window.location = "<?=url('/redirect/report')?>";
                });

                $("a[type=more-url]").off("click").on("click", function() {
                    var url_id = $(this).attr('url-id');
                    window.location.href = "<?=url('/count/detail?store_id=')?>" + '' + url_id;
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
            $('#user-table tbody').on('click', 'tr', function () {
                var data = usertable.row( this ).data();
                console.log(data);
            } );




        });
    </script>

@endsection