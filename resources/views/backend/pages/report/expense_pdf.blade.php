<!DOCTYPE html>
<html>

<head>
    <title>Expense Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 14px;
            color: #000;
            line-height: 1.5;
            height: 100%;
            width: 100%;
        }

        @page {
            size: 250mm 310mm;
        }

        th,
        td {
            border: 1px solid rgb(238, 238, 238);
            padding: 5px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #ffffff;
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

    <div class="div" style="text-align: center">
        <h1 style="color: blue;padding:2px;margin:0"><i>{{ setting('app_name') }}</i></h1>
        <h4 style="padding:2px;margin:0"><i>Expense Reports</i></h4>
        <h5 style="padding:2px;margin:0">Date Range: {{ $start_date }} to {{ $end_date }}</h5>
        <p style="padding: 0px">Total Expense: {{ currencyBD($expense_amount) }}/=</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Account</th>
                <th>Amount</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_date }}
                    <td>{{ $expense->category->name ?? 'Unknow Category' }}</td>
                    <td>{{ $expense->fromAccount->account_name ?? 'N/A' }} - {{ $expense->fromAccount->account_number??'N/A' }}</td>
                    <td>{{ currencyBD($expense->expense_amount) }}/=</td>
                    <td>{{ $expense->note }}</td>
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
