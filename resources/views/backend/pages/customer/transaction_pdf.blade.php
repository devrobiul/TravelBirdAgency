<!DOCTYPE html>
<html>

<head>
    <title>Transaction Reports || {{setting('app_name')}}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid rgb(207, 207, 207);
            padding: 5px;
            text-align: left;
            font-size: 12px;
        }

        .page-break {
            page-break-after: auto;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            opacity: 0.1;
            width: 380px;
            height: auto;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding: 20px 0;
            background-color: #f4f4f4;
            margin-top: 20px;
        }

        th {
            background-color: #ffffff00;
        }
    </style>
</head>

<body>
    <img class="watermark" src="{{ public_path('backend/assets/uploads/files/logo.png') }}" alt="Watermark Logo">
    <div class="div" style="text-align: center">
        <h1 style="color: blue;padding:2px;margin:0"><i>{{ setting('app_name') }}</i></h1>
        <h4 style="padding:2px;margin:0"><i>{{ $customer->name }} (Payment Report)</i></h4>
        <h5 style="padding:2px;margin:0">Date Range: {{ $start_date }} to {{ $end_date }}</h5>
        <p style="padding: 0px">Total Amount: <span style="color:green">{{ number_format($total_transaction) }}</span>/=
        </p>
    </div>


    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Transaction date</th>
                <th>Account Name</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Tnx_ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction as $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $item->transaction_date }}</td>
                    <td>{{ $item->fromAccount->account_name ?? null }}[<strong>{{ $item->fromAccount->account_number ?? null }}</strong>]
                    <td>{{ $item->payment_type }}</td>
                    </td>
                    <td>{{ number_format($item->amount) }}/=</td>
                    <td>{{ $item->transaction_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer"
        style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; font-size: 12px; background-color: #fff; padding: 0;">
        <p>
            Thank you for booking with us!
            For any inquiries, contact us at <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>
            <br>
            Software developed by <strong>Mohammad Robiul Hossain</strong>
            <br>
            <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a>
            (<a href="tel:+8801882850027">+8801882850027</a>)
        </p>
    </div>
    <div class="page-break"></div>
</body>

</html>
