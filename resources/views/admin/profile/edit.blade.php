@extends('admin.layout')

@section('title', 'Pengaturan Profil Admin')
@section('page-title', 'Pengaturan Profil Admin')
@section('page-subtitle', 'Ganti email login dan password akun admin')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/profile.css')
@endpush

@php
    // Modal OTP terbuka otomatis hanya tepat setelah kode dikirim atau saat kode salah
    $showOtp = $pending && ($errors->otp->any() || session('otp_sent'));
@endphp

<div class="pf-grid">
    {{-- ===== CARD 1: GANTI EMAIL ===== --}}
    <section class="pf-card" id="email-card">
        <header class="pf-card-head">
            <div class="pf-icon"><i data-lucide="mail"></i></div>
            <div>
                <h2>Ganti Email</h2>
                <p>Kode verifikasi akan dikirim ke <strong>email baru</strong>.</p>
            </div>
        </header>

        <div class="pf-current">
            <span>Email saat ini</span>
            <strong>{{ $admin->email }}</strong>
        </div>

        @if($pending)
            <div class="pf-pending">
                <i data-lucide="clock"></i>
                <div>
                    Menunggu verifikasi untuk <strong>{{ $pending['email'] }}</strong>.
                    <button type="button" class="pf-link" onclick="openOtp()">Masukkan kode</button>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.profile.email.request') }}" class="pf-form" novalidate>
            @csrf
            <label class="pf-label" for="new_email">Email Baru</label>
            <input class="pf-input @error('new_email', 'email') is-invalid @enderror" type="email" id="new_email" name="new_email"
                   value="{{ old('new_email', $pending['email'] ?? '') }}" placeholder="nama@domain.com" autocomplete="email" required>
            @error('new_email', 'email') <p class="pf-error">{{ $message }}</p> @enderror

            <label class="pf-label" for="email_current_password">Password Saat Ini</label>
            <div class="pf-pass">
                <input class="pf-input @error('current_password', 'email') is-invalid @enderror" type="password" id="email_current_password"
                       name="current_password" placeholder="Untuk konfirmasi keamanan" autocomplete="current-password" required>
                <button type="button" class="pf-eye" data-toggle="email_current_password" aria-label="Lihat password"><i data-lucide="eye"></i></button>
            </div>
            @error('current_password', 'email') <p class="pf-error">{{ $message }}</p> @enderror

            <button type="submit" class="pf-btn"><i data-lucide="send"></i> Kirim Kode Verifikasi</button>
        </form>
    </section>

    {{-- ===== CARD 2: GANTI PASSWORD ===== --}}
    <section class="pf-card" id="password-card">
        <header class="pf-card-head">
            <div class="pf-icon"><i data-lucide="lock"></i></div>
            <div>
                <h2>Ganti Password</h2>
                <p>Minimal 8 karakter, berbeda dari password lama.</p>
            </div>
        </header>

        <form method="POST" action="{{ route('admin.profile.password') }}" class="pf-form" novalidate>
            @csrf @method('PUT')
            <label class="pf-label" for="pw_current">Password Saat Ini</label>
            <div class="pf-pass">
                <input class="pf-input @error('current_password', 'password') is-invalid @enderror" type="password" id="pw_current"
                       name="current_password" autocomplete="current-password" required>
                <button type="button" class="pf-eye" data-toggle="pw_current" aria-label="Lihat password"><i data-lucide="eye"></i></button>
            </div>
            @error('current_password', 'password') <p class="pf-error">{{ $message }}</p> @enderror

            <label class="pf-label" for="pw_new">Password Baru</label>
            <div class="pf-pass">
                <input class="pf-input @error('password', 'password') is-invalid @enderror" type="password" id="pw_new"
                       name="password" autocomplete="new-password" minlength="8" required>
                <button type="button" class="pf-eye" data-toggle="pw_new" aria-label="Lihat password"><i data-lucide="eye"></i></button>
            </div>
            <div class="pf-strength"><span id="pw-bar"></span></div>
            <p class="pf-hint" id="pw-hint">Gunakan kombinasi huruf besar, kecil, angka, dan simbol.</p>
            @error('password', 'password') <p class="pf-error">{{ $message }}</p> @enderror

            <label class="pf-label" for="pw_confirm">Konfirmasi Password Baru</label>
            <div class="pf-pass">
                <input class="pf-input" type="password" id="pw_confirm" name="password_confirmation" autocomplete="new-password" required>
                <button type="button" class="pf-eye" data-toggle="pw_confirm" aria-label="Lihat password"><i data-lucide="eye"></i></button>
            </div>
            <p class="pf-error" id="pw-match" hidden>Konfirmasi belum sama dengan password baru.</p>

            <button type="submit" class="pf-btn"><i data-lucide="save"></i> Simpan Password</button>
        </form>
    </section>
</div>

