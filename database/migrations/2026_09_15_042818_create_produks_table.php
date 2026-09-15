<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('produks', function (Blueprint $table) {
        $table->id();
        $table->string('slug')->unique();
        $table->string('title');
        $table->string('category')->nullable();
        $table->text('description')->nullable();
        $table->unsignedBigInteger('price');
        $table->unsignedBigInteger('original_price')->nullable();
        $table->unsignedInteger('stock')->default(0);
        $table->string('image')->nullable();

        // Detail halaman produk
        $table->string('main_image')->nullable();
        $table->json('gallery')->nullable();
        $table->string('badge')->nullable();
        $table->unsignedInteger('review_count')->default(0);
        $table->string('collection')->nullable();
        $table->text('short_desc')->nullable();
        $table->json('colors')->nullable();
        $table->json('sizes')->nullable();
        $table->string('default_size')->nullable();
        $table->string('long_desc_title')->nullable();
        $table->text('long_desc')->nullable();
        $table->json('features')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
