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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Customer pemilik transaksi
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Jenis transaksi: topup / vpn_purchase
            $table->string('type', 50);

            // Nominal transaksi
            $table->decimal('amount', 15, 2);

            // Status transaksi
            // pending / approved / rejected
            $table->string('status', 30)
                ->default('pending');

            // Keterangan transaksi
            $table->string('description')->nullable();

            // Metode pembayaran untuk top up
            $table->string('payment_method')->nullable();

            // Bukti pembayaran
            $table->string('proof')->nullable();

            // Admin yang melakukan konfirmasi
            $table->foreignId('confirmed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Waktu konfirmasi admin
            $table->timestamp('confirmed_at')
                ->nullable();

            $table->timestamps();

            // Index agar pencarian transaksi lebih cepat
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
