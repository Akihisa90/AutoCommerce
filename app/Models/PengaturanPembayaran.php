<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPembayaran extends Model
{
    protected $table = 'pengaturan_pembayaran';
    protected $fillable = ['metode', 'key', 'value'];

    public static function get($metode, $key, $default = null)
    {
        $record = static::where('metode', $metode)->where('key', $key)->first();
        return $record?->value ?? $default;
    }

    public static function set($metode, $key, $value)
    {
        return static::updateOrCreate(
            ['metode' => $metode, 'key' => $key],
            ['value' => $value]
        );
    }
}
