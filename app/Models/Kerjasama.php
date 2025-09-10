<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerjasama extends Model
{
    use HasFactory;
    protected $connection = 'mysql2connection';

    public function Client()
    {
        return $this->setConnection('mysql2connection')->belongsTo(Client::class);
    }
}
