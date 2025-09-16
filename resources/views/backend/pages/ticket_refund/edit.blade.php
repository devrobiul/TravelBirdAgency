@extends('backend.layout.app')

@push('css')
    <style>
        .select2-container .select2-selection--single {
            height: 33px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
        }

        .select2 {
            width: 100% !important;
        }

        .details_show_hide {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-between">
                        <div><button class="btn btn-sm btn-danger click_pnr"><i class="fa fa-plus"></i>Click Ticket details
                                ({{ $ticket->ticket_pnr }})
                            </button></div>
                        <div>

                            <a class="btn btn-sm btn-secondary" href="{{ route('admin.inventory.refundticket.index') }}"><i
                                    class="fa fa-minus"></i>
                                Back All Refund</a>
                        </div>
                    </div>
                </div>
                <div class=" card-body details_show_hide">
                    <div class="row ">
                        <div class="col-md-4">
                            <table class="table table-bordered" style="width:100%">
                                <tbody>
                                    @if ($ticket->purchase->purchase_vendor_id == 0)
                                        <tr>
                                            <td>Purchase Vendor</td>
                                            <td>{{ setting('app_name') }} (My Self)</td>
                                        </tr>
                                        <tr>
                                            <td>ACC</td>
                                            <td>{{ $ticket->purchase->account->account_name ?? 'N/A' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>Purchase Vendor</td>
                                            <td>{{ $ticket->purchase->vendor->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td width="">Purchase History</td>
                                        <td width="">
                                            Price: {{ currencyBD($ticket->purchase->purchase_price ?? null) }}/= <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="">Issue Date</td>
                                        <td width="">{{ $ticket->issue_date }}</td>
                                    </tr>
                                    <tr>
                                        <td width="">Note</td>
                                        <td width="">{{ $ticket->purchase->purchase_note }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered" style="width:100%">
                                <tbody>
                                    <tr>
                                        <td width="">Sale Vendor</td>
                                        <td width="">{{ $ticket->sales->customer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="">Sale History</td>
                                        <td width="">
                                            Price: {{ currencyBD($ticket->sales->sale_price) }}/=
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="">Sale profit</td>
                                        <td width="">{{ currencyBD($ticket->sales->sale_profit) }}/=</td>
                                    </tr>
                                    <tr>
                                        <td width="">Sale loss</td>
                                        <td width="">{{ currencyBD($ticket->sales->sale_loss) }}/=</td>
                                    </tr>
                                    <tr>
                                        <td width="">Sale Date</td>
                                        <td width="">{{ $ticket->sales->sale_date }}</td>
                                    </tr>

                                    <tr>
                                        <td width="">Sale Note</td>
                                        <td width="">{{ $ticket->sales->sale_note }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered" style="width:100%">
                                <tbody>
                                    <tr>
                                        <td width="">Airline</td>
                                        <td width="">
                                            {{ $ticket->airline->IATA }}-{{ $ticket->airline->Airline }}-{{ $ticket->airline->Country }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="">Travel Status</td>
                                        <td width="">{{ strtoupper($ticket->travel_status) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="">Date</td>
                                        <td width="">Departing: {{ $ticket->depart_date }} <br>
                                            @if ($ticket->travel_status == 'roundtrip' || $ticket->travel_status == 'multicity')
                                                Returning : {{ $ticket->return_date }}
                                            @endif
                                        </td>
                                    </tr>




                                    <tr>
                                        <td width="">Airports</td>
                                        <td width="">{{ $ticket->journey_from }} <i class="fa fa-plane"
                                                aria-hidden="true"></i> {{ $ticket->journey_to }} <br>
                                            @if ($ticket->travel_status == 'multicity')
                                                {{ $ticket->multicity_from }} <i class="fa fa-plane"
                                                    aria-hidden="true"></i>
                                                {{ $ticket->multicity_to }}
                                            @endif
                                        </td>


                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <div class="col-md-12">
                            <table class="table table-bordered" style="width:100%">
                                <thead>
                                    <th>PAX NAME</th>
                                    <th>PAX PHONE</th>
                                    <th>PAX TYPE</th>
                                </thead>
                                <tbody>
                                    @foreach ($pax_data as $item)
                                        <tr>

                                            <td>{{ $item['name'] }}</td>


                                            <td width="">
                                                {{ $item['mobile_no'] }}
                                            </td>


                                            <td width="">
                                                {{ $item['type'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <form class="formSubmit" method="POST"
                        action="{{ route('admin.inventory.refundticket.update', $ticketRefund->id) }}">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="refund_pnr">Ticket PNR <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="refund_pnr" id="refund_pnr" readonly class="form-control "
                                        value="{{ $ticket->ticket_pnr }}" placeholder="Ticket PNR" />
                                </div>
                                <span class="text-danger error-message" id="error-refund_pnr"></span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="customer_name">Customer Name <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="customer_name" id="customer_name" readonly
                                        class="form-control " value="{{ $ticket->sales->customer->name }}"
                                        placeholder="Customer Name" />
                                </div>
                                <span class="text-danger error-message" id="error-ticket_pnr"></span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="phone">Customer Mobile <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="phone" id="phone" readonly class="form-control "
                                        value="{{ $ticket->sales->customer->phone }}" placeholder="Customer Mobile" />
                                </div>
                                <span class="text-danger error-message" id="error-ticket_pnr"></span>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="refund_vendor_id">Refund Vendor</label>
                                <div class="input-group">
                                    <select name="refund_vendor_id" id="refund_vendor_id"
                                        class="single-select form-control select2 refund_vendor_id">
                                        <option value="0"
                                            {{ $ticketRefund->refund_vendor_id == 0 ? 'selected' : '' }}>
                                            My self</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $ticketRefund->refund_vendor_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                                || {{ $item->phone }} || {{ $item->passport_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="refund_amount">Refund Amount<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span
                                        class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                        <i class="fa fa-pen"></i>
                                    </span>
                                    <input name="refund_amount" id="refund_amount" type="number" class="form-control "
                                        value="{{ $ticketRefund->refund_amount }}" placeholder="Enter Refund Amount" />
                                </div>
                                <span class="text-danger error-message" id="error-refund_amount"></span>

                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="customer_refund">Customer Refund <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span
                                        class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                        <i class="fa fa-pen"></i>
                                    </span>
                                    <input name="customer_refund" id="customer_refund" type="number"
                                        class="form-control "value="{{ $ticketRefund->customer_refund }}"
                                        placeholder="Enter Customer Refund" />
                                </div>
                                <span class="text-danger error-message" id="error-customer_refund"></span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="refund_profit">Refund Profit<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">

                                    <input name="refund_profit" id="refund_profit" type="number" readonly
                                        class="form-control "value="{{ $ticketRefund->refund_profit }}"
                                        placeholder="00" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="refund_date">Refund Submit Date</label>
                                <div class="input-group">
                                    <span
                                        class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input name="refund_date" id="refund_date" type="date"
                                        class="form-control " value="{{ $ticketRefund->refund_date }}"
                                        placeholder="dd/mm/yyyy" />
                                </div>

                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="mb-0" for="refund_expected_date">Refund Excepted Date</label>
                                <div class="input-group">
                                    <span
                                        class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input name="refund_expected_date" id="refund_expected_date" type="date"
                                        class=" form-control "
                                        value="{{ $ticketRefund->refund_expected_date }}" placeholder="dd/mm/yyyy" />
                                </div>

                            </div>
                            @if ($ticket->purchase->purchase_vendor_id !== 0)
                                <div class="col-md-6">
                                    <label class="mb-0" for="profit_account_id">Send profit
                                        Method</label>
                                    <div class="input-group">
                                        <select name="profit_account_id" id="profit_account_id"
                                            class="single-select form-control select2">
                                            <option value="">Select Method</option>
                                            @foreach (App\Models\AgencyAccount::all() as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $ticketRefund->profit_account_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->account_name }} ||
                                                    {{ currencyBD($item->current_balance) }}/=
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <button class="btn btn-secondary btn-sm" type="submit"> <i class="fas fa-sync"></i> Edit
                                 Refund</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2(); // Initialize select2
        });
        $(document).ready(function() {
            $('.click_pnr').on('click', function() {
                $('.details_show_hide').toggle();
                if ($('.details_show_hide').is(':visible')) {
                    $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
                } else {
                    $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
                }
            });
        });
        $(document).ready(function() {
            // Trigger calculation when values in either of the fields change
            $('#refund_amount, #customer_refund').on('input', function() {
                var salePrice = parseFloat($('#refund_amount').val()) ||
                    0; // Get sale price value or 0 if not valid
                var purchasePrice = parseFloat($('#customer_refund').val()) ||
                    0; // Get purchase price value or 0 if not valid

                // Ensure that purchase_price does not exceed sale_price
                if (purchasePrice > salePrice) {
                    alert("Purchase price cannot be greater than sale price");
                    $('#customer_refund').val(salePrice); // Reset purchase price to sale price
                    purchasePrice = salePrice; // Set purchasePrice to salePrice for calculation
                }

                // Calculate profit (sale_price - purchase_price)
                var profit = salePrice - purchasePrice;

                // Set the calculated profit value to sale_profit field
                $('#refund_profit').val(profit);
            });

            // Additional check to make sure only numbers are entered
            $('#refund_amount, #purchase_price').on('input', function() {
                // Allow only numbers and prevent non-numeric characters
                this.value = this.value.replace(/[^0-9.]/g, '');
            });
        });
    </script>
@endpush
