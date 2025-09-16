<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error')</title>

    <!-- Styles -->
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(to bottom right, #e0f7fa, #ffffff);
            color: #374151;
        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            flex-direction: column;
            padding: 20px;
        }

        .error-code {
            font-size: 96px;
            font-weight: 700;
            color: #0288d1;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .error-description {
            font-size: 16px;
            margin-bottom: 30px;
            color: #6b7280;
        }

        .btn {
            display: inline-block;
            margin: 5px;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #0288d1;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0277bd;
        }

        .btn-secondary {
            background-color: white;
            color: #0288d1;
            border: 2px solid #0288d1;
        }

        .btn-secondary:hover {
            background-color: #e1f5fe;
        }

        .illustration {
            width: 150px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @media (max-width: 640px) {
            .error-code { font-size: 72px; }
            .error-message { font-size: 20px; }
            .illustration { width: 120px; }
        }
    </style>
</head>
<body>
    <div class="flex-center">
        @yield('content')
    </div>
</body>
</html>
