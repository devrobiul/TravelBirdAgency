<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $customer->name }} || {{ setting('app_name') }} || Sale Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Courier New", monospace;
            font-size: 14px;
            line-height: 1.4;
        }

        th,
        td {
            vertical-align: middle;
        }

        .table td,
        .table th {
            padding: 5px;
        }

        .balance {
            font-weight: bold;
            color: #0b2e13;
        }

        .credit {
            color: #ff3f02;
        }

        .debit {
            color: #059609;
        }

        .client-payment {
            color: #038c11;
        }

        .purchase {
            color: #ff0004;
        }

        .office-payment {
            color: #6a7bd4;
        }

        .table-container {
            margin-top: 200px;
            /* adjust for header height */
            margin-bottom: 60px;
            /* adjust for footer height */
            max-height: calc(100vh - 180px);
            /* viewport minus header/footer */
            overflow-y: auto;
            padding: 0 15px;
        }

        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 15px 0;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            z-index: 1000;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            z-index: 1000;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            opacity: 0.1;
            max-width: 500px;
            pointer-events: none;
        }
    </style>
</head>

<body>

    <div class="container my-2">
        <div class="fixed-header">
            <h5>{{ $customer->name }} Accounting Report</h5>
            <p>Customer: {{ $customer->name }} | Phone: {{ $customer->phone }} | Address: {{ $customer->address }}</p>
            <p>Date Range: {{ $start_date }} to {{ $end_date }}</p>
            <form action="{{ route('customerCheckReportdwonload', [$customer->id, $customer->uuid]) }}" method="get"
                class="d-inline">
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="start_date" value="{{ $start_date }}">
                <input type="hidden" name="end_date" value="{{ $end_date }}">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-download"></i> PDF ডাউনলোড
                </button>
            </form>

        </div>
        <img src="{{ asset(setting('watermark')) }}" width="100%" class="watermark" alt="Watermark">

        <div class="table-responsive table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td colspan="6"><strong>Previous Balance</strong></td>
                        <td><strong>{{ currencyBD($previous_balance) }}/=</strong></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Sale/Invoice</th>
                        <th>Service</th>
                        <th>Description</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $runningBalance = $previous_balance ?? 0;
                        $index = 1;
                    @endphp

                    @foreach ($combined as $item)
                        @php
                            $isSale = $item['type'] === 'sale';
                            $isPurchase = $item['type'] === 'purchase';
                            $client_payment = $item['type'] === 'client_payment';
                            $office_payment = $item['type'] === 'office_payment';

                            $credit = 0;
                            $debit = 0;
                            $minus = 0;

                            if ($isSale) {
                                $credit = $item['price'];
                            } elseif ($client_payment) {
                                $debit = $item['price'];
                            } elseif ($isPurchase) {
                                $debit = $item['purchase_price'];
                            } elseif ($office_payment) {
                                $minus = $item['price'];
                            }

                            $runningBalance += $credit - $debit + $minus;
                        @endphp

                        <tr
                            style="
                    {{ $item['type'] == 'purchase' ? 'color:#ff0004;' : '' }}
                    {{ $client_payment ? 'color: #038c11;' : '' }}
                    {{ $office_payment ? 'color: #6a7bd4;' : '' }}
                ">
                            <td>{{ $index++ }}</td>
                            <td>
                                @if ($isSale)
                                    Sale:{{ $item['date'] }} <br>
                                    Invoice:<span style="color: blue;">{{ $item['invoice'] }}</span>
                                @elseif ($isPurchase)
                                    Purchase:{{ $item['date'] }} <br>
                                    Invoice:<span style="color: blue;">{{ $item['invoice'] }}</span>
                                @elseif ($client_payment)
                                    Client Payment <br>
                                    {{ $item['date'] }}
                                @elseif ($office_payment)
                                    {{ setting('app_name') }} Payment<br>{{ $item['date'] }} <br>
                                @endif
                            </td>
                            <td>
                                @if (isset($item['product']) && $item['product'])
                                    @if ($item['product']->product_type == 'single_ticket')
                                        Ticket:<span
                                            style="color: blue;font-size: 12px;">{{ $item['product']->ticket_pnr }}</span>({{ ucfirst(strtolower($item['product']->ticket_type ?? 'N/A')) }})
                                        <br>
                                        Sale:{{ $item['product']->sale_date ?? 'N/A' }} <br>
                                        @if ($item['product']->ticket_type == 're_issue_date')
                                            Re-Issue:{{ $item['product']->re_issue_date ?? 'N/A' }} <br>
                                        @elseif($item['product']->ticket_type == 'ticket_refund')
                                            Refund Requested:{{ $item['product']->refund_date ?? 'N/A' }} <br>
                                        @endif
                                        @if ($item['product']->pax_data)
                                            @foreach (json_decode($item['product']->pax_data, true) as $data)
                                                P/N:{{ $data['name'] ?? 'N/A' }} <br>
                                                P/M:{{ $data['mobile_no'] ?? 'N/A' }}<br>
                                                P/T:{{ $data['type'] ?? 'N/A' }} <br>
                                                P/P:{{ $data['price'] ?? 'N/A' }}/-
                                            @endforeach
                                        @endif
                                    @elseif($item['product'] && $item['product']->product_type == 'ticket_refund')
                                        <strong>Ticket PNR:</strong>
                                        {{ str_replace('REFUND-', '', strtoupper($item['product']->ticket_pnr ?? 'N/A')) }}
                                        <br><strong>Ticket Type:</strong>
                                        {{ $item['product']->ticket_type ?? null }}<br>
                                        @if ($item['product']->sales->sale_customer_id == 0)
                                            <strong> Refund Vendor:</strong> <span
                                                style="color: red;">{{ setting('app_name') }} (Myself)</span>
                                            <br>
                                        @else
                                            <strong>Refund Vendor:</strong>
                                            <span style="color: rgb(33, 166, 255);">
                                                <a
                                                    href="{{ route('admin.customer.details', $item['product']->sales->customer->slug) }}">
                                                    {{ $item['product']->sales->customer->name ?? 'N/A' }}
                                                </a>
                                            </span>
                                        @endif
                                    @elseif ($item['product'] && $item['product']->product_type == 'group_ticket')
                                        <strong>Group Ticket</strong>:<span
                                            style="color:blue">{{ $item['product']->ticket_pnr }}</span>(G-Ticket)<br>
                                        <strong>Issue:</strong>{{ $item['product']->issue_date ?? 'N/A' }}<br>

                                        <strong>P/N:</strong>{{ $item['pax_name'] ?? 'N/A' }}<br>
                                        <strong>P/M:</strong>{{ $item['pax_mobile_no'] ?? 'N/A' }}<br>
                                        <strong>P/T:</strong>{{ $item['pax_type'] ?? 'N/A' }}
                                    @elseif($item['product']->product_type == 'passport')
                                        Passport:{{ strtoupper($item['product']->passport_type ?? 'N/A') }}
                                        <br>
                                        @if ($item['product']->pax_data)
                                            @foreach (json_decode($item['product']->pax_data, true) as $pax)
                                                <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                            @endforeach
                                        @endif
                                    @elseif($item['product']->product_type == 'visa_sale')
                                        Visa:{{ strtoupper($item['product']->visa->visa_name ?? 'N/A') }}[<span
                                            style="color: blue">{{ strtoupper($item['product']->visa_type ?? 'N/A') }}</span>]<br>
                                        @if ($item['product']->pax_data)
                                            @foreach (json_decode($item['product']->pax_data, true) as $pax)
                                                <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                            @endforeach
                                        @endif
                                    @elseif($item['product']->product_type == 'manpower')
                                        Manpower:{{ strtoupper($item['product']->visit_country ?? 'N/A') }}<br>
                                        @if ($item['product']->pax_data)
                                            @foreach (json_decode($item['product']->pax_data, true) as $pax)
                                                <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                            @endforeach
                                        @endif
                                    @elseif($item['product']->product_type == 'hotel_booking')
                                        Hotel Name:{{ strtoupper($item['product']->hotel_name ?? 'N/A') }}<br>
                                        Hotel location:{{ strtoupper($item['product']->hotel_location ?? 'N/A') }}<br>
                                        @if ($item['product']->pax_data)
                                            @foreach (json_decode($item['product']->pax_data, true) as $pax)
                                                <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                            @endforeach
                                        @endif
                                    @elseif($item['product']->product_type == 'custom_bill')
                                        @if ($item['product']->meta_data)
                                            @foreach (json_decode($item['product']->meta_data, true) as $pax)
                                                <strong>{{ $loop->index + 1 }}. </strong>
                                                {{ $pax['service_name'] ?? 'N/A' }}<br>
                                                BDT {{ $pax['service_cost'] ?? 'N/A' }}/-<br>
                                            @endforeach
                                        @endif
                                    @else
                                        <em>No product data available</em>
                                    @endif
                                @elseif ($client_payment)
                                    {{ $item['account_name'] }} <br>{{ $item['account_number'] }}
                                @elseif ($office_payment)
                                    {{ $item['account_name'] }} <br>{{ $item['account_number'] }}
                                @else
                                @endif
                            </td>
                            <td>
                                @if (isset($item['product']) && $item['product'])
                                    @if ($item['product']->travel_status)
                                        @php
                                            $status = strtoupper($item['product']->travel_status);
                                            $departing_date = $item['product']->depart_date;
                                            $journey_from = $item['product']->journey_from;
                                            $journey_to = $item['product']->journey_to;
                                            $return_date = $item['product']->return_date;
                                            $multicity_from = $item['product']->multicity_from;
                                            $multicity_to = $item['product']->multicity_to;
                                            $icon = ' TO ';
                                        @endphp
                                        Airline:{{ $item['product']->airline->IATA ?? 'N/A' }}
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
                                    @elseif ($item['product']->product_type === 'passport')
                                        Track: <span
                                            style="color: blue;">{{ strtoupper($item['product']->tracking_id ?? 'N/A') }}</span><br>
                                        Application:{{ $item['product']->sale_date ?? 'N/A' }}<br>
                                        Delivery:{{ $item['product']->delivery_date ?? 'N/A' }}<br>
                                    @elseif ($item['product']->product_type === 'visa_sale')
                                        Country:{{ $item['product']->visit_country ?? 'N/A' }}<br>
                                        Visa Issue:{{ $item['product']->visa_exp_date ?? 'N/A' }}<br>
                                        Visa Expire:{{ $item['product']->visa_exp_date ?? 'N/A' }}<br>
                                    @elseif ($item['product']->product_type === 'manpower')
                                        Passport No:{{ $item['product']->tracking_id ?? 'N/A' }}<br>
                                        Submitted date:{{ $item['product']->delivery_date ?? 'N/A' }}<br>
                                    @elseif($item['product']->product_type == 'hotel_booking')
                                        Visiting C:
                                        {{ strtoupper($item['product']->visit_country ?? 'N/A') }}<br>
                                        Hotel Stay: {{ $item['product']->hotel_number_of_day ?? 'N/A' }} <br>
                                    @elseif($item['product']->product_type == 'ticket_refund')
                                        REFUND TICKET
                                    @elseif($item['product']->product_type == 'custom_bill')
                                        Other Bill/Services
                                    @else
                                        <em>No product data available</em>
                                    @endif
                                @elseif($client_payment)
                                    Tnx Id - {{ $item['transaction_id'] ?? 'N/A' }}
                                @elseif($office_payment)
                                    Tnx Id: - {{ $item['transaction_id'] ?? 'N/A' }}
                                @endif
                            </td>

                            <td style="color: #059609;">
                                @if ($minus)
                                <strong>{{ currencyBD($minus) }}/-</strong>@else<strong>{{ currencyBD($debit) }}/-</strong>
                                @endif
                            </td>
                            <td style="color: #ff3f02;">{{ currencyBD($credit) }}/=</td>
                            <td style="color: #0b2e13;">{{ currencyBD($runningBalance) }}/=</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>

                        <td colspan="4">

                        </td>

                        <td colspan="3" style="text-align:right">
                            Total Balance: <strong>{{ currencyBD($runningBalance) }}/=</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
    <div class="footer">
        <p style="color:rgba(0,0,0,0.541)">
            Travel Agency Accounting Software. Developed by <strong>Mohammad Robiul Hossain</strong><br>
            <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a> (<a
                href="tel:+8801882850027">+8801882850027</a>)
        </p>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "scrollX": true
            });
        });
    </script>

</body>

</html>
