<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Ulasan';
    protected static ?string $modelLabel = 'Ulasan';
    protected static ?string $pluralModelLabel = 'Ulasan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Ulasan')
                    ->schema([
                        Forms\Components\TextInput::make('product.name')
                            ->label('Produk')
                            ->disabled(),
                        Forms\Components\TextInput::make('order.order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),
                        Forms\Components\TextInput::make('rating')
                            ->label('Rating (1-5)')
                            ->disabled()
                            ->numeric(),
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Tampilkan di halaman produk')
                            ->required(),
                        Forms\Components\Textarea::make('comment')
                            ->label('Komentar')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('⭐ Rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(60)
                    ->placeholder('(kosong)'),
                Tables\Columns\ToggleColumn::make('is_approved')
                    ->label('Tampilkan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Status')
                    ->trueLabel('Disetujui')
                    ->falseLabel('Menunggu')
                    ->placeholder('Semua'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
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
            'index' => Pages\ListReviews::route('/'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

