@extends('backend.layout.app')

@section('content')
<div class="row">
    <div class="col-md-4 m-auto">
        <div class="card">
            <div class="card-header">
                Expense Report
            </div>
            <div class="card-body">
                <form action="{{ route('admin.report.expenseReportPdf') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="start_date">Start Date</label>
                            <div class="input-group">
                                <span
                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input name="start_date" id="start_date" type="date" max="{{ date('Y-m-d') }}"
                                    class="datepicker form-control" placeholder="Start Date" />
                            </div>
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="end_date">End Date</label>
                            <div class="input-group">
                                <span
                                    class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input name="end_date" id="end_date" type="date"
                                    class="datepicker form-control" max="{{ date('Y-m-d') }}"
                                    value="{{ old('end_date', date('Y-m-d')) }}" placeholder="End Date" />
                            </div>
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mx-sm-2">
                                <button type="submit" class="btn btn-secondary w-100"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
