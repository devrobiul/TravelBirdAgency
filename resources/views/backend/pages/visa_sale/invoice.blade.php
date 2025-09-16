<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{setting('app_name')}} || Visa Invoice</title>
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
        .header {
            border-bottom: 2px solid rgba(224, 224, 224, 0.342);
            padding-bottom: 0px;
            margin-bottom: 20px;
            width: 95%;
        }


        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #f3f3f3;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f1f1f1;
            font-size: 14px
        }

        .table td {
            font-size: 14px;
        }

        /* Footer section */
        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 20px 0;
            background-color: #f4f4f4;
            margin-top: 20px;
        }

        /* Custom footer logo */
        #img2 {
            height: 40px;
            width: 80px;
            margin-top: 0px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
     
        <div class="header" style="text-align:center;">
            <img src="{{ public_path(setting('primary_logo')) }}" alt="Go Trip Travel" style="width:100%" height=""
                style="margin-bottom:20px">
        </div>
        <!-- Passenger and Company Details -->
        <div class="section" style="padding: 10px 0px 0px 0px">
            <address>
                <strong>Invoice:</strong><a href="" style="text-decoration: none">
                    {{ $visasale->invoice_no }}</a><br>
                <strong>Customer Name:</strong> {{ $visasale->sales->customer->name }}<br>
                <strong>Visiting Country:</strong> {{ $visasale->visit_country }}<br>
                <strong>Phone:</strong> {{ $visasale->sales->customer->phone ?? null }}<br>
                <strong>Sale by:</strong> {{ $visasale->user->name ?? null }}<br>
                <strong>Sale:</strong> {{ $visasale->sale_date }}<br>
            </address>
            <address>
             {!! setting('pdf_address') !!}
            </address>
        </div>

        <!-- Travel and Payment Info -->
        <div class="section" style="">
            <div style="margin-right:40px">
                <div>
                    <table class="table table-bordered" style="width:100%">
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Visa</th>
                            <th>Date of Issue</th>
                            <th>Date of Expire</th>
                        </tr>
                        <tbody>
                            <tr>
                                 @foreach ($pax_data as $item)
                                      <td>{{ $item['name'] ?? '-' }}</td>
                                       <td>{{ $item['mobile_no'] }}</td>
                                 @endforeach
                    
                                <td>{{ $visasale->visa->visa_name ?? null }} ({{ $visasale->visa_type }}
                                    Visa)</td>
                                <td>{{ $visasale->visa_issue_date }}</td>
                                <td>{{ $visasale->visa_exp_date }}</td>
                            </tr>


                        </tbody>
                    </table>
                </div>
                <div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Visa Sale</td>
                                <td>{{ currencyBD($visasale->sales->sale_price) }}/=</td>
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
