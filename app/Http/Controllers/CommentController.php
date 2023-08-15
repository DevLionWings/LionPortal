<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Useraccount;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Tiketdiscussion;
use App\Models\Counter;

class CommentController extends Controller
{

    public function addComment(Request $request)
    {   

        $comment_body = $request->comment_body;
        $ticketno = $request->ticketno;
        $file = $request->filecomment;

        $validate = $request->validate([
            'comment_body' => 'required'
        ]);
        
        if($validate){
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
                'attachment' => '',
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

            $disc = ''; 
            $dataCommnt = DB::connection('pgsql')->table('helpdesk.t_discussion as a')
                ->join('master_data.m_user as b', 'a.senderid', '=', 'b.userid')
                ->select('a.senderid', 'b.username', 'a.createdon', 'a.comment')
                ->where('a.ticketno', $ticketno)
                ->latest('a.ticketno')
                ->first();
            
            /* Get Comment */
            $commentArray = [];

            array_push($commentArray, [
                "COMMENT" => trim($dataCommnt->comment),
                "SENDER" => trim($dataCommnt->username),
                "DATE" => trim($dataCommnt->createdon)
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
        // $dataCommnt = DB::connection('pgsql')->table('helpdesk.t_discussion')->where('ticketno', $request->ticketno)->get();
        // $jsonCmmnt = json_decode($dataCommnt, true);
        $dataCommnt = DB::connection('pgsql')->table('helpdesk.t_discussion as a')
                ->join('master_data.m_user as b', 'a.senderid', '=', 'b.userid')
                ->select('a.senderid', 'b.username', 'a.createdon', 'a.comment')
                ->where('a.ticketno', $ticketno)
                ->get();
        $jsonCmmnt = json_decode($dataCommnt, true);

        /* Get Comment */
        $comment = $jsonCmmnt;
        $commentArray = [];
        foreach ($comment as $key => $value) {
            array_push($commentArray, [
                "COMMENT" => trim($value['comment']),
                "SENDER" => trim($value['username']),
                "DATE" => trim($value['createdon'])
            ]);
        }

        $data['disc'] = $commentArray; 

        return $data;
    }
}
