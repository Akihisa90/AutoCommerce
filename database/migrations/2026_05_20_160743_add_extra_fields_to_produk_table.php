<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('rating', 3, 2)->default(0)->after('harga');
            $table->integer('review_count')->default(0)->after('rating');
            $table->string('brand')->nullable()->after('kategori_id');
            $table->string('warna')->nullable()->after('brand');
            $table->string('ukuran')->nullable()->after('warna');
            $table->string('bahan')->nullable()->after('ukuran');
            $table->string('label')->nullable()->after('bahan');
            $table->boolean('gratis_ongkir')->default(false)->after('label');
            $table->boolean('same_day_delivery')->default(false)->after('gratis_ongkir');
            $table->decimal('harga_diskon', 12, 2)->nullable()->after('harga');
            $table->integer('terjual')->default(0)->after('stok');
            $table->integer('dilihat')->default(0)->after('terjual');
            $table->timestamp('published_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn([
                'rating', 'review_count', 'brand', 'warna', 'ukuran',
                'bahan', 'label', 'gratis_ongkir', 'same_day_delivery',
                'harga_diskon', 'terjual', 'dilihat', 'published_at'
            ]);
        });
    }
};
