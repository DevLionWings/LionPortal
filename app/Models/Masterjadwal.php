<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterjadwal extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.jadwal";

    protected $fillable = [
        'Tgl',
        'Kode Group',
        'Kode Shift',
        'Keterangan',
    ];
}
