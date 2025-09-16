@extends('backend.layout.app')
@push('css')
<style>
    body { background: #f8f9fa; }
    .invoice-container { background: #fff; padding: 30px; border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); max-width: 1200px; margin:auto; }
    .invoice-header { border-bottom: 2px solid #dee2e6; margin-bottom: 20px; padding-bottom: 10px; }
    .invoice-title { font-size:24px; font-weight:bold; color:#333; }
    .table th, .table td { vertical-align: middle !important; }
    .total-row td { font-weight:bold; }

    @media print {
        body { background:#fff !important; }
        #printBtn, #add_pax, .remove_pax, .select2, select { display:none !important; }
        .invoice-container { box-shadow:none !important; border:none !important; margin:0; width:100%; max-width:100%; page-break-after:always; }
        @page { size:A4; margin:20mm; }
    }
    .address p { margin-bottom:0 !important; }
</style>
@endpush

@section('content')
<div class="container my-4">
    <form action="{{ route('admin.inventory.other.update', $bill->id) }}" method="post" class="formSubmit">
        @csrf
        @method('PUT')

        <div class="invoice-container" id="invoiceArea">
            <!-- Header -->
            <div class="row invoice-header">
                <div class="col-md-6 address">
                    {!! setting('pdf_address') !!}
                </div>
                <div class="col-md-6 text-right">
                    <h5 class="text-primary">INVOICE</h5>
                    <p class="mb-0">Invoice #: {{ $bill->invoice_no ?? '123' }}</p>
                    <p class="mb-0">
                        Date:
                        <input type="date" name="sale_date" id="sale_date" class="form-control form-control-sm d-inline-block w-auto"
                            value="{{ old('sale_date', $bill->sale_date ?? date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                    </p>
                </div>
            </div>

            <!-- Client Info -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Service type</label>
                    <select name="service_type" id="service_type" class="form-control form-control-sm select2" style="width:100%">
                        <option value="">Select type</option>
                        <option value="b2c" {{ $bill->service_type == 'b2c' ? 'selected' : '' }}>Office to Client</option>
                        <option value="b2b" {{ $bill->service_type == 'b2b' ? 'selected' : '' }}>Vendor to Office</option>
                    </select>
                </div>

                <div class="col-md-4 sale_client_section" style="{{ $bill->service_type=='b2c' ? '' : 'display:none' }}">
                    <label>Client/Customer:</label>
                    <select name="sale_customer_id" class="form-control form-control-sm select2">
                        <option value="">Select Client</option>
                        @foreach ($customers as $item)
                            <option value="{{ $item->id }}" {{ $bill->sales->sale_customer_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} || {{ $item->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 purchase_client_section" style="{{ $bill->service_type=='b2b' ? '' : 'display:none' }}">
                    <label>Office Service Purchase Supplier</label>
                    <select name="purchase_vendor_id" class="form-control form-control-sm select2">
                        <option value="">Select Purchase Vendor</option>
                        @foreach ($customers as $item)
                            <option value="{{ $item->id }}" {{ $bill->purchase_vendor_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} || {{ $item->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Total Purchase Price</label>
                    <input type="number" id="purchsae_price" class="form-control form-control-sm" name="purchase_price"
                        value="{{ old('purchase_price', $bill->purchase->purchase_price ?? 0) }}" required>
                </div>
            </div>

            <!-- Services Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:50%">Service</th>
                            <th style="width:30%">Cost</th>
                            <th style="width:20%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="services-wrapper">
                        @php $services = json_decode($bill->meta_data) ?? [] @endphp
                        @if(count($services) > 0)
                            @foreach($services as $service)
                            <tr class="service-item">
                                <td>
                                    <input type="text" name="service_name[]" class="form-control form-control-sm service_name" value="{{ $service->service_name }}">
                                </td>
                                <td>
                                    <input type="number" name="service_cost[]" class="form-control form-control-sm service_cost" value="{{ $service->service_cost }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove_pax"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="service-item">
                                <td>
                                    <input type="text" name="service_name[]" class="form-control form-control-sm service_name">
                                </td>
                                <td>
                                    <input type="number" name="service_cost[]" class="form-control form-control-sm service_cost">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove_pax"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-secondary btn-sm mb-3" id="add_pax">
                <i class="fa fa-plus"></i> Add More
            </button>

            <!-- Totals -->
            <div class="row justify-content-end">
                <div class="col-md-6">
                    <table class="table">
                        <tr class="total-row">
                            <td>Total Sale Price</td>
                            <td><input type="text" id="sale_price" name="sale_price" class="form-control form-control-sm text-right sale_price" value="{{ old('sale_price', $bill->sale_price ?? 0) }}" readonly></td>
                        </tr>
                        <tr class="total-row">
                            <td>Total Profit</td>
                            <td><input type="number" id="total_profit" name="sale_profit" class="form-control form-control-sm text-right total_profit" value="{{ old('sale_profit', $bill->sale_profit ?? 0) }}" readonly></td>
                        </tr>
                        <tr class="total-row">
                            <td>Total Loss</td>
                            <td><input type="number" id="sale_loss" name="sale_loss" class="form-control form-control-sm text-right total_loss" value="{{ old('sale_loss', $bill->sale_loss ?? 0) }}" readonly></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6 mb-2 purchase_payment_method" style="{{ $bill->service_type=='b2c' ? '' : 'display:none' }}">
                    <label>Purchase Method</label>
                    <select name="purchase_account_id" class="form-control form-control-sm select2">
                        <option value="">Select Method</option>
                        @foreach ($account as $item)
                            <option value="{{ $item->id }}" {{ $bill->purchase->purchase_account_id == $item->id ? 'selected' : '' }}>
                                {{ $item->account_name }} || {{ currencyBD($item->current_balance) }}/=
                            </option>
                        @endforeach
                    </select>
                    <label class="mt-2">Purchase Transaction No</label>
                    <input type="text" name="purchase_tnxid" class="form-control form-control-sm" value="{{ old('purchase_tnxid', $bill->purchase_tnxid ?? '') }}">
                </div>
            </div>
        </div>

        <div class="text-right mt-3">
            <button class="btn btn-secondary" type="submit"><i class="fas fa-sync"></i> Save Invoice</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    function calculateTotals() {
        let totalSale = 0;
        $(".service_cost").each(function() {
            totalSale += parseFloat($(this).val()) || 0;
        });
        let purchasePrice = parseFloat($("#purchsae_price").val()) || 0;
        $("#sale_price").val(totalSale);
        let diff = totalSale - purchasePrice;
        $("#total_profit").val(diff > 0 ? diff : 0);
        $("#total_loss").val(diff < 0 ? Math.abs(diff) : 0);
    }

    // Initial calculation
    calculateTotals();

    $(document).on("input", ".service_cost", calculateTotals);
    $("#purchsae_price").on("input", calculateTotals);

    $("#add_pax").click(function() {
        let newRow = `<tr class="service-item">
            <td><input type="text" name="service_name[]" class="form-control form-control-sm service_name"></td>
            <td><input type="number" name="service_cost[]" class="form-control form-control-sm service_cost"></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove_pax"><i class="fa fa-times"></i></button></td>
        </tr>`;
        $("#services-wrapper").append(newRow);
    });

    $(document).on("click", ".remove_pax", function() {
        $(this).closest("tr").remove();
        calculateTotals();
    });

    // Service type toggle
    function toggleServiceSections() {
        let type = $("#service_type").val();
        if(type == 'b2c'){
            $('.sale_client_section, .purchase_payment_method').show();
            $('.purchase_client_section').hide();
        } else if(type == 'b2b'){
            $('.purchase_client_section').show();
            $('.sale_client_section, .purchase_payment_method').hide();
        } else {
            $('.sale_client_section, .purchase_client_section, .purchase_payment_method').hide();
        }
    }
    toggleServiceSections();
    $("#service_type").on("change", toggleServiceSections);

});
</script>
@endpush
