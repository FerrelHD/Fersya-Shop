<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembeli')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')->label('Nomor Pesanan')->disabled(),
                        Forms\Components\TextInput::make('guest_name')->label('Nama')->disabled(),
                        Forms\Components\TextInput::make('guest_phone')->label('Telepon')->disabled(),
                        Forms\Components\TextInput::make('guest_email')->label('Email')->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Pengiriman')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Pembayaran')
                            ->prefix('Rp')
                            ->disabled()
                            ->numeric(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending (Belum Bayar)',
                                'paid' => 'Lunas (Sudah Bayar)',
                                'failed' => 'Gagal / Batal',
                            ])
                            ->required()
                            ->helperText('Ubah ke Lunas setelah konfirmasi mutasi masuk.'),
                        Forms\Components\Select::make('shipping_status')
                            ->label('Status Pengiriman')
                            ->options([
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('shipping_receipt_number')
                            ->label('Nomor Resi Pengiriman')
                            ->placeholder('Contoh: JNE123456789ID')
                            ->helperText('Isi setelah paket dikirim.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('guest_name')
                    ->label('Pembeli')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guest_phone')
                    ->label('Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'expired' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('shipping_status')
                    ->label('Pengiriman')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'dikirim' => 'info',
                        'diproses' => 'warning',
                        'menunggu_pembayaran' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('shipping_receipt_number')
                    ->label('Resi')
                    ->searchable()
                    ->placeholder('-')
                    ->copyable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Sudah Bayar',
                        'failed' => 'Gagal',
                        'expired' => 'Kadaluarsa',
                    ]),
                Tables\Filters\SelectFilter::make('shipping_status')
                    ->label('Status Pengiriman')
                    ->options([
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Update'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
