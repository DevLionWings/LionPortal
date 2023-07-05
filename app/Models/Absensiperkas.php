<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensiperkas extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.absensi";

    protected $fillable = [
        'Nip', 
        'Tgl In', 
        'Jam In', 
        'Tgl Out', 
        'Jam Out', 
        'Lama Kerja', 
        'Proses', 
        'No Kasus', 
        'Jam Lembur', 
        'Shift', 
        'CardX', 
        'Lama Off'
    ];
}
