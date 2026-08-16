<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export_csv')
                ->label('Export Laporan (CSV)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function (): StreamedResponse {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        fputs($handle, "\xEF\xBB\xBF");

                        fputcsv($handle, [
                            'No. Pesanan', 'Nama Pembeli', 'No. WhatsApp', 'Email',
                            'Total Bayar (Rp)', 'Diskon (Rp)', 'Kupon',
                            'Status Pembayaran', 'Status Pengiriman', 'No. Resi', 'Tanggal'
                        ]);

                        Order::latest()->chunk(100, function ($orders) use ($handle) {
                            foreach ($orders as $order) {
                                fputcsv($handle, [
                                    $order->order_number,
                                    $order->guest_name,
                                    $order->guest_phone,
                                    $order->guest_email ?? '-',
                                    $order->total_amount,
                                    $order->discount_amount,
                                    $order->coupon_code ?? '-',
                                    $order->payment_status,
                                    $order->shipping_status,
                                    $order->shipping_receipt_number ?? '-',
                                    $order->created_at->format('Y-m-d H:i:s'),
                                ]);
                            }
                        });

                        fclose($handle);
                    }, 'laporan-penjualan-fersya-' . date('Y-m-d') . '.csv', [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                }),
        ];
    }
}
