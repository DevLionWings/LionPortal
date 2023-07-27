<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendancenew extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "absensi.kartuabsensi";

    protected $fillable = [
        'notr',
        'id',
        'nama',
        'keterangan',
        'j1',
        'j2',
        'j3',
        'j4',
        'j5',
        'j6'
    ];
}
