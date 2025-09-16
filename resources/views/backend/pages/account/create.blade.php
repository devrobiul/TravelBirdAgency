@php
    $route = $account ? route('admin.accounts.update', $account->id) : route('admin.accounts.store');
@endphp

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Account</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <form action="{{ $route }}" method="post" class="customersubmit" enctype="multipart/form-data">
            @csrf
            @if ($account)
                @method('PUT')
            @endif
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="account_name">Account Type</label>
                            <select name="account_type" id="account_type" class="form-control select2">
                                <option value="">Select type</option>
                                <option value="Bank"
                                    {{ old('account_type', $account->account_type ?? '') == 'Bank' ? 'selected' : '' }}>
                                    Bank</option>
                                <option value="Login_Wallet"
                                    {{ old('account_type', $account->account_type ?? '') == 'Login_Wallet' ? 'selected' : '' }}>
                                    Login Wallet</option>
                            </select>

                            <span class="text-danger error-text account_type_error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="account_name">Account Name</label>
                            <input type="text" name="account_name" id="account_name"
                                value="{{ old('account_name', $account->account_name ?? '') }}" class="form-control "
                                placeholder="Account name">
                            <span class="text-danger error-text account_name_error"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input type="text" name="account_number" id="account_number"
                                value="{{ old('account_number', $account->account_number ?? '') }}"
                                class="form-control " placeholder="Account number">
                            <span class="text-danger error-text account_number_error"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="branch_name">Account Branch</label>
                            <input type="text" name="branch_name" id="branch_name"
                                value="{{ old('branch_name', $account->branch_name ?? '') }}" class="form-control "
                                placeholder="Branch name">
                            <span class="text-danger error-text branch_name_error"></span>
                        </div>
                    </div>
                    @if (!$account)
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="branch_name">Opening Balance</label>
                                <input type="number" name="opening_balance" id="opening_balance"
                                    value="{{ old('opening_balance', $account->opening_balance ?? '0') }}"
                                    class="form-control" placeholder="Opening balance">
                                <span class="text-danger error-text opening_balance"></span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 10px !important;
    }

    label {
        margin-bottom: 0px !important;
    }
</style>
<script src="{{ asset('backend/assets/js/ajax.js') }}"></script>
