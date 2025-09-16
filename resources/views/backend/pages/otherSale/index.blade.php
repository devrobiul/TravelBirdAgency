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
            <span style="font-size: 20px ">Other Bills</span>
            <a href="{{ route('admin.inventory.other.create') }}" class="btn btn-secondary btn-sm float-right"><i
                    class="fa fa-plus"></i> Create Bill
            </a>

        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="invoice_no">Bill No</label>
                        <input type="text" placeholder="Invoice No" name="invoice_no"
                            class="form-control form-control-sm">
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
                        <label for="sale_date">Sale Date</label>
                        <input type="date" class="form-control form-control-sm" id="sale_date" name="sale_date"
                            placeholder="Y-m-d">
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table id="example" class="table table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Sale Date</th>
                            <th>Bill No</th>
                            <th>Client/Customer</th>
                            <th>Bill Amount</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customBills as $bill)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$bill->sale_date}}</td>
                                <td>{{$bill->invoice_no}}</td>
                                <td>
                             
                                    <a href="{{ route('admin.customer.details',$bill->sales->customer->slug) }}">{{$bill->sales->customer->name??'N/A'}} -- {{$bill->sales->customer->phone??'N/A'}}</a></td>
                                <td>{{currencyBD($bill->sales->sale_price)}}/-</td>
                                <td>{{$bill->user->name??'N/A'}}</td>
                                 <td>        <a href="{{ route('admin.billGeneratePDf',$bill->id) }}" class="btn btn-dark btn-sm"> <i  class="bi bi-download"> </i> </a> <a href="{{ route('admin.inventory.other.edit',$bill->id) }}" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                                   <form
                                                        action="{{ route('admin.inventory.other.destroy',$bill->id) }}"
                                                        method="POST" onsubmit="return confirmDelete(event);"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
   <script>
        $('#example').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
            columnDefs: [{
                orderable: false,
                targets: [0, 5]
            }],
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var table = $('#example').DataTable();
                var pageInfo = table.page.info();
                var index = iDisplayIndexFull + 1;
                $('td:eq(0)', nRow).html(index);
            }
        });
    </script>
@endpush
