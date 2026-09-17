<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();

        $salesToday = Order::whereDate('created_at', $today)->sum('total');
        $salesThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');

        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        // Tren penjualan 12 hari terakhir
        $salesTrend = collect(range(11, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->startOfDay();
            return [
                'label' => $date->format('d'),
                'total' => Order::whereDate('created_at', $date)->sum('total'),
            ];
        });

        // Produk terlaris (dari order_items asli, bukan dummy)
        $topProducts = OrderItem::selectRaw('product_title, product_image, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_title', 'product_image')
            ->orderByDesc('total_qty')
            ->take(4)
            ->get();

        return view('admin.dashboard', [
            'salesToday' => $salesToday,
            'salesThisMonth' => $salesThisMonth,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'statusCounts' => $statusCounts,
            'salesTrend' => $salesTrend,
            'topProducts' => $topProducts,
        ]);
    }
}