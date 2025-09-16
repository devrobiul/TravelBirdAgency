@extends('backend.layout.app')

@push('style')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">Visa Sales</span>
            <a href="{{ route('admin.inventory.visasale.create') }}" class="btn btn-secondary btn-sm float-right"><i class="fa fa-plus"
                    aria-hidden="true"></i> Visa Sale
                Sale</a>

        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="invoice_no">Invoice No</label>
                        <input type="text" placeholder="Invoice No" name="invoice_no"
                            class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="visa_id">Visa name</label>
                        <select name="visa_id" class="form-control form-control-sm select2" id="visa_id">
                            <option value="">All</option>
                            @foreach ($visa as $item)
                                <option value="{{ $item->id }}">{{ $item->visa_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="visa_type">Visa type</label>
                        <select name="visa_type" class="form-control form-control-sm select2" id="visa_type">
                            <option value="">All</option>
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="sale_customer_id">Sales Customers</label>
                        <select name="sale_customer_id" class="form-control form-control-sm select2" id="sale_customer_id">
                            <option value="">All</option>
                            @foreach ($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        <label for="sale_date">Sale Date</label>
                        <input type="date" class="form-control form-control-sm" id="sale_date"
                            name="sale_date" placeholder="dd//mm//yy">
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table id="example" class="table table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    SL
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Visa
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Purchase Vendor
                                </div>
                            </th>


                            <th style="">
                                <div class="d-flex align-items-center">
                                    Sale Customer
                                </div>
                            </th>


                            <th style="">
                                <div class="d-flex align-items-center">
                                    Profit/Loss
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Action
                                </div>
                            </th>



                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let datatable = $('#example').DataTable({
            "autoWidth": false,
            processing: true,
            serverSide: true,
            ajax: {
                url: @json(route('admin.inventory.visasale.index')),
                data: function(d) {
                    d.invoice_no = $('input[name="invoice_no"]').val();
                    d.visa_type = $('#visa_type').val();
                    d.visa_id = $('#visa_id').val();
                    d.sale_customer_id = $('#sale_customer_id').val();
                    d.sale_date = $('#sale_date').val();
                }
            },

            pageLength: 25,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'visa_type',
                    name: 'visa_type'
                },
                {
                    data: 'purchase_vendor',
                    name: 'purchase_vendor'
                },

                {
                    data: 'sale_data',
                    name: 'sale_data'
                },


                {
                    data: 'loss_profit',
                    name: 'loss_profit'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });

        $('input[name="invoice_no"]').on('input', function() {
            datatable.draw();
        });

        $('#visa_id, #visa_type, #sale_customer_id,#sale_date').on('change',
            function() {
                datatable.draw();
            });
    </script>
@endpush
