<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterbagian extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.masterbagian";

    protected $fillable = [
        'Kode Divisi',
        'Kode Bagian',
        'Nama Bagian',
        'Kabag',
        'Cost Center',
        'Begda',
        'Endda',
    ];
}
