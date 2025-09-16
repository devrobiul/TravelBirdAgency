@extends('backend.layout.app')

@section('content')
    <div class="row text-center mb-4">
        <div class="col-md-12">
            <div class="btn-group">
                <a href="{{ route('admin.inventory.passport.index') }}" class="btn btn-sm b-0 btn-info "> <i class="fas fa-plus"></i>
                    Back
                    Passport</a>&nbsp;&nbsp;
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6 m-auto">
            <div class="card">
                <div class="card-header">
                    Sale History
                </div>
                <div class="card-body ">
                    <table class="table table-bordered" style="width:100%">
                        <tbody>
                            <tr>
                                <td width="">Sale Clients</td>
                                <td width="">{{ $passport->sales->customer->name }}</td>
                            </tr>
                            <tr>
                                <td width="">Sale BY</td>
                                <td width="">{{ $passport->user->name }}</td>
                            </tr>
                            <tr>
                                <td width="">Sale History</td>
                                <td width="">
                                    Cost Price: {{ currencyBD($passport->purchase->purchase_price) }}/= <br>
                                    Sale Price: {{ currencyBD($passport->sales->sale_price) }}/= <br>
                                    Sale profit: {{ currencyBD($passport->sales->sale_profit) }}/= <br>

                                </td>
                            </tr>
                            <tr>
                                <td width="">Passport</td>
                                <td width="">{{ strtoupper($passport->passport_type) }}</td>
                            </tr>
                            <tr>
                                <td width="">Sale Date</td>
                                <td width="">{{ $passport->sale_date }}</td>
                            </tr>
                              <tr>
                                <td width="">Sale Note</td>
                                <td width="">{{ $passport->sales->sale_note }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
