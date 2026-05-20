<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_diskon', ['persen', 'nominal']);
            $table->decimal('nilai_diskon', 14, 2);
            $table->decimal('min_pembelian', 14, 2)->default(0);
            $table->decimal('max_diskon', 14, 2)->nullable();
            $table->integer('kuota_penggunaan')->default(0);
            $table->integer('jumlah_dipakai')->default(0);
            $table->datetime('mulai_berlaku');
            $table->datetime('berakhir_berlaku');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
