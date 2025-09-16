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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;600;700&display=swap" rel="stylesheet" />
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
            backdrop-filter: blur(2px);
        }

        .login-card {
            background: rgb(255, 255, 255);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 128, 128, 0.25);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .login-card img {
            max-width: 200px;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #444;
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

        .input-group-text {
            background-color: #e0f2f1;
            border: 1px solid #ddd;
            color: #00796b;
            cursor: pointer;
        }

        .btn-login {
            background-color: #00796b;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #005b4f;
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
            <div class="login-title text-center">
                <img src="{{ asset(setting('primary_logo')) }}" alt="Logo">
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="formSubmit">
                @csrf
                <div class="form-group text-left">
                    <label>Mobile Number</label>
                    <input type="number" name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="Enter Mobile Number" autofocus />
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group text-left">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Enter Password" />
                        <div class="input-group-append">
                            <span class="input-group-text" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login btn-dark btn-block">Login</button>
            </form>

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

    <!-- JS for password toggle -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $('#togglePassword').click(function () {
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    </script>
</body>

</html>
