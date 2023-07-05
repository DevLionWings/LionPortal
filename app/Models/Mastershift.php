<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mastershift extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.mastershift";

    protected $fillable = [
        'Kode Shift', 
        'Nama Shift', 
        'Jam In', 
        'Jam Out', 
        'Lama Kerja', 
        'Begda', 
        'Endda', 
        'hitung_off',
    ];
}
