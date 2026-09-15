<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
   protected $fillable = [
    'slug', 'title', 'category', 'description', 'price', 'original_price',
    'stock', 'image', 'main_image', 'gallery', 'badge', 'review_count',
    'collection', 'short_desc', 'colors', 'sizes', 'default_size',
    'long_desc_title', 'long_desc', 'features',
];

protected $casts = [
    'gallery' => 'array',
    'colors' => 'array',
    'sizes' => 'array',
    'features' => 'array',
];
}
