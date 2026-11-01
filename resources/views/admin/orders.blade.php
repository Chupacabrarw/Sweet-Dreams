@extends('admin.layout')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Proses pesanan masuk dan pantau pengiriman')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/orders.css')
@endpush

<div class="filter-pills">
    <a href="{{ route('admin.orders') }}" class="filter-pill {{ $activeFilter === 'all' ? 'active' : '' }}">Semua <strong>{{ $counts['all'] }}</strong></a>
    <a href="{{ route('admin.orders', ['status' => 'verifikasi']) }}" class="filter-pill {{ $activeFilter === 'verifikasi' ? 'active' : '' }}">Perlu Verifikasi Bayar <strong>{{ $counts['verifikasi'] }}</strong></a>
    <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="filter-pill {{ $activeFilter === 'pending' ? 'active' : '' }}">Baru <strong>{{ $counts['pending'] }}</strong></a>
    <a href="{{ route('admin.orders', ['status' => 'processing']) }}" class="filter-pill {{ $activeFilter === 'processing' ? 'active' : '' }}">Diproses <strong>{{ $counts['processing'] }}</strong></a>
    <a href="{{ route('admin.orders', ['status' => 'shipped']) }}" class="filter-pill {{ $activeFilter === 'shipped' ? 'active' : '' }}">Dikirim <strong>{{ $counts['shipped'] }}</strong></a>
    <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="filter-pill {{ $activeFilter === 'completed' ? 'active' : '' }}">Selesai <strong>{{ $counts['completed'] }}</strong></a>
</div>

