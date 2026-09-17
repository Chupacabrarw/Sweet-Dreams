<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Snapshot alamat pengiriman (biar tetap utuh walau alamat aslinya diubah/dihapus nanti)
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->string('shipping_recipient_name');
            $table->string('shipping_phone')->nullable();
            $table->text('shipping_address');
            $table->string('shipping_city')->nullable();
            $table->string('shipping_province')->nullable();
            $table->string('shipping_postal_code')->nullable();

            // Ongkos kirim (nanti diisi dari RajaOngkir)
            $table->string('shipping_courier')->nullable();
            $table->string('shipping_service')->nullable();
            $table->unsignedInteger('shipping_cost')->default(0);
            $table->string('tracking_number')->nullable();

            // Ringkasan biaya
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('total');

            // Status pesanan
            $table->string('status')->default('pending'); // pending, processing, shipped, completed, cancelled

            // Pembayaran (nanti diisi dari Midtrans)
            $table->string('payment_method')->nullable(); // midtrans, cod, dst
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed, expired
            $table->string('payment_reference')->nullable(); // Midtrans order_id / transaction_id

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};