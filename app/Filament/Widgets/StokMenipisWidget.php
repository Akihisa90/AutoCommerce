<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class StokMenipisWidget extends TableWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Stok Menipis';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Produk::query()
                    ->with('kategori')
                    ->where('stok', '<=', 5)
                    ->orderBy('stok')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->color(fn ($state) => $state <= 2 ? 'danger' : 'warning')
                    ->weight(fn ($state) => $state <= 2 ? 'bold' : 'normal'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->url(fn (Produk $record) => url('admin/produks/' . $record->id . '/edit'))
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning'),
            ])
            ->defaultSort('stok')
            ->paginated(false);
    }
}
