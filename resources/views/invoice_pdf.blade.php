<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <style>
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        .text-bold{
            font-weight: bold;
        }
        .table tr{
            line-height: 40px;
        }
        .table tr td{
            font-size: 18px;
        }
        .v-bottom{
            vertical-align: bottom;
        }
        table {
            border: 1px solid #ddd;
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            border-spacing: 0;
        }
        table>tbody>tr:nth-of-type(odd) {
            background-color: white;
        }
        table>tbody>tr>td,table>thead>tr>th {
            border: 1px dashed #ddd;
            padding: 2px;
            line-height: 1.4;
            vertical-align: middle;
        }
        .invoice_table td{
            line-height: 1.9;
        }
        .invoice_table th{
            line-height: 1.5;
        }
        .pl-40{
            padding-left: 40px;
        }
    </style>
</head>
<body>
<div class="">
    <img src="{{asset("images/Adobe-reader.png")}}" style="float: right; width:100px">
    <h1>Tax Invoice</h1>
</div>
<table style="border-collapse: collapse" cellspacing="0" width="100%">
    <thead>
    <tr >
        <th class="text-left" style="font-size: 20px">Acount number:</th>
        <th style="font-size: 24px">Tax Invoice Summary</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="font-size: 24px">{{$data['receipt']}}</td>
        <td>Tax Invoice Number:</td>
        <td class="text-right">{{$data['invoice_number']}}</td>
    </tr>
    <tr>
        <td rowspan="2"></td>
        <td>Tax Invoice Date:</td>
        <td class="text-right">{{$data['payment_date']}}</td>
    </tr>
    <tr>
        <td>Invoice Month: {{$data['invoice_month']}}</td>
        <td></td>
    </tr>
    <tr>
        <td>{{$data['user_profile']}}<br>
            {{$data['company']}}</td>
        <td class="v-bottom">TOTAL AMOUNT</td>
        <td class="v-bottom text-right">{{$data['currency']}} {{$data['invoice_value']}}</td>
    </tr>
    <tr>
        <td>{{$data['street']}}</td>
        <td rowspan="2" class="v-bottom">TOTAL TAX</td>
        <td rowspan="2" class="v-bottom text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.1}}</td>
    </tr>
    <tr>
        <td>{{$data['suburb']}}</td>
    </tr>
    <tr>
        <td>{{$data['suburb']}}, {{$data['street']}}, {{strval(date('Y'))}}, {{$data['country_code']}}</td>
        <td></td>
        <td rowspan="2" class="v-bottom text-right" style="font-size: 20px">Original For Recipien</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
<p style="font-size: 22px">This Tax Invoice is for the billing period from {{$data['payment_date']}}</p>
<h3 style="margin-top: 10px; font-size: 22px">Tax Invoice Summary</h3>
<table class="invoice_table" style="border-collapse: collapse" cellspacing="0" width="100%">
    <thead>
    <tr style="background-color: #8dccea">
        <th class="text-left" style="font-size: 22px; width: 80%">SPG Service Charges</th>
        <th class="text-right">{{$data['currency']}} {{$data['invoice_value']}}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="pl-40">Charges (excl. GST)</td>
        <td class="text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.9}}</td>
    </tr>
    <tr>
        <td class="pl-40">Credits/Discount</td>
        <td class="text-right">{{$data['currency']}} 0.00</td>
    </tr>
    <tr>
        <td class="text-bold pl-40">Net Charges (After Credits/Discount, excl. GST)</td>
        <td class="text-bold text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.9}}</td>
    </tr>
    <tr>
        <td class="text-bold pl-40">Total GST Amount at 10%</td>
        <td class="text-bold text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.1}}</td>
    </tr>
    </tbody>
</table>
<table class="invoice_table" style="border-collapse: collapse" cellspacing="0" width="100%">
    <thead>
    <tr style="background-color: #8dccea">
        <th class="text-left" style="font-size: 22px; width: 80%">Detailed Usage for Account # {{$data['receipt']}}</th>
        <th class="text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.9}}</th>
    </tr>
    </thead>
    <tbody>
    <tr style="background-color: #e0e0e0">
        <td class="text-bold">Item ID# {{$data['receipt']}}       DescriptIon: {{$data['user_profile']}}     User Profile: {{$data['user_profile']}}</td>
        <td class="text-right">{{$data['currency']}} {{$data['invoice_value']}}</td>
    </tr>
    <tr>
        <td class="pl-40">Charges (excl. GST)          Click Count: 0      Rate Per Click: ${{$data['rate_per_click']}}</td>
        <td class="text-right">AUD 0.00</td>
    </tr>
    <tr>
        <td class="pl-40">Credits/Discount</td>
        <td class="text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.9}}</td>
    </tr>
    <tr>
        <td class="text-bold pl-40">Net Charges (After Credits / Discount, excl. GST)</td>
        <td class="text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.1}}</td>
    </tr>
    <tr>
        <td class="text-bold pl-40">Total GST Amount at 10%</td>
        <td class="text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.1}}</td>
    </tr>
    <tr>
        <td class="pl-40">GST Amount</td>
        <td class="text-right">{{$data['currency']}} {{$data['invoice_value'] * 0.9}}</td>
    </tr>
    </tbody>
</table>
<p class="text-center" style="color: #999999; font-size:12px;">{{$data['company']}}<br>
    {{$data['street']}}<br>
    {{$data['state']}}, {{$data['street']}}, {{strval(date('Y'))}}, {{$data['country_code']}}<br><br>
    {{$data['billing_id']}}</p>
</body>
</html>