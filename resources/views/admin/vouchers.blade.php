@extends('admin.layout')

@section('title', 'Promo & Voucher')
@section('page-title', 'Promo & Voucher')
@section('page-subtitle', 'Kelola kode diskon dan kampanye flash sale')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/vouchers.css')
@endpush

<div class="admin-stat-grid">
    <div class="admin-stat-card"><div class="admin-stat-label">Voucher aktif</div><div class="admin-stat-value">{{ $totalActive }}</div></div>
    <div class="admin-stat-card"><div class="admin-stat-label">Penukaran total</div><div class="admin-stat-value">{{ $totalRedeemed }}</div></div>
    <div class="admin-stat-card"><div class="admin-stat-label">Nilai diskon terealisasi</div><div class="admin-stat-value">Rp {{ number_format($totalDiscountValue, 0, ',', '.') }}</div></div>
    <div class="admin-stat-card"><div class="admin-stat-label">Total voucher</div><div class="admin-stat-value">{{ $vouchers->count() }}</div></div>
</div>

<div class="admin-card" style="margin-bottom:1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3 style="font-size:1rem;">Daftar promo</h3>
        <button type="button" class="btn-pink" style="background:#d44d6e;color:#fff;border:none;border-radius:10px;padding:0.6rem 1.1rem;font-weight:600;cursor:pointer;" onclick="document.getElementById('voucher-form-card').scrollIntoView({behavior:'smooth'})">+ Buat voucher</button>
    </div>

    @if($vouchers->isEmpty())
        <p style="color:#8a6a72; font-size:0.88rem;">Belum ada voucher.</p>
    @else
        <table class="admin-table">
            <thead><tr><th>Kode</th><th>Diskon</th><th>Ketentuan</th><th>Periode</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($vouchers as $v)
                    <tr>
                        <td><strong style="color:#d44d6e;">{{ $v->code }}</strong></td>
                        <td>{{ $v->discount_type === 'percentage' ? $v->discount_value . '%' : 'Rp ' . number_format($v->discount_value, 0, ',', '.') }}</td>
                        <td>Min. Rp {{ number_format($v->min_purchase, 0, ',', '.') }}</td>
                        <td>{{ $v->starts_at->format('d M') }} - {{ $v->ends_at->format('d M Y') }}</td>
                        <td><span class="status-pill {{ $v->statusClass() }}">{{ $v->statusLabel() }}</span></td>
                        <td>
                            <span class="action-link" onclick='editVoucher(@json($v))'>Edit</span>
                            <form method="POST" action="{{ route('admin.vouchers.destroy', $v->id) }}" style="display:inline;" onsubmit="return confirm('Hapus voucher ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-link" style="background:none;border:none;padding:0;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="admin-card" id="voucher-form-card">
    <h3 id="voucher-form-title" style="font-size:1rem; margin-bottom:1.2rem;">Buat voucher baru</h3>

    <form method="POST" action="{{ route('admin.vouchers.store') }}" id="voucher-form">
        @csrf
        <input type="hidden" name="_method" id="voucher-form-method" value="POST">

        <div class="form-row">
            <div class="form-group">
                <label>Kode voucher</label>
                <input type="text" name="code" id="v-code" required style="text-transform:uppercase;">
            </div>
            <div class="form-group">
                <label>Jenis diskon</label>
                <select name="discount_type" id="v-type">
                    <option value="percentage">Persentase</option>
                    <option value="fixed">Nominal (Rp)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nilai diskon</label>
                <input type="number" name="discount_value" id="v-value" required>
            </div>
            <div class="form-group">
                <label>Minimum belanja</label>
                <!-- Tambahkan required di sini -->
                <input type="number" name="min_purchase" id="v-min" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tanggal mulai</label>
                <input type="date" name="starts_at" id="v-start" required>
            </div>
            <div class="form-group">
                <label>Tanggal berakhir</label>
                <input type="date" name="ends_at" id="v-end" required>
            </div>
            <div class="form-group">
                <label>Batas penggunaan</label>
                <!-- Ini dibiarkan kosong tanpa required karena kamu bilang kecuali batas penggunaan -->
                <input type="number" name="usage_limit" id="v-limit" placeholder="Kosongkan = tanpa batas">
            </div>
            <div class="form-group">
                <label>Maks. diskon</label>
                <!-- Tambahkan required di sini -->
                <input type="number" name="max_discount" id="v-max" placeholder="Khusus persentase">
            </div>
        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                <input type="checkbox" name="is_active" id="v-active" value="1" checked style="width: auto;">
                <label for="v-active" style="margin-bottom: 0;">Voucher Aktif</label>
            </div>
        </div>

        <button type="submit" class="btn-pink" style="background:#d44d6e;color:#fff;border:none;border-radius:10px;padding:0.7rem 1.2rem;font-weight:600;cursor:pointer;">Simpan Voucher</button>
        <button type="button" onclick="resetVoucherForm()" style="background:#fff;border:1px solid #e5dde0;border-radius:10px;padding:0.7rem 1.2rem;font-weight:600;cursor:pointer;">Batal</button>
    </form>
</div>

<script>
    const voucherForm = document.getElementById('voucher-form');

    function resetVoucherForm() {
        voucherForm.reset();
        voucherForm.action = "{{ route('admin.vouchers.store') }}";
        document.getElementById('voucher-form-method').value = 'POST';
        document.getElementById('voucher-form-title').textContent = 'Buat voucher baru';
        document.getElementById('v-code').disabled = false;
        // Tambahkan baris ini:
        document.getElementById('v-active').checked = true;
        
    }

    function editVoucher(v) {
        voucherForm.action = `/admin/promo/${v.id}`;
        document.getElementById('voucher-form-method').value = 'PUT';
        document.getElementById('voucher-form-title').textContent = `Edit voucher: ${v.code}`;

        document.getElementById('v-code').value = v.code;
        document.getElementById('v-code').disabled = true;
        document.getElementById('v-type').value = v.discount_type;
        document.getElementById('v-value').value = v.discount_value;
        document.getElementById('v-min').value = v.min_purchase;
        // Kita potong 10 karakter pertama saja (YYYY-MM-DD) agar bisa dibaca oleh form
        document.getElementById('v-start').value = v.starts_at ? v.starts_at.substring(0, 10) : '';
        document.getElementById('v-end').value = v.ends_at ? v.ends_at.substring(0, 10) : '';
        document.getElementById('v-limit').value = v.usage_limit || '';
        document.getElementById('v-max').value = v.max_discount || '';

        document.getElementById('voucher-form-card').scrollIntoView({ behavior: 'smooth' });
        document.getElementById('v-max').value = v.max_discount || '';
        // Tambahkan baris ini:
        document.getElementById('v-active').checked = v.is_active;

        // Mengunci input max_discount jika jenis diskon adalah Nominal
    document.getElementById('v-type').addEventListener('change', function() {
        const maxInput = document.getElementById('v-max');
        if (this.value === 'fixed') {
            maxInput.value = '';
            maxInput.disabled = true;
            maxInput.placeholder = 'Tidak perlu diisi';
        } else {
            maxInput.disabled = false;
            maxInput.placeholder = 'Khusus persentase';
        }
    });

    // Panggil event saat editVoucher agar status kekuncinya menyesuaikan
    // (Kamu bisa taruh baris ini di bagian paling akhir dalam function editVoucher)
    document.getElementById('v-type').dispatchEvent(new Event('change'));
    }
</script>
@endsection