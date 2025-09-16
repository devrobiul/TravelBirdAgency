@extends('backend.layout.app')
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-center align-items-center">
                <form action="{{ route('admin.customerBalanceSheetPdf', $customer->id) }}" method="POST" class="mr-3">
                    @csrf
                    <input type="hidden" value="{{ $start_date }}" name="start_date">
                    <input type="hidden" value="{{ $end_date }}" name="end_date">
                    <button type="submit" class="btn btn-info btn-sm">
                        <i class="bi bi-download"></i> Download balance sheet
                    </button>
                </form>

                <a href="{{ route('admin.customer.details', $customer->slug) }}" class="btn btn-sm btn-secondary"
                    rel="noopener noreferrer">
                    <i class="fa fa-minus" aria-hidden="true"></i> Go Back Customer
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-lg table-bordered">
                        <thead style="background: none;">
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
                                    style="{{ $item['type'] == 'purchase' ? 'color:#ff0004;' : '' }}
                                            {{ $client_payment ? 'color: #038c11;' : '' }}
                                            {{ $office_payment ? 'color: #6a7bd4;' : '' }}">
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
                                                        <strong>P/N:</strong>{{ $data['name'] ?? 'N/A' }} <br>
                                                        <strong>P/M:</strong>{{ $data['mobile_no'] ?? 'N/A' }}<br>
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
                                                Hotel
                                                location:{{ strtoupper($item['product']->hotel_location ?? 'N/A') }}<br>
                                                @if ($item['product']->pax_data)
                                                    @foreach (json_decode($item['product']->pax_data, true) as $pax)
                                                        <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                        <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                    @endforeach
                                                @endif
                                            @elseif($item['product']->product_type == 'custom_bill')
                               
                                                @if ($item['product']->meta_data)
                                                    @foreach (json_decode($item['product']->meta_data, true) as $pax)
                                                        <strong>{{ $loop->index+1 }}. </strong> {{ $pax['service_name'] ?? 'N/A' }}<br>
                                                      </strong> {{ $pax['service_cost'] ?? 'N/A' }}/-<br>
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
                                               Other Services <br>

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
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                pageLength: 100, // Sets the number of rows per page to 100
                paging: true, // Enables pagination
                lengthMenu: [10, 25, 50, 100, 200], // Optional: Custom page length menu
            });
        });
    </script>
@endpush
