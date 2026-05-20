<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('transaksi_id')->constrained('transaksi')->cascadeOnDelete();
            $table->foreignId('detail_transaksi_id')->constrained('detail_transaksi')->cascadeOnDelete();
            $table->tinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'produk_id', 'transaksi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_reviews');
    }
};
