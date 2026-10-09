<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'recipient_name', 'phone',
        'address', 'city', 'city_id', 'subdistrict', 'province', 'province_id', 'postal_code', 'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}