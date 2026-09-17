<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin - Sweet Dreams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Inter',sans-serif; background:#20161b;
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding:1.5rem;
        }
        .admin-login-card {
            background:#fff; border-radius:20px; padding:2.75rem 2.5rem;
            width:100%; max-width:400px;
        }
        .admin-login-logo {
            width:52px; height:52px; border-radius:14px; background:#d44d6e;
            display:flex; align-items:center; justify-content:center;
            font-family:'Playfair Display',serif; color:#fff; font-size:1.5rem; font-weight:700;
            margin:0 auto 1.25rem;
        }
        .admin-login-title {
            text-align:center; font-family:'Playfair Display',serif;
            font-size:1.6rem; font-weight:700; color:#20161b; margin-bottom:0.35rem;
        }
        .admin-login-subtitle { text-align:center; font-size:0.85rem; color:#8a6a72; margin-bottom:2rem; }
        .form-group { margin-bottom:1.2rem; }
        .form-group label { display:block; font-size:0.82rem; font-weight:600; color:#3a2a2e; margin-bottom:0.4rem; }
        .form-group input {
            width:100%; padding:0.75rem 1rem; border:1px solid #e5dde0; border-radius:10px; font-size:0.9rem;
        }
        .form-group input:focus { outline:none; border-color:#d44d6e; }
        .btn-admin-login {
            width:100%; background:#d44d6e; color:#fff; border:none; border-radius:10px;
            padding:0.85rem; font-size:0.95rem; font-weight:700; cursor:pointer; margin-top:0.5rem;
        }
        .btn-admin-login:hover { background:#b83d5c; }
        .error-box {
            background:#fde2e6; color:#c53660; padding:0.75rem 1rem; border-radius:10px;
            font-size:0.85rem; margin-bottom:1.2rem;
        }
        .back-link { display:block; text-align:center; margin-top:1.5rem; font-size:0.82rem; color:#8a6a72; text-decoration:none; }
        .back-link:hover { color:#d44d6e; }
    </style>
</head>
<body>
    <div class="admin-login-card">
        <div class="admin-login-logo">S</div>
        <h1 class="admin-login-title">Login Dashboard Admin</h1>
        <p class="admin-login-subtitle">Khusus untuk tim internal Sweet Dreams</p>

        @if($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-admin-login">Masuk ke Dashboard</button>
        </form>

        <a href="/" class="back-link">← Kembali ke halaman utama</a>
    </div>
</body>
</html>