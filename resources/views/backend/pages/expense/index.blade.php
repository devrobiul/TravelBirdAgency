@extends('backend.layout.app')
@push('css')
    
@endpush

@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card mt-3">
                    <div class="card-header">
                        Expense
                    </div>
                    <div class="card-body">
                        @php
                            if ($expense) {
                                $route = route('admin.expense.update', $expense->id);
                            } else {
                                $route = route('admin.expense.store');
                            }
                        @endphp
                        <form action="{{ $route }}" method="POST" id="saleForm">
                            @csrf
                            @if ($expense)
                                @method('PUT')
                            @endif
                            <div class="form-group mb-2">
                                <label for="category_id">Select Category</label>
                                <select name="category_id" id="category_id" class="form-control select2">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if ($expense) {{ $expense->category_id == $category->id ? 'selected' : '' }} @endif>
                                            {{ $category->name ?? 'Unknow Category' }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label for="category_id">Select Account</label>

                                <select name="account_id" id="account_id" class="form-control select2">
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            @if ($expense) {{ $expense->account_id == $account->id ? 'selected' : '' }} @endif>
                                            {{ $account->account_name }} || {{currencyBD($account->current_balance)}}/=</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Enter expense amount</label>
                                <input type="number" name="expense_amount" id="expense_amount" class="form-control"
                                    placeholder="Enter expense amount" autofocus
                                    value="{{ $expense->expense_amount ?? null }}">

                                @error('expense_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Enter expense date</label>
                                <input type="date" name="expense_date" class="form-control"
                                    value="{{ $expense && $expense->expense_date
                                        ? \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d')
                                        : date('Y-m-d') }}"
                                    max="{{ date('Y-m-d') }}">
                                @error('expense_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <textarea name="note" id="note" class="form-control" rows="2" placeholder="Write note (if any)">{{ $expense->note ?? null }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm float-end">
                                <i class="bi bi-check-circle-fill"></i> Submit
                            </button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="card mt-3">

                    <div class="card-body">
                        <div class="table-responsive mt-5">
                            <table class="table table-bordered datatable">
                                <thead class="table-light">
                                    <tr>

                                        <th>SL</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $ex)
                                        <tr>

                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $ex->category->name ?? 'Unknow Category' }}
                                            </td>
                                            <td>
                                                {{ $ex->expense_amount }}
                                            </td>
                                            <td>
                                                {{ $ex->expense_date }}
                                            </td>
                                            <td>
                                                {{ $ex->note }}
                                            </td>
                                            <td>
                                                <div class="">

                                                    <a href="{{ route('admin.expense.edit', $ex->id) }}"
                                                        class="btn btn-sm btn-warning me-3" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <form action="{{ route('admin.expense.destroy', $ex->id) }}"
                                                        method="POST" onsubmit="return confirmDelete(event);"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>

                                                </div>
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
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                pageLength: 10,

            });
        });
    </script>
@endpush
