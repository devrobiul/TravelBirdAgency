@extends('backend.layout.app') @push('css')
    <style>
        .select2-container .select2-selection--single {
            height: 33px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
        }
    </style>
@endpush

@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="text-center mb-4">Add New Invoice (Passport) <a
                                data-url="{{ route('admin.customer.create') }}" class="btn btn-sm btn-secondary show-modal"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Add Client</a></h4>
                        <div>
                            <a class="btn btn-sm btn-secondary" href="{{ route('admin.inventory.passport.index') }}"><i
                                    class="fa fa-minus" aria-hidden="true"></i> Go Back</a>
                        </div>
                    </div>

                    <form action="{{ route('admin.inventory.passport.store') }}" method="POST" class="formSubmit">
                        @csrf
                        <input type="hidden" value="passport" name="product_type">
                        <div class="row justify-content-between">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex justify-content-between">
                                            <h4 class="text-center mb-2">Passport Details Section</h4>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="client_id">Passport Type</label>
                                                <div class="d-flex">
                                                    <div class="input-group w-100">
                                                        <select name="passport_type" id="passport_type"
                                                            class="clients-select form-control select2" style="width: 100%">
                                                            <option value="new_passport">New Passport</option>
                                                            <option value="reissue_passport">Re-Issue Passport</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <span class="text-danger error-message" id="error-passport_type"></span>

                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="sale_customer_id">Select Client</label>
                                                <div class="d-flex">
                                                    <div class="input-group w-100">
                                                        <select name="sale_customer_id" id="sale_customer_id"
                                                            class="clients-select form-control select2">
                                                            <option value="">Select Client</option>
                                                            @foreach ($customers as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name }} ||
                                                                    {{ $item->phone }} || {{ $item->passport_no }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <span class="text-danger error-message" id="error-sale_customer_id"></span>

                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="dath_of_birth">DOB<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span
                                                        class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                    <input type="date" name="dath_of_birth" id="dath_of_birth"
                                                        value="{{ date('Y-m-d') }}" class="form-control form-control-sm"
                                                        placeholder="" />
                                                </div>
                                                <span class="text-danger error-message" id="error-dath_of_birth"></span>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="tracking_id">Tracking Id<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="tracking_id" id="tracking_id"
                                                        class="form-control form-control-sm" placeholder="Tracking Id" />
                                                </div>
                                                <span class="text-danger error-message" id="error-tracking_id"></span>
                                            </div>




                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="sale_date">Sales Date</label>
                                                <div class="input-group">
                                                    <input name="sale_date" id="sale_date" type="date"
                                                        class="form-control form-control-sm"
                                                        value="{{ old('sale_date', date('Y-m-d')) }}" placeholder="" />
                                                </div>
                                                <span class="text-danger error-message" id="error-sale_date"></span>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="mb-0" for="delivery_date">Delivery Date</label>
                                                <div class="input-group">
                                                    <input name="delivery_date" id="delivery_date" type="date"
                                                        class="form-control form-control-sm" value="{{ date('Y-m-d') }}"
                                                        placeholder="Y-m-d" />
                                                </div>
                                                <span class="text-danger error-message" id="error-delivery_date"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <span class="">Passport Pricing</span>
                                        <span><input class="form-check-input" id="vendorCheckbox" type="checkbox">Check
                                            Vendor</span>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6 mb-2 purchase_vendor">
                                                <label class="mb-0" for="vendor_id">Select Vendor</label>
                                                <div class="d-flex">
                                                    <div class="input-group w-100">
                                                        <select name="vendor_id" id="vendor_id"
                                                            class="clients-select form-control select2">
                                                            <option value="">Select Vendor</option>
                                                            @foreach ($customers as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name }} ||
                                                                    {{ $item->phone }} || {{ $item->passport_no }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <span class="text-danger error-message"
                                                    id="error-purchase_vendor_id"></span>

                                            </div>
                                            <div class="col-md-6 b-2 sign_price">
                                                <label class="mb-0" for="sign_price">Sign Price<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sign_price" id="sign_price" type="number"
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="Sign Price" />
                                                </div>
                                                <span class="text-danger error-message" id="error-sign_price"></span>
                                            </div>
                                            <div class="col-md-6 b-3">
                                                <label class="mb-0" for="purchase_price">Cost Price<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="purchase_price" id="purchase_price" type="number"
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="Cost" />
                                                </div>
                                                <span class="text-danger error-message" id="error-purchase_price"></span>
                                            </div>
                                            <div class="col-md-6 b-3">
                                                <label class="mb-0" for="sale_price">Sale Price<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_price" id="sale_price" type="number"
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="Sale" />
                                                </div>
                                                <span class="text-danger error-message" id="error-sale_price"></span>

                                            </div>


                                            <div class="col-md-6 mb-2">
                                                <label class="mb-0" for="sale_profit">Profits<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_profit" id="sale_profit" type="number" readonly
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="mb-0" for="sale_loss">Loss<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input name="sale_loss" id="sale_loss" type="number" readonly
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="mb-0" for="purchase_account_id">Purchase
                                                    Mehtod</label>
                                                <div class="d-flex">
                                                    <div class="input-group w-100">
                                                        <select name="purchase_account_id" id="purchase_account_id"
                                                            class="clients-select form-control select2">
                                                            <option value="">Select Method</option>
                                                            @foreach ($account as $item)
                                                                <option value="{{ $item->id }}">
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
                                                    <input name="purchase_tnxid" id="purchase_tnxid" type="text"
                                                        class="form-control form-control-sm" value=""
                                                        placeholder="Transaction No" />

                                                </div>
                                                <span class="text-danger error-message" id="error-purchase_tnxid"></span>

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
                                            <div class="col-md-4 mb-2">
                                                <label class="mb-0">Pax Type <span class="text-danger">*</span></label>
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
                                                <span class="text-danger error-message" id="error-pax_type"></span>
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
                            <button type="submit" class="btn btn-sm  btn-secondary">
                                <i class="fas fa-sync"></i> Send data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            // DOM elements target
            const purchasePriceInput = document.getElementById('purchase_price');
            const salePriceInput = document.getElementById('sale_price');
            const signPriceInput = document.getElementById('sign_price'); // New input field for sign price
            const profitInput = document.getElementById('sale_profit');
            const lossInput = document.getElementById('sale_loss');

            // Attach event listeners to input fields
            purchasePriceInput.addEventListener('input', calculate);
            salePriceInput.addEventListener('input', calculate);
            signPriceInput.addEventListener('input', calculate); // Listen for input on sign price

            function calculate() {
                // Fetch input values and parse them as numbers
                const purchasePrice = parseFloat(purchasePriceInput.value) || 0;
                const salePrice = parseFloat(salePriceInput.value) || 0;
                const signPrice = parseFloat(signPriceInput.value) || 0; // Get value from sign price input

                // Initialize profit and loss to 0
                let profit = 0;
                let loss = 0;

                // Calculate profit based on sign_price
                if (signPrice) {
                    profit = signPrice + purchasePrice - salePrice;
                } else {
                    profit = purchasePrice - salePrice;
                }

                // Calculate loss if profit is negative
                if (profit > 0) {
                    loss = -profit; // Loss is the absolute value of negative profit
                    profit = 0; // Set profit to 0 since it's a loss
                }

                // Update the result fields
                profitInput.value = Math.abs(profit); // Show profit (if any)
                lossInput.value = Math.abs(loss); // Show loss as a positive number (if any)

            }
        </script>



        <script>
            $(document).ready(function() {
                $('#sale_customer_id').change(function() {
                    var selectedOption = $(this).find('option:selected');
                    var customerName = selectedOption.text().split("||")[0].trim();
                    var customerPhone = selectedOption.text().split("||")[1].trim();
                    $('#pax_name').val(customerName);
                    $('#pax_mobile_no').val(customerPhone);
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('.purchase_vendor').hide();
                $('.sign_price').hide();

                $('#vendorCheckbox').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.purchase_vendor').show();
                        $('.sign_price').show();
                    } else {
                        $('.purchase_vendor').hide();
                        $('.sign_price').hide();
                    }
                });
            });
            $(document).ready(function() {
                $('#sale_customer_id').change(function() {
                    var selectedClient = $(this).val(); // সিলেক্ট করা কাস্টমারের ID

                    $('#purchase_vendor_id option').each(function() {
                        if ($(this).val() == selectedClient) {
                            $(this).prop('disabled',
                                true); // একই কাস্টমারকে পাসেস ভেন্ডর থেকে নিষ্ক্রিয় করা
                        } else {
                            $(this).prop('disabled', false); // অন্যদের সক্রিয় রাখা
                        }
                    });

                    $('#purchase_vendor_id').val(0).trigger('change'); // ডিফল্ট অপশন সিলেক্ট করা
                });
            });
        </script>
    @endpush
