<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetingroom extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "master_data.m_meeting_room";

    protected $fillable = [
        'roomid',
        'roomname',
        'roombuilding',
        'roomfloor',
        'roompublic',
        'roomcapacity',
        'plantid',
        'active',
        'statusroom',
        'createddate'
    ];
}
