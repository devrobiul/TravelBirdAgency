@extends('backend.layout.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">{{ $data->account_name }} transaction ||Current balance: <span
                    class="text-success">{{ currencyBD($data->current_balance) }}/-</span></span>
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary float-right"> <i class="fa fa-arrow-left"></i> Go
                Back</a>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-bordered text-left">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Author</th>
                            <th>Transaction type</th>
                            <th>Note</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $transactions = $data->transactionsOut
                                ->merge($data->transactionsIn)
                                ->sortBy('transaction_date');
                        @endphp

                        @foreach ($transactions as $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item->transaction_date ?? 'N/A' }}</td>
                                <td>
                                    {{ $item->fromAccount->account_name ?? 'N/A' }} -
                                    {{ $item->fromAccount->account_number ?? 'N/A' }}
                                    @if ($item->toAccount)
                                        <i class="bi bi-arrow-right"></i> {{ $item->toAccount->account_name ?? 'N/A' }} -
                                        {{ $item->toAccount->account_number ?? 'N/A' }}
                                    @endif
                                    @if ($item->customer && $item->payment_type == 'client_payment')
                                        <i class="bi bi-arrow-left"></i> {{ $item->account_name ?? 'N/A' }} -
                                        {{ $item->transaction_number ?? 'N/A' }}
                                    @endif
                                    @if ($item->customer && $item->payment_type == 'office_payment')
                                        <i class="bi bi-arrow-right"></i> {{ $item->account_name ?? 'N/A' }} -
                                        {{ $item->transaction_number ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>{{ $item->user->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($item->transaction_type ?? '') }}</td>
                                <td>{{ $item->note ?? 'N/A' }}</td>
                                <td>{{ currencyBD($item->amount) }}/=</td>
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
            order: [
                [0, 'asc']
            ]
        });
    </script>
@endpush
