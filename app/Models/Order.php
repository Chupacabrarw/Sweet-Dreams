<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id',
        'shipping_recipient_name', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_province', 'shipping_postal_code',
        'shipping_courier', 'shipping_service', 'shipping_cost',
        'subtotal', 'discount', 'voucher_code', 'total', 'status',
        'payment_method', 'payment_status', 'payment_reference',
        'payment_url', 'va_number', 'qr_string', 'payment_expired_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}