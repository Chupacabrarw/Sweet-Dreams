@extends('admin.layout')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Proses pesanan masuk dan pantau pengiriman')

@section('content')
<style>
    .filter-pills { display:flex; gap:0.6rem; margin-bottom:1.5rem; flex-wrap:wrap; }
    .filter-pill { padding:0.55rem 1.1rem; border-radius:10px; font-size:0.85rem; font-weight:600; background:#fff; border:1px solid #e5dde0; cursor:pointer; text-decoration:none; color:#3a2a2e; transition: all 0.2s; }
    .filter-pill:hover { border-color:#d44d6e; color:#d44d6e; }
    .filter-pill.active { background:#d44d6e; color:#fff; border-color:#d44d6e; }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { text-align:left; font-size:0.72rem; text-transform:uppercase; color:#8a6a72; padding:0.75rem 1rem; border-bottom:2px solid #f1e4e7; letter-spacing:0.05em; }
    .admin-table td { padding:1rem; border-bottom:1px solid #f8eff1; font-size:0.88rem; vertical-align:middle; }
    .admin-table tbody tr:hover { background:#fdf7f8; }

    .status-pill { padding:0.3rem 0.85rem; border-radius:20px; font-size:0.76rem; font-weight:700; display:inline-block; }
    .status-pill.pending    { background:#fde2e6; color:#c53660; }
    .status-pill.processing { background:#e2ecfd; color:#2f5fc9; }
    .status-pill.shipped    { background:#fff3d9; color:#b7791f; }
    .status-pill.completed  { background:#e3f9ee; color:#1e9e64; }
    .status-pill.cancelled  { background:#f1e4e7; color:#8a6a72; }

    .action-link { color:#d44d6e; font-weight:600; font-size:0.82rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.3rem; padding:0.35rem 0.8rem; border:1px solid #f4c6d0; border-radius:8px; transition: all 0.2s; }
    .action-link:hover { background:#d44d6e; color:#fff; border-color:#d44d6e; }

    /* MODAL */
    .order-modal-overlay { display:none; position:fixed; inset:0; background:rgba(30,10,20,0.5); backdrop-filter:blur(5px); z-index:9000; align-items:flex-start; justify-content:center; padding:2rem 1rem; overflow-y:auto; }
    .order-modal-overlay.open { display:flex; }
    .order-modal-card { background:#fff; border-radius:24px; width:100%; max-width:820px; box-shadow:0 24px 60px rgba(0,0,0,0.2); animation:popIn 0.3s cubic-bezier(0.16,1,0.3,1); overflow:hidden; margin:auto; }
    @keyframes popIn { from { transform:scale(0.94) translateY(16px); opacity:0; } to { transform:scale(1) translateY(0); opacity:1; } }

    .modal-header { background:linear-gradient(135deg,#3a2a2e 0%,#5a3a42 100%); padding:1.75rem 2rem; display:flex; align-items:center; justify-content:space-between; color:#fff; }
    .modal-header-left h2 { font-size:1.35rem; font-weight:700; margin:0 0 0.3rem 0; color:#fff; }
    .modal-header-left p { font-size:0.82rem; color:#d4b8c0; margin:0; }
    .modal-status-pill { padding:0.4rem 1rem; border-radius:50px; font-size:0.8rem; font-weight:700; display:inline-block; margin-top:0.4rem; }
    .btn-modal-close { width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.15); border:none; color:#fff; font-size:1.2rem; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background 0.2s; }
    .btn-modal-close:hover { background:rgba(255,255,255,0.3); }

    .modal-body { padding:1.75rem 2rem; display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
    .info-section { background:#fdf7f8; border:1px solid #f4dbe2; border-radius:16px; padding:1.25rem 1.5rem; }
    .info-section-title { font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#d44d6e; margin:0 0 1rem 0; }
    .info-row { display:flex; justify-content:space-between; align-items:flex-start; padding:0.45rem 0; border-bottom:1px solid #f4dbe2; font-size:0.85rem; gap:1rem; }
    .info-row:last-child { border-bottom:none; }
    .info-row .ilabel { color:#8a6a72; flex-shrink:0; }
    .info-row .ivalue { font-weight:600; color:#3a2a2e; text-align:right; line-height:1.5; }
    .info-row .ivalue.paid { color:#1e9e64; }
    .info-row .ivalue.unpaid { color:#c53660; }

    .modal-items-section { padding:0 2rem 1.5rem; }
    .items-section-title { font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#d44d6e; margin:0 0 1rem 0; }
    .order-item-card { display:flex; align-items:center; gap:1rem; padding:0.85rem 0; border-bottom:1px solid #f8eff1; }
    .order-item-card:last-child { border-bottom:none; }
    .order-item-img { width:52px; height:52px; border-radius:10px; overflow:hidden; border:1.5px solid #f4dbe2; background:#fdf7f8; flex-shrink:0; }
    .order-item-img img { width:100%; height:100%; object-fit:cover; display:block; }
    .order-item-img-ph { width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d4b8c0; font-size:1.2rem; }
    .order-item-details { flex:1; }
    .order-item-title { font-size:0.9rem; font-weight:700; color:#3a2a2e; margin:0 0 0.2rem 0; }
    .order-item-variant { font-size:0.78rem; color:#8a6a72; margin:0; }
    .order-item-price-col { text-align:right; flex-shrink:0; }
    .order-item-price-col .osubtotal { font-weight:700; color:#3a2a2e; font-size:0.9rem; }
    .order-item-price-col .oprice-qty { font-size:0.76rem; color:#8a6a72; margin-top:0.1rem; }

    .totals-section { padding:1rem 2rem 1.75rem; background:#fdf7f8; border-top:1px solid #f4dbe2; }
    .total-row { display:flex; justify-content:space-between; padding:0.4rem 0; font-size:0.88rem; color:#6a4a52; }
    .total-row .tval { font-weight:600; color:#3a2a2e; }
    .total-row .tval.green { color:#1e9e64; }
    .total-row.grand { padding-top:0.75rem; margin-top:0.25rem; border-top:1.5px solid #f4dbe2; }
    .total-row.grand span { font-size:1.1rem; font-weight:700; color:#3a2a2e; }
    .total-row.grand .tval { font-size:1.25rem; color:#d44d6e; }

    .modal-form-section { padding:1.5rem 2rem; border-top:1px solid #f1e4e7; display:grid; grid-template-columns:1fr 1fr auto; gap:1rem; align-items:flex-end; background:#fff; }
    .mf-group label { display:block; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#8a6a72; margin-bottom:0.45rem; }
    .mf-group select, .mf-group input { width:100%; padding:0.65rem 0.9rem; border:1.5px solid #e8d0d6; border-radius:10px; font-size:0.88rem; color:#3a2a2e; background:#fff; outline:none; transition:border-color 0.2s; }
    .mf-group select:focus, .mf-group input:focus { border-color:#d44d6e; }
    .btn-update-status { height:44px; padding:0 1.5rem; background:linear-gradient(135deg,#e87b94 0%,#d44d6e 100%); color:#fff; border:none; border-radius:10px; font-size:0.88rem; font-weight:700; cursor:pointer; white-space:nowrap; box-shadow:0 4px 14px rgba(212,77,110,0.3); transition:all 0.2s; }
    .btn-update-status:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(212,77,110,0.4); }

    @media (max-width:640px) { .modal-body { grid-template-columns:1fr; } .modal-form-section { grid-template-columns:1fr; } .modal-header,.modal-body,.modal-items-section,.modal-form-section { padding-left:1.25rem; padding-right:1.25rem; } }
</style>

@if(session('success'))
    <div style="background:#e3f9ee;color:#1e9e64;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;">
        {{ session('success') }}
    </div>
@endif

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
