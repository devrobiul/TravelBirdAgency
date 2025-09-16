@extends('backend.layout.app')

@push('css')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">Hotel Booking</span>
            <a href="{{ route('admin.inventory.hotel.create') }}" class="btn btn-secondary btn-sm float-right"><i class="fa fa-plus"
                    aria-hidden="true"></i> Hotel Booking
            </a>

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
                        <label for="hotel_name">Hotel name</label>
                        <input type="text" placeholder="Hotel name" name="hotel_name"
                            class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="hotel_location">Hotel location</label>
                        <input type="text" placeholder="Hotel location" name="hotel_location"
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
                        <input type="date" class="form-control form-control-sm" id="sale_date"
                            name="sale_date" placeholder="Y-m-d">
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
                                    Hotel Description
                                </div>
                            </th>

                            <th style="">
                                <div class="d-flex align-items-center">
                                    Sale Customer
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Purchase Vendor
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
                url: @json(route('admin.inventory.hotel.index')),
                data: function(d) {
                    d.hotel_name = $('input[name="hotel_name"]').val();
                    d.hotel_location = $('input[name="hotel_location"]').val();
                    d.invoice_no = $('input[name="invoice_no"]').val();
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
                    data: 'sale_by',
                    name: 'sale_by'
                },
                {
                    data: 'hotel_description',
                    name: 'hotel_description'
                },

                {
                    data: 'sale_data',
                    name: 'sale_data'
                },

                {
                    data: 'purchase_vendor',
                    name: 'purchase_vendor'
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


        $('input[name="hotel_name"],input[name="invoice_no"],input[name="hotel_location"]').on('input', function() {
            datatable.draw();
        });

        $('#sale_date, #sale_customer_id').on('change', function() {
            datatable.draw();
        });
    </script>
@endpush
