<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Carbon;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Voucher Promo';
    protected static ?string $modelLabel = 'Voucher';
    protected static ?string $pluralModelLabel = 'Voucher Promo';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Voucher')
                    ->schema([
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Voucher')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->helperText('Contoh: DISKON50, HEMAT100')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Voucher')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Pengaturan Diskon')
                    ->schema([
                        Forms\Components\Select::make('tipe_diskon')
                            ->label('Tipe Diskon')
                            ->options([
                                'persen' => 'Persentase (%)',
                                'nominal' => 'Nominal (Rp)',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('max_diskon', null)),
                        Forms\Components\TextInput::make('nilai_diskon')
                            ->label(fn (Get $get): string => $get('tipe_diskon') === 'persen' ? 'Persentase Diskon (%)' : 'Nominal Diskon (Rp)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(fn (Get $get): ?int => $get('tipe_diskon') === 'persen' ? 100 : null)
                            ->helperText(fn (Get $get): string => $get('tipe_diskon') === 'persen' ? 'Masukkan angka 1-100' : 'Masukkan nominal dalam Rupiah'),
                        Forms\Components\TextInput::make('max_diskon')
                            ->label('Maksimal Diskon (Rp)')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn (Get $get): bool => $get('tipe_diskon') === 'persen')
                            ->helperText('Kosongkan jika tidak ada batasan maksimal'),
                        Forms\Components\TextInput::make('min_pembelian')
                            ->label('Minimum Pembelian (Rp)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Total belanja minimum untuk menggunakan voucher'),
                    ])->columns(2),

                Forms\Components\Section::make('Masa Berlaku & Kuota')
                    ->schema([
                        Forms\Components\DateTimePicker::make('mulai_berlaku')
                            ->label('Mulai Berlaku')
                            ->required()
                            ->default(now()),
                        Forms\Components\DateTimePicker::make('berakhir_berlaku')
                            ->label('Berakhir Berlaku')
                            ->required()
                            ->after('mulai_berlaku'),
                        Forms\Components\TextInput::make('kuota_penggunaan')
                            ->label('Kuota Penggunaan Total')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Isi 0 untuk tanpa batas'),
                        Forms\Components\TextInput::make('max_penggunaan_per_user')
                            ->label('Maksimal Penggunaan Per User')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->helperText('Berapa kali setiap user dapat menggunakan voucher ini'),
                        Forms\Components\Toggle::make('is_aktif')
                            ->label('Voucher Aktif')
                            ->default(true)
                            ->helperText('Matikan untuk menonaktifkan voucher'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('tipe_diskon')
                    ->label('Diskon')
                    ->formatStateUsing(fn ($state, $record) => $record->getTipeDiskonLabel())
                    ->badge()
                    ->color(fn ($state) => $state === 'persen' ? 'warning' : 'success'),
                Tables\Columns\TextColumn::make('min_pembelian')
                    ->label('Min. Pembelian')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_dipakai')
                    ->label('Terpakai')
                    ->formatStateUsing(fn ($state, $record) => $record->kuota_penggunaan > 0 ? "{$state}/{$record->kuota_penggunaan}" : "{$state} (∞)"),
                Tables\Columns\TextColumn::make('berakhir_berlaku')
                    ->label('Berakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_badge')
                    ->label('Status')
                    ->formatStateUsing(fn ($record) => $record->getStatusBadge()['label'])
                    ->badge()
                    ->color(fn ($record) => $record->getStatusBadge()['class']),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_diskon')
                    ->options([
                        'persen' => 'Persentase',
                        'nominal' => 'Nominal',
                    ]),
                Tables\Filters\TernaryFilter::make('is_aktif')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
