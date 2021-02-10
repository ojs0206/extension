<html>
<head>
    <title> Email Verification</title>
</head>
<body>
<div style="text-align: center; padding-top: 50px;">
    <h2>
        Welcome to dvpgrid demo!
    </h2>
    @if ($success == 1)
        <h5>Your email {{$email}} is verified now. Thanks for your cooperation!</h5>
        <a href="<?=url('/home');?>">Please go to home page</a>
    @else
        <h5>Your email {{$email}} failed on verification. Please try it again. Thanks for your patience and cooperation!</h5>
        <a href="<?=url('/child');?>">Please go to user setting page</a>
    @endif
    
</div>
</body>
</html>
