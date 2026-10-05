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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('payment_url')->nullable()->after('payment_reference');
            $table->string('va_number')->nullable()->after('payment_url');
            $table->text('qr_string')->nullable()->after('va_number');
            $table->timestamp('payment_expired_at')->nullable()->after('qr_string');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_url', 'va_number', 'qr_string', 'payment_expired_at']);
        });
    }
};
