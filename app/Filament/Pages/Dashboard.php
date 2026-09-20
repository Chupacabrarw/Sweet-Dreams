<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -2;

    public function getViewData(): array
    {
        $today          = now()->startOfDay();
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd   = now()->subMonth()->endOfMonth();

        $salesToday     = Order::whereDate('created_at', $today)->sum('total');
        $salesThisMonth = Order::whereBetween('created_at', [$thisMonthStart, now()])->sum('total');
        $salesLastMonth = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('total');
        $totalOrders    = Order::count();
        $totalRevenue   = Order::sum('total');
        $totalCustomers = User::where('role', 'customer')->count();

        $momGrowth = $salesLastMonth > 0
            ? round((($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100, 1)
            : ($salesThisMonth > 0 ? 100 : 0);

        $avgOrderValue   = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;
        $ordersThisMonth = Order::whereBetween('created_at', [$thisMonthStart, now()])->count();
        $ordersLastMonth = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $orderGrowth     = $ordersLastMonth > 0
            ? round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100, 1)
            : ($ordersThisMonth > 0 ? 100 : 0);

        $statusCounts = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
        ];

        $salesTrend = collect(range(11, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->startOfDay();
            return [
                'label' => $date->format('d M'),
                'total' => Order::whereDate('created_at', $date)->sum('total'),
            ];
        });

        $topProducts = OrderItem::selectRaw('product_title, product_image, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_title', 'product_image')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $paymentMix = Order::selectRaw('payment_method, COUNT(*) as cnt')
            ->groupBy('payment_method')
            ->pluck('cnt', 'payment_method');

        $last30      = now()->subDays(29)->startOfDay();
        $ordersByDay = Order::where('created_at', '>=', $last30)->get()
            ->groupBy(fn($o) => $o->created_at->dayOfWeek);
        $dayNames    = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $bestDayIdx  = $ordersByDay->map->count()->sortDesc()->keys()->first();
        $bestDay     = $bestDayIdx !== null ? $dayNames[$bestDayIdx] : '-';

        $insights = $this->generateInsights(
            $momGrowth, $avgOrderValue, $statusCounts['pending'],
            $salesThisMonth, $salesLastMonth, $ordersThisMonth,
            $topProducts->first()?->product_title ?? null,
            $paymentMix, $bestDay, $totalCustomers,
        );

        return compact(
            'salesToday', 'salesThisMonth', 'salesLastMonth',
            'totalOrders', 'totalRevenue', 'totalCustomers',
            'momGrowth', 'avgOrderValue', 'orderGrowth', 'ordersThisMonth',
            'statusCounts', 'salesTrend', 'topProducts', 'paymentMix',
            'bestDay', 'insights',
        );
    }

    private function generateInsights(
        float $momGrowth, int $avgOrderValue, int $pendingOrders,
        int $salesThisMonth, int $salesLastMonth, int $ordersThisMonth,
        ?string $topProduct, $paymentMix, string $bestDay, int $totalCustomers,
    ): array {
        $insights = [];

        if ($momGrowth >= 20) {
            $insights[] = ['type'=>'positive','icon'=>'trending-up','title'=>'Pertumbuhan Luar Biasa! 🎉',
                'body'=>"Pendapatan tumbuh <strong>+{$momGrowth}%</strong> bulan ini. Tambah stok & tingkatkan anggaran iklan!"];
        } elseif ($momGrowth >= 5) {
            $insights[] = ['type'=>'positive','icon'=>'trending-up','title'=>'Tren Pendapatan Positif',
                'body'=>"Pendapatan tumbuh <strong>+{$momGrowth}%</strong>. Fokus pada retensi pelanggan."];
        } elseif ($momGrowth < 0) {
            $insights[] = ['type'=>'warning','icon'=>'trending-down','title'=>'Pendapatan Menurun',
                'body'=>"Pendapatan turun <strong>{$momGrowth}%</strong>. Pertimbangkan flash sale atau voucher eksklusif."];
        } else {
            $insights[] = ['type'=>'neutral','icon'=>'bar-chart-2','title'=>'Mulai Catat Penjualan Pertama',
                'body'=>"Buat <strong>voucher selamat datang</strong> (misal: SWEETDREAM10) dan bagikan ke calon pelanggan."];
        }

        if ($pendingOrders > 10) {
            $insights[] = ['type'=>'warning','icon'=>'clock','title'=>'Antrian Pesanan Perlu Perhatian',
                'body'=>"Ada <strong>{$pendingOrders} pesanan</strong> belum diproses. Segera proses hari ini!"];
        } elseif ($pendingOrders > 0) {
            $insights[] = ['type'=>'neutral','icon'=>'package','title'=>'Ada Pesanan Menunggu',
                'body'=>"<strong>{$pendingOrders} pesanan</strong> dalam status pending. Pastikan diproses segera."];
        } else {
            $insights[] = ['type'=>'tip','icon'=>'package','title'=>'Tips: Proses Cepat = Bintang 5',
                'body'=>"Toko dengan waktu proses <strong>di bawah 12 jam</strong> mendapat ulasan bintang 5 hingga 3× lebih banyak."];
        }

        if ($avgOrderValue > 300000) {
            $insights[] = ['type'=>'positive','icon'=>'star','title'=>'Segmen Pelanggan Premium',
                'body'=>"AOV <strong>Rp ".number_format($avgOrderValue,0,',','.')."</strong> — perkuat lini high-end & program eksklusif member."];
        } else {
            $insights[] = ['type'=>'tip','icon'=>'gift','title'=>'Strategi: Gratis Ongkir = Konversi +40%',
                'body'=>"Terapkan <strong>gratis ongkir minimal pembelian</strong> Rp 200.000 untuk tingkatkan AOV rata-rata 25–40%."];
        }

        if ($bestDay !== '-') {
            $insights[] = ['type'=>'tip','icon'=>'calendar','title'=>"Hari Terbaik Promo: {$bestDay}",
                'body'=>"Hari <strong>{$bestDay}</strong> adalah puncak transaksi. Jadwalkan flash sale & email blast di hari ini."];
        } else {
            $insights[] = ['type'=>'tip','icon'=>'calendar','title'=>'Waktu Emas Posting Promo',
                'body'=>"<strong>Jumat–Minggu pukul 19.00–22.00</strong> adalah jam paling aktif belanja online fashion Indonesia."];
        }

        if ($topProduct) {
            $insights[] = ['type'=>'tip','icon'=>'award','title'=>'Produk Unggulan Teridentifikasi',
                'body'=>"<strong>\"$topProduct\"</strong> adalah terlaris. Buat bundle eksklusif atau rilis varian baru!"];
        } else {
            $insights[] = ['type'=>'tip','icon'=>'camera','title'=>'Foto Produk Berkualitas = +60% Konversi',
                'body'=>"Minimal <strong>4 foto berkualitas tinggi</strong> per produk terbukti meningkatkan konversi hingga 60%."];
        }

        if ($totalCustomers > 0 && $ordersThisMonth === 0) {
            $insights[] = ['type'=>'warning','icon'=>'users','title'=>"{$totalCustomers} Pelanggan Belum Bertransaksi",
                'body'=>"Kirim <strong>voucher eksklusif</strong> atau notifikasi flash sale untuk mengaktifkan mereka kembali."];
        } else {
            $insights[] = ['type'=>'tip','icon'=>'tag','title'=>'Optimalkan Program Voucher',
                'body'=>"Pelanggan yang pakai voucher memiliki kemungkinan <strong>repeat order 2× lebih tinggi</strong>."];
        }

        return array_slice($insights, 0, 6);
    }
}
