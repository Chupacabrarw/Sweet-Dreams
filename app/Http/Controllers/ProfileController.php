<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();

         $user = [
            'name' => $authUser->name,
            'email' => $authUser->email,
            'phone' => 'Belum diisi',
            'birthdate' => 'Belum diisi',
            'birthdate_raw' => '',
            'city' => 'Belum diisi',
            'avatar' => !empty($authUser->avatar) ? $authUser->avatar : 'images/avatars/avatar-1.svg',
            'avatar_url' => $authUser->avatar_url,
        ];

                $stats = [
            'total_orders' => $authUser->orders()->count(),
            'in_delivery' => $authUser->orders()->where('status', 'shipped')->count(),
                        'wishlist_count' => $authUser->wishlists()->count(),
        ];

        $recentOrders = $authUser->orders()
            ->with('items')
            ->latest()
            ->take(2)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => '#' . $order->order_number,
                    'slug' => $order->order_number,
                    'title' => optional($order->items->first())->product_title ?? 'Produk',
                    'status' => $this->statusLabel($order->status),
                    'status_type' => $this->statusType($order->status),
                    'price' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                ];
            })
            ->toArray();

        $myOrders = $authUser->orders()
            ->with('items')
            ->latest()
            ->get()
            ->map(function ($order) {
                $firstItem = $order->items->first();
                $extraCount = $order->items->count() - 1;

                return [
                    'id' => '#' . $order->order_number,
                    'slug' => $order->order_number,
                    'date' => $order->created_at->copy()->setTimezone('Asia/Jakarta')->translatedFormat('d M Y'),
                    'status' => $this->statusLabel($order->status),
                    'status_type' => $this->statusType($order->status),
                    'can_cancel' => $order->status === 'pending' && $order->payment_status !== 'paid',
                    'title' => $firstItem?->product_title ?? 'Produk',
                    'variant' => ($firstItem ? "{$firstItem->color} · Size {$firstItem->size}" : '')
                        . ($extraCount > 0 ? " (+{$extraCount} produk lainnya)" : ''),
                    'qty' => $firstItem?->quantity ?? 0,
                    'price' => 'Rp ' . number_format($firstItem?->price ?? 0, 0, ',', '.'),
                    'total' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                    'image' => $firstItem?->product_image ?? 'images/alya-avatar.jpg',
                ];
            })
            ->toArray();

                $addresses = $authUser->addresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get()
            ->map(function ($addr) {
                return [
                    'id' => $addr->id,
                    'label' => $addr->label,
                    'name' => $addr->recipient_name,
                    'phone' => $addr->phone,
                    'address' => $addr->address,
                    'city' => $addr->city,
                    'city_id' => $addr->city_id,
                    'province' => $addr->province,
                    'province_id' => $addr->province_id,
                    'postal_code' => $addr->postal_code,
                    'is_primary' => $addr->is_primary,
                ];
            })
            ->toArray();
                    $wishlistItems = $authUser->wishlists()
            ->with('product')
            ->latest()
            ->get()
            ->map(function ($w) {
                return [
                    'slug' => $w->product->slug,
                    'title' => $w->product->title,
                    'price' => 'Rp ' . number_format($w->product->price, 0, ',', '.'),
                    'image' => $w->product->image,
                ];
            })->toArray();

        return view('profil', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'myOrders' => $myOrders,
            'addresses' => $addresses,
            'wishlistItems' => $wishlistItems,
        ]);
    }
        protected function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Sedang dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($status),
        };
    }

    protected function statusType(string $status): string
    {
        return match ($status) {
            'shipped' => 'shipping',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            default => 'pending',
        };
    }
        public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $request->user()->id,
            'avatar' => 'nullable|string|max:6000000',
        ]);

        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('avatar')) {
            $avatar = $request->input('avatar');
            $updateData['avatar'] = str_starts_with($avatar, 'data:')
                ? $this->storeAvatarDataUrl($avatar)
                : $avatar;
        }

        if (!empty($updateData)) {
            $request->user()->update($updateData);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'avatar' => $request->user()->avatar,
            'avatar_url' => $request->user()->avatar_url,
        ]);
    }

    private function storeAvatarDataUrl(string $avatar): string
    {
        if (!preg_match('/\Adata:image\/(jpeg|png|webp);base64,([A-Za-z0-9+\/=]+)\z/', $avatar, $matches)) {
            throw ValidationException::withMessages([
                'avatar' => 'Foto profil harus berupa JPG, PNG, atau WEBP yang valid.',
            ]);
        }

        $contents = base64_decode($matches[2], true);
        $image = $contents === false ? false : getimagesizefromstring($contents);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (
            $contents === false
            || strlen($contents) > 4 * 1024 * 1024
            || !$image
            || !in_array($image['mime'], $allowedMimeTypes, true)
        ) {
            throw ValidationException::withMessages([
                'avatar' => 'Foto profil maksimal 4 MB dan harus berupa JPG, PNG, atau WEBP.',
            ]);
        }

        $extension = match ($image['mime']) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        };
        $path = 'avatars/' . Str::uuid() . '.' . $extension;

        if (!Storage::disk('public')->put($path, $contents)) {
            throw new \RuntimeException('Foto profil gagal disimpan ke penyimpanan publik.');
        }

        return 'storage/' . $path;
    }
}