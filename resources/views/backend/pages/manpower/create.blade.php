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
                        <h4 class="text-center mb-4">Add New Manpower Invoice
                            <a data-url="{{ route('admin.customer.create') }}" class="btn btn-sm btn-secondary show-modal"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Add Client</a>
                        </h4>
                        <div>
                            <a href="{{ route('admin.inventory.manpower.index') }}" class="btn btn-sm btn-secondary">Go
                                Back</a>
                        </div>
                    </div>
                    @if ($manpower)
                        <form action="{{ route('admin.inventory.manpower.update', $manpower->id) }}" method="POST"
                            class="formSubmit">
                            @csrf
                            @method('put')
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_customer_id">Select Client</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="sale_customer_id" id="sale_customer_id"
                                                        class="clients-select form-control select2"
                                                        onchange="updateClientDetails()">
                                                        <option value="">Select Client</option>
                                                        @foreach ($customers as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-name="{{ $item->name }}"
                                                                data-phone="{{ $item->phone }}"
                                                                data-due="{{ $item->total_due }}"
                                                                data-balance="{{ $item->balance }}"
                                                                {{ $manpower->sales->sale_customer_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->name }} || {{ $item->phone }} ||

                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-sale_customer_id"></span>
                                            <span class="" id="client_due_display"></span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="visit_country">Manpower Country</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="visit_country" id="visit_country"
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option value="">Select Country
                                                        </option>
                                                        @foreach ($country as $item)
                                                            <option value="{{ $item->name }}"
                                                                {{ $manpower->visit_country == $item->name ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-visit_country"></span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="tracking_id">Passport number</label>
                                            <input name="tracking_id" id="tracking_id" type="text"
                                                value=" {{ $manpower->tracking_id }}" class="form-control form-control-sm"
                                                placeholder="Passport Number" />
                                            <span class="text-danger error-message" id="error-tracking_id"></span>
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_date">Sales Date</label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="sale_date" id="sale_date" type="text"
                                                    class="datepicker form-control form-control-sm"
                                                    value="{{ $manpower->sale_date }}" placeholder="dd/mm/yyyy" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="delivery_date">Submitted Date</label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="delivery_date" id="delivery_date" type="text"
                                                    class="datepicker form-control form-control-sm"
                                                    value="{{ $manpower->delivery_date }}" placeholder="dd/mm/yyyy" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="mb-0" for="purchase_vendor_id">Purchase Vendor</label>
                                            <div class="input-group">
                                                <select name="purchase_vendor_id" id="purchase_vendor_id"
                                                    class="single-select form-control select2 purchase_vendor_id">
                                                    <option value="">Select Vendor</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $manpower->purchase->purchase_vendor_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                            || {{ $item->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="text-danger error-message" id="error-purchase_vendor_id"></span>
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
                                                            value="{{ $manpower->purchase->purchase_price }}"
                                                            placeholder="Purchase Price" />
                                                    </div>
                                                    <span class="text-danger error-message"
                                                        id="error-purchase_price"></span>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="mb-0" for="sale_price">Sale Price<span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input name="sale_price" id="sale_price" type="number"
                                                            class="form-control form-control-sm"
                                                            value="{{ $manpower->sales->sale_price }}"
                                                            placeholder="Sale price" />
                                                    </div>
                                                    <span class="text-danger error-message" id="error-sale_price"></span>
                                                </div>

                                                <div class="col-md-2 mb-3">
                                                    <label class="mb-0" for="sale_profit">Profits<span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input name="sale_profit" id="sale_profit" type="number"
                                                            readonly class="form-control form-control-sm"
                                                            placeholder="0"value="{{ $manpower->sales->sale_profit }}" />
                                                    </div>

                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="mb-0" for="sale_loss">Loss<span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input name="sale_loss" id="sale_loss" type="number" readonly
                                                            value="{{ $manpower->sales->sale_loss }}"
                                                            class="form-control form-control-sm" placeholder="0" />
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
                                                                        placeholder="Pax Name"
                                                                        value="{{ $item['name'] }}">
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
                                                                        placeholder="Phone"
                                                                        value="{{ $item['mobile_no'] }}">
                                                                </div>
                                                                <span class="text-danger error-message"
                                                                    id="error-pax_mobile_no"></span>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="">Sale Note</label>
                                                                    <textarea name="sale_note" class="form-control" rows="2" cols="2" id="sale_note">{{ $manpower->sales->sale_note }}</textarea>

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
                                    <i class="fas fa-sync"></i> Edit & Update</button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('admin.product.store') }}" method="POST" class="formSubmit">
                            @csrf
                            <input type="hidden" value="manpower" name="product_type">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_customer_id">Select Client</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="sale_customer_id" id="sale_customer_id"
                                                        class="clients-select form-control select2"
                                                        onchange="updateClientDetails()">
                                                        <option value="">Select Client</option>
                                                        @foreach ($customers as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-name="{{ $item->name }}"
                                                                data-phone="{{ $item->phone }}"
                                                                data-due="{{ $item->total_due }}"
                                                                data-balance="{{ $item->balance }}">
                                                                {{ $item->name }} || {{ $item->phone }} ||

                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-sale_customer_id"></span>
                                            <span class="" id="client_due_display"></span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="visit_country">Manpower Country</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="visit_country" id="visit_country"
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option value="">Select Country
                                                        </option>
                                                        @foreach ($country as $item)
                                                            <option value="{{ $item->name }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-visit_country"></span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="tracking_id">Passport number</label>
                                            <input name="tracking_id" id="tracking_id" type="text"
                                                class="form-control form-control-sm" placeholder="Passport Number" />
                                            <span class="text-danger error-message" id="error-tracking_id"></span>
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_date">Sales Date</label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="sale_date" id="sale_date" type="date"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('sale_date', date('Y-m-d')) }}"
                                                    placeholder="dd/mm/yyyy" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="delivery_date">Submitted Date</label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="delivery_date" id="delivery_date" type="date"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('delivery_date', date('Y-m-d')) }}"
                                                    placeholder="dd/mm/yyyy" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="mb-0" for="purchase_vendor_id">Purchase Vendor</label>
                                            <div class="input-group">
                                                <select name="purchase_vendor_id" id="purchase_vendor_id"
                                                    class="single-select form-control select2 purchase_vendor_id">
                                                    <option value="">Select Vendor</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                            || {{ $item->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="text-danger error-message" id="error-purchase_vendor_id"></span>
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
                                                    <span class="text-danger error-message"
                                                        id="error-purchase_price"></span>
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
                                                        <input name="sale_profit" id="sale_profit" type="number"
                                                            readonly class="form-control form-control-sm"
                                                            placeholder="0" />
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
                                                        <span class="text-danger error-message"
                                                            id="error-g_pax_name"></span>
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
                                <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-sync"></i> Send
                                    data</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        // DOM elements target
        const purchasePriceInput = document.getElementById('purchase_price');
        const salePriceInput = document.getElementById('sale_price');
        const profitInput = document.getElementById('sale_profit');
        const lossInput = document.getElementById('sale_loss');

        // Attach event listeners to input fields
        purchasePriceInput.addEventListener('input', calculate);
        salePriceInput.addEventListener('input', calculate);

        function calculate() {
            // Fetch input values and parse them as numbers
            const purchasePrice = parseFloat(purchasePriceInput.value) || 0;
            const salePrice = parseFloat(salePriceInput.value) || 0;

            // Initialize profit and loss to 0
            let profit = 0;
            let loss = 0;

            // Calculate profit or loss
            if (salePrice > purchasePrice) {
                profit = salePrice - purchasePrice;
            } else if (purchasePrice > salePrice) {
                loss = purchasePrice - salePrice;
            }

            // Update the result fields
            profitInput.value = profit.toFixed(2); // Show profit (if any)
            lossInput.value = loss.toFixed(2); // Show loss (if any)
        }
    </script>



    <script>
        function updateClientDetails() {
            // Get the selected option from the dropdown
            const selectedOption = document.querySelector('#sale_customer_id').selectedOptions[0];

            // Get the name, phone number, and due amount from the selected option's data attributes
            const clientName = selectedOption.getAttribute('data-name') || '';
            const phoneNumber = selectedOption.getAttribute('data-phone') || '';
            const clientDue = selectedOption.getAttribute('data-due') || '0.00';
            const clientbalance = selectedOption.getAttribute('data-balance') || '0.00';

            // Update the input fields with the selected client's details
            document.querySelector('#pax_name').value = clientName;
            document.querySelector('#pax_mobile_no').value = phoneNumber;

            // Show the due amount
            const dueElement = document.querySelector('#client_due_display');
            if (clientDue > 0) {
                dueElement.textContent = `Due : ${clientDue}/=`;
                dueElement.style.color = 'red';
            } else {
                dueElement.textContent = `Balance : ${clientbalance}/=`;
                dueElement.style.color = 'green';
            }
        }

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
