<?php

namespace App\Filament\Resources\PGJKontrakResource\Pages;

use App\Filament\Resources\PGJKontrakResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePGJKontrak extends CreateRecord
{
    protected static string $resource = PGJKontrakResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
