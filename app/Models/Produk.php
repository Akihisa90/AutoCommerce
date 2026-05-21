<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'nama', 'kategori_id', 'harga', 'stok', 'deskripsi', 'gambar',
        'rating', 'review_count', 'brand', 'warna', 'ukuran', 'bahan',
        'label', 'gratis_ongkir', 'same_day_delivery', 'harga_diskon',
        'terjual', 'dilihat', 'published_at'
    ];
    protected $casts = [
        'harga' => 'decimal:2',
        'harga_diskon' => 'decimal:2',
        'rating' => 'decimal:2',
        'gratis_ongkir' => 'boolean',
        'same_day_delivery' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProdukReview::class);
    }

    public function updateRating()
    {
        $avgRating = $this->reviews()->avg('rating');
        $reviewCount = $this->reviews()->count();
        $this->update([
            'rating' => $avgRating ?? 0,
            'review_count' => $reviewCount
        ]);
    }

    public function isInWishlist($userId): bool
    {
        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    public function getHargaDiskonPercent(): ?int
    {
        if (!$this->harga_diskon || $this->harga_diskon >= $this->harga) {
            return null;
        }
        return (int) round((($this->harga - $this->harga_diskon) / $this->harga) * 100);
    }

    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    public function scopeBestSeller($query)
    {
        return $query->orderBy('terjual', 'desc');
    }

    public function scopeTerbaru($query)
    {
        return $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
    }

    public function scopeRatingTertinggi($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function imageUrl(): ?string
    {
        if (!$this->gambar) {
            return null;
        }
        return Storage::disk('public')->url($this->gambar);
    }
}
