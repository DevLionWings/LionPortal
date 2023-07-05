<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personaliauser extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.user";

    protected $fillable = [
        'user',
        'name',
        'password',
        'role',
    ];
    
}