{{-- ===== MODAL KODE OTP ===== --}}
@if($pending)
<div class="pf-modal {{ $showOtp ? 'open' : '' }}" id="otp-modal" role="dialog" aria-modal="true" aria-labelledby="otp-title">
    <div class="pf-modal-box">
        <button type="button" class="pf-modal-close" onclick="closeOtp()" aria-label="Tutup"><i data-lucide="x"></i></button>
        <div class="pf-icon pf-icon-lg"><i data-lucide="shield-check"></i></div>
        <h3 id="otp-title">Masukkan Kode Verifikasi</h3>
        <p class="pf-modal-desc">Kode 6 angka sudah dikirim ke<br><strong>{{ $pending['email'] }}</strong></p>

        <form method="POST" action="{{ route('admin.profile.email.verify') }}" novalidate>
            @csrf
            <input class="pf-otp @error('otp_code', 'otp') is-invalid @enderror" type="text" name="otp_code" id="otp_code"
                   inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" placeholder="••••••" required>
            @error('otp_code', 'otp') <p class="pf-error pf-center">{{ $message }}</p> @enderror
            <p class="pf-timer">Berlaku <span id="otp-timer" data-expires="{{ $pending['expires_at'] }}">--:--</span></p>
            <button type="submit" class="pf-btn pf-btn-block"><i data-lucide="check"></i> Verifikasi &amp; Ganti Email</button>
        </form>

        <div class="pf-modal-foot">
            <form method="POST" action="{{ route('admin.profile.email.request') }}" id="resend-form">
                @csrf
                <input type="hidden" name="new_email" value="{{ $pending['email'] }}">
                <input type="hidden" name="current_password" id="resend-password">
                <button type="button" class="pf-link" id="resend-btn" data-wait="{{ $pending['resend_in'] }}" onclick="resendOtp()">Kirim ulang kode</button>
            </form>
            <span>·</span>
            <form method="POST" action="{{ route('admin.profile.email.cancel') }}">
                @csrf @method('DELETE')
                <button type="submit" class="pf-link pf-link-muted">Batalkan</button>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    // Lihat/sembunyikan password
    document.querySelectorAll('.pf-eye').forEach(btn => btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.toggle);
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Lihat password');
        btn.innerHTML = `<i data-lucide="${show ? 'eye-off' : 'eye'}"></i>`;
        if (window.lucide) lucide.createIcons();
    }));

    // Indikator kekuatan & kecocokan password baru
    const pwNew = document.getElementById('pw_new'), pwConfirm = document.getElementById('pw_confirm');
    function checkPassword() {
        const v = pwNew.value;
        let score = 0;
        if (v.length >= 8) score++;
        if (/[a-z]/.test(v) && /[A-Z]/.test(v)) score++;
        if (/\d/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;
        const labels = ['Terlalu pendek', 'Lemah', 'Cukup', 'Kuat', 'Sangat kuat'];
        const bar = document.getElementById('pw-bar');
        bar.style.width = (v ? Math.max(score, 1) * 25 : 0) + '%';
        bar.dataset.level = score;
        document.getElementById('pw-hint').textContent = v ? `Kekuatan: ${labels[v.length < 8 ? 0 : score]}` : 'Gunakan kombinasi huruf besar, kecil, angka, dan simbol.';
        document.getElementById('pw-match').hidden = !pwConfirm.value || pwConfirm.value === v;
    }
    pwNew.addEventListener('input', checkPassword);
    pwConfirm.addEventListener('input', checkPassword);

    // Modal OTP
    const otpModal = document.getElementById('otp-modal');
    function openOtp() { otpModal?.classList.add('open'); setTimeout(() => document.getElementById('otp_code')?.focus(), 50); }
    function closeOtp() { otpModal?.classList.remove('open'); }
    otpModal?.addEventListener('click', e => { if (e.target === otpModal) closeOtp(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeOtp(); });
    if (otpModal?.classList.contains('open')) setTimeout(() => document.getElementById('otp_code')?.focus(), 50);
    document.getElementById('otp_code')?.addEventListener('input', e => { e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6); });

    // Hitung mundur masa berlaku kode
    const timer = document.getElementById('otp-timer');
    if (timer) {
        const tick = () => {
            const left = Math.max(0, Number(timer.dataset.expires) - Math.floor(Date.now() / 1000));
            timer.textContent = left ? `${String(Math.floor(left / 60)).padStart(2, '0')}:${String(left % 60).padStart(2, '0')}` : 'habis — minta kode baru';
            timer.classList.toggle('expired', !left);
        };
        tick(); setInterval(tick, 1000);
    }

    // Kirim ulang kode: butuh password lagi (keamanan), dengan jeda 60 detik
    const resendBtn = document.getElementById('resend-btn');
    if (resendBtn) {
        let wait = Number(resendBtn.dataset.wait);
        const label = () => {
            resendBtn.disabled = wait > 0;
            resendBtn.textContent = wait > 0 ? `Kirim ulang (${wait}d)` : 'Kirim ulang kode';
        };
        label();
        setInterval(() => { if (wait > 0) { wait--; label(); } }, 1000);
    }
    function resendOtp() {
        const pw = prompt('Masukkan password saat ini untuk mengirim ulang kode:');
        if (!pw) return;
        document.getElementById('resend-password').value = pw;
        document.getElementById('resend-form').submit();
    }
</script>
@endsection
