@extends('backend.layout.app')

@push('css')
    <style>
        /* Small UI polish */
        .stat-card {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(26, 30, 40, 0.06);
        }

        .stat-card .card-body {
            padding: 14px;
        }

        .stat-number {
            font-size: 1.05rem;
            font-weight: 600;
        }

        .stat-label {
            font-size: .9rem;
            color: #1c1d1d;
            margin-bottom: .25rem;
        }

        .stat-icon {
            font-size: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            border-radius: 10px;
            background: rgba(59, 130, 246, .08);
        }

        .stat-icon.negative {
            background: rgba(16, 185, 129, .08);
        }

        .stat-icon.positive {
            background: rgba(239, 68, 68, .06);
        }

        .card-header .title {
            font-size: 20px;
            font-weight: 600;
        }

        .small-note {
            font-size: .85rem;
            color: #6b7280;
        }

        .action-btns a {
            margin-right: .35rem;
        }

        .form-inline-row .form-group {
            margin-bottom: .75rem;
        }

        .copy-feedback {
            display: none;
            font-size: .85rem;
            color: #10b981;
            margin-left: .5rem;
        }
    </style>
@endpush

@section('content')
    <div class="col-md-12">
        <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h3 class="mb-0 fw-bold">{{ $customer->name ?? 'Customer' }}({{ $customer->phone ?? '' }})</h3>
            </div>
            <button class="btn btn-secondary btn-sm show-modal mb-2"
                data-url="{{ route('admin.customer.edit', $customer->id) }}">
                <i class="bi bi-pencil"></i> Edit Customer
            </button>
            <div class="input-group input-group-sm" style="max-width:400px;">
                <input type="text" id="referralLink"
                    value="{{ route('customerCheckReport', [$customer->slug, $customer->uuid]) }}" readonly
                    class="form-control">
                <button class="btn btn-sm btn-dark" id="copyBtn" type="button">
                    <i class="bi bi-clipboard" id="copyIcon"></i>
                    <span id="copyMessage" class="d-none">Copied!</span>
                </button>

            </div>
        </div>


        <div class="row g-3">
            {{-- Total Balance --}}
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="stat-icon">
                                <i class="bi bi-wallet2" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-label">Total Balance</div>
                            <div class="stat-number">৳ {{ currencyBD($balance) }}/=</div>
                            <div class="small-note">Overall balance for this customer</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GoTrip Due (negative balance shown as positive amount) --}}
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="stat-icon negative">
                                <i class="bi bi-cash-stack" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-label">Office Due</div>
                            <div class="stat-number">
                                ৳
                                @if ($balance < 0)
                                    {{ currencyBD(abs($balance)) }}/=
                                @else
                                    0.00/=
                                @endif
                            </div>
                            <div class="small-note">Amount owed to GoTrip</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Sale --}}
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="stat-icon">
                                <i class="bi bi-receipt" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-label">Total Sale</div>
                            <div class="stat-number">৳ {{ currencyBD($total_sale) }}/=</div>
                            <div class="small-note">All sales for this customer</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Last Payment --}}
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="stat-icon">
                                <i class="bi bi-clock-history" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-label">Last Payment</div>
                            <div class="stat-number">৳ {{ currencyBD($last_payment->amount ?? 0) }}/=</div>
                            <div class="small-note">
                                {{ optional($last_payment)->created_at ? optional($last_payment)->created_at->format('d M, Y') : 'No data found' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gotrip Purchase --}}
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="stat-icon">
                                <i class="bi bi-basket3" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-label">Office Purchase</div>
                            <div class="stat-number">৳ {{ currencyBD($total_purchase) }}/=</div>
                            <div class="small-note">Total purchase amount</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- G/T Last Payment --}}
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="stat-icon">
                                <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-label">Office Last Payment</div>
                            <div class="stat-number">৳ {{ currencyBD($office_last_payment->amount ?? 0) }}/=</div>
                            <div class="small-note">Office Last payment</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Registered --}}
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-label">Registered</div>
                            <div class="stat-number">{{ $customer->created_at->format('d F, Y') }}</div>
                            <div class="small-note">Customer since registration date</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment & Forms Column --}}
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="title"> Payment</span>
                        <a href="{{ route('admin.customer.allTransaction', $customer->slug) }}"
                            class="btn btn-dark btn-sm"><i class="fas fa-sync"></i> All Transction</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.customer.transaction', $customer->id) }}" method="post"
                            enctype="multipart/form-data" id="paymentForm">
                            @csrf
                            <div class="row form-inline-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_type">Payment type</label>
                                        <select name="payment_type" id="payment_type"
                                            class="select2 form-control form-control-sm" required
                                            onchange="toggleGotripFields()">
                                            <option value="client_payment">Client Payment</option>
                                            <option value="office_payment">Office Payment</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_id">Account Name</label>
                                        <select name="from_account_id" id="account_id"
                                            class="select2 form-control form-control-sm"
                                            onchange="updateAccountNumber(event)" required>
                                            <option value="">Select Method</option>
                                            @foreach ($account as $item)
                                                <option value="{{ $item->id }}"
                                                    data-number="{{ $item->account_number }}">
                                                    {{ $item->account_name }} — {{ currencyBD($item->current_balance) }}/=
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_number">Account Number</label>
                                        <input type="text" id="account_number" class="form-control form-control-sm"
                                            readonly placeholder="Account Number">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="deposit_amount">Pay Amount</label>
                                        <input type="number" name="amount" id="deposit_amount"
                                            class="form-control form-control-sm" placeholder="Pay Amount" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="transaction_id">Transaction Id</label>
                                        <input type="text" name="transaction_id" id="transaction_id"
                                            class="form-control form-control-sm" placeholder="Transaction Id" required>
                                    </div>
                                </div>

                                {{-- Gotrip to Client fields --}}
                                <div class="col-md-4" style="">
                                    <div class="form-group">
                                        <label for="account_name">Client Account Name</label>
                                        <input type="text" name="account_name" id="account_name"
                                            class="form-control form-control-sm" placeholder="Client Account Name">
                                    </div>
                                </div>

                                <div class="col-md-4" style="">
                                    <div class="form-group">
                                        <label for="client_account_no">Client Account No</label>
                                        <input type="text" name="transaction_number" id="client_account_no"
                                            class="form-control form-control-sm" placeholder="Client Account No">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="transaction_date">Trasnaction date</label>
                                        <input type="date" name="transaction_date" id="transaction_date"
                                            max="{{ date('Y-m-d') }}" value="{{ old('deposit_date', date('Y-m-d')) }}"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="deposit_note">Trasnaction note</label>
                                        <input type="text" name="note" id="note"
                                            class="form-control form-control-sm" placeholder="Trasnaction note">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 text-center">
                                <button type="submit" class="btn btn-dark btn-sm"><i class="bi bi-save"></i> Payment
                                    Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Reports & History Column --}}
            <div class="col-12 col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">Sale & Purchase Report</div>
                    <div class="card-body">
                        <form method="get" action="{{ route('admin.customer.saleReport', $customer->id) }}"
                            class=" g-2">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-2">
                                    <input name="start_date" id="start_date" type="date"
                                        class=" form-control form-control-sm" placeholder="start date" />
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <input name="end_date" id="end_date" type="date"
                                        class=" form-control form-control-sm"max="{{ date('Y-m-d') }}"
                                        value="{{ old('end_date', date('Y-m-d')) }}" required />
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-sync"></i>
                                    Check</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">{{ $customer->name }} (Payment History)</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.b2bTransactionReport', $customer->id) }}">
                            @csrf
                            <div class="row g-2">
                                <div class="col-12 col-md-6 mb-2">
                                    <input name="start_date" id="start_date_tnx" type="date"
                                        class=" form-control form-control-sm" placeholder="start date" />
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <input name="end_date" id="end_date_tnx" type="date"
                                        class=" form-control form-control-sm" max="{{ date('Y-m-d') }}"
                                        value="{{ old('end_date', date('Y-m-d')) }}" />
                                </div>

                            </div>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-dark btn-sm"><i class="fas fa-sync"></i>
                                    Check</button>
                            </div>
                        </form>
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
                        Today Sale and purchase Record Latest 10

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
                                                Saler: {{ $item->product->user->name ?? 'NA' }} <br>
                                                Invoice: {{ $item->product->invoice_no }}
                                            </td>
                                            <td>
                                                @if ($item->product && $item->product->product_type == 'single_ticket')
                                                    <strong>Ticket</strong>: <span
                                                        style="color:blue">{{ $item->product->ticket_pnr }}</span>
                                                    ({{ $item->product->ticket_type ?? 'N/A' }})
                                                    <br>
                                                    <strong>Sale:</strong> {{ $item->product->sale_date ?? 'N/A' }}<br>
                                                    @if ($item->product->pax_data)
                                                        @foreach (json_decode($item->product->pax_data, true) as $pax)
                                                            <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                            <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                            <strong>P/T:</strong> {{ $pax['type'] ?? 'N/A' }}<br>
                                                            <strong>P/P:</strong> {{ $pax['price'] ?? 'N/A' }}/-<br>
                                                        @endforeach
                                                    @endif
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
                                                        <strong> Refund Vendor:</strong> <span
                                                            style="color: red;">{{ setting('app_name') }} (Myself)</span>
                                                        <br>
                                                    @else
                                                        <strong>Refund Vendor:</strong> <span
                                                            style="color: rgb(33, 166, 255);">
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
                                                    @foreach (json_decode($item->product->pax_data, true) as $pax)
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
                                                    @foreach (json_decode($item->product->pax_data, true) as $pax)
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
                                                    @foreach (json_decode($item->product->pax_data, true) as $pax)
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
                                                    @if ($item->product->pax_data)
                                                        @foreach (json_decode($item->product->pax_data, true) as $pax)
                                                            <strong>P/N:</strong> {{ $pax['name'] ?? 'N/A' }}<br>
                                                            <strong>P/M:</strong> {{ $pax['mobile_no'] ?? 'N/A' }}<br>
                                                        @endforeach
                                                    @endif
                                                @elseif($item->product && $item->product->product_type == 'custom_bill')
                                                    <strong>Custom/Bill:</strong> <br>
                                                    @foreach (json_decode($item->product->meta_data, true) as $pax)
                                                        <strong>{{ $loop->index + 1 }}</strong>
                                                        {{ $pax['service_name'] ?? 'N/A' }}<br>
                                                        {{ $pax['service_cost'] ?? 'N/A' }}/- <br>
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
                                                    Other Service/Bill <br>
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
                                                    <span style="color: red;">{{ setting('app_name') }} (Myself)</span>
                                                    <br>
                                                    AC:
                                                    {{ $item->product->purchase->fromAccount->account_name ?? null }}<br>
                                                    A/N:
                                                    {{ $item->product->purchase->fromAccount->account_number ?? null }}<br>
                                                @else
                                                    @if ($item->product->product_type == 'ticket_refund')
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

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function updateAccountNumber(event) {
            const selectedOption = event.target.selectedOptions ? event.target.selectedOptions[0] : null;
            const accountNumber = selectedOption ? selectedOption.getAttribute('data-number') : '';
            const accountNumberInput = document.getElementById('account_number');
            accountNumberInput.value = accountNumber || '';
        }

        function toggleGotripFields() {
            const paymentType = document.getElementById('payment_type').value;
            const fields = document.querySelectorAll('.gotripToClientFields');
            fields.forEach(el => el.style.display = (paymentType === 'office_payment') ? 'block' : 'none');
        }

        document.addEventListener("DOMContentLoaded", function() {
            toggleGotripFields(); // initial
        });

        $(document).ready(function() {
            $('#copyBtn').click(function() {
                var copyText = $('#referralLink');
                copyText.select();
                copyText[0].setSelectionRange(0, 99999); // Mobile devices

                document.execCommand("copy");

                // Show "Copied!" feedback
                $('#copyIcon').addClass('d-none');
                $('#copyMessage').removeClass('d-none');

                setTimeout(function() {
                    $('#copyMessage').addClass('d-none');
                    $('#copyIcon').removeClass('d-none');
                }, 1000); // 1 second
            });
        });
    </script>
@endpush
