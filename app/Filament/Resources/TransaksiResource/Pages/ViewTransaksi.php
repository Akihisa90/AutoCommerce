<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;

class ViewTransaksi extends ViewRecord
{
    protected static string $resource = TransaksiResource::class;
    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\Action::make('updateStatus')
                ->label('Update Status')
                ->icon('heroicon-o-arrow-path')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'diproses' => 'Diproses',
                            'dikirim' => 'Dikirim',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ])->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update(['status' => $data['status']]);
                    $this->dispatch('close-modal');
                }),
        ];

        // Add verification actions for orders with uploaded proof
        if ($this->record->bukti_pembayaran && $this->record->status === 'pending') {
            $actions[] = Actions\Action::make('verifikasiBukti')
                ->label('Verifikasi Bukti Pembayaran')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->modalHeading('Verifikasi Bukti Pembayaran')
                ->modalDescription('Periksa bukti pembayaran dan pilih tindakan')
                ->modalWidth('lg')
                ->form([
                    \Filament\Forms\Components\View::make('bukti_pembayaran_preview')
                        ->label('Bukti Pembayaran')
                        ->view('filament.components.bukti-pembayaran-preview'),
                    \Filament\Forms\Components\Select::make('tindakan')
                        ->options([
                            'diterima' => '✅ Diterima - Pembayaran sesuai',
                            'ditolak' => '❌ Ditolak - Pembayaran tidak sesuai',
                        ])
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('catatan')
                        ->label('Catatan Admin')
                        ->placeholder('Tambahkan catatan jika diperlukan...')
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    if ($data['tindakan'] === 'diterima') {
                        $this->record->update([
                            'status' => 'diproses',
                        ]);
                        $this->dispatch('notification', type: 'success', message: 'Pembayaran diverifikasi. Pesanan diproses.');
                    } else {
                        $this->record->update([
                            'status' => 'pending',
                        ]);
                        $this->dispatch('notification', type: 'warning', message: 'Pembayaran ditolak. User akan dihubungi.');
                    }
                });
        }

        return $actions;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Components\Section::make('Informasi Pembeli')->schema([
                Components\TextEntry::make('nama_pembeli')->label('Nama Pembeli'),
                Components\TextEntry::make('user.email')->label('Email'),
                Components\TextEntry::make('no_telepon')->label('No. Telepon'),
                Components\TextEntry::make('alamat')->label('Alamat'),
            ])->columns(2),

            Components\Section::make('Detail Pembayaran')->schema([
                Components\TextEntry::make('total')->label('Total')->money('IDR'),
                Components\TextEntry::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'cod' => 'COD',
                        'qris' => 'QRIS',
                        'dana' => 'DANA',
                        'transfer_bank' => 'Transfer Bank',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'cod',
                        'primary' => 'qris',
                        'info' => 'dana',
                        'warning' => 'transfer_bank',
                    ]),
                Components\TextEntry::make('sub_tipe_pembayaran')
                    ->label('Sub Tipe Pembayaran')
                    ->formatStateUsing(fn (?string $state): string => match($state) {
                        'nomor_hp' => 'Via Nomor HP',
                        'qr_code' => 'Via QR Code',
                        default => '-',
                    })
                    ->visible(fn (?string $state): bool => $state !== null),
                Components\TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'diproses',
                        'primary' => 'dikirim',
                        'success' => 'selesai',
                        'danger' => 'dibatalkan',
                    ]),
                Components\TextEntry::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d M Y H:i'),
            ])->columns(2),

            Components\Section::make('Bukti Pembayaran')
                ->visible(fn (Components\Section $component): bool => !empty($component->getRecord()->bukti_pembayaran))
                ->schema([
                    Components\ImageEntry::make('bukti_pembayaran')
                        ->label('Bukti Pembayaran')
                        ->disk('public')
                        ->width(300),
                ]),
        ]);
    }
}
