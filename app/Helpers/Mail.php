<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail as SendtoMail;
use App\Mail\SendMail;

use Auth;

class Mail
{

    public static function SENDMAIL($ticketno, $category, $cateName, $priority, $priorityName, $subject, $remark, $status, $statusid, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1)
    {
        $username = Session::get('username');
        $useremail = Session::get('usermail');
        if($useremail == null || $emailSign == null || $emailReq == null || $emailApprove1 == null){
            $emails = array(
                    $useremail => 'blank@lionwings.com',
                    $emailSign => 'blank@lionwings.com',
                    $emailReq => 'blank@lionwings.com',
                    $emailApprove1 => 'blank@lionwings.com'
                );
        } else {
            $emails = array($useremail, $emailSign, $emailReq, $emailApprove1);
        }
        
        $mailData = array(
            'username' => $username,
            'ticketno' => $ticketno,
            'category' => $category,
            'categoryname' => $cateName,
            'priority' => $priority,
            'priorityname' => $priorityName,
            'subject' => $subject,
            'detail' => $remark,
            'status' => $status,
            'statusid' => $statusid,
            'assignedto' => $assign,
            'assigned_to' => $assignNameSign
        );
       
        SendtoMail::to($emails)->send(new SendMail($mailData));
    }
}