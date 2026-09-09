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
        Schema::create('vpn_accounts', function (Blueprint $table) {
            $table->string('vpn_ip')->nullable();
            $table->id();

            // Customer pemilik akun VPN
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Server MikroTik yang digunakan
            $table->foreignId('server_id')
                ->constrained('servers')
                ->cascadeOnDelete();

            // Jenis VPN: l2tp / sstp
            $table->string('vpn_type');

            // Username dan password VPN
            $table->string('username');
            $table->string('password');

            // Status akun VPN
            $table->string('status')->default('active');

            // Waktu mulai dan berakhir masa aktif
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vpn_accounts');
    }
};
