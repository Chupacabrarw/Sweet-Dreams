<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\QrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Pembayaran QRIS statis: pembeli scan QR (nominal + kode unik tertanam),
 * lalu unggah bukti bayar. Admin mencocokkan mutasi di aplikasi merchant
 * dan mengonfirmasi lunas dari halaman Manajemen Pesanan.
 */
class PaymentController extends Controller
{
    public function __construct(protected QrisService $qris)
    {
    }

    protected function ownedOrder(Request $request, string $orderNumber): Order
    {
        return Order::where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
    }

    /**
     * Cek status pembayaran (dipakai polling di halaman pesanan).
     * GET /api/payment/status/{orderNumber}
     */
    public function checkStatus(Request $request, string $orderNumber)
    {
        $order = $this->qris->syncOrder($this->ownedOrder($request, $orderNumber));

        $message = match ($order->payment_status) {
            'paid'      => 'Pembayaran sudah dikonfirmasi.',
            'verifying' => 'Bukti bayar sedang dicek admin.',
            'expired'   => 'Batas waktu pembayaran sudah lewat, pesanan dibatalkan.',
            default     => 'Pembayaran belum dikonfirmasi.',
        };

        return response()->json([
            'success'        => true,
            'payment_status' => $order->payment_status,
            'order_status'   => $order->status,
            'message'        => $message,
            'order_number'   => $order->order_number,
            'payment_amount' => $order->payment_amount,
        ]);
    }

    /**
     * Pembeli mengunggah bukti bayar QRIS -> status "verifying".
     * POST /pesanan/{orderNumber}/bukti-bayar
     */
    public function uploadProof(Request $request, string $orderNumber)
    {
        $request->validate([
            'proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'proof.required' => 'Pilih foto/screenshot bukti pembayaran dulu.',
            'proof.image'    => 'Bukti bayar harus berupa gambar.',
            'proof.max'      => 'Ukuran gambar maksimal 4 MB.',
        ]);

        $order = $this->qris->syncOrder($this->ownedOrder($request, $orderNumber));

        if ($order->payment_status !== 'unpaid' || $order->status !== 'pending') {
            return back()->with('error', 'Pesanan ini sudah tidak menunggu pembayaran.');
        }

        $path = $request->file('proof')->store('payment-proofs', 'local');

        $saved = DB::transaction(function () use ($order, $path) {
            $locked = Order::query()->lockForUpdate()->find($order->id);
            if (!$locked || $locked->payment_status !== 'unpaid' || $locked->status !== 'pending') {
                return false;
            }

            if ($locked->payment_proof) {
                Storage::disk('local')->delete($locked->payment_proof);
            }

            $locked->update([
                'payment_status'            => QrisService::STATUS_VERIFYING,
                'payment_proof'             => $path,
                'payment_proof_uploaded_at' => now(),
                'payment_note'              => null,
            ]);

            return true;
        });

        if (!$saved) {
            Storage::disk('local')->delete($path);
            return back()->with('error', 'Pesanan ini sudah tidak menunggu pembayaran.');
        }

        return redirect()->route('pesanan.detail', $order->order_number)
            ->with('success', 'Bukti bayar terkirim! Admin akan mengecek dan mengonfirmasi pesananmu secepatnya.');
    }

    /**
     * Tampilkan bukti bayar (hanya pemilik pesanan & admin).
     * GET /pesanan/{orderNumber}/bukti-bayar
     */
    public function showProof(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $user = $request->user();
        abort_unless($user->role === 'admin' || $order->user_id === $user->id, 403);
        abort_unless($order->payment_proof && Storage::disk('local')->exists($order->payment_proof), 404);

        return Storage::disk('local')->response($order->payment_proof, null, [
            'Cache-Control' => 'private, max-age=0, no-store',
        ]);
    }
}
