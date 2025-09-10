<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $connection = 'mysql2connection';
    protected $table = 'divisis';

    public function Jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
