<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
           protected $fillable = [
        'category_id', 'title', 'slug', 'collection',
        'price', 'original_price', 'discount', 'rating',
        'review_count', 'sizes', 'colors', 'badge',
        'short_desc', 'image', 'sales_count',
        'gallery', 'long_desc_title', 'long_desc', 'features',
        'sku', 'stock',
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'gallery' => 'array',
        'features' => 'array',
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