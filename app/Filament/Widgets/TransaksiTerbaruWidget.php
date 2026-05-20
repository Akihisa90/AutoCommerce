<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TransaksiTerbaruWidget extends TableWidget
{
    protected static ?int $sort = 0;
    protected static ?string $heading = 'Transaksi Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()
                    ->with(['user', 'detailTransaksi'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No. Transaksi')
                    ->formatStateUsing(fn ($state) => '#TRX' . str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pembeli')
                    ->label('Pembeli')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'diproses',
                        'primary' => 'dikirim',
                        'success' => 'selesai',
                        'danger' => 'dibatalkan',
                    ]),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Pembayaran')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'cod' => 'COD',
                        'qris' => 'QRIS',
                        'dana' => 'DANA',
                        'transfer_bank' => 'Transfer Bank',
                        default => $state ?? '-',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn (Transaksi $record) => url('admin/transaksis/' . $record->id))
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}
