<?php

namespace App\Filament\Resources\PengaturanPembayaranResource\Pages;

use App\Filament\Resources\PengaturanPembayaranResource;
use App\Models\PengaturanPembayaran;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ManagePengaturanPembayaran extends Page
{
    protected static string $resource = PengaturanPembayaranResource::class;

    protected static string $view = 'filament.pages.pengaturan-pembayaran';

    public array $data = [];

    protected $listeners = [
        'upload-qris-qr-image' => 'handleQrisUpload',
        'upload-dana-qr-image' => 'handleDanaUpload',
    ];

    public function mount(): void
    {
        $this->data = [
            'qris_qr_image' => PengaturanPembayaran::get('qris', 'qr_image', ''),
            'qris_description' => PengaturanPembayaran::get('qris', 'description', 'Scan QR Code di bawah menggunakan aplikasi e-wallet atau mobile banking Anda'),
            'bank_1_name' => PengaturanPembayaran::get('transfer_bank', 'bank_1_name', ''),
            'bank_1_number' => PengaturanPembayaran::get('transfer_bank', 'bank_1_number', ''),
            'bank_1_holder' => PengaturanPembayaran::get('transfer_bank', 'bank_1_holder', ''),
            'bank_2_name' => PengaturanPembayaran::get('transfer_bank', 'bank_2_name', ''),
            'bank_2_number' => PengaturanPembayaran::get('transfer_bank', 'bank_2_number', ''),
            'bank_2_holder' => PengaturanPembayaran::get('transfer_bank', 'bank_2_holder', ''),
            'dana_qr_image' => PengaturanPembayaran::get('dana', 'qr_image', ''),
            'dana_phone' => PengaturanPembayaran::get('dana', 'phone_number', ''),
            'dana_name' => PengaturanPembayaran::get('dana', 'account_name', ''),
        ];
    }

    public function handleQrisUpload($data): void
    {
        $this->data['qris_qr_image'] = $this->saveImage($data, 'qris');
    }

    public function handleDanaUpload($data): void
    {
        $this->data['dana_qr_image'] = $this->saveImage($data, 'dana');
    }

    private function saveImage(string $dataUrl, string $prefix): string
    {
        if (str_starts_with($dataUrl, 'data:image/')) {
            $imageData = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));
            $filename = $prefix . '_' . time() . '.png';
            Storage::disk(config('filesystems.cloud', 'public'))->put('pembayaran/' . $filename, $imageData);
            return 'pembayaran/' . $filename;
        }
        return '';
    }

    public function save(): void
    {
        PengaturanPembayaran::set('qris', 'qr_image', $this->data['qris_qr_image'] ?? '');
        PengaturanPembayaran::set('qris', 'description', $this->data['qris_description'] ?? '');
        PengaturanPembayaran::set('transfer_bank', 'bank_1_name', $this->data['bank_1_name'] ?? '');
        PengaturanPembayaran::set('transfer_bank', 'bank_1_number', $this->data['bank_1_number'] ?? '');
        PengaturanPembayaran::set('transfer_bank', 'bank_1_holder', $this->data['bank_1_holder'] ?? '');
        PengaturanPembayaran::set('transfer_bank', 'bank_2_name', $this->data['bank_2_name'] ?? '');
        PengaturanPembayaran::set('transfer_bank', 'bank_2_number', $this->data['bank_2_number'] ?? '');
        PengaturanPembayaran::set('transfer_bank', 'bank_2_holder', $this->data['bank_2_holder'] ?? '');
        PengaturanPembayaran::set('dana', 'qr_image', $this->data['dana_qr_image'] ?? '');
        PengaturanPembayaran::set('dana', 'phone_number', $this->data['dana_phone'] ?? '');
        PengaturanPembayaran::set('dana', 'account_name', $this->data['dana_name'] ?? '');

        Notification::make()
            ->success()
            ->title('Pengaturan pembayaran berhasil disimpan')
            ->send();
    }
}
