
@extends('backend.layout.app')

@push('css')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="text-center mt-2">
                <a href="{{ route('admin.inventory.groupticket.index') }}" class="btn btn-sm btn-secondary"><i class="fa fa-minus" aria-hidden="true"></i> Go Back</a>
            </div>
                    <div class="card-body">
            <div style="width:100%;">
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Airline</th>
                                <th>Travel Status</th>
                                <th>Travel Date</th>
                                <th>Travel Airport</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $product->airline->IATA }}-{{ $product->airline->Airline }}</td>
                                <td>{{ strtoupper($product->travel_status) }}</td>
                                <td>Depart: {{ $product->depart_date }}
                                    @if ($product->travel_status == 'roundtrip' || $product->travel_status == 'multicity')
                                        <br>Return: {{ $product->return_date }}
                                    @endif
                                </td>
                                <td>{{ $product->journey_from }} TO {{ $product->journey_to }}
                                    @if ($product->travel_status == 'multicity')
                                        <br>{{ $product->multicity_from }} TO {{ $product->multicity_to }}
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            @if ($product->group_ticket_sales)
                <div style="width:100%">
                    <div>
                        <table class="table">
                            <thead>
                                <td>CLIENT NAME</td>
                                <td>PAX NAME</td>
                                <td>PAX MOBILE</td>
                                <td>PAX TYPE</td>
                                <td>Sale Price</td>
                            </thead>
                            <tbody>
                                @foreach ($product->group_ticket_sales as $item)
                                    <tr>
                                        <td>{{ $item->customer->name }}</td>
                                        <td>{{ $item->pax_name }}</td>
                                        <td>{{ $item->pax_mobile_no }}</td>
                                        <td>{{ $item->pax_type }}</td>
                                        <td>{{ currencyBD($item->sale_price) }}/=</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @endif

            <div style="width:95%;">
                <div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Ticket Status</td>
                                <td style="font-weight: bold">{{ ucfirst(strtolower($product->ticket_type)) }}</td>

                                <td>Purchase Price</td>
                                <td style="font-weight: bold">
                                    {{ currencyBD($product->purchase->purchase_price) }}/=
                                </td>
                                <td>Total Price</td>
                                <td style="font-weight: bold">
                                    {{ currencyBD($product->group_ticket_sales->sum('sale_price')) }}/=
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection

@push('scripts')
  
@endpush
