<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Jabatan extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'mysql2connection';

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}
