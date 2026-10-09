<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus total fitur size chart & harga varian (tidak dipakai di UI mana pun).
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('template_size_chart_id');
        });
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('variant_price');
        });
        Schema::dropIfExists('size_charts');
    }

    public function down(): void
    {
        Schema::create('size_charts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('data')->nullable();
            $table->timestamps();
        });
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedInteger('variant_price')->nullable()->after('stock');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('template_size_chart_id')
                ->nullable()->after('height')
                ->constrained('size_charts')->nullOnDelete();
        });
    }
};
