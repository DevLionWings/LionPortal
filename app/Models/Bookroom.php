<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookroom extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "meeting.t_booking";

    protected $fillable = [
        'bookid',
        'userid',
        'username',
        'roomid',
        'subject',
        'description',
        'status',
        'startdate',
        'enddate',
        'starttime',
        'endtime',
        'bookedon',
        'bookedby'
    ];
}
