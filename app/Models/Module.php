<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "master_data.m_module";

    protected $fillable = [
        'moduleid',
        'description'
    ];
}
