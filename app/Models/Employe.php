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
        'alamat',
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

    public function getImgAttribute($value)
    {
        if (! $value) {
            return null;
        }

        // Normalisasi path lama -> taruh ke folder profile
        if (! str_starts_with($value, 'images/')) {
            return "images/{$value}";
        }

        return $value;
    }

    public function getImgKtpDpnAttribute($value)
    {
        if (! $value) {
            return null;
        }

        if (! str_starts_with($value, 'images/')) {
            return "images/{$value}";
        }

        return $value;
    }


    public static function booted()
    {
        static::updating(function ($record) {
            foreach (['img', 'img_ktp_dpn', 'file_bpjs_kesehatan', 'file_bpjs_ketenaga'] as $field) {
                if ($record->isDirty($field)) {
                    $oldFile = $record->getOriginal($field);

                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
        });

        static::deleting(function ($record) {
            foreach (['img', 'img_ktp_dpn', 'file_bpjs_kesehatan', 'file_bpjs_ketenaga'] as $field) {
                $file = $record->{$field};

                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
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
}
