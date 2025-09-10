<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PGJ_Kontrak extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysqlEdata';

    protected $fillable = [
        'no_srt',
        'tgl_dibuat',
        'nama_pk_ptm',
        'alamat_pk_ptm',
        'jabatan_pk_ptm',
        'nama_pk_kda',
        'tempat_lahir_pk_kda',
        'tgl_lahir_pk_kda',
        'nik_pk_kda',
        'alamat_pk_kda',
        'jabatan_pk_kda',
        'status_pk_kda',
        'unit_pk_kda',
        'tgl_mulai_kontrak',
        'tgl_selesai_kontrak',
        'g_pok',
        'tj_hadir',
        'kinerja',
        'lain_lain',
        'send_to_operator',
        'send_to_atasan',
        'ttd',
    ];

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('no_srt', 'like', "%{$searchTerm}%")
                ->orWhere('nama_pk_kda', 'like', "%{$searchTerm}%")
                ->orWhere('jabatan_pk_kda', 'like', "%{$searchTerm}%")
                ->orWhere('unit_pk_kda', 'like', "%{$searchTerm}%")
                ->orWhere('nik_pk_kda', 'like', "%{$searchTerm}%")
                ->orWhere('tgl_mulai_kontrak', 'like', "%{$searchTerm}%")
                ->orWhere('tgl_selesai_kontrak', 'like', "%{$searchTerm}%");
        });
    
    }

}
