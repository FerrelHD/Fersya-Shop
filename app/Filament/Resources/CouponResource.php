<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Kupon & Promo';
    protected static ?string $modelLabel = 'Kupon Diskon';
    protected static ?string $pluralModelLabel = 'Kupon Diskon';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode Kupon')
                    ->placeholder('Contoh: FERSYA10')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->deferLoading(),
                Forms\Components\Select::make('type')
                    ->label('Tipe Diskon')
                    ->options([
                        'percent' => 'Persentase (%)',
                        'fixed' => 'Nominal Tetap (Rp)',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->label('Nilai Diskon')
                    ->helperText('Jika persentase, isi 10 untuk 10%. Jika nominal, isi 15000.')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('min_spend')
                    ->label('Minimal Belanja (Rp)')
                    ->default(0)
                    ->numeric(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->weight('bold')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'percent' ? 'info' : 'success')
                    ->formatStateUsing(fn (string $state): string => $state === 'percent' ? 'Persentase (%)' : 'Nominal Tetap (Rp)'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($record) => $record->type === 'percent' ? "{$record->value}%" : "Rp " . number_format($record->value, 0, ',', '.')),
                Tables\Columns\TextColumn::make('min_spend')
                    ->label('Min. Belanja')
                    ->money('IDR'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
