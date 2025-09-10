<?php

namespace App\Filament\Resources\TempUsersResource\Pages;

use App\Filament\Resources\TempUsersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTempUsers extends ListRecords
{
    protected static string $resource = TempUsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
           //
        ];
    }

    /**
     * use this class to add SubHeading On Index Page
     *
     * @return string|null
     */
    public function getSubheading(): ?string
    {
        return 'Use This To Approve New Employes ( Karyawan ).';
    }
}
