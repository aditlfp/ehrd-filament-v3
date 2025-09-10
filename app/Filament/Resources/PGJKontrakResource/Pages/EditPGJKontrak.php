<?php

namespace App\Filament\Resources\PGJKontrakResource\Pages;

use App\Filament\Resources\PGJKontrakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPGJKontrak extends EditRecord
{
    protected static string $resource = PGJKontrakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
