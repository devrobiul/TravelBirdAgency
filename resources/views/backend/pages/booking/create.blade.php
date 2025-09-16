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
                            <a data-url="{{ route('admin.customer.create') }}" class="btn btn-sm btn-secondary show-modal"><i class="fa fa-plus-circle"
                                    aria-hidden="true"></i> Add Client</a>                            
                        </h4>
                        <div>
                            <a href="{{ route('admin.inventory.hotel.index') }}" class="btn btn-sm btn-secondary"> <i
                                    class="fa fa-minus" aria-hidden="true"></i> Go Back</a>
                        </div>
                    </div>

                    <form action="{{ route('admin.product.store') }}" method="POST" class="formSubmit">
                        @csrf
                        <input type="hidden" value="hotel_booking" name="product_type">
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
                                                            data-balance="{{ $item->balance }}">
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
                                                        <option value="{{ $con->name }}">{{ $con->name }}
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
                                                class="form-control form-control-sm" value=""
                                                placeholder="Enter Hotel name" />
                                        </div>
                                        <span class="text-danger error-message" id="error-hotel_name"></span>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="mb-0" for="hotel_number_of_day">Hotel stay period/day count<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input name="hotel_number_of_day" id="hotel_number_of_day" type="text"
                                                class="form-control form-control-sm" value=""
                                                placeholder="Enter Hotel stay period count" />
                                        </div>
                                        <span class="text-danger error-message" id="error-hotel_number_of_day"></span>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="mb-0" for="hotel_location">Hotel Location<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input name="hotel_location" id="hotel_location" type="text"
                                                class="form-control form-control-sm" value=""
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

                                                <option value="{{ setting('booking_email') }}">
                                                    {{ setting('booking_email') }}
                                                </option>
                                                <option value="{{ setting('booking_email2') }}">
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
                                                class="form-control form-control-sm" value=""
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
                                            <input name="sale_date" id="sale_date" type="date"
                                                class="form-control form-control-sm"
                                                value="{{ old('sale_date', date('Y-m-d')) }}" placeholder="dd/mm/yyyy" />
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
                                                        placeholder="Purchase Price" />
                                                </div>
                                                <span class="text-danger error-message" id="error-purchase_price"></span>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="mb-0" for="sale_price">Sale Price<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_price" id="sale_price" type="number"
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="Sale price" />
                                                </div>
                                                <span class="text-danger error-message" id="error-sale_price"></span>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="sale_profit">Profits<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_profit" id="sale_profit" type="number" readonly
                                                        class="form-control form-control-sm" placeholder="0" />
                                                </div>

                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="mb-0" for="sale_loss">Loss<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_loss" id="sale_loss" type="number" readonly
                                                        class="form-control form-control-sm" placeholder="0" />
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3" id="purchase_payment_method" style="">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="mb-0" for="purchase_method_id">Purchase
                                                            Method</label>
                                                        <div class="input-group">
                                                            <select name="purchase_account_id" id="purchase_account_id"
                                                                class="single-select form-control select2">
                                                                <option value="">Select Method</option>
                                                                @foreach ($account as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->account_name }} ||
                                                                        {{ currencyBD($item->current_balance) }}/=
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
                                                        <input type="text" class="form-control form-control-sm"
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
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row" id="field_wrapper">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="g_pax_name">Pax Name</label>
                                                    <input type="text" name="g_pax_name[]" id="pax_name"
                                                        value=""
                                                        class="form-control form-control-sm @error('g_pax_name')is-invalid @enderror"
                                                        placeholder="Pax name" aria-describedby="helpId">
                                                    <span class="text-danger error-message" id="error-g_pax_name"></span>
                                                </div>

                                            </div>

                                                      <div class="col-md-4 mb-2">
                                                            <label class="mb-0">Pax Type <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <select name="g_pax_type[]"
                                                                    class="form-control form-control-sm select2">
                                                                    <option value="Adult">
                                                                        Adult</option>
                                                                    <option value="Child">
                                                                        Child</option>
                                                                    <option value="Infant">
                                                                        Infant</option>
                                                                </select>
                                                            </div>
                                                            <span class="text-danger error-message"
                                                                id="error-pax_type"></span>
                                                        </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="pax_mobile_no">Pax Mobile</label>
                                                    <input type="number" name="g_pax_mobile_no[]" id="pax_mobile_no"
                                                        value=""
                                                        class="form-control form-control-sm @error('pax_mobile_no')is-invalid @enderror"
                                                        placeholder="Pax mobile" aria-describedby="helpId">
                                                    <span class="text-danger error-message"
                                                        id="error-g_pax_mobile_no"></span>
                                                </div>

                                            </div>
                                   

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="visa_exp_date">Sale note</label>
                                                    <textarea name="sale_note" id="" class="form-control form-control-sm" cols="3" rows="3"
                                                        placeholder="Sale note"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="fas fa-sync"></i> Send data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        /** ---------------------------
         *  Profit / Loss Calculation
         * --------------------------- */
        function calculate() {
            let purchasePrice = parseFloat($('#purchase_price').val()) || 0;
            let salePrice = parseFloat($('#sale_price').val()) || 0;

            let profit = 0, loss = 0;

            if (salePrice > purchasePrice) {
                profit = salePrice - purchasePrice;
            } else if (purchasePrice > salePrice) {
                loss = purchasePrice - salePrice;
            }

            $('#sale_profit').val(profit.toFixed(2));
            $('#sale_loss').val(loss.toFixed(2));
        }

        $('#purchase_price, #sale_price').on('input', calculate);


        /** ---------------------------
         *  Auto-fill Pax info
         * --------------------------- */
        $('#sale_customer_id').on('change', function () {
            let selectedOption = $(this).find('option:selected');
            let customerName  = selectedOption.data('name')  || '';
            let customerPhone = selectedOption.data('phone') || '';

            $('#pax_name').val(customerName);
            $('#pax_mobile_no').val(customerPhone);
        });


        /** ---------------------------
         *  Disable same customer in vendor select
         * --------------------------- */
        $('#sale_customer_id').on('change', function () {
            let selectedClient = $(this).val();

            $('#purchase_vendor_id option').each(function () {
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

