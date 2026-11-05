<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Pengaturan Profil Admin: ganti email (verifikasi OTP ke email BARU) dan ganti password.
 * Data OTP sementara disimpan di cache (tabel `cache` sudah ada di lokal & Supabase),
 * jadi tidak perlu migrasi kolom baru di tabel users.
 */
class ProfileController extends Controller
{
    public const OTP_TTL_MINUTES = 10;
    public const OTP_MAX_ATTEMPTS = 5;
    public const RESEND_COOLDOWN_SECONDS = 60;

    protected function otpKey(int $userId): string
    {
        return "admin-email-otp:{$userId}";
    }

    public function edit(Request $request)
    {
        $pending = Cache::get($this->otpKey($request->user()->id));

        return view('admin.profile.edit', [
            'admin'   => $request->user(),
            'pending' => $pending ? [
                'email'      => $pending['email'],
                'expires_at' => $pending['expires_at'],
                'resend_in'  => max(0, $pending['sent_at'] + self::RESEND_COOLDOWN_SECONDS - now()->timestamp),
            ] : null,
        ]);
    }

    /** Langkah 1: cek password, buat OTP 6 digit, kirim ke email BARU. */
    public function requestEmailOtp(Request $request): RedirectResponse
    {
        $user = $request->user();
        $request->merge(['new_email' => strtolower(trim((string) $request->input('new_email')))]);

        $data = $request->validateWithBag('email', [
            'new_email'        => [
                'required', 'email:rfc', 'max:255',
                Rule::notIn([strtolower($user->email)]),
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'current_password' => ['required', 'current_password'],
        ], [
            'new_email.required'                => 'Email baru wajib diisi.',
            'new_email.email'                   => 'Format email tidak valid.',
            'new_email.not_in'                  => 'Email baru tidak boleh sama dengan email saat ini.',
            'new_email.unique'                  => 'Email ini sudah dipakai akun lain.',
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
        ]);

        $existing = Cache::get($this->otpKey($user->id));
        if ($existing && $existing['email'] === $data['new_email']
            && now()->timestamp - $existing['sent_at'] < self::RESEND_COOLDOWN_SECONDS) {
            $wait = self::RESEND_COOLDOWN_SECONDS - (now()->timestamp - $existing['sent_at']);
            throw ValidationException::withMessages(['new_email' => "Tunggu {$wait} detik sebelum meminta kode baru."])
                ->errorBag('email');
        }

        // Maksimal 5 permintaan OTP per 15 menit per admin (cegah spam ke alamat orang lain)
        $limiterKey = 'admin-email-otp-request:' . $user->id;
        if (RateLimiter::tooManyAttempts($limiterKey, 5)) {
            $minutes = (int) ceil(RateLimiter::availableIn($limiterKey) / 60);
            throw ValidationException::withMessages(['new_email' => "Terlalu banyak permintaan kode. Coba lagi dalam {$minutes} menit."])
                ->errorBag('email');
        }
        RateLimiter::hit($limiterKey, 15 * 60);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(self::OTP_TTL_MINUTES);

        try {
            // SENGAJA dikirim ke email BARU: membuktikan admin benar-benar memiliki alamat itu
            Mail::to($data['new_email'])->send(new SendOtpMail($code, $user->name, self::OTP_TTL_MINUTES));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim OTP ganti email admin: ' . $e->getMessage());
            throw ValidationException::withMessages(['new_email' => 'Email kode verifikasi gagal dikirim. Cek pengaturan email (MAIL_*) lalu coba lagi.'])
                ->errorBag('email');
        }

        Cache::put($this->otpKey($user->id), [
            'email'      => $data['new_email'],
            'code_hash'  => Hash::make($code),     // kode tidak disimpan dalam bentuk asli
            'expires_at' => $expiresAt->timestamp,
            'sent_at'    => now()->timestamp,
            'attempts'   => 0,
        ], $expiresAt);

        return redirect()->route('admin.profile')
            ->with('otp_sent', true)
            ->with('success', "Kode verifikasi 6 angka sudah dikirim ke {$data['new_email']}. Berlaku " . self::OTP_TTL_MINUTES . ' menit.');
    }

    /** Langkah 2: cocokkan OTP, lalu ganti email. */
    public function verifyEmailOtp(Request $request): RedirectResponse
    {
        $user = $request->user();
        $key = $this->otpKey($user->id);

        $request->validateWithBag('otp', [
            'otp_code' => ['required', 'digits:6'],
        ], [
            'otp_code.required' => 'Kode verifikasi wajib diisi.',
            'otp_code.digits'   => 'Kode verifikasi harus 6 angka.',
        ]);

        $pending = Cache::get($key);
        if (!$pending || now()->timestamp > $pending['expires_at']) {
            Cache::forget($key);
            throw ValidationException::withMessages(['otp_code' => 'Kode sudah kedaluwarsa atau belum diminta. Minta kode baru.'])
                ->errorBag('otp');
        }

        if (!Hash::check($request->input('otp_code'), $pending['code_hash'])) {
            $pending['attempts']++;
            if ($pending['attempts'] >= self::OTP_MAX_ATTEMPTS) {
                Cache::forget($key);
                throw ValidationException::withMessages(['otp_code' => 'Kode salah ' . self::OTP_MAX_ATTEMPTS . ' kali. Demi keamanan, minta kode baru.'])
                    ->errorBag('otp');
            }
            Cache::put($key, $pending, \Illuminate\Support\Carbon::createFromTimestamp($pending['expires_at']));
            $left = self::OTP_MAX_ATTEMPTS - $pending['attempts'];
            throw ValidationException::withMessages(['otp_code' => "Kode salah. Sisa {$left} percobaan."])
                ->errorBag('otp');
        }

        // Cek ulang: email bisa saja sudah dipakai akun lain selama menunggu OTP
        $taken = \App\Models\User::where('email', $pending['email'])->where('id', '!=', $user->id)->exists();
        if ($taken) {
            Cache::forget($key);
            throw ValidationException::withMessages(['otp_code' => 'Email tersebut sudah dipakai akun lain. Gunakan email lain.'])
                ->errorBag('otp');
        }

        $user->forceFill(['email' => $pending['email'], 'email_verified_at' => now()])->save();
        Cache::forget($key);

        return redirect()->route('admin.profile')
            ->with('success', "Email admin berhasil diganti ke {$pending['email']}. Gunakan email ini untuk login berikutnya.");
    }

    /** Batalkan permintaan ganti email yang masih menunggu OTP. */
    public function cancelEmailOtp(Request $request): RedirectResponse
    {
        Cache::forget($this->otpKey($request->user()->id));

        return redirect()->route('admin.profile')->with('success', 'Permintaan ganti email dibatalkan.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'different:current_password', Password::min(8)],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
            'password.different'                => 'Password baru harus berbeda dari password saat ini.',
            'password.min'                      => 'Password baru minimal 8 karakter.',
        ]);

        $request->user()->forceFill(['password' => Hash::make($request->input('password'))])->save();
        $request->session()->regenerate();

        return redirect()->route('admin.profile')->with('success', 'Password admin berhasil diganti.');
    }
}
