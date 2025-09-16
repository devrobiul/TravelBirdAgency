<!DOCTYPE html>
<html>

<head>
    <title>{{ $customer->name }} || {{ setting('app_name') }} || Invoice </title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            /* Changed font */
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

        .watermark {
            position: fixed;
            top: 50%;
            left: 45%;
            transform: translate(-50%, -50%);
            z-index: -1;
            opacity: 0.1;
            width: 400px;
            height: auto;
            page-break-before: always;
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

        .text-center {
            text-align: center;
        }

        address {
            font-style: normal;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <img class="watermark" src="{{ public_path(setting('watermark')) }}" width="100%" alt="Watermark Logo">

    <div class="invoice_header" style="text-align: right; margin-to:60px;margin-bottom:30px">
        <h1 style="padding:0; margin:0; float:left;">
            <img src="{{ public_path(setting('watermark')) }}" width="100px" alt="">
        </h1>
        <i> {!! setting('pdf_address') !!}</i>
    </div>

    <div class="text-center">
        <h3 style="text-align:center; font-weight:bold; font-size:20px; margin-bottom:15px;">
            {{ $customer->name ?? 'N/A' }} - Billing Invoice
        </h3>
    </div>

    <!-- Invoice Info -->
    <address>
        <strong>Date:</strong> {{ date('d F,Y') }}<br>
        <strong>Customer Name:</strong> {{ $customer->name ?? 'N/A' }}<br>
        <strong>Address:</strong> {{ $customer->address ?? 'N/A' }}
    </address>

    <!-- Services Table -->
    <table class="table" style="margin-bottom:5px;">
        <thead>
            <tr>
                <th>Bill No</th>
                <th>Entry By</th>
                <th>Service</th>
                <th>Date</th>
                <th>Detail</th>
                <th>Bill Taka</th>
            </tr>
        </thead>
        <tbody>
         @php $total_bill = 0; @endphp
            @foreach ($products as $index => $product)
            @php
            $sale_price = $product->sales->sale_price ?? 0;
            $total_bill += $sale_price;
        @endphp
                <tr>
                    <td>{{ $product->invoice_no }}</td>
                    <td>{{ $product->user->name ?? 'Admin' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $product->product_type)) }}
                        @if ($product->ticket_pnr)
                            <br>PNR: {{ $product->ticket_pnr }}
                        @endif

                    </td>
                    <td>{{ \Carbon\Carbon::parse($product->sale_date)->format('d M, Y') }}</td>
                    <td>
                        @if ($product->product_type == 'single_ticket')
                            @foreach (json_decode($product->pax_data, true) as $pax)
                                Guest:{{ $pax['name'] ?? 'N/A' }},
                            @endforeach
                        @endif
                        @if ($product->product_type == 'group_ticket')
                            Guest:{{ $product->sales->pax_name }},
                        @endif
                        @if ($product->product_type == 'visa_sale')
                            @foreach (json_decode($product->pax_data, true) as $pax)
                                Guest:{{ $pax['name'] ?? 'N/A' }},
                            @endforeach
                            {{ strtoupper($product->visa->visa_name ?? 'N/A') }},
                            {{ strtoupper($iproduct->visa_type ?? 'N/A') }},
                        @endif
                        @if ($product->product_type == 'manpower')
                            @foreach (json_decode($product->pax_data, true) as $pax)
                                Guest:{{ $pax['name'] ?? 'N/A' }},
                            @endforeach
                            {{ strtoupper($product->visit_country ?? 'N/A') }},
                            {{ strtoupper($iproduct->visa_type ?? 'N/A') }},
                        @endif
                        @if ($product->product_type == 'hotel_booking')
                            @foreach (json_decode($product->pax_data, true) as $pax)
                                Guest:{{ $pax['name'] ?? 'N/A' }},
                            @endforeach
                            {{ strtoupper($product->hotel_name ?? 'N/A') }},
                            {{ strtoupper($iproduct->hotel_location ?? 'N/A') }},
                        @endif
                        @if ($product->travel_status)
                            @php
                                $status = strtoupper($product->travel_status);
                                $departing_date = $product->depart_date;
                                $journey_from = $product->journey_from;
                                $journey_to = $product->journey_to;
                                $return_date = $product->return_date;
                                $multicity_from = $product->multicity_from;
                                $multicity_to = $product->multicity_to;
                                $icon = ' TO ';
                            @endphp
                            Airline:{{ $product->airline->Airline ?? 'N/A' }},{{$product->airline->IATA  }},
                            ({{ $status }})
                            <br>
                            @if ($status === 'ONEWAY')
                                Departing: {{ $departing_date ?? 'N/A' }}<br>
                                {!! $journey_from . $icon . $journey_to !!}
                            @elseif ($status === 'ROUNDTRIP')
                                Departing:{{ $departing_date ?? 'N/A' }}<br>
                                Returning:{{ $return_date ?? 'N/A' }}<br>
                                {!! $journey_from . $icon . $journey_to !!}
                            @elseif ($status === 'MULTICITY')
                                Departing:{{ $departing_date ?? 'N/A' }}<br>
                                Returning:{{ $return_date ?? 'N/A' }}<br>
                                {!! $journey_from . $icon . $journey_to !!}<br>
                                {!! $multicity_from . $icon . $multicity_to !!}
                            @endif
                        @endif
                        @if ($product->product_type == 'custom_bill')
                            @foreach (json_decode($product->meta_data, true) as $pax)
                                Sevice:{{ $pax['service_name'] ?? 'N/A' }},
                                Cost:{{ $pax['service_cost'] ?? 'N/A' }} <br>
                            @endforeach
                        @endif
                    </td>
                    <td>

                        {{ currencyBD($product->sales->sale_price) }}/-

                    </td>
                </tr>
   
            @endforeach
                     <tr>
        <td colspan="5" style="text-align:right; font-weight:bold;">Total</td>
        <td style="font-weight:bold;">{{ currencyBD($total_bill) }}/-</td>
    </tr>
        </tbody>
    </table>
     <span style="color: rgb(0, 0, 0);font-size:16px">({{ numberToWords($total_bill) }} taka only) </span> 
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

    <div class="footer">
        <p style="color:rgba(0,0,0,0.54); font-size:11px;">
            <span style="font-size: 12px; color:darkslategray;"><i>[Note: Computer Generated Invoice, Signature Not
                    Required]</i></span><br>
            Travel Agency Accounting Software. Developed by <strong>Mohammad Robiul Hossain</strong><br>
            <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a>
            (<a href="tel:+8801882850027">+8801882850027</a>)
        </p>
    </div>
</body>

</html>
