<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserAbsensi extends Model
{
    use Notifiable;
    protected $connection = 'mysql2connection';
    protected $table = 'users';

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nama_lengkap',
        'kerjasama_id',
        'email',
        'password',
        'image',
        'devisi_id',
        'jabatan_id',
        'status_id',
        'temp_ban',
        'nik',
        'no_hp',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function Jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function Divisi()
    {
        return $this->belongsTo(Divisi::class, 'devisi_id', 'id');
    }

    public function Kerjasama()
    {
        return $this->belongsTo(Kerjasama::class);
    }

    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Kerjasama::class,
            'id',       // Foreign key on Kerjasama table
            'id',       // Foreign key on Client table
            'kerjasama_id', // Foreign key on User table
            'client_id'     // Foreign key on Kerjasama table
        );
    }
}
