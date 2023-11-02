<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "helpdesk.t_transport";

    protected $fillable = [
        'transportid',
        'ticketid',
        'transportno',
        'sendto_lqa',
        'approveby_lqa_date',
        'approveby_lqa',
        'sendto_lpr',
        'approveby_lqr_date',
        'approveby_lqr',
        'upload',
        'status_lqa',
        'status_lpr'
    ];
}
