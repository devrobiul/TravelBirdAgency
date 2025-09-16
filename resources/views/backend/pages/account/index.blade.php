@extends('backend.layout.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">All Account</span>
            <a data-url="{{ route('admin.accounts.create') }}" class="btn btn-sm btn-secondary float-right show-modal"><i
                    class="fa fa-plus" aria-hidden="true"></i> Add
                Account</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-bordered table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Account Type</th>
                            <th>Account Name</th>
                            <th>Account number</th>
                            <th>Account Branch</th>
                            <th>Account Balance </th>
                            <th>Action</th>
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
            ajax: @json(route('admin.accounts.index')),
            pageLength: 10,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'account_type',
                    name: 'account_type',
                },
                {
                    data: 'account_name',
                    name: 'account_name',
                },

                {
                    data: 'account_number',
                    name: 'account_number',
                },
                {
                    data: 'branch_name',
                    name: 'branch_name',
                },
                {
                    data: 'current_balance',
                    name: 'current_balance',
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    </script>
@endpush
