<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(\Illuminate\Support\Str::random(8));
            }
        });
    }
           protected $fillable = [
        'category_id', 'title', 'slug', 'collection',
        'price', 'original_price', 'discount', 'rating',
        'review_count', 'sizes', 'colors', 'badge',
        'short_desc', 'image', 'sales_count',
        'sku', 'stock', 'is_active'
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
        public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}