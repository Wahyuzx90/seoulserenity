<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Seoul Serenity</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --ink: #1a1a1a;
            --parchment: #fef9f0;
            --gochujang: #c23b22;
            --gold: #d4af37;
            --ash: #6b6b6b;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--parchment) 0%, #f0e6d5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #c23b22, #8b2a18);
            color: white;
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            margin-bottom: 20px;
        }
        .login-left p {
            opacity: 0.8;
            line-height: 1.6;
        }
        .login-right {
            flex: 1;
            padding: 48px;
        }
        .login-right h2 {
            font-size: 28px;
            margin-bottom: 8px;
        }
        .login-right .subtitle {
            color: var(--ash);
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 14px;
            transition: 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--gochujang);
            box-shadow: 0 0 0 3px rgba(194,59,34,0.1);
        }
        .btn-login {
            width: 100%;
            background: var(--gochujang);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-login:hover {
            background: #8b2a18;
            transform: translateY(-2px);
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .register-link a {
            color: var(--gochujang);
            text-decoration: none;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-left {
                padding: 32px;
                text-align: center;
            }
            .login-left h1 {
                font-size: 32px;
            }
            .login-right {
                padding: 32px;
            }
        }
        @media (max-width: 480px) {
            .login-left h1 {
                font-size: 24px;
            }
            .login-right h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h1>Seoul Serenity</h1>
            <p>Rasakan keautentikan cita rasa Korea dalam setiap suapan</p>
        </div>
        <div class="login-right">
            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk ke akun Anda</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@contoh.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Kata sandi" required>
                </div>
                <button type="submit" class="btn-login">Masuk</button>
            </form>
            <div class="register-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</body>
</html>