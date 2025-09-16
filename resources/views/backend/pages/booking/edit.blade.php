@extends('backend.layout.app')
@push('css')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <div class="card card-body">
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="text-center mb-4">Add New Hotel Booking
                            <a data-url="{{ route('admin.customer.create') }}" class="btn btn-sm btn-secondary show-modal"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Add Client</a>
                        </h4>
                        <div>
                            <a href="{{ route('admin.inventory.hotel.index') }}" class="btn btn-sm btn-secondary"> <i
                                    class="fa fa-minus" aria-hidden="true"></i> Go Back</a>
                        </div>
                    </div>

                    <form action="{{ route('admin.inventory.hotel.update', $hotel->id) }}" method="POST" class="formSubmit">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="mb-0" for="sale_customer_id">Select Client</label>
                                        <div class="d-flex">
                                            <div class="input-group w-100">
                                                <select name="sale_customer_id" id="sale_customer_id"
                                                    class="clients-select form-control form-control-md select2"
                                                    style="width: 100%">
                                                    <option value="">Select Client</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-phone="{{ $item->phone }}"
                                                            data-due="{{ $item->total_due }}"
                                                            data-balance="{{ $item->balance }}"
                                                            {{ $hotel->sales->sale_customer_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }} || {{ $item->phone }} ||
                                                            {{ $item->passport_no }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>
                                        <span class="text-danger error-message" id="error-sale_customer_id"></span>

                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label class="mb-0" for="visit_country">Select Country</label>
                                        <div class="d-flex">
                                            <div class="input-group w-100">
                                                <select name="visit_country" id="visit_country"
                                                    class="clients-select form-control form-control-md select2"
                                                    style="width: 100%">
                                                    <option value="">Select Country</option>
                                                    @foreach ($country as $con)
                                                        <option value="{{ $con->name }}"
                                                            {{ $con->name == $hotel->visit_country ? 'selected' : '' }}>
                                                            {{ $con->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <span class="text-danger error-message" id="error-visit_country"></span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="mb-0" for="hotel_name">Hotel name<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input name="hotel_name" id="hotel_name" type="text"
                                                class="form-control form-control-sm" value="{{ $hotel->hotel_name }}"
                                                placeholder="Enter Hotel name" />
                                        </div>
                                        <span class="text-danger error-message" id="error-hotel_name"></span>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="mb-0" for="hotel_number_of_day">Hotel stay period<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input name="hotel_number_of_day" id="hotel_number_of_day"
                                                type="text"value="{{ $hotel->hotel_number_of_day }}"
                                                class="form-control form-control-sm" value=""
                                                placeholder="Enter Hotel stay period" />
                                        </div>
                                        <span class="text-danger error-message" id="error-hotel_number_of_day"></span>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="mb-0" for="hotel_location">Hotel Location<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input name="hotel_location" id="hotel_location" type="text"
                                                class="form-control form-control-sm" value="{{ $hotel->hotel_location }}"
                                                placeholder="Enter Hotel Location" />
                                        </div>
                                        <span class="text-danger error-message" id="error-hotel_location"></span>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="mb-0" for="hotel_purchase_email">Hotel Booking Email<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select name="hotel_purchase_email" id="hotel_purchase_email"
                                                class="clients-select form-control form-control-md select2"
                                                style="width: 100%">
                                                <option value="">Select Email</option>
                                                <option
                                                    value="{{ setting('booking_email') }}" 
                                                    {{ $hotel->hotel_purchase_email == setting('booking_email') ? 'selected' : '' }}>
                                                    {{ setting('booking_email') }}
                                                </option>
                                                <option
                                                    value="{{ setting('booking_email2') }}" 
                                                    {{ $hotel->hotel_purchase_email == setting('booking_email2') ? 'selected' : '' }}>
                                                    {{ setting('booking_email2') }}
                                                </option>

                                            </select>
                                        </div>
                                        <span class="text-danger error-message" id="error-hotel_purchase_email"></span>

                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="mb-0" for="hotel_refer">Hotel Referal Link Name/Broker<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input name="hotel_refer" id="hotel_refer" type="text"
                                                class="form-control form-control-sm" value="{{ $hotel->hotel_refer }}"
                                                placeholder="Enter Referal Link Name/Broker" />
                                        </div>
                                        <span class="text-danger error-message" id="error-hotel_refer"></span>

                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="mb-0" for="sale_date">Sales Date</label>
                                        <div class="input-group">
                                            <span
                                                class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input name="sale_date" id="sale_date" type="text"
                                                class="datepicker form-control form-control-sm"
                                                value="{{ $hotel->sale_date }}" placeholder="dd/mm/yyyy" />
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex justify-content-between">
                                            <h4 class="text-center mb-2">Visa Pricing Section</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="mb-0" for="purchase_price">Purchase Price<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="purchase_price" id="purchase_price" type="number"
                                                        class="form-control form-control-sm"
                                                        value="{{ $hotel->purchase->purchase_price }}"
                                                        placeholder="Purchase Price" />
                                                </div>
                                                <span class="text-danger error-message" id="error-purchase_price"></span>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="mb-0" for="sale_price">Sale Price<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_price" id="sale_price" type="number"
                                                        class="form-control form-control-sm"
                                                        value="{{ $hotel->sales->sale_price }}"
                                                        placeholder="Sale price" />
                                                </div>
                                                <span class="text-danger error-message" id="error-sale_price"></span>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="sale_profit">Profits<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_profit" id="sale_profit" type="number" readonly
                                                        class="form-control form-control-sm"
                                                        value="{{ $hotel->sales->sale_profit }}" placeholder="0" />
                                                </div>

                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="sale_loss">Loss<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_loss" id="sale_loss" type="number" readonly
                                                        class="form-control form-control-sm" placeholder="0"
                                                        value="{{ $hotel->sales->sale_loss }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3" id="purchase_payment_method" style="">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="mb-0" for="purchase_account_id">Purchase
                                                            Method</label>
                                                        <div class="input-group">
                                                            <select name="purchase_account_id" id="purchase_account_id"
                                                                class="single-select form-control select2">
                                                                <option value="">Select Method</option>
                                                                @foreach ($account as $item)
                                                                    <option
                                                                        value="{{ $item->id }}"{{ $hotel->purchase->purchase_account_id == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->account_name }}-{{ $item->account_number }}-{{ $item->balance }}/=
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="text-danger error-message"
                                                            id="error-purchase_account_id"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="mb-0" for="purchase_tnxid">Purchase
                                                            Transaction
                                                            No</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm"id="purchase_tnxid"
                                                            placeholder="Purchase tnxid" name="purchase_tnxid"
                                                            value="{{ $hotel->purchase->purchase_tnxid }}">
                                                        <span class="text-danger error-message"
                                                            id="error-purchase_tnxid"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
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
                                                                    placeholder="Pax Name" value="{{ $item['name'] }}">
                                                            </div>
                                                            <span class="text-danger error-message"
                                                                id="error-pax_name"></span>
                                                        </div>

                                                        <div class="col-md-4 mb-2">
                                                            <label class="mb-0">Pax Type <span
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

                                                        <div class="col-md-4 mb-2">
                                                            <label class="mb-0">Mobile No<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input type="number" name="g_pax_mobile_no[]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Phone" value="{{ $item['mobile_no'] }}">
                                                            </div>
                                                            <span class="text-danger error-message"
                                                                id="error-pax_mobile_no"></span>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Sale Note</label>
                                                                <textarea name="sale_note" class="form-control" rows="2" cols="2" id="sale_note">{{ $hotel->sales->sale_note }}</textarea>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm b-0   btn-secondary">
                                <i class="fas fa-sync"></i> Edit & Publish</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  <script>
    // ===== Profit / Loss Auto Calculation =====
    const purchasePriceInput = document.getElementById('purchase_price');
    const salePriceInput = document.getElementById('sale_price');
    const profitInput = document.getElementById('sale_profit');
    const lossInput = document.getElementById('sale_loss');

    purchasePriceInput.addEventListener('input', calculate);
    salePriceInput.addEventListener('input', calculate);

    function calculate() {
        const purchasePrice = parseFloat(purchasePriceInput.value) || 0;
        const salePrice = parseFloat(salePriceInput.value) || 0;
        let profit = 0;
        let loss = 0;

        if (salePrice > purchasePrice) {
            profit = salePrice - purchasePrice;
        } else if (purchasePrice > salePrice) {
            loss = purchasePrice - salePrice;
        }

        profitInput.value = profit.toFixed(2);
        lossInput.value = loss.toFixed(2);
    }

    // ===== Auto-fill Pax Name & Mobile from Client =====
    $(document).ready(function() {
        $('#sale_customer_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var customerName = selectedOption.data('name') || '';
            var customerPhone = selectedOption.data('phone') || '';

            // প্রথম Pax Row তে auto-fill হবে
            $('.single_pax_row').first().find('input[name="g_pax_name[]"]').val(customerName);
            $('.single_pax_row').first().find('input[name="g_pax_mobile_no[]"]').val(customerPhone);
        });
    });

    // ===== Prevent Same Customer as Vendor =====
    $(document).ready(function() {
        $('#sale_customer_id').change(function() {
            var selectedClient = $(this).val();
            $('#purchase_vendor_id option').each(function() {
                if ($(this).val() == selectedClient) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
            $('#purchase_vendor_id').val(0).trigger('change');
        });
    });
</script>

@endpush
