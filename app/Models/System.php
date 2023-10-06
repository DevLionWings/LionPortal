<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "master_data.m_system";

    protected $fillable = [
        'systemid',
        'description'
    ];
}
