<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['key', 'title', 'subtitle', 'button_text', 'link', 'image', 'body', 'status'];

    /** Maks. foto tambahan untuk slider hero di beranda (selain gambar banner utama). */
    public const MAX_BANNER_SLIDES = 6;

    /** Foto slide bawaan (tanpa orang) sebelum admin mengatur sendiri. */
    public const DEFAULT_BANNER_SLIDES = [
        'images/hero/slide-piyama-di-ranjang.jpg',
        'images/hero/slide-kamar-tidur-tanaman.jpg',
        'images/hero/slide-ranjang-putih.jpg',
    ];

    /** Foto tambahan slider hero (path relatif / URL penuh), disimpan sebagai JSON di key `banner_slides`. */
    public static function bannerSlides(): array
    {
        $body = static::where('key', 'banner_slides')->value('body');
        // Belum pernah diatur admin → pakai foto bawaan; kalau admin menghapus semua, tersimpan "[]" dan tetap kosong
        $list = $body === null ? self::DEFAULT_BANNER_SLIDES : json_decode($body, true);

        return array_values(array_filter(is_array($list) ? $list : [], fn ($p) => is_string($p) && $p !== ''));
    }

    /** Semua foto hero untuk beranda: gambar banner utama dulu, lalu foto slide (tanpa duplikat). */
    public static function heroSlides(?self $banner): array
    {
        return array_values(array_unique(array_filter([$banner?->image, ...static::bannerSlides()])));
    }
}
