<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <style>
        .text-center{
            text-align: center;
        }
        .table tr{
            line-height: 40px;
        }
        .table tr td{
            font-size: 18px;
        }
    </style>
</head>
<body>
<h1 class="text-center" style="border-bottom: 1px solid black">Invoice</h1>
<table class="table" style="margin-left: 200px; width: 90%;border-collapse: collapse">
    <tbody>
    <tr>
        <td style="">User profile:</td>
        <td>{{$data['user_profile']}}</td>
    </tr>
    <tr>
        <td style="">Billing Profile ID:</td>
        <td>{{$data['billing_id']}}</td>
    </tr>
    <tr>
        <td style="">Invoice Month:</td>
        <td>{{$data['invoice_month']}}</td>
    </tr>
    <tr>
        <td style="">Invoice Value:</td>
        <td>{{$data['invoice_value']}}</td>
    </tr>
    <tr>
        <td style="">Billing Currency:</td>
        <td>{{$data['currency']}}</td>
    </tr>
    <tr>
        <td style="">Payment Method:</td>
        <td>{{$data['payment_method']}}</td>
    </tr>
    <tr>
        <td style="">Payment Date:</td>
        <td>{{$data['payment_date']}}</td>
    </tr>
    <tr style="border-bottom: 1px solid black">
        <td style="">Receipt:</td>
        <td>{{$data['receipt']}}</td>
    </tr>
    </tbody>
</table>
<div class="" style="margin-left: 200px; border-bottom: 1px dashed black; width: 300px; margin-top: 20px; margin-bottom: 20px"></div>
<table class="" style="margin-left: 200px; width: 70%;border-collapse: collapse">
    <tbody>
    <tr>
        <td style="color: red;font-size: 25px">Status:</td>
        <td style="color: red;font-size: 25px">{{$data['status']}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>