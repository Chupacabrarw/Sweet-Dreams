<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nominal yang harus dibayar = total + kode unik (pencocokan mutasi QRIS)
            $table->unsignedSmallInteger('payment_unique_code')->nullable()->after('payment_expired_at');
            $table->unsignedInteger('payment_amount')->nullable()->after('payment_unique_code');

            // Bukti bayar dari pembeli + hasil verifikasi admin
            $table->string('payment_proof')->nullable()->after('payment_amount');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_proof_uploaded_at');
            $table->string('payment_note')->nullable()->after('payment_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_unique_code', 'payment_amount', 'payment_proof',
                'payment_proof_uploaded_at', 'payment_verified_at', 'payment_note',
            ]);
        });
    }
};
