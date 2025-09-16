<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('app_name') }} || Passport Invoice</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        html,
        body {
            height: 100%;
            background-color: #ffffff;
            font-family: "Courier New", Courier, monospace;
            color: #000;
            line-height: 1.5;
        }

        /* Invoice container styles */
        .invoice-container {
            width: 210mm;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
            position: relative;
        }



        /* Watermark logo */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            opacity: 0.1;
            width: 380px;
            height: auto;
        }

        /* Header section */
        .header {
            border-bottom: 2px solid rgb(240, 240, 240);
            padding-bottom: 0px;
            margin-bottom: 20px;
            width: 95%;
        }




        /* Address and contact section */
        .section {}

        address {
            width: 48%;
            font-size: 14px;
            line-height: 1.8;
            color: #000000;
            display: inline-block;
        }

        /* Card section for Travel and Payment Info */
        .card {
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 15px;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            padding: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid rgb(235, 235, 235);
            padding: 5px;
            text-align: left;
            font-size: 12px
        }

        .table th {
            background-color: #f1f1f1;
            font-weight: 400;
        }

        .table td {
            font-size: 12px;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 20px 0;
            background-color: #f4f4f4;
            margin-top: 20px;
        }

        #img2 {
            height: 40px;
            width: 80px;
            margin-top: 0px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Watermark -->
        <img class="watermark" src="{{ public_path(setting('watermark')) }}" alt="Watermark Logo">
        <div class="header" style="text-align:center;">
            <img src="{{ public_path(setting('primary_logo')) }}" alt="{{ setting('app_name') }}" style="width:100%" height=""
                style="margin-bottom:20px">
        </div>
        <!-- Passenger and Company Details -->
        <div class="section" style="padding: 10px 0px 0px 0px">
            <address>
                <strong>Invoice:</strong> <span style="color:blue">{{ $product->invoice_no }}</span><br>
                <strong>Customer Name:</strong> {{ $product->sales->customer->name }}<br>
                <strong>Phone:</strong> {{ $product->sales->customer->phone }}<br>
                <strong>Application date:</strong> {{ $product->application_date }}<br>
                <strong>Passport Sale:</strong> {{ $product->sale_date }}<br>
                <strong>Sale By:</strong> {{ $product->user->name }}
            </address>
            <address>
                {!! setting('pdf_address') !!}
            </address>
        </div>

        <!-- Travel and Payment Info -->
        <div class="section" style="">
            <div style="margin-right:40px">
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sale Date</th>
                                <th>Passport</th>
                                <th>Applicaiton date</th>
                                <th>Delivery date</th>
                                <th>Sale price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $product->sale_date }}</td>
                                <td>{{ strtoupper(str_replace('_',' ',$product->passport_type)) }}</td>
                                <td>{{ $product->sale_date }}</td>
                                <td>{{ $product->delivery_date }}</td>
                                <td>{{ currencyBD($product->sales->sale_price) }}/=</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>



        <div class="footer"
            style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; font-size: 12px; background-color: #fff; padding: 10px 0;">

                <p>
                Thank you for booking with us!
                For any inquiries, contact us at <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>
                <br>
                Software developed by <strong>Mohammad Robiul Hossain</strong>
                <br>
                <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a>
                (<a href="tel:+8801882850027">+8801882850027</a>)
            </p>

        </div>
    </div>
</body>

</html>
