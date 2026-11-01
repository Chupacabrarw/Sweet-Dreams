<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteContent;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * QRIS statis toko -> QRIS dinamis per pesanan (nominal sudah tertanam).
 *
 * Payload QRIS = deretan TLV (tag 2 digit, panjang 2 digit, nilai) + CRC16
 * di tag 63. Untuk menanam nominal: tag 01 "11" (statis) jadi "12" (dinamis),
 * tambah tag 54 (nominal), lalu hitung ulang CRC. Pembeli tinggal scan,
 * nominal langsung terisi di aplikasi e-wallet / m-banking.
 *
 * Karena QRIS statis tidak punya notifikasi otomatis, tiap pesanan diberi
 * kode unik (Rp 1–999) supaya admin bisa mencocokkan mutasi di aplikasi
 * merchant, plus pembeli mengunggah bukti bayar untuk diverifikasi admin.
 */
class QrisService
{
    /** Status bayar: bukti sudah diunggah, menunggu dicek admin. */
    public const STATUS_VERIFYING = 'verifying';

    /** Payload QRIS statis aktif: CMS admin dulu, fallback ke .env. */
    public function staticPayload(): string
    {
        $fromCms = null;
        try {
            $fromCms = SiteContent::where('key', 'qris_payload')->value('body');
        } catch (\Throwable $e) {
            // tabel belum ada / DB mati: pakai .env
        }

        return trim((string) ($fromCms ?: config('services.qris.payload', '')));
    }

    public function expiryMinutes(): int
    {
        return max(15, (int) config('services.qris.expiry_minutes', 1440));
    }

    public function isConfigured(): bool
    {
        return $this->isValid($this->staticPayload());
    }

