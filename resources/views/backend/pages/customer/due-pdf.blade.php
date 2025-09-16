<!DOCTYPE html>
<html>

<head>
    <title>Due Customer list || {{ setting('app_name') }}</title>
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
    <div class="div" style="text-align: center">
        <h1 style="color: blue;padding:2px;margin:0"><i>{{ setting('app_name') }}</i></h1>
        <h4 style="padding:2px;margin:0"><i>Due Customer List</i></h4>

    </div>


    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Address</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($due_customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->name }} - {{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>

                        @if ($customer->balance > 0)
                            {{ currencyBD($customer->balance) }}/=
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer"
        style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; font-size: 12px; background-color: #fff; padding: 0;">
        <p>

            Software developed by <strong>Mohammad Robiul Hossain</strong>
            <br>
            <a href="https://www.raidaitbd.com" target="_blank">www.raidaitbd.com</a>
            (<a href="tel:+8801882850027">+8801882850027</a>)
        </p>
    </div>
    <div class="page-break"></div>
</body>

</html>