<div class="admin-card">
    <h3 style="font-size:1rem; margin-bottom:1.25rem; color:#3a2a2e;">Daftar Pesanan Masuk</h3>

    @if($rows->isEmpty())
        <div style="text-align:center; padding:3rem 1rem; color:#8a6a72;">
            <div style="font-size:2.5rem; margin-bottom:0.75rem;">&#128230;</div>
            <p style="font-size:0.9rem;">Belum ada pesanan.</p>
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No. Order</th><th>Pelanggan</th><th>Tanggal</th><th>Status</th><th>Total</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td><strong style="color:#d44d6e;">#{{ $row['order_number'] }}</strong></td>
                        <td>{{ $row['customer'] }}</td>
                        <td style="color:#8a6a72;">{{ $row['date'] }}</td>
                        <td>
                            <span class="status-pill {{ $row['status'] }}">{{ $row['status_label'] }}</span>
                            @if($row['payment_status'] === 'verifying')
                                <span class="pay-flag verifying">Cek bukti bayar</span>
                            @elseif($row['payment_status'] === 'paid')
                                <span class="pay-flag paid">Lunas</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $row['total'] }}</strong>
                            @if($row['payment_amount'] && $row['payment_status'] !== 'paid')
                                <div style="font-size:0.74rem; color:#8a6a72;">Bayar QRIS: {{ $row['payment_amount'] }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="action-link" onclick="loadOrderDetail('{{ $row['order_number'] }}', {{ $row['id'] }})">
                                Lihat Detail
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="admin-pagination">{{ $rows->links('vendor.pagination.admin') }}</div>
    @endif
</div>

{{-- MODAL --}}
<div class="order-modal-overlay" id="order-modal-overlay">
    <div class="order-modal-card">

        <div class="modal-header">
            <div class="modal-header-left">
                <h2 id="modal-order-number">Detail Pesanan</h2>
                <p id="modal-order-date"></p>
                <span class="modal-status-pill status-pill" id="modal-status-badge"></span>
            </div>
            <button class="btn-modal-close" id="btn-close-modal">&times;</button>
        </div>

        <div class="modal-body">
            <div class="info-section">
                <p class="info-section-title">Informasi Pelanggan</p>
                <div class="info-row"><span class="ilabel">Nama Akun</span><span class="ivalue" id="modal-customer-name">-</span></div>
                <div class="info-row"><span class="ilabel">Email</span><span class="ivalue" id="modal-customer-email">-</span></div>
                <div class="info-row"><span class="ilabel">Penerima</span><span class="ivalue" id="modal-recipient-name">-</span></div>
                <div class="info-row"><span class="ilabel">Telepon</span><span class="ivalue" id="modal-phone">-</span></div>
            </div>

            <div class="info-section">
                <p class="info-section-title">Alamat &amp; Pengiriman</p>
                <div class="info-row"><span class="ilabel">Alamat</span><span class="ivalue" id="modal-address">-</span></div>
                <div class="info-row"><span class="ilabel">Kota</span><span class="ivalue" id="modal-city">-</span></div>
                <div class="info-row"><span class="ilabel">Provinsi</span><span class="ivalue" id="modal-province">-</span></div>
                <div class="info-row"><span class="ilabel">Kode Pos</span><span class="ivalue" id="modal-postal">-</span></div>
                <div class="info-row"><span class="ilabel">Kurir</span><span class="ivalue" id="modal-courier">-</span></div>
                <div class="info-row"><span class="ilabel">Ongkir</span><span class="ivalue" id="modal-shipping-cost">-</span></div>
                <div class="info-row"><span class="ilabel">No. Resi</span><span class="ivalue" id="modal-tracking">-</span></div>
            </div>

            <div class="info-section">
                <p class="info-section-title">Informasi Pembayaran</p>
                <div class="info-row"><span class="ilabel">Metode</span><span class="ivalue" id="modal-payment-method">-</span></div>
                <div class="info-row"><span class="ilabel">Status Bayar</span><span class="ivalue" id="modal-payment-status">-</span></div>
                <div class="info-row"><span class="ilabel">Nominal QRIS</span><span class="ivalue" id="modal-payment-amount">-</span></div>
                <div class="info-row"><span class="ilabel">Batas Bayar</span><span class="ivalue" id="modal-payment-expiry">-</span></div>
                <div class="info-row"><span class="ilabel">Voucher</span><span class="ivalue" id="modal-voucher">-</span></div>
            </div>

            <div class="info-section">
                <p class="info-section-title">Verifikasi Pembayaran QRIS</p>
                <div id="modal-proof-box" class="proof-box"></div>
                <p id="modal-payment-note" class="proof-note" style="display:none;"></p>
                <form method="POST" id="payment-verify-form" class="verify-form" style="display:none;">
                    @csrf @method('PUT')
                    <input type="text" name="note" maxlength="255" placeholder="Catatan (opsional, tampil ke pembeli jika ditolak)">
                    <div class="verify-actions">
                        <button type="submit" name="action" value="approve" class="btn-verify approve"
                                onclick="return confirm('Pastikan dana dengan nominal ini sudah masuk di aplikasi merchant QRIS. Konfirmasi lunas?')">Konfirmasi Lunas</button>
                        <button type="submit" name="action" value="reject" class="btn-verify reject" id="btn-verify-reject"
                                onclick="return confirm('Tolak bukti bayar ini? Pembeli akan diminta mengunggah ulang.')">Tolak Bukti</button>
                    </div>
                    <small style="color:#8a6a72; font-size:0.74rem;">Cocokkan <strong>nominal persis</strong> (termasuk kode unik) dengan mutasi QRIS di aplikasi merchant sebelum konfirmasi.</small>
                </form>
            </div>
        </div>

        <div class="modal-items-section">
            <p class="items-section-title">Produk yang Dipesan</p>
            <div id="modal-items-list"></div>
        </div>

        <div class="totals-section">
            <div class="total-row"><span>Subtotal Produk</span><span class="tval" id="modal-subtotal">-</span></div>
            <div class="total-row"><span>Biaya Pengiriman</span><span class="tval" id="modal-shipping-cost-total">-</span></div>
            <div class="total-row"><span>Diskon / Voucher</span><span class="tval green" id="modal-discount">-</span></div>
            <div class="total-row grand">
                <span>Total Pembayaran</span>
                <span class="tval" id="modal-total">-</span>
            </div>
        </div>

        <form method="POST" id="order-status-form">
            @csrf @method('PUT')
            <div class="modal-form-section">
                <div class="mf-group">
                    <label>Update Status Pesanan</label>
                    <select name="status" id="order-status-select">
                        <option value="pending">Baru (Pending)</option>
                        <option value="processing">Sedang Diproses</option>
                        <option value="shipped">Sudah Dikirim</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="mf-group">
                    <label>Nomor Resi Pengiriman <span id="resi-required-mark" style="display:none;color:#e11d48;">*</span></label>
                    <input type="text" name="tracking_number" id="order-tracking-input" placeholder="Contoh: JNE0239847201">
                    <small id="resi-hint" style="display:none;color:#e11d48;font-size:0.75rem;">Wajib diisi saat status "Sudah Dikirim".</small>
                </div>
                <button type="submit" class="btn-update-status">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Resi wajib saat status "Sudah Dikirim" (backend juga menolak tanpa resi)
    function syncResiRequirement() {
        var isShipped = document.getElementById('order-status-select').value === 'shipped';
        document.getElementById('order-tracking-input').required = isShipped;
        document.getElementById('resi-required-mark').style.display = isShipped ? 'inline' : 'none';
        document.getElementById('resi-hint').style.display = isShipped ? 'block' : 'none';
    }
    document.getElementById('order-status-select').addEventListener('change', syncResiRequirement);

function loadOrderDetail(orderNumber, orderId) {
    fetch('/admin/pesanan/' + orderId, { headers: { 'Accept': 'application/json' } })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            document.getElementById('modal-order-number').textContent = 'Pesanan #' + data.order_number;
            document.getElementById('modal-order-date').textContent = 'Tanggal: ' + data.created_at;
            var badge = document.getElementById('modal-status-badge');
            badge.textContent = data.status_label;
            badge.className = 'modal-status-pill status-pill ' + data.status;

            document.getElementById('modal-customer-name').textContent = data.customer_name || '-';
            document.getElementById('modal-customer-email').textContent = data.customer_email || '-';
            document.getElementById('modal-recipient-name').textContent = data.shipping_recipient_name || '-';
            document.getElementById('modal-phone').textContent = data.shipping_phone || '-';

            document.getElementById('modal-address').textContent = data.shipping_address || '-';
            document.getElementById('modal-city').textContent = data.shipping_city || '-';
            document.getElementById('modal-province').textContent = data.shipping_province || '-';
            document.getElementById('modal-postal').textContent = data.shipping_postal_code || '-';
            document.getElementById('modal-courier').textContent = data.shipping_courier || '-';
            document.getElementById('modal-shipping-cost').textContent = data.shipping_cost || '-';
            document.getElementById('modal-tracking').textContent = data.tracking_number || '(Belum ada)';
            document.getElementById('modal-shipping-cost-total').textContent = data.shipping_cost || '-';

            var pmLabels = { qris: 'QRIS', ewallet: 'E-Wallet', transfer: 'Transfer Bank' };
            document.getElementById('modal-payment-method').textContent = pmLabels[data.payment_method] || data.payment_method || '-';
            var psEl = document.getElementById('modal-payment-status');
            var psLabels = { paid: 'Lunas', verifying: 'Menunggu Verifikasi', unpaid: 'Belum Dibayar', expired: 'Kedaluwarsa', failed: 'Gagal' };
            psEl.textContent = psLabels[data.payment_status] || data.payment_status || '-';
            psEl.className = 'ivalue ' + (data.payment_status === 'paid' ? 'paid' : 'unpaid');
            document.getElementById('modal-payment-amount').textContent = data.payment_amount
                ? data.payment_amount + (data.payment_unique_code ? ' (kode unik ' + data.payment_unique_code + ')' : '')
                : '-';
            document.getElementById('modal-payment-expiry').textContent = data.payment_expired_at || '-';

            var proofBox = document.getElementById('modal-proof-box');
            proofBox.textContent = '';
            if (data.payment_proof_url) {
                var a = document.createElement('a');
                a.href = data.payment_proof_url; a.target = '_blank'; a.rel = 'noopener';
                var img = document.createElement('img');
                img.src = data.payment_proof_url; img.alt = 'Bukti bayar';
                a.appendChild(img);
                var cap = document.createElement('span');
                cap.textContent = 'Diunggah ' + (data.payment_proof_uploaded_at || '') + ' · klik untuk perbesar';
                proofBox.appendChild(a); proofBox.appendChild(cap);
            } else {
                var empty = document.createElement('span');
                empty.textContent = data.payment_status === 'paid'
                    ? 'Dikonfirmasi lunas' + (data.payment_verified_at ? ' pada ' + data.payment_verified_at : '') + '.'
                    : 'Pembeli belum mengunggah bukti bayar.';
                proofBox.appendChild(empty);
            }
            var noteEl = document.getElementById('modal-payment-note');
            noteEl.textContent = data.payment_note ? 'Catatan: ' + data.payment_note : '';
            noteEl.style.display = data.payment_note ? 'block' : 'none';

            var verifyForm = document.getElementById('payment-verify-form');
            var canVerify = data.status !== 'cancelled' && data.payment_status !== 'paid';
            verifyForm.style.display = canVerify ? 'flex' : 'none';
            verifyForm.action = '/admin/pesanan/' + orderId + '/pembayaran' + window.location.search;
            document.getElementById('btn-verify-reject').style.display = data.payment_status === 'verifying' ? '' : 'none';
            document.getElementById('modal-voucher').textContent = data.voucher_code || '(Tidak ada)';

            var itemsHtml = (data.items || []).map(function(i) {
                // product_image tersimpan relatif ("images/...") -> absolutkan agar
                // tidak resolve ke /admin/images/... (penyebab gambar rusak)
                var src = i.image
                    ? (i.image.charAt(0) === '/' || i.image.indexOf('http') === 0 ? i.image : '/' + i.image)
                    : null;
                var imgHtml = src
                    ? '<img src="' + src + '" alt="' + i.title + '" onerror="this.outerHTML=\'<div class=&quot;order-item-img-ph&quot;>&#128230;</div>\'">'
                    : '<div class="order-item-img-ph">&#128230;</div>';
                return '<div class="order-item-card">' +
                    '<div class="order-item-img">' + imgHtml + '</div>' +
                    '<div class="order-item-details">' +
                        '<p class="order-item-title">' + i.title + '</p>' +
                        '<p class="order-item-variant">' + (i.variant || '-') + ' | Qty: ' + i.qty + '</p>' +
                    '</div>' +
                    '<div class="order-item-price-col">' +
                        '<div class="osubtotal">' + i.subtotal + '</div>' +
                        '<div class="oprice-qty">' + i.price + ' x ' + i.qty + '</div>' +
                    '</div>' +
                '</div>';
            }).join('');
            document.getElementById('modal-items-list').innerHTML = itemsHtml || '<p style="color:#8a6a72;font-size:0.85rem;">Tidak ada produk.</p>';

            document.getElementById('modal-subtotal').textContent = data.subtotal;
            document.getElementById('modal-discount').textContent = '- ' + data.discount;
            document.getElementById('modal-total').textContent = data.total;

            document.getElementById('order-status-select').value = data.status;
            document.getElementById('order-tracking-input').value = data.tracking_number || '';
            syncResiRequirement();
            document.getElementById('order-status-form').action = '/admin/pesanan/' + orderId;

            document.getElementById('order-modal-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        })
        .catch(function(err) {
            alert('Gagal memuat detail pesanan.');
            console.error(err);
        });
}

document.getElementById('btn-close-modal').addEventListener('click', closeModal);
document.getElementById('order-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
function closeModal() {
    document.getElementById('order-modal-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
</script>
@endsection
