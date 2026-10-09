<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->get('start') ? Carbon::parse($request->get('start')) : now()->subDays(7)->startOfDay();
        $end = $request->get('end') ? Carbon::parse($request->get('end'))->endOfDay() : now()->endOfDay();

        $orders = Order::query()
            ->countedAsSale()
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $grossRevenue = $orders->sum(fn ($o) => $o->subtotal + $o->shipping_cost);
        $totalDiscount = $orders->sum('discount');
        $netRevenue = $orders->sum('total');
        $avgOrder = $orders->count() > 0 ? $netRevenue / $orders->count() : 0;

        $dailyChart = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayOrders = $orders->filter(fn ($o) => $o->created_at->isSameDay($date));
            $dailyChart->push([
                'label' => $date->format('d'),
                'total' => $dayOrders->sum('total'),
            ]);
        }

        $dailyRecap = $orders->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))
            ->map(function ($group, $date) {
                return [
                    'date' => Carbon::parse($date)->translatedFormat('d M Y'),
                    'orders' => $group->count(),
                    'gross' => $group->sum(fn ($o) => $o->subtotal + $o->shipping_cost),
                    'discount' => $group->sum('discount'),
                    'net' => $group->sum('total'),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('admin.reports', [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'grossRevenue' => $grossRevenue,
            'totalDiscount' => $totalDiscount,
            'netRevenue' => $netRevenue,
            'avgOrder' => $avgOrder,
            'dailyChart' => $dailyChart,
            'dailyRecap' => $dailyRecap,
        ]);
    }

    public function export(Request $request)
    {
        $start = $request->get('start') ? Carbon::parse($request->get('start')) : now()->subDays(7)->startOfDay();
        $end = $request->get('end') ? Carbon::parse($request->get('end'))->endOfDay() : now()->endOfDay();

        $orders = Order::query()
            ->countedAsSale()
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get(['order_number', 'created_at', 'subtotal', 'shipping_cost', 'discount', 'total', 'status', 'payment_status']);

        $filename = 'laporan-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel baca UTF-8
            fputcsv($out, ['No. Pesanan', 'Tanggal', 'Subtotal', 'Ongkir', 'Diskon', 'Total', 'Status', 'Pembayaran']);
            foreach ($orders as $order) {
                fputcsv($out, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->subtotal,
                    $order->shipping_cost,
                    $order->discount,
                    $order->total,
                    $order->status,
                    $order->payment_status,
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}