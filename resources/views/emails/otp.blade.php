<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode Verifikasi Email</title>
</head>
<body style="margin:0; padding:0; background:#faf3f5; font-family:Arial, Helvetica, sans-serif; color:#2a1f22;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#faf3f5; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #f1e4e7;">
                    <tr>
                        <td style="background:#d44d6e; padding:20px 28px; color:#ffffff; font-size:18px; font-weight:bold;">
                            {{ config('app.name') }} · Admin
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px; font-size:15px;">Halo {{ $adminName }},</p>
                            <p style="margin:0 0 20px; font-size:15px; line-height:1.6;">
                                Ada permintaan untuk menjadikan alamat email ini sebagai email login admin.
                                Masukkan kode berikut di halaman <strong>Pengaturan Profil Admin</strong>:
                            </p>
                            <div style="text-align:center; margin:0 0 20px;">
                                <span style="display:inline-block; font-size:34px; font-weight:bold; letter-spacing:10px; color:#d44d6e; background:#fdf0f3; border:1px dashed #f2c9d3; border-radius:12px; padding:14px 22px;">{{ $otpCode }}</span>
                            </div>
                            <p style="margin:0 0 8px; font-size:14px; color:#8a6a72;">
                                Kode berlaku <strong>{{ $expiresInMinutes }} menit</strong> dan hanya bisa dipakai sekali.
                            </p>
                            <p style="margin:0; font-size:14px; color:#8a6a72; line-height:1.6;">
                                Kalau kamu tidak merasa meminta perubahan ini, abaikan email ini — email login admin tidak akan berubah.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px; background:#fbf6f7; font-size:12px; color:#a8909a;">
                            Jangan bagikan kode ini kepada siapa pun, termasuk yang mengaku dari {{ config('app.name') }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
