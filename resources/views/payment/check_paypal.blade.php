<!DOCTYPE html>
<html>
<head>
    <title>PayPal checked</title>
    <meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" href="<?=asset('bower_components/bootstrap/dist/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=asset('bower_components/sweetalert/dist/sweetalert.css');?>">
    <script src="<?=asset('jquery-1.9.1.min.js');?>"></script>
</head>
<body>
<script src="<?=asset('bootstrap/js/bootstrap.min.js');?>"></script>
<script src="<?=asset('bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>
<script>
    $( document ).ready(function() {
        var email = "{{$email}}";
        if(email==null)
            email = "";
        swal({
            title: "Notify",
            text: "Your paypal was connected",
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            closeOnConfirm: true,
        },function (isConfirm) {
            if (isConfirm) {
                var parent = $(window.opener.document.body);
                $(parent).find('#paypal_payment').hide();
                $(parent).find('#payment_method').hide();
                $(parent).find('#pp_success').text(email);
                $(parent).find('#paypal_status').show();
                window.close();
            }
        });
    });
</script>

</body>
</html>


