<?php

namespace App\Filament\Resources\PGJKontrakResource\Pages;

use App\Filament\Resources\PGJKontrakResource;
use App\Models\PGJ_Kontrak;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPGJKontraks extends ViewRecord
{
    protected static string $resource = PGJKontrakResource::class;
    protected static string $view = 'filament.pages.kontrak-page';
    protected static ?string $slug = 'kontrak';


    public function getKontrak(): PGJ_Kontrak
    {
        return PGJ_Kontrak::findOrFail($this->record->id);
    }

    public static function getRoute(): string
    {
        return 'kontrak/{id}';
    }
}
