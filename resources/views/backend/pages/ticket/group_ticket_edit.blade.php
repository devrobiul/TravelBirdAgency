@extends('backend.layout.app') @push('css')
    <style>
        .select2-container .select2-selection--single {
            height: 33px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
        }

        .select2 {
            width: 100%;
        }
    </style>
    @endpush @section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="text-center mb-4">Group Ticket Edit</h4>
                        <div>
                            <a class="btn btn-sm btn-secondary" href="{{ route('admin.inventory.groupticket.index') }}"><i
                                    class="fa fa-minus" aria-hidden="true"></i> Go Back</a>
                        </div>
                    </div>

                    <form action="{{ route('admin.inventory.groupticket.update', $g_ticket->id) }}" method="POST"
                        class="formSubmit">
                        @csrf
                        @method('put')

                        <div class="row justify-content-between">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="ticket_pnr">Ticket Pnr <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="ticket_pnr" id="ticket_pnr"
                                                        class="form-control form-control-sm" placeholder="Ticket PNR"
                                                        value="{{ $g_ticket->ticket_pnr }}" />
                                                </div>
                                                <span class="text-danger error-message" id="error-ticket_pnr"></span>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="group_qty">Qty<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="group_qty" id="group_qty"
                                                        value="{{ $g_ticket->group_qty }}"
                                                        class="form-control form-control-sm" placeholder="Qty" />
                                                </div>
                                                <span class="text-danger error-message" id="error-group_qty"></span>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="group_ticket_qty">Insert Now <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary minus-btn"><i
                                                            class="fa fa-minus"></i></button>
                                                    <input type="number" name="group_ticket_qty" id="group_ticket_qty"
                                                        class="form-control form-control-sm text-center"
                                                        value="{{ $g_ticket->group_ticket_qty }}" />
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary plus-btn"><i
                                                            class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="purchase_vendor_id">Purchase Vendor</label>
                                                <div class="input-group">
                                                    <select name="purchase_vendor_id" id="purchase_vendor_id"
                                                        class="single-select form-control form-control-sm select2 purchase_vendor_id">
                                                        <option value="0">My self</option>
                                                        @foreach ($customers as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $g_ticket->purchase->purchase_vendor_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                                || {{ $item->phone }}
                                                            </option>
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="purchase_price">Purchase<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">

                                                    <input name="purchase_price" id="purchase_price" type="number"
                                                        class="form-control form-control-sm"
                                                        value="{{ $g_ticket->purchase->purchase_price }}"
                                                        placeholder="Price" />
                                                </div>
                                                <span class="text-danger error-message" id="error-purchase_price"></span>
                                            </div>


                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="airline_id_ticket">Airline <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select name="airline_id" id="airline_id_ticket"
                                                        class="single-select form-control select2">
                                                        <option value="">Search Airline</option>
                                                        @foreach ($airlines as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $g_ticket->airline_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->IATA }}-{{ $item->Airline }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="text-danger error-message" id="error-airline_id"></span>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="issue_date">Issue Date<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="issue_date" id="issue_date" type="date"
                                                        class="datepicker form-control form-control-sm"
                                                        value="{{ $g_ticket->issue_date }}" placeholder="" />
                                                </div>
                                            </div>

                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="sale_date">Sales Date</label>
                                                <div class="input-group">

                                                    <input name="sale_date" id="sale_date" type="date"
                                                        class="datepicker form-control form-control-sm"
                                                        value="{{ $g_ticket->sale_date }}" placeholder="dd/mm/yyyy" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row justify-content-between">
                            <div class="col-md-12" id="group_ticket_column">
                                <div class="card" id="pax-details-container">
                                    <div class="card-body pax-detail">
                                        <div class="group_ticket_column_field_wrapper">



                                        </div>
                                    </div>
                                </div>
                            </div>







                            <div class="col-md-6" id="group_ticket_column_pricing">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex justify-content-between">
                                            <h4 class="text-center mb-2">Ticket Sale Section</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="mb-0" for="group_travel_status">Travel Status <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <!-- Radio buttons for single selection -->
                                                    <span class="input-group-addon mr-1">
                                                        <input type="radio" name="travel_status" value="oneway"
                                                            style="cursor: pointer" id="oneway"
                                                            aria-label="One Way"{{ $g_ticket->travel_status == 'oneway' ? 'checked' : '' }}>
                                                        <label for="oneway">One Way</label>
                                                    </span>
                                                    <span class="input-group-addon mr-1">
                                                        <input type="radio" name="travel_status" value="roundtrip"
                                                            id="roundtrip"
                                                            aria-label="Round Trip"{{ $g_ticket->travel_status == 'roundtrip' ? 'checked' : '' }}
                                                            style="cursor: pointer">
                                                        <label for="roundtrip">Round Trip</label>
                                                    </span>
                                                    <span class="input-group-addon">
                                                        <input type="radio" name="travel_status" value="multicity"
                                                            id="multicity"
                                                            aria-label="Multi City"{{ $g_ticket->travel_status == 'multicity' ? 'checked' : '' }}
                                                            style="cursor: pointer">
                                                        <label for="multicity">Multi City</label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="mb-0" for="depart_date">Departing<span
                                                        class="text-danger">*</span></label>
                                                <input name="depart_date" id="depart_date" type="date"
                                                    class="form-control form-control-sm"
                                                    value="{{ $g_ticket->depart_date }}" placeholder="" />
                                            </div>
                                            <div class="col-md-3 mb-3" id="group_return_date_container"
                                                style="display: none;">
                                                <label class="mb-0" for="return_date">Returning <span
                                                        class="text-danger">*</span></label>
                                                <input name="return_date" id="return_date" type="date"
                                                    class="form-control form-control-sm"
                                                    value="{{ $g_ticket->return_date }}" placeholder="Select a date" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="mb-0" for="group_journey_from">Journey From</label>
                                                <div class="input-group">
                                                    <select name="journey_from" id="group_journey_from"
                                                        class="single-select form-control select2">
                                                        <option value="" selected>Search Airport/City</option>
                                                        @foreach ($airports as $item)
                                                            <option value="{{ $item->code }}"
                                                                {{ $g_ticket->journey_from == $item->code ? 'selected' : '' }}>
                                                                {{ $item->code }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="mb-0" for="group_journey_to">Journey to</label>
                                                <div class="input-group">
                                                    <select name="journey_to" id="group_journey_to"
                                                        class="single-select form-control select2">
                                                        <option value="" selected>Search Airport/City</option>
                                                        @foreach ($airports as $item)
                                                            <option value="{{ $item->code }}"
                                                                {{ $g_ticket->journey_to == $item->code ? 'selected' : '' }}>
                                                                {{ $item->code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3" id="group_multicity_airport_container"
                                                style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="mb-0" for="group_multicity_from">Multicity
                                                            From</label>
                                                        <div class="input-group">
                                                            <select name="multicity_from" id="group_multicity_from"
                                                                class="form-control select2" style="width: 100%">
                                                                <option value="" selected>Search Airport/City
                                                                </option>
                                                                @foreach ($airports as $item)
                                                                    <option value="{{ $item->code }}"
                                                                        {{ $g_ticket->multicity_from == $item->code ? 'selected' : '' }}>
                                                                        {{ $item->code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="mb-0" for="group_multicity_to">Multicity
                                                            To</label>
                                                        <div class="input-group">
                                                            <select name="multicity_to"
                                                                id="group_multicity_to"style="width: 100%"
                                                                class="form-control select2">
                                                                <option value="" selected>Search Airport/City
                                                                </option>
                                                                @foreach ($airports as $item)
                                                                    <option value="{{ $item->code }}"
                                                                        {{ $g_ticket->multicity_to == $item->code ? 'selected' : '' }}>
                                                                        {{ $item->code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="group_ticket_column_pricing">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex justify-content-between">
                                            <h4 class="text-center mb-2">Ticket Sale Section </h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 purchase_payment_method">
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0" for="purchase_account_id">Purchase
                                                            Mehtod</label>
                                                        <div class="d-flex">
                                                            <div class="input-group w-100">
                                                                <select name="purchase_account_id"
                                                                    id="purchase_account_id"
                                                                    class="clients-select form-control select2">
                                                                    <option value="">Select Method</option>
                                                                    @foreach ($account as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            {{ $g_ticket->purchase->purchase_account_id == $item->id ? 'selected' : '' }}>
                                                                            {{ $item->account_name }} ||
                                                                            {{ currencyBD($item->current_balance) }}/=
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger error-message"
                                                                    id="error-purchase_account_id"></span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0" for="purchase_tnxid">Transaction No<span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input name="purchase_tnxid" id="purchase_tnxid"
                                                                type="text" class="form-control form-control-sm"
                                                                value="{{ $g_ticket->purchase->purchase_tnxid }}"
                                                                placeholder="Transaction No" />
                                                        </div>
                                                        <span class="text-danger error-message"
                                                            id="error-purchase_tnxid"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="mb-0" for="sale_profit">Profits<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">

                                                    <input name="product_sale_profit" id="sale_profit" type="number"
                                                        readonly class="form-control form-control-sm"
                                                        value="{{ $g_ticket->product_sale_profit }}" placeholder="0" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="mb-0" for="sale_loss">Loss<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">

                                                    <input name="product_sale_loss" id="sale_loss" type="number"
                                                        readonly class="form-control form-control-sm"
                                                        value="{{ $g_ticket->product_sale_loss }}" placeholder="0" />
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="visa_exp_date">Purchase note</label>
                                                    <textarea name="purchase_note" id="" class="form-control ticket_refund" cols="2" rows="2"
                                                        placeholder="Purchase note">{{ $g_ticket->purchase->purchase_note }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-secondary">
                                <i class="fas fa-sync    "></i> Edit & Update data</button>
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
            var wrapper = $('.group_ticket_column_field_wrapper');

            function getMaxField() {
                return parseInt($('#group_qty').val()) || 1;
            }

            // Template for one pax row
            function getFieldHTML() {
                return `
            <div class="group_ticket_column_add_parent">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="mb-0">Pax Name</label>
                        <input name="group_pax_name[]" class="form-control form-control-sm pax_name" placeholder="Pax Name">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-0">Mobile No</label>
                        <input type="number" name="group_pax_mobile_no[]" class="form-control form-control-sm pax_mobile" placeholder="Phone">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-0">Pax Type</label>
                        <select name="group_pax_type[]" class="form-control form-control-sm select2 pax_type" style="width:100%">
                            <option value="Adult">Adult</option>
                            <option value="Child">Child</option>
                            <option value="Infant">Infant</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-0">Price</label>
                        <input type="number" name="sale_price[]" class="form-control form-control-sm sale_price" placeholder="Price">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="mb-0">Select Client</label>
                        <select name="sale_customer_id[]" class="clients-select form-control select2 sale_client" style="width:100%">
                            <option value="">Select Client</option>
                            @foreach ($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} || {{ $item->phone }} || {{ $item->passport_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 mb-3">
                        <label class="mb-0">Action</label><br>
                        <button type="button" class="remove_button btn btn-sm btn-danger">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>`;
            }

            // Update wrapper with required qty
            function updateTicketFields(ticketQty) {
                var maxField = getMaxField();
                if (ticketQty > maxField) ticketQty = maxField;

                while (wrapper.find('.group_ticket_column_add_parent').length < ticketQty) {
                    wrapper.append(getFieldHTML());
                    wrapper.find('.select2').last().select2();
                }

                $('#group_ticket_qty').val(ticketQty);
            }

            // Load existing rows from DB (edit mode)
            @foreach ($g_ticket->group_ticket_sales as $index => $item)
                updateTicketFields({{ $loop->index + 1 }});
                $('input[name="group_pax_name[]"]').eq({{ $index }}).val("{{ $item->pax_name }}");
                $('input[name="group_pax_mobile_no[]"]').eq({{ $index }}).val(
                "{{ $item->pax_mobile_no }}");
                $('select[name="group_pax_type[]"]').eq({{ $index }}).val("{{ $item->pax_type }}").trigger(
                    'change');
                $('input[name="sale_price[]"]').eq({{ $index }}).val("{{ $item->sale_price }}");
                $('select[name="sale_customer_id[]"]').eq({{ $index }}).val(
                    "{{ $item->sale_customer_id }}").trigger('change');
            @endforeach

            // Plus button
            $('.plus-btn').click(function() {
                var currentQty = parseInt($('#group_ticket_qty').val()) || 0;
                var maxField = getMaxField();
                if (currentQty < maxField) {
                    updateTicketFields(currentQty + 1);
                } else {
                    alert('Quantity cannot exceed total group quantity!');
                }
            });

            // Minus button
            $('.minus-btn').click(function() {
                var currentQty = parseInt($('#group_ticket_qty').val()) || 0;
                if (currentQty > 0) {
                    wrapper.find('.group_ticket_column_add_parent').last().remove();
                    $('#group_ticket_qty').val(currentQty - 1);
                }
            });

            // Remove button (row delete)
            $(wrapper).on('click', '.remove_button', function(e) {
                e.preventDefault();
                $(this).closest('.group_ticket_column_add_parent').remove();
                var totalFields = wrapper.find('.group_ticket_column_add_parent').length;
                $('#group_ticket_qty').val(totalFields);
            });

            // When group_qty changes manually
            $('#group_qty').on('input', function() {
                var maxField = getMaxField();
                var currentQty = parseInt($('#group_ticket_qty').val()) || 0;
                if (currentQty > maxField) {
                    while (wrapper.find('.group_ticket_column_add_parent').length > maxField) {
                        wrapper.find('.group_ticket_column_add_parent').last().remove();
                    }
                    $('#group_ticket_qty').val(maxField);
                }
            });
        });
    </script>







    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const travelStatusRadios = document.querySelectorAll(
                'input[name="travel_status"]'); // Corrected name attribute
            const returnDateContainer = document.getElementById('group_return_date_container');
            const multicityAirportContainer = document.getElementById('group_multicity_airport_container');

            // Add event listeners to each radio button
            travelStatusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'roundtrip') {
                        returnDateContainer.style.display = 'block'; // Show return date field
                        multicityAirportContainer.style.display = 'none'; // Hide multicity fields
                    } else if (this.value === 'multicity') {
                        multicityAirportContainer.style.display = 'block'; // Show multicity fields
                        returnDateContainer.style.display = 'block'; // Show return date field
                    } else { // Oneway
                        returnDateContainer.style.display = 'none'; // Hide return date field
                        multicityAirportContainer.style.display = 'none'; // Hide multicity fields
                    }
                });
            });

            // Trigger the initial state based on selected radio
            const selectedRadio = document.querySelector(
                'input[name="travel_status"]:checked'); // Corrected name attribute
            if (selectedRadio) {
                selectedRadio.dispatchEvent(new Event('change'));
            }
        });
    </script>
    <script>
        document.addEventListener('input', function() {
            const purchasePrice = parseFloat(document.getElementById('purchase_price').value) ||
                0; // Purchase Price
            let totalSales = 0;
            let totalDue = 0;

            // Calculate Total Sales: Sum of all Prices for all rows
            document.querySelectorAll('[name="sale_price[]"]').forEach((priceInput, index) => {
                const price = parseFloat(priceInput.value) || 0;

                // Add price to Total Sales
                totalSales += price;

                // Calculate Due for this row (price - pay)
                const due = price; // Assuming the due is just the sale price without payment
                totalDue += due; // Sum up the dues
            });

            // Profit or Loss Calculation
            const profitField = document.getElementById('sale_profit');
            const lossField = document.getElementById('sale_loss');

            if (totalSales > purchasePrice) {
                profitField.value = (totalSales - purchasePrice).toFixed(2); // Show Profit
                lossField.value = 0; // No Loss
            } else {
                lossField.value = (purchasePrice - totalSales).toFixed(2); // Show Loss
                profitField.value = 0;
            }


            document.getElementById('total_payment').value = totalSales.toFixed(
                2);
            document.getElementById('total_due').value = totalDue.toFixed(2);
        });
    </script>
    <script>
        $(document).ready(function() {
            const vendorSelect = $('#purchase_vendor_id');
            const paymentMethodSection = $('.purchase_payment_method'); // Payment Method + Transaction No

            function togglePaymentMethod() {
                if (vendorSelect.val() === '0') { // My self selected
                    paymentMethodSection.show();
                } else {
                    paymentMethodSection.hide();
                }
            }

            // On page load
            togglePaymentMethod();

            // On change
            vendorSelect.on('change', togglePaymentMethod);
        });
    </script>
@endpush
