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
            <span style="font-size: 20px;">Refund Ticket</span>
            <form class="float-right ticketSearch" action="{{route('admin.inventory.refundticket.ticketSearch')}}" method="GET">
                @csrf
                <div class="input-group">
                    <input type="text" name="searchpnr" class="form-control" placeholder="Search PNR" required aria-label="Search PNR">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ticket_pnr">Ticket PNR</label>
                        <input type="text" placeholder="Search PNR" name="ticket_pnr"
                            class="form-control form-control">
                    </div>
                </div>
      
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Refund Status</label>
                        <select name="status" class="form-control form-control select2" id="status">
                            <option value="">All Refund</option>
                            <option value="0">Pending</option>
                            <option value="1">Refunded</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="refund_date">Application Date</label>
                        <input type="date" class="form-control form-control" id="refund_date"
                            name="refund_date" placeholder="Y-m-d">
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
                                    Refund Date
                                </div>
                            </th>
                             <th style="">
                                <div class="d-flex align-items-center">
                                    Refund Vendor/Customer
                                </div>
                            </th>
                  
                             <th style="">
                                <div class="d-flex align-items-center">
                                    Refund
                                </div>
                 
                            <th style="">
                                <div class="d-flex align-items-center">
                                   Execpt/Status
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
                url: @json(route('admin.inventory.refundticket.index')),
                data: function(d) {
                    d.ticket_pnr = $('input[name="ticket_pnr"]').val();
                    d.refund_date = $('#refund_date').val();
                    d.status = $('#status').val();
                }
            },
        pageLength: 10,
        columns: [
            {
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
                data: 'vendor',
                name: 'vendor'
            },
      
       
             {
                data: 'refund',
                name: 'refund'
            },
         
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action'
            }
        ]
    });
    
     $('input[name="ticket_pnr"]').on('input', function() {
            datatable.draw();
        });

        $('#status,#refund_date').on('change',
            function() {
                datatable.draw();
            });
</script>

@endpush