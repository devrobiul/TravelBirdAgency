<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
   <!-- SEO Title -->
    <title>Raidait BD | বাংলাদেশি ট্রাভেল এজেন্সি একাউন্টিং সফটওয়্যার</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Raidait BD: বাংলাদেশের ট্রাভেল এজেন্সির জন্য সহজ ও দ্রুত একাউন্টিং সফটওয়্যার। বিক্রি, খরচ, লাভ-ক্ষতি এবং হিসাব পরিচালনা করুন সহজে।">
    <meta name="keywords" content="Raidait BD, ট্রাভেল এজেন্সি সফটওয়্যার, একাউন্টিং সফটওয়্যার বাংলাদেশ, ট্রাভেল একাউন্টিং, খরচ হিসাব সফটওয়্যার, বিক্রয় ও লাভ সফটওয়্যার, Travel Agency Accounting, Agency Accounting Software, Profit Loss Management">
    
    <!-- Open Graph / Facebook Meta -->
    <meta property="og:title" content="Raidait BD | ট্রাভেল এজেন্সি একাউন্টিং সফটওয়্যার">
    <meta property="og:description" content="বাংলাদেশের ট্রাভেল এজেন্সির জন্য সহজ ও দ্রুত একাউন্টিং সফটওয়্যার। খরচ, বিক্রি, লাভ-ক্ষতি ও হিসাব পরিচালনা করুন সহজে।">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://b2b.raidaitbd.com/">
    <meta property="og:image" content="https://raidaitbd.com/frontend/upload/files/1842533674168505.webp">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Raidait BD | ট্রাভেল এজেন্সি একাউন্টিং সফটওয়্যার">
    <meta name="twitter:description" content="বাংলাদেশের ট্রাভেল এজেন্সির জন্য সহজ ও দ্রুত একাউন্টিং সফটওয়্যার। খরচ, বিক্রি, লাভ-ক্ষতি ও হিসাব পরিচালনা করুন সহজে।">
    <meta name="twitter:image" content="https://raidaitbd.com/frontend/upload/files/1842533674168505.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@400;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <style>
        body {
            font-family: "Jost", sans-serif;
            background: url('{{ asset(setting("login_bg")) }}') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            backdrop-filter: blur(0px);
        }

        .login-card {
            background: rgb(255, 255, 255);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 128, 128, 0.25);
            width: 100%;
            max-width: 520px;
            text-align: center;
           
        }

        .login-card img {
            max-width: 150px;
            margin-bottom: 15px;
        }

        .login-card h2 {
            font-weight: 500;
            color: #00695c;
        }

        .login-card p {
            font-size: 15px;
            color: #666;
        }

        .form-control {
            background-color: #fdfdfd;
            border: 1px solid #ddd;
            color: #333;
            padding: 12px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #00796b;
            box-shadow: 0 0 0 0.2rem rgba(0, 121, 107, 0.25);
        }

        .form-group label {
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .btn-main {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-search {
            background-color: #00796b;
            color: #fff;
        }

        .btn-search:hover {
            background-color: #004d40;
        }

        .btn-download {
            background-color: #0288d1;
            color: #fff;
        }

        .btn-download:hover {
            background-color: #01579b;
        }

        .btn-back {
            background-color: #f1f1f1;
            color: #444;
        }

        .btn-back:hover {
            background-color: #ddd;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }

        .dev-support {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #444;
        }

        .dev-support a {
            color: #00796b;
            font-weight: 600;
            text-decoration: none;
        }

        .dev-support a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Logo -->
            <img src="{{ asset(setting('primary_logo')) }}" alt="Logo">

            <!-- Customer Info -->
            <h5>Hello, {{ $customer->name }}!</h5>
            <p>Client-side Balancesheet Searching Panel!</p>

            <!-- Date Form -->
            <form method="GET" action="{{ route('customerCheckReportview',[$customer->id,$customer->uuid]) }}">
                <div class="form-group text-left">
                    <label for="start_date"><i class="fa fa-calendar-alt"></i> Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           max="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group text-left">
                    <label for="end_date"><i class="fa fa-calendar-alt"></i> End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-d') }}" class="form-control"
                           max="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Button group -->
                <div class="button-group">
                    <button type="submit" class="btn btn-success w-100 btn-search">
                        <i class="fa fa-search"></i> Search
                    </button>
                   
                </div>
            </form>

            <!-- Developer Info -->
            <div class="dev-support">
                <p>
                    <i class="fa fa-code"></i> Developed by 
                    <a href="https://raidaitbd.com" target="_blank">Mohammad Robiul Hossain</a><br>
                    <a href="https://www.facebook.com/devrobiulbd/" target="_blank">
                        <i class="fab fa-facebook"></i> Facebook</a> | 
                    <a href="tel:01882850027"><i class="fab fa-whatsapp"></i> WhatsApp: 01882850027</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
