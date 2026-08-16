<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Mail\OrderShippedMail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\Order $order */
        $order = $this->record;

        if ($order->guest_email && $order->shipping_status === 'dikirim') {
            try {
                Mail::to($order->guest_email)->send(new OrderShippedMail($order));
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email resi pengiriman: ' . $e->getMessage());
            }
        }
    }
}
