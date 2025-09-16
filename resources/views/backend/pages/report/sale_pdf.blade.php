<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <title>{{ setting('app_name') }} || @if ($product_type == 'passport')
            Passport Sale Report
        @elseif($product_type == 'visa_sale')
            Visa Sale Report
        @elseif($product_type == 'single_ticket')
            Ticket Sale Report
        @elseif($product_type == 'hotel_booking')
            Hotel Booking Report
        @elseif($product_type == 'group_ticket')
            Group Ticket
        @elseif($product_type == 'ticket_refund')
            Refund Ticket
        @elseif($product_type == 'custom_bill')
            Other Bill/Service
        @else
            All Sale Reports
        @endif
    </title>
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 14px;
            color: #000;
            line-height: 1.5;
            height: 100%;
            width: 100%;
        }

        @page {
            size: 250mm 310mm;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
            /* Ensure table rows do not break */
        }

        tr {
            page-break-inside: avoid;
            /* Prevent rows from splitting across pages */
            page-break-after: auto;
        }

        th,
        td {
            border: 1px solid rgb(241, 241, 241);
            padding: 4px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background-color: #f2f2f2;
            font-size: 13px;
        }
         .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 5px 0;
            background-color: #f4f4f4;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="div" style="text-align: center">
        <h1 style="color: blue;padding:2px;margin:0"><i>{{ setting('app_name') }}</i></h1>
        <h4 style="padding:2px;margin:0"><i>
                @if ($product_type == 'passport')
                    Passport Sale
                @elseif($product_type == 'visa_sale')
                    Visa Sale
                @elseif($product_type == 'single_ticket')
                    Ticket Sale
                @elseif($product_type == 'manpower')
                    Man power
                @elseif($product_type == 'group_ticket')
                    Group Ticket
                @elseif($product_type == 'hotel_booking')
                    Hotel Booking Report
                @elseif($product_type == 'ticket_refund')
                    Ticket Refund
                @elseif($product_type == 'custom_bill')
                    Other Bill/Services
                @else
                    All Sale Reports
                @endif
            </i></h4>
        <h5 style="padding:2px;margin:0">Date Range: {{ $start_date }} to {{ $end_date }}</h5>
        <p style="padding: 0px">
            Sale: <span style="color:rgb(55, 0, 255)">{{ currencyBD($sale_price) }}</span> /=
            Profit: <span style="color:rgb(24, 204, 0)">{{ currencyBD($sale_profit) }}</span> /=
            Loss: <span style="color:rgb(255, 0, 0)">{{ currencyBD($sale_loss) }}</span> /=
        </p>

    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Saler</th>
                <th>Service/Client</th>
                <th>Description</th>
                <th>Purchase Vendor</th>
                <th>Sale</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($sales as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->sale_date }} <br>
                        {{ $item->product->user->name }}
                    </td>
                    <td>
                        @if ($item->product && $item->product->product_type == 'single_ticket')
                            <strong>Ticket</strong>:<span
                                style="color:blue">{{ $item->product->ticket_pnr }}</span>({{ $item->product->ticket_type ?? 'N/A' }})<br>
                            <strong>Sale:</strong>{{ $item->product->sale_date ?? 'N/A' }}<br>
                            @if ($item->product && $item->product->meta_data)
                                @foreach (json_decode($item->product->meta_data) as $data)
                                    P/N: {{ $data->name ?? 'N/A' }} <br>
                                    P/M: {{ $data->mobile_no ?? 'N/A' }}<br>
                                    P/T:{{ $data['type'] ?? 'N/A' }} <br>
                                    P/P:{{ $data['price'] ?? 'N/A' }}/-
                                @endforeach
                            @endif

                            <strong>C/N:</strong><span
                                style="color: rgb(33, 166, 255);">{{ $item->product->sales->customer->name }}</span><br>
                            <strong>C/P:</strong>{{ $item->product->sales->customer->phone }}<br>
                        @elseif($item->product && $item->product->product_type == 'ticket_refund')
                            <strong>Ticket PNR:</strong>
                            {{ str_replace('REFUND-', '', strtoupper($item->product->ticket_pnr ?? 'N/A')) }}
                            <br><strong>Ticket Type:</strong>
                            {{ $item->product->ticket_type ?? null }}<br>
                            @if ($item->product->sales->sale_customer_id == 0)
                                <strong> Refund Vendor:</strong> <span style="color: red;">{{ setting('app_name') }}
                                    (Myself)</span>
                                <br>
                            @else
                                <strong>Refund Vendor:</strong> <span style="color: rgb(33, 166, 255);">
                                    <a
                                        href="{{ route('admin.customer.details', $item->product->sales->customer->slug) }}">{{ $item->product->sales->customer->name ?? null }}</a>
                                </span><br>
                                <strong>R/P:</strong>
                                {{ $item->product->sales->customer->phone ?? null }}<br>
                            @endif
                        @elseif ($item->product && $item->product->product_type == 'group_ticket')
                            <strong>Group Ticket</strong>:<span
                                style="color:blue">{{ $item->product->ticket_pnr }}</span>({{ $item->product->ticket_type ?? 'N/A' }})<br>
                            <strong>Sale:</strong>{{ $item->product->sale_date ?? 'N/A' }}<br>
                            <strong>C/N:</strong><span
                                style="color: rgb(33, 166, 255);">{{ $item->customer->name }}</span>
                            <br>
                             @if ($item->product && $item->product->pax_data)
                                @foreach (json_decode($item->product->pax_data) as $data)
                                    P/N: {{ $data->name ?? 'N/A' }} <br>
                                    P/M: {{ $data->mobile_no ?? 'N/A' }}<br>
                                    P/T: {{ $data->type ?? 'N/A' }}<br>
                                @endforeach
                            @endif
                        @elseif($item->product && $item->product->product_type == 'passport')
                            <strong>Passport:</strong>{{ strtoupper($item->product->passport_type ?? 'N/A') }}<br>
                           @if ($item->product && $item->product->pax_data)
                                @foreach (json_decode($item->product->pax_data) as $data)
                                    P/N: {{ $data->name ?? 'N/A' }} <br>
                                    P/M: {{ $data->mobile_no ?? 'N/A' }}<br>
                                    P/T: {{ $data->type ?? 'N/A' }}<br>
                                @endforeach
                            @endif
                            <strong>C/N:</strong><span
                                style="color: rgb(33, 166, 255);">{{ $item->product->sales->customer->name }}</span><br>
                            <strong>C/P:</strong>{{ $item->product->sales->customer->phone }}<br>
                        @elseif($item->product && $item->product->product_type == 'visa_sale')
                            <strong>Visa:</strong>{{ strtoupper($item->product->visa->visa_name ?? 'N/A') }}
                            [<span style="color: blue">{{ strtoupper($item->product->visa_type ?? 'N/A') }}</span>]<br>
                            @if ($item->product && $item->product->pax_data)
                                @foreach (json_decode($item->product->pax_data) as $data)
                                    P/N: {{ $data->name ?? 'N/A' }} <br>
                                    P/M: {{ $data->mobile_no ?? 'N/A' }}<br>
                                    P/T: {{ $data->type ?? 'N/A' }}<br>
                                @endforeach
                            @endif
                            <strong>C/N:</strong><span
                                style="color: rgb(33, 166, 255);">{{ $item->product->sales->customer->name }}</span><br>
                            <strong>C/P:</strong>{{ $item->product->sales->customer->phone }}<br>
                        @elseif($item->product && $item->product->product_type == 'manpower')
                            <strong>Manpower:</strong>
                            {{ $item->product->visit_country }}
                            [<span
                                style="color: blue">{{ strtoupper($item->product->tracking_id ?? 'N/A') }}</span>]<br>
                            @if ($item->product && $item->product->pax_data)
                                @foreach (json_decode($item->product->pax_data) as $data)
                                    P/N: {{ $data->name ?? 'N/A' }} <br>
                                    P/M: {{ $data->mobile_no ?? 'N/A' }}<br>
                                    P/T: {{ $data->type ?? 'N/A' }}<br>
                                @endforeach
                            @endif
                            <strong>C/N:</strong><span
                                style="color: rgb(33, 166, 255);">{{ $item->product->sales->customer->name }}</span><br>
                            <strong>C/P:</strong>{{ $item->product->sales->customer->phone }}<br>
                        @elseif($item['product']->product_type == 'hotel_booking')
                            Hotel Name:{{ strtoupper($item['product']->hotel_name ?? 'N/A') }}<br>
                            location:{{ strtoupper($item['product']->hotel_location ?? 'N/A') }}<br>
                           @if ($item->product && $item->product->pax_data)
                                @foreach (json_decode($item->product->pax_data) as $data)
                                    P/N: {{ $data->name ?? 'N/A' }} <br>
                                    P/M: {{ $data->mobile_no ?? 'N/A' }}<br>
                                    P/T: {{ $data->type ?? 'N/A' }}<br>
                                @endforeach
                            @endif
                        @elseif($item['product']->product_type == 'custom_bill')
                          
                           @if ($item->product && $item->product->meta_data)
                                @foreach (json_decode($item->product->meta_data,true) as $data)
                                        {{$loop->index+1 }}.
                                   {{ $data['service_name'] ?? 'N/A' }} <br>
                                  BDT {{ $data['service_cost'] ?? 'N/A' }}/-<br>
                                @endforeach
                            @endif
                        @else
                            <em>No product data available</em>
                        @endif
                    </td>

                    <td>
                        @if ($item->product && $item->product->travel_status)
                            @php
                                $status = strtoupper($item->product->travel_status);
                                $departing_date = $item->product->depart_date;
                                $journey_from = $item->product->journey_from;
                                $journey_to = $item->product->journey_to;
                                $return_date = $item->product->return_date;
                                $multicity_from = $item->product->multicity_from;
                                $multicity_to = $item->product->multicity_to;
                                $icon = ' TO ';
                            @endphp
                            <strong>Travel Status:</strong>{{ $status }}<br>
                            <strong>Airline:</strong>
                            {{ $item->product->airline->IATA ?? 'N/A' }}<br>
                            @if ($status === 'ONEWAY')
                                <strong>Departing:</strong>{{ $departing_date ?? 'N/A' }}<br>
                                {!! $journey_from . $icon . $journey_to !!}
                            @elseif ($status === 'ROUNDTRIP')
                                <strong>Departing:</strong>{{ $departing_date ?? 'N/A' }}<br>
                                <strong>Returning:</strong>{{ $return_date ?? 'N/A' }}<br>
                                {!! $journey_from . $icon . $journey_to !!}
                            @elseif ($status === 'MULTICITY')
                                <strong>Departing:</strong>{{ $departing_date ?? 'N/A' }}<br>
                                <strong>Returning:</strong>{{ $return_date ?? 'N/A' }}<br>
                                {!! $journey_from . $icon . $journey_to !!}<br>
                                {!! $multicity_from . $icon . $multicity_to !!}
                            @endif
                        @elseif($item->product->product_type == 'passport')
                            <strong>Track</strong>:<span
                                style="color: blue">{{ strtoupper($item->product->tracking_id ?? 'N/A') }}</span> <br>
                            <strong>Application</strong>:{{ $item->product->sale_date ?? 'N/A' }} <br>
                            <strong>Delivery</strong>:{{ $item->product->delivery_date ?? 'N/A' }} <br>
                        @elseif($item->product->product_type == 'visa_sale')
                            <strong>Country</strong>:{{ $item->product->visit_country ?? 'N/A' }} <br>
                            <strong>Visa Issue</strong>:{{ $item->product->visa_exp_date ?? 'N/A' }} <br>
                            <strong>Visa Expire</strong>:{{ $item->product->visa_exp_date ?? 'N/A' }} <br>
                        @elseif($item->product->product_type == 'manpower')
                            <strong>Country</strong>:{{ $item->product->visit_country ?? 'N/A' }} <br>
                            <strong>Submitted Date</strong>:{{ $item->product->delivery_date ?? 'N/A' }} <br>
                        @elseif($item['product']->product_type == 'hotel_booking')
                            Visiting C:{{ strtoupper($item['product']->visit_country ?? 'N/A') }}<br>
                            Hotel Stay:{{ $item['product']->hotel_number_of_day ?? 'N/A' }} <br>
                        @elseif($item->product->product_type == 'ticket_refund')
                            REFUND TICKET
          
                        @elseif($item->product->product_type == 'custom_bill')
                           Other Bill/Services <br>
                           <strong>C/N:</strong><span
                                style="color: rgb(33, 166, 255);">{{ $item->product->sales->customer->name }}</span><br>
                                 <strong>C/P:</strong>{{ $item->product->sales->customer->phone }}
                        @else
                        @endif
                    </td>
                    <td>
                        @if ($item->product->purchase->purchase_vendor_id == 0)
                            <span style="color: red;">{{ setting('app_name') }} (Myself)</span> <br>
                            AC {{ $item->product->purchase->fromAccount->account_name ?? 'N/A' }}<br>
                            A/N:{{ $item->product->purchase->fromAccount->account_number ?? 'N/A' }}<br>
                            TXNID:{{ $item->product->purchase->purchase_tnxid }}<br>
                        @else
                            @if ($item->product->product_type == 'ticket_refund')
                                R/Client
                            @endif
                            {{ $item->product->purchase->vendor->name ?? 'N/A' }} <br>
                        @endif
                        @if ($item->product->product_type == 'passport')
                            AC {{ $item->product->purchase->fromAccount->account_name ?? 'N/A' }}<br>
                            A/N:{{ $item->product->purchase->fromAccount->account_number ?? 'N/A' }}<br>
                            TXNID:{{ $item->product->purchase->purchase_tnxid }}<br>
                        @endif
                        Purchase:{{ currencyBD($item->product->purchase->purchase_price) }}/= <br>
                    </td>

                    <td>
                        Price:{{ currencyBD($item->sale_price) }}/=<br>
                        Profit:{{ currencyBD($item->sale_profit) }}/=<br>
                        Loss:{{ currencyBD($item->sale_loss) }}/=
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="tfoot-row">
                <td colspan="2">Total Sale:{{ currencyBD($sale_price) }}</td>
                <td colspan="2">Total Profit:{{ currencyBD($sale_profit) }}</td>

                <td colspan="2">Total Loss:{{ currencyBD($sale_loss) }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="page-break"></div>

    <div class="footer"
            style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; font-size: 12px; background-color: #fff; padding: 10px 0;">
                     <p>Software developed by <strong>Mohammad Robiul Hossain</strong>
                <br>
                <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a>
                (<a href="tel:+8801882850027">+8801882850027</a>)
            </p>
        </div>
</body>

</html>
