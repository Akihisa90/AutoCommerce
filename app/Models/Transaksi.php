<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'user_id', 'voucher_id', 'nama_pembeli', 'no_telepon', 'alamat',
        'metode_pembayaran', 'sub_tipe_pembayaran', 'bukti_pembayaran',
        'total', 'diskon_voucher', 'total_bayar', 'status'
    ];
    protected $casts = [
        'total' => 'decimal:2',
        'diskon_voucher' => 'decimal:2',
        'total_bayar' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProdukReview::class);
    }
}
