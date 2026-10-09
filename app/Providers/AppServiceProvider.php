<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Railway (dan proxy TLS lain) meneruskan request sebagai http internal,
        // sehingga asset()/@vite bikin URL http:// -> diblokir browser (mixed content).
        // Paksa https di production agar CSS/JS ke-load.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
