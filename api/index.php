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

require __DIR__ . '/../public/index.php';
