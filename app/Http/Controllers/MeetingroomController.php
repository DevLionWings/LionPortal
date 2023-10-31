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

class MeetingroomController extends Controller
{   
    public function __construct(Repository $repository, Response $response, Mail $mail)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->mail = $mail;
    }

    public function adminIndex(Request $request)
    {
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        }

        $usreq = '';
        $rmid = '';
        $tm = '';
        
        $dataUsr = $this->repository->GETUSERBYROLE();
        $json = json_decode($dataUsr, true);

        if($json["rc"] == "00") {
            /* Get User for User Requestor */
            $requestor = $json['requestor'];
            $requestorArray = [];
            foreach ($requestor as $key => $value) {
                array_push($requestorArray, [
                    "NAME" => trim($value['username']),
                    "ID" => trim($value['userid'])
                ]);
            }
            $data['usreq'] = $requestorArray; 
            /* End */
        }

        $dataRoom = DB::connection('pgsql')->table('master_data.m_meeting_room')->get();

        $dataTrimArray = [];
        foreach ($dataRoom as $key => $value) {
            array_push($dataTrimArray, [
                "NAME" => trim($value->roomname),
                "ID" => trim($value->roomid)
            ]);
        }
        $data['rmid'] = $dataTrimArray; 

        $dataTime = DB::connection('pgsql')->table('master_data.m_meeting_time')
        ->orderby('counter', 'asc')
        ->get();

        $dataTrimArray = [];
        foreach ($dataTime as $key => $value) {
            array_push($dataTrimArray, [
                "START" => trim($value->starttime),
                "END" => trim($value->endtime),
                "ID" => trim($value->counter)
            ]);
        }
        $data['tm'] = $dataTrimArray; 
        
        return view('fitur.adminroom', $data);
    }

    public function roomList(Request $request)
    {   
        $date = date('Y-m-d');

        $dataRoom = DB::connection('pgsql')->table('master_data.m_meeting_room as a')
            ->join('meeting.t_booking as b', 'a.roomid', '=', 'b.roomid')
            ->select('b.userid', 'b.username', 'a.roomid', 'a.roomname', 'a.roomfloor', 'a.roomcapacity', 'a.active', 'b.subject', 'b.description', 'b.status', 'b.startdate', 
            'b.bookid', 'b.enddate', 'b.starttime', 'b.endtime', 'b.bookedon', 'b.bookedby')
            ->whereIn('status', [0, 1, 2])
            ->where('roompublic', 1)
            ->where('active', 1)
            ->where('b.startdate', '<=', $date)
            ->where('b.enddate', '>=', $date)
            ->get();
        $dataTrimArray = [];

        foreach ($dataRoom as $key => $value) {
            array_push($dataTrimArray, [
                "bookid" => trim($value->bookid),
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
        ->addColumn('action', function($row){
            $datenow = date('Y-m-d');
            $datetime = date('H:i:s');
            // $editBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm" 
            // data-roomid="'.$row["roomid"].'"><i class="fas fa-edit"></i></a>';
            // $availBtn = '<a href="javascript:void(0)" class="avail btn btn-success btn-sm" 
            // data-roomid="'.$row["roomid"].'" data-bookid="'.$row["bookid"].'">Available</i></a>';
            $cancelBtn = '<a href="javascript:void(0)" class="cancel btn btn-info btn-sm" 
            data-roomid="'.$row["roomid"].'" data-bookid="'.$row["bookid"].'">Cancel</a>';

            $editBtn = ' <a href="javascript:void(0)" class="edit btn btn-success btn-sm" 
            data-roomid="'.$row["roomid"].'" data-userid="'.$row["userid"].'" data-bookid="'.$row["bookid"].'" data-startdate="'.$row["startdate"].'" data-enddate="'.$row["enddate"].'" 
            data-starttime="'.$row["starttime"].'" data-endtime="'.$row["endtime"].'"><i class="fas fa-edit"></i></a>';
            if($row["statusroom"] == '1'){
                return $cancelBtn. $editBtn;
            }
        })
        
        ->rawColumns(['action'])
        ->make(true);
    }

    public function countRoom(Request $request)
    {
        $ava = '';
        $book = '';
        $cncl = '';
        $nwrm = '';
        $date = date('Y-m-d');
        
        $dataAvail = DB::connection('pgsql')->table('meeting.t_booking')
            ->where('status', '0')
            ->where('startdate', $date)
            ->count();
            if($dataAvail != ''){
                $dataRespArray = [];
                array_push($dataRespArray, $dataAvail);
                $data['ava'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error1'
                ], 400);
            }
        
        $dataBooked = DB::connection('pgsql')->table('meeting.t_booking')
            ->where('status', '1')
            ->where('startdate', $date)
            ->count();
            if($dataBooked != ''){
                $dataRespArray = [];
                array_push($dataRespArray, $dataBooked);
                $data['book'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error2'
                ], 400);
            }
    
        $dataCancel = DB::connection('pgsql')->table('meeting.t_booking')
            ->where('status', '2')
            ->where('startdate', $date)
            ->count();
            if($dataCancel != ''){
                $dataRespArray = [];
                array_push($dataRespArray, $dataCancel);
                $data['cncl'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
    
        $dataNewroom = DB::connection('pgsql')->table('meeting.t_booking')
            ->where('status', '3')
            ->where('startdate', $date)
            ->count();
            if($dataNewroom != ''){
                $dataRespArray = [];
                array_push($dataRespArray, $dataNewroom);
                $data['nwrm'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error4'
                ], 400);
            }
            
        return $data;
    
    }

    public function addRoom(Request $request)
    {
        /* Generate Room Id */
        $year = date("Y");
        $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT005')->where('period', $year)->first();

        $subs1 = 'RM';
        $subs2 =  str_pad($dataPrefix->last_number + 1, 4, "00", STR_PAD_LEFT);
        $roomId = $subs1.$subs2;

        $last = $dataPrefix->last_number + 1;
        $update = DB::connection('pgsql')->table('master_data.m_counter')
            ->where('counterid', 'CT005')
            ->where('period', $year)
            ->update([
                'last_number' => $last
        ]);
        /* End */

        $dataRoom = DB::connection('pgsql')->table('master_data.m_meeting_room')->insert([
            'roomid' => $roomId,
            'roomname' =>  $request->roomname,
            'roombuilding' => '',
            'roomfloor' => $request->roomfloor,
            'roompublic' => $request->roompublic,
            'roomcapacity' => $request->roomcapacity,
            'plantid' => $request->plantid,
            'active' => $request->active,
            'timeid' => 'TM001',
            'createddate' => date('Y-m-d'),
            
        ]);

        if($dataRoom == true){
            return redirect()->route('admin-index')->with("success", "New Room Meeting Added");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function editRoom(Request $request)
    {   
        $dataEmailBookedBy = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $request->userid)->first();  
        $update = DB::connection('pgsql')->table('meeting.t_booking')->where('bookid', $request->bookid)->update([
            'bookid' => $request->bookid,
            'startdate' => $request->startdate1,
            'enddate' => $request->enddate1,
            'starttime' => $request->starttime1,
            'endtime' => $request->endtime1,
            'roomid' => $request->roomAvail1,
        ]);

        /* Send Notif Email Receipt Cancel Booking */
        $emailBook = Session::get('usermail');
        $assignNameBook = Session::get('username');
        $assignNameBookBy = $dataEmailBookedBy->username;
        $emailBookBy = $dataEmailBookedBy->usermail;
        $newBookId = $request->bookid.'[Change Room]';
        $subject = '';
        $desc = 'Range Date : '.$request->startdate1. '-'. $request->enddate1;
        $date = date('Y-m-d H:i:s');
        $starttime = $request->starttime1;
        $endtime = $request->endtime1;
        $SendMail = $this->mail->SENDMAILBOOKROOM($newBookId, $subject, $desc, $date, $starttime, $endtime, $assignNameBook, $emailBook, $emailBookBy, $assignNameBookBy);
        /* End */
    
        if($update == true){
            return redirect()->route('admin-index')->with("success", "Room update successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function userIndex(Request $request)
    {   
        $time = date('H:i:s');
        $rmid = '';
        $tm = '';
        
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        }

        $dataRoom = DB::connection('pgsql')->table('master_data.m_meeting_room')->get();

        $dataTrimArray = [];
        foreach ($dataRoom as $key => $value) {
            array_push($dataTrimArray, [
                "NAME" => trim($value->roomname),
                "ID" => trim($value->roomid)
            ]);
        }
        $data['rmid'] = $dataTrimArray; 

        
        $dataTime = DB::connection('pgsql')->table('master_data.m_meeting_time')
        ->orderby('counter', 'asc')
        ->get();

        $dataTrimArray = [];
        foreach ($dataTime as $key => $value) {
            array_push($dataTrimArray, [
                "START" => trim($value->starttime),
                "END" => trim($value->endtime),
                "ID" => trim($value->counter)
            ]);
        }
        $data['tm'] = $dataTrimArray; 
        
        return view('fitur.userroom', $data);
    }

    public function roomListUser(Request $request)
    {
        $date = date('Y-m-d');

        $dataRoom = DB::connection('pgsql')->table('master_data.m_meeting_room as a')
            ->join('meeting.t_booking as b', 'a.roomid', '=', 'b.roomid')
            ->select('b.userid', 'b.username', 'a.roomid', 'a.roomname', 'a.roomfloor', 'a.roomcapacity', 'a.active', 'b.subject', 'b.description', 'b.status', 'b.startdate', 
            'b.bookid', 'b.enddate', 'b.starttime', 'b.endtime', 'b.bookedon', 'b.bookedby')
            ->whereIn('status', [1, 2])
            ->where('roompublic', 1)
            ->where('active', 1)
            ->where('b.startdate', '<=', $date)
            ->where('b.enddate', '>=', $date)
            ->get();
        $dataTrimArray = [];

        foreach ($dataRoom as $key => $value) {
            array_push($dataTrimArray, [
                "bookid" => trim($value->bookid),
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
        ->addColumn('action', function($row){
            $datenow = date('Y-m-d');
            $userid = Session::get('userid');
            // $bookBtn = '<a href="javascript:void(0)" class="book btn btn-danger btn-sm" 
            // data-roomid="'.$row["roomid"].'">Booking</i></a>';
           
            $viewBtn = '<a href="javascript:void(0)" class="view btn btn-secondary btn-sm" 
            data-roomname="'.$row["roomname"].'"  data-bookid="'.$row["bookid"].'" data-subject="'.$row["subject"].'" data-startdate="'.$row["startdate"].'" data-enddate="'.$row["enddate"].'" 
            data-description="'.$row["description"].'" data-starttime="'.$row["starttime"].'" data-endtime="'.$row["endtime"].'"><i class="fa fa-eye" aria-hidden="true"></i></a>';

            $cancelBtn = ' <a href="javascript:void(0)" class="cancel btn btn-info btn-sm" 
            data-roomid="'.$row["roomid"].'"  data-bookid="'.$row["bookid"].'">Cancel</i></a>';
           
            if($row["statusroom"] == '1' && $row["userid"] == $userid){
                return  $viewBtn. $cancelBtn;
            } else {
                return $viewBtn;
            }

        })
        
        ->rawColumns(['action'])
        ->make(true);
    }

    public function bookRoom(Request $request)
    {   
       
        $roleid = Session::get('roleid');
        
        if($roleid == 'RD011'){
            $dataEmailBookedBy = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $request->bookedby)->first();
            $username = $dataEmailBookedBy->username;
            $userid = $dataEmailBookedBy->userid;
            $bookedby = Session::get('username');
        } else{
            $userid = Session::get('userid');
            $username = Session::get('username');
            $dataEmailBookedBy = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->first();
        }
        $subject = $request->subject;
        $desc = $request->detail;
        $date = $request->startdate;
        $starttime = $request->starttime;
        $endtime = $request->endtime;

        $validated = $request->validate([
                'startdate' => 'required',
                'enddate' => 'required',
                'starttime' => 'required',
                'endtime' => 'required',
                'roomAvail' => 'required',
            ],
            [
                'required'  => 'The :attribute field is required.'
            ]
        );
   
        $dataRoom = DB::connection('pgsql')->table('master_data.m_meeting_room')
        ->where('roomid', $request->roomAvail)
        ->orderBy('roomid','desc')
        ->first();
       
        /* Generate Room Id */
        $subs1 = substr($dataRoom->roomid, 0, 3);
        $subs2 = substr($dataRoom->roomid, 2, 3);
        $int = intval($subs2);
        $newInt = $int+1;
        $newRoomId = $subs1.$newInt;
        /* End */

        /* Generate Bookid Id */
        $year = date("Y");
        $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT004')->where('period', $year)->first();

        $subs1 = 'BM';
        $subs2 =  str_pad($dataPrefix->last_number + 1, 4, "00", STR_PAD_LEFT);
        $newBookId = $subs1.$subs2;

        $last = $dataPrefix->last_number + 1;
        $update = DB::connection('pgsql')->table('master_data.m_counter')
            ->where('counterid', 'CT004')
            ->where('period', $year)
            ->update([
                'last_number' => $last
        ]);
        /* End */

        $insert = DB::connection('pgsql')->table('meeting.t_booking')->insert([
            'bookid' => $newBookId,
            'userid' => $userid,
            'username' => $username,
            'roomid' => $dataRoom->roomid,
            'subject' => $request->subject,
            'description' => $request->detail,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
            'starttime' => $request->starttime,
            'endtime' => $request->endtime,
            'status' => 1,
            'bookedon' => date('Y-m-d H:i:s'),
            'bookedby' => $bookedby
        ]);

        /* Send Notif Email Receipt Booking */
        $emailBook = Session::get('usermail');
        $assignNameBook = Session::get('username');
        $assignNameBookBy = $dataEmailBookedBy->username;
        $emailBookBy = $dataEmailBookedBy->usermail;
        $SendMail = $this->mail->SENDMAILBOOKROOM($newBookId, $subject, $desc, $date, $starttime, $endtime, $assignNameBook, $emailBook, $emailBookBy, $assignNameBookBy);
        /* End */
        
       
        if($insert == true){
            if($roleid == 'RD011'){
                return redirect()->route('admin-index')->with("success", "Room Meeting Booked");
            } else {
                return redirect()->route('user-index')->with("success", "Room Meeting Booked");
            }
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function getRoom(Request $request)
    {
        $date = date('Y-m-d');
        /* Checked Room Available */
        $isValidRoom = DB::connection('pgsql')->table('master_data.m_meeting_room as a')
            ->join('master_data.m_meeting_time as b', 'a.timeid', '=', 'b.timeid')
            ->join('meeting.t_booking as c', function($q){
                $q->on('a.roomid', '=', 'c.roomid')
                ->on( 'b.starttime', '>=', 'c.starttime' )
                ->on('b.endtime', '<=', 'c.endtime');
            })
            ->select('c.userid', 'c.username', 'c.roomid', 'a.roomname', 'a.roomfloor', 'a.roomcapacity', 'a.active', 'c.subject', 
            'c.description', 'c.status', 'c.startdate', 'c.enddate', 'b.starttime', 'b.endtime', 'c.status')
            ->whereIn('c.status', [0, 1])
            ->where('c.startdate', '>=', $request->startdate)
            ->where('c.enddate', '<=', $request->enddate)
            ->where('b.starttime', '>=', $request->starttime)
            ->where('b.endtime', '<=', $request->endtime)
            ->distinct('c.roomid')
            ->get();

        $minDate =  DB::connection('pgsql')->table('meeting.t_booking')
                    ->where('status', 1)
                    ->where('enddate', '>=', $date)
                    ->min('startdate');

        $maxDate =  DB::connection('pgsql')->table('meeting.t_booking')
                    ->where('status', 1)
                    ->where('enddate', '>=', $date)
                    ->max('enddate');
   
        if($minDate == null && $maxDate == null){
            $minDate = $date;
            $maxDate = $date;
        } else if ($request->startdate > $minDate && $request->enddate > $maxDate){
            $minDate = $request->startdate;
            $maxDate = $request->enddate;
        } else {
            $minDate = $minDate;
            $maxDate = $maxDate;
        }
       
        $isValidRoom2 = DB::connection('pgsql')->table('master_data.m_meeting_room as a')
            ->join('master_data.m_meeting_time as b', 'a.timeid', '=', 'b.timeid')
            ->join('meeting.t_booking as c', function($q){
                $q->on('a.roomid', '=', 'c.roomid')
                ->on( 'b.starttime', '>=', 'c.starttime' )
                ->on('b.endtime', '<=', 'c.endtime');
            })
            ->select('c.userid', 'c.username', 'c.roomid', 'a.roomname', 'a.roomfloor', 'a.roomcapacity', 'a.active', 'c.subject', 
            'c.description', 'c.status', 'c.startdate', 'c.enddate', 'b.starttime', 'b.endtime', 'c.status')
            ->whereIn('c.status', [0, 1])
            ->where(function ($q) use ($minDate, $maxDate){
                $q->where('c.startdate', '>=', $minDate)
                ->where('c.enddate', '<=', $maxDate);
            })
            ->where('c.enddate', '>=', $date)
            ->where('b.starttime', '>=', $request->starttime)
            ->where('b.endtime', '<=', $request->endtime)
            ->distinct('c.roomid')
            ->get();

        $arr_isvalid = [];
        foreach($isValidRoom as $valid){
            array_push($arr_isvalid, $valid->roomid);
        }
        foreach($isValidRoom2 as $valid){
            array_push($arr_isvalid, $valid->roomid);
        }
        /* End */
        /* Get Room Available */
        $dataRoomBook = DB::connection('pgsql')->table('master_data.m_meeting_room')
            ->whereIn('roomid', $arr_isvalid)
            ->where('roompublic', 1)
            ->orderBy('roomid','desc')
            ->get();
        $dataRoomAvail = DB::connection('pgsql')->table('master_data.m_meeting_room')
            ->whereNotIn('roomid', $arr_isvalid)
            ->where('roompublic', 1)
            ->orderBy('roomid','desc')
            ->get();
        /* End */

        $response = array(
            'dataBook' => $dataRoomBook,
            'dataAvail' => $dataRoomAvail
        );

        return $response;
    }

    public function cancelRoom(Request $request)
    {
        $roleid = Session::get('roleid');
        
        $dataEmailBookedBy = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $request->userid)->first();  
        $update = DB::connection('pgsql')->table('meeting.t_booking')->where('bookid', $request->bookid)->update([
            'description' => $request->desc,
            'status' => 2,
            'bookedon' => date('Y-m-d H:i:s'),
            'bookedby' => $request->userid
        ]);

        /* Send Notif Email Receipt Cancel Booking */
        $emailBook = Session::get('usermail');
        $assignNameBook = Session::get('username');
        $assignNameBookBy = $dataEmailBookedBy->username;
        $emailBookBy = $dataEmailBookedBy->usermail;
        $newBookId = 'Canceled';
        $subject = '';
        $desc = $request->desc.' ('.date('Y-m-d H:i:s').')';
        $date = '';
        $starttime = '';
        $endtime = '';
        $SendMail = $this->mail->SENDMAILBOOKROOM($newBookId, $subject, $desc, $date, $starttime, $endtime, $assignNameBook, $emailBook, $emailBookBy, $assignNameBookBy);
        /* End */

        if($update == true){
            if($roleid == 'RD011'){
                return redirect()->route('admin-index')->with("success", "Room Meeting Canceled");
            } else {
                return redirect()->route('user-index')->with("success", "Room Meeting Canceled");
            }
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function availRoom(Request $request)
    {
        return $request->all();
        $update = DB::connection('pgsql')->table('meeting.t_booking')->where('bookid', $request->bookid)->update([
            'status' => 0,
            'bookedon' => date('Y-m-d')
        ]);
        
        if($update == true){
            return redirect()->route('admin-index')->with("success", "Room Meeting Available");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

}
