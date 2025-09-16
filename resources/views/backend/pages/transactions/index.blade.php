@extends('backend.layout.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">All {{ ucfirst($type) }}</span>
            <a href="{{ route('admin.accounts.transaction.create', ['type' => $type]) }}"
                class="btn btn-secondary btn-sm float-right "><i class="fa fa-plus" aria-hidden="true"></i> Add
                {{ ucfirst($type) }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>

                            <th style="">
                                <div class="d-flex align-items-center">
                                    Sl
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">

                                    Account
                                </div>
                            </th>

                            <th style="">
                                <div class="d-flex align-items-center">
                                    Amount
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">

                                    Transaction Date
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Note
                                </div>
                            </th>
                            <th style="">
                                <div class="d-flex align-items-center">
                                    Transaction by
                                </div>
                            </th>

                            <th>
                                <div class="d-flex align-items-center">

                                    Action

                                </div>
                            </th>
                        </tr>
                    </thead>
              <tbody>
    @foreach ($transactions as $item)
        <tr>
            <td>{{ $loop->index + 1 }}</td>
        <td>
            @if ($item->transaction_type === 'transfer')
                {{ $item->fromAccount->account_name ?? 'N/A' }} 
                <i class="fas fa-arrow-right mx-1"></i> 
                {{ $item->toAccount->account_name ?? 'N/A' }}
            @else
                {{ $item->fromAccount->account_name ?? 'N/A' }}
            @endif
        </td>


            <td>{{ currencyBD($item->amount) }}</td>
            <td>{{ Carbon\Carbon::parse($item->transaction_date)->format('m/d/Y')}}</td>
            <td>{{ $item->note ?? 'N/A' }}</td>
            <td>{{ $item->user->name ?? null }}</td>
            <td>
                <!-- Edit -->
                <a href="{{ route('admin.accounts.transaction.edit', ['type' => $type, $item->id]) }}"
                   class="btn btn-sm btn-info" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>

    
                <form action="{{ route('admin.accounts.transaction.destroy', ['type' => $type, $item->id]) }}"
                      method="POST" style="display:inline;" onsubmit="return confirmDelete(event);">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </form>
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">


    <script>
       $('#example').DataTable({
    paging: true,
    searching: true,
    ordering: true,
    info: true,
    responsive: true,
    columnDefs: [
        { orderable: false, targets: [0, 5] }
    ],
    fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        var table = $('#example').DataTable();
        var pageInfo = table.page.info();
        var index = iDisplayIndexFull + 1;
        $('td:eq(0)', nRow).html(index);
    }
});

    </script>
@endpush
