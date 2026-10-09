<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluar Akun - Sweet Dreams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/pages/auth/logout.css')
    
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
