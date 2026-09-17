<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'min_purchase',
        'max_discount', 'usage_limit', 'used_count', 'starts_at', 'ends_at', 'is_active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function statusLabel(): string
    {
        if (!$this->is_active) return 'Nonaktif';
        if ($this->starts_at->isFuture()) return 'Terjadwal';
        if ($this->ends_at->isPast()) return 'Berakhir';
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return 'Habis';
        return 'Aktif';
    }

    public function statusClass(): string
    {
        return match ($this->statusLabel()) {
            'Aktif' => 'aktif',
            'Terjadwal' => 'terjadwal',
            default => 'nonaktif',
        };
    }

    public function isValidFor(int $subtotal): bool
    {
        return $this->is_active
            && !$this->starts_at->isFuture()
            && !$this->ends_at->isPast()
            && (!$this->usage_limit || $this->used_count < $this->usage_limit)
            && $subtotal >= $this->min_purchase;
    }

    public function calculateDiscount(int $subtotal): int
    {
        $discount = $this->discount_type === 'percentage'
            ? (int) round($subtotal * $this->discount_value / 100)
            : $this->discount_value;

        return $this->max_discount ? min($discount, $this->max_discount) : $discount;
    }
}