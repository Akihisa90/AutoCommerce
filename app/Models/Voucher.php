<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'kode', 'nama', 'deskripsi', 'tipe_diskon', 'nilai_diskon',
        'min_pembelian', 'max_diskon', 'kuota_penggunaan', 'max_penggunaan_per_user',
        'jumlah_dipakai', 'mulai_berlaku', 'berakhir_berlaku', 'is_aktif'
    ];

    protected $casts = [
        'nilai_diskon' => 'decimal:2',
        'min_pembelian' => 'decimal:2',
        'max_diskon' => 'decimal:2',
        'mulai_berlaku' => 'datetime',
        'berakhir_berlaku' => 'datetime',
        'is_aktif' => 'boolean',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_aktif) return false;

        $now = now();
        if ($now < $this->mulai_berlaku || $now > $this->berakhir_berlaku) return false;

        if ($this->kuota_penggunaan > 0 && $this->jumlah_dipakai >= $this->kuota_penggunaan) return false;

        return true;
    }

    public function canApply(float|int $subtotal, ?int $userId = null): bool
    {
        if (!$this->isValid()) return false;
        if ($subtotal < $this->min_pembelian) return false;

        if ($userId && $this->max_penggunaan_per_user > 0) {
            $userUsage = $this->transaksis()->where('user_id', $userId)->count();
            if ($userUsage >= $this->max_penggunaan_per_user) return false;
        }

        return true;
    }

    public function calculateDiscount(float|int $subtotal): float
    {
        $nilaiDiskon = (float) $this->nilai_diskon;

        if ($this->tipe_diskon === 'persen') {
            $discount = ($subtotal * $nilaiDiskon) / 100;
            $maxDiskon = $this->max_diskon ? (float) $this->max_diskon : null;
            if ($maxDiskon && $discount > $maxDiskon) {
                $discount = $maxDiskon;
            }
            return min($discount, $subtotal);
        }

        return min($nilaiDiskon, $subtotal);
    }

    public function getUserUsageCount(int $userId): int
    {
        return $this->transaksis()->where('user_id', $userId)->count();
    }

    public function getRemainingUserQuota(int $userId): int
    {
        if ($this->max_penggunaan_per_user <= 0) return -1;
        return max(0, $this->max_penggunaan_per_user - $this->getUserUsageCount($userId));
    }

    public function getTipeDiskonLabel(): string
    {
        if ($this->tipe_diskon === 'persen') {
            return $this->nilai_diskon . '%';
        }
        return 'Rp ' . number_format($this->nilai_diskon, 0, ',', '.');
    }

    public function getRemainingQuota(): int
    {
        if ($this->kuota_penggunaan <= 0) return -1;
        return max(0, $this->kuota_penggunaan - $this->jumlah_dipakai);
    }

    public function getStatusBadge(): array
    {
        if (!$this->is_aktif) {
            return ['label' => 'Nonaktif', 'class' => 'bg-secondary'];
        }

        $now = now();
        if ($now < $this->mulai_berlaku) {
            return ['label' => 'Belum Berlaku', 'class' => 'bg-info'];
        }
        if ($now > $this->berakhir_berlaku) {
            return ['label' => 'Kadaluarsa', 'class' => 'bg-danger'];
        }
        if ($this->kuota_penggunaan > 0 && $this->jumlah_dipakai >= $this->kuota_penggunaan) {
            return ['label' => 'Kuota Habis', 'class' => 'bg-warning text-dark'];
        }

        return ['label' => 'Aktif', 'class' => 'bg-success'];
    }
}
