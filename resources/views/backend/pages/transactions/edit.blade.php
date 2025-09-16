@extends('backend.layout.app')
@push('style')
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
        <div class="col-md-6 m-auto">
            <div class="card">
                <div class="card-header">
                    @if ($transaction->transaction_type === 'transfer')
                        {{ $transaction->fromAccount->account_name ?? 'N/A' }}
                        <i class="fas fa-arrow-right mx-1"></i>
                        {{ $transaction->toAccount->account_name ?? 'N/A' }}
                    @else
                        {{ $transaction->account->account_name ?? 'N/A' }}
                    @endif
                    <a href="{{ route('admin.accounts.transaction.index', $type) }}"
                        class="btn btn-dark btn-sm float-right"><i class="fa fa-plus" aria-hidden="true"></i> All
                        {{ ucfirst($type) }}</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accounts.transaction.update', [$type, $transaction->id]) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="amount">{{ ucfirst($type) }} Amount</label>
                                    <input type="number" name="amount" id="amount"
                                        value="{{ old('amount', $transaction->amount) }}"
                                        class="form-control  @error('amount')is-invalid @enderror"
                                        placeholder="{{ ucfirst($type) }} Amount" aria-describedby="helpId">
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-secondary btn-sm text-center"> <i class="fas fa-sync    "></i> Edit and
                                Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateAccountNumber(event) {
            const selectedOption = event.target.selectedOptions[0];
            const accountNumber = selectedOption.getAttribute('data-number');
            const accountNumberInput = document.getElementById('account_number');
            accountNumberInput.value = accountNumber || '';
        }
    </script>
@endpush
