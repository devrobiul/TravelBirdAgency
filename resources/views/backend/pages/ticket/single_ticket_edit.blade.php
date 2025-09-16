
@extends('backend.layout.app') @push('css')
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
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-body">
                <div class="card-title d-flex justify-content-between">
                    <h4 class="text-center mb-4">

                        Edit Ticket

                        <a data-url="" class="btn btn-sm btn-secondary show-modal"><i class="fa fa-plus-circle"
                                aria-hidden="true"></i> Add Client</a>
                    </h4>

                    <div>
                        <a class="btn btn-sm btn-secondary" href="{{ route('admin.inventory.singleticket.index') }}"><i
                                class="fa fa-plus-circle" aria-hidden="true"></i> All
                            Ticket</a>

                    </div>
                </div>

                <form action="{{ route('admin.inventory.singleticket.update', $ticket->id) }}" method="POST"
                    enctype="multipart/form-data" class="formSubmit">
                    @csrf
                    @method('put')
                    <div class="row justify-content-between">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 mb-2" id="sale-client-field">
                                            <label class="mb-0" for="sale_customer_id">Sale
                                                Client/Customer</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="sale_customer_id" id="sale_customer_id"
                                                        class="clients-select form-control form-control-sm select2">
                                                        <option value="">Select Client</option>
                                                        @foreach ($customers as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-name="{{ $item->name }}"
                                                                data-phone="{{ $item->phone }}"
                                                                {{ $ticket->sales->sale_customer_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->name }} || {{ $item->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="ticket_type">Ticket Type<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select name="ticket_type" id="ticket_type"
                                                    class="form-control form-control-sm select2 ticket_type">
                                                    <option value="issue_ticket"
                                                        {{ $ticket->ticket_type == 'issue_ticket' ? 'selected' : '' }}>
                                                        Issue Ticket</option>
                                                    <option value="re_issue_ticket"
                                                        {{ $ticket->ticket_type == 're_issue_ticket' ? 'selected' : '' }}>
                                                        Re-Issue Ticket</option>
                                                    <option value="return_adjust"
                                                        {{ $ticket->ticket_type == 'return_adjust' ? 'selected' : '' }}>
                                                        Return Adjust Ticket</option>

                                                </select>
                                            </div>
                                        </div>
                                        @if ($ticket->ticket_pnr)
                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="ticket_pnr">Ticket PNR <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="ticket_pnr" id=""
                                                        value="{{ $ticket->ticket_pnr }}"
                                                        class="form-control form-control-sm" placeholder="Ticket PNR" />
                                                </div>
                                            </div>
                                        @endif


                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="purchase_vendor_id">Purchase Vendor</label>
                                            <div class="input-group">
                                                <select name="purchase_vendor_id" id="purchase_vendor_id"
                                                    class="single-select form-control form-control-sm select2 purchase_vendor_id">
                                                    <option value="0"
                                                        {{ $ticket->purchase->purchase_vendor_id == 0 ? 'selected' : '' }}>
                                                        My self</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $ticket->purchase->purchase_vendor_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                            || {{ $item->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="purchase_price">Purchase Price <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fa fa-pen"></i>
                                                </span>
                                                <input name="purchase_price" id="purchase_price" type="number"
                                                    class="form-control form-control-sm"
                                                    value="{{ $ticket->purchase->purchase_price }}"
                                                    placeholder="Enter Purchase Price" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="airline_id">Airline <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select name="airline_id" id="airline_id"
                                                    class="single-select form-control form-control-sm select2">
                                                    <option value="">Search Airline</option>
                                                    @foreach ($airlines as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $ticket->airline_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->IATA }}-{{ $item->Airline }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-2" id="reissue_date_field" style="display:none">
                                            <label class="mb-0" for="re_issue_date">ReIssue Date<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="re_issue_date" id="re_issue_date" type="date"
                                                    value="{{ $ticket->re_issue_date }}"
                                                    class="form-control-sm form-control " placeholder="00/00/0000" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_date">Sales Date</label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="sale_date" id="sale_date" type="date"
                                                    class="datepicker form-control form-control-sm"
                                                    value="{{ $ticket->sale_date }}" placeholder="dd/mm/yyyy" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="departer_time">Departer time</label>
                                            <div class="input-group">

                                                <input name="departer_time" id="departer_time" type="text"
                                                    class="form-control  form-control-sm timepicker"
                                                    value="{{ $ticket->departer_time }}" placeholder="HH:MM" />
                                            </div>

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="arrival_time">Arrival time</label>
                                            <div class="input-group">

                                                <input name="arrival_time" id="arrival_time" type="text"
                                                    class="form-control form-control-sm timepicker"
                                                    value="{{ $ticket->arrival_time }}" placeholder="HH:MM" />
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row justify-content-between">
                        <div class="col-md-6" id="single_ticket_column">
                            <div class="card" id="pax-details-container">
                                <div class="card-body pax-detail">
                                    <div class="card-title d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="text-center mb-2">Pax & Passport Details</h4>
                                    </div>
                                    <div class="single_ticket_column_field_wrapper">
                                        <div class="single_ticket_column_add_parent">
                                            <div class="row" id="">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-secondary  btn-sm"
                                                        id="add_pax">
                                                        <i class="fa fa-plus"></i> Add More
                                                    </button>

                                                    <div id="pax_fields" class="mb-3">
                                                        @foreach ($pax_data as $item)
                                                            <div class="row single_pax_row">
                                                                <div class="col-md-4 mb-2">
                                                                    <label class="mb-0">Pax Name<span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <span
                                                                            class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                                            <i class="fa fa-pen"></i>
                                                                        </span>
                                                                        <input name="g_pax_name[]" type="text"
                                                                            class="form-control form-control-sm"
                                                                            placeholder="Pax Name"
                                                                            value="{{ $item['name'] }}">
                                                                    </div>
                                                                    <span class="text-danger error-message"
                                                                        id="error-pax_name"></span>
                                                                </div>

                                                                <div class="col-md-2 mb-2">
                                                                    <label class="mb-0">Type <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <select name="g_pax_type[]"
                                                                            class="form-control form-control-sm select2">
                                                                            <option value="Adult"
                                                                                {{ $item['type'] == 'Adult' ? 'selected' : '' }}>
                                                                                Adult</option>
                                                                            <option value="Child"
                                                                                {{ $item['type'] == 'Child' ? 'selected' : '' }}>
                                                                                Child</option>
                                                                            <option value="Infant"
                                                                                {{ $item['type'] == 'Infant' ? 'selected' : '' }}>
                                                                                Infant</option>
                                                                        </select>
                                                                    </div>
                                                                    <span class="text-danger error-message"
                                                                        id="error-pax_type"></span>
                                                                </div>

                                                                <div class="col-md-3 mb-2">
                                                                    <label class="mb-0">Mobile No<span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <input type="number" name="g_pax_mobile_no[]"
                                                                            class="form-control form-control-sm"
                                                                            placeholder="Phone"
                                                                            value="{{ $item['mobile_no'] }}">
                                                                    </div>
                                                                    <span class="text-danger error-message"
                                                                        id="error-pax_mobile_no"></span>
                                                                </div>
                                                                <div class="col-md-2 mb-2">
                                                                    <label class="mb-0">Price<span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <input type="number" name="g_pax_price[]"
                                                                            class="form-control form-control-sm g_pax_price"
                                                                            placeholder="Price"
                                                                            value="{{ $item['price']??0 }}">
                                                                    </div>
                                                                    <span class="text-danger error-message"
                                                                        id="error-g_pax_price"></span>
                                                                </div>

                                                                <div class="col-md-1 mb-2 d-flex align-items-end">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove_pax">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-2 purchase-method-section"
                                                    id="purchase_payment_method" style="">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="mb-0" for="purchase_method_id">Purchase
                                                                Method</label>
                                                            <div class="input-group">
                                                                <select name="purchase_account_id" id="purchase_method_id"
                                                                    class="single-select form-control select2">
                                                                    <option value="">Select Method</option>
                                                                   @foreach ($account as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            {{ $ticket->purchase->purchase_account_id == $item->id ? 'selected' : '' }}>
                                                                            {{ $item->account_name }} ||
                                                                            {{ $item->current_balance }}/=
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="mb-0" for="purchase_method_id">Purchase
                                                                Transaction
                                                                No</label>
                                                            <input type="text" class="form-control ticket_refund" value="{{ $ticket->purchase->purchase_tnxid }}"
                                                                placeholder="Purchase tnxid" name="purchase_tnxid">
                                                            <span class="text-danger error-message"
                                                                id="error-purchase_tnxid"></span>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="single_ticket_column_pricing">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex justify-content-between">
                                        <h4 class="text-center mb-2">Ticket Sale Section</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 b-3">
                                            <label class="mb-0" for="sale_price">Sale Price<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                         
                                                <input name="sale_price" id="sale_price" type="number" readonly
                                                    class="form-control form-control-sm"
                                                    value="{{ $ticket->sales->sale_price }}" placeholder="0" />
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-2">
                                            <label class="mb-0" for="sale_profit">Profits<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">

                                                <input name="sale_profit" id="sale_profit" type="number" readonly
                                                    class="form-control form-control-sm"
                                                    value="{{ $ticket->sales->sale_profit }}" placeholder="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="mb-0" for="sale_loss">Loss<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">

                                                <input name="sale_loss" id="sale_loss" type="number" readonly
                                                    class="form-control form-control-sm"
                                                    value="{{ $ticket->sales->sale_loss }}" placeholder="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="mb-0" for="travel_status">Travel Status <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <!-- Radio buttons for single selection -->
                                                <span class="input-group-addon mr-1">
                                                    <input type="radio" name="travel_status" value="oneway"
                                                        style="cursor: pointer" id="oneway"
                                                        aria-label="One Way"{{ $ticket->travel_status == 'oneway' ? 'checked' : '' }}>
                                                    <label for="oneway">One Way</label>
                                                </span>
                                                <span class="input-group-addon mr-1">
                                                    <input type="radio" name="travel_status" value="roundtrip"
                                                        id="roundtrip"
                                                        aria-label="Round Trip"{{ $ticket->travel_status == 'roundtrip' ? 'checked' : '' }}
                                                        style="cursor: pointer">
                                                    <label for="roundtrip">Round Trip</label>
                                                </span>
                                                <span class="input-group-addon">
                                                    <input type="radio" name="travel_status" value="multicity"
                                                        id="multicity"
                                                        aria-label="Multi City"{{ $ticket->travel_status == 'multicity' ? 'checked' : '' }}
                                                        style="cursor: pointer">
                                                    <label for="multicity">Multi City</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="mb-0" for="depart_date">Departing<span
                                                    class="text-danger">*</span></label>
                                            <input name="depart_date" id="depart_date" type="date" required
                                                class="form-control  form-control-sm" value="{{ $ticket->depart_date }}"
                                                placeholder="dd/mm/YY" />
                                        </div>
                                        <div class="col-md-3 mb-2" id="return_date_container" style="display: none;">
                                            <label class="mb-0" for="return_date">Returning <span
                                                    class="text-danger">*</span></label>
                                            <input name="return_date" id="return_date"
                                                type="date"value="{{ $ticket->return_date }}"
                                                class="form-control  form-control-sm" placeholder="dd/mm/YY" />
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="mb-0" for="journey_from">Journey From</label>
                                            <div class="input-group">
                                                <select name="journey_from" id="journey_from"required
                                                    class="single-select form-control select2">
                                                    <option value="">Search Airport/City</option>
                                                    @foreach ($airports as $item)
                                                        <option value="{{ $item->code }}"
                                                            {{ $ticket->journey_from == $item->code ? 'selected' : '' }}>
                                                            {{ $item->code }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="mb-0" for="journey_to">Journey to</label>
                                            <div class="input-group">
                                                <select name="journey_to" id="journey_to"required
                                                    class="single-select form-control select2 form-control-sm">
                                                    <option value="">Search Airport/City</option>
                                                    @foreach ($airports as $item)
                                                        <option value="{{ $item->code }}"
                                                            {{ $ticket->journey_to == $item->code ? 'selected' : '' }}>
                                                            {{ $item->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2" id="multicity_airport_container"
                                            style="display: {{ $ticket->travel_status === 'multicity' ? 'block' : 'none' }};">

                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="mb-0" for="multicity_from">Multicity
                                                        From</label>
                                                    <div class="input-group">
                                                        <select name="multicity_from" id="multicity_from"
                                                            class="form-control select2" style="width: 100%">
                                                            <option value="">Search Airport/City
                                                            </option>
                                                            @foreach ($airports as $item)
                                                                <option value="{{ $item->code }}"
                                                                    {{ $ticket->multicity_from == $item->code ? 'selected' : '' }}>
                                                                    {{ $item->code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="mb-0" for="multicity_to">Multicity To</label>
                                                    <div class="input-group">
                                                        <select name="multicity_to" id="multicity_to"style="width: 100%"
                                                            class="form-control select2 form-control-sm">
                                                            <option value="">Search Airport/City
                                                            </option>
                                                            @foreach ($airports as $item)
                                                                <option value="{{ $item->code }}"
                                                                    {{ $ticket->multicity_to == $item->code ? 'selected' : '' }}>
                                                                    {{ $item->code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="visa_exp_date">Sale note</label>
                                                        <textarea name="sale_note" id="" class="form-control ticket_refund" cols="2" rows="2"
                                                            placeholder="Sale note">{{$ticket->sales->sale_note}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="visa_exp_date">Purchase note</label>
                                                        <textarea name="purchase_note" id="" class="form-control ticket_refund" cols="2" rows="2"
                                                            placeholder="Purchase note">{{$ticket->purchase->purchase_note}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                  

                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-sm  btn-secondary">
                            <i class="fas fa-sync"></i> Edit & Publish</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection



@push('scripts')
<script>
$(document).ready(function() {
    // Initialize select2
    $('.select2').select2();

    // Travel Status Toggle
    const toggleTravelFields = () => {
        const selectedValue = $('input[name="travel_status"]:checked').val();
        const returnDateContainer = $('#return_date_container');
        const multicityAirportContainer = $('#multicity_airport_container');

        if (selectedValue === 'roundtrip') {
            returnDateContainer.show();
            multicityAirportContainer.hide();
        } else if (selectedValue === 'multicity') {
            returnDateContainer.show();
            multicityAirportContainer.show();
        } else {
            returnDateContainer.hide();
            multicityAirportContainer.hide();
        }
    };
    $('input[name="travel_status"]').on('change', toggleTravelFields);
    toggleTravelFields();

    // Profit / Loss Calculation
    const purchasePriceInput = $('#purchase_price');
    const salePriceInput = $('#sale_price');
    const profitInput = $('#sale_profit');
    const lossInput = $('#sale_loss');

    const updateSalePriceAndProfitLoss = () => {
        let totalSalePrice = 0;
        $('.g_pax_price').each(function() {
            totalSalePrice += parseFloat($(this).val()) || 0;
        });
        salePriceInput.val(totalSalePrice.toFixed(2));

        const purchasePrice = parseFloat(purchasePriceInput.val()) || 0;
        const profitOrLoss = totalSalePrice - purchasePrice;

        if (profitOrLoss >= 0) {
            profitInput.val(profitOrLoss.toFixed(2));
            lossInput.val("0.00");
        } else {
            lossInput.val(Math.abs(profitOrLoss).toFixed(2));
            profitInput.val("0.00");
        }
    };

    // Trigger calculation when purchase price or any g_pax_price changes
    purchasePriceInput.on('input', updateSalePriceAndProfitLoss);
    $(document).on('input', '.g_pax_price', updateSalePriceAndProfitLoss);

    // Ticket Type Handling
    const ticketTypeSelect = $('.ticket_type');
    const reissueDateField = $('#reissue_date_field');
    const refundDateField = $('#refund_date_field');
    const ticketPNRInput = $('#ticket_pnr');

    ticketTypeSelect.on('change', function() {
        const value = $(this).val();
        if (value === 're_issue_ticket') {
            reissueDateField.show();
            refundDateField.hide();
            ticketPNRInput.val('');
        } else if (value === 'refund_ticket') {
            refundDateField.show();
            reissueDateField.hide();
            ticketPNRInput.val('R-');
        } else {
            reissueDateField.hide();
            refundDateField.hide();
            ticketPNRInput.val('');
        }
    }).trigger('change');

    // Vendor Payment Toggle
    const vendorSelect = $('#purchase_vendor_id');
    const paymentMethodSection = $('#purchase_payment_method');
    const togglePaymentMethod = () => {
        if (vendorSelect.val() == '0') paymentMethodSection.show();
        else paymentMethodSection.hide();
    };
    vendorSelect.on('change', togglePaymentMethod);
    togglePaymentMethod();

    // Add / Remove PAX Rows
    $('#add_pax').on('click', function() {
        let paxFields = $('#pax_fields');
        let newRow = $(`
        <div class="row single_pax_row">
            <div class="col-md-4 mb-2">
                <div class="input-group">
                    <span class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                        <i class="fa fa-pen"></i>
                    </span>
                    <input name="g_pax_name[]" type="text" class="form-control form-control-sm" placeholder="Pax Name">
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="input-group">
                    <select name="g_pax_type[]" class="form-control form-control-sm select2">
                        <option value="Adult">Adult</option>
                        <option value="Child">Child</option>
                        <option value="Infant">Infant</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="input-group">
                    <input type="number" name="g_pax_mobile_no[]" class="form-control form-control-sm" placeholder="Phone">
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="input-group">
                    <input type="number" name="g_pax_price[]" class="form-control form-control-sm g_pax_price" placeholder="Price">
                </div>
            </div>
            <div class="col-md-1 mb-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove_pax">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        `);
        paxFields.append(newRow);
        newRow.find(".select2").select2();
    });

    $(document).on('click', '.remove_pax', function() {
        if ($('.single_pax_row').length > 1) $(this).closest('.single_pax_row').remove();
        else alert("At least one passenger is required.");
        updateSalePriceAndProfitLoss();
    });

    // Auto-fill first PAX row with selected customer
    $('#sale_customer_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const customerName = selectedOption.data('name') || '';
        const customerPhone = selectedOption.data('phone') || '';

        $('.single_pax_row').first().find('input[name="g_pax_name[]"]').val(customerName);
        $('.single_pax_row').first().find('input[name="g_pax_mobile_no[]"]').val(customerPhone);

        // Disable vendor option if same as customer
        const selectedClient = $(this).val();
        $('#purchase_vendor_id option').each(function() {
            $(this).prop('disabled', $(this).val() == selectedClient);
        });
        $('#purchase_vendor_id').val('0').trigger('change');

        updateSalePriceAndProfitLoss();
    });

    // Trigger calculation on page load
    updateSalePriceAndProfitLoss();
});
</script>

@endpush
