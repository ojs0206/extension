<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img src="<?=asset('logo.png')?>" alt="" width="18%" class="navbar-brand">
            <a class="navbar-brand" href="#">Extension</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">


            <ul class="nav navbar-nav navbar-right">
                <img src="<?=asset('avatar.png');?>" width="2%" style="float:left; margin-right:1px; margin-left:15px; padding-top: 1.5%;">
                    <p style="float:left; margin-right:15px; padding-top: 1.5%; color: white;"><?=$admin;?></p>
                <li><a href="<?=url('/home');?>"><span class="glyphicon glyphicon-camera"></span> Site Collection</a></li>
                <li><a href="<?=url('/count');?>"><span class="glyphicon glyphicon-signal"></span> Redirect Collection & Statistics</a></li>
                <li id="payment-tag">
                    <a href="<?=url('/payment');?>"><span class="glyphicon glyphicon-usd"></span> Billing & Payments</a>
                    <div class="payment-dropdown" id="payment-dropdown-menu">
                        @if($type != 'Admin')
                            <a href="<?=url('/payment/newBillingSetup');?>">New Billing Setup</a><br>
                            <a href="<?=url('/payment/summary');?>">Summary</a><br>
                            <a href="<?=url('/payment/transaction');?>">Transaction</a><br>
                            <a href="<?=url('/payment/settings');?>">Settings</a>
                        @else
                            <a href="<?=url('/payment/graph');?>">Graphical Interface</a><br>
                        @endif
                    </div>

                </li>
                @if($type != 'User')
                    <li><a href="<?=url('/child');?>"><span class="glyphicon glyphicon-pencil"></span> Settings</a></li>
                @endif
                <li><a href="<?=url('/sign-out');?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>