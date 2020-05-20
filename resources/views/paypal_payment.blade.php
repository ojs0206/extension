@extends('layout.master')

@section('title')
    HOME
@endsection

@section('page-summary')
    HOME
@endsection

@section('description')

@endsection


@section('css4page')
    <style type="text/css">
        label.heading{
            font-weight: 600;
        }
        .payment-form{
            width: 480px;
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
            margin-top: 80px;
            text-align: center;
            border: 1px solid #0b58a2;
        }

        .paypal {
            width: 100%;
        }
    </style>
@endsection
@section('content')



    <form action="<?=url('/payment/updateMembership')?>" method="post" class="payment-form">
        {{csrf_field()}}
        <h3 id="plzwait">Please wait ...</h3>
        <div id="dropin-container"></div>
        <br><br>
        <input type="hidden" id="productid" name="productid"/>
        <input type="hidden" name="amount" value="<?=$amount?>"/>
        <button type="submit" id = "paybtn" class="btn btn-wide btn-primary" style="display:none;">Pay with Braintree</button>
    </form>

    <iframe id="respTmp" name="respTmp" style="display: none;">
    </iframe>

@endsection

@section('js4page')
    <script src="https://js.braintreegateway.com/js/braintree-2.31.0.min.js"> </script>
@endsection

@section('js4event')


    <script type="text/javascript">
        // var good = false;
        // $("#respTmp").on("load", function() {
        //     var ret = $(this).contents().find("body").html(), resp = null;
        //     good = true;
        //     window.close();
        //     window.opener.continuePaymentWithPaypal(ret);
        // });
        //
        //
        // $(window).on('beforeunload', function (e) {
        //     if(good == false){
        //         window.opener.closedWithNoUser();
        //     }
        // });

        $.ajax({
            url: "<?=url('/payment/getClientToken')?>",
            type: "get",
            dataType: "json",
            success: function(data){
                console.log(data);
                braintree.setup(
                    data,
                    'dropin',
                    {
                        container: 'dropin-container',
                        onReady: function(){
                            //alert("aa");
                            $("#paybtn").show();
                            $("#plzwait").hide();
                        }
                    }
                );
            }
        });
    </script>



@endsection