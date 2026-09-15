@extends('layouts.admin')

@section('title', 'Promo & Voucher')
@section('page-title', 'Promo & Voucher')
@section('page-subtitle', 'Kelola kode diskon dan kampanye flash sale')

@section('content')

    @php
        $voucherAktif = $voucherAktif ?? 12;
        $penukaranBulanIni = $penukaranBulanIni ?? 846;
        $nilaiDiskon = $nilaiDiskon ?? 'Rp 18,4 jt';
        $konversiPromo = $konversiPromo ?? '14,2%';

        // Ganti $promo dengan data asli dari controller (mis. Voucher::latest()->get())
        $promo = $promo ?? [
            ['kode' => 'DREAM10', 'diskon' => '10%', 'ketentuan' => 'Min. Rp 300.000', 'periode' => '01–30 Sep 2026', 'status' => 'Aktif'],
            ['kode' => 'PAYDAY50', 'diskon' => 'Rp 50.000', 'ketentuan' => 'Min. Rp 500.000', 'periode' => '25–30 Sep 2026', 'status' => 'Terjadwal'],
            ['kode' => 'FLASH20', 'diskon' => '20%', 'ketentuan' => 'Lingerie pilihan', 'periode' => '08 Sep, 18–22', 'status' => 'Aktif'],
            ['kode' => 'WELCOME15', 'diskon' => '15%', 'ketentuan' => 'Pengguna baru', 'periode' => 'Tanpa batas', 'status' => 'Aktif'],
        ];

        $statusStyle = [
            'Aktif' => 'bg-emerald-50 text-emerald-600',
            'Terjadwal' => 'bg-sky-50 text-sky-600',
            'Berakhir' => 'bg-gray-100 text-gray-500',
        ];
    @endphp

    {{-- Stat cards --}}
    <div class="grid grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Voucher aktif</div>
            <div class="text-2xl font-bold text-ink">{{ $voucherAktif }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Penukaran bulan ini</div>
            <div class="text-2xl font-bold text-ink">{{ number_format($penukaranBulanIni, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Nilai diskon</div>
            <div class="text-2xl font-bold text-ink">{{ $nilaiDiskon }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Konversi promo</div>
            <div class="text-2xl font-bold text-ink">{{ $konversiPromo }}</div>
        </div>
    </div>

    {{-- Daftar promo --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5">
            <h2 class="font-semibold text-ink">Daftar promo</h2>
            <button type="button" onclick="document.getElementById('voucher-form').scrollIntoView({behavior:'smooth'})"
                    class="flex items-center gap-2 bg-brand hover:bg-brand-dark text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Buat voucher
            </button>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-brand-light/40 text-ink-muted text-xs uppercase tracking-wide">
                    <th class="text-left font-medium px-6 py-3">Kode</th>
                    <th class="text-left font-medium px-6 py-3">Diskon</th>
                    <th class="text-left font-medium px-6 py-3">Ketentuan</th>
                    <th class="text-left font-medium px-6 py-3">Periode</th>
                    <th class="text-left font-medium px-6 py-3">Status</th>
                    <th class="text-left font-medium px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($promo as $p)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-6 py-4 font-semibold text-brand">{{ $p['kode'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['diskon'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['ketentuan'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['periode'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusStyle[$p['status']] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $p['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-sm">
                                <a href="#" class="text-brand font-medium hover:underline">Edit</a>
                                <span class="text-ink-muted">·</span>
                                <button type="button" class="text-ink-muted hover:text-ink">
                                    <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-ink-muted">Belum ada promo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form buat voucher --}}
    <div id="voucher-form" class="bg-white rounded-2xl border border-black/5 p-6">
        <h2 class="font-semibold text-ink mb-5">Buat voucher baru</h2>

        <form method="POST" action="{{ Route::has('promo.store') ? route('promo.store') : '#' }}" class="grid grid-cols-4 gap-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Kode voucher</label>
                <input type="text" name="kode" value="{{ old('kode') }}" placeholder="SEPTEMBER25"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Jenis diskon</label>
                <select name="jenis_diskon" class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    <option value="persentase">Persentase</option>
                    <option value="nominal">Nominal (Rp)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Nilai diskon</label>
                <input type="text" name="nilai_diskon" value="{{ old('nilai_diskon') }}" placeholder="25%"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Minimum belanja</label>
                <input type="text" name="min_belanja" value="{{ old('min_belanja') }}" placeholder="Rp 400.000"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>

            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Tanggal mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Tanggal berakhir</label>
                <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Batas penggunaan</label>
                <input type="text" name="batas_penggunaan" value="{{ old('batas_penggunaan') }}" placeholder="500 kali"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Maks. diskon</label>
                <input type="text" name="maks_diskon" value="{{ old('maks_diskon') }}" placeholder="Rp 100.000"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>

            <div class="col-span-4 flex items-center gap-3 pt-1">
                <button type="submit" class="bg-brand hover:bg-brand-dark text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                    Terbitkan voucher
                </button>
                <button type="submit" name="draft" value="1" class="border border-black/10 text-ink-soft text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-black/[0.02] transition">
                    Simpan draft
                </button>
            </div>
        </form>
    </div>

@endsection