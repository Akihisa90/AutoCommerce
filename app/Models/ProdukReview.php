<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukReview extends Model
{
    protected $table = 'produk_reviews';
    protected $fillable = [
        'user_id', 'produk_id', 'transaksi_id', 'detail_transaksi_id',
        'rating', 'komentar'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function detailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class);
    }
}
