<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin - Sweet Dreams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/pages/admin/login.css')
</head>
<body>
    <div class="admin-login-card">
        <div class="admin-login-logo"><img src="/images/logo.png" alt="Sweet Dream Logo"></div>
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