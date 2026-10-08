import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/layouts/app.css',
                'resources/css/layouts/app-chatbot.css',
                'resources/css/layouts/admin.css',
                'resources/css/pages/checkout.css',
                'resources/css/pages/katalog.css',
                'resources/css/pages/keranjang.css',
                'resources/css/pages/kontak.css',
                'resources/css/pages/pesanan-detail.css',
                'resources/css/pages/produk-detail.css',
                'resources/css/pages/profil.css',
                'resources/css/pages/tentang.css',
                'resources/css/pages/welcome.css',
                'resources/css/pages/wishlist.css',
                'resources/css/pages/admin/content.css',
                'resources/css/pages/admin/contact-messages.css',
                'resources/css/pages/admin/customers.css',
                'resources/css/pages/admin/dashboard.css',
                'resources/css/pages/admin/orders.css',
                'resources/css/pages/admin/products.css',
                'resources/css/pages/admin/reports.css',
                'resources/css/pages/admin/stock.css',
                'resources/css/pages/admin/vouchers.css',
                'resources/css/pages/admin/login.css',
                'resources/css/pages/auth/login.css',
                'resources/css/pages/auth/logout.css',
                'resources/css/pages/filament/pages/dashboard.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
