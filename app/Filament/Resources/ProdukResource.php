<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('kategori_id')
                            ->label('Kategori')
                            ->relationship('kategori', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('harga')
                            ->label('Harga Normal')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->live(debounce: 300),
                        Forms\Components\TextInput::make('harga_diskon')
                            ->label('Harga Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->helperText('Kosongkan jika tidak ada diskon')
                            ->live(debounce: 300),
                        Forms\Components\Placeholder::make('diskon_info')
                            ->label('Persentase Diskon')
                            ->hidden(fn (Forms\Get $get): bool => !$get('harga') || !$get('harga_diskon'))
                            ->content(fn (Forms\Get $get): string => $get('harga') && $get('harga_diskon') && $get('harga_diskon') < $get('harga')
                                ? number_format((($get('harga') - $get('harga_diskon')) / $get('harga')) * 100, 0) . '% OFF'
                                : 'Tidak ada diskon'),
                        Forms\Components\TextInput::make('stok')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Produk')
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Merek/Brand')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('warna')
                            ->label('Warna')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('ukuran')
                            ->label('Ukuran')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('bahan')
                            ->label('Bahan')
                            ->maxLength(100),
                    ])->columns(2),

                Forms\Components\Section::make('Label & Pengiriman')
                    ->schema([
                        Forms\Components\Select::make('label')
                            ->label('Label Khusus')
                            ->options([
                                'best_seller' => 'Best Seller',
                                'diskon' => 'Diskon',
                                'baru' => 'Baru',
                                'stok_terbatas' => 'Stok Terbatas',
                                'flash_sale' => 'Flash Sale',
                            ])
                            ->nullable()
                            ->searchable(),
                        Forms\Components\Toggle::make('gratis_ongkir')
                            ->label('Gratis Ongkir')
                            ->default(false),
                        Forms\Components\Toggle::make('same_day_delivery')
                            ->label('Same Day Delivery')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('gambar')
                    ->label('Gambar Produk')
                    ->image()
                    ->directory('produk')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth(800)
                    ->imageResizeTargetHeight(800)
                    ->imagePreviewHeight('250')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('center bottom')
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames(false)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->disk('public')
                    ->circular()
                    ->size(70),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_diskon')
                    ->label('Harga Diskon')
                    ->money('IDR', true)
                    ->sortable()
                    ->toggleable()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'best_seller' => 'Best Seller',
                        'diskon' => 'Diskon',
                        'baru' => 'Baru',
                        'stok_terbatas' => 'Stok Terbatas',
                        'flash_sale' => 'Flash Sale',
                        default => $state ?? '-'
                    })
                    ->color(fn ($state) => match($state) {
                        'best_seller' => 'danger',
                        'diskon' => 'warning',
                        'baru' => 'info',
                        'stok_terbatas' => 'warning',
                        'flash_sale' => 'danger',
                        default => 'secondary'
                    }),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('terjual')
                    ->label('Terjual')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_id')
                    ->relationship('kategori', 'nama')
                    ->label('Filter Kategori'),
                Tables\Filters\SelectFilter::make('label')
                    ->label('Filter Label')
                    ->options([
                        'best_seller' => 'Best Seller',
                        'diskon' => 'Diskon',
                        'baru' => 'Baru',
                        'stok_terbatas' => 'Stok Terbatas',
                        'flash_sale' => 'Flash Sale',
                    ]),
                Tables\Filters\TernaryFilter::make('gratis_ongkir')
                    ->label('Gratis Ongkir'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('setDiskon')
                    ->label('Diskon')
                    ->icon('heroicon-o-tag')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('tipe_diskon')
                            ->label('Tipe Diskon')
                            ->options([
                                'persen' => 'Persentase (%)',
                                'nominal' => 'Nominal (Rp)',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('nilai_diskon')
                            ->label(fn (Get $get): string => $get('tipe_diskon') === 'persen' ? 'Persentase (%)' : 'Nominal (Rp)')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function (Produk $record, array $data) {
                        if ($data['tipe_diskon'] === 'persen') {
                            $diskon = ($record->harga * $data['nilai_diskon']) / 100;
                            $record->update([
                                'harga_diskon' => $record->harga - $diskon,
                                'label' => $record->label ?? 'diskon',
                            ]);
                        } else {
                            $hargaBaru = $record->harga - $data['nilai_diskon'];
                            if ($hargaBaru > 0) {
                                $record->update([
                                    'harga_diskon' => $hargaBaru,
                                    'label' => $record->label ?? 'diskon',
                                ]);
                            }
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setDiskon')
                        ->label('Berikan Diskon')
                        ->icon('heroicon-o-tag')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Select::make('tipe_diskon')
                                ->label('Tipe Diskon')
                                ->options([
                                    'persen' => 'Persentase (%)',
                                    'nominal' => 'Nominal (Rp)',
                                ])
                                ->required()
                                ->live(),
                            Forms\Components\TextInput::make('nilai_diskon')
                                ->label(fn (Forms\Get $get): string => $get('tipe_diskon') === 'persen' ? 'Persentase (%)' : 'Nominal (Rp)')
                                ->required()
                                ->numeric()
                                ->minValue(1),
                        ])
                        ->action(function (array $data, \Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $produk) {
                                if ($data['tipe_diskon'] === 'persen') {
                                    $diskon = ($produk->harga * $data['nilai_diskon']) / 100;
                                    $produk->update([
                                        'harga_diskon' => $produk->harga - $diskon,
                                        'label' => $produk->label ?? 'diskon',
                                    ]);
                                } else {
                                    $hargaBaru = $produk->harga - $data['nilai_diskon'];
                                    if ($hargaBaru > 0) {
                                        $produk->update([
                                            'harga_diskon' => $hargaBaru,
                                            'label' => $produk->label ?? 'diskon',
                                        ]);
                                    }
                                }
                            }
                        }),
                    Tables\Actions\BulkAction::make('hapusDiskon')
                        ->label('Hapus Diskon')
                        ->icon('heroicon-o-x-circle')
                        ->color('secondary')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $produk) {
                                $produk->update([
                                    'harga_diskon' => null,
                                ]);
                            }
                        }),
                ]),
            ]);
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'view' => Pages\ViewProduk::route('/{record}'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
