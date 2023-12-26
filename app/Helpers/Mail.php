<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail as SendtoMail;
use App\Mail\SendMail;
use App\Mail\SendMailUpdate;
use App\Mail\SendMailComment;
use App\Mail\SendMailBooking;
use App\Mail\SendMailTransport;

use Auth;

class Mail
{

    public static function SENDMAIL($ticketno, $category, $cateName, $priority, $priorityName, $subject, $remark, $note, $status, $statusid, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1, $emailHead)
    {
        $username = Session::get('username');
        $useremail = Session::get('usermail');
        if($useremail == null || $emailSign == null || $emailReq == null || $emailApprove1 == null || $emailHead == null){
            $emails = array(
                    $useremail => 'blank@lionwings.com',
                    $emailSign => 'blank@lionwings.com',
                    $emailReq => 'blank@lionwings.com',
                    $emailApprove1 => 'blank@lionwings.com',
                    $emailHead => 'blank@lionwings.com'
                );
        } else {
            $emails = array($useremail, $emailSign, $emailReq, $emailApprove1, $emailHead);
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
            'note' => $note,
            'status' => $status,
            'statusid' => $statusid,
            'assignedto' => $assign,
            'assigned_to' => $assignNameSign
        );
       
        SendtoMail::to($emails)->send(new SendMail($mailData));
    }

    public static function SENDMAILUPDATE($ticketno, $category, $cateName, $priority, $priorityName, $subject, $remark, $note, $status, $statusid, $comment_body, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1, $emailCreated, $emailCreatedName, $emailDivision, $emailListcomment)
    {
        $username = Session::get('username');
        $useremail = Session::get('usermail');
        if($useremail == null || $emailSign == null || $emailReq == null || $emailApprove1 == null){
            $emails = array(
                    $useremail => 'blank@lionwings.com',
                    $emailSign => 'blank@lionwings.com',
                    $emailReq => 'blank@lionwings.com',
                    $emailApprove1 => 'blank@lionwings.com',
                    $emailCreated => 'blank@lionwings.com'
                );
        } else {
            $emails = array($useremail, $emailSign, $emailReq, $emailApprove1, $emailCreated);
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
            'note' => $note,
            'status' => $status,
            'statusid' => $statusid,
            'comment' => $comment_body,
            'assignedto' => $assign,
            'assigned_to' => $assignNameSign,
            'createdname' => $emailCreatedName
        );
       
        SendtoMail::to($emails)->cc($emailListcomment)->bcc($emailDivision)->send(new SendMailUpdate($mailData));
    }

    public static function SENDMAILCOMMENT($ticketno, $comment_body, $assignNameSign, $emailSign, $emailFrom, $detail, $emailMgrIt, $emailMgrUser, $emailRequestor, $emailCreated, $emailDivision, $emailListcomment)
    {
        $emails = array($emailSign, $emailFrom, $emailMgrIt, $emailMgrUser, $emailRequestor, $emailCreated);
        
        $mailData = array(
            'comment' => $comment_body,
            'ticketno' => $ticketno,
            'detail' => $detail,
            'assigned_to' => $assignNameSign
        );
       
        SendtoMail::to($emails)->cc($emailListcomment)->bcc($emailDivision)->send(new SendMailComment($mailData));
    }

    public static function SENDMAILBOOKROOM($newBookId, $subject, $desc, $startdate, $enddate, $starttime, $endtime, $assignNameBook, $emailBook, $emailBookBy, $assignNameBookBy, $roomName, $repeat, $status)
    {
        $emails = array($emailBook, $emailBookBy);
        $bccEmails = array("fakhrur.rozi@lionwings.com", "bimantara.bayu@lionwings.com");
      
        $mailData = array(
            'bookid' => $newBookId,
            'subject' => $subject,
            'desc' => $desc,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'starttime' => $starttime,
            'endtime' => $endtime,
            'room' => $roomName,
            'bookingname' => $assignNameBook,
            'bookingbyname' => $assignNameBookBy,
            'repeat' => $repeat,
            'status' => $status
        );
    
        SendtoMail::to($emails)->bcc($bccEmails)->send(new SendMailBooking($mailData));
    }

    public static function SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo, $status, $remark)
    {
        $emails = array($emailSender, $emailSendTo);
      
        $mailData = array(
            'ticketno' => $ticketno,
            'transportid' => $transportId,
            'transno' => $transno,
            'sendername' => $emailNameSender,
            'sendtoname' => $emailNameSendTo,
            'status' => $status,
            'remark' => $remark
        );
    
        SendtoMail::to($emails)->send(new SendMailTransport($mailData));
    }
}