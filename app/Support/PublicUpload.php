<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Simpan gambar upload ke disk publik yang dikonfigurasi (filesystems.upload_disk).
 * Lokal  → "images/products/x.jpg" (relatif, dibaca lewat asset()).
 * Vercel → URL penuh Supabase Storage (asset() mengembalikan URL apa adanya).
 */
class PublicUpload
{
    public static function store(UploadedFile $file, string $directory, string $filename): string
    {
        return self::put($directory . '/' . $filename, $file->get(), $file->getMimeType());
    }

    public static function put(string $path, string $contents, ?string $mime = null): string
    {
        $diskName = config('filesystems.upload_disk', 'public_dir');
        $disk = Storage::disk($diskName);

        $disk->put($path, $contents, array_filter([
            'visibility'  => 'public',
            'ContentType' => $mime,
        ]));

        return $diskName === 'public_dir' ? $path : $disk->url($path);
    }
}
