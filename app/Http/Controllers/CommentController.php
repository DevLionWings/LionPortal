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
        $ticketno = $request->ticketno;

        /* Generate Ticket Number */ 
        $year = date("Y");
        $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT002')->where('period', $year)->get();
        $prefix = $dataPrefix[0]->prefix;
        $period = $dataPrefix[0]->period;
        $start_numb = $dataPrefix[0]->start_number;
        $end_numb = $dataPrefix[0]->end_number;
        $last = $dataPrefix[0]->last_number;
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
            $last_numb =  str_pad($dataPrefix[0]->last_number + 1, 4, "00", STR_PAD_LEFT);

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
        DB::commit();

        return redirect()->route('tiket')->with("success", "successfully");
    }
}