    /** Payload valid = TLV utuh, diakhiri tag 63 dengan CRC yang cocok. */
    public function isValid(string $payload): bool
    {
        $payload = trim($payload);
        if (strlen($payload) < 20 || !str_starts_with($payload, '000201')) {
            return false;
        }

        try {
            $tags = $this->parse($payload);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        $crc = $tags['63'] ?? null;
        if ($crc === null || !str_ends_with($payload, '6304' . $crc)) {
            return false;
        }

        return strtoupper($crc) === $this->crc16(substr($payload, 0, -4));
    }

    /** Info merchant untuk ditampilkan (nama, kota, NMID). */
    public function merchantInfo(?string $payload = null): array
    {
        $payload ??= $this->staticPayload();
        try {
            $tags = $this->parse($payload);
        } catch (InvalidArgumentException $e) {
            return ['name' => null, 'city' => null, 'nmid' => null];
        }

        $nmid = null;
        if (isset($tags['51'])) {
            try {
                $nmid = $this->parse($tags['51'])['02'] ?? null;
            } catch (InvalidArgumentException $e) {
                // abaikan
            }
        }

        return [
            'name' => $tags['59'] ?? null,
            'city' => $tags['60'] ?? null,
            'nmid' => $nmid,
        ];
    }

    /** Bangun payload QRIS dinamis dengan nominal tertentu (rupiah, bulat). */
    public function makeDynamic(int $amount, ?string $staticPayload = null): string
    {
        $staticPayload ??= $this->staticPayload();
        if (!$this->isValid($staticPayload)) {
            throw new InvalidArgumentException('Payload QRIS toko belum diatur atau tidak valid.');
        }
        if ($amount < 1) {
            throw new InvalidArgumentException('Nominal QRIS harus lebih dari 0.');
        }

        $tags = $this->parse($staticPayload);
        unset($tags['63'], $tags['54'], $tags['55'], $tags['56'], $tags['57']);
        $tags['01'] = '12';
        $tags['54'] = (string) $amount;

        // Urutan tag QRIS menaik (26..51 akun merchant, 52, 53, 54, 58, ...)
        ksort($tags, SORT_STRING);

        $body = '';
        foreach ($tags as $tag => $value) {
            $body .= $this->tlv($tag, $value);
        }
        $body .= '6304';

        return $body . $this->crc16($body);
    }

    /**
     * Siapkan tagihan QRIS untuk pesanan: kode unik, nominal bayar,
     * payload QR, dan batas waktu. Dipanggil saat checkout & saat tagihan
     * lama (mis. pesanan era Midtrans) belum punya QR.
     */
    public function prepareOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $locked = Order::query()->lockForUpdate()->findOrFail($order->id);

            $code = $this->pickUniqueCode((int) $locked->total, $locked->id);
            $amount = (int) $locked->total + $code;

            $locked->update([
                'payment_method'      => 'qris',
                'payment_unique_code' => $code,
                'payment_amount'      => $amount,
                'qr_string'           => $this->makeDynamic($amount),
                'payment_reference'   => null,
                'payment_url'         => null,
                'payment_expired_at'  => now()->addMinutes($this->expiryMinutes()),
            ]);

            return $locked;
        });
    }

    /**
     * Pastikan pesanan unpaid punya tagihan QRIS aktif (mis. pesanan lama era
     * Midtrans yang belum punya QR), lalu batalkan kalau sudah lewat batas.
     * Dipanggil saat pembeli membuka / mengecek pesanan (lazy, tanpa cron).
     */
    public function syncOrder(Order $order): Order
    {
        if ($order->payment_status !== 'unpaid' || $order->status !== 'pending') {
            return $order;
        }

        if ($order->isPaymentExpired()) {
            app(InventoryService::class)->cancelOrder($order, [
                'payment_status' => 'expired',
                'status'         => 'cancelled',
            ], true);

            return $order->refresh();
        }

        if (empty($order->qr_string) && $this->isConfigured()) {
            return $this->prepareOrder($order);
        }

        return $order;
    }

    /** Batalkan semua tagihan QRIS yang kedaluwarsa (untuk scheduler). */
    public function expireOverdue(): int
    {
        $count = 0;
        Order::query()
            ->where('payment_status', 'unpaid')
            ->where('status', 'pending')
            ->whereNotNull('payment_expired_at')
            ->where('payment_expired_at', '<', now())
            ->lazyById()
            ->each(function (Order $order) use (&$count) {
                if (app(InventoryService::class)->cancelOrder($order, [
                    'payment_status' => 'expired',
                    'status'         => 'cancelled',
                ], true)) {
                    $count++;
                }
            });

        return $count;
    }

    /**
     * Kode unik 1–999 sehingga nominal bayar tidak bentrok dengan tagihan
     * lain yang masih terbuka (biar mutasi bisa dicocokkan 1:1).
     */
    protected function pickUniqueCode(int $total, int $exceptOrderId): int
    {
        $taken = Order::query()
            ->where('id', '!=', $exceptOrderId)
            ->whereIn('payment_status', ['unpaid', self::STATUS_VERIFYING])
            ->where('status', '!=', 'cancelled')
            ->whereBetween('payment_amount', [$total + 1, $total + 999])
            ->pluck('payment_amount')
            ->map(fn ($a) => (int) $a - $total)
            ->flip();

        for ($i = 0; $i < 30; $i++) {
            $code = random_int(1, 999);
            if (!$taken->has($code)) {
                return $code;
            }
        }

        for ($code = 1; $code <= 999; $code++) {
            if (!$taken->has($code)) {
                return $code;
            }
        }

        return random_int(1, 999);
    }

    /** @return array<string,string> tag => value (urutan asli dipertahankan) */
    public function parse(string $payload): array
    {
        $tags = [];
        $i = 0;
        $len = strlen($payload);
        while ($i < $len) {
            if ($i + 4 > $len) {
                throw new InvalidArgumentException('TLV QRIS terpotong.');
            }
            $tag = substr($payload, $i, 2);
            $size = substr($payload, $i + 2, 2);
            if (!ctype_digit($tag) || !ctype_digit($size) || $i + 4 + (int) $size > $len) {
                throw new InvalidArgumentException('TLV QRIS rusak di posisi ' . $i . '.');
            }
            $tags[$tag] = substr($payload, $i + 4, (int) $size);
            $i += 4 + (int) $size;
        }

        return $tags;
    }

    protected function tlv(string $tag, string $value): string
    {
        return $tag . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    /** CRC16-CCITT (poly 0x1021, init 0xFFFF) sesuai spesifikasi QRIS. */
    public function crc16(string $data): string
    {
        $crc = 0xFFFF;
        $n = strlen($data);
        for ($i = 0; $i < $n; $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($b = 0; $b < 8; $b++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
