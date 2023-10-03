<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\Mail;
use App\Helpers\Response;
use App\Helpers\Repository;
use App\Models\Meetingroom;
use App\Models\Bookroom;
use App\Models\Meetingtime;
use App\Models\Counter;
use DataTables;

class ViewroomController extends Controller
{
    public function __construct(Repository $repository, Response $response, Mail $mail)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->mail = $mail;
    }

    public function viewRoom(Request $request)
    {
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        }
        
        return view('fitur.viewroom');
    }

    public function listRoom(Request $request)
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $dataRoom = DB::connection('pgsql')->table('master_data.m_meeting_room as a')
            ->join('meeting.t_booking as b', 'a.roomid', '=', 'b.roomid')
            ->select('b.userid', 'b.username', 'a.roomid', 'a.roomname', 'a.roomfloor', 'a.roomcapacity', 'a.active', 'b.subject', 'b.description', 'b.status', 'b.startdate', 
            'b.enddate', 'b.starttime', 'b.endtime', 'b.bookedon', 'b.bookedby')
            ->where('a.roompublic', 1)
            ->where('a.active', 1)
            ->where('b.startdate', $date)
            // ->where('b.enddate', '<=', $date)
            // ->where('b.starttime', '>', $time)
            ->where('b.endtime', '>', $time)
            ->get();
        $dataTrimArray = [];

        foreach ($dataRoom as $key => $value) {
            array_push($dataTrimArray, [
                "userid" => trim($value->userid),
                "username" => trim($value->username),
                "roomid" => trim($value->roomid),
                "roomname" => trim($value->roomname),
                "roomfloor" => trim($value->roomfloor),
                "roomcapacity" => trim($value->roomcapacity),
                "active" => trim($value->active),
                "subject" => trim($value->subject),
                "description" => trim($value->description),
                "statusroom" => trim($value->status),  
                "startdate" => trim($value->startdate), 
                "enddate" => trim($value->enddate),
                "starttime" => trim($value->starttime),
                "endtime" => trim($value->endtime),
                "bookedon" => trim($value->bookedon),
                "bookedby" => trim($value->bookedby),
            ]);
        }
        $data['dat'] = $dataTrimArray;
       
        return DataTables::of($data['dat'])
        ->make(true);
    }

}
