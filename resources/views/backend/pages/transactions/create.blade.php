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
                    <span style="font-size: 20px ">{{ ucfirst($type) }}</span>
                    <a href="{{ route('admin.accounts.transaction.index', $type) }}"
                        class="btn btn-dark btn-sm float-right"><i class="fa fa-plus" aria-hidden="true"></i> All
                        {{ ucfirst($type) }}</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accounts.transaction.store', $type) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @if (in_array($type, ['deposit', 'withdraw']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_id">Account Name</label>
                                        <select name="account_id" id="account_id" class="select2 form-control " required
                                            style="width: 100%" data-placeholder="Select Account"
                                            onchange="updateAccountNumber(event)">
                                            <option value="">Select Method</option>
                                            @foreach ($accounts as $item)
                                                <option value="{{ $item->id }}"
                                                    data-number="{{ $item->account_number }}">
                                                    {{ $item->account_name }} || {{ currencyBD($item->current_balance) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_number">Account Number</label>
                                        <input type="number" id="account_number" value="" class="form-control"
                                            readonly placeholder="Account Number">
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="from_account_id">FROM AC/N</label>
                                        <select name="from_account_id" id="from_account_id"
                                            class="form-control form-control-sm select2">
                                            <option value="">-- Select From Account --</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->account_name }} (Balance:
                                                    {{ currencyBD($account->current_balance) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-message" id="error-from_account_id"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="to_account_id">TO AC/N</label>
                                        <select name="to_account_id" id="to_account_id"
                                            class="form-control form-control-sm select2">
                                            <option value="">-- Select To Account --</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->account_name }} (Balance:
                                                    {{ currencyBD($account->current_balance) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-message" id="error-to_account_id"></span>
                                    </div>
                                </div>
                            @endif


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">{{ ucfirst($type) }} Amount</label>
                                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                                        class="form-control  @error('amount')is-invalid @enderror"
                                        placeholder="{{ ucfirst($type) }} Amount" aria-describedby="helpId">
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                                 @if (in_array($type, ['deposit', 'withdraw']))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_id">Transaction id</label>
                                    <input type="text" name="transaction_id" id="transaction_id"
                                        value="{{ old('transaction_id') }}" class="form-control " placeholder="TNX ID"
                                        aria-describedby="helpId">

                                    @error('transaction_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_date">Transaction date</label>
                                    <input type="date" name="transaction_date" id=""
                                        value="{{ old('transaction_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                        class="form-control " placeholder="Deposit Amount" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">{{ ucfirst($type) }} note</label>
                                    <input type="text" name="note" id="note" class="form-control  "
                                        placeholder="{{ ucfirst($type) }} note" aria-describedby="helpId">
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-secondary btn-sm  text-center"><i class="fas fa-save"></i>
                                Submit</button>
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

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#from_account_id').on('change', function() {
                let selectedFromAccount = $(this).val();
                let toAccountOptions = $('#to_account_id').find('option');
                toAccountOptions.prop('disabled', false);
                toAccountOptions.each(function() {
                    if ($(this).val() === selectedFromAccount) {
                        $(this).prop('disabled', true);
                    }
                });
            });
        });
    </script>
@endpush
