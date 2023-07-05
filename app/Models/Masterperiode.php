<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterperiode extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.masterperiode";

    protected $fillable = [
        'Kode Periode',
        'Nama Periode',
        'Begda',
        'Endda',
    ];
}
