@extends('layout.base')

@section('title')
    Login
@endsection

@section('css4page')

@endsection

@section('body')
<!-- start: LOGIN -->
<div class="row">


    <div class="m col-sm-4 col-sm-offset-4" style = "margin-top: 150px">
        <p style="font-size: 30px" class="text-center">
            <strong>Welcome to Grid Redirection</strong>
        </p>
        <p class="text-center">
            Please enter your name and password to log in.
        </p>
        <!-- start: LOGIN BOX -->
        <div class="box-login">
            <form class="form-login" action="<?=url('sign-in');?>" method = "post">
                @if(isset($error))
                    <p class="text-danger">{{$error}}</p>
                @endif
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Username: </label>
                    <input type="text" class="form-control" name="username" placeholder="Username">
                </div>
                <div class="form-group form-actions">
                    <label>Password: </label>
                    <input type="password" class="form-control password" name="password" placeholder="Password">

                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-red btn-block">
                        Login
                    </button>
                </div>
            </form>
            <!-- start: COPYRIGHT -->
            <!-- end: COPYRIGHT -->
        </div>
        <!-- end: LOGIN BOX -->
    </div>
</div>
<!-- end: LOGIN -->

@endsection

@section('js4page')
@endsection

@section('js4event')
    <script>
    jQuery(document).ready(function() {

    });

    </script>
@endsection