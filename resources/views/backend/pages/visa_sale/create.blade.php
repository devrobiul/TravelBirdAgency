@extends('backend.layout.app')
@push('style')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')

        <div class="col-md-12">
            <div class="row">
                <div class="card card-body">
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="text-center mb-4">Add New Visa Invoice
                            <a data-url="{{ route('admin.customer.create') }}" class="btn btn-sm btn-secondary show-modal"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Add Client</a>
                        </h4>
                        <div>
                            <a href="{{ route('admin.inventory.visasale.index') }}" class="btn btn-sm btn-secondary">Go Back</a>
                        </div>
                    </div>
                    @if ($visa_sale)
                        <form action="{{ route('admin.inventory.visasale.update', $visa_sale->id) }}" method="POST"
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
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option value="">Select Client</option>
                                                        @foreach ($customers as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $visa_sale->sales->sale_customer_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-sale_customer_id"></span>

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="visa_id">Select Visa</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="visa_id" id="visa_id"
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option>Select Visa
                                                        </option>
                                                        @foreach ($visa as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $visa_sale->visa_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->visa_name }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-visa_id"></span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="visa_type">Visa Type</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="visa_type" id="visa_type"
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option value="">Visa Type</option>
                                                        <option value="Student"
                                                            {{ $visa_sale->visa_type == 'Student' ? 'selected' : '' }}>
                                                            Student Visa</option>
                                                        <option value="Medical"
                                                            {{ $visa_sale->visa_type == 'Medical' ? 'selected' : '' }}>
                                                            Medical Visa</option>
                                                        <option value="Tourist"
                                                            {{ $visa_sale->visa_type == 'Tourist' ? 'selected' : '' }}>
                                                            Tourist Visa</option>
                                                        <option value="Umrah"
                                                            {{ $visa_sale->visa_type == 'Umrah' ? 'selected' : '' }}>Umrah
                                                            Visa</option>
                                                        <option value="Labour"
                                                            {{ $visa_sale->visa_type == 'Labour' ? 'selected' : '' }}>
                                                            Labour Visa</option>
                                                        <option value="Immigrant"
                                                            {{ $visa_sale->visa_type == 'Immigrant' ? 'selected' : '' }}>
                                                            Immigrant Visa</option>
                                                        <option value="Nonimmigrant"
                                                            {{ $visa_sale->visa_type == 'Nonimmigrant' ? 'selected' : '' }}>
                                                            Nonimmigrant Visa</option>
                                                        <option value="Refugee"
                                                            {{ $visa_sale->visa_type == 'Refugee' ? 'selected' : '' }}>
                                                            Refugee Visa</option>
                                                        <option value="Business"
                                                            {{ $visa_sale->visa_type == 'Business' ? 'selected' : '' }}>
                                                            Business Visa</option>
                                                        <option value="Schengen"
                                                            {{ $visa_sale->visa_type == 'Schengen' ? 'selected' : '' }}>
                                                            Schengen Visa</option>
                                                        <option value="eVisa"
                                                            {{ $visa_sale->visa_type == 'eVisa' ? 'selected' : '' }}>eVisa
                                                        </option>
                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-visa_type"></span>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="mb-0" for="visit_country">Visiting Country <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input name="visit_country" id="visit_country" type="text"
                                                    class="form-control form-control-sm"
                                                    value="{{ $visa_sale->visit_country }}"
                                                    placeholder="Enter visit country" />
                                            </div>
                                            <span class="text-danger error-message" id="error-visit_country"></span>

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_date">Sales Date</label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="sale_date" id="sale_date" type="date"
                                                    class=" form-control form-control-sm"
                                                    value="{{ $visa_sale->sale_date }}" placeholder="dd/mm/yyyy" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="mb-0" for="purchase_vendor_id">Purchase Vendor</label>
                                            <div class="input-group">
                                                <select name="purchase_vendor_id" id="purchase_vendor_id"
                                                    class="single-select form-control select2 purchase_vendor_id">
                                                    <option value="0"
                                                        {{ $visa_sale->purchase->purchase_vendor_id == 0 ? 'selected' : '' }}>
                                                        My self</option>
                                                    @foreach ($customers as $item)
                                                        <option
                                                            value="{{ $item->id }}"{{ $visa_sale->purchase->purchase_vendor_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                            || {{ $item->phone }} || {{ $item->passport_no }}
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
                                                            value="{{ $visa_sale->purchase->purchase_price }}"
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
                                                            value="{{ $visa_sale->sales->sale_price }}"
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
                                                            value="{{ $visa_sale->sales->sale_profit }}"
                                                            placeholder="0" />
                                                    </div>

                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="mb-0" for="sale_loss">Loss<span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input name="sale_loss" id="sale_loss" type="number" readonly
                                                            class="form-control form-control-sm" placeholder="0"
                                                            value="{{ $visa_sale->sales->sale_loss }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 purchase-method-section"
                                                    id="purchase_payment_method" style="">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="mb-0" for="purchase_method_id">Purchase
                                                                Method</label>
                                                            <div class="input-group">
                                                                <select name="purchase_account_id"
                                                                    id="purchase_account_id"
                                                                    class="single-select form-control select2">
                                                                    <option value="">Select Method</option>
                                                                    @foreach ($account as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            {{ $visa_sale->purchase->purchase_account_id == $item->id ? 'selected' : '' }}>
                                                                            {{ $item->account_name }} || {{ currencyBD($item->current_balance )}}/=
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
                                                                placeholder="Purchase tnxid" name="purchase_tnxid"
                                                                value="{{ $visa_sale->purchase->purchase_tnxid }}">
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
                @foreach($pax_data as $index => $pax)
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label for="pax_name_{{ $index }}">Pax Name</label>
                            <input type="text" name="pax_name[]" id="pax_name_{{ $index }}"
                                value="{{ $pax['name'] ?? '' }}"
                                class="form-control form-control-sm @error('pax_name') is-invalid @enderror"
                                placeholder="Pax name">
                            <span class="text-danger error-message" id="error-pax_name_{{ $index }}"></span>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label for="pax_mobile_no_{{ $index }}">Pax Mobile</label>
                            <input type="number" name="pax_mobile_no[]" id="pax_mobile_no_{{ $index }}"
                                value="{{ $pax['mobile_no'] ?? '' }}"
                                class="form-control form-control-sm @error('pax_mobile_no') is-invalid @enderror"
                                placeholder="Pax mobile">
                            <span class="text-danger error-message" id="error-pax_mobile_no_{{ $index }}"></span>
                        </div>
                    </div>
                @endforeach

                <div class="col-md-3 mb-2">
                    <div class="form-group">
                        <label for="visa_issue_date">Date of Issue</label>
                        <input type="date" name="visa_issue_date" id="visa_issue_date"
                            value="{{ $visa_sale->visa_issue_date }}"
                            class="form-control form-control-sm ">
                        <span class="text-danger error-message" id="error-visa_issue_date"></span>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <div class="form-group">
                        <label for="visa_exp_date">Date of Expire</label>
                        <input type="date" name="visa_exp_date" id="visa_exp_date"
                            value="{{ $visa_sale->visa_exp_date }}"
                            class="form-control form-control-sm">
                        <span class="text-danger error-message" id="error-visa_exp_date"></span>
                    </div>
                </div>

                <div class="col-md-12 mb-2">
                    <div class="form-group">
                        <label for="sale_note">Sale Note</label>
                        <textarea name="sale_note" id="sale_note" class="form-control form-control-sm" rows="3"
                            placeholder="Sale note">{{ $visa_sale->sales->sale_note ?? '' }}</textarea>
                    </div>
                </div>
            </div>

    
        </div>
    </div>
</div>

                            </div>
                                    <div class="text-center">
                <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="fas fa-sync    "></i> Edit & Update
                </button>
            </div>
                        </form>
                    @else
                        <form action="{{ route('admin.product.store') }}" method="POST" class="formSubmit">
                            @csrf
                            <input type="hidden" value="visa_sale" name="product_type">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_customer_id">Select Client</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="sale_customer_id" id="sale_customer_id"
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option value="">Select Client</option>
                                                        @foreach ($customers as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-sale_customer_id"></span>

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="visa_id">Select Visa</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="visa_id" id="visa_id"
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option>Select Visa
                                                        </option>
                                                        @foreach ($visa as $item)
                                                            <option value="{{ $item->id }}">{{ $item->visa_name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-visa_id"></span>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="visa_type">Visa Type</label>
                                            <div class="d-flex">
                                                <div class="input-group w-100">
                                                    <select name="visa_type" id="visa_type"
                                                        class="clients-select form-control form-control-md select2"
                                                        style="width: 100%">
                                                        <option value="">Visa Type</option>
                                                        <option value="Student">Student Visa</option>
                                                        <option value="Medical">Medical Visa</option>
                                                        <option value="Tourist">Tourist Visa</option>
                                                        <option value="Umrah">Umrah Visa</option>
                                                        <option value="Labour">Labour Visa</option>
                                                        <option value="Immigrant">Immigrant visa</option>
                                                        <option value="Nonimmigrant">Nonimmigrant visa</option>
                                                        <option value="Refugee">Refugee visa</option>
                                                        <option value="Business">Business visa</option>
                                                        <option value="Schengen">Schengen visa</option>
                                                        <option value="eVisa">eVisa</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <span class="text-danger error-message" id="error-visa_type"></span>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="mb-0" for="visit_country">Visiting Country <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input name="visit_country" id="visit_country" type="text"
                                                    class="form-control form-control-sm" value=""
                                                    placeholder="Enter visit country" />
                                            </div>
                                            <span class="text-danger error-message" id="error-visit_country"></span>

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="mb-0" for="sale_date">Sales Date</label>
                                            <div class="input-group">
                                                <span
                                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input name="sale_date" id="sale_date" type="date"
                                                    class=" form-control form-control-sm"
                                                    value="{{ old('sale_date', date('Y-m-d')) }}"
                                                    placeholder="dd/mm/yyyy" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="mb-0" for="purchase_vendor_id">Purchase Vendor</label>
                                            <div class="input-group">
                                                <select name="purchase_vendor_id" id="purchase_vendor_id"
                                                    class="single-select form-control select2 purchase_vendor_id">
                                                    <option value="0">My self</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                            || {{ $item->phone }} || {{ $item->passport_no }}
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
                                                <div class="col-md-12 mb-3 purchase-method-section"
                                                    id="purchase_payment_method" style="">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="mb-0" for="purchase_method_id">Purchase
                                                                Method</label>
                                                            <div class="input-group">
                                                                <select name="purchase_account_id"
                                                                    id="purchase_account_id"
                                                                    class="single-select form-control select2">
                                                                    <option value="">Select Method</option>
                                                                    @foreach ($account as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->account_name }} || {{ currencyBD($item->current_balance) }}/=
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="pax_name">Pax Name</label>
                                                        <input type="text" name="g_pax_name[]" id="pax_name"
                                                            value="{{ old('g_pax_name') }}"
                                                            class="form-control form-control-sm @error('g_pax_name')is-invalid @enderror"
                                                            placeholder="Pax name" aria-describedby="helpId">
                                                        <span class="text-danger error-message"
                                                            id="error-g_pax_name"></span>
                                                    </div>

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="pax_mobile_no">Pax Mobile</label>
                                                        <input type="number" name="g_pax_mobile_no[]" id="g_pax_mobile_no"
                                                            value="{{ old('g_pax_mobile_no') }}"
                                                            class="form-control form-control-sm @error('g_pax_mobile_no')is-invalid @enderror"
                                                            placeholder="visa mobile" aria-describedby="helpId">
                                                        <input type="hidden" name="g_pax_type[]" id="g_pax_type"
                                                            value="{{ old('g_pax_type') }}">
                                                        <span class="text-danger error-message"
                                                            id="error-g_pax_mobile_no"></span>
                                                    </div>

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="visa_issue_date">Date of Issue</label>
                                                        <input type="date" name="visa_issue_date" id="visa_issue_date"
                                                            value="{{ old('visa_issue_date', date('Y-m-d')) }}"
                                                            class="form-control form-control-sm"
                                                            aria-describedby="helpId">
                                                        <span class="text-danger error-message"
                                                            id="error-visa_issue_date"></span>
                                                    </div>

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="visa_exp_date">Date of Expire</label>
                                                        <input type="date" name="visa_exp_date" id="visa_exp_date"
                                                            value="{{ old('visa_exp_date', date('Y-m-d')) }}"
                                                            class="form-control form-control-sm"
                                                            aria-describedby="helpId">
                                                        <span class="text-danger error-message"
                                                            id="error-visa_exp_date"></span>
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
                                <button class="btn btn-secondary btn-sm"><i class="fas fa-sync    "></i> Send Data</button>
                            </div>
                        </form>
                    @endif
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
        $(document).ready(function() {

            const vendorSelect = $('#purchase_vendor_id');
            const paymentMethodSection = $('#purchase_payment_method');

            const togglePaymentMethod = () => {
                const selectedValue = vendorSelect.val();
                if (selectedValue === '0') {
                    paymentMethodSection.show();
                } else {
                    paymentMethodSection.hide();
                }
            };

            vendorSelect.change(togglePaymentMethod);
            togglePaymentMethod();
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
