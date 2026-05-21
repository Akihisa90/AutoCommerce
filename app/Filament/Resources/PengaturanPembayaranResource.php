<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaturanPembayaranResource\Pages;
use App\Models\PengaturanPembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengaturanPembayaranResource extends Resource
{
    protected static ?string $model = PengaturanPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pengaturan Pembayaran';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('metode')
                    ->options([
                        'qris' => 'QRIS',
                        'transfer_bank' => 'Transfer Bank',
                        'dana' => 'DANA',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('key')
                    ->label('Key')
                    ->required(),
                Forms\Components\Textarea::make('value')
                    ->label('Value')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metode')
                    ->badge()
                    ->colors([
                        'primary' => 'qris',
                        'warning' => 'transfer_bank',
                        'info' => 'dana',
                    ]),
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(40)
                    ->copyable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metode')
                    ->options([
                        'qris' => 'QRIS',
                        'transfer_bank' => 'Transfer Bank',
                        'dana' => 'DANA',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePengaturanPembayaran::route('/'),
        ];
    }
}
