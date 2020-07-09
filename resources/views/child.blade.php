@extends('layout.master')

@section('title')
    Edit Child
@endsection

@section('page-summary')
    Edit Child
@endsection

@section('description')

@endsection

@section('styles')
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
                    <div class="col-sm-10">
                        <h1>User Profile</h1>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a class="btn btn-wide btn-primary" href="#" id="id-add-child"><i class="fa fa-plus"></i>Add Profile</a>
                    </div>
                </div>

                <div class="row" style="margin-left: 0; margin-right: 0;">
                    <table class="table table-striped table-hover" id="user-table" style="width: 98%; margin: auto;">
                        <thead>
                        <tr>
                            <th class="">NO</th>
                            <th class="">Name</th>
                            <th>Company</th>
                            @if($type == 'Admin')
                                <th class="">Manage User</th>
                            @endif
                            <th class="">Type</th>
                            <th>E-mail</th>
                            <th>Account #</th>
                            @if($type != 'User')
                                <th class="center">Edit</th>
                            @endif
                            <th>Test</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $index => $user)
                            <tr>
                                <td class=""><?=($index + 1)?></td>

                                <td>
                                    <?=$user->username?>
                                </td>
                                <td>
                                    <?=$user->company?>
                                </td>
                                @if($type == 'Admin')
                                    <td>
                                        <?=$user->parent_name?>
                                    </td>
                                @endif

                                <td class="">
                                    <?=$user->type?>
                                </td>
                                <td>
                                    <?=$user->email?>
                                </td>
                                <td>
                                    <?=$user->account_id?>
                                </td>
                                @if($type != 'User')
                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <a href="#" user-id="<?=$user->id?>" user-name="<?=$user->username?>" user-password="<?=$user->password?>" user-email="<?=$user->email?>" user-company="<?=$user->company?>" user-type="<?=$user->type?>" type="edit-user" class="btn btn-transparent btn-xs tooltips"  data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil fa fa-white"></i></a>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group dropdown ">
                                                <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right dropdown-light" role="menu">
                                                    <li>
                                                        <a href="#" user-id="<?=$user->id?>" type="edit-user" >Update</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td>
                                    <button class="btn btn-primary" id="test_button" onclick="sendEmail({{$user->id}})">Test</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="container">

    </div>


    <div class="modal fade in" id="id-modal" tabindex="-1" role="dialog" aria-labelledby="Quiz" aria-hidden="true">
        <form method="post" enctype="multipart/form-data" id="id-user-form" >
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
                            <label class="control-label" for="id-name"> User Name: </label>
                            <input type="text" class="form-control" id="id-name" name="username"  required="true">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id-password"> Password: </label>
                            <input type="password" class="form-control" name="password" id = "id-password" required="true">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id-repeat"> RepeatPassword: </label>
                            <input type="password" class="form-control" name="repeat_password" id = "id-repeat" required="true">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id-repeat"> E-mail: </label>
                            <input type="email" class="form-control" name="email" id = "id-email" required="true">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id-company"> Company: </label>
                            <input type="text" class="form-control" id="id-company" name="company"  required="true">
                        </div>
                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="form-group ">
                                    <label class="control-label" for="id-type"> User Type: </label>
                                    <select id="id-type">
                                        @if($type == 'Admin')
                                            <option value="Manager">Manager</option>
                                        @endif
                                            <option value="User">User</option>
                                    </select>
                                    <input type = "text" id = "id-select-type" name = "select_type" style="display: none;">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" id="id-user-id">
                        <input type="hidden" name="type_status" id="id-user-type">

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

@endsection

@section('js4event')

    <script>
        function sendEmail(id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('/sendEmail')}}",
                method:"POST",
                data:{userid:id},
                success: function( resp ) {
                    if(resp.result){
                        if(resp.success)
                            toastr.success("Mail sent.");
                        else
                            toastr.error("Server Error");
                    }
                    else
                        toastr.error("Connection Error")
                }
            });
        }
        jQuery(document).ready(function() {
            var select_user;
            var select_type;
            $("a[type=edit-user]").click(function (event) {
                event.preventDefault();
                var user_id = $(this).attr('user-id');
                var name = $(this).attr('user-name');
                var pass = $(this).attr('user-password');
                var email = $(this).attr('user-email');
                var company = $(this).attr('user-company');
                var type = $(this).attr('user-type');
                select_user = user_id;
                select_type = type;

                $("#id-modal-title").text("Update Child");
                $("#id-btn-submit").text('Update');
                $("#id-name").val(name);
                $("#id-password").val(pass);
                $("#id-repeat").val(pass);
                $("#id-email").val(email);
                $("#id-company").val(company);
                $("#id-type").val(type);
                {
                    $( "option" ).each(function( index ) {
                        var value = $(this).val();
                        if(value == 'User') {
                            if(type == 'Manager') {
                                $(this).css("display","none");
                            } else {
                                $(this).css("display","initial");
                            }
                        }
                        console.log( index + ": " + $( this ).val() );
                    });
                }

                $("#id-user-id").val(user_id);
                $("#id-modal").modal("show");
            });

            $("#id-btn-submit").on("click", function (event) {
                console.log("Click SUbmit button");
                event.preventDefault();
                var url;
                var name = $("#id-name").val();
                var password = $("#id-password").val();
                var repeat_password = $("#id-repeat").val();
                var email = $("#id-email").val();
                var type = $("#id-type").val();
                var company = $("#id-company").val();
                console.log("ASVD" + type);
                $("#id-select-type").val(type);
                if(name === '') {
                    toastr.error("Required user name");
                    return;
                }
                if(password === '') {
                    toastr.error("Required password");
                    return;
                }

                if (email === '') {
                    toastr.error("Required email");
                    return;
                }

                if (company === '') {
                    toastr.error("Required company");
                    return;
                }

                if(password !== repeat_password) {
                    toastr.error("Password is not correctly");
                    return ;
                }
                //var image_change = $("#id-image-changed").val();

                var url = '<?=url('/child/add')?>';
                var txt = $("#id-btn-submit").text();
                if(txt === 'Update') {
                    url = '<?=url('/child/update')?>';
                }

                var frmdata = new FormData($('#id-user-form')[0]);

                $.ajax({
                    url:url,
                    type:'post',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data:frmdata,
                    success:function(response){
                        if(response.status == "ok") {
                            window.location.reload();
                        } else if(response.status == "fail") {
                            toastr.error(response.msg);
                        }
                    }
                });
            });


            $("a[type=edit-user]").on("click", function (event) {

            });

            $("#id-add-child").on("click", function (event) {
                event.preventDefault();

                $("#id-name").val('');
                $("#id-password").val('');
                $("#id-repeat").val('');
                $("#id-email").val('');
                $("#id-type").val('');
                $("#id-modal-title").text("Add Child");
                $("#id-btn-submit").text('Add');
                $( "option" ).each(function( index ) {
                    var value = $(this).val();
                    if(value == 'User') {
                        $(this).css("display","initial");
                    }
                    console.log( index + ": " + $( this ).val() );
                });
                $("#id-modal").modal("show");
            });
        });
    </script>

@endsection