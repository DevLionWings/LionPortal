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
use App\Models\Tiketdiscussion;
use App\Models\Counter;

class CommentController extends Controller
{
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }


    public function addComment(Request $request)
    {   
        $userid = Session::get('userid');
        $useremail = Session::get('usermail');
        $mgrid = Session::get('mgrid');
        $roleid = Session::get('roleid');
        $comment_body = $request->input('comment_body');
        // $comment_body = $request->comment_body;
        $ticketno = $request->ticketno;
        $file = $request->filecomment;
        $requestor = $request->requestor;
        $mgrUser = $request->approve;
        $mgrIt = $request->approveit;
        $strfile = str_replace( "\\", '/', $file);
        $basefile = basename($strfile);
      
        $validate = $request->validate([
            'comment_body' => 'required'
        ]);

        if($validate){
            
            $upload = array();
            if (!empty($request->file('filecomment'))){
                $doc = $request->file('filecomment');
                $path = Storage::putFileAs("public/comment/".$userid."/".$ticketno, new File($doc), $ticketno."_".date('Y-m-d').".".$doc->getClientOriginalExtension());
                $path = explode("/", $path);
                $path[0] = "storage";
                array_push($upload, join("/",$path));
            } else {
                $upload = [''];
            }

            /* Generate Ticket Number */ 
            $year = date("Y");
            $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT002')->where('period', $year)->first();
            $prefix = $dataPrefix->prefix;
            $period = $dataPrefix->period;
            $start_numb = $dataPrefix->start_number;
            $end_numb = $dataPrefix->end_number;
            //test real
            $last = $dataPrefix->last_number;
            /* Session Data */
            $session = array(
                'last_number' => $last,
                'ticketno' => $ticketno,
            );
            /* Set User Session */
            Session::put('last_number', $last);
            Session::put('ticketno', $ticketno);
            $lastSession = Session::get('last_number');
            if ($start_numb <= $end_numb && $last == $lastSession){
                $last_numb =  str_pad($dataPrefix->last_number + 1, 4, "00", STR_PAD_LEFT);

            } else 
                $last_numb = '0000';
            /* End */

            $userid = Session::get('userid');
            $counterno = $prefix. $period. $last_numb;
            
            /* Insert Comment */
            $insert = DB::connection('pgsql')->table('helpdesk.t_discussion')->insert([
                'ticketno' => $request->ticketno,
                'counterno' => $counterno,
                'senderid' => $userid,
                'comment' => $request->comment_body,
                'attachment' => $basefile,
                'createdon' =>  date('Y-m-d H:i:s'),
            ]);

            $update = DB::connection('pgsql')->table('master_data.m_counter')
                ->where('counterid', $counterno)
                ->where('period', $year)
                ->where('prefix', $prefix)
                ->where('description', 'DISCUSSION')
                ->update([
                    'last_number' => $last
            ]);
            DB::commit();
            
            /* Send Email */
            $getTicket = DB::connection('pgsql')->table('helpdesk.t_ticket')->where('ticketno', $ticketno)->first();
            $assignto = $getTicket->assignedto;
            $detail = $getTicket->detail;
            $dataAssign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->first();
            $dataMgrIt = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrIt)->first();
            $dataMgrUser = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrUser)->first();
            $dataReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->first();

            $emailFrom = $useremail;
            $assignNameSign = $dataAssign->username;
            $emailSign =  $dataAssign->usermail;
            $emailMgrIt =  $dataMgrIt->usermail;
            $emailMgrUser =  $dataMgrUser->usermail;
            $emailRequestor = $dataReq->usermail;

            $SendMail = $this->mail->SENDMAILCOMMENT($ticketno, $comment_body, $assignNameSign, $emailSign, $emailFrom, $detail, $emailMgrIt, $emailMgrUser, $emailRequestor);
    
            /* End Send Email */

            $disc = ''; 
            $dataCommnt = DB::connection('pgsql')->table('helpdesk.t_discussion as a')
                ->join('master_data.m_user as b', 'a.senderid', '=', 'b.userid')
                ->select('a.senderid', 'b.username', 'a.createdon', 'a.comment', 'a.attachment')
                ->where('a.ticketno', $ticketno)
                ->orderBy('a.createdon', 'desc')
                ->first();    
        
            /* Get Comment */
            $commentArray = [];

            array_push($commentArray, [
                "COMMENT" => trim($dataCommnt->comment),
                "SENDER" => trim($dataCommnt->username),
                "DATE" => trim($dataCommnt->createdon),
                "FILE" => trim($dataCommnt->attachment),
            ]);

            $data['disc'] = $commentArray; 

            return $data;

        } else {
            return redirect()->route('tiket')->with("error", "required");
        }
        
    }

    public function listComment(Request $request){
        $disc = ''; 
        $ticketno = $request->ticketno;

        $dataCommnt = DB::connection('pgsql')->table('helpdesk.t_discussion as a')
                ->join('master_data.m_user as b', 'a.senderid', '=', 'b.userid')
                ->select('a.senderid', 'b.username', 'a.createdon', 'a.comment', 'a.attachment')
                ->where('a.ticketno', $ticketno)
                ->orderBy('a.createdon', 'desc')
                ->get();
      
        $jsonCmmnt = json_decode($dataCommnt, true);

        /* Get Comment */
        $comment = $jsonCmmnt;
        $commentArray = [];
        foreach ($comment as $key => $value) {
            array_push($commentArray, [
                "COMMENT" => trim($value['comment']),
                "SENDER" => trim($value['username']),
                "DATE" => trim($value['createdon']),
                "FILE" => trim($value['attachment']),
            ]);
        }

        $data['disc'] = $commentArray; 

        return $data;
    }

    public function countComment(Request $request){
        $disc = ''; 
        $ticketno = $request->ticketno;

        $countCommnt = DB::connection('pgsql')->table('helpdesk.t_discussion as a')
                ->join('master_data.m_user as b', 'a.senderid', '=', 'b.userid')
                ->where('a.ticketno', $ticketno)
                ->count();
      
        $jsonCmmnt = json_decode($countCommnt, true);

        $data['disc'] = $countCommnt; 

        return $data;
    }
}
