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
        window.onunload = refreshParent;
        function refreshParent() {
            window.opener.location.reload();
        }
        window.close();
    });
</script>

</body>
</html>


