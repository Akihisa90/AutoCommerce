<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembeli')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pembeli')
                            ->label('Nama Pembeli')
                            ->disabled(),
                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->disabled(),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
                Forms\Components\Section::make('Detail Transaksi')
                    ->schema([
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cod' => 'COD (Bayar di Tempat)',
                                'qris' => 'QRIS',
                                'dana' => 'DANA',
                                'transfer_bank' => 'Transfer Bank',
                            ])
                            ->disabled(),
                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->disabled()
                            ->prefix('Rp'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No. Transaksi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pembeli')
                    ->label('Pembeli')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'cod' => 'COD',
                        'qris' => 'QRIS',
                        'dana' => 'DANA',
                        'transfer_bank' => 'Transfer',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'cod',
                        'primary' => 'qris',
                        'info' => 'dana',
                        'warning' => 'transfer_bank',
                    ]),
                Tables\Columns\IconColumn::make('bukti_pembayaran')
                    ->label('Bukti')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->tooltip(function ($state): string {
                        return $state ? 'Bukti sudah diupload' : 'Belum ada bukti';
                    }),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cod' => 'COD',
                        'qris' => 'QRIS',
                        'dana' => 'DANA',
                        'transfer_bank' => 'Transfer Bank',
                    ]),
                Tables\Filters\TernaryFilter::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->trueLabel('Sudah Upload')
                    ->falseLabel('Belum Upload')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('bukti_pembayaran'),
                        false: fn ($query) => $query->whereNull('bukti_pembayaran'),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required(),
                    ])
                    ->action(function (Transaksi $record, array $data): void {
                        $record->update(['status' => $data['status']]);
                    }),
                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Transaksi $record): bool => $record->bukti_pembayaran && $record->status === 'pending')
                    ->modalHeading('Verifikasi Bukti Pembayaran')
                    ->modalWidth('lg')
                    ->form([
                        Forms\Components\View::make('bukti_pembayaran_preview')
                            ->label('Bukti Pembayaran')
                            ->view('filament.components.bukti-pembayaran-preview'),
                        Forms\Components\Select::make('tindakan')
                            ->options([
                                'diterima' => '✅ Diterima - Pembayaran sesuai, pesanan diproses',
                                'ditolak' => '❌ Ditolak - Pembayaran tidak sesuai',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Admin')
                            ->placeholder('Tambahkan catatan jika diperlukan...')
                            ->rows(3),
                    ])
                    ->action(function (Transaksi $record, array $data): void {
                        if ($data['tindakan'] === 'diterima') {
                            $record->update(['status' => 'diproses']);
                        }
                    })
                    ->successNotificationTitle(fn (array $data): string =>
                        $data['tindakan'] === 'diterima'
                            ? 'Pembayaran diverifikasi. Pesanan diproses.'
                            : 'Pembayaran ditolak.'
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'view' => Pages\ViewTransaksi::route('/{record}'),
        ];
    }
}
