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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();

            // Jenis pembayaran: bank / qris / ewallet
            $table->string('type', 30);

            // Nama metode pembayaran
            // Contoh: BCA, BRI, QRIS, DANA
            $table->string('name', 100);

            // Nomor rekening / nomor tujuan pembayaran
            $table->string('account_number', 100)->nullable();

            // Nama pemilik rekening
            $table->string('account_name', 150)->nullable();

            // Lokasi file gambar QRIS
            $table->string('qris_image')->nullable();

            // Aktif / tidak aktif
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Mempercepat pencarian metode pembayaran aktif
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
