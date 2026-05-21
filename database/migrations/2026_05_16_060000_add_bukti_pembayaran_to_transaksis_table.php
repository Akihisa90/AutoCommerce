<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->enum('sub_tipe_pembayaran', ['nomor_hp', 'qr_code'])->nullable()->after('metode_pembayaran');
            $table->string('bukti_pembayaran')->nullable()->after('sub_tipe_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['sub_tipe_pembayaran', 'bukti_pembayaran']);
        });
    }
};
