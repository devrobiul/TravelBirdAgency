<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('app_name') }}|| Group Ticket Invoice</title>
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
            background-color: #fff;
            font-family: "Courier New", Courier, monospace;
            color: #000;
            line-height: 1.5;
        }

        /* Invoice container styles */
        .invoice-container {
            width: 210mm;
            margin: 70px auto;
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
            width: 400px;
            height: auto;
        }

        /* Header section */
        .header {
            border-bottom: 2px solid rgba(224, 224, 224, 0.342);
            padding-bottom: 0px;
            margin-bottom: 20px;
            width: 95%;
        }



        /* Address and contact section */


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



        .card-body {
            padding: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table td {
            border: 1px solid rgb(245, 245, 245);
            padding: 5px;
            text-align: left;
            font-size: 12px
        }

        .table th {
            background-color: #fafafa;
            font-weight: 400;
            font-size: 13px;
            text-align: left;
            padding: 5px;
            border: 1px solid rgb(245, 245, 245);
        }

        .table td {
            font-size: 13px;
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
            <img src="{{ public_path(setting('primary_logo')) }}" alt=""style="width:200px" height=""
                style="margin-bottom:20px">
        </div>
        <div class="section" style="padding: 20px 0px 0px 0px">
            <address>
                   <strong>Group Ticket</span> <br>
                 <strong>PNR:</strong><span style="color:rgb(0, 4, 255)">{{ $product->ticket_pnr }}</span> <br>
              <strong>Sale Date:</strong> {{ $product->sale_date }}<br>
                    <strong>Authorized:</strong> {{ $product->user->name }}
            </address>
            <address>
               {!! setting('pdf_address') !!}
            </address>
        </div>
        <div class="section" style="margin-top: 15px">
            <div style="width:95%;">
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Airline</th>
                                <th>Travel Status</th>
                                <th>Travel Date</th>
                                <th>Travel Airport</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $product->airline->IATA }}-{{ $product->airline->Airline }}</td>
                                <td>{{ strtoupper($product->travel_status) }}</td>
                                <td>Depart: {{ $product->depart_date }}
                                    @if ($product->travel_status == 'roundtrip' || $product->travel_status == 'multicity')
                                        <br>Return: {{ $product->return_date }}
                                    @endif
                                </td>
                                <td>{{ $product->journey_from }} TO {{ $product->journey_to }}
                                    @if ($product->travel_status == 'multicity')
                                        <br>{{ $product->multicity_from }} TO {{ $product->multicity_to }}
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            @if ($product->group_ticket_sales)
                <div style="width:95%;">
                    <div>
                        <table class="table">
                            <thead>
                                <td>CLIENT NAME</td>
                                <td>PAX NAME</td>
                                <td>PAX MOBILE</td>
                                <td>PAX TYPE</td>
                            </thead>
                            <tbody>
                                @foreach ($product->group_ticket_sales as $item)
                                    <tr>
                                        <td>{{ $item->customer->name }}</td>
                                        <td>{{ $item->pax_name }}</td>
                                        <td>{{ $item->pax_mobile_no }}</td>
                                        <td>{{ $item->pax_type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @endif

            <div style="width:95%;">
                <div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Ticket Status</td>
                                <td style="font-weight: bold">{{ ucfirst(strtolower($product->ticket_type)) }}</td>

                                <td>Total Price</td>
                                <td style="font-weight: bold">
                                    {{ currencyBD($product->group_ticket_sales->sum('sale_price')) }}/=
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div class="footer"
            style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; font-size: 12px; background-color: #fff; padding: 10px 0;">

            <p>
               
                Software developed by <strong>Mohammad Robiul Hossain</strong>
                <br>
                <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a>
                (<a href="tel:+8801882850027">+8801882850027</a>)
            </p>
        </div>
    </div>
</body>

</html>
