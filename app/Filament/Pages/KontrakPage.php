<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\PGJ_Kontrak;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\View;

class KontrakPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.kontrak-page';
    protected static ?string $navigationLabel = 'View Contract';
    protected static ?string $slug = 'kontrak';

    public $kontrak;

    public function mount(int $id)
    {
        $this->kontrak = PGJ_Kontrak::findOrFail($id);
    }

    public static function getRoute(): string
    {
        return 'kontrak/{id}';
    }
}
