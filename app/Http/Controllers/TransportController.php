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

        /* Checked Transport Number*/
        if($request->opsi == "exist"){
            $transid = $request->data_transportid;
            /* IF Existing Get data transport id */
            $existing = DB::connection('pgsql')->table('helpdesk.t_transport')
                ->whereIn('transportid', $transid)
                ->get();

            $trim_transno = [];
            foreach ($existing as $key => $value) {
                array_push($trim_transno, trim(preg_replace('/\s+/', ',', $value->transportno)));
            }
            $transno = implode(",", $trim_transno);
            /* End */
        } else if($request->opsi == "new"){
            $transno = $request->transnumber;
        } else {
            $transno = "";
        }
        /* End */

        /* Checked Opsi Transport */
        if(empty($request->lpr)){
            $lqa = '1';
            $date_lqa = date('Y-m-d H:i:s');
            $lpr = '0';
            $date_lpr = date('Y-m-d H:i:s');
        } else if (empty($request->lqa)) {
            $lqa = '0';
            $date_lqa = date('Y-m-d H:i:s');
            $lpr = '1';
            $date_lpr = date('Y-m-d H:i:s');
        } else if (empty($request->lpr) && empty($request->lqa)){
            $lqa = '0';
            $date_lqa = date('Y-m-d H:i:s');
            $lpr = '0';
            $date_lpr = date('Y-m-d H:i:s');
        } else {
            $lqa = '1';
            $date_lqa = date('Y-m-d H:i:s');
            $lpr = '1';
            $date_lpr = date('Y-m-d H:i:s');
        }
        /* end checked */
        
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
        $insert = DB::connection('pgsql')->table('helpdesk.t_transport')->insert([
            'transportid' => $transportId,
            'ticketid' => $request->ticketno,
            'transportno' => $transno,
            'sendto_lqa' => $lqa,
            'createdon_lqa' => $date_lqa,
            'sendto_lpr' => $lpr,
            'createdon_lpr' => $date_lpr,
            'createdon' => date('Y-m-d H:i:s'),
        ]);
        /* End */
        /* Send Email */
        $emailTRANS = $this->mail->SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo);
        /* End */

        if($insert == true){
            return redirect()->route('tiket')->with("success", "transport send successfully");
        } else { 
            return redirect()->back()->with("error", "error");
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
        $transportId = $request->transid;
        $transno = $request->transno;
      
        if($request->sendlqa == '1' && $request->sendlpr == '1'){
            $datelqa = date('Y-m-d H:i:s');
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = Session::get('username');
            $usernamelpr = Session::get('username');
        } else if($request->sendlqa == '1'){
            $datelqa = date('Y-m-d H:i:s');
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = Session::get('username');
            $usernamelpr = '';
        } else if($request->sendlpr == '1'){
            $datelqa = date('Y-m-d H:i:s');
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = '';
            $usernamelpr = Session::get('username');
        } else {
            $datelqa = date('Y-m-d H:i:s');
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = '';
            $usernamelpr = '';
        }
  
        /* Approve Transport */
        $approve = DB::connection('pgsql')->table('helpdesk.t_transport')->update([
            'approveby_lqa_date' => $datelqa,
            'approveby_lpr_date' => $datelpr,
            'approveby_lqa' => $usernamelqa,
            'approveby_lpr' => $usernamelpr,
            'status_lqa' => $request->sendlqa,
            'status_lpr' => $request->sendlpr,
            'status_lqa_date' => $datelqa,
            'status_lpr_date' => $datelpr,
            'remark' => $request->remark
        ]);
        /* End */
        /* Send Email */
        $emailTRANS = $this->mail->SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo);
        /* End */

        if($approve == true){
            return redirect()->route('tiket')->with("success", "transport approved successfully");
        } else { 
            return redirect()->back()->with("error", "error");
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
        $transportId = $request->transid;
        $transno = $request->transno;

        if($request->sendlqa == '1' && $request->sendlpr == '1'){
            $datelqa = date('Y-m-d H:i:s');
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = Session::get('username');
            $usernamelpr = Session::get('username');
        } else if($request->sendlqa == '1'){
            $datelqa = date('Y-m-d H:i:s');
            $datelpr =  date('Y-m-d H:i:s');
            $usernamelqa = Session::get('username');
            $usernamelpr = '';
        } else if($request->sendlpr == '1'){
            $datelqa =  date('Y-m-d H:i:s');
            $datelpr = date('Y-m-d H:i:s');
            $usernamelqa = '';
            $usernamelpr = Session::get('username');
        } else {
            $datelqa = date('Y-m-d H:i:s');
            $datelpr =  date('Y-m-d H:i:s');
            $usernamelqa = '';
            $usernamelpr = '';
        }
       
        /* Trasnported Transport */
        $transported = DB::connection('pgsql')->table('helpdesk.t_transport')->update([
            'date_trans_lqa' => $datelqa,
            'date_trans_lpr' => $datelpr,
            'transportby_lqa' => $usernamelqa,
            'transportby_lpr' => $usernamelpr,
            'status_trans_lqa' => $request->sendlqa,
            'status_trans_lpr' => $request->sendlpr,
            'remark' => $request->remark
        ]);
        /* End */
        /* Send Email */
        $emailTRANS = $this->mail->SENDMAILTRANSPORT($transportId, $ticketno, $transno, $emailNameSender, $emailSender, $emailSendTo, $emailNameSendTo);
        /* End */

        if($transported == true){
            return redirect()->route('tiket')->with("success", "transported submit successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function listTransport(Request $request)
    {
        $trq = ''; 
        $ticketno = $request->ticketno;

        $dataTransport = DB::connection('pgsql')->table('helpdesk.t_transport')
                ->where('ticketid', $ticketno)
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

}
