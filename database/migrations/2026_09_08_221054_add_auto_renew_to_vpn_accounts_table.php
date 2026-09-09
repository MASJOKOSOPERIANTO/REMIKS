<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('vpn_accounts', function (Blueprint $table) {
            $table->boolean('auto_renew')
                ->default(false)
                ->after('status');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('vpn_accounts', function (Blueprint $table) {
            $table->dropColumn('auto_renew');
        });
    }
};
