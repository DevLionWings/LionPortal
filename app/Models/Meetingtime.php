<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetingtime extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "master_data.m_meeting_time";

    protected $fillable = [
        'timeid',
        'counter',
        'starttime',
        'endtime'
    ];
}
