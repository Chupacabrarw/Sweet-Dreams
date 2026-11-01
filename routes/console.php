<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Batalkan otomatis pesanan QRIS yang lewat batas bayar (stok dikembalikan).
// Jalan kalau scheduler aktif (`php artisan schedule:work` / cron schedule:run);
// tanpa scheduler pun pesanan tetap dibatalkan saat dibuka pembeli.
Artisan::command('orders:expire-qris', function () {
    $count = app(\App\Services\QrisService::class)->expireOverdue();
    $this->info("{$count} pesanan QRIS kedaluwarsa dibatalkan.");
})->purpose('Batalkan pesanan QRIS yang lewat batas waktu bayar');

\Illuminate\Support\Facades\Schedule::command('orders:expire-qris')->everyFiveMinutes()->withoutOverlapping();
