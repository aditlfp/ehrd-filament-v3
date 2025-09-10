<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class Employe extends Model
{
    use HasFactory;
    protected $connection = 'mysqlEdata'; 
    protected $casts = [
        'jenis_bpjs' => 'array',
        'jbt_name' => 'array'
    ];

    protected $appends = ['no_induk_karyawan'];
    
    protected $fillable = [
        'user_id',
        'name',
        'ttl',
        'nik',
        'initials',
        'numbers',
        'date_real',
        'no_kk',
        'no_ktp',
        'client_id',
        'img',
        'img_ktp_dpn',
        'img_ktp_bkg',
        'jenis_bpjs',
        'no_bpjs_kesehatan',
        'file_bpjs_kesehatan',
        'no_bpjs_ketenaga',
        'file_bpjs_ketenaga',
    ];

    public function getNoIndukKaryawanAttribute(): ?string
    {
        if (! $this->initials || ! $this->numbers || ! $this->date_real) {
            return null;
        }

        return $this->initials
            . str_pad($this->numbers, 3, '0', STR_PAD_LEFT)
            . Carbon::parse($this->date_real)->format('ymd');
    }

    public function getNoKkAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function getNoKtpAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function getImgUrlAttribute() {
        if (!$this->img) return null;

        return str_starts_with($this->img, 'images/')
            ? $this->img
            : 'images/' . $this->img;
    }

    
    public static function booted()
    {
        static::updating(function ($record) {
            if ($record->isDirty('img')) {
                Storage::disk('public')->delete($record->getOriginal('img'));
            }

            if ($record->isDirty('img_ktp_dpn')) {
                Storage::disk('public')->delete($record->getOriginal('img_ktp_dpn'));
            }
           
            if ($record->isDirty('file_bpjs_kesehatan')) {
                Storage::disk('public')->delete($record->getOriginal('file_bpjs_kesehatan'));
            }
           
            if ($record->isDirty('file_bpjs_ketenaga')) {
                Storage::disk('public')->delete($record->getOriginal('file_bpjs_ketenaga'));
            }
           
        });

        static::deleting(function ($record) {
            if ($record->img) {
                Storage::disk('public')->delete($record->img);
            }

            if ($record->img_ktp_dpn) {
                Storage::disk('public')->delete($record->img_ktp_dpn);
            }
            if ($record->file_bpjs_kesehatan) {
                Storage::disk('public')->delete($record->file_bpjs_kesehatan);
            }
            if ($record->file_bpjs_ketenaga) {
                Storage::disk('public')->delete($record->file_bpjs_ketenaga);
            }
        });
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'name', 'nama_lengkap');
    }    

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    public function slipGajis()
    {
        return $this->hasMany(\App\Models\SlipGaji::class, 'karyawan', 'name');
    }
}
