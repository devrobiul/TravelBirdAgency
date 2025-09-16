@extends('backend.layout.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">{{ ucfirst(str_replace('_',' ',$type)) }}</span>
  
            <a data-url="{{ route('admin.customer.create')}}"
                class="btn btn-secondary btn-sm float-right show-modal">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New
            </a>
                  <a href="{{ route('admin.customer.dueCustomerList') }}"
                class="btn btn-dark btn-sm float-right mr-3">
                 <i class="bi bi-download"></i> Download
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($data as $item)
                           <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->address }}</td>
                                <td>
                                    @if(isset($item->balance))
                                        @if ($item->balance < 0)
                                            GotripDue = <strong class="text-success">{{ currencyBD($item->balance) }}</strong>/=
                                        @elseif ($item->balance > 0)
                                            CustomerDue = <strong class="text-danger">{{ currencyBD($item->balance) }}</strong>/=
                                        @else
                                            <strong class="text-dark">{{ currencyBD(0) }}</strong>/=
                                        @endif
                                    @else
                                        <strong class="text-dark">{{ currencyBD(0) }}</strong>/=
                                    @endif
                                </td>
                                <td>
                                    <button data-url="{{ route('admin.customer.edit',$item->id) }}" class="btn btn-info btn-sm show-modal"><i class="bi bi-pencil"></i> Edit customer</button>
                                    <a href="{{ route('admin.customer.details',$item->slug) }}" class="btn btn-secondary btn-sm "><i class="bi bi-eye"></i> Customer Dashboard</a>
                                </td>
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
