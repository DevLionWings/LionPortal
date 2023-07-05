<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterdivisi extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.masterdivisi";

    protected $fillable = [
        'Kode Divisi',
        'Nama Divisi',
        'Begda',
        'Endda',
    ];
}
