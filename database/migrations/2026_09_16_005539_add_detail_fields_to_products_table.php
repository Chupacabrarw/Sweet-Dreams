<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('gallery')->nullable()->after('image');
            $table->string('long_desc_title')->nullable()->after('short_desc');
            $table->text('long_desc')->nullable()->after('long_desc_title');
            $table->json('features')->nullable()->after('long_desc');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['gallery', 'long_desc_title', 'long_desc', 'features']);
        });
    }
};