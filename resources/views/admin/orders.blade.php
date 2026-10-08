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
                        <td><span class="status-pill {{ $row['status'] }}">{{ $row['status_label'] }}</span></td>
                        <td><strong>{{ $row['total'] }}</strong></td>
                        <td>
                            <span class="action-link" onclick="loadOrderDetail('{{ $row['order_number'] }}', {{ $row['id'] }})">
                                Lihat Detail
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                <div class="info-row"><span class="ilabel">Referensi</span><span class="ivalue" id="modal-payment-ref">-</span></div>
                <div class="info-row"><span class="ilabel">Voucher</span><span class="ivalue" id="modal-voucher">-</span></div>
            </div>

            <div></div>
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
                    <label>Nomor Resi Pengiriman</label>
                    <input type="text" name="tracking_number" id="order-tracking-input" placeholder="Contoh: JNE0239847201">
                </div>
                <button type="submit" class="btn-update-status">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
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
            psEl.textContent = data.payment_status === 'paid' ? 'Lunas' : 'Belum Dibayar';
            psEl.className = 'ivalue ' + (data.payment_status === 'paid' ? 'paid' : 'unpaid');
            document.getElementById('modal-payment-ref').textContent = data.payment_reference || '(Tidak ada)';
            document.getElementById('modal-voucher').textContent = data.voucher_code || '(Tidak ada)';

            var itemsHtml = (data.items || []).map(function(i) {
                var imgHtml = i.image
                    ? '<img src="' + i.image + '" alt="' + i.title + '">'
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
