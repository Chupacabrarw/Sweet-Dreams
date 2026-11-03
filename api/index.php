<?php

// Entry point Vercel (vercel-php). Filesystem Vercel read-only kecuali /tmp,
// jadi storage Laravel (view compiled, cache, log) dipindah ke /tmp/storage.
$storage = '/tmp/storage';
foreach (['framework/views', 'framework/cache/data', 'framework/sessions', 'logs', 'app/private'] as $dir) {
    if (!is_dir("$storage/$dir")) {
        @mkdir("$storage/$dir", 0755, true);
    }
}
$_ENV['LARAVEL_STORAGE_PATH'] = $_SERVER['LARAVEL_STORAGE_PATH'] = $storage;

// Script aslinya /api/index.php; tanpa ini Laravel menganggap /api sebagai base path
// sehingga route /api/chatbot, /api/login, dst. jadi 404.
$_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

require __DIR__ . '/../public/index.php';
