<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masteradmin extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.masteradmin";

    protected $fillable = [
        'Kode Admin',
        'Nama Admin',
        'Nip',
        'Nama',
        'Begda',
        'Endda',
    ];
}
