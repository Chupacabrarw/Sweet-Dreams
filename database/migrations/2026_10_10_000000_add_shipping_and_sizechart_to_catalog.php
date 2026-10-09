<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Template size chart dinamis (dipakai dropdown form produk)
        if (!Schema::hasTable('size_charts')) {
            Schema::create('size_charts', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                // Contoh: {"S": {"dada": "80-84", "pinggang": "60-64"}, "M": {...}}
                $table->json('data')->nullable();
                $table->timestamps();
            });
        }

        // 2) Kolom pengiriman + deskripsi lengkap + relasi template di products.
        //    (berat sudah ada dari migration 2026_10_09; kolom nama mengikuti
        //    konvensi English yang sudah dipakai tabel ini: title/price/desc.)
        if (!Schema::hasColumn('products', 'long_desc')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('long_desc')->nullable()->after('short_desc');
            });
        }
        foreach (['length', 'width', 'height'] as $dim) {
            if (!Schema::hasColumn('products', $dim)) {
                Schema::table('products', function (Blueprint $table) use ($dim) {
                    $after = $dim === 'length' ? 'weight' : ($dim === 'width' ? 'length' : 'width');
                    $table->unsignedSmallInteger($dim)->nullable()->after($after);
                });
            }
        }
        if (!Schema::hasColumn('products', 'template_size_chart_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('template_size_chart_id')
                    ->nullable()->after('height')
                    ->constrained('size_charts')->nullOnDelete();
            });
        }

        // 3) Harga khusus per varian (Shopee: "beda ukuran beda harga")
        if (!Schema::hasColumn('product_variants', 'variant_price')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->unsignedInteger('variant_price')->nullable()->after('stock');
            });
        }

        // Seed 2 template bawaan agar dropdown langsung ada isinya
        $seeds = [
            'Celana Dalam' => [
                'S'  => ['pinggang' => '60-66 cm'],
                'M'  => ['pinggang' => '67-73 cm'],
                'L'  => ['pinggang' => '74-80 cm'],
                'XL' => ['pinggang' => '81-88 cm'],
            ],
            'Pijama' => [
                'S'  => ['dada' => '86-90 cm', 'pinggang' => '64-68 cm'],
                'M'  => ['dada' => '91-95 cm', 'pinggang' => '69-73 cm'],
                'L'  => ['dada' => '96-101 cm', 'pinggang' => '74-79 cm'],
                'XL' => ['dada' => '102-108 cm', 'pinggang' => '80-86 cm'],
            ],
        ];
        foreach ($seeds as $seedName => $seedData) {
            if (DB::table('size_charts')->where('name', $seedName)->exists()) {
                continue;
            }
            DB::table('size_charts')->insert([
                'name' => $seedName,
                'data' => json_encode($seedData),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('variant_price');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('template_size_chart_id');
            $table->dropColumn(['long_desc', 'length', 'width', 'height']);
        });
        Schema::dropIfExists('size_charts');
    }
};
