@extends('backend.layout.app')

@section('content')
<div class="row">
    <div class="col-md-6 m-auto">
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <strong>Profit & Loss Report</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.report.profitloss') }}" method="get">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date">Start Date</label>
                            <input name="start_date" id="start_date" type="date" max="{{ date('Y-m-d') }}"
                                   class="form-control" value="{{ old('start_date', $start_date ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date">End Date</label>
                            <input name="end_date" id="end_date" type="date" max="{{ date('Y-m-d') }}"
                                   class="form-control" value="{{ old('end_date', $end_date ?? date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Compact Cards --}}
@if($data)
<div class="row mt-3 g-2 d-flex justify-content-center">
    @foreach($data as $type => $row)
        @if($row['sale'] > 0)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card shadow-sm small-card text-center p-2 border-primary">
                <div class="card-body p-1">
                    <h6 class="card-title mb-1" style="font-size:0.75rem; font-weight:600;">
                        {{ ucfirst(str_replace('_',' ', $type)) }}
                    </h6>
                    <div style="font-size:0.7rem;">
                        <span>Sale: <strong>{{ currencyBD($row['sale']) }}</strong></span><br>
                        <span class="text-info">Purchase: <strong>{{ currencyBD($row['purchase']) }}</strong></span><br>
                        <span class="text-success">Profit: <strong>{{ currencyBD($row['profit']) }}</strong></span><br>
                        <span class="text-danger">Loss: <strong>{{ currencyBD($row['loss']) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>

{{-- Invoice & Expense --}}
@php
    $total_sale = 0;
    $total_purchase = 0;
    $total_profit = 0;
    foreach($data as $row){
        $total_sale     += $row['sale'];
        $total_purchase += $row['purchase'];
        $total_profit   += $row['profit'];
    }
    $net_profit = $total_profit - $total_expense;
@endphp

<div class="row mt-4">
    
    <div class="col-md-6 m-auto">

 <h4 class="text-center font-weight-bold">
    {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} to {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}
</h4>

        <div class="card shadow-sm">
            <div class="card-header bg-info text-white text-center">
                <strong>Invoice Summary</strong>

            </div>
            <div class="card-body p-2">
                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                    <tr class="font-weight-bold">
                            <td>Sale</td>
                            <td class="text-end">৳ {{ currencyBD($total_sale) }}</td>
                        </tr>
                   <tr class="font-weight-bold">
                            <td>Cost</td>
                            <td class="text-end text-info">৳ {{ currencyBD($total_purchase,2) }}</td>
                        </tr>
                   <tr class="font-weight-bold">
                            <td>Gross Profit</td>
                            <td class="text-end text-success">৳ {{ currencyBD($total_profit,2) }}</td>
                        </tr>
                    <tr class="font-weight-bold">
                            <td>Expenses</td>
                            <td class="text-end text-warning">৳ {{ currencyBD($total_expense,2) }}</td>
                        </tr>
                    <tr class="font-weight-bold">
                            <td>Net Profit</td>
                            <td class="text-end text-danger">৳ {{ currencyBD($net_profit) }}</td>
                        </tr>
                       <tr class="font-weight-bold">
                            <td>Others Income</td>
                            <td class="text-end text-primary">৳ {{ currencyBD($totalExtraIncome) }}</td>
                        </tr>
                     <tr class="font-weight-bold">
                            <td>Total Profit</td>
                            <td class="text-end text-danger">৳ {{ currencyBD($net_profit+$totalExtraIncome) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white text-center">
                <strong>Expenses Details</strong>
            </div>
            <div class="card-body p-2">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->category->name ?? 'N/A' }}</td>
                            <td class="text-end">৳ {{ currencyBD($expense->expense_amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                 <tr class="font-weight-bold">
                            <td>Total</td>
                            <td class="text-end">৳ {{ currencyBD($total_expense) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Expense Details --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white text-center">
                <strong>Others Income</strong>
            </div>
            <div class="card-body p-2">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($extraIncome as $income)
                        <tr>
                            <td>{{ $income->category->name ?? 'N/A' }}</td>
                            <td class="text-end">৳ {{ currencyBD($income->income_amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>Total</td>
                            <td class="text-end">৳ {{ currencyBD($totalExtraIncome) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


@endif
@endsection
