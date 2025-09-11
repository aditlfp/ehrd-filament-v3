<?php

namespace App\Filament;

use Filament\AvatarProviders\UiAvatarsProvider;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Auth\Authenticatable;

class UiCustomAvatarProvider  extends UiAvatarsProvider
{
    /**
     * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Contracts\Auth\Authenticatable $record
     * @return string
     */
    public function get(Model | Authenticatable $record): string
    {
        $name = (string) ($record->name ?? 'User');

        // contoh: ganti warna background jadi biru tua
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=FFFFFF&background=1E3A8A';
    }
}
