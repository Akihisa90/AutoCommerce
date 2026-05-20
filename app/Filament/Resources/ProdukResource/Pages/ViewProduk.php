<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\ImageEntry;

class ViewProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Components\Section::make('Detail Produk')->schema([
                Components\TextEntry::make('nama')
                    ->label('Nama Produk'),
                Components\TextEntry::make('kategori.nama')
                    ->label('Kategori'),
                Components\TextEntry::make('harga')
                    ->label('Harga')
                    ->money('IDR'),
                Components\TextEntry::make('stok')
                    ->label('Stok')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    }),
                Components\TextEntry::make('deskripsi')
                    ->label('Deskripsi'),
                Components\ImageEntry::make('gambar')
                    ->label('Gambar Produk')
                    ->disk('public')
                    ->circular()
                    ->size(200)
                    ->alignment('center'),
                Components\TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])->columns(2),
        ]);
    }
}
