<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\File;
use App\Helpers\Mail;
use App\Models\Useraccount;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Counter;
use App\Models\Transport;

class TransportController extends Controller
{
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function sendTransport(Request $request)
    {   
        $userid = Session::get('userid');
        $emailNameSender = Session::get('username');
        $emailSender = Session::get('usermail');
        $mgrid = Session::get('mgrid');
        $ticketno = $request->ticketno;
        if($mgrid == null){
            $dataUser = DB::connection('pgsql')->table('master_data.m_user')
            ->where('userid', $userid)
            ->first();
        } else {
            $dataUser = DB::connection('pgsql')->table('master_data.m_user')
            ->where('userid', $mgrid)
            ->first();
        }
        $emailSendTo = $dataUser->usermail;
        $emailNameSendTo = $dataUser->username;
        
        if ($request->lqa == null && $request->lpr == null){
            // return redirect()->route('tiket')->with("error", "Checked Box not Found");
            return "Checked Box not Found";
        } else {
            /* Checked Opsi Transport */
            if ($request->lqa == null) {
                $lqa = '1';
                $date_lqa = '';
                $lpr = '1';
                $date_lpr = date('Y-m-d H:i:s');
                $status = 'Request To LPR';
            } else if ($request->lpr == null){
                $lqa = '1';
                $date_lqa = date('Y-m-d H:i:s');
                $lpr = '0';
                $date_lpr = '';
                $status = 'Request To LQA';
            } 
            /* end checked */
           
            /* Checked Transport Number*/
            if($request->opsi == "exist"){
                $transid = $request->transportid;
                /* If Existing Get data transport id */
                $existing = DB::connection('pgsql')->table('helpdesk.t_transport')
                    ->where('transportid', $transid)
                    ->get();

                $trim_transno = [];
                foreach ($existing as $key => $value) {
                    array_push($trim_transno, $value->transportno);
                    // array_push($trim_transno, trim(preg_replace('/\s+/', ',', $value->transportno)));
                }
                $transno = implode(" ", $trim_transno);
        
                $transportId = $existing[0]->transportid;
                $sendtolqa = $existing[0]->sendto_lqa;
                $sendtolpr = $existing[0]->sendto_lpr;

                /* Update Transport to DB */
                if($date_lqa == ''){
                    $insert = DB::connection('pgsql')->table('helpdesk.t_transport')
                    ->where('transportid', $transid)
                    ->update([
                        'transportno' => $transno,
                        'sendto_lqa' => $lqa,
                        'sendto_lpr' => $lpr,
                        'createdon_lpr' => $date_lpr
                    ]);

                    $updateTicket = DB::connection('pgsql')->table('helpdesk.t_ticket')
                    ->where('ticketno', $ticketno)
                    ->update([
                        'statusid' => 'SD010',
                    ]);
                } else {
                    $insert = DB::connection('pgsql')->table('helpdesk.t_transport')
                    ->where('transportid', $transid)
                    ->update([
                        'transportno' => $transno,
                        'sendto_lqa' => $lqa,
                        'createdon_lqa' => $date_lqa,
                        'sendto_lpr' => $lpr
                    ]);

                    $updateTicket = DB::connection('pgsql')->table('helpdesk.t_ticket')
                    ->where('ticketno', $ticketno)
                    ->update([
                        'statusid' => 'SD010',
                    ]);
                } 
                /* End */

            } else if($request->opsi == "new"){
                $transno = $request->transnumber;

                /* Generate Transport Id */
                $year = date("Y");
                $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT007')->where('period', $year)->first();

                $subs1 = $dataPrefix->prefix;
                $subs2 =  str_pad($dataPrefix->last_number + 1, 4, "00", STR_PAD_LEFT);
                $transportId = $subs1.$subs2;
                $last = $dataPrefix->last_number + 1;
                $update = DB::connection('pgsql')->table('master_data.m_counter')
                    ->where('counterid', 'CT007')
                    ->where('period', $year)
                    ->update([
                        'last_number' => $last
                ]);
                /* End */

                /* Insert Transport to DB */
                if($date_lqa == ''){
                    $insert = DB::connection('pgsql')->table('helpdesk.t_transport')->insert([
                        'transportid' => $transportId,
                        'ticketno' => $request->ticketno,
                        'transportno' => $transno,
                        'sendto_lqa' => $lqa,
                        'sendto_lpr' => $lpr,
                        'createdon_lpr' => $date_lpr,
                        'createdon' => date('Y-m-d H:i:s'),
                    ]);

                    $updateTicket = DB::connection('pgsql')->table('helpdesk.t_ticket')
                    ->where('ticketno', $ticketno)
                    ->update([
                        'statusid' => 'SD010',
                    ]);
                    DB::commit();
                } else {
                    $insert = DB::connection('pgsql')->table('helpdesk.t_transport')->insert([
                        'transportid' => $transportId,
                        'ticketno' => $request->ticketno,
                        'transportno' => $transno,
                        'sendto_lqa' => $lqa,
                        'createdon_lqa' => $date_lqa,
                        'sendto_lpr' => $lpr,
                        'createdon' => date('Y-m-d H:i:s'),
                    ]);

                    $updateTicket = DB::connection('pgsql')->table('helpdesk.t_ticket')
                    ->where('ticketno', $ticketno)
                    ->update([
                        'statusid' => 'SD010',
                    ]);
                    DB::commit();
                }
                /* End */
            } else {
                $transno = "";
            }
            /* End */

            /* Send Email */
            $remark = 'Request Transport';
            $emailTRANS = $this->mail->SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo, $status, $remark);
            /* End */

            if($insert == true){
                return "request send successfully";
            } else {
                return "Checked Box not Found";
            }
        }
    }

    public function approveTransport(Request $request)
    {
        $userid = Session::get('userid');
        $username = Session::get('username');
        $emailNameSender = Session::get('username');
        $emailSender = Session::get('usermail');
        $emailSendTo = 'it@lionwings.com';
        $emailNameSendTo = 'IT-Lion Wings';
        $ticketno = $request->ticketno;
        $datatrq = $request->data_transportid;
        // $transno = $request->transno;
        // $page = $request->page;

        if($request->sendlqa == '1' && $request->sendlpr == '1'){
            $datelqa = '';
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = '';
            $usernamelpr =  Session::get('username');
            $status = "APPROVE TO LPR";
        } else if($request->sendlqa == '1'){
            $datelqa = date('Y-m-d H:i:s');
            $datelpr = '';
            $usernamelqa = Session::get('username');
            $usernamelpr = '';
            $status = "APPROVE TO LQA";
        } 
        
        $getTransno = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq)->first();
        $transno = $getTransno->transportno;

        /* Approve Transport */
        if($datelqa == ''){
            for ($a=0; $a < count($datatrq) ; $a++){
                $approve = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq[$a])->update([
                    'approveby_lpr_date' => $datelpr,
                    'approveby_lpr' => $usernamelpr,
                    'status_lpr' => $request->sendlpr,
                    'status_lpr_date' => $datelpr,
                    'remark' => $request->remark
                ]);
            }
        } else {
            for ($a=0; $a < count($datatrq) ; $a++){
                $approve = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq[$a])->update([
                    'approveby_lqa_date' => $datelqa,
                    'approveby_lqa' => $usernamelqa,
                    'status_lqa' => $request->sendlqa,
                    'status_lqa_date' => $datelqa,
                    'remark' => $request->remark
                ]);
            }
        }
        /* End */

        /* Send Email */ 
        $transportId = implode(",", $datatrq);
        $remark = $request->remark;
        $emailTRANS = $this->mail->SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo, $status, $remark);
        /* End */

        if($approve == true){
            return "approved send successfully";
            // if($page == 'mytiket'){
            //     return redirect()->route('mytiket')->with("success", "approved send successfully");
            // } else {
            //     return redirect()->route('tiket')->with("success", "approved send successfully");
            // }
        } else { 
            return "Checked Box not Found";
        }
    }

    public function rejectTransport(Request $request)
    {
        $userid = Session::get('userid');
        $username = Session::get('username');
        $emailNameSender = Session::get('username');
        $emailSender = Session::get('usermail');
        $emailSendTo = 'it@lionwings.com';
        $emailNameSendTo = 'IT - Lion Wings';
        $ticketno = $request->ticketno;
        $datatrq = $request->data_transportid;
        // $transno = $request->transno;
        $date = date('Y-m-d H:i:s');
        $page = $request->page;
        
        if($request->sendlqa == '1' && $request->sendlpr == '1'){
            $lqa = 0;
            $lpr = 0;
        } else if($request->sendlqa == '1'){
            $lqa = 0;
            $lpr = 0;
        } else if($request->sendlpr == '1'){
            $lqa = 1;
            $lpr = 0;
        } else {
            $lqa = 0;
            $lpr = 0;
        }
        
        $getTransno = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq)->first();
        $transno = $getTransno->transportno;

        if($request->status == 'APPROVE'){
            /* Approve Transport */
            for ($a=0; $a < count($datatrq) ; $a++){
                $approve = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq[$a])->update([
                    'approveby_lqa_date' => $date,
                    'approveby_lpr_date' => $date,
                    'approveby_lqa' => $username,
                    'approveby_lpr' => $username,
                    'status_lqa' => $lqa,
                    'status_lpr' => $lpr,
                    'status_lqa_date' => $date,
                    'status_lpr_date' => $date,
                    'remark' => $request->remark
                ]);
            }
            /* End */
        } else {
            /* Transported Transport */
            for ($a=0; $a < count($datatrq) ; $a++){
                $approve = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq[$a])->update([
                    'date_trans_lqa' => $date,
                    'date_trans_lpr' => $date,
                    'transportby_lqa' => $username,
                    'transportby_lpr' => $username,
                    'status_trans_lqa' => $lqa,
                    'status_trans_lpr' => $lpr,
                    'remark' => $request->remark
                ]);
            }
            /* End */
        }
        
        /* Send Email */ 
        $transportId = implode(",", $datatrq);
        $status = "REJECT";
        $remark = $request->remark;
        $emailTRANS = $this->mail->SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo, $status, $remark);
        /* End */

        if($approve == true){
            return response()->json([
                'url'=>url('/tiket'),
                'message'=>'transport rejected successfully'
            ]);
            // return redirect()->route('tiket')->with("success", "transport approved successfully");
        } else { 
            return response()->json([
                'url'=>route('/tiket'),
                'message'=>'eror reject'
        ]);
            // return redirect()->back()->with("error", "error");
        }
    }

    public function transportedTransport(Request $request)
    {
        $userid = Session::get('userid');
        $username = Session::get('username');
        $emailNameSender = Session::get('username');
        $emailSender = Session::get('usermail');
        $emailSendTo = 'it@lionwings.com';
        $emailNameSendTo = 'IT-Lion Wings';
        $ticketno = $request->ticketno;
        $datatrq = $request->data_transportid;
        // $transno = $request->transno;
        // $page = $request->page;

        if($request->sendlqa == '1' && $request->sendlpr == '1'){
            $datelqa = '';
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = '';
            $usernamelpr = Session::get('username');
            $status = "TRANSPORTED TO LPR";
        } else if($request->sendlqa == '1'){
            $datelqa = date('Y-m-d H:i:s');
            $datelpr =  '';
            $usernamelqa = Session::get('username');
            $usernamelpr = '';
            $status = "TRANSPORTED TO LQA";
        } 
        
        $getTransno = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq)->first();
        $transno = $getTransno->transportno;
        
        /* Trasnported Transport */
        if($datelqa == ''){
            for ($a=0; $a < count($datatrq) ; $a++){
                $transported = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq[$a])->update([
                    'date_trans_lpr' => $datelpr,
                    'transportby_lpr' => $usernamelpr,
                    'status_trans_lpr' => $request->sendlpr,
                    'remark' => $request->remark
                ]);
            }
        } else {    
            for ($a=0; $a < count($datatrq) ; $a++){
                $transported = DB::connection('pgsql')->table('helpdesk.t_transport')->where('transportid', $datatrq[$a])->update([
                    'date_trans_lqa' => $datelqa,
                    'transportby_lqa' => $usernamelqa,
                    'status_trans_lqa' => $request->sendlqa,
                    'remark' => $request->remark
                ]);
            }
        }
        
        // $updateTicket = DB::connection('pgsql')->table('helpdesk.t_ticket')
        // ->where('ticketno', $ticketno)
        // ->update([
        //     'statusid' => 'SD011',
        // ]);
        /* End */

        /* Send Email */
        $transportId = implode(",", $datatrq);
        $remark = $request->remark;
        $emailTRANS = $this->mail->SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo, $status, $remark);
        /* End */

        if($transported == true){
            // if($page == 'mytiket'){
            //     return redirect()->route('mytiket')->with("success", "transported successfully");
            // } else {
            //     return redirect()->route('tiket')->with("success", "transported successfully");
            // }
            return "transported send successfully";
        } else { 
            return "Checked Box not Found";
        }
    }

    public function listTransport(Request $request)
    {
        $trq = ''; 
        $ticketno = $request->ticketno;

        $dataTransport = DB::connection('pgsql')->table('helpdesk.t_transport')
                ->where('ticketno', $ticketno)
                ->orderBy('createdon', 'desc')
                ->get();
      
        $jsonTransport= json_decode($dataTransport, true);

        /* Get Comment */
        $transport = $jsonTransport;
        $transportArray = [];
        foreach ($transport as $key => $value) {
            array_push($transportArray, [
                "TRANSNO" => trim($value['transportno']),
                "TRANSID" => trim($value['transportid']),
                "LQA" => trim($value['sendto_lqa']),
                "LPR" => trim($value['sendto_lpr']),
                "DATE" => trim($value['createdon']),
                "DATELQA" => trim($value['createdon_lqa']),
                "DATELPR" => trim($value['createdon_lpr']),
                "STATUSLQA" => trim($value['status_lqa']),
                "STATUSLPR" => trim($value['status_lpr']),
                "DATESTATUSLQA" => trim($value['status_lqa_date']),
                "DATESTATUSLPR" => trim($value['status_lpr_date']),
                "APPROVEBYLQA" => trim($value['approveby_lqa']),
                "DATEAPPROVEBYLQA" => trim($value['approveby_lqa_date']),
                "APPROVEBYLPR" => trim($value['approveby_lpr']),
                "DATEAPPROVEBYLPR" => trim($value['approveby_lpr_date']),
                "STATUSTRANSLQA" => trim($value['status_trans_lqa']),
                "STATUSTRANSLPR" => trim($value['status_trans_lpr']),
                "TRANSBYLQA" => trim($value['transportby_lqa']),
                "TRANSBYLPR" => trim($value['transportby_lpr']),
                "DATETRANSBYLQA" => trim($value['date_trans_lqa']),
                "DATETRANSBYLPR" => trim($value['date_trans_lpr']),
            ]);
        }

        $data['trq'] = $transportArray; 

        return $data;
    }

    public function approveOption(Request $request)
    {
        $dataTransport =  DB::connection('pgsql')->table('helpdesk.t_transport')
            ->where('ticketno', $request->ticketno)
            ->where(function($q){
                $q->where('sendto_lqa', 1)->where('status_lqa', 0)
                ->orWhere('sendto_lpr', 1)->where('status_lpr', 0);
                
            })
            ->get();

        $jsonTransportid = json_decode($dataTransport, true);

        /* Get Transportid */
        $transport = $jsonTransportid;
        $transportArray = [];
        foreach ($transport as $key => $value) {
            array_push($transportArray, [
                "TRANSPORTID" => trim($value['transportid'])
            ]);
        }

        $data['disc'] = $transportArray; 
        
        return $transportArray;
    }

    public function transportOption(Request $request)
    {
        $dataTransport =  DB::connection('pgsql')->table('helpdesk.t_transport')
            ->where('ticketno', $request->ticketno)
            ->where(function($q){
                $q->where('status_lqa', 1)->where('status_trans_lqa', 0)
                ->orWhere('status_lpr', 1)->where('status_trans_lpr', 0);
                
            })
            ->get();

        $jsonTransportid = json_decode($dataTransport, true);

        /* Get Transportid */
        $transport = $jsonTransportid;
        $transportArray = [];
        foreach ($transport as $key => $value) {
            array_push($transportArray, [
                "TRANSPORTID" => trim($value['transportid'])
            ]);
        }

        $data['disc'] = $transportArray; 
        
        return $transportArray;
    }

    public function transportOptionCreate(Request $request)
    {
        $dataTransport =  DB::connection('pgsql')->table('helpdesk.t_transport')
        ->where('ticketno', $request->ticketno)
        ->get();

        $jsonTransportid = json_decode($dataTransport, true);

        /* Get Transportid */
        $transport = $jsonTransportid;
        $transportArray = [];
        foreach ($transport as $key => $value) {
            array_push($transportArray, [
                "TRANSPORTID" => trim($value['transportid']),
                "LQA" => trim($value['sendto_lqa']),
                "LPR" => trim($value['sendto_lpr']),
                "TRANSPORTEDLQA" => trim($value['status_trans_lqa']),
                "TRANSPORTEDLPR" => trim($value['status_trans_lpr']),
            ]);
        }

        $data['disc'] = $transportArray; 
        
        return $transportArray;
    }

}
