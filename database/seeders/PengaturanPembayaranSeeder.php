<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanPembayaran;

class PengaturanPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // QRIS
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'qris', 'key' => 'qr_content'],
            ['value' => '']
        );
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'qris', 'key' => 'description'],
            ['value' => 'Scan QR Code di bawah menggunakan aplikasi e-wallet atau mobile banking Anda']
        );

        // Transfer Bank - BCA
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'transfer_bank', 'key' => 'bank_1_name'],
            ['value' => 'BCA']
        );
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'transfer_bank', 'key' => 'bank_1_number'],
            ['value' => '1234567890']
        );
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'transfer_bank', 'key' => 'bank_1_holder'],
            ['value' => 'PT Otomotif Shop']
        );

        // Transfer Bank - BNI
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'transfer_bank', 'key' => 'bank_2_name'],
            ['value' => 'BNI']
        );
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'transfer_bank', 'key' => 'bank_2_number'],
            ['value' => '0987654321']
        );
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'transfer_bank', 'key' => 'bank_2_holder'],
            ['value' => 'PT Otomotif Shop']
        );

        // DANA
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'dana', 'key' => 'phone_number'],
            ['value' => '081234567890']
        );
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'dana', 'key' => 'account_name'],
            ['value' => 'Otomotif Shop']
        );
        PengaturanPembayaran::updateOrCreate(
            ['metode' => 'dana', 'key' => 'qr_content'],
            ['value' => '']
        );
    }
}
