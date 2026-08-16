<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Toko';
    protected static ?string $title = 'Pengaturan Toko & Announcement';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'announcement_active' => Setting::get('announcement_active', '1') === '1',
            'announcement_text' => Setting::get('announcement_text', '🍞 Freshly Baked Everyday · 🚚 Gratis Ongkos Kirim · Gunakan Kupon FERSYA10 untuk Diskon 10%'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Announcement Bar')
                    ->description('Pengaturan banner pengumuman yang muncul di paling atas website.')
                    ->schema([
                        Forms\Components\Toggle::make('announcement_active')
                            ->label('Tampilkan Announcement Bar di Website')
                            ->default(true),
                        Forms\Components\Textarea::make('announcement_text')
                            ->label('Teks Announcement Bar')
                            ->rows(3)
                            ->required()
                            ->helperText('Bisa memuat teks promo, gratis ongkir, atau kode kupon.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::set('announcement_active', $state['announcement_active'] ? '1' : '0');
        Setting::set('announcement_text', $state['announcement_text']);

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->success()
            ->send();
    }
}
