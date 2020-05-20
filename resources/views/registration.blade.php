@extends('layout.base')

@section('title')
    Register
    @endsection

    @section('css4page')

    @endsection

    @section('body')
            <!-- start: REGISTRATION -->

    <div class="row">
        <div class="main-login col-sm-offset-4 col-sm-4" style="margin-top: 100px;">
            <h2 class="text-center text-dark text-bold  margin-top-15">
                Sign Up
            </h2>
            <!-- start: REGISTER BOX -->
            <div class="box-register">
                <form role="form" class="form-register" action = "{{url('/registration/CreateUser')}}" method = "post" id = "frmProject" target="resp">
                    @if(isset($error))
                        <h4 class="text-danger">{{$error}}</h4>
                    @endif
                    {{ csrf_field() }}

                    <div class="form-group">
                        <div class = "row">
                            <label class = "col-sm-4"style="text-align: right"> UserName:</label>
                            <div class = "col-sm-8">
                                <input type="text" class="form-control" name="username" id = "username" placeholder="User Name" style="float: right">
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <div class = "row">
                            <label class = "col-sm-4"style="text-align: right"> Password:</label>
                            <div class = "col-sm-8">
                                <input type="password" class="form-control" name="password" id = "password" placeholder=""style="float: right">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class = "row">
                            <label class = "col-sm-4 control-label"style="text-align: right"> RepeatPassword:</label>
                            <div class = "col-sm-8">
                                <input type="password" class="form-control" name="repeat_password" id = "repeat_password" placeholder=""style="float: right">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">

                        <div class = "row ">
                            <div class = "col-sm-offset-6 col-sm-4">
                                <button type="submit" id = "signup" class="btn btn-red btn-block col-sm-4">
                                    Sign Up
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">

                        <div class = "col-sm-offset-4">
                            <p class="text-center">
                                Already have an account?
                                <a href="<?=url('login')?>"><strong> Log-in </strong></a>
                            </p>
                        </div>

                    </div>

                    <hr/>
                </form>
                <!-- start: COPYRIGHT -->
                <!-- end: COPYRIGHT -->
            </div>
            <!-- end: REGISTER BOX -->
        </div>
    </div>
    <iframe id="resp" name="resp" style="display: none">

    </iframe>
    <!-- end: REGISTRATION -->
@endsection

@section('js4page')
@endsection

@section('js4event')
    <script>

        jQuery(document).ready(function() {
        });

        $("#resp").on("load", function() {
            var ret = $(this).contents().find("body").html(), resp = '';
            var length = ret.length;
            for(var i = 0; i < length; i ++) {
                if(ret[i] == '{') {
                    for(var j = i; j < length; j ++) {
                        resp += ret[j];
                        if(ret[j] == '}') {
                            break;
                        }
                    }
                    break;
                }
            }
            console.log(resp);
            var obj = JSON.parse(resp);
            if(obj.type != "ok") {
                showToastrMsgFail(obj.msg);
            }
            else {
                showToastrMsgSuccess(obj.msg);
                window.location.replace("<?=url('login')?>");
            }
            console.log(resp);




        });

    </script>
@endsection