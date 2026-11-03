<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        // Gambar upload admin (produk, banner, avatar) saat lokal: langsung ke folder public/
        'public_dir' => [
            'driver' => 'local',
            'root' => public_path(),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/'),
            'visibility' => 'public',
            'throw' => true,
        ],

        // Supabase Storage lewat protokol S3 (dipakai di Vercel, filesystem-nya read-only)
        'supabase' => [
            'driver' => 's3',
            'key' => env('SUPABASE_S3_KEY'),
            'secret' => env('SUPABASE_S3_SECRET'),
            'region' => env('SUPABASE_S3_REGION', 'ap-southeast-1'),
            'bucket' => env('SUPABASE_PUBLIC_BUCKET', 'images'),
            'endpoint' => env('SUPABASE_URL').'/storage/v1/s3',
            'url' => env('SUPABASE_URL').'/storage/v1/object/public/'.env('SUPABASE_PUBLIC_BUCKET', 'images'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'throw' => true,
        ],

        'supabase_private' => [
            'driver' => 's3',
            'key' => env('SUPABASE_S3_KEY'),
            'secret' => env('SUPABASE_S3_SECRET'),
            'region' => env('SUPABASE_S3_REGION', 'ap-southeast-1'),
            'bucket' => env('SUPABASE_PRIVATE_BUCKET', 'private'),
            'endpoint' => env('SUPABASE_URL').'/storage/v1/s3',
            'use_path_style_endpoint' => true,
            'throw' => false,
        ],

    ],

    // Disk untuk gambar upload yang tampil publik, dan untuk bukti bayar QRIS (privat)
    'upload_disk' => env('UPLOAD_DISK', 'public_dir'),
    'proof_disk' => env('PAYMENT_PROOF_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
