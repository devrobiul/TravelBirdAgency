<!DOCTYPE html>
<html>

<head>
    <title>{{ $product->sales->customer->name }} || {{ setting('app_name') }} || Invoice </title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 14px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
        }

        @page {
            size: 210mm 297mm;
            background: url('{{ public_path(setting('watermark')) }}') no-repeat center center;
            background-size: contain;
        }

        table th:nth-child(4),
        table td:nth-child(4) {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            border: .3px solid rgb(77, 77, 77);
        }

        th,
        td {
            border: .3px solid rgb(77, 77, 77);
            padding: 4px;
            font-size: 12px;
        }

        th {
            background-color: #f4f4f4;
            text-align: left;
        }



        .invoice_header {
            letter-spacing: 1px;
        }

        h5 {
            font-weight: 600;
            font-size: 13.5px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            background-color: #fff;
        }
    </style>
</head>

<body>
  
    <div class="invoice_header" style="text-align: right; margin:40px 0px;">
        <h1 style="padding:0; margin:0; float:left;">
            <img src="{{ public_path(setting('watermark')) }}" width="100px" alt="">
        </h1>
        <i> {!! setting('pdf_address') !!}</i>
    </div>

 <div class="text-center">
       <h3 style="text-align:center; font-weight:bold; font-size:20px; margin-bottom:15px;">
        Billing Invoice
    </h3>
 </div>

    <!-- Invoice Info -->
    <address style="margin-bottom: 20px">
        <strong>Invoice:</strong>{{ $product->invoice_no??'N/A' }} <br>
        <strong>Invoice Date:</strong>{{ \Carbon\Carbon::parse($product->sale_date)->format('d/m/Y') }} <br>
        <strong>Customer Name:</strong>{{ $product->sales->customer->name??'N/A' }}<br>
        <strong>Adderess:</strong>{{ $product->sales->customer->address??'N/A' }}
    </address>
    
    <!-- Services Table -->
    <table class="table" style="margin-bottom:5px;">
        <thead>
            <tr>
                <th style="width:5%;">SL</th>
                <th style="width:25%;">Service performed date</th>
                <th style="width:55%;">Service details</th>
                <th style="width:15%;">Bill amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($product->meta_data, true) as $service)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($product->sale_date)->format('d/m/Y')}}</td>
                <td>{{ $service['service_name'] }}</td>
                <td style="text-align:right;">{{ currencyBD($service['service_cost']) }}/=</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <p style="text-align:right; margin-top:5px;">
       <span style="color: green">({{ numberToWords($product->sales->sale_price) }} taka only) </span>  Total Amount: BDT <span style="font-weight: bold">{{ currencyBD($product->sales->sale_price) }}/=</span>
    </p>

    <!-- 🔽 Compact Bank Section -->
<div class="bank-details" style="margin-top:20px; font-size:11px; line-height:1.3;">
    <h4 style="margin-bottom:5px; font-size:16px; border-bottom:2px solid black;width:250px">Bank & Payment Information</h4>
                 <p style="font-size: 13px">   <strong>Bank Name:</strong> Eastern Bank Ltd.<br>
                    <strong>A/C Name:</strong> Travel Bird<br>
                    <strong>Branch:</strong> Khulshi<br>
                    <strong>A/C No:</strong> 0251070115189<br>
                    <strong>Swift Code:</strong> EBLDBDDH<br>
                    <strong>Routing:</strong> 095154361</p>
               
     
    
</div>



    <div class="footer" style="">
        <p style="color:rgba(0,0,0,0.54); font-size:11px;">
                 <p style="font-size: 12px;color:darkslategray"><i>[Note:Computer Generted Invoice Signature Not Required]</i></p>
            Travel Agency Accounting Software. Developed by <strong>Mohammad Robiul Hossain</strong><br>
            <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a> 
            (<a href="tel:+8801882850027">+8801882850027</a>)
        </p>
    </div>
</body>

</html>
