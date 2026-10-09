<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom mati hasil audit: 100% NULL dan nol referensi di app/views.
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['long_desc_title', 'features']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('long_desc_title')->nullable()->after('long_desc');
            $table->json('features')->nullable()->after('long_desc_title');
        });
    }
};
