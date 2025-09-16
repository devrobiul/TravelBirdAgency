@extends('backend.layout.app')

@section('content')
    <div class="row">
        <div class="col-md-8 m-auto">
            <div class="card">
                <div class="card-header text-center bg-success text-light">
                    <h3>{{setting('app_name')}} Sale/Purchase Reports</h3>
                </div>
                <div class="card-body">
                    <form class="" action="{{ route('admin.report.saleReportPdf') }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-md-3 mb-2">

                                <div class="input-group">
                                    <span
                                        class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input name="start_date" id="start_date" type="date"
                                        class="form-control form-control-sm" placeholder="start date" />
                                </div>
                                @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-2">

                                <div class="input-group">
                                    <span
                                        class="border rounded-left d-flex justify-content-center align-items-center px-2 border-right-0 bg-light">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input name="end_date" id="end_date" type="date"
                                        class="form-control form-control-sm" required
                                        value="{{ old('end_date', date('Y-m-d')) }}" placeholder="" />
                                </div>
                                @error('end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="product_type" class="form-control form-control-sm select2"
                                        id="product_type">
                                        <option value="">All</option>
                                        <option value="single_ticket">Ticket Sale</option>
                                        <option value="ticket_refund">Refund Ticket</option>
                                        <option value="group_ticket">Group Ticket</option>
                                        <option value="passport">Passport Sale</option>
                                        <option value="visa_sale">Visa Sale</option>
                                        <option value="manpower">Man power</option>
                                        <option value="hotel_booking">Hotel Booking</option>
                                        <option value="custom_bill">Other Bill/Services</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mx-sm-2">
                                    <button type="submit" class="btn btn-secondary btn-sm">Search</button>
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
