<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (blank($product->sku)) {
                $product->sku = null;
            }
        });

        static::created(function (Product $product) {
            if (filled($product->sku)) {
                return;
            }

            $prefix = static::skuPrefix($product->category()->value('slug') ?? '');
            $product->forceFill([
                'sku' => sprintf('%s-%05d', $prefix, $product->getKey()),
            ])->saveQuietly();
        });
    }

    public static function skuPrefix(string $categorySlug): string
    {
        $words = preg_split('/[^a-z0-9]+/i', $categorySlug, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if (count($words) > 1) {
            return strtoupper(implode('', array_map(
                fn (string $word) => substr($word, 0, 1),
                array_slice($words, 0, 3)
            )));
        }

        return strtoupper(substr($words[0] ?? 'PR', 0, 2));
    }
           protected $fillable = [
        'category_id', 'title', 'slug', 'collection',
        'price', 'original_price', 'discount', 'rating',
        'review_count', 'sizes', 'colors', 'badge',
        'short_desc', 'long_desc', 'image', 'gallery', 'sales_count',
        'sku', 'stock', 'is_active', 'weight',
        'length', 'width', 'height'
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'gallery' => 'array',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
        public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

        public function reviews(): HasMany
        {
            return $this->hasMany(ProductReview::class);
        }
}