<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mastergroup extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.masterworkgroup";

    protected $fillable = [
        'Kode Group',
        'Nama Group',
        'Begda',
        'Endda',
    ];
}
