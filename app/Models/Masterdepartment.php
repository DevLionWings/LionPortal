<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterdepartment extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "master_data.m_department";

    protected $fillable = [
        'departmentid',
        'description'
    ];
    
}
