@extends('backend.layout.app')
@push('css')
    <style>
        body {
            background: #f8f9fa;
        }

        .invoice-container {
            background: #fff;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            max-width: 1200px;
            /* A4 width */
            margin: auto;
        }

        .invoice-header {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .total-row td {
            font-weight: bold;
        }

        /* -------- Print Styles -------- */
        @media print {
            body {
                background: #fff !important;
            }

            #printBtn,
            #add_pax,
            .remove_pax,
            .select2,
            select {
                display: none !important;
            }

            .invoice-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0;
                width: 100%;
                max-width: 100%;
                page-break-after: always;
            }

            @page {
                size: A4;
                margin: 20mm;
            }
        }

        .address p {
            margin-bottom: 0 !important;
        }
    </style>
@endpush

@section('content')
    <div class="container my-4">
        <form action="{{ route('admin.product.store') }}" method="post" class="formSubmit">
            @csrf
            <input type="hidden" value="custom_bill" name="product_type">
        <div class="invoice-container" id="invoiceArea">
            <!-- Header -->
            <div class="row invoice-header">
                <div class="col-md-6 address">
                    {!! setting('pdf_address') !!}
                </div>
                <div class="col-md-6 text-right">
                    <h5 class="text-primary">INVOICE</h5>
                    <p class="mb-0">Invoice #: 123</p>
                    <p class="mb-0">
                        Date:
                        <input type="date" name="sale_date" id="sale_date"
                            class="form-control form-control-sm d-inline-block w-auto"
                            value="{{ old('sale_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                    </p>
                </div>
            </div>

            <!-- Client Info -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Service type</label>
                        <select name="service_type" id="service_type"
                            class="form-control form-control-sm mt-2 select2" required style="width: 100%">
                            <option value="">Select type</option>
                            <option value="b2c">Office to Client</option>
                            <option value="b2b">Vendor to Office</option>
                         
                        </select>
                    </div>
                </div>
                <div class="col-md-4 sale_client_section" style="display: none">
                    <div class="form-group">
                        <label>Client/Customer:</label>
                        <select name="sale_customer_id" id="sale_customer_id"
                            class="form-control form-control-sm mt-2 select2" style="width: 100%">
                            <option value="">Select Client</option>
                            @foreach ($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} || {{ $item->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 purchase_client_section" >
                    <div class="form-group">
                        <label>Office Service Purchase Supplier</label>
                        <select name="purchase_vendor_id" id="purchase_vendor_id"
                            class="form-control form-control-sm mt-2 select2" style="width: 100%">
                            <option value="">Select Purchase Vendor</option>
                            @foreach ($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} || {{ $item->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                      <div class="col-md-4 " >
                    <div class="form-group">
                        <label>Total Purchase Price</label>
                         <input type="number" id="purchsae_price" class="purhcase_price form-control form-control-sm" placeholder="00" name="purchase_price" required>
                    </div>
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
                        <tr class="service-item">
                            <td>
                                <input type="text" name="service_name[]"
                                    class="form-control form-control-sm service_name" placeholder="Service name">
                            </td>
                            <td>
                                <input type="number" name="service_cost[]"
                                    class="form-control form-control-sm service_cost text-right" placeholder="0.00">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove_pax">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add More Button -->
            <button type="button" class="btn btn-secondary btn-sm mb-3" id="add_pax">
                <i class="fa fa-plus"></i> Add More
            </button>

            <!-- Totals -->
            <div class="row justify-content-end">
                <div class="col-md-6">
                    <table class="table">
                        <tr class="total-row">
                            <td>Total Sale Price</td>
                            <td>
                                <input type="text" id="sale_price" name="sale_price" class="form-control form-control-sm text-right sale_price"
                                    value="0" readonly>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td>Total Profit</td>
                            <td>
                                <input type="number" id="total_profit" name="sale_profit" class="form-control form-control-sm text-right total_profit"
                                    value="0" readonly>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td>Total Loss</td>
                            <td>
                                <input type="number" id="sale_loss" name="sale_loss" class="form-control form-control-sm text-right total_loss"
                                    value="0" readonly>
                            </td>
                        </tr>

                    </table>
                </div>
                       <div class="col-md-6 mb-2 purchase_payment_method"
                                                    id="purchase_payment_method" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label class="mb-0" for="purchase_method_id">Purchase
                                                                Method</label>
                                                            <div class="input-group">
                                                                <select name="purchase_account_id" id="purchase_method_id"
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
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="mb-0" for="purchase_method_id">Purchase
                                                                Transaction
                                                                No</label>
                                                            <input type="text" class="form-control form-control-sm "
                                                                placeholder="Purchase tnxid" name="purchase_tnxid">
                                                            <span class="text-danger error-message"
                                                                id="error-purchase_tnxid"></span>
                                                        </div>

                                                    </div>
                                                </div>
            </div>

      
        </div>

        <!-- Print Button -->
        <div class="text-right mt-3">
            <button class="btn btn-secondary" type="submit">
                <i class="fas fa-sync"></i> Save Invoice
            </button>
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
            let val = parseFloat($(this).val()) || 0;
            totalSale += val;
        });

        let purchasePrice = parseFloat($("#purchsae_price").val()) || 0;

        // Sale Price
        $("#sale_price").val(totalSale);

        // Profit or Loss
        let difference = totalSale - purchasePrice;

        if(difference > 0){
            $("#total_profit").val(difference);
            $("#total_loss").val(0);
        } else if(difference < 0){
            $("#total_profit").val(0);
            $("#total_loss").val(Math.abs(difference));
        } else {
            $("#total_profit").val(0);
            $("#total_loss").val(0);
        }
    }

    // On service cost input change
    $(document).on("input", ".service_cost", calculateTotals);

    // On purchase price input change
    $("#purchsae_price").on("input", calculateTotals);

    // Add more service row
    $("#add_pax").click(function() {
        let newRow = `
        <tr class="service-item">
            <td>
                <input type="text" name="service_name[]" class="form-control form-control-sm service_name" placeholder="Service name">
            </td>
            <td>
                <input type="number" name="service_cost[]" class="form-control form-control-sm service_cost text-right" placeholder="0.00">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove_pax">
                    <i class="fa fa-times"></i>
                </button>
            </td>
        </tr>`;
        $("#services-wrapper").append(newRow);
    });

    // Remove service row
    $(document).on("click", ".remove_pax", function() {
        $(this).closest("tr").remove();
        calculateTotals();
    });

});


$(document).ready(function() {
    $('.sale_client_section').hide();
    $('.purchase_client_section').hide();
    $('#service_type').on('change', function() {
        var serviceType = $(this).val();
        if (serviceType === 'b2c') {
            $('.sale_client_section').show();
            $('.purchase_payment_method').show();
            $('.purchase_client_section').hide();
        } else if (serviceType === 'b2b') {
            $('.purchase_client_section').show();
            $('.sale_client_section').hide();
            $('.purchase_payment_method').hide();
        } else {
            $('.sale_client_section').hide();
            $('.purchase_client_section').hide();
        }
    });
});
</script>

@endpush
