@extends('backend.layout.app')

@section('content')
<div class="row">
    <div class="col-md-10 m-auto">
        <div class="card shadow-sm">
            <div class="card-header">
                <strong>Accounts Report</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.report.transactionReport') }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    
                    <!-- Start Date -->
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <div class="input-group">
              
                            <input type="date" name="start_date" id="start_date" 
                                   max="{{ date('Y-m-d') }}"
                                   class="form-control" 
                                   value="{{ old('start_date') }}">
                        </div>
                        @error('start_date')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Date -->
                      <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <div class="input-group">
                 
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control" 
                                   max="{{ date('Y-m-d') }}" 
                                   value="{{ old('end_date', date('Y-m-d')) }}">
                        </div>
                        @error('end_date')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Account -->
                  <div class="col-md-3 mb-3">
                        <label for="from_account_id" class="form-label">Select Account</label>
                        <select name="from_account_id" id="from_account_id" class="form-control select2 w-100" style="width: 100%">
                            <option value="">Select Account</option>
                            @foreach ($accounts as $item)
                                <option value="{{ $item->id }}">{{ $item->account_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
