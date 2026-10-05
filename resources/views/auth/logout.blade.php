<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluar Akun - Sweet Dreams</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #fff8fa;
            color: #5a3a42;
        }
        .logout-box {
            text-align: center;
            background: #ffffff;
            padding: 2.5rem 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(212, 77, 110, 0.1);
            border: 1.5px solid #fbd5df;
            max-width: 360px;
            width: 90%;
        }
        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid #fbd5df;
            border-top-color: #d44d6e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1.25rem;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h2 {
            font-size: 1.15rem;
            margin: 0 0 0.5rem 0;
            color: #3a2a2e;
        }
        p {
            font-size: 0.85rem;
            color: #8a6a72;
            margin: 0;
        }
    </style>
    
<script>
    try {
        localStorage.removeItem('sweetdreams_auth_user');
    } catch(e) {}
    window.location.replace('/logout');
</script>
</head>
<body>
    <div class="logout-box">
        <div class="spinner"></div>
        <h2>Mengeluarkan Akun</h2>
        <p>Mohon tunggu sebentar, sesi Anda sedang diakhiri...</p>
    </div>
</body>
</html>
