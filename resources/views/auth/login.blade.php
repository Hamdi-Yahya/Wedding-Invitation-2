<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wedding Invitation Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset dan base styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F7F7F2;
        }

        /* Container utama login card */
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        /* Card login dengan shadow dan border radius */
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px 32px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            text-align: center;
        }

        /* Icon hati di atas card */
        .login-icon {
            width: 56px;
            height: 56px;
            background-color: #FFF8E1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        /* Judul login */
        .login-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        /* Subtitle login */
        .login-subtitle {
            font-size: 13px;
            color: #999;
            margin-bottom: 28px;
        }

        /* Form group wrapper */
        .form-group {
            text-align: left;
            margin-bottom: 16px;
        }

        /* Label form */
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        /* Input field styling */
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background: #FAFAF5;
            transition: border-color 0.2s;
        }

        /* Focus state untuk input */
        .form-group input:focus {
            outline: none;
            border-color: #F5D547;
        }

        /* Tombol login */
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #F5D547;
            color: #1a1a1a;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            font-family: inherit;
            transition: background-color 0.2s;
        }

        /* Hover state tombol */
        .btn-login:hover {
            background-color: #E5C63A;
        }

        /* Alert error styling */
        .alert-error {
            background-color: #FDE8E8;
            color: #721c24;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-icon">💛</div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Login ke Dashboard Admin</p>

            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username"
                        value="{{ old('username') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-login">🔐 LOGIN</button>
            </form>
        </div>
    </div>
</body>

</html>