@extends('backend.layout.app')

@push('css')
    <style>
            .progress-steps-vertical {
        display: flex;
        flex-direction: column-reverse; /* নিচ থেকে উপরে সাজানোর জন্য */
        align-items: flex-start;
    }

    .progress-steps-vertical .step {
        display: flex;
        align-items: center;
        position: relative;
    }

    .progress-steps-vertical .step:last-child {
        margin-bottom: 0;
    }

    .progress-steps-vertical .step i {
        font-size: 1.5rem;
        margin-right: 0.75rem;
    }

    .progress-steps-vertical .step-connector {
        position: absolute;
        left: 0.75rem;
        top: 2rem;
        width: 2px;
        height: calc(100% - 2rem);
        background-color: #dee2e6;
    }

    .progress-steps-vertical .step:last-child .step-connector {
        display: none;
    }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="font-weight-bold">{{ $customer->name }} // <span class="text-success">
                            {{ $customer->phone }}</span></span> <a href="{{ url()->previous() }}"
                        class="btn btn-secondary btn-sm float-right">{{ $customer->name }} <i class="bi bi-arrow-left"></i>
                        Go Back</a>

                </div>

                <div class="card-body">

                    <div class="bill_sent_form float-right mb-3" style=" display:inline-block; vertical-align:top;">
                        <form id="bulk-action-form" method="POST" action="{{ route('admin.customer.bulk-status-update') }}" class="d-flex justify-content-center"
                            style="gap:10px;">
                            @csrf
                            <input type="hidden" name="customer_id" id="customer" value="{{ $customer->id }}">
                            <input type="hidden" name="product_ids" id="product_ids">
                            <select name="status" id="bulk_status" class="form-select form-control select2">
                                <option value="bill_sent">💌 Bill Sent</option>
                                <option value="paid">💰 Paid</option>
                                <option value="unsent">❌ unsent</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm " style="width: 200px">Update
                                Status</button>
                        </form>
                    </div>


                    <div class="table-responsive">
                        <table id="example" class="table table-bordered table-responsive-sm">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="main_checkbox"></th>
                                    <th>Bill No</th>
                                    <th>Service/Client</th>
                                    <th>Description</th>
                                    <th>Purchase Vendor</th>
                                    <th>Sale</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="sub_checkbox" value="{{ $item->product->id }}">
                                        </td>
                                        <td><strong class="text-info">{{ $item->product->invoice_no }}</strong><br>
                                        {{ $item->sale_date }}
                                    <br> {{ $item->product->user->name ?? 'NA' }} 
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

                                        <td>
                                    @php
    $status = $item->product->status ?? 'unsent';

    $steps = [
        'unsent' => ['label' => 'Unsent', 'icon' => 'bi-x-circle-fill', 'color' => 'danger'],
        'bill_sent' => ['label' => 'Bill Sent', 'icon' => 'bi-send-fill', 'color' => 'primary'],
        'paid' => ['label' => 'Paid', 'icon' => 'bi-cash-stack', 'color' => 'success'],
    ];
@endphp


<div class="progress-steps-vertical">
    @foreach($steps as $key => $step)
        @php
            $active = false;
            if($status == 'unsent' && $key == 'unsent') $active = true;
            if($status == 'bill_sent' && in_array($key, ['unsent','bill_sent'])) $active = true;
            if($status == 'paid') $active = true;
        @endphp

        <div class="step">
            <i class="bi {{ $active ? $step['icon'].' text-'.$step['color'] : 'bi-lock-fill text-secondary' }}"></i>
            <span class="{{ $active ? 'text-'.$step['color'].' fw-bold' : 'text-secondary' }}">
                {{ $step['label'] }}
            </span>

            @if(!$loop->last)
                <div class="step-connector"></div>
            @endif
        </div>
    @endforeach
</div>


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

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                pageLength: 25,
                paging: true, 
                lengthMenu: [10, 25, 50, 100, 200], 
            });
        });
        $(document).ready(function() {
            $('.bill_sent_form').hide();
            $('.main_checkbox').on('change', function() {
                $('.sub_checkbox').prop('checked', $(this).is(':checked'));
                toggleForm();
                updateSelectedIds();
            });
            $(document).on('change', '.sub_checkbox', function() {
                let total = $('.sub_checkbox').length;
                let checked = $('.sub_checkbox:checked').length;
                $('.main_checkbox').prop('checked', total === checked);

                toggleForm();
                updateSelectedIds();
            });

            function toggleForm() {
                if ($('.sub_checkbox:checked').length > 0) {
                    $('.bill_sent_form').show();
                } else {
                    $('.bill_sent_form').hide();
                }
            }
            function updateSelectedIds() {
                let ids = [];
                $('.sub_checkbox:checked').each(function() {
                    ids.push($(this).val());
                });
                $('#product_ids').val(ids.join(','));
            }

        

        });
    </script>
@if(session('pdf_url'))
<script>

    window.open("{!! session('pdf_url') !!}");
</script>
@endif

@endpush
