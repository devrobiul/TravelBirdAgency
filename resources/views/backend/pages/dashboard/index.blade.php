@extends('backend.layout.app')

@push('css')

<style>
    body {
        overflow-x: hidden !important; /* horizontal scroll বন্ধ */
    }

    /* === Bigger Simple Card === */
    .stat-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
        transition: 0.2s;
        margin-bottom:20px;
    }
    .stat-card:hover {
        background: #f9fafb;
    }
    .stat-card .card-body {
        display: flex;
        align-items: center;
        padding: 18px;
    }

    /* Icon */
    .stat-icon {
        font-size: 26px;   /* icon বড় */
        width: 55px;       /* বক্স বড় */
        height: 55px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        margin-right: 15px;
    }

    /* Title */
    .stat-title {
        font-size: 15px;   /* টাইটেল একটু বড় */
        font-weight: 600;
        color: #444;
    }

    /* Value */
    .stat-value {
        font-size: 22px;   /* ভ্যালু আরও বড় */
        font-weight: 700;
        color: #111;
    }

    /* Table */
    .custom-table thead {
        background: #4f46e5;
        color: #fff;
    }
    .custom-table th, .custom-table td {
        padding: 10px 12px !important;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

        
    @php
        $cards = [
            ['title' => 'Today Sales', 'value' => $today_sale, 'icon' => 'bi-cart', 'color' => 'bg-primary'],
            ['title' => 'Today Profit', 'value' => $today_sale_profit, 'icon' => 'bi-graph-up', 'color' => 'bg-success'],
            ['title' => 'Today Loss', 'value' => $today_sale_loss, 'icon' => 'bi-graph-down', 'color' => 'bg-danger'],
            ['title' => 'Today Expense', 'value' => $today_expense, 'icon' => 'bi-cash', 'color' => 'bg-dark'],

            ['title' => 'Total Deposit', 'value' => $total_deposit, 'icon' => 'bi-bank', 'color' => 'bg-info'],
            ['title' => 'Total Withdraw', 'value' => $total_withdraw, 'icon' => 'bi-wallet2', 'color' => 'bg-warning'],
            ['title' => 'Total Loss', 'value' => $total_loss, 'icon' => 'bi-exclamation-triangle', 'color' => 'bg-danger'],
            ['title' => 'Total Expenses', 'value' => $total_expense, 'icon' => 'bi-clipboard', 'color' => 'bg-secondary'],

            ['title' => 'Customer Due', 'value' => $total_cus_due, 'icon' => 'bi-person', 'color' => 'bg-warning','link' =>route('admin.customer.index', ['type' => 'due_customer'])],
            ['title' => 'Office Due', 'value' => $total_office_due, 'icon' => 'bi-building', 'color' => 'bg-secondary'],
            ['title' => 'Office Due Count', 'value' => $office_due_count, 'icon' => 'bi-journal', 'color' => 'bg-secondary'],
            ['title' => 'Due Customer', 'value' => $due_customers_count, 'icon' => 'bi-person-dash', 'color' => 'bg-warning'],

            ['title' => 'Total Customers', 'value' => $client, 'icon' => 'bi-people', 'color' => 'bg-info'],
            ['title' => 'Wallet Balance', 'value' => $account_total, 'icon' => 'bi-credit-card', 'color' => 'bg-primary'],
            ['title' => 'Office Wallet', 'value' => $account, 'icon' => 'bi-wallet-fill', 'color' => 'bg-success'],
            ['title' => 'Office Staff', 'value' => $staff, 'icon' => 'bi-person-badge', 'color' => 'bg-dark'],
        ];
    @endphp
    <div class="row gx-3">
            <span class="d-block mb-3 text-danger fw-bold">
        ⚠️ Your domain expires on Jan 17, 2026 — Hosting expires on Feb 2, 2026 ⚠️     <a href="{{ route('admin.customer.dueCustomerList') }}"
                class="btn btn-info btn-sm float-right mr-3">
                 <i class="bi bi-download"></i> Due Customer
            </a>
    </span>
      <div class="col-md-12">
        <div class="row">
         <div class="col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>📊 Sale Profit Loss Monthly Analytics Reports</strong>
                    <form method="GET" action="{{ route('admin.dashboard') }}">
                        @csrf
                        <select name="year" class="form-control form-control-sm select2" onchange="this.form.submit()">
                            <option value="{{ date('Y') }}">Select Year</option>
                            @for ($y = 2025; $y <= 2030; $y++)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="barChart" style="width: 100%; height:400px;"></canvas>
                </div>
            </div>
         </div>
     

      <div class="col-md-6">
        <div class="row">
         @foreach (collect($cards)->take(8) as $card)
        <div class="col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon {{ $card['color'] }}">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                    <div>
                        <span class="stat-title">{{ $card['title'] }}</span>
                        <div class="stat-value">
                            ৳ {{ is_numeric($card['value']) ? currencyBD($card['value']) : $card['value'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
         @endforeach
     </div>
    </div>

        </div>
      </div>
    


   @foreach (collect($cards)->slice(8) as $card)
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon {{ $card['color'] }}">
                            <i class="bi {{ $card['icon'] }}" aria-hidden="true"></i>
                        </div>
                        <div>
                            <span class="stat-title">{{ $card['title'] }}</span>
                            <div class="stat-value">
                                ৳ {{ is_numeric($card['value']) ? currencyBD($card['value']) : $card['value'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @php
    $allPayments = $todayClientPayment->merge($todayOfficePayment);
@endphp

<div class="col-md-12">
    <div class="card stat-card mb-3">
        <div class="card-header">
            <h4>💳 Today Client Payments ({{ currencyBD($todayClientPayment->sum('amount')) }}/=) /Office Payments ({{ currencyBD($todayOfficePayment->sum('amount')) }}/=)</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered custom-table">
                    <thead>
                        <tr>
                            <th>Approval</th>
                            <th>Payment</th>
                            <th>Account</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allPayments as $item)
                            <tr>
                                <td>{{ $item->user->name }}<br><small>{{ $item->transaction_date }}</small></td>
                                <td>
                                    {{ ucfirst( $item->payment_type) }}
                                    <br>[{{ $item->customer->name ?? 'N/A' }}]
                                    <br>[{{ $item->customer->phone ?? 'N/A' }}]
                                </td>
                                <td>
                                    {{ $item->fromAccount->account_name ?? 'NA' }}
                                    <br>[{{ $item->fromAccount->account_number ?? 'NA' }}]
                                </td>
                                <td>
                                    <strong>{{ currencyBD($item->amount) }}/=</strong>
                                    <br><small>{{ $item->transaction_id }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No payment found for today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
   @if (!$sales->isEmpty())
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Today Sale and purchase Record</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered table-responsive-sm">
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
                                            <td>Sale date: {{ $item->sale_date }} <br>
                                                Saler: {{ $item->product->user->name }} <br>
                                                Invoice: {{ $item->product->invoice_no }}
                                            </td>
                                            <td>
                                                @if ($item->product && $item->product->product_type == 'single_ticket')
                                                    <strong>Ticket</strong>: <span
                                                        style="color:blue">{{ $item->product->ticket_pnr }}</span>
                                                    ({{ $item->product->ticket_type ?? 'N/A' }})
                                                    <br>
                                                    <strong>Sale:</strong> {{ $item->product->sale_date ?? 'N/A' }}<br>
                                                    
                                                        @foreach (json_decode($item->product->pax_data,true) as $pax)
                                                            <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                            <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                             P/T:{{ $pax['type'] ?? 'N/A' }} <br>
                                                              P/P:{{ $pax['price'] ?? 'N/A' }}/-<br>
                                                        @endforeach
                                                 
                                                    <strong>C/N:</strong> <span style="color: rgb(33, 166, 255);"> <a
                                                            href="{{ route('admin.customer.details', $item->product->sales->customer->slug) }}">{{ $item->product->sales->customer->name ?? null }}</a>
                                                    </span><br>
                                                    <strong>C/P:</strong>
                                                    {{ $item->product->sales->customer->phone ?? null }}<br>
                                                @elseif($item->product && $item->product->product_type == 'ticket_refund')
                                                    <strong>Ticket PNR:</strong>
                                                    {{ str_replace('REFUND-', '', strtoupper($item->product->ticket_pnr ?? 'N/A')) }}
                                                    <br><strong>Ticket Type:</strong>
                                                    {{ $item->product->ticket_type ?? null }}<br>
                                                           @if ($item->product->sales->sale_customer_id == 0)
                                                    <strong> Refund Vendor:</strong> <span style="color: red;">{{ setting('app_name') }} (Myself)</span>
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
                                                    <strong>Ticket</strong>:<span
                                                        style="color:blue">{{ $item->product->ticket_pnr }}</span>({{ $item->product->ticket_type ?? 'N/A' }})<br>
                                                    <strong>Issue:</strong>{{ $item->product->issue_date ?? 'N/A' }}<br>
                                                    <strong>C/N:</strong> <span style="color: rgb(33, 166, 255);"> <a
                                                            href="{{ route('admin.customer.details', $item->product->sales->customer->slug) }}">{{ $item->product->sales->customer->name ?? null }}</a>
                                                    </span><br>
                                                    <strong>P/N:</strong>{{ $item->pax_name ?? 'N/A' }}<br>
                                                    <strong>P/M:</strong>{{ $item->pax_mobile_no ?? 'N/A' }}<br>
                                                    <strong>P/T:</strong>{{ $item->pax_type ?? 'N/A' }}
                                                @elseif($item->product && $item->product->product_type == 'passport')
                                                    <strong>Passport:</strong>
                                                    {{ strtoupper($item->product->passport_type ?? 'N/A') }}<br>
                                                          @foreach (json_decode($item->product->pax_data,true) as $pax)
                                                            <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                            <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                      
                                                        @endforeach
                                                    <strong>C/N:</strong> <span style="color: rgb(33, 166, 255);"> <a
                                                            href="{{ route('admin.customer.details', $item->product->sales->customer->slug) }}">{{ $item->product->sales->customer->name ?? null }}</a>
                                                    </span><br>
                                                    <strong>C/P:</strong>
                                                    {{ $item->product->sales->customer->phone ?? null }}<br>
                                                @elseif($item->product && $item->product->product_type == 'visa_sale')
                                                    <strong>Visa:</strong>
                                                    {{ strtoupper($item->product->visa->visa_name ?? 'N/A') }}
                                                    [<span
                                                        style="color: blue">{{ strtoupper($item->product->visa_type ?? 'N/A') }}</span>]<br>
                                                       @foreach (json_decode($item->product->pax_data,true) as $pax)
                                                            <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                            <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                      
                                                        @endforeach
                                                    <strong>C/N:</strong> <span style="color: rgb(33, 166, 255);"> <a
                                                            href="{{ route('admin.customer.details', $item->product->sales->customer->slug) }}">{{ $item->product->sales->customer->name ?? null }}</a>
                                                    </span><br>
                                                    <strong>C/P:</strong>
                                                    {{ $item->product->sales->customer->phone ?? null }}<br>
                                                @elseif($item->product && $item->product->product_type == 'manpower')
                                                    <strong>Manpower:</strong>
                                                    {{ $item->product->visit_country }}
                                                    [<span
                                                        style="color: blue">{{ strtoupper($item->product->tracking_id ?? 'N/A') }}</span>]<br>
                                                @foreach (json_decode($item->product->pax_data,true) as $pax)
                                                            <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                            <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                      
                                                        @endforeach
                                                    <strong>C/N:</strong> <span style="color: rgb(33, 166, 255);"> <a
                                                            href="{{ route('admin.customer.details', $item->product->sales->customer->slug) }}">{{ $item->product->sales->customer->name ?? null }}</a>
                                                    </span><br>
                                                    <strong>C/P:</strong>
                                                    {{ $item->product->sales->customer->phone ?? null }}<br>
                                                @elseif($item->product->product_type == 'hotel_booking')
                                                    Hotel Name :{{ strtoupper($item->product->hotel_name ?? 'N/A') }}<br>
                                                    Location
                                                    :{{ strtoupper($item->product->hotel_location ?? 'N/A') }}<br>
                                                       @foreach (json_decode($item->product->pax_data,true) as $pax)
                                                            <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                            <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                      
                                                        @endforeach
                                                @elseif($item->product->product_type == 'custom_bill')
                                                       @foreach (json_decode($item->product->meta_data,true) as $pax)
                                                            <strong>{{$loop->index+1}}. </strong> {{ $pax['service_name'] ?? 'N/A' }}<br>
                                                            <strong>BDT</strong> {{ $pax['service_cost'] ?? 'N/A' }}/-<br>
                                                      
                                                        @endforeach
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
                                                        $icon = '----';
                                                    @endphp
                                                    <strong>Travel Status: </strong>{{ $status }}<br>
                                                    <strong>Airline: </strong>
                                                    {{ $item->product->airline->IATA ?? 'N/A' }}<br>
                                                    @if ($status === 'ONEWAY')
                                                        <strong>Departing: </strong>{{ $departing_date ?? 'N/A' }}<br>
                                                        {!! $journey_from . $icon . $journey_to !!}
                                                    @elseif ($status === 'ROUNDTRIP')
                                                        <strong>Departing: </strong>{{ $departing_date ?? 'N/A' }}<br>
                                                        <strong>Returning: </strong>{{ $return_date ?? 'N/A' }}<br>
                                                        {!! $journey_from . $icon . $journey_to !!}
                                                    @elseif ($status === 'MULTICITY')
                                                        <strong>Departing: </strong>{{ $departing_date ?? 'N/A' }}<br>
                                                        <strong>Returning: </strong>{{ $return_date ?? 'N/A' }}<br>
                                                        {!! $journey_from . $icon . $journey_to !!}<br>
                                                        {!! $multicity_from . $icon . $multicity_to !!}
                                                    @endif
                                                @elseif($item->product->product_type == 'passport')
                                                    <strong>Track</strong>:<span
                                                        style="color: blue">{{ strtoupper($item->product->tracking_id ?? 'N/A') }}</span>
                                                    <br>
                                                    <strong>Application</strong>:
                                                    {{ $item->product->sale_date ?? 'N/A' }} <br>
                                                    <strong>Delivery</strong>:
                                                    {{ $item->product->delivery_date ?? 'N/A' }}
                                                    <br>
                                                @elseif($item->product->product_type == 'visa_sale')
                                                    <strong>Country</strong>: {{ $item->product->visit_country ?? 'N/A' }}
                                                    <br>
                                                    <strong>Visa Issue</strong>:
                                                    {{ $item->product->visa_exp_date ?? 'N/A' }}
                                                    <br>
                                                    <strong>Visa Expire</strong>:
                                                    {{ $item->product->visa_exp_date ?? 'N/A' }}
                                                    <br>
                                                @elseif($item->product->product_type == 'manpower')
                                                    <strong>Country</strong>: {{ $item->product->visit_country ?? 'N/A' }}
                                                    <br>
                                                    <strong>Submitted Date</strong>:
                                                    {{ $item->product->delivery_date ?? 'N/A' }} <br>
                                                @elseif($item->product->product_type == 'hotel_booking')
                                                    Visiting C:
                                                    {{ strtoupper($item->product->visit_country ?? 'N/A') }}<br>
                                                    Hotel Stay: {{ $item->product->hotel_number_of_day ?? 'N/A' }} <br>
                                                @elseif($item->product->product_type == 'ticket_refund')
                                                    REFUND TICKET
                                                @elseif($item->product->product_type == 'custom_bill')
                                                    Custom Bill/Services <br>
                                                         <strong>C/N:</strong> <span style="color: rgb(33, 166, 255);"> <a
                                                            href="{{ route('admin.customer.details', $item->product->sales->customer->slug) }}">{{ $item->product->sales->customer->name ?? null }}</a>
                                                    </span><br>
                                                    <strong>C/P:</strong>
                                                    {{ $item->product->sales->customer->phone ?? null }}<br>
                                                @else
                                                    <em>No product data available</em>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->product->purchase->purchase_vendor_id == 0)
                                                    <span style="color: red;">{{ setting('app_name') }} (Myself)</span> <br>
                                                    AC: {{ $item->product->purchase->fromAccount->account_name ?? null }}<br>
                                                    A/N:
                                                    {{ $item->product->purchase->fromAccount->account_number ?? null }}<br>
                                                @else
                                                    @if ($item->product->product_type == 'refund_ticket')
                                                        R/Client
                                                    @endif
                                                    <strong style="color:rgb(5, 151, 29)"><a
                                                            href="{{ route('admin.customer.details', $item->product->purchase->vendor->slug) }}"><i
                                                                class="fas fa-link    "></i>
                                                            {{ $item->product->purchase->vendor->name ?? (null ?? 'N/A') }}</a></strong>
                                                    <br>
                                                @endif
                                                Purchase:
                                                {{ currencyBD($item->product->purchase->purchase_price ?? null) }}/=
                                                <br>
                                            </td>

                                            <td>
                                                Price: {{ currencyBD($item->sale_price) }}/=<br>
                                                Profit: {{ currencyBD($item->sale_profit) }}/=<br>
                                                Loss: {{ currencyBD($item->sale_loss) }}/=
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:right"><strong>Total:</strong></td>
                                        <td>Price: {{ currencyBD($sale_price) }}/=</td>                                    
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var months = @json($months_Chart);
    var sales = @json($sales_Chart);
    var saleLoss = @json($sale_loss_Chart);
    var saleProfit = @json($sale_profit_Chart);
    var ctx = document.getElementById('barChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Sales', data: sales, backgroundColor: '#3b82f6' },
                { label: 'Loss', data: saleLoss, backgroundColor: '#ef4444' },
                { label: 'Profit', data: saleProfit, backgroundColor: '#10b981' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 } },
                y: { beginAtZero: true, title: { display: true, text: 'Amount' } }
            }
        }
    });
</script>
@endpush
