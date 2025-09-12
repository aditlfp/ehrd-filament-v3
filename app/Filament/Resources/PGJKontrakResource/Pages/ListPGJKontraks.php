<?php

namespace App\Filament\Resources\PGJKontrakResource\Pages;

use App\Filament\Resources\PGJKontrakResource;
use App\Models\PGJ_Kontrak;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPGJKontraks extends ListRecords
{
    protected static string $resource = PGJKontrakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Kontrak'),
        ];
    }
}
