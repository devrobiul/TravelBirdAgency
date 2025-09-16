@extends('backend.layout.app')
@push('css')
    <style>
        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {

            line-height: 30px !important;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="">({{ $customer->name }}) <span class="text-info">Total Payment:</span>
                        <span class="text-success">{{ number_format($transaction->sum('amount')) }}/=</span>
                    </span>

                    <a href="{{ route('admin.customer.details', $customer->slug) }}"
                        class="btn btn-sm btn-secondary float-right">
                        <i class="fa fa-minus"></i> Back to page</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Payment Issue</th>
                                    <th>Payment Type</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Tnx_ID</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $item->user->name ?? null }}
                                        </td>
                                        <td>
                                        
                                           {{ $item->customer->name }} - ({{ ucfirst(str_replace('_',' ',$item->payment_type)) }})
                                        </td>
                                        <td>
                                            {{ $item->fromAccount->account_name??null }} - {{ $item->fromAccount->account_number??null }}
                                        </td>
                                        <td>{{ currencyBD($item->amount) }}/=</td>
                                        <td>{{ $item->transaction_id }}</td>
                                        <td>{{ $item->transaction_date }}</td>
                                        <td>

                                            <form action="{{ route('admin.customer.transactionDelete',$item->id) }}" method="POST" style="display:inline;"
                                                onsubmit="return confirmDelete(event);">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                autoWidth: false,
                paging: true,
                ordering: true,
                searching: true,
            });
        });
    </script>
@endpush
