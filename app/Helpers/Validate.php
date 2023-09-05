<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use Auth;

class Validate
{

   public static function GETUSEREMAIL($flag, $userreq, $assignto, $mgrIt, $mgrUser, $userid, $cateId, $roleid)
    {
        /* Get User Email */
        if($flag == 'ADD'){
            if($cateId == "CD001"){
                if ($roleid == "RD002"){
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('roleid', 'RD006')->first();
                    $dataEmail = 'NOT';

                    // $emailSign = $dataEmailSign->usermail;
                    // $assignNameSign = $dataEmailSign->username;
                    // $emailReq = 'blank@lionwings.com';
                    // $emailApprove1 = 'blank@lionwings.com';
                    // $auth = true;
                } else if ($roleid == "RD006"){
                    $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();

                    // $emailSign = $dataEmailSign->usermail;
                    // $assignNameSign = $dataEmailSign->username;
                    // $emailReq = $dataEmail->usermail;
                    // $emailApprove1 = 'blank@lionwings.com';
                    // $auth = true;
                } else if ($roleid == 'RD003'){
                    $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->first();
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrIt)->first();

                    // $emailSign = $dataEmailSign->usermail;
                    // $assignNameSign = $dataEmailSign->username;
                    // $emailReq = $dataEmail->usermail;
                    // $emailApprove1 = 'blank@lionwings.com';
                    // $auth = true;
                } else {
                    $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->first();

                    // $emailSign = $dataEmailSign->usermail;
                    // $assignNameSign = $dataEmailSign->username;
                    // $emailReq = $dataEmail->usermail;
                    // $emailApprove1 = 'blank@lionwings.com';
                    // $auth = true;
                }
            } else if ($roleid == "RD002"){
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('roleid', 'RD006')->first();
                $dataEmail = 'NOT';

                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                // $emailReq = 'blank@lionwings.com';
                // $emailApprove1 = 'blank@lionwings.com';
                // $auth = true;
            } else if ($roleid == "RD003"){
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrUser)->first();
                $dataEmail = 'NOT';

                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                // $emailReq = 'blank@lionwings.com';
                // $emailApprove1 = 'blank@lionwings.com';
                // $auth = true;
            } else if ($roleid == "RD006"){
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
                $dataEmail = 'NOT';

                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                // $emailReq = $dataEmail->usermail;
                // $emailApprove1 = 'blank@lionwings.com';
                // $auth = true;
            } else {
                $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->first();

                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                // $emailReq = $dataEmail->usermail;
                // $emailApprove1 = 'blank@lionwings.com';
                // $auth = true;
            }
            if($dataEmail == 'NOT'){
                $dataADD = [
                    'emailSign' => $dataEmailSign->usermail,
                    'assignNameSign' => $dataEmailSign->username,
                    'emailReq' =>  'blank@lionwings.com',
                    'emailApprove1' => 'blank@lionwings.com',
                    'emailApproveit' => 'blank@lionwings.com'
                ];
            } else {
                $dataADD = [
                    'emailSign' => $dataEmailSign->usermail,
                    'assignNameSign' => $dataEmailSign->username,
                    'emailReq' =>  $dataEmail->usermail,
                    'emailApprove1' => 'blank@lionwings.com',
                    'emailApproveit' => 'blank@lionwings.com'
                    
                ];
            }
            return $dataADD;
        } else if($flag == 'UPD'){
            if($cateId == "CD001 "){
                if($roleid == 'RD006'){ 
                    /* Get Email Signto */
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
                    $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                    $dataEmailApprove1 = 'NOT';
                } else if($roleid == 'RD002'){
                    /* Get Email Signto */
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
                    $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                    $dataEmailApprove1 = 'NOT';
                } else {
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->first();
                    $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                    $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrIt)->first();
                }

            } else if($roleid == 'RD002'){ //ketika kategori incindent
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
                $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                $dataEmailApprove1 = 'NOT';
                
                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                // $dataEmail = 'blank@lionwings.com';
                // $emailReq = $dataEmailReq->usermail;
                // $assignNameReq = $dataEmailReq->username;
                // $emailApprove1 = 'blank@lionwings.com';
            } else if($roleid == 'RD006'){ 
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
                $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrUser)->first();

                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                // $emailReq = $dataEmailReq->usermail;
                // $assignNameReq = $dataEmailReq->username;
                // $emailApprove1 = $dataEmailApprove1->usermail;
                // $assignNameApprove1 = $dataEmailApprove1->username;
            } else {
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrIt)->first();
                $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrUser)->first();

                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                // $emailReq = $dataEmailReq->usermail;
                // $assignNameReq = $dataEmailReq->username;
                // $emailApprove1 = $dataEmailApprove1->usermail;
                // $assignNameApprove1 = $dataEmailApprove1->username;
            }
            if($dataEmailApprove1 == 'NOT'){
                $dataUPD = [
                    'emailSign' => $dataEmailSign->usermail,
                    'assignNameSign' => $dataEmailSign->username,
                    'emailReq' =>  $dataEmailReq->usermail,
                    'emailApprove1' => 'blank@lionwings.com',
                    'emailApproveit' => 'blank@lionwings.com'
                ];
            } else {
                $dataUPD = [
                    'emailSign' => $dataEmailSign->usermail,
                    'assignNameSign' => $dataEmailSign->username,
                    'emailReq' =>  $dataEmailReq->usermail,
                    'emailApprove1' => $dataEmailApprove1->usermail,
                    'emailApproveit' => 'blank@lionwings.com'
                    
                ];
            }
            return $dataUPD;
        } else if($flag == 'CLS'){
            if($roleid == "RD006"){
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                /* Get Email Requestor */
                $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                // $emailReq = $dataEmailReq->usermail;
                // $assignNameReq = $dataEmailReq->username;
                /* Get Email Approve1 */
                $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrUser)->first();
                // $emailApprove1 = $dataEmailApprove1->usermail;
                /* Get Email IT */
                $dataEmailApproveit = 'NOT';
            }  else if(isset($mgrUser)){ #incident#
                    /* Get Email Signto */
                    $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
                    // $emailSign = $dataEmailSign->usermail;
                    // $assignNameSign = $dataEmailSign->username;
                    /* Get Email Requestor */
                    $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                    // $emailReq = $dataEmailReq->usermail;
                    /* Get Email IT */
                    $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrIt)->first();
                    // $emailApproveit = $dataEmailApproveit->usermail;
                    $dataEmailApproveit = 'NOT';
            } else {
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->first();
                // $emailSign = $dataEmailSign->usermail;
                // $assignNameSign = $dataEmailSign->username;
                /* Get Email Requestor */
                $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userreq)->first();
                // $emailReq = $dataEmailReq->usermail;
                /* Get Email Approve1 */
                $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrUser)->first();
                // $emailApprove1 = $dataEmailApprove1->usermail;
                /* Get Email IT */
                $dataEmailApproveit = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrIt)->first();
                // $emailApproveit = $dataEmailApproveit->usermail;
            }

            if($dataEmailApproveit == 'NOT'){
                $dataCls = array(
                    'emailSign' => $dataEmailSign->usermail,
                    'emailReq' => $dataEmailReq->usermail,
                    'assignNameSign' => $dataEmailSign->username,
                    'emailApprove1' => $dataEmailApprove1->usermail,
                    'emailApproveit' => 'blank@lionwings.com'
                );
            } else {
                $dataCls = array(
                    'emailSign' => $dataEmailSign->usermail,
                    'emailReq' => $dataEmailReq->usermail,
                    'assignNameSign' => $dataEmailSign->username,
                    'emailApprove1' => $dataEmailApprove1->usermail,
                    'emailApproveit' => $dataEmailApproveit->usermail

                );
            }
            return $dataCls;
        }
    }

}
