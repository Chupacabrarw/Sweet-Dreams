@extends('admin.layout')

@section('title', 'Manajemen Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Kenali pelanggan dan riwayat pembelian mereka')

@section('content')
<style>
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { text-align:left; font-size:0.72rem; text-transform:uppercase; color:#8a6a72; padding:0.75rem 0.5rem; border-bottom:1px solid #f1e4e7; }
    .admin-table td { padding:0.9rem 0.5rem; border-bottom:1px solid #f1e4e7; font-size:0.88rem; }
    .cust-cell { display:flex; align-items:center; gap:0.7rem; }
    .cust-avatar { width:36px; height:36px; border-radius:50%; background:#f2c9d3; display:flex; align-items:center; justify-content:center; font-weight:700; color:#a13655; font-size:0.85rem; }
    .status-pill { padding:0.25rem 0.7rem; border-radius:20px; font-size:0.76rem; font-weight:600; }
    .status-pill.vip { background:#fde2e6; color:#c53660; }
    .status-pill.lama { background:#e2ecfd; color:#2f5fc9; }
    .status-pill.baru { background:#e3f9ee; color:#1e9e64; }
    .action-link { color:#d44d6e; font-weight:600; font-size:0.85rem; cursor:pointer; }
    .history-card { display:flex; flex-direction:column; gap:0.3rem; padding:1rem; border:1px solid #f1e4e7; border-radius:12px; }
</style>

<div class="admin-stat-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="admin-stat-card">
        <div class="admin-stat-label">Pelanggan baru</div>
        <div class="admin-stat-value">{{ $totalBaru }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Pelanggan lama</div>
        <div class="admin-stat-value">{{ $totalLama }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Pelanggan VIP</div>
        <div class="admin-stat-value">{{ $totalVip }}</div>
    </div>
</div>

<div class="admin-card" style="margin-bottom:1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3 style="font-size:1rem;">Pelanggan terdaftar · {{ $customers->count() }}</h3>
        <form method="GET" action="{{ route('admin.customers') }}">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari pelanggan..." style="padding:0.5rem 0.9rem;border:1px solid #e5dde0;border-radius:10px;font-size:0.85rem;">
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr><th>Pelanggan</th><th>Email</th><th>Pesanan</th><th>Total belanja</th><th>Segmen</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($customers as $c)
                <tr>
                    <td>
                        <div class="cust-cell">
                            <div class="cust-avatar">{{ strtoupper(substr($c['name'], 0, 1)) }}</div>
                            <span>{{ $c['name'] }}</span>
                        </div>
                    </td>
                    <td>{{ $c['email'] }}</td>
                    <td>{{ $c['orders_count'] }}</td>
                    <td>{{ $c['total_spent'] }}</td>
                    <td><span class="status-pill {{ $c['segment_class'] }}">{{ $c['segment_label'] }}</span></td>
                    <td><span class="action-link" onclick="loadCustomerHistory({{ $c['id'] }})">Lihat riwayat</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="admin-card" id="history-box" style="display:none;">
    <h3 id="history-title" style="font-size:1rem; margin-bottom:1rem;"></h3>
    <div id="history-list" style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;"></div>
</div>

<script>
    function loadCustomerHistory(id) {
        fetch(`/admin/pelanggan/${id}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                document.getElementById('history-title').textContent = `Riwayat pembelian · ${data.name}`;

                const list = document.getElementById('history-list');
                if (data.orders.length === 0) {
                    list.innerHTML = '<p style="color:#8a6a72;font-size:0.88rem;">Belum ada pesanan.</p>';
                } else {
                    list.innerHTML = data.orders.map(o => `
                        <div class="history-card">
                            <strong style="font-size:0.85rem;">#${o.order_number}</strong>
                            <span style="font-size:0.8rem;color:#8a6a72;">${o.date} · ${o.items_count} item</span>
                            <span style="color:#d44d6e;font-weight:700;">${o.total}</span>
                        </div>
                    `).join('');
                }

                document.getElementById('history-box').style.display = 'block';
                document.getElementById('history-box').scrollIntoView({ behavior: 'smooth' });
            });
    }
</script>
@endsection