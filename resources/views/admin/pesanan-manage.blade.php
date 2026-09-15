@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Proses pesanan masuk dan pantau pengiriman')

@section('content')

    @php
        // Ganti $pesanan dengan data asli dari controller (mis. Pesanan::latest()->get())
        $pesanan = $pesanan ?? [
            ['id' => '1042', 'no' => '#SD-260908-1042', 'pelanggan' => 'Alya Rahma', 'tanggal' => '08 Sep, 09:42', 'status' => 'Baru', 'total' => 'Rp 578.000'],
            ['id' => '1039', 'no' => '#SD-260908-1039', 'pelanggan' => 'Citra Lestari', 'tanggal' => '08 Sep, 08:16', 'status' => 'Diproses', 'total' => 'Rp 329.000'],
            ['id' => '1028', 'no' => '#SD-260907-1028', 'pelanggan' => 'Maya Sari', 'tanggal' => '07 Sep, 19:24', 'status' => 'Dikirim', 'total' => 'Rp 747.000'],
            ['id' => '1011', 'no' => '#SD-260907-1011', 'pelanggan' => 'Dewi Anjani', 'tanggal' => '07 Sep, 14:05', 'status' => 'Selesai', 'total' => 'Rp 249.000'],
        ];

        $statusStyle = [
            'Baru' => 'bg-rose-50 text-rose-500',
            'Diproses' => 'bg-sky-50 text-sky-600',
            'Dikirim' => 'bg-amber-50 text-amber-600',
            'Selesai' => 'bg-emerald-50 text-emerald-600',
        ];

        $filterTabs = $filterTabs ?? [
            ['label' => 'Semua', 'value' => 'semua', 'count' => 1248],
            ['label' => 'Baru', 'value' => 'baru', 'count' => 38],
            ['label' => 'Diproses', 'value' => 'diproses', 'count' => 64],
            ['label' => 'Dikirim', 'value' => 'dikirim', 'count' => 126],
            ['label' => 'Selesai', 'value' => 'selesai', 'count' => 1020],
        ];
        $activeFilter = request('status', 'semua');

        // Detail pesanan yang lagi dibuka (mis. dari route /admin/pesanan/{id})
        $detail = $detail ?? [
            'no' => '#SD-260908-1042',
            'terverifikasi' => true,
            'items' => [
                ['nama' => 'Luna Satin Pajama Set · Blush / M', 'qty' => 2, 'harga' => 'Rp 249.000'],
                ['nama' => 'Amora Lace Bralette · Black / M', 'qty' => 1, 'harga' => 'Rp 179.000'],
            ],
            'diskon' => ['kode' => 'DREAM10', 'nominal' => '− Rp 67.700'],
            'total' => 'Rp 609.300',
            'no_resi' => 'JNE0239847201',
            'status_kirim' => 'Siap dikirim',
        ];
    @endphp

    {{-- Filter tabs --}}
    <div class="flex items-center gap-2">
        @foreach ($filterTabs as $tab)
            <a href="{{ request()->fullUrlWithQuery(['status' => $tab['value']]) }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium transition
                      {{ $activeFilter === $tab['value'] ? 'bg-brand text-white' : 'bg-white text-ink-soft border border-black/10 hover:bg-black/[0.02]' }}">
                {{ $tab['label'] }} {{ $tab['count'] }}
            </a>
        @endforeach
    </div>

    {{-- Daftar pesanan --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5">
            <h2 class="font-semibold text-ink">Daftar pesanan masuk</h2>
            <button type="button" class="text-sm text-brand font-medium flex items-center gap-1">
                Filter tanggal <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </button>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-brand-light/40 text-ink-muted text-xs uppercase tracking-wide">
                    <th class="text-left font-medium px-6 py-3">No. Order</th>
                    <th class="text-left font-medium px-6 py-3">Pelanggan</th>
                    <th class="text-left font-medium px-6 py-3">Tanggal</th>
                    <th class="text-left font-medium px-6 py-3">Status</th>
                    <th class="text-left font-medium px-6 py-3">Total</th>
                    <th class="text-left font-medium px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($pesanan as $p)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-6 py-4 font-medium text-ink">{{ $p['no'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['pelanggan'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['tanggal'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusStyle[$p['status']] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $p['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-ink">{{ $p['total'] }}</td>
                        <td class="px-6 py-4">
                            <a href="#detail-{{ $p['id'] }}" class="text-brand font-medium text-sm hover:underline">Lihat detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-ink-muted">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Detail pesanan --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-ink">Detail pesanan {{ $detail['no'] }}</h2>
            @if ($detail['terverifikasi'])
                <span class="text-sm text-emerald-600 font-medium flex items-center gap-1.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i> Pembayaran terverifikasi
                </span>
            @endif
        </div>

        <div class="grid grid-cols-[1fr_280px] gap-8">
            {{-- Items --}}
            <div>
                <div class="divide-y divide-black/5">
                    @foreach ($detail['items'] as $item)
                        <div class="flex items-center justify-between py-2.5 text-sm">
                            <span class="text-ink-soft">{{ $item['nama'] }}</span>
                            <span class="text-ink-soft">{{ $item['qty'] }} × {{ $item['harga'] }}</span>
                        </div>
                    @endforeach
                    @if (!empty($detail['diskon']))
                        <div class="flex items-center justify-between py-2.5 text-sm">
                            <span class="text-ink-soft">Diskon {{ $detail['diskon']['kode'] }}</span>
                            <span class="text-rose-500">{{ $detail['diskon']['nominal'] }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex items-center justify-between pt-4 mt-1 border-t border-black/5">
                    <span class="font-medium text-ink">Total</span>
                    <span class="text-lg font-bold text-brand">{{ $detail['total'] }}</span>
                </div>
            </div>

            {{-- Status pengiriman form --}}
            <form method="POST" action="{{ Route::has('pesanan.update-status') ? route('pesanan.update-status', $detail['no']) : '#' }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-ink mb-1.5">Status pengiriman</label>
                    <select name="status_kirim" class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                        <option {{ $detail['status_kirim'] === 'Siap dikirim' ? 'selected' : '' }}>Siap dikirim</option>
                        <option {{ $detail['status_kirim'] === 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option {{ $detail['status_kirim'] === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink mb-1.5">Nomor resi</label>
                    <input type="text" name="no_resi" value="{{ $detail['no_resi'] }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                </div>
                <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Perbarui status
                </button>
            </form>
        </div>
    </div>

@endsection