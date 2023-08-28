<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterkaryawan extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "hris.t_karyawan";

    protected $fillable = [
        'idsmu',
        'id',
        'nama',
        'tgl_in',
        'sex',
        'bagian',
        'tgl_lahir',
        'gaji',
        'turun_gaji',
        'jabatan',
        'spsi',
        'koperasi',
        'date_update'
    ];
}
