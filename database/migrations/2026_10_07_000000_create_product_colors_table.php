<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('slug', 80)->unique();
            $table->string('hex', 7);
            $table->timestamps();
        });

        $colorsBySlug = [];
        foreach (DB::table('products')->pluck('colors') as $encodedColors) {
            foreach (json_decode($encodedColors, true) ?: [] as $name) {
                $name = trim((string) $name);
                $slug = Str::slug($name);

                if ($name !== '' && $slug !== '') {
                    $colorsBySlug[$slug] = $name;
                }
            }
        }

        foreach (DB::table('product_variants')->distinct()->pluck('color') as $name) {
            $name = trim((string) $name);
            $slug = Str::slug($name);

            if ($name !== '' && $slug !== '') {
                $colorsBySlug[$slug] = $name;
            }
        }

        $knownHex = [
            'pink' => '#E8A0B0',
            'gold' => '#D4A854',
            'white' => '#F5F0EC',
            'cream' => '#EEDFC8',
            'grey' => '#6A6A7A',
            'gray' => '#6A6A7A',
            'dark blue' => '#27385D',
            'black' => '#272329',
            'hitam' => '#272329',
            'red' => '#C53C50',
            'merah' => '#C53C50',
            'blue' => '#4C74A5',
            'biru' => '#4C74A5',
            'green' => '#54836C',
            'hijau' => '#54836C',
            'purple' => '#8665A7',
            'ungu' => '#8665A7',
            'navy' => '#27385D',
            'brown' => '#80604B',
            'cokelat' => '#80604B',
            'beige' => '#D9C5A1',
            'maroon' => '#702D3E',
        ];

        foreach ($colorsBySlug as $slug => $name) {
            $tableName = strtolower($name);
            DB::table('product_colors')->insert([
                'name' => $name,
                'slug' => $slug,
                'hex' => $knownHex[$tableName] ?? '#B58D97',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};
