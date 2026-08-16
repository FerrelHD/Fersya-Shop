<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $monthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $pendingPayment = Order::where('payment_status', 'pending')->count();

        $readyToShip = Order::where('payment_status', 'paid')
            ->where('shipping_status', 'diproses')
            ->count();

        $pendingReviews = Review::where('is_approved', false)->count();

        $lowStock = ProductVariant::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->count();

        return [
            Stat::make('💰 Pendapatan Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Bulan ini: Rp ' . number_format($monthRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('🚚 Siap Kirim', $readyToShip . ' pesanan')
                ->description($pendingPayment . ' menunggu pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color($readyToShip > 0 ? 'warning' : 'gray'),

            Stat::make('⭐ Ulasan Baru', $pendingReviews . ' menunggu')
                ->description('Perlu disetujui sebelum tampil')
                ->descriptionIcon('heroicon-m-star')
                ->color($pendingReviews > 0 ? 'info' : 'gray'),

            Stat::make('📦 Stok Hampir Habis', $lowStock . ' varian')
                ->description('Sisa ≤ 5 pcs — segera restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStock > 0 ? 'danger' : 'success'),
        ];
    }
}
