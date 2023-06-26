<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail as SendtoMail;
use App\Mail\SendMail;

use Auth;

class Mail
{

    public static function SENDMAIL($ticketno, $category, $priority, $subject, $remark, $status, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1)
    {
        $username = Session::get('username');
        $useremail = Session::get('usermail');
        $emails = array($useremail, $emailSign, $emailReq, $emailApprove1);
        
        $mailData = array(
            'username' => $username,
            'ticketno' => $ticketno,
            'category' => $category,
            'priority' => $priority,
            'subject' => $subject,
            'detail' => $remark,
            'status' => $status,
            'assignedto' => $assign,
            'assigned_to' => $assignNameSign
        );
        SendtoMail::to($emails)->send(new SendMail($mailData));
    }
}