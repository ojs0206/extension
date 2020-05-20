<!DOCTYPE html>
<!-- Template Name: Packet - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9"><![endif]-->
<!--[if !IE]><!-->
<html>
<!--<![endif]-->
<!-- start: HEAD -->
<head>
    <title>@yield('title')-Grid</title>
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="_csrf" content="{{csrf_token()}}"/>
    <meta name="_csrf_header" content="_token"/>
    <!-- end: META -->
    <!-- start: GOOGLE FONTS -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <!-- end: GOOGLE FONTS -->
    <!-- start: MAIN CSS -->
    <link rel="stylesheet" href="<?=asset('bower_components/bootstrap/dist/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/font-awesome/css/font-awesome.min.css')?>">

    <link rel="stylesheet" href="<?=asset('bower_components/themify-icons/themify-icons.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/flag-icon-css/css/flag-icon.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/animate.css/animate.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/switchery/dist/switchery.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/ladda/dist/ladda-themeless.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/slick.js/slick/slick.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/slick.js/slick/slick-theme.css')?>">
    <link rel="stylesheet" href="<?=asset('assets/css/loading_spinner.css')?>">
    <link rel="stylesheet" href="<?=asset('assets/css/styles.css')?>">
    <link rel="stylesheet" href="<?=asset('assets/css/plugins.css')?>">
    <link rel="stylesheet" href="<?=asset('assets/css/themes/lyt1-theme-1.css')?>" id="skin_color">
    <link rel="stylesheet" href="<?=asset('bower_components/sweetalert/dist/sweetalert.css');?>">
    <link rel="stylesheet" href="<?=asset('bower_components/toastr/toastr.min.css');?>">
    <link href=<?=asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.min.css');?> rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?=asset('bower_components/sweetalert/dist/sweetalert.css');?>">

    <link rel="stylesheet" href="<?=asset('css/prism.css')?>">
    <link rel="stylesheet" href="<?=asset('css/intlTelInput.css?1575016932390')?>">
    <link rel="stylesheet" href="<?=asset('css/demo.css?1575016932390')?>">

    <!-- end: MAIN CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('css4page')
    <link rel="shortcut icon" href="<?=asset('favicon.ico');?>" />
    @yield('styles')
    <script src="<?=asset('jquery-1.9.1.min.js');?>"></script>

    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        nav {
            z-index: 99999;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .payment-dropdown {
            display: none;
            position: absolute;
            background-color: navajowhite;
            color: black;
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
    </style>
</head>
<!-- end: HEAD -->
<!-- start: BODY -->
<body>

<input type="hidden" id="asset_path" value="<?=asset('');?>">
<input type="hidden" id="home_path" value="<?=url('');?>">

<input id="avatar_path" type="hidden" value="<?=config("filesystems.disks.user_avatar_url");?>"/>
<input id="avatar_admin_path" type="hidden" value="<?=asset('assets/images/avatar/s');?>/"/>
<input id="default_admin_path" type="hidden" value="<?=asset('assets/images/');?>/"/>

@yield('body')

        <!-- start: MAIN JAVASCRIPTS -->
<script src="<?=asset('bootstrap/js/bootstrap.min.js');?>"></script>
<script src="<?=asset('js/common.js');?>"></script>
<script src="<?=asset('js/md5.min.js');?>"></script>
<!--\bower_components\jquery.knobe       bootstrap-timepicker.js-->
<!-- end: MAIN JAVASCRIPTS -->
<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
@yield('js4page')
        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<!-- start: Packet JAVASCRIPTS -->

<script src="<?=asset('js/letter-icons.js');?>"></script>
<script src="<?=asset('js/toastr.js');?>"></script>
<script src="<?=asset('js/sweetalert.min.js');?>"></script>
<script src="<?=asset('js/selectFx/classie.js');?>"></script>
<script src="<?=asset('js/selectFx/selectFx.js');?>"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->


<!-- end: Packet JAVASCRIPTS -->
<!-- start: JavaScript Event Handlers for this page -->
@yield('js4event')

<script src="<?=asset('js/index.js')?>"></script>
<!-- end: JavaScript Event Handlers for this page -->
<script>
    default_image_path = $("#default_admin_path").val();
    $(document).ready(function() {
        home_path = $("#home_path").val();
        avatar_path = $("#avatar_path").val();
        avatar_admin_path = $("#avatar_admin_path").val();
    });

    var token = $("meta[name='_csrf']").attr("content");
    var header = $("meta[name='_csrf_header']").attr("content");

    $.ajaxSetup({
        data: {
            "_token" : token
        },
        error: function() {
            //showToastrMsg({type:"fail", msg:lang["op_failed"]});
            $(".csspinner").removeClass("csspinner");
        }
    });

    $(document).ajaxSend(function(e, xhr, options) {
        xhr.setRequestHeader(header, token);
    });

    $('#payment-tag').hover(function() {
        $('#payment-dropdown-menu').css('display', 'block');
    }, function () {
        $('#payment-dropdown-menu').css('display', 'none');
    });
</script>
</body>
</html>