<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $banner = \App\Models\SiteContent::firstOrCreate(['key' => 'banner_homepage'], [
        'title'       => "Sweet Dreams\nStart Here",
        'subtitle'    => 'Koleksi sleepwear & lingerie premium untuk kenyamanan dan kepercayaan dirimu.',
        'button_text' => 'Shop Now',
        'link'        => '/katalog',
        'image'       => 'images/hero-banner.jpg',
    ]);

    $catIds  = json_decode(\App\Models\SiteContent::where('key', 'home_categories')->value('body') ?? '[]', true) ?: [];
    $prodIds = json_decode(\App\Models\SiteContent::where('key', 'home_products')->value('body') ?? '[]', true) ?: [];

    $homeCategories = count($catIds)
        ? \App\Models\Category::whereIn('id', $catIds)->orderByRaw('FIELD(id,' . implode(',', $catIds) . ')')->get()
        : collect();

    $homeProducts = count($prodIds)
        ? \App\Models\Product::whereIn('id', $prodIds)->orderByRaw('FIELD(id,' . implode(',', $prodIds) . ')')->get()
        : collect();

    $wishlistIds = auth()->check()
        ? auth()->user()->wishlists()->pluck('product_id')->map(fn($id) => (int)$id)->toArray()
        : [];

    return view('welcome', compact('banner', 'homeCategories', 'homeProducts', 'wishlistIds'));
});

Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;

Route::post('/api/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/api/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/api/logout', [AuthController::class, 'logout'])->name('api.logout');
Route::post('/api/check-email', [AuthController::class, 'checkEmail'])->name('api.check-email');
Route::post('/api/reset-password', [AuthController::class, 'resetPassword'])->name('api.reset-password');
Route::put('/api/profile', [App\Http\Controllers\ProfileController::class, 'update'])->middleware('auth');



Route::get('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/produk', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products');
    Route::post('/produk', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::put('/produk/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/produk/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    // Category management
    Route::post('/kategori', [App\Http\Controllers\Admin\ProductController::class, 'storeCategory'])->name('categories.store');
    Route::put('/kategori/{category}', [App\Http\Controllers\Admin\ProductController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/kategori/{category}', [App\Http\Controllers\Admin\ProductController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/stok', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('stock');
    Route::put('/stok/{variant}', [App\Http\Controllers\Admin\StockController::class, 'update'])->name('stock.update');

        Route::get('/pesanan', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders');
    Route::get('/pesanan/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/pesanan/{order}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');

        Route::get('/pelanggan', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers');
    Route::get('/pelanggan/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');

        Route::get('/konten', [App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content');
    Route::put('/konten/banner', [App\Http\Controllers\Admin\ContentController::class, 'updateBanner'])->name('content.banner');
    Route::put('/konten/featured', [App\Http\Controllers\Admin\ContentController::class, 'updateFeatured'])->name('content.featured');
    Route::put('/konten/text/{key}', [App\Http\Controllers\Admin\ContentController::class, 'updateText'])->name('content.text');

        Route::get('/promo', [App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers');
    Route::post('/promo', [App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::put('/promo/{voucher}', [App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/promo/{voucher}', [App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.destroy');

        Route::get('/laporan', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
});

Route::middleware('auth')->group(function () {
    Route::post('/api/addresses', [App\Http\Controllers\AddressController::class, 'store']);
    Route::put('/api/addresses/{address}', [App\Http\Controllers\AddressController::class, 'update']);
    Route::delete('/api/addresses/{address}', [App\Http\Controllers\AddressController::class, 'destroy']);
    Route::patch('/api/addresses/{address}/primary', [App\Http\Controllers\AddressController::class, 'setPrimary']);
});

Route::middleware('auth')->group(function () {
    Route::post('/api/cart', [App\Http\Controllers\CartController::class, 'store']);
    Route::patch('/api/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'updateQuantity']);
    Route::delete('/api/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'destroy']);
});

Route::get('/api/wishlist/ids', [App\Http\Controllers\WishlistController::class, 'ids']);

Route::middleware('auth')->group(function () {
    Route::post('/api/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle']);
});

Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'index'])
    ->middleware('auth')
    ->name('profil');
    

Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->middleware('auth')->name('wishlist');
Route::get('/katalog/{category?}', [App\Http\Controllers\ProductController::class, 'index'])->name('katalog');
Route::get('/produk/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->middleware('auth')->name('produk.detail');
Route::get('/keranjang', [App\Http\Controllers\CartController::class, 'index'])->middleware('auth')->name('keranjang');



Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->middleware('auth')->name('checkout');
Route::post('/api/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->middleware('auth');
Route::post('/api/checkout/validate-voucher', [App\Http\Controllers\CheckoutController::class, 'validateVoucher'])->middleware('auth');

Route::post('/api/chatbot', [App\Http\Controllers\ChatbotController::class, 'chat']);

// ===== SHIPPING (RAJAONGKIR) =====
Route::get('/api/shipping/provinces', [App\Http\Controllers\ShippingController::class, 'provinces']);
Route::get('/api/shipping/cities', [App\Http\Controllers\ShippingController::class, 'cities']);
Route::post('/api/shipping/cost', [App\Http\Controllers\ShippingController::class, 'cost']);

// ===== PAYMENT GATEWAY (KOMERCE) =====
Route::get('/api/payment/status/{orderNumber}', [App\Http\Controllers\PaymentController::class, 'checkStatus']);
Route::post('/api/payment/webhook', [App\Http\Controllers\PaymentController::class, 'handleWebhook']);

// ===== ORDER TRACKING DETAIL =====//
Route::get('/pesanan/{orderNumber}', [App\Http\Controllers\OrderController::class, 'show'])
    ->middleware('auth')
    ->name('pesanan.detail');

Route::get('/login', function () {
    return view('auth.login', ['initialMode' => 'login']);
})->name('login');

Route::get('/register', function () {
    return view('auth.login', ['initialMode' => 'register']);
})->name('register');

Route::match(['get', 'post'], '/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->wantsJson() || $request->is('api/*')) {
        return response()->json(['status' => 'success', 'message' => 'Berhasil keluar']);
    }

    return redirect('/login?status=logout');
})->name('logout');

