<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasus extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.kasus";

    protected $fillable = [
        'No Kasus', 
        'Tgl Kasus', 
        'Nip', 
        'Tgl In', 
        'Jam In', 
        'Tgl Out', 
        'Jam Out', 
        'Shift', 
        'Tipe', 
        'Jam Kurang', 
        'Bayar Jam', 
        'Bayar Penuh',
        'Keterangan',
        'Jam Lembur',
        'Lama Off',
        'Jam Dibayar'
    ];
}
