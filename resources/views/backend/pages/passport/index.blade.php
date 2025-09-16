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
            <span style="font-size: 20px ">Passport Sales</span>
            <a href="{{ route('admin.inventory.passport.create') }}" class="btn btn-secondary btn-sm float-right"><i class="fa fa-plus"
                    aria-hidden="true"></i> Passport
                Sale</a>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tracking_id">Passport ID</label>
                        <input type="text" placeholder="Passport ID" name="tracking_id"
                            class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="invoice_no">Invoice No</label>
                        <input type="text" placeholder="Invoice No" name="invoice_no"
                            class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="passport_type">Passport Type</label>
                        <select name="passport_type" class="form-control form-control-sm select2" id="passport_type">
                            <option value="">All</option>
                            <option value="new_passport">New Passport</option>
                            <option value="reissue_passport">ReIssue Passport</option>
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
                            name="sale_date" placeholder="Y-m-d">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="sale_date">Delivery Date</label>
                        <input type="date" class="form-control form-control-sm" id="delivery_date"
                            name="delivery_date" placeholder="Y-m-d">
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
                                    Sale By
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Passport ID
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Passport Type
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
                url: @json(route('admin.inventory.passport.index')),
                data: function(d) {
                    d.tracking_id = $('input[name="tracking_id"]').val();
                    d.invoice_no = $('input[name="invoice_no"]').val();
                    d.passport_type = $('#passport_type').val();
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
                    data: 'sale_by',
                    name: 'sale_by'
                },
                {
                    data: 'tracking_id',
                    name: 'tracking_id'
                },

                {
                    data: 'passport_type',
                    name: 'passport_type'
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


        $('input[name="tracking_id"],input[name="invoice_no"]').on('input', function() {
            datatable.draw();
        });

        $('#passport_type, #delivery_date,#sale_date, #sale_customer_id').on('change', function() {
            datatable.draw();
        });
    </script>
@endpush
