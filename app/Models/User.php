<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
// SEKARANG SUDAH ADA TANDA TITIK KOMA (;) DI AKHIR BARIS INI
use Filament\Models\Contracts\FilamentUser; 

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telepon',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Izinkan akses ke Filament Admin Panel hanya untuk role 'admin'
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function reviews()
    {
        return $this->hasMany(ProdukReview::class);
    }
    public function getNamaAttribute()
    {
        return $this->name;
    }
}
