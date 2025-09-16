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
            <span style="font-size: 20px ">Group Ticket Sales/Purchase</span>
            <a href="{{ route('admin.inventory.groupticket.create') }}" class="btn btn-secondary btn-sm float-right"><i
                    class="fa fa-plus" aria-hidden="true"></i> Group Ticket
                Sale</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ticket_pnr">Ticket PNR</label>
                        <input type="text" placeholder="Ticekt PNR" name="ticket_pnr"
                            class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="invoice_no">Invoice No</label>
                        <input type="text" placeholder="Invoice No" name="invoice_no"
                            class="form-control form-control-sm">
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sale_date">Sale Date</label>
                        <input type="date" class="form-control form-control-sm datepicker" id="sale_date"
                            name="sale_date" placeholder="dd//mm//yy">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="departing_date">Departing Date</label>
                        <input type="date" class="form-control form-control-sm" id="departing_date"
                            name="departing_date" placeholder="dd//mm//yy">
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
                                    Sale Customer
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
                url: @json(route('admin.inventory.groupticket.index')),
                data: function(d) {
                    d.ticket_pnr = $('input[name="ticket_pnr"]').val();
                    d.invoice_no = $('input[name="invoice_no"]').val();
                    d.purchase_vendor_id = $('#purchase_vendor_id').val();
                    d.purchase_due = $('#purchase_due').val();
                    d.sale_due = $('#sale_due').val();
                    d.sale_customer_id = $('#sale_customer_id').val();
                    d.sale_date = $('#sale_date').val();
                    d.departing_date = $('#departing_date').val();
                    d.ticket_type = $('#ticket_type').val();
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
                    data: 'sale_data',
                    name: 'sale_data'
                },
                {
                    data: 'loss_profit',
                    name: 'loss_profit'
                },
                {
                    data: 'customer_data',
                    name: 'customer_data'
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

        $('#purchase_vendor_id,#ticket_type, #purchase_due, #sale_due, #sale_customer_id,#sale_date,#departing_date').on('change',
            function() {
                datatable.draw();
            });
    </script>
@endpush
