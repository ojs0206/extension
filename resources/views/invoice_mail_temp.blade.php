<?php

?>
<html>
<head>
    <title>Invoice mail</title>
</head>
<body>
    <h2>
        Welcome to dvpgrid demo! email verification
    </h2>
    Please click on the link below to verify your email address and username
    <h5>Name: </h5> {{$username}}
    <h5>Company: </h5> {{$Company}}
    <h3><a href="{{url("verify/$email/$token")}}">Click Here</a> To verify your email. </h3>
</body>
</html>
