<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mastertunjangan extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "master_data.m_tunjangan";

    protected $fillable = [
        'tunjanganid',
        'categoryid',
        'tunjangandescr',
        'categorydescr',
        'value'
    ];
}
