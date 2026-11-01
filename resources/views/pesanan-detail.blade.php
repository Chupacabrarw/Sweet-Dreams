@extends('layouts.app')

@section('title', 'Tracking Pesanan ' . $order['order_id'] . ' - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/pesanan-detail.css')
@endpush

<div class="tracking-page">
    {{-- Back Link --}}
    <a href="/profil" class="tracking-back-link" id="btn-back-to-profil">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Kembali ke Akun Saya
    </a>

    {{-- Header --}}
    <div class="tracking-header">
        <span class="tracking-eyebrow">PESANAN {{ $order['order_id'] }}</span>
        <h1>{{ $order['headline'] }}</h1>
        <p>{{ $order['subtitle'] }}</p>
    </div>

    @if(session('success'))
        <div class="order-flash success" role="status">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="order-flash error" role="alert">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="order-flash error" role="alert">{{ $errors->first() }}</div>
    @endif

    {{-- Pembayaran QRIS: scan QR bernominal, lalu unggah bukti bayar --}}
    @php $qris = $order['qris'] ?? []; @endphp
    @if(($order['payment_status'] ?? '') === 'unpaid' && ($order['status_raw'] ?? '') === 'pending')
        <div class="qris-pay-card" id="qris-pay-card">
            <div class="qris-pay-head">
                <span class="qris-eyebrow">Menunggu Pembayaran</span>
                <h3>Scan QRIS untuk membayar</h3>
                @if(!empty($qris['expires_at']))
                    <p class="qris-deadline">Bayar sebelum <strong>{{ $qris['expires_label'] }}</strong>
                        &middot; sisa <strong id="qris-countdown" data-expires="{{ $qris['expires_at'] }}">--:--:--</strong></p>
                @endif
            </div>

            @if(!empty($qris['note']))
                <div class="qris-rejected" role="alert">
                    <strong>Bukti bayar sebelumnya ditolak.</strong> {{ $qris['note'] }}
                </div>
            @endif

            @if(empty($qris['payload']))
                <p class="qris-unavailable">QRIS toko sedang tidak tersedia. Silakan hubungi admin lewat halaman <a href="{{ route('kontak') }}">Kontak</a>.</p>
            @else
                <div class="qris-pay-body">
                    <div class="qris-code-col">
                        <div class="qris-code-frame">
                            <div class="qris-code-brand">QRIS</div>
                            <div id="qris-code" class="qris-code" data-payload="{{ $qris['payload'] }}" role="img" aria-label="Kode QR pembayaran {{ $qris['amount_label'] }}"></div>
                            <div class="qris-merchant">
                                {{ strtoupper($qris['merchant']['name'] ?? 'Sweet Dreams') }}
                                @if(!empty($qris['merchant']['nmid']))<span>NMID {{ $qris['merchant']['nmid'] }}</span>@endif
                            </div>
                        </div>
                        <button type="button" class="qris-btn ghost" id="qris-download">Simpan Gambar QR</button>
                    </div>

                    <div class="qris-info-col">
                        <span class="qris-label">Total yang harus dibayar</span>
                        <div class="qris-amount-row">
                            <strong class="qris-amount">{{ $qris['amount_label'] }}</strong>
                            <button type="button" class="qris-copy" data-copy="{{ $qris['amount'] }}">Salin</button>
                        </div>
                        @if(!empty($qris['unique_code']))
                            <p class="qris-hint">Sudah termasuk <strong>kode unik Rp {{ $qris['unique_code'] }}</strong> untuk mencocokkan pembayaranmu. Nominal otomatis terisi saat QR di-scan, <strong>jangan diubah</strong>.</p>
                        @endif

                        <ol class="qris-steps">
                            <li>Buka aplikasi e-wallet / m-banking berlogo QRIS (GoPay, OVO, DANA, ShopeePay, BCA, dll).</li>
                            <li>Scan QR di samping. Kalau belanja dari HP, simpan gambar QR lalu pilih "upload dari galeri" di aplikasimu.</li>
                            <li>Pastikan nama merchant <strong>{{ $qris['merchant']['name'] ?? 'Sweet Dreams' }}</strong> dan nominal sesuai, lalu bayar.</li>
                            <li>Unggah screenshot bukti bayar di bawah ini.</li>
                        </ol>

                        <form method="POST" action="{{ route('pesanan.proof.upload', $order['raw_order_number']) }}" enctype="multipart/form-data" class="qris-proof-form" id="qris-proof-form">
                            @csrf
                            <label class="qris-file">
                                <input type="file" name="proof" accept="image/png,image/jpeg,image/webp" required id="qris-proof-input">
                                <span id="qris-file-name">Pilih screenshot bukti bayar (maks. 4 MB)</span>
                            </label>
                            <button type="submit" class="qris-btn primary" id="qris-proof-submit">Kirim Bukti Bayar</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @elseif(($order['payment_status'] ?? '') === 'verifying')
        <div class="qris-pay-card verifying">
            <span class="qris-eyebrow">Menunggu Verifikasi</span>
            <h3>Bukti bayar sudah kami terima</h3>
            <p class="qris-hint">Admin sedang mencocokkan pembayaran <strong>{{ $qris['amount_label'] ?? $order['summary']['total'] }}</strong>
                (diunggah {{ $qris['proof_uploaded_at'] ?? '-' }}). Pesanan otomatis lanjut diproses setelah dikonfirmasi; halaman ini akan memperbarui sendiri.</p>
            @if(!empty($qris['proof_url']))
                <a href="{{ $qris['proof_url'] }}" target="_blank" rel="noopener" class="qris-btn ghost">Lihat bukti yang dikirim</a>
            @endif
        </div>
    @endif

    @if(in_array($order['payment_status'] ?? '', ['unpaid', 'verifying'], true) && ($order['status_raw'] ?? '') === 'pending')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
        <script>
        (function () {
            const orderNumber = @json($order['raw_order_number'] ?? '');
            const box = document.getElementById('qris-code');
            let qrDataUrl = null;

            if (box && window.qrcode) {
                const qr = qrcode(0, 'M');
                qr.addData(box.dataset.payload);
                qr.make();
                box.innerHTML = qr.createSvgTag({ cellSize: 6, margin: 2, scalable: true });
                qrDataUrl = qr.createDataURL(10, 4);
            } else if (box) {
                box.textContent = 'Gagal memuat QR. Muat ulang halaman.';
            }

            document.getElementById('qris-download')?.addEventListener('click', function () {
                if (!qrDataUrl) return;
                const a = document.createElement('a');
                a.href = qrDataUrl;
                a.download = 'QRIS-' + orderNumber + '.gif';
                document.body.appendChild(a);
                a.click();
                a.remove();
            });

            document.querySelectorAll('.qris-copy').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    navigator.clipboard?.writeText(btn.dataset.copy).then(function () {
                        btn.textContent = 'Tersalin!';
                        setTimeout(function () { btn.textContent = 'Salin'; }, 1800);
                    });
                });
            });

            const input = document.getElementById('qris-proof-input');
            input?.addEventListener('change', function () {
                const label = document.getElementById('qris-file-name');
                if (label) label.textContent = input.files[0] ? input.files[0].name : 'Pilih screenshot bukti bayar (maks. 4 MB)';
            });
            document.getElementById('qris-proof-form')?.addEventListener('submit', function () {
                const btn = document.getElementById('qris-proof-submit');
                if (btn) { btn.disabled = true; btn.textContent = 'Mengirim...'; }
            });

            const cd = document.getElementById('qris-countdown');
            if (cd) {
                const deadline = new Date(cd.dataset.expires).getTime();
                const tick = function () {
                    const left = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
                    const h = String(Math.floor(left / 3600)).padStart(2, '0');
                    const m = String(Math.floor(left % 3600 / 60)).padStart(2, '0');
                    const s = String(left % 60).padStart(2, '0');
                    cd.textContent = h + ':' + m + ':' + s;
                    if (left === 0) { clearInterval(timer); setTimeout(function () { location.reload(); }, 1500); }
                };
                const timer = setInterval(tick, 1000);
                tick();
            }

            // Polling ringan: begitu admin konfirmasi, halaman ikut berubah
            const startStatus = @json($order['payment_status'] ?? '');
            setInterval(function () {
                if (document.hidden) return;
                fetch('/api/payment/status/' + encodeURIComponent(orderNumber), { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.ok ? r.json() : null; })
                    .then(function (res) {
                        if (res && (res.payment_status !== startStatus || res.order_status !== 'pending')) {
                            location.reload();
                        }
                    })
                    .catch(function () {});
            }, 20000);
        })();
        </script>
    @endif

    {{-- Tracking Card --}}
    <div class="tracking-card {{ $order['status_type'] === 'cancelled' ? 'cancelled' : '' }}">
        {{-- Courier Info --}}
        <div class="courier-header">
            <div class="courier-info">
                <h3>{{ $order['courier']['name'] }}</h3>
                <span class="courier-resi">Resi {{ $order['courier']['resi'] }} &middot; {{ $order['courier']['service'] }}</span>
            </div>
            <span class="tracking-status-badge {{ $order['status_type'] }}">{{ $order['status'] }}</span>
        </div>

        @if($order['status_type'] === 'cancelled')
            <div class="cancelled-order-progress" role="status">
                <span class="cancelled-order-icon" aria-hidden="true">×</span>
                <div>
                    <strong>Alur pesanan dihentikan</strong>
                    <p>Pesanan ini sudah dibatalkan dan tidak akan dilanjutkan ke proses pengiriman.</p>
                </div>
            </div>
        @else
            {{-- Progress Bar --}}
            @php
                $totalSteps = count($order['progress']);
                $currentStep = $order['current_step'];
                $fillPercent = ($totalSteps > 1) ? ($currentStep / ($totalSteps - 1)) * 100 : 0;
            @endphp
            <div class="progress-tracker">
                <div class="progress-track-line">
                    <div class="progress-track-fill" data-width="{{ $fillPercent }}" style="width: 0%"></div>
                </div>
                @foreach($order['progress'] as $idx => $step)
                    <div class="progress-step">
                        @if($step['completed'])
                            <div class="step-dot completed"></div>
                        @elseif($idx === $currentStep + 1)
                            <div class="step-dot current"></div>
                        @else
                            <div class="step-dot"></div>
                        @endif
                        <span class="step-label">{{ $step['label'] }}</span>
                        <span class="step-date">{{ $step['date'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Latest Update Bar --}}
        <div class="latest-update-bar {{ $order['status_type'] === 'cancelled' ? 'cancelled' : '' }}">
            <div class="latest-update-left">
                <div class="latest-update-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <div class="latest-update-text">
                    <h4>{{ $order['latest_update']['text'] }}</h4>
                    <span>{{ $order['latest_update']['time'] }}</span>
                </div>
            </div>
            <button class="btn-lihat-detail" id="btn-toggle-timeline">Lihat detail</button>
        </div>

        {{-- Konfirmasi terima (ala Shopee): hanya saat paket sedang dikirim --}}
        @if(($order['status_raw'] ?? '') === 'shipped')
            <form method="POST" action="{{ route('pesanan.complete', $order['raw_order_number']) }}" onsubmit="return confirm('Paket sudah sampai di tanganmu?');">
                @csrf
                <button type="submit" style="display:flex;align-items:center;justify-content:center;gap:0.5rem;width:100%;margin-top:1rem;background:#e06b88;color:#ffffff;font-weight:700;font-size:0.9rem;padding:12px 24px;border:none;border-radius:10px;cursor:pointer;box-shadow:0 3px 10px rgba(224,107,136,0.3);">Paket Sudah Diterima</button>
            </form>
        @endif

        {{-- Timeline Detail (Expandable) --}}
        <div class="tracking-timeline-wrapper" id="timeline-wrapper">
            <div class="tracking-timeline">
                @foreach($order['timeline'] as $group)
                    <div class="timeline-date-group">
                        <div class="timeline-date-label">{{ $group['date'] }}</div>
                        @foreach($group['events'] as $event)
                            <div class="timeline-event">
                                <span class="timeline-event-time">{{ $event['time'] }}</span>
                                <span class="timeline-event-text">{{ $event['text'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Product List --}}
    <h3 class="tracking-section-title">Produk dalam pesanan ini</h3>
    @foreach($order['products'] as $product)
        <div class="tracking-product-card {{ $order['status_type'] === 'cancelled' ? 'cancelled' : '' }}">
            <div class="tracking-product-left">
                <div class="tracking-product-img">
                    <img src="{{ asset($product['image']) }}" alt="{{ $product['title'] }}">
                </div>
                <div class="tracking-product-info">
                    <h4>{{ $product['title'] }}</h4>
                    <p>{{ $product['variant'] }}</p>
                </div>
            </div>
            <div class="tracking-product-price">{{ $product['price'] }}</div>
        </div>
    @endforeach

    {{-- Order Summary --}}
    <div class="tracking-summary-card">
        <div class="summary-row">
            <span class="summary-row-label">Subtotal produk</span>
            <span class="summary-row-value">{{ $order['summary']['subtotal'] }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-row-label">Ongkos kirim</span>
            <span class="summary-row-value">{{ $order['summary']['shipping'] }}</span>
        </div>
        <div class="summary-row total">
            <span class="summary-row-label">Total Belanja</span>
            <span class="summary-row-value">{{ $order['summary']['total'] }}</span>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="tracking-footer-actions">
        <a href="/profil" class="btn-back-orders" id="btn-footer-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Kembali ke Pesanan Saya
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timeline toggle
    const btnToggle = document.getElementById('btn-toggle-timeline');
    const timelineWrapper = document.getElementById('timeline-wrapper');
    let timelineOpen = false;

    if (btnToggle && timelineWrapper) {
        btnToggle.addEventListener('click', function() {
            timelineOpen = !timelineOpen;
            if (timelineOpen) {
                timelineWrapper.classList.add('open');
                btnToggle.textContent = 'Tutup detail';
            } else {
                timelineWrapper.classList.remove('open');
                btnToggle.textContent = 'Lihat detail';
            }
        });
    }

    // Clicking "Kembali" goes to profil with pesanan tab active
    const btnBack = document.getElementById('btn-back-to-profil');
    const btnFooterBack = document.getElementById('btn-footer-back');

    function goBackToPesanan(e) {
        e.preventDefault();
        sessionStorage.setItem('sweetdreams_active_tab', 'pesanan');
        window.location.href = '/profil';
    }

    if (btnBack) btnBack.addEventListener('click', goBackToPesanan);
    if (btnFooterBack) btnFooterBack.addEventListener('click', goBackToPesanan);

    // Animate progress bar on load
    const progressFill = document.querySelector('.progress-track-fill');
    if (progressFill) {
        const targetWidth = progressFill.getAttribute('data-width');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                progressFill.style.width = targetWidth + '%';
            });
        });
    }
});
</script>
@endsection
