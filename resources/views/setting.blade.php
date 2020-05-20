@extends('layout.master')

@section('title')
    URL SETTING
@endsection

@section('page-summary')
    URL SETTING
@endsection

@section('description')

@endsection

@section('styles')
    <style>
        td.editCell {
            display: flex;
            align-items: center;
            justify-content: center
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

                <div class="col-sm-6">
                    <h1>URL List</h1>
                </div>
                <div class="col-sm-2 text-right">
                    @if($type != 'User')
                        <a class="btn btn-wide btn-primary" href="#" id="id-add-url"><i class="fa fa-plus"></i> Add URL</a>
                    @endif
                </div>

                <div class="col-sm-2 text-right">
                    <a class="btn btn-wide btn-primary" href="#" id="id-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                </div>

                <div class="col-sm-2 text-right">
                    <a class="btn btn-wide btn-primary" href="#" id="id-export"><i class="fa fa-save"></i> Export</a>
                </div>

            </div>

            <div class="panel-heading border-bottom">

                <div class="row" style="margin-left: 0; margin-right: 0;">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover table-full-width addborder" id="user-table" style="width: 98%; margin: auto;">
                            <thead>
                            <tr>
                                <th class="">NO</th>
                                <th class="">URL Path</th>
                                <th class="">User Profile</th>
                                <th class="">Manage URL Source</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade in" id="id-modal" tabindex="-1" role="dialog" aria-labelledby="Quiz" aria-hidden="true">
        <form method="post" enctype="multipart/form-data" id="id-url-form" >
            {{csrf_field()}}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                        <h4 class="modal-title" id="id-modal-title"></h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="control-label" for="id-url"> Content URL: </label>
                            <input type="text" class="form-control" id="id-url" name="url"  required="true">
                        </div>

                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="form-group ">
                                    <label class="control-label" for="id-manager"> URL Manager: </label>
                                    <select id="id-manager">
                                        @foreach($managers as $manager)
                                            <option value="<?=$manager->username;?>"><?=$manager->username;?></option>
                                        @endforeach
                                    </select>
                                    <input type = "text" id = "id-select_manager" name = "select_manager" style="display: none;">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="url_id" id="id-url-id">
                        <input type="hidden" name="width" id="id-width" >
                        <input type="hidden" name="height" id="id-height" >
                        <input type="hidden" name="image_path" id="id-path">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="id-btn-submit">
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('js4page')
    <script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?=asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js');?>"></script>
@endsection

@section('js4event')

    <script>
        var usertable;

        jQuery(document).ready(function() {

            usertable = $("#user-table").DataTable({
                "ajax": {
                    url: "<?=url('/get/all-urls');?>",
                },
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [5, 25, 50, 100],
                language: {
                    "search": "Filter Search:  "
                },
                columns: [
                    { name:"no", 				    data: "no",	 				        defaultContent:"",      orderable: false},
                    { name:"url", 	                data: "url",	 		            defaultContent:""},
                    { name:"username", 	            data: "username",	 	            defaultContent:""},
                    { name:"tools", 			    data: "no",	 		                defaultContent:"",      render: dtRender_tools,  "className" : "editCell"}
                ],
                order: [[2, 'asc']]
            });





            usertable.on("draw.dt", function() {

                $("[name = btnDeleteField]").off("click").on("click", function() {

                    event.preventDefault();
                    var id = $(this).data("id");
                    showConfirmMessage(null, "Delete Url", "Are you sure want to delete the url?", null, null, function() {
                        console.log(id);
                        $.ajax({
                            type: 'post',
                            url: '<?=url('/setting/delete')?>',
                            data: {
                                url_id: id
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




                $("[name = btnEditField]").on("click", function() {


                    event.preventDefault();
                    $("#id-img-preview").attr("src", "");
                    $("#id-image-changed").val("NO");

                    $("#id-modal").modal("show");
                    $("#id-modal-title").text("Edit URL");
                    $("#id-btn-submit").text('Save');
                    $("#id-manager").val('');
                    $("#id-url").val('');

                    var url_id = $(this).data("id");
                    $("#id-url-id").val(url_id);

                    $.ajax({
                        type: 'get',
                        url: '<?=url('/get/url-info')?>',
                        data: {
                            url_id: url_id
                        },
                        dataType: "json",
                        success: function (response) {

                            if(response.status == "ok") {

                                var info = response.data[0];
                                console.log(info.username);
                                $("#id-manager").val(info.username);

                                $("#id-url").val(info.url);
                                $("#id-path").val(info.image_path);


                            } else if(response.status == "fail") {
                                toastr.error(response.msg);
                            }
                        },
                        error: function () {

                            toastr.error('Server connectin error');
                        }
                    });
                });
            });

            $("#id-refresh").off("click").on("click", function() {
                usertable.draw();
            });

            $("#id-export").off("click").on("click", function() {
                window.location = "<?=url('/setting/report')?>";
            });



            $("#id-btn-submit").on("click", function (event) {
                event.preventDefault();
                var url;
                $("#id-select_manager").val($( "#id-manager option:selected" ).val());
                var btn_text = $("#id-btn-submit").text();
                //var image_change = $("#id-image-changed").val();
                if(btn_text == 'Save') {
                    url = '<?=url('/setting/edit')?>';
                }
                else {
                    url = '<?=url('/setting/add')?>';
                }
                var str = $("#id-url").val();
                if(str == '') {
                    return ;
                }


                var frmdata = new FormData($('#id-url-form')[0]);
                $.ajax({
                    url:url,
                    type:'post',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data:frmdata,
                    success:function(response){
                        if(response.status == "ok") {
                            $("#id-modal").modal("hide");
                            usertable.draw();
                        } else if(response.status == "fail") {
                            toastr.error(response.msg);
                        }
                    }
                });



            });




            $("#id-add-url").on("click", function (event) {
                console.log("CLICK TYPE");
                event.preventDefault();
                $("#id-img-preview").attr("src", "");
                $("#id-image-changed").val("NO");


                $("#id-modal").modal("show");
                $("#id-modal-title").text("Add URL");
                $("#id-btn-submit").text('Add');
                $("#id-manager").val('');
                $("#id-url").val('');

                var url_id = $(this).attr("url-id");




            });



            $("#imgLoad").on("click", function() {
                $("#id-img-preview").prop("src", $("#id-loading").val());
                console.log("Click Load");
                var str = $("#id-url").val();
                console.log(str);
                //str = getImageAPI(str);
                console.log(str);
                var user_id = '<?=session()->get(SESS_UID)?>';
                $.ajax({
                    type: 'get',
                    url: '<?=url('/get/image-path')?>',
                    data: {
                        url: str,
                        user_id: user_id
                    },
                    dataType: "json",
                    success: function (response) {

                        if(response.status == "ok") {

                            var info = response.image_path;
                            var whole_path = '<?=asset('/assets/images')?>/' + info;
                            var downloadingImage = new Image();
                            downloadingImage.onload = function(){
                                $("#id-img-preview").prop("src", this.src);
                                var width = this.naturalWidth;
                                var height = this.naturalHeight;
                                console.log(width + " " + height);
                                $("#id-width").val(width);
                                $("#id-height").val(height);
                            };
                            downloadingImage.src = whole_path;
                            $("#id-path").val(info);
                        } else if(response.status == "fail") {
                            toastr.error(response.msg);
                        }
                    },
                    error: function () {

                        toastr.error('Server connectin error');
                    }
                });


            });


            $("#id-image-file-input").change(function () {
                var reader = new FileReader();
                reader.onload = function (e) {



                }
                reader.readAsDataURL($(this)[0].files[0]);
            });


        });
    </script>

@endsection