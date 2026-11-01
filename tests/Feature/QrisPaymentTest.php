<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\QrisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QrisPaymentTest extends TestCase
{
    use RefreshDatabase;

    private const STATIC_QRIS = '00020101021126650013ID.CO.BCA.WWW011893600014000406485502150008850040648550303UMI51440014ID.CO.QRIS.WWW0215ID10254331896990303UMI5204569153033605802ID5911sweet dream6008BANYUMAS61055311462070703A016304405C';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.qris.payload' => self::STATIC_QRIS, 'services.qris.expiry_minutes' => 60]);
        Storage::fake('local');
    }

    public function test_static_qris_becomes_dynamic_with_amount_and_valid_crc(): void
    {
        $qris = app(QrisService::class);

        $this->assertTrue($qris->isValid(self::STATIC_QRIS));
        $this->assertFalse($qris->isValid(substr(self::STATIC_QRIS, 0, -1) . '0'));

        $dynamic = $qris->makeDynamic(150237);
        $tags = $qris->parse($dynamic);

        $this->assertTrue($qris->isValid($dynamic));
        $this->assertSame('12', $tags['01']);
        $this->assertSame('150237', $tags['54']);
        $this->assertSame('sweet dream', $tags['59']);
    }

    private function cartFor(User $user): CartItem
    {
        $category = Category::create(['name' => 'Sleepwear', 'slug' => 'sleepwear']);
        $product = Product::create([
            'category_id' => $category->id, 'title' => 'Satin Pajama', 'slug' => 'satin-pajama',
            'price' => 150000, 'sizes' => ['M'], 'colors' => ['Pink'], 'stock' => 5,
            'is_active' => true, 'weight' => 250, 'image' => 'images/x.jpg',
        ]);
        ProductVariant::create(['product_id' => $product->id, 'size' => 'M', 'color' => 'Pink', 'stock' => 5]);

        return CartItem::create(['user_id' => $user->id, 'product_id' => $product->id, 'size' => 'M', 'color' => 'Pink', 'quantity' => 1]);
    }

    private function checkout(User $user): Order
    {
        $item = $this->cartFor($user);

        $res = $this->actingAs($user)->postJson('/api/checkout', [
            'name' => 'Alya', 'phone' => '0812', 'address' => 'Jl. Mawar 1', 'city' => 'Banyumas',
            'province' => 'Jawa Tengah', 'postal_code' => '53114', 'shipping_id' => 'jne',
            'shipping_courier' => 'JNE Regular', 'shipping_cost' => 17000, 'payment_id' => 'qris',
            'item_ids' => [$item->id],
        ]);

        $res->assertCreated()->assertJsonPath('payment_method', 'QRIS (Scan & Bayar)');

        return Order::where('order_number', $res->json('order_number'))->firstOrFail();
    }

    public function test_checkout_creates_qris_bill_with_unique_code(): void
    {
        $user = User::factory()->create();
        $order = $this->checkout($user);

        $this->assertSame('unpaid', $order->payment_status);
        $this->assertSame(167000, (int) $order->total);
        $this->assertGreaterThanOrEqual(1, $order->payment_unique_code);
        $this->assertSame(167000 + $order->payment_unique_code, (int) $order->payment_amount);
        $this->assertSame((string) $order->payment_amount, app(QrisService::class)->parse($order->qr_string)['54']);
        $this->assertNotNull($order->payment_expired_at);

        $this->actingAs($user)->get('/pesanan/' . $order->order_number)
            ->assertOk()
            ->assertSee('Scan QRIS untuk membayar')
            ->assertSee('Rp ' . number_format($order->payment_amount, 0, ',', '.'));
    }

    public function test_proof_upload_then_admin_approves(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->checkout($user);

        $this->actingAs($user)
            ->post("/pesanan/{$order->order_number}/bukti-bayar", ['proof' => UploadedFile::fake()->image('bukti.png')])
            ->assertRedirect("/pesanan/{$order->order_number}");

        $order->refresh();
        $this->assertSame('verifying', $order->payment_status);
        Storage::disk('local')->assertExists($order->payment_proof);

        // Pembeli tidak bisa membatalkan saat bukti sedang dicek
        $this->actingAs($user)->post("/pesanan/{$order->order_number}/batalkan");
        $this->assertSame('pending', $order->fresh()->status);

        // Bukti hanya bisa dilihat pemilik & admin
        $this->actingAs(User::factory()->create())->get("/pesanan/{$order->order_number}/bukti-bayar")->assertForbidden();
        $this->actingAs($admin)->get("/pesanan/{$order->order_number}/bukti-bayar")->assertOk();

        $this->actingAs($admin)->get('/admin/pesanan/' . $order->id, ['Accept' => 'application/json'])
            ->assertJsonPath('payment_status', 'verifying');

        $this->actingAs($admin)->put("/admin/pesanan/{$order->id}/pembayaran", ['action' => 'approve'])
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('processing', $order->status);
        $this->assertNotNull($order->payment_verified_at);
    }

    public function test_admin_rejects_proof_and_buyer_can_reupload(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->checkout($user);

        $this->actingAs($user)->post("/pesanan/{$order->order_number}/bukti-bayar", ['proof' => UploadedFile::fake()->image('a.png')]);
        $this->actingAs($admin)->put("/admin/pesanan/{$order->id}/pembayaran", ['action' => 'reject', 'note' => 'Nominal kurang']);

        $order->refresh();
        $this->assertSame('unpaid', $order->payment_status);
        $this->assertSame('Nominal kurang', $order->payment_note);

        $this->actingAs($user)->get('/pesanan/' . $order->order_number)->assertSee('Nominal kurang');
        $this->actingAs($user)->post("/pesanan/{$order->order_number}/bukti-bayar", ['proof' => UploadedFile::fake()->image('b.png')]);
        $this->assertSame('verifying', $order->fresh()->payment_status);
    }

    public function test_overdue_unpaid_order_is_cancelled_and_stock_restored(): void
    {
        $user = User::factory()->create();
        $order = $this->checkout($user);
        $this->assertSame(4, ProductVariant::first()->stock);

        $this->travel(61)->minutes();
        $this->artisan('orders:expire-qris')->assertSuccessful();

        $order->refresh();
        $this->assertSame('cancelled', $order->status);
        $this->assertSame('expired', $order->payment_status);
        $this->assertSame(5, ProductVariant::first()->stock);
    }

    public function test_admin_pages_show_qris_settings_and_verification_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->checkout(User::factory()->create());
        $this->actingAs($order->user)->post("/pesanan/{$order->order_number}/bukti-bayar", ['proof' => UploadedFile::fake()->image('a.png')]);

        $this->actingAs($admin)->get('/admin/pesanan?status=verifikasi')
            ->assertOk()->assertSee($order->order_number)->assertSee('Cek bukti bayar');

        $this->actingAs($admin)->get('/admin/konten')
            ->assertOk()->assertSee('ID1025433189699');

        $this->actingAs($admin)->put('/admin/konten/qris', ['qris_payload' => 'rusak'])
            ->assertSessionHas('error');
    }

    public function test_status_endpoint_is_private(): void
    {
        $user = User::factory()->create();
        $order = $this->checkout($user);

        $this->actingAs(User::factory()->create())->getJson('/api/payment/status/' . $order->order_number)->assertNotFound();
        $this->actingAs($user)->getJson('/api/payment/status/' . $order->order_number)
            ->assertOk()->assertJsonPath('payment_status', 'unpaid');
    }
}
