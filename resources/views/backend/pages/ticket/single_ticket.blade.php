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
            <span style="font-size: 20px ">Ticket Sales/Purchase</span>
            <a href="{{ route('admin.inventory.singleticket.create') }}" class="btn btn-secondary btn-sm float-right"><i
                    class="fa fa-plus"></i> Sale Ticket
                </a>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="ticket_pnr">Ticket PNR</label>
                        <input type="text" placeholder="Ticekt PNR" name="ticket_pnr"
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
                        <label for="purchase_vendor_id">Select Vendor</label>
                        <select name="purchase_vendor_id" class="form-control form-control-sm select2"
                            id="purchase_vendor_id">
                            <option value="">All</option>
                            <option value="my_self">My Self</option>
                            @foreach ($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="sale_customer_id">Select Customer</label>
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
                        <label for="ticket_type">Ticekt Type</label>
                        <select name="ticket_type" class="form-control form-control-sm select2" id="ticket_type">
                            <option value="">All Ticket</option>
                            <option value="issue_ticket">Issue</option>
                            <option value="re_issue_ticket">Re-Issue</option>
                            <option value="return_adjust">Return Adjust</option>
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
                        <label for="depart_date">Depart Date</label>
                        <input type="date" class="form-control form-control-sm" id="depart_date"
                            name="depart_date" placeholder="Y-m-d">
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
                                    Ticket PNR
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Travel status
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Purchase vendor
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Sale vendor
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
                url: @json(route('admin.inventory.singleticket.index')),
                data: function(d) {
                    d.ticket_pnr = $('input[name="ticket_pnr"]').val();
                    d.invoice_no = $('input[name="invoice_no"]').val();
                    d.purchase_vendor_id = $('#purchase_vendor_id').val();
                    d.purchase_due = $('#purchase_due').val();
                    d.sale_due = $('#sale_due').val();
                    d.sale_customer_id = $('#sale_customer_id').val();
                    d.sale_date = $('#sale_date').val();
                    d.ticket_type = $('#ticket_type').val();
                    d.depart_date = $('#depart_date').val();
                }
            },
            pageLength: 10,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ticket_pnr',
                    name: 'ticket_pnr'
                },
                {
                    data: 'travel_status',
                    name: 'travel_status'
                },
                {
                    data: 'purchase_vendor',
                    name: 'purchase_vendor'
                },
                {
                    data: 'customer_data',
                    name: 'customer_data'
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


        $('input[name="ticket_pnr"],input[name="invoice_no"]').on('input', function() {
            datatable.draw();
        });

        $('#purchase_vendor_id,#depart_date,#ticket_type, #purchase_due, #sale_due, #sale_customer_id,#sale_date').on('change',
            function() {
                datatable.draw();
            });
    </script>
@endpush
