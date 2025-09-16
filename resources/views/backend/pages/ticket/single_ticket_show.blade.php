@extends('backend.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Top Buttons --}}
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="btn-group">
                <a href="{{ route('admin.inventory.singleticket.index') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-arrow-left"></i> Back to Tickets
                </a>
                <a class="btn btn-sm btn-dark" href="{{ route('admin.singleTicketPdf',$ticket->id) }}">
                    <i class="fas fa-file-download"></i> Download PDF
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- Purchase History --}}
        <div class="col-md-4 col-12 mb-3">
            <div class="card shadow rounded">
                <div class="card-header font-weight-bold">
                    <i class="fas fa-shopping-cart"></i> Purchase History
                </div>
                <div class="card-body p-3">
                    <table class="table  table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>Vendor</td>
                                <td>
                                    @if($ticket->purchase->purchase_vendor_id == 0)
                                        <span class="">{{ setting('app_name') }} (My Self)</span>
                                    @else
                                        <span class="">{{ $ticket->purchase->vendor->name ?? 'N/A' }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Account</td>
                                <td>{{ $ticket->purchase->account->account_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td><strong>{{ number_format($ticket->purchase->purchase_price ?? 0) }}/=</strong></td>
                            </tr>
                            <tr>
                                <td>Issue Date</td>
                                <td><i class="fas fa-calendar-alt"></i> {{ $ticket->issue_date }}</td>
                            </tr>
                            <tr>
                                <td>Note</td>
                                <td>{{ $ticket->purchase->purchase_note ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sale History --}}
        <div class="col-md-4 col-12 mb-3">
            <div class="card shadow rounded">
                <div class="card-header font-weight-bold">
                    <i class="fas fa-dollar-sign"></i> Sale History
                </div>
                <div class="card-body p-3">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>Customer</td>
                                <td>{{ $ticket->sales->customer->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td><strong>{{ currencyBD($ticket->sales->sale_price ?? 0) }}/=</strong></td>
                            </tr>
                            <tr>
                                <td>Profit / Loss</td>
                                <td>
                                    @if($ticket->sales->sale_profit)
                                        <span class="badge badge-success">Profit: {{ currencyBD($ticket->sales->sale_profit) }}/=</span>
                                    @elseif($ticket->sales->sale_loss)
                                        <span class="badge badge-danger">Loss: {{ currencyBD($ticket->sales->sale_loss) }}/=</span>
                                    @else
                                        <span class="badge badge-secondary">Neither loss nor profit</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td><i class="fas fa-calendar-alt"></i> {{ $ticket->sales->sale_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Note</td>
                                <td>{{ $ticket->sales->sale_note ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Travel Status --}}
    <div class="col-md-4 col-12 mb-3">
    <div class="card shadow rounded">
        <div class="card-header font-weight-bold">
            <i class="fas fa-plane"></i> Travel Status
        </div>
        <div class="card-body p-3">
            <table class="table table-borderless mb-0">
                <tbody>
                    <tr>
                        <td>Airline</td>
                        <td>{{ $ticket->airline->IATA ?? '-' }} - {{ $ticket->airline->Airline ?? '-' }} - {{ $ticket->airline->Country ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><span class="badge badge-info">{{ strtoupper($ticket->travel_status) }}</span></td>
                    </tr>
                    <tr>
                        <td>Dates & Times</td>
                        <td>
                            <i class="fas fa-calendar-alt"></i> Departing: {{ $ticket->depart_date }} <br>
                            <i class="fas fa-clock"></i> Depart Time: {{ $ticket->departer_time ?? '-' }} <br>
                            <i class="fas fa-clock"></i> Arrival Time: {{ $ticket->arrival_time ?? '-' }} <br>
                            @if($ticket->travel_status == 'roundtrip' || $ticket->travel_status == 'multicity')
                                <i class="fas fa-calendar-alt"></i> Returning: {{ $ticket->return_date }} <br>
                                <i class="fas fa-clock"></i> Return Depart Time: {{ $ticket->return_departer_time ?? '-' }} <br>
                                <i class="fas fa-clock"></i> Return Arrival Time: {{ $ticket->return_arrival_time ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Route</td>
                        <td>
                            {{ $ticket->journey_from }} <i class="fas fa-plane"></i> {{ $ticket->journey_to }}<br>
                            @if($ticket->travel_status == 'multicity')
                                {{ $ticket->multicity_from }} <i class="fas fa-plane"></i> {{ $ticket->multicity_to }} <br>
                                <i class="fas fa-clock"></i> Multicity Depart Time: {{ $ticket->multicity_departer_time ?? '-' }} <br>
                                <i class="fas fa-clock"></i> Multicity Arrival Time: {{ $ticket->multicity_arrival_time ?? '-' }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


        {{-- PAX Data --}}
        @if($pax_data && count($pax_data))
        <div class="col-12 mb-3">
            <div class="card shadow rounded">
                <div class="card-header font-weight-bold">
                    <i class="fas fa-users"></i> PAX Details
                </div>
                <div class="card-body p-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>PAX Name</th>
                                <th>PAX Type</th>
                                <th>PAX Mobile</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pax_data as $item)
                            <tr>
                                <td>{{ $item['name'] ?? '-' }}</td>
                                <td>{{ $item['type'] ?? '-' }}</td>
                                <td>{{ $item['mobile_no'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
