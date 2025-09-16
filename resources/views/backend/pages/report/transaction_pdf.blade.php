<!DOCTYPE html>
<html>

<head>
    <title>{{ $account->account_name }} Transaction Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 12px;
            vertical-align: top;
        }

        th {
            background-color: #f4f4f4;
        }

        .text-center {
            text-align: center;
        }

        .arrow {
            font-weight: bold;
        }

         .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 5px 0;
            background-color: #f4f4f4;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <h2>{{ setting('app_name') }}</h2>
        <h4>{{ $account->account_name }} Account Report</h4>
        <p>Date Range: {{ $start_date }} to {{ $end_date }}</p>
        <p>Total Balance: {{ currencyBD($account->current_balance) }}/=</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Type/InvoiceNo</th>
                <th style="width: 40%">Account</th>
                <th>Note</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $item->transaction_date ? \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') : 'N/A' }}
                    </td>

                    <td>{{ ucfirst($item->transaction_type ?? ($item->payment_type ?? '')) }} <br>
                        @if ($item->customer)
                            ({{ $item->customer->name ?? 'N/A' }})
                        @endif
                    </td>
                    <td>

                        {{ $item->fromAccount->account_name ?? 'N/A' }}[{{ $item->fromAccount->account_number ?? 'N/A' }}]
                        @if ($item->toAccount)
                            ->
                            {{ $item->toAccount->account_name ?? 'N/A' }}[{{ $item->toAccount->account_number ?? 'N/A' }}]
                        @endif
                        @if ($item->payment_type === 'client_payment')

                            <_ {{ $item->account_name ?? 'N/A' }}[{{ $item->transaction_number ?? 'N/A' }}]
                            
                        @endif

                        @if ($item->payment_type === 'office_payment')
                            -> {{ $item->account_name ?? 'N/A' }}[{{ $item->transaction_number ?? 'N/A' }}]
                        @endif
                    </td>

                    <td>{{ $item->note ?? '-' }}</td>
                    <td>{{ currencyBD($item->amount) }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

            <div class="footer"
            style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; font-size: 12px; background-color: #fff; padding: 10px 0;">
                     <p>Software developed by <strong>Mohammad Robiul Hossain</strong>
                <br>
                <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a>
                (<a href="tel:+8801882850027">+8801882850027</a>)
            </p>
        </div>
</body>

</html>
