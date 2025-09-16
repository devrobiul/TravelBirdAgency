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
            <span style="font-size: 20px ">Man Power Sales</span>
            <a href="{{ route('admin.inventory.manpower.create') }}" class="btn btn-secondary btn-sm float-right"><i
                    class="fa fa-plus" aria-hidden="true"></i> Manpower
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
                        <label for="visit_country">Manpower Country</label>
                        <select name="visit_country" class="form-control form-control-sm select2" id="visit_country">
                            <option value="">All</option>
                            @foreach ($country as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tracking_id">Passport No</label>
                        <input type="text" placeholder="Passport No" name="tracking_id"
                            class="form-control form-control-sm">
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
                        <input type="date" class="form-control form-control-sm " id="sale_date"
                            name="sale_date" placeholder="dd//mm//yy">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="delivery_date">Submitted Date</label>
                        <input type="date" class="form-control form-control-sm" id="delivery_date"
                            name="delivery_date" placeholder="dd//mm//yy">
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
                                    Manpower
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
                url: @json(route('admin.inventory.manpower.index')),
                data: function(d) {
                    d.invoice_no = $('input[name="invoice_no"]').val();
                    d.tracking_id = $('input[name="tracking_id"]').val();
                    d.visit_country = $('#visit_country').val();
                    d.sale_customer_id = $('#sale_customer_id').val();
                    d.sale_date = $('#sale_date').val();
                    d.delivery_date = $('#delivery_date').val();
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
                    data: 'manpower',
                    name: 'manpower'
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

        $('input[name="invoice_no"],input[name="tracking_id"]').on('input', function() {
            datatable.draw();
        });

        $('#visa_id, #visit_country, #sale_customer_id,#sale_date,#delivery_date').on('change',
            function() {
                datatable.draw();
            });
    </script>
@endpush
