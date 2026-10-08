<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd   = now()->subMonth()->endOfMonth();

        // ── Stat Cards ──────────────────────────────────────────────
        $salesToday      = Order::query()->countedAsSale()->whereDate('created_at', $today)->sum('total');
        $salesThisMonth  = Order::query()->countedAsSale()->whereBetween('created_at', [$thisMonthStart, now()])->sum('total');
        $salesLastMonth  = Order::query()->countedAsSale()->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('total');
        $totalOrders     = Order::query()->countedAsSale()->count();
        $totalRevenue    = Order::query()->countedAsSale()->sum('total');
        $totalCustomers  = User::where('role', 'customer')->count();

        // ── Month-over-Month Growth ─────────────────────────────────
        $momGrowth = $salesLastMonth > 0
            ? round((($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100, 1)
            : ($salesThisMonth > 0 ? 100 : 0);

        // ── Average Order Value ─────────────────────────────────────
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        // ── Orders this month vs last month ─────────────────────────
        $ordersThisMonth = Order::query()->countedAsSale()->whereBetween('created_at', [$thisMonthStart, now()])->count();
        $ordersLastMonth = Order::query()->countedAsSale()->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $orderGrowth     = $ordersLastMonth > 0
            ? round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100, 1)
            : ($ordersThisMonth > 0 ? 100 : 0);

        // ── Status Counts ───────────────────────────────────────────
        $statusCounts = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
        ];

        // ── Sales Trend (12 days) ───────────────────────────────────
        $salesTrend = collect(range(11, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->startOfDay();
            return [
                'label' => $date->format('d M'),
                'total' => Order::query()->countedAsSale()->whereDate('created_at', $date)->sum('total'),
            ];
        });

        // ── Top Products ────────────────────────────────────────────
        $topProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.status', '!=', 'cancelled')
            ->selectRaw('order_items.product_title, order_items.product_image, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('order_items.product_title', 'order_items.product_image')
            ->orderByDesc('total_qty')
            ->take(4)
            ->get();

        // ── Payment Method Distribution ─────────────────────────────
        $paymentMix = Order::query()->countedAsSale()->selectRaw('payment_method, COUNT(*) as cnt')
            ->groupBy('payment_method')
            ->pluck('cnt', 'payment_method');

        // ── Best Day of Week (last 30 days) ────────────────────────
        $last30 = now()->subDays(29)->startOfDay();
        $ordersByDay = Order::query()->countedAsSale()->where('created_at', '>=', $last30)
            ->get()
            ->groupBy(fn($o) => $o->created_at->dayOfWeek);
        $dayNames    = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $bestDayIdx  = $ordersByDay->map->count()->sortDesc()->keys()->first();
        $bestDay     = $bestDayIdx !== null ? $dayNames[$bestDayIdx] : '-';

        // ── Rule-Based AI Insights ──────────────────────────────────
        $insights = $this->generateInsights(
            momGrowth:       $momGrowth,
            avgOrderValue:   $avgOrderValue,
            pendingOrders:   $statusCounts['pending'],
            salesThisMonth:  $salesThisMonth,
            salesLastMonth:  $salesLastMonth,
            ordersThisMonth: $ordersThisMonth,
            topProduct:      $topProducts->first()?->product_title ?? null,
            paymentMix:      $paymentMix,
            bestDay:         $bestDay,
            totalCustomers:  $totalCustomers,
        );

        return view('admin.dashboard', [
            'salesToday'      => $salesToday,
            'salesThisMonth'  => $salesThisMonth,
            'salesLastMonth'  => $salesLastMonth,
            'totalOrders'     => $totalOrders,
            'totalRevenue'    => $totalRevenue,
            'totalCustomers'  => $totalCustomers,
            'momGrowth'       => $momGrowth,
            'avgOrderValue'   => $avgOrderValue,
            'orderGrowth'     => $orderGrowth,
            'ordersThisMonth' => $ordersThisMonth,
            'statusCounts'    => $statusCounts,
            'salesTrend'      => $salesTrend,
            'topProducts'     => $topProducts,
            'paymentMix'      => $paymentMix,
            'bestDay'         => $bestDay,
            'insights'        => $insights,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // Rule-based AI Insight Generator
    // ──────────────────────────────────────────────────────────────────
    private function generateInsights(
        float  $momGrowth,
        int    $avgOrderValue,
        int    $pendingOrders,
        int    $salesThisMonth,
        int    $salesLastMonth,
        int    $ordersThisMonth,
        ?string $topProduct,
        $paymentMix,
        string $bestDay,
        int    $totalCustomers,
    ): array {
        $insights = [];

        // 1. Revenue Growth / Decline
        if ($momGrowth >= 20) {
            $insights[] = [
                'type'  => 'positive',
                'icon'  => 'trending-up',
                'title' => 'Pertumbuhan Pendapatan Luar Biasa! 🎉',
                'body'  => "Pendapatan bulan ini tumbuh <strong>+{$momGrowth}%</strong> dibanding bulan lalu. Momentum ini sangat baik — pertimbangkan untuk menambah stok produk terlaris dan meningkatkan anggaran iklan.",
            ];
        } elseif ($momGrowth >= 5) {
            $insights[] = [
                'type'  => 'positive',
                'icon'  => 'trending-up',
                'title' => 'Tren Pendapatan Positif',
                'body'  => "Pendapatan tumbuh <strong>+{$momGrowth}%</strong> dari bulan lalu. Pertahankan strategi saat ini dan fokus pada retensi pelanggan untuk memaksimalkan CLV.",
            ];
        } elseif ($momGrowth < 0) {
            $insights[] = [
                'type'  => 'warning',
                'icon'  => 'trending-down',
                'title' => 'Pendapatan Menurun',
                'body'  => "Pendapatan turun <strong>{$momGrowth}%</strong> dibanding bulan lalu. Pertimbangkan kampanye flash sale, voucher eksklusif, atau retargeting iklan untuk mendongkrak konversi.",
            ];
        } else {
            $insights[] = [
                'type'  => 'neutral',
                'icon'  => 'bar-chart-2',
                'title' => 'Mulai Catat Penjualan Pertama',
                'body'  => "Belum ada transaksi bulan ini. Aktifkan promosi pertama Anda — coba buat <strong>voucher selamat datang</strong> (misal: SWEETDREAM10) dan bagikan ke calon pelanggan via media sosial.",
            ];
        }

        // 2. Pending Orders
        if ($pendingOrders > 10) {
            $insights[] = [
                'type'  => 'warning',
                'icon'  => 'clock',
                'title' => 'Antrian Pesanan Perlu Perhatian',
                'body'  => "Ada <strong>{$pendingOrders} pesanan</strong> yang belum diproses. Penanganan cepat meningkatkan kepuasan pelanggan dan mempercepat perputaran kas. Segera proses hari ini!",
            ];
        } elseif ($pendingOrders > 0) {
            $insights[] = [
                'type'  => 'neutral',
                'icon'  => 'package',
                'title' => 'Ada Pesanan Menunggu',
                'body'  => "<strong>{$pendingOrders} pesanan</strong> dalam status pending. Pastikan diproses segera agar pengiriman tepat waktu.",
            ];
        } else {
            $insights[] = [
                'type'  => 'tip',
                'icon'  => 'package',
                'title' => 'Tips: Kecepatan Proses Pesanan = Rating Bintang 5',
                'body'  => "Toko dengan waktu proses pesanan <strong>di bawah 12 jam</strong> mendapatkan ulasan bintang 5 hingga 3× lebih banyak. Atur notifikasi pesanan baru agar bisa merespons secepat mungkin.",
            ];
        }

        // 3. Average Order Value
        if ($avgOrderValue > 300000) {
            $insights[] = [
                'type'  => 'positive',
                'icon'  => 'star',
                'title' => 'AOV Tinggi — Segmen Pelanggan Premium',
                'body'  => "Rata-rata nilai pesanan <strong>Rp " . number_format($avgOrderValue, 0, ',', '.') . "</strong> menunjukkan pelanggan menyukai produk premium. Perkuat lini produk high-end dan tambahkan program eksklusif member.",
            ];
        } elseif ($avgOrderValue > 0 && $avgOrderValue < 150000) {
            $insights[] = [
                'type'  => 'neutral',
                'icon'  => 'shopping-bag',
                'title' => 'Peluang: Tingkatkan Nilai Belanja per Transaksi',
                'body'  => "AOV saat ini <strong>Rp " . number_format($avgOrderValue, 0, ',', '.') . "</strong>. Coba terapkan strategi <strong>minimum pembelian untuk gratis ongkir</strong> atau rekomendasi produk 'sering dibeli bersama' di halaman produk.",
            ];
        } else {
            $insights[] = [
                'type'  => 'tip',
                'icon'  => 'gift',
                'title' => 'Strategi: Gratis Ongkir = Konversi Naik 40%',
                'body'  => "Terapkan <strong>gratis ongkir minimal pembelian</strong> (misal: gratis ongkir untuk belanja di atas Rp 200.000). Ini terbukti meningkatkan Average Order Value rata-rata <strong>25–40%</strong> di industri fashion.",
            ];
        }

        // 4. Best Sales Day
        if ($bestDay !== '-') {
            $insights[] = [
                'type'  => 'tip',
                'icon'  => 'calendar',
                'title' => "Hari Terbaik untuk Promo: {$bestDay}",
                'body'  => "Data menunjukkan hari <strong>{$bestDay}</strong> adalah puncak transaksi 30 hari terakhir. Jadwalkan flash sale, email blast, atau push notifikasi di hari ini untuk ROI maksimal.",
            ];
        } else {
            $insights[] = [
                'type'  => 'tip',
                'icon'  => 'calendar',
                'title' => 'Waktu Emas Posting Promo Toko Fashion',
                'body'  => "Riset industri fashion Indonesia: <strong>Jumat–Minggu pukul 19.00–22.00</strong> adalah waktu paling aktif belanja online. Jadwalkan konten dan flash sale Anda di jam-jam tersebut untuk jangkauan maksimal.",
            ];
        }

        // 5. Top Product recommendation
        if ($topProduct) {
            $insights[] = [
                'type'  => 'tip',
                'icon'  => 'award',
                'title' => 'Produk Unggulan Teridentifikasi',
                'body'  => "<strong>\"{$topProduct}\"</strong> adalah produk terlaris Anda. Pertimbangkan membuat <strong>bundle eksklusif</strong>, menambah stok, atau merilis varian baru berdasarkan produk ini untuk memaksimalkan revenue.",
            ];
        } else {
            $insights[] = [
                'type'  => 'tip',
                'icon'  => 'camera',
                'title' => 'Foto Produk Berkualitas = Konversi +60%',
                'body'  => "Produk dengan <strong>minimal 4 foto berkualitas tinggi</strong> (detail tekstur, dipakai model, flat lay, close-up) terbukti meningkatkan konversi hingga <strong>60%</strong> dibanding produk dengan 1 foto saja.",
            ];
        }

        // 6. Customer base / conversion
        if ($totalCustomers > 0 && $ordersThisMonth > 0) {
            $convRate = round(($ordersThisMonth / max($totalCustomers, 1)) * 100, 1);
            if ($convRate < 10) {
                $insights[] = [
                    'type'  => 'warning',
                    'icon'  => 'users',
                    'title' => 'Tingkat Konversi Perlu Ditingkatkan',
                    'body'  => "Dari <strong>{$totalCustomers} pelanggan terdaftar</strong>, hanya {$convRate}% bertransaksi bulan ini. Aktifkan re-engagement via email reminder, notifikasi wishlist, atau voucher personal untuk pelanggan dormant.",
                ];
            } else {
                $insights[] = [
                    'type'  => 'positive',
                    'icon'  => 'users',
                    'title' => 'Konversi Pelanggan Sehat',
                    'body'  => "<strong>{$convRate}% pelanggan</strong> aktif bertransaksi bulan ini. Fokus pada program referral untuk memperluas basis pelanggan baru sambil mempertahankan yang lama.",
                ];
            }
        }

        return array_slice($insights, 0, 5); // maks 5 insight
    }
}