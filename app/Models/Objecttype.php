<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objecttype extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "master_data.m_object_type";

    protected $fillable = [
        'objectid',
        'description'
    ];  
}
