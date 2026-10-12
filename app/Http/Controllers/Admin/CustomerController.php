<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Catatan: ambang batas segmen ini masih patokan sementara, bisa disesuaikan nanti
    protected function segment(int $ordersCount, int $totalSpent, $createdAt): array
    {
        if ($totalSpent >= 2000000) {
            return ['label' => 'VIP', 'class' => 'vip'];
        }
        if ($ordersCount === 0 && $createdAt->diffInDays(now()) <= 30) {
            return ['label' => 'Baru', 'class' => 'baru'];
        }
        return ['label' => 'Lama', 'class' => 'lama'];
    }

    public function index(Request $request)
    {
        $search = $request->get('q');

        $base = fn () => User::where('role', 'customer')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
            ->withCount(['orders as orders_count' => fn ($query) => $query->countedAsSale()])
            ->withSum(['orders as total_spent' => fn ($query) => $query->countedAsSale()], 'total');

        // Total segmen dari agregat ringan (tetap akurat walau daftar di-paginate)
        $totals = ['Baru' => 0, 'Lama' => 0, 'VIP' => 0];
        foreach ($base()->get(['users.id', 'users.created_at']) as $u) {
            $totals[$this->segment($u->orders_count, (int) $u->total_spent, $u->created_at)['label']]++;
        }

        $customers = $base()->latest('id')
            ->paginate(20)
            ->withQueryString()
            ->through(function ($u) {
                $totalSpent = (int) $u->total_spent;
                $seg = $this->segment($u->orders_count, $totalSpent, $u->created_at);

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'orders_count' => $u->orders_count,
                    'total_spent' => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
                    'segment_label' => $seg['label'],
                    'segment_class' => $seg['class'],
                    'joined_days_ago' => $u->created_at->diffInDays(now()),
                ];
            });

        return view('admin.customers', [
            'customers' => $customers,
            'search' => $search,
            'totalBaru' => $totals['Baru'],
            'totalLama' => $totals['Lama'],
            'totalVip' => $totals['VIP'],
        ]);
    }

    public function show(User $customer)
    {
        $orders = $customer->orders()->with('items')->latest()->take(3)->get();

        return response()->json([
            'name' => $customer->name,
            'orders' => $orders->map(fn ($o) => [
                'order_number' => $o->order_number,
                'date' => $o->created_at->translatedFormat('d M Y'),
                'items_count' => $o->items->count(),
                'total' => 'Rp ' . number_format($o->total, 0, ',', '.'),
            ]),
        ]);
    }
}