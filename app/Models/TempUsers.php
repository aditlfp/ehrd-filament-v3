<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class TempUsers extends Model
{
    use HasFactory, Notifiable;
    
    protected $connection = 'mysql2connection';
    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'status',
    ];

    public function getNikAttribute()
    {
        try {
            return Crypt::decryptString($this->data['nik'] ?? '');
        } catch (\Exception $e) {
            return $this->data['nik'] ?? '-';
        }
    }

    public function getNoKkAttribute()
    {
        try {
            return Crypt::decryptString($this->data['no_kk'] ?? '');
        } catch (\Exception $e) {
            return $this->data['no_kk'] ?? '-';
        }
    }

    public function routeNotificationForMail()
    {
        $data = $this->data;

        if (is_string($data)) {
            $data = json_decode($data, true) ?? [];
        }

        return $data['email'] ?? '';
    }


    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function Devisi()
    {
        return $this->belongsTo(Divisi::class, 'devisi_id', 'id');
    }
}
