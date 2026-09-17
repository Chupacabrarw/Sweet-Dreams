@extends('admin.layout')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Proses pesanan masuk dan pantau pengiriman')

@section('content')
<style>
    .filter-pills { display:flex; gap:0.6rem; margin-bottom:1.5rem; flex-wrap:wrap; }
    .filter-pill {
        padding:0.55rem 1.1rem; border-radius:10px; font-size:0.85rem; font-weight:600;
        background:#fff; border:1px solid #e5dde0; cursor:pointer;
    }
    .filter-pill.active { background:#d44d6e; color:#fff; border-color:#d44d6e; }
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { text-align:left; font-size:0.72rem; text-transform:uppercase; color:#8a6a72; padding:0.75rem 0.5rem; border-bottom:1px solid #f1e4e7; }
    .admin-table td { padding:0.9rem 0.5rem; border-bottom:1px solid #f1e4e7; font-size:0.88rem; }
    .status-pill { padding:0.25rem 0.7rem; border-radius:20px; font-size:0.76rem; font-weight:600; }
    .status-pill.pending { background:#fde2e6; color:#c53660; }
    .status-pill.processing { background:#e2ecfd; color:#2f5fc9; }
    .status-pill.shipped { background:#fff3d9; color:#b7791f; }
    .status-pill.completed { background:#e3f9ee; color:#1e9e64; }
    .status-pill.cancelled { background:#f1e4e7; color:#8a6a72; }
    .action-link { color:#d44d6e; font-weight:600; font-size:0.85rem; cursor:pointer; }

    .order-detail-box { display:none; margin-top:1.5rem; }
    .order-detail-grid { display:grid; grid-template-columns:1fr 300px; gap:2rem; }
    .order-item-row { display:flex; justify-content:space-between; padding:0.5rem 0; font-size:0.88rem; }
    .form-group label { display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; margin-top:1rem; }
    .form-group select, .form-group input { width:100%; padding:0.6rem 0.8rem; border:1px solid #e5dde0; border-radius:10px; font-size:0.88rem; }
</style>

@if(session('success'))
    <div style="background:#e3f9ee;color:#1e9e64;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;">
        {{ session('success') }}
    </div>
@endif

<div class="filter-pills">
    <a href="{{ route('admin.orders') }}" class="filter-pill {{ $activeFilter === 'all' ? 'active' : '' }}">Semua {{ $counts['all'] }}</a>
    <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="filter-pill {{ $activeFilter === 'pending' ? 'active' : '' }}">Baru {{ $counts['pending'] }}</a>
    <a href="{{ route('admin.orders', ['status' => 'processing']) }}" class="filter-pill {{ $activeFilter === 'processing' ? 'active' : '' }}">Diproses {{ $counts['processing'] }}</a>
    <a href="{{ route('admin.orders', ['status' => 'shipped']) }}" class="filter-pill {{ $activeFilter === 'shipped' ? 'active' : '' }}">Dikirim {{ $counts['shipped'] }}</a>
    <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="filter-pill {{ $activeFilter === 'completed' ? 'active' : '' }}">Selesai {{ $counts['completed'] }}</a>
</div>

<div class="admin-card">
    <h3 style="font-size:1rem; margin-bottom:1rem;">Daftar pesanan masuk</h3>

    @if($rows->isEmpty())
        <p style="color:#8a6a72; font-size:0.88rem;">Belum ada pesanan.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr><th>No. Order</th><th>Pelanggan</th><th>Tanggal</th><th>Status</th><th>Total</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>#{{ $row['order_number'] }}</td>
                        <td>{{ $row['customer'] }}</td>
                        <td>{{ $row['date'] }}</td>
                        <td><span class="status-pill {{ $row['status'] }}">{{ $row['status_label'] }}</span></td>
                        <td>{{ $row['total'] }}</td>
                        <td><span class="action-link" onclick="loadOrderDetail('{{ $row['order_number'] }}', {{ $row['id'] }})">Lihat detail</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="admin-card order-detail-box" id="order-detail-box">
    <h3 id="order-detail-title" style="font-size:1rem; margin-bottom:1rem;"></h3>
    <div class="order-detail-grid">
        <div id="order-detail-items"></div>
        <form method="POST" id="order-status-form">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Status pesanan</label>
                <select name="status" id="order-status-select">
                    <option value="pending">Baru</option>
                    <option value="processing">Diproses</option>
                    <option value="shipped">Dikirim</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nomor resi</label>
                <input type="text" name="tracking_number" id="order-tracking-input" placeholder="Contoh: JNE0239847201">
            </div>
            <button type="submit" class="btn-pink" style="margin-top:1.2rem; background:#d44d6e;color:#fff;border:none;border-radius:10px;padding:0.7rem 1.2rem;font-weight:600;cursor:pointer;">Perbarui status</button>
        </form>
    </div>
</div>

<script>
    function loadOrderDetail(orderNumber, orderId) {
        fetch(`/admin/pesanan/${orderId}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                document.getElementById('order-detail-title').textContent = `Detail pesanan #${data.order_number}`;

                const itemsHtml = data.items.map(i => `
                    <div class="order-item-row">
                        <span>${i.title} · ${i.variant}</span>
                        <span>${i.qty} × ${i.price}</span>
                    </div>
                `).join('') + `
                    <div class="order-item-row" style="border-top:1px solid #f1e4e7;padding-top:0.7rem;margin-top:0.5rem;">
                        <span>Subtotal</span><span>${data.subtotal}</span>
                    </div>
                    <div class="order-item-row"><span>Diskon</span><span>- ${data.discount}</span></div>
                    <div class="order-item-row" style="font-weight:700;"><span>Total</span><span>${data.total}</span></div>
                `;
                document.getElementById('order-detail-items').innerHTML = itemsHtml;

                document.getElementById('order-status-select').value = data.status;
                document.getElementById('order-tracking-input').value = data.tracking_number || '';
                document.getElementById('order-status-form').action = `/admin/pesanan/${orderId}`;

                document.getElementById('order-detail-box').style.display = 'block';
                document.getElementById('order-detail-box').scrollIntoView({ behavior: 'smooth' });
            });
    }
</script>
@endsection