<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Useraccount;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Attendancenew;
use App\Models\Tiket;
use App\Models\Tiketdiscussion;
use App\Models\Tiketpriority;
use App\Models\Tiketstatus;
use App\Models\Masteremployee;
use App\Models\Masterdivisi;
use App\Models\Masteradmin;
use App\Models\Masterbagian;
use App\Models\Mastergroup;
use App\Models\Masterperiode;
use Auth;

class Repository
{
    // public function __construct()
    // {
    //     ini_set('max_execution_time', 3);
    // }

    public static function GETUSER($userid, $password)
    {
        
        if(DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->exists())
        {
            $data = DB::connection('pgsql')->table('master_data.m_user as a')
                    ->join('master_data.m_department as b', 'a.departmentid', '=', 'b.departmentid')
                    ->where('userid', $userid)
                    ->first();
            $count = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->count();
            DB::commit();
            $userPass =  trim($data->pass);
            if($userPass == $password){
                $response = array(
                    'rc' => '00',
                    'msg' => 'success',
                    'data' => $data,
                    'count' => $count
                );
            } else {
                $response = array(
                    'rc' => '01',
                    'msg' => 'Wrong User Or Password',
                    'data' => [],
                    'count' => []
                );
            }
          
        } else {
            $response = array(
                'rc' => '01',
                'msg' => 'User Not Found',
                'data' => [],
                'count' => []
            );
        }
        return json_encode($response);
    }

    public static function GETABSEN($id, $userid, $start_date, $end_date, $roleid, $departmentid, $mgrid, $myteam, $start, $length)
    {   
        // $start_date = "2023-06-01";
        // $end_date = "2023-06-19";
        $datenow = date('Y-m-d');
        $datauser = DB::connection('pgsql')->table('master_data.m_user')
                    ->where('mgrid', $userid)
                    ->orWhere('userid', $userid)
                    ->get();

        $response = array(
            'data' => $datauser,
        );
        $dat_arr = $response['data']; 
        
        if ($roleid == 'RD006' || $roleid == 'RD002'){
            $arr_user = array();
            foreach($dat_arr as $key => $value){
                array_push($arr_user, $value->userid);
            };
        } else {
            $arr_user = array(
                $userid
            );
        }
        
        if(DB::connection('pgsql')->table('absensi.kartuabsensi')->where('id', $userid)->exists()){
            if ($start_date == $datenow && $end_date == $datenow && $myteam == '%'){
                if ($roleid == 'RD006'){
                    $count = DB::connection('pgsql')->table('absensi.kartuabsensi')
                    ->where('id', $userid)
                    ->where('tgl', '>', now()->subDays(30)->endOfDay())
                    ->orderBy('tgl', 'desc')
                    ->count();
            

                    $data = DB::connection('pgsql')->table('absensi.kartuabsensi')
                        ->where('id', $userid)
                        ->where('tgl', '>', now()->subDays(30)->endOfDay())
                        ->orderBy('tgl', 'desc')
                        ->offset($start)
                        ->limit($length)
                        ->get();
                    
                    if($data->isNotEmpty()){
                        $response = array(
                            'rc' => '00',
                            'msg' => 'success',
                            'data' => $data,
                            'total' => $count
                        );
                    } else {
                        $response = array(
                            'rc' => '01',
                            'msg' => 'failed',
                            'data' => [],
                            'total' => []
                        );
                    }
                } else {
                    $count = DB::connection('pgsql')->table('absensi.kartuabsensi')
                        ->whereIn('id', $arr_user)
                        ->where('tgl', '>', now()->subDays(30)->endOfDay())
                        ->orderBy('tgl', 'desc')
                        ->count();
                

                    $data = DB::connection('pgsql')->table('absensi.kartuabsensi')
                        ->whereIn('id', $arr_user)
                        ->where('tgl', '>', now()->subDays(30)->endOfDay())
                        ->orderBy('tgl', 'desc')
                        ->offset($start)
                        ->limit($length)
                        ->get();
                    
                    if($data->isNotEmpty()){
                        $response = array(
                            'rc' => '00',
                            'msg' => 'success',
                            'data' => $data,
                            'total' => $count
                        );
                    } else {
                        $response = array(
                            'rc' => '01',
                            'msg' => 'failed',
                            'data' => [],
                            'total' => []
                        );
                    }
                }
            } else if ($myteam == '%'){
                $countfilter = DB::connection('pgsql')->table('absensi.kartuabsensi')
                    ->whereIn('id', $arr_user)
                    ->whereBetween(DB::raw('DATE(tgl)'), [$start_date, $end_date])
                    ->count();

                $data = DB::connection('pgsql')->table('absensi.kartuabsensi')
                    ->whereIn('id', $arr_user)
                    ->whereBetween(DB::raw('DATE(tgl)'), [$start_date, $end_date])
                    ->offset($start)
                    ->limit($length)
                    ->get();
                    
                if($data->isNotEmpty()){
                    $response = array(
                        'rc' => '00',
                        'msg' => 'success',
                        'data' => $data,
                        'total' => $countfilter
                    );
                } else {
                    $response = array(
                        'rc' => '01',
                        'msg' => 'failed',
                        'data' => [],
                        'total' => []
                    );
                }
            } else {
                $countfilter = DB::connection('pgsql')->table('absensi.kartuabsensi')
                    // ->whereIn('id', $arr_user)
                    ->where('id', 'LIKE','%'.$myteam.'%')
                    ->whereBetween(DB::raw('DATE(tgl)'), [$start_date, $end_date])
                    ->orderBy('tgl', 'desc')
                    ->count();

                $data = DB::connection('pgsql')->table('absensi.kartuabsensi')
                    // ->whereIn('id', $arr_user)
                    ->where('id', 'LIKE','%'.$myteam.'%')
                    ->whereBetween(DB::raw('DATE(tgl)'), [$start_date, $end_date])
                    ->orderBy('tgl', 'desc')
                    ->offset($start)
                    ->limit($length)
                    ->get();
                    
                if($data->isNotEmpty()){
                    $response = array(
                        'rc' => '00',
                        'msg' => 'success',
                        'data' => $data,
                        'total' => $countfilter
                    );
                } else {
                    $response = array(
                        'rc' => '01',
                        'msg' => 'failed',
                        'data' => [],
                        'total' => []
                    );
                }
            }
            return json_encode($response);
        } else {
            $response = array(
                'rc' => '01',
                'msg' => 'failed1',
                'data' => [],
                'total' => []
            );
        }
        return json_encode($response);
    }

    public static function GETMYTICKET($userid, $divisionid, $roleid)
    {
        try{
            if($roleid == 'RD009'){
                $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                    ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'a.assignedto', 'a.createdon', 'b.mgrid' )
                    ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                    ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'c.description', 'a.assignedto', 'a.createdon' )
                    ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                    ->leftjoin('master_data.m_category as e',  function($q){
                        $q->on('a.categoryid', '=', 'e.categoryid')
                        ->on( 'a.systemid', '=', 'e.systemid' );
                    })
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                    ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date')
                    ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1')
                    ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid','a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1', 'h.username as approvedit')
                    ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid')
                    ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid', 'j.description as systemname')
                    ->where('f.divisionid', $divisionid)
                    ->orWhere('a.assignedto', '')
                    ->count();
            
                $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                    ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'a.assignedto', 'a.createdon', 'b.mgrid' )
                    ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                    ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'c.description', 'a.assignedto', 'a.createdon' )
                    ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                    ->leftjoin('master_data.m_category as e',  function($q){
                        $q->on('a.categoryid', '=', 'e.categoryid')
                        ->on( 'a.systemid', '=', 'e.systemid' );
                    })
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                    ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 'f.divisionid',
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date')
                    ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1')
                    ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid','a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1', 'h.username as approvedit')
                    ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid')
                    ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid', 'j.description as systemname')
                    ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                    ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname', 'f.divisionid')
                    ->where('f.divisionid', $divisionid)
                    ->orWhere('a.assignedto', '')
                    ->orderBy('a.ticketno', 'desc')
                    ->simplePaginate($count);
                 
            } else {
                $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'a.assignedto', 'a.createdon', 'b.mgrid' )
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'c.description', 'a.assignedto', 'a.createdon' )
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid','a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1', 'h.username as approvedit')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                        'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid')
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                        'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid', 'j.description as systemname')
                        ->where('a.userid', $userid)
                        ->orWhere('b.mgrid', $userid)
                        ->orWhere('a.assignedto', $userid)
                        ->orWhere('a.assignedto', '')
                        ->count();
            
                $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                    ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'a.assignedto', 'a.createdon', 'b.mgrid' )
                    ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                    ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'c.description', 'a.assignedto', 'a.createdon' )
                    ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                    ->leftjoin('master_data.m_category as e',  function($q){
                        $q->on('a.categoryid', '=', 'e.categoryid')
                        ->on( 'a.systemid', '=', 'e.systemid' );
                    })
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                    ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date')
                    ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1')
                    ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid','a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'g.username as approved1', 'h.username as approvedit')
                    ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid')
                    ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid', 'j.description as systemname')
                    ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                    ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 'a.target_date', 'g.username as approved1', 
                    'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                    ->where('a.userid', $userid)
                    ->orWhere('b.mgrid', $userid)
                    ->orWhere('a.assignedto', $userid)
                    ->orWhere('a.assignedto', '')
                    ->orderBy('a.ticketno', 'desc')
                    ->limit(10)
                    ->simplePaginate($count);
            }
                $response = array(
                    'rc' => '00',
                    'msg' => 'success',
                    'data' => $data,
                    'total' => $count
                );
                
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    }
    
    public static function GETFILTERMYTIKET($userid, $ticketno, $requestor, $status, $start_date, $end_date, $roleid, $myteam, $module, $divisionid)
    {   
        // $start_date = "2023-06-01";
        // $end_date = "2023-06-19";
        // $status = "SD00";
        // $ticketno = "HLP20230002";
        // $assignto = "101017";
        try{
            if($start_date == $end_date){
                if ($roleid == 'RD009') {
                    $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid') 
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid') 
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')                                                                                                                                                                                                
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('a.assignedto', 'LIKE','%'.$myteam.'%') 
                        ->where('f.divisionid', $divisionid)
                        ->count();
                
                    $data =  DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('a.assignedto', 'LIKE','%'.$myteam.'%')
                        ->where('f.divisionid', $divisionid)
                        ->limit(10)
                        ->orderBy('a.ticketno', 'desc')
                        ->simplePaginate($count);
                } else {
                    $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('f.userid', $userid) 
                        ->where('f.divisionid', $divisionid)
                        ->count();
        
                    $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('f.userid', $userid) 
                        ->where('f.divisionid', $divisionid)
                        ->limit(10)
                        ->simplePaginate($count);
    
                }
            } else {
                if ($roleid == 'RD009') {
                    $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid') 
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid') 
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')                                                                                                                                                                                                
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('a.assignedto', 'LIKE','%'.$myteam.'%')
                        ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date]) 
                        ->where('f.divisionid', $divisionid)
                        ->count();
                   
                    $data =  DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('a.assignedto', 'LIKE','%'.$myteam.'%')
                        ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                        ->where('f.divisionid', $divisionid)
                        ->orderBy('a.ticketno', 'desc')
                        ->simplePaginate($count);
                } else {
                    $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('a.assignedto', 'LIKE','%'.$myteam.'%')
                        ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                        ->count();
        
                    $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.statusid', 'LIKE','%'.$status.'%') 
                        ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                        ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                        ->where('a.moduleid', 'LIKE','%'.$module.'%')
                        ->where('a.assignedto', 'LIKE','%'.$myteam.'%')
                        ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                        ->orderBy('a.ticketno', 'desc')
                        ->limit(10)
                        ->simplePaginate($count);
    
                }
            }
          
            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'data' => $data,
                'total' => $count
            );
            
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    }

    public static function GETTIKET($userid, $roleid, $ticketno, $typeticket, $divisionid, $start, $length)
    {   
        try{
            // if($typeticket == 'ticketall'){
            //     if($ticketno == 'ticketlist'){
            //         if ($roleid == 'RD004' || $roleid == 'RD005' || $roleid == 'RD006' || $roleid == 'RD007' || $roleid == 'RD008' || $roleid == 'RD009' || $roleid == 'RD001') {
            //             $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            //                 ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
            //                 ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
            //                 ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
            //                 ->leftjoin('master_data.m_category as e',  function($q){
            //                     $q->on('a.categoryid', '=', 'e.categoryid')
            //                     ->on( 'a.systemid', '=', 'e.systemid' );
            //                 })
            //                 ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
            //                 ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
            //                 ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
            //                 ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
            //                 ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
            //                 ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
            //                 ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
            //                 ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
            //                 'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
            //                 'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
            //                 ->count();
            
            //             $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            //                 ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
            //                 ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
            //                 ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
            //                 ->leftjoin('master_data.m_category as e',  function($q){
            //                     $q->on('a.categoryid', '=', 'e.categoryid')
            //                     ->on( 'a.systemid', '=', 'e.systemid' );
            //                 })
            //                 ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
            //                 ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
            //                 ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
            //                 ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
            //                 ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
            //                 ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
            //                 ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
            //                 ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
            //                 'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
            //                 'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
            //                 ->limit(10)
            //                 ->orderBy('a.ticketno', 'desc')
            //                 ->offset($start)
            //                 ->limit($length)
            //                 ->get();

            //         } else {
            //             $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            //                 ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
            //                 ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
            //                 ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
            //                 ->leftjoin('master_data.m_category as e',  function($q){
            //                     $q->on('a.categoryid', '=', 'e.categoryid')
            //                     ->on( 'a.systemid', '=', 'e.systemid' );
            //                 })
            //                 ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
            //                 ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
            //                 ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
            //                 ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
            //                 ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
            //                 ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
            //                 ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
            //                 ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
            //                 'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
            //                 'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
            //                 ->where('a.userid', $userid)
            //                 ->orWhere('b.mgrid', $userid)
            //                 ->orWhere('a.assignedto', $userid)
            //                 ->orWhere('a.assignedto', '')
            //                 ->orderBy('a.ticketno', 'desc')
            //                 ->count();
            
            //             $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            //                 ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
            //                 ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
            //                 ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
            //                 ->leftjoin('master_data.m_category as e',  function($q){
            //                     $q->on('a.categoryid', '=', 'e.categoryid')
            //                     ->on( 'a.systemid', '=', 'e.systemid' );
            //                 })
            //                 ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
            //                 ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
            //                 ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
            //                 ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
            //                 ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
            //                 ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
            //                 ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
            //                 ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
            //                 ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
            //                 'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
            //                 'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
            //                 ->where('a.userid', $userid)
            //                 ->orWhere('b.mgrid', $userid)
            //                 ->orWhere('a.assignedto', '')
            //                 ->orderBy('a.ticketno', 'desc')
            //                 ->offset($start)
            //                 ->limit($length)
            //                 ->get();

            //         }
            //     } else {
            //         $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            //             ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
            //             ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
            //             ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
            //             ->leftjoin('master_data.m_category as e',  function($q){
            //                 $q->on('a.categoryid', '=', 'e.categoryid')
            //                 ->on( 'a.systemid', '=', 'e.systemid' );
            //             })
            //             ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
            //             ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
            //             ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
            //             ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
            //             ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
            //             ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
            //             ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
            //             ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
            //             'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
            //             'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
            //             ->where('a.ticketno', $ticketno)
            //             ->orderBy('a.ticketno', 'desc')
            //             ->count();
        
            //         $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            //             ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
            //             ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
            //             ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
            //             ->leftjoin('master_data.m_category as e',  function($q){
            //                 $q->on('a.categoryid', '=', 'e.categoryid')
            //                 ->on( 'a.systemid', '=', 'e.systemid' );
            //             })
            //             ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
            //             ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
            //             ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
            //             ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
            //             ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
            //             ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
            //             ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
            //             ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
            //             'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
            //             'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
            //             ->where('a.ticketno', $ticketno)
            //             ->orderBy('a.ticketno', 'desc')
            //             ->offset($start)
            //             ->limit($length)
            //             ->get();
            //     }
            // } else {
            if($ticketno == 'ticketlist'){    
                if($roleid == 'RD009'){
                    $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('f.divisionid', $divisionid)
                        ->orWhere('a.assignedto', '')
                        ->orderBy('a.ticketno', 'desc')
                        ->count();

                    $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('f.divisionid', $divisionid)
                        ->orWhere('a.assignedto', '')
                        ->orderBy('a.ticketno', 'desc')
                        ->offset($start)
                        ->limit($length)
                        ->get();
                } else if($roleid == 'RD006'){
                    $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.userid', $userid)
                        ->orWhere('a.assignedto', $userid)
                        ->orWhere('a.assignedto', '')
                        ->orderBy('a.ticketno', 'desc')
                        ->count();
        
                    $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.userid', $userid)
                        ->orWhere('a.assignedto', $userid)
                        ->orWhere('a.assignedto', '')
                        ->orderBy('a.ticketno', 'desc')
                        ->offset($start)
                        ->limit($length)
                        ->get();
                } else {
                    $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.userid', $userid)
                        ->orWhere('b.mgrid', $userid)
                        ->orWhere('a.assignedto', $userid)
                        ->orWhere('a.assignedto', '')
                        ->orderBy('a.ticketno', 'desc')
                        ->count();

                    $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->leftjoin('master_data.m_category as e',  function($q){
                            $q->on('a.categoryid', '=', 'e.categoryid')
                            ->on( 'a.systemid', '=', 'e.systemid' );
                        })
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                        ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                        ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                        ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                        ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                        ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                        'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                        ->where('a.userid', $userid)
                        ->orWhere('b.mgrid', $userid)
                        ->orWhere('a.assignedto', $userid)
                        ->orWhere('a.assignedto', '')
                        ->orderBy('a.ticketno', 'desc')
                        ->offset($start)
                        ->limit($length)
                        ->get();
                        // ->simplePaginate($count);
                }
            } else {
                $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                    ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                    ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                    ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                    ->leftjoin('master_data.m_category as e',  function($q){
                        $q->on('a.categoryid', '=', 'e.categoryid')
                        ->on( 'a.systemid', '=', 'e.systemid' );
                    })
                    ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                    ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                    ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                    ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                    ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                    ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                    ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                    'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                    ->where('a.ticketno', $ticketno)
                    ->orderBy('a.ticketno', 'desc')
                    ->count();
    
                $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                    ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                    ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                    ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                    ->leftjoin('master_data.m_category as e',  function($q){
                        $q->on('a.categoryid', '=', 'e.categoryid')
                        ->on( 'a.systemid', '=', 'e.systemid' );
                    })
                    ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                    ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                    ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                    ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                    ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                    ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                    ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                    ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                    'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                    'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                    ->where('a.ticketno', $ticketno)
                    ->orderBy('a.ticketno', 'desc')
                    ->offset($start)
                    ->limit($length)
                    ->get();
            }
            
            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'data' => $data,
                'total' => $count
            );
            
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    }

    public static function GETFILTERTIKET($userid, $ticketno, $requestor, $assignto, $status, $start_date, $end_date, $roleid, $system, $module, $typeticket, $divisionid, $start, $length, $keyword)
    {   
        // $start_date = "2023-06-01";
        // $end_date = "2023-06-19";
        // $status = "SD00";
        // $ticketno = "HLP20230002";
        // $assignto = "101017";
        try{
            if($typeticket == 'ticketall'){
                if($start_date == $end_date && $keyword == null){
                    if ($roleid == 'RD004' || $roleid == 'RD005' || $roleid == 'RD006' || $roleid == 'RD007' || $roleid == 'RD008' || $roleid == 'RD009' || $roleid == 'RD001') {
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid') 
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid') 
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')                                                                                                                                                                                                
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->count();
                    
                        $data =  DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else {
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('b.mgrid', $userid)
                            ->where('a.assignedto', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->count();
            
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('b.mgrid', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
        
                    }
                } else if($keyword == null){
                    if ($roleid == 'RD004' || $roleid == 'RD005' || $roleid == 'RD006' || $roleid == 'RD007' || $roleid == 'RD008' || $roleid == 'RD009' || $roleid == 'RD001') {
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid') 
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid') 
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')                                                                                                                                                                                                
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date]) 
                            ->count();
                    
                        $data =  DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date]) 
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else {
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('b.mgrid', $userid)
                            ->where('a.assignedto', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date]) 
                            ->count();
            
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('b.mgrid', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date]) 
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
        
                    }
                } else {
                    if ($roleid == 'RD004' || $roleid == 'RD005' || $roleid == 'RD006' || $roleid == 'RD007' || $roleid == 'RD008' || $roleid == 'RD009' || $roleid == 'RD001') {
                    
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid') 
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid') 
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')                                                                                                                                                                                                
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->count();
                    
                        $data =  DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname' , 'j.description as systemname')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else {
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('b.mgrid', $userid)
                            ->where('a.assignedto', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->count();
            
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('b.mgrid', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
        
                    }
                }
            } else {
                if($start_date == $end_date && $ticketno == '%' && $requestor == '%' && $assignto == '%' && $status == '%' && $system == '%' && $module == '%' && $keyword == null){
                    if($roleid == 'RD009'){
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('f.divisionid', $divisionid)
                            ->orWhere('a.assignedto', '')
                            ->orderBy('a.ticketno', 'desc')
                            ->count();
    
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('f.divisionid', $divisionid)
                            ->orWhere('a.assignedto', '')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else if($roleid == 'RD006'){
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->orWhere('a.assignedto', $userid)
                            ->orWhere('a.assignedto', '')
                            ->orderBy('a.ticketno', 'desc')
                            ->count();
            
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->orWhere('a.assignedto', $userid)
                            ->orWhere('a.assignedto', '')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else {
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->orWhere('b.mgrid', $userid)
                            ->orWhere('a.assignedto', $userid)
                            ->orWhere('a.assignedto', '')
                            ->orderBy('a.ticketno', 'desc')
                            ->count();
            
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->orWhere('b.mgrid', $userid)
                            ->orWhere('a.assignedto', $userid)
                            ->orWhere('a.assignedto', '')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    }
                } else if($start_date == $end_date) {
                    if($roleid == 'RD009'){
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('f.divisionid', $divisionid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->count();
    
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('f.divisionid', $divisionid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%')
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else if($roleid == 'RD006'){
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->count();
            
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else {
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.assignedto', $userid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->count();
        
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.assignedto', $userid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%') 
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get(); 
                    }     
                } else {
                    if($roleid == 'RD009'){
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('f.divisionid', $divisionid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%')
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                            ->orderBy('a.ticketno', 'desc')
                            ->count();
    
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid')
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date',  
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid', 'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('f.divisionid', $divisionid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%')
                            ->where('a.assignedto', 'LIKE','%'.$assignto.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else if($roleid == 'RD006'){
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                            ->orderBy('a.ticketno', 'desc')
                            ->count();
            
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.userid', $userid)
                            ->where('a.assignedto', '')
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();
                    } else {      
                        $count = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.assignedto', $userid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                            ->count();
        
                        $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                            ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                            ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                            ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                            ->leftjoin('master_data.m_category as e',  function($q){
                                $q->on('a.categoryid', '=', 'e.categoryid')
                                ->on( 'a.systemid', '=', 'e.systemid' );
                            })
                            ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                            ->leftjoin('master_data.m_user as g', 'a.approvedby_1', '=', 'g.userid')
                            ->leftjoin('master_data.m_user as h', 'a.approvedby_it', '=', 'h.userid')
                            ->leftjoin('master_data.m_user as i', 'a.createdby', '=', 'i.userid')
                            ->leftjoin('master_data.m_object_type as k', 'a.objectid', '=', 'k.objectid')
                            ->leftjoin('master_data.m_module as l', 'a.moduleid', '=', 'l.moduleid') 
                            ->join('master_data.m_system as j', 'a.systemid', '=', 'j.systemid')
                            ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                            'a.createdon', 'a.last_update', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'b.headid', 'b.spvid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date', 
                            'a.target_date', 'g.username as approved1', 'h.username as approvedit', 'i.username as created', 'a.systemid', 'a.moduleid',  'l.description as modulename', 'a.objectid', 'k.description as objectname', 'j.description as systemname')
                            ->where('a.assignedto', $userid)
                            ->where('a.ticketno', 'LIKE','%'.$ticketno.'%') 
                            ->where('a.statusid', 'LIKE','%'.$status.'%') 
                            ->where('a.userid', 'LIKE','%'.$requestor.'%')
                            ->where('a.moduleid', 'LIKE','%'.$module.'%')
                            ->where('a.systemid', 'LIKE','%'.$system.'%')
                            ->where('a.subject', 'LIKE','%'.$keyword.'%')
                            ->orWhere('a.detail', 'LIKE','%'.$keyword.'%')
                            ->whereBetween(DB::raw('DATE(a.createdon)'), [$start_date, $end_date])
                            ->orderBy('a.ticketno', 'desc')
                            ->offset($start)
                            ->limit($length)
                            ->get();           
                    }      
                }
            }
          
            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'data' => $data,
                'total' => $count
            );
            
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    }

    public static function GETTICKETAPPROVE($userid, $ticketno, $roleid)
    {
        try{
            $data = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
                        ->join('master_data.m_user as b', 'a.userid', '=', 'b.userid')
                        ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'a.assignedto', 'a.createdon', 'b.mgrid' )
                        ->join('master_data.m_ticket_priority as c', 'a.priorid', '=', 'c.priorid')
                        ->select('a.ticketno', 'a.userid', 'b.username', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'a.priorid', 'c.description', 'a.assignedto', 'a.createdon' )
                        ->join('master_data.m_ticket_status as d', 'a.statusid', '=', 'd.statusid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid', 'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                        ->join('master_data.m_category as e', 'a.categoryid', '=', 'e.categoryid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto', 'a.createdon' )
                        ->leftjoin('master_data.m_user as f', 'a.assignedto', '=', 'f.userid')
                        ->select('a.ticketno', 'a.userid', 'b.username as requestor', 'a.categoryid','e.description as category',  'a.subject', 'a.attachment', 'a.statusid', 'd.description as status','a.priorid', 'c.description as priority', 'a.assignedto','f.username as assigned_to', 
                        'a.createdon', 'b.departmentid', 'a.detail', 'a.approvedby_1', 'a.approvedby_2', 'a.approvedby_3', 'a.approvedby_it', 'a.rejectedby', 'a.createdby', 'b.mgrid', 'a.approvedby1_date', 'a.approvedby2_date', 'a.approvedby3_date', 'a.approvedbyit_date')
                        ->where('a.ticketno', $ticketno)
                        ->orderBy('a.ticketno', 'DESC')
                        ->get();
            
                $response = array(
                    'rc' => '00',
                    'msg' => 'success',
                    'data' => $data
                );
            
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    
    }
    
    public static function GETUSERBYROLE()
    {   
        try{
            $requestorAll = DB::connection('pgsql')->table('master_data.m_user')
            ->orderBy('username', 'asc')
            ->get();

            $requestor = DB::connection('pgsql')->table('master_data.m_user as a')
            ->join('master_data.m_role as b', 'a.roleid', '=', 'b.roleid')
            ->whereIn('b.roleid', ['RD002','RD003','RD005', 'RD006', 'RD007', 'RD008', 'RD009', 'RD010', 'RD012' ])
            ->orderBy('a.username', 'asc')
            ->get();

            $category = DB::connection('pgsql')->table('master_data.m_category')->where('flagging', 1)
            ->get();

            $priority = DB::connection('pgsql')->table('master_data.m_ticket_priority')
            ->get();

            $status = DB::connection('pgsql')->table('master_data.m_ticket_status')
            ->get();

            $ticketno = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->get();

            $assign = DB::connection('pgsql')->table('master_data.m_user as a')
            ->join('master_data.m_department as b', 'a.departmentid', '=', 'b.departmentid')
            ->whereIn('b.departmentid', ['DD001'])
            ->orderBy('a.username', 'asc')
            ->get();

            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'requestor' => $requestor,
                'requestorall' => $requestorAll,
                'category' => $category,
                'priority' => $priority,
                'assign' => $assign,
                'status' => $status,
                'ticketno' => $ticketno,
            );
            
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);  
         
    }

    public static function GETAPPROVEBYDEPARTMENT($departmentid, $userid)
    {
        try{
            $datauser = DB::connection('pgsql')->table('master_data.m_user')
                ->where('departmentid', $departmentid)
                ->get();

            $response = array(
                'data' => $datauser,
            );
            $dataTrimArray = $response['data']; 

            $arr_user = array();
            foreach ($dataTrimArray as $key => $value) {
                array_push($arr_user, [
                    "userid" => trim($value->userid),
                    "username" => trim($value->username),
                    "pass" => trim($value->pass),
                    "departmentid" => trim($value->departmentid),
                    "plantid" => trim($value->plantid),
                    "roleid" => trim($value->roleid),
                    "spvid" => trim($value->spvid),
                    "mgrid" => trim($value->mgrid),
                    "usermail" => trim($value->usermail),
                    "createdon" => trim($value->createdon),
                ]);
            }
            return $arr_user;
            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'data' => $arr_user
            );
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return $response;
    }
    
    public static function ADDTIKET($ticketno, $userreq, $category, $userid, $subject, $assign, $statusid, $createdon, $approvedby_1, $approvedby_it, $priority, $remark, $createdby, $departmentid, $upload, $roleid, $last, $counterid, $prefix, $targetdate, $system, $module, $object)
    {       
        try{
            $year = date('Y');
            /* passing Data to Array */
            if ($roleid == 'RD002') {
                $value = array(
                    'ticketno' => $ticketno,
                    'categoryid' => $category,
                    'userid' => $userid,
                    'subject' => $subject,
                    'detail' => $remark,
                    'attachment' => $upload,
                    'assignedto' => $assign,
                    'statusid' => $statusid,
                    'createdon' => $createdon,
                    'approvedby_1' => $approvedby_1,
                    'approvedby_2' => '',
                    'approvedby_3' => '',
                    'approvedby_it' => $approvedby_it,
                    'priorid' => $priority,
                    'rejectedby' => '',
                    'remark' => $remark,
                    'approvedby1_date' => date('Y-m-d'),
                    'approvedby2_date' => null,
                    'approvedby3_date' => null,
                    'approvedbyit_date' => null,
                    'createdby' => $createdby,
                    'targetdate' => $targetdate,
                    'systemid' => $system,
                    'moduleid' => $module,
                    'objectid' => $object
                );
            } else if ($roleid == 'RD003') {
                $value = array(
                    'ticketno' => $ticketno,
                    'categoryid' => $category,
                    'userid' => $userid,
                    'subject' => $subject,
                    'detail' => $remark,
                    'attachment' => $upload,
                    'assignedto' => $assign,
                    'statusid' => $statusid,
                    'createdon' => $createdon,
                    'approvedby_1' => $approvedby_1,
                    'approvedby_2' => '',
                    'approvedby_3' => '',
                    'approvedby_it' => $approvedby_it,
                    'priorid' => $priority,
                    'rejectedby' => '',
                    'remark' => $remark,
                    'approvedby1_date' => null,
                    'approvedby2_date' => null,
                    'approvedby3_date' => null,
                    'approvedbyit_date' => null,
                    'createdby' => $createdby,
                    'targetdate' => $targetdate,
                    'systemid' => $system,
                    'moduleid' => $module,
                    'objectid' => $object
                );
            } else {
                $value = array(
                    'ticketno' => $ticketno,
                    'categoryid' => $category,
                    'userid' => $userreq,
                    'subject' => $subject,
                    'detail' => $remark,
                    'attachment' => $upload,
                    'assignedto' => $assign,
                    'statusid' => $statusid,
                    'createdon' => $createdon,
                    'approvedby_1' => $approvedby_1,
                    'approvedby_2' => '',
                    'approvedby_3' => '',
                    'approvedby_it' => $approvedby_it,
                    'priorid' => $priority,
                    'rejectedby' => '',
                    'remark' => $remark,
                    'approvedby1_date' => date('Y-m-d'),
                    'approvedby2_date' => date('Y-m-d'),
                    'approvedby3_date' => date('Y-m-d'),
                    'approvedbyit_date' => date('Y-m-d'),
                    'createdby' => $createdby,
                    'targetdate' => $targetdate,
                    'systemid' => $system,
                    'moduleid' => $module,
                    'objectid' => $object
                );
            }
        
            $insert = DB::connection('pgsql')->table('helpdesk.t_ticket')->insert([
                'ticketno' => $value['ticketno'],
                'categoryid' => $value['categoryid'],
                'userid' => $value['userid'],
                'subject' => $value['subject'],
                'detail' => $value['detail'],
                'attachment' => $value['attachment'][0],
                'assignedto' => $value['assignedto'],
                'statusid' => $value['statusid'],
                'createdon' => $value['createdon'],
                'approvedby_1' => $value['approvedby_1'],
                'approvedby_2' => '',
                'approvedby_3' => '',
                'approvedby_it' => $value['approvedby_it'],
                'priorid' => $value['priorid'],
                'rejectedby' => '',
                'remark' => $value['rejectedby'],
                'approvedby1_date' => $value['approvedby1_date'],
                'approvedby2_date' => $value['approvedby2_date'],
                'approvedby3_date' => $value['approvedby3_date'],
                'approvedbyit_date' => $value['approvedbyit_date'],
                'createdby' => $value['createdby'],
                'target_date' => $value['targetdate'],
                'systemid' => $value['systemid'],
                'moduleid' => $value['moduleid'],
                'objectid' => $value['objectid']
            ]);
           
            DB::commit();
            $update = DB::connection('pgsql')->table('master_data.m_counter')
                ->where('counterid', $counterid)
                ->where('period', $year)
                ->where('prefix', $prefix)
                ->where('description', 'HELPDESK')
                ->update([
                    'last_number' => $last
            ]);
            DB::commit();
           
            return response()->json([
                'rc' => '00',
                'desc' => 'success',
                'msg' => 'success',
                'data' => ''
            ]);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'rc' => '01',
                'desc' => 'failed',
                'msg' => 'failed',
                'data' => $e->getMessage()
            ]);

        }
    }   

    public static function UPDATECOUNTER($last, $counterid)
    {   
        //tidak terpakai//
        $year = date('Y');
        $update = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', $counterid)->where('period', $year)->update([
            'last_number' => $last
        ]);

        if(!empty($update)){
            return response()->json([
                'rc' => '00',
                'desc' => 'success',
                'msg' => 'success',
                'data' => $update
            ]);
        } else {
            return response()->json([
                'rc' => '01',
                'desc' => 'failed',
                'msg' => 'failed',
                'data' => $update
            ]);
        }

    }

    public static function UPDATETICKET($userid, $ticketno, $assignto, $approvedby1, $approveby_it, $rejectedby, $statusid, $approveby_1_date, $approveby_it_date, $roleid)
    { 
        try{
            if($roleid == "RD006"){
                /* Manager IT */
                $update = DB::connection('pgsql')->table('helpdesk.t_ticket')
                ->where('ticketno', $ticketno)
                ->update([
                    'assignedto' => $assignto,
                    'statusid' => $statusid,
                    'approvedby_it' => $approveby_it,
                    'rejectedby' => $rejectedby,
                    'approvedbyit_date' => $approveby_it_date,
                ]);
            } else if($roleid == "RD002"){
                /* Manager User */
                $update = DB::connection('pgsql')->table('helpdesk.t_ticket')
                ->where('ticketno', $ticketno)
                ->update([
                    'assignedto' => $assignto,
                    'statusid' => $statusid,
                    'approvedby_1' => $approvedby1,
                    'rejectedby' => $rejectedby,
                    'approvedby1_date' => $approveby_1_date,
                ]);
            } else {
                /* IT Tim */
                $update = DB::connection('pgsql')->table('helpdesk.t_ticket')
                ->where('ticketno', $ticketno)
                ->update([
                    'assignedto' => $assignto,
                    'statusid' => $statusid,
                    'rejectedby' => $rejectedby,
                ]);
            }
            DB::commit();
            return response()->json([
                'rc' => '00',
                'desc' => 'success',
                'msg' => 'success',
                'data' => ''
            ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'rc' => '01',
                'desc' => 'failed',
                'msg' => 'failed',
                'data' => $e->getMessage()
            ]);

        }
    }

    public static function CLOSEDTICKET($ticketno, $assignto, $statusid, $remark)
    {
        try{
            $closed = DB::connection('pgsql')->table('helpdesk.t_ticket')
                ->where('ticketno', $ticketno)
                ->update([
                    'assignedto' => $assignto,
                    'statusid' => $statusid,
                    'remark' => $remark,
                ]);
        
            DB::commit();
            return response()->json([
                'rc' => '00',
                'desc' => 'success',
                'msg' => 'success',
                'data' => ''
            ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'rc' => '01',
                'desc' => 'failed',
                'msg' => 'failed',
                'data' => $e->getMessage()
            ]);
        }
    }

    public static function INDEXFILTERPERSNOLIA()
    {
        try {
            $nip = DB::connection('mysql2')->table('personalia.masteremployee')->where('Endda', '9998-12-31')->get();
            $divisi = DB::connection('mysql2')->table('personalia.masterdivisi')->get();
            $bagian = DB::connection('mysql2')->table('personalia.masterbagian')->get();
            $group = DB::connection('mysql2')->table('personalia.masterworkgroup')->get();
            $admin = DB::connection('mysql2')->table('personalia.masteradmin')->get();
            $periode = DB::connection('mysql2')->table('personalia.masterperiode')->get();
            $shift = DB::connection('mysql2')->table('personalia.mastershift')->get();

            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'nip' => $nip,
                'divisi' => $divisi,
                'bagian' => $bagian,
                'group' => $group,
                'admin' => $admin,
                'periode' => $periode,
                'shift' => $shift
            );

        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    }
    
    public static function GETPERSONALIA($nip, $limit, $page, $start_date, $end_date)
    {    
        // $start_date = '2023-06-02';
        // $end_date = '2023-06-02';
        $bindings = [
            'start' => $start_date,
            'end' => $end_date,
            'limit' => $limit,
            'page' => $page
        ];
        try{          
            $count = DB::connection('mysql2')->table('personalia.absensi as a')
            ->join('personalia.masteremployee as b', function($q){
                    $q->on('a.Nip', '=', 'b.Nip')
                    ->on( 'b.Begda', '<=', 'a.Tgl In' )
                    ->on('b.Endda', '>=', 'a.Tgl In');
            })
            ->leftjoin('personalia.mastershift as c', 'a.Shift', '=', 'c.Kode Shift')
            ->select('a.Nip', 'b.Nama', 'b.Kode Divisi AS KodeDivisi', 'b.Kode Bagian AS KodeBagian', 'b.Kode Group AS KodeGroup', 'a.Tgl In AS TglIn', 'a.Jam In AS JamIn',
                    'a.Tgl Out AS TglOut', 'a.Jam Out AS JamOut', 'a.Lama Kerja AS LamaKerja', 'a.Jam Lembur AS JamLembur', 'a.Shift', 'a.Lama Off AS LamaOff', 'a.No Kasus AS NoKasus',
                    'a.CardX', 'c.Jam In AS ShiftIn', 'c.Jam Out AS ShiftOut', \DB::raw('(
                        CASE WHEN b.`Kode Group` != "" THEN 
                            CASE 
                                WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN "VALID" 
                                WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN "VALID"
                                WHEN (a.`Lama Kerja` <= 7) THEN "LESS WORKING HOURS"
                                WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN "LATE < 10"
                            ELSE "INVALID" END 
                        ELSE "VALID" END) AS TimeValidation'))
            ->whereBetween('a.Tgl In', [$start_date, $end_date])
            ->selectRaw('WHERE TimeValidation IN ("INVALID", "LATE", "LESS WORKING HOURS")')
            ->count();
        $data = DB::connection('mysql2')->select("SELECT Nip, Nama, KodeDivisi, KodeBagian, KodeGroup, TglIn, JamIn, TglOut, JamOut, LamaKerja, JamLembur, Shift, LamaOff, NoKasus, CardX, ShiftIn, ShiftOut, TimeValidation,
            ( 
                SELECT `Kode Shift` FROM personalia.mastershift 
                WHERE `Jam In` >= JamIn AND `Jam Out` <= JamOut 
                AND `Kode Shift` REGEXP '^S.$'
                AND (TIME_TO_SEC(TIMEDIFF(`Jam In`, JamIn))/60) <= 60 
                AND (TIME_TO_SEC(TIMEDIFF(JamOut, `Jam Out`))/60) <= 360
            ) AS NewShift,
                CASE 
                    WHEN (TimeValidation = 'VALID') THEN 'VL'
                    WHEN (TimeValidation = 'INVALID') THEN 'IV'
                    WHEN (TimeValidation = 'LATE < 10') THEN 'LT'
                    WHEN (TimeValidation = 'LESS WORKING HOURS') THEN 'LS'
                END TimeCategory
            FROM (
                SELECT a.Nip, b.Nama, b.`Kode Divisi` AS KodeDivisi, b.`Kode Bagian` AS KodeBagian, b.`Kode Group` AS KodeGroup,
                a.`Tgl In` AS TglIn, a.`Jam In` AS JamIn, a.`Tgl Out` AS TglOut, a.`Jam Out` AS JamOut, a.`Lama Kerja` AS LamaKerja, a.`Jam Lembur` AS JamLembur,
                a.Shift, a.`Lama Off` AS LamaOff, a.`No Kasus` AS NoKasus, a.CardX, c.`Jam In` AS ShiftIn, c.`Jam Out` AS ShiftOut,
                CASE
                    WHEN (b.`Kode Group` != '') 
                    THEN
                        CASE
                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN 'VALID'
                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN 'VALID'
                        WHEN (a.`Lama Kerja` < 7) THEN 'LESS WORKING HOURS'
                        WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN 'LATE < 10'
                        ELSE 'INVALID'
                        END
                    ELSE 'VALID'
                END TimeValidation
                FROM personalia.absensi AS a 
                INNER JOIN personalia.masteremployee AS b ON a.Nip = b.Nip AND b.Begda <= a.`Tgl In` AND b.Endda >= a.`Tgl In`
                LEFT OUTER JOIN personalia.mastershift AS c ON a.Shift = c.`Kode Shift`
                WHERE a.`Tgl In` >= :start AND a.`Tgl In` <= :end
            ) a WHERE TimeValidation IN ('INVALID', 'LATE', 'LESS WORKING HOURS') LIMIT :limit, :page", $bindings);
        
            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'data' => $data,
                'total' => $count
            );
            
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    }

    public static function GETFILTERPERSONALIA($nip, $kodedivisi, $kodebagian, $kodegroup, $kodeadmin, $kodeperiode, $kontrak, $start_date, $end_date, $limit, $page)
    {   
        // $start_date = '2023-06-02';
        // $end_date = '2023-06-02';
        // $kodedivisi = 'HRD';
        // $kodebagian = 'a';
        // $kodegroup = 'a';
        // $kodeperiode = 'a';
        // $kontrak = 'a';
        if($nip == null || $nip == []){
            $bindings = [
                'start' => $start_date,
                'end' => $end_date,
                'limit' => $limit,
                'page' => $page,
                'divisi' => '%'.$kodedivisi.'%',
                'bagian' => '%'.$kodebagian.'%',
                'group' => '%'.$kodegroup.'%',
                'admins' => '%'.$kodeadmin.'%',
                'periode' => '%'.$kodeperiode.'%',
                'kontrak' => '%'.$kontrak.'%'
            ];
        } else {
            $bindings = [
                'start' => $start_date,
                'end' => $end_date,
                'limit' => $limit,
                'page' => $page,
                'divisi' => '%'.$kodedivisi.'%',
                'bagian' => '%'.$kodebagian.'%',
                'group' => '%'.$kodegroup.'%',
                'admins' => '%'.$kodeadmin.'%',
                'periode' => '%'.$kodeperiode.'%',
                'kontrak' => '%'.$kontrak.'%',
                'nip' => $nip
            ];
        }
        
        try{          
            if($start_date == $end_date){
                $count = DB::connection('mysql2')->table('personalia.absensi as a')
                    ->join('personalia.masteremployee as b', function($q){
                            $q->on('a.Nip', '=', 'b.Nip')
                            ->on( 'b.Begda', '<=', 'a.Tgl In' )
                            ->on('b.Endda', '>=', 'a.Tgl In');
                    })
                    ->leftjoin('personalia.mastershift as c', 'a.Shift', '=', 'c.Kode Shift')
                    ->select('a.Nip', 'b.Nama', 'b.Kode Divisi AS KodeDivisi', 'b.Kode Bagian AS KodeBagian', 'b.Kode Group AS KodeGroup', 'a.Tgl In AS TglIn', 'a.Jam In AS JamIn',
                            'a.Tgl Out AS TglOut', 'a.Jam Out AS JamOut', 'a.Lama Kerja AS LamaKerja', 'a.Jam Lembur AS JamLembur', 'a.Shift', 'a.Lama Off AS LamaOff', 'a.No Kasus AS NoKasus',
                            'a.CardX', 'c.Jam In AS ShiftIn', 'c.Jam Out AS ShiftOut', \DB::raw('(
                                CASE WHEN b.`Kode Group` != "" && a.`No Kasus` = "" THEN 
                                    CASE 
                                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN "VALID" 
                                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN "VALID"
                                        WHEN (a.`Lama Kerja` <= 7) THEN "LESS WORKING HOURS"
                                        WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN "LATE < 10"
                                    ELSE "INVALID" END 
                                ELSE "VALID" END) AS TimeValidation'))
                    ->whereBetween('a.Tgl In', [$start_date, $end_date])
                    ->where('b.Kode Divisi', 'LIKE', '%'.$kodedivisi.'%')
                    ->where('b.Kode Bagian', 'LIKE', '%'.$kodebagian.'%')
                    ->where('b.Kode Group', 'LIKE', '%'.$kodegroup.'%')
                    ->where('b.Kode Periode', 'LIKE', '%'.$kodeperiode.'%')
                    ->selectRaw('WHERE TimeValidation IN ("INVALID", "LATE", "LESS WORKING HOURS")')
                    ->count();
                $data = DB::connection('mysql2')->select("SELECT Nip, Nama, KodeDivisi, KodeBagian, KodeGroup, TglIn, JamIn, TglOut, JamOut, LamaKerja, JamLembur, Shift, LamaOff, NoKasus, CardX, ShiftIn, ShiftOut, TimeValidation,
                    ( 
                        SELECT `Kode Shift` FROM personalia.mastershift 
                        WHERE `Jam In` >= JamIn AND `Jam Out` <= JamOut 
                        AND `Kode Shift` REGEXP '^S.$'
                        AND (TIME_TO_SEC(TIMEDIFF(`Jam In`, JamIn))/60) <= 60 
                        AND (TIME_TO_SEC(TIMEDIFF(JamOut, `Jam Out`))/60) <= 360
                    ) AS NewShift,
                        CASE 
                            WHEN (TimeValidation = 'VALID') THEN 'VL'
                            WHEN (TimeValidation = 'INVALID') THEN 'IV'
                            WHEN (TimeValidation = 'LATE < 10') THEN 'LT'
                            WHEN (TimeValidation = 'LESS WORKING HOURS') THEN 'LS'
                        END TimeCategory
                    FROM (
                        SELECT a.Nip, b.Nama, b.`Kode Divisi` AS KodeDivisi, b.`Kode Bagian` AS KodeBagian, b.`Kode Group` AS KodeGroup,
                        a.`Tgl In` AS TglIn, a.`Jam In` AS JamIn, a.`Tgl Out` AS TglOut, a.`Jam Out` AS JamOut, a.`Lama Kerja` AS LamaKerja, a.`Jam Lembur` AS JamLembur,
                        a.Shift, a.`Lama Off` AS LamaOff, a.`No Kasus` AS NoKasus, a.CardX, c.`Jam In` AS ShiftIn, c.`Jam Out` AS ShiftOut,
                        CASE
                            WHEN (b.`Kode Group` != '' && a.`No Kasus` = '') 
                            THEN
                                CASE
                                WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN 'VALID'
                                WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN 'VALID'
                                WHEN (a.`Lama Kerja` < 7) THEN 'LESS WORKING HOURS'
                                WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN 'LATE < 10'
                                ELSE 'INVALID'
                                END
                            ELSE 'VALID'
                        END TimeValidation
                        FROM personalia.absensi AS a 
                        INNER JOIN personalia.masteremployee AS b ON a.Nip = b.Nip AND b.Begda <= a.`Tgl In` AND b.Endda >= a.`Tgl In`
                        LEFT OUTER JOIN personalia.mastershift AS c ON a.Shift = c.`Kode Shift`
                        WHERE a.`Tgl In` >= :start AND a.`Tgl In` <= :end
                        AND b.`Kode Divisi` LIKE :divisi
                        AND b.`Kode Bagian` LIKE :bagian
                        AND b.`Kode Group` LIKE :group
                        AND b.`Kode Admin` LIKE :admins
                        AND b.`Kode Periode` LIKE :periode
                        AND b.`Kode Kontrak` LIKE :kontrak
                    ) a  WHERE TimeValidation IN ('INVALID', 'LATE', 'LESS WORKING HOURS') LIMIT :limit, :page", $bindings);
            } else if($nip == []){
                $count = DB::connection('mysql2')->table('personalia.absensi as a')
                    ->join('personalia.masteremployee as b', function($q){
                            $q->on('a.Nip', '=', 'b.Nip')
                            ->on( 'b.Begda', '<=', 'a.Tgl In' )
                            ->on('b.Endda', '>=', 'a.Tgl In');
                    })
                    ->leftjoin('personalia.mastershift as c', 'a.Shift', '=', 'c.Kode Shift')
                    ->select('a.Nip', 'b.Nama', 'b.Kode Divisi AS KodeDivisi', 'b.Kode Bagian AS KodeBagian', 'b.Kode Group AS KodeGroup', 'a.Tgl In AS TglIn', 'a.Jam In AS JamIn',
                            'a.Tgl Out AS TglOut', 'a.Jam Out AS JamOut', 'a.Lama Kerja AS LamaKerja', 'a.Jam Lembur AS JamLembur', 'a.Shift', 'a.Lama Off AS LamaOff', 'a.No Kasus AS NoKasus',
                            'a.CardX', 'c.Jam In AS ShiftIn', 'c.Jam Out AS ShiftOut', \DB::raw('(
                                CASE WHEN b.`Kode Group` != "" && a.`No Kasus` = "" THEN 
                                    CASE 
                                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN "VALID" 
                                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN "VALID"
                                        WHEN (a.`Lama Kerja` <= 7) THEN "LESS WORKING HOURS"
                                        WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN "LATE < 10"
                                    ELSE "INVALID" END 
                                ELSE "VALID" END) AS TimeValidation'))
                    ->whereBetween('a.Tgl In', [$start_date, $end_date])
                    ->where('b.Kode Divisi', 'LIKE', '%'.$kodedivisi.'%')
                    ->where('b.Kode Bagian', 'LIKE', '%'.$kodebagian.'%')
                    ->where('b.Kode Group', 'LIKE', '%'.$kodegroup.'%')
                    ->where('b.Kode Periode', 'LIKE', '%'.$kodeperiode.'%')
                    ->selectRaw('WHERE TimeValidation IN ("INVALID", "LATE", "LESS WORKING HOURS")')
                    ->count();
                $data = DB::connection('mysql2')->select("SELECT Nip, Nama, KodeDivisi, KodeBagian, KodeGroup, TglIn, JamIn, TglOut, JamOut, LamaKerja, JamLembur, Shift, LamaOff, NoKasus, CardX, ShiftIn, ShiftOut, TimeValidation,
                ( 
                    SELECT `Kode Shift` FROM personalia.mastershift 
                    WHERE `Jam In` >= JamIn AND `Jam Out` <= JamOut 
                    AND `Kode Shift` REGEXP '^S.$'
                    AND (TIME_TO_SEC(TIMEDIFF(`Jam In`, JamIn))/60) <= 60 
                    AND (TIME_TO_SEC(TIMEDIFF(JamOut, `Jam Out`))/60) <= 360
                ) AS NewShift,
                    CASE 
                        WHEN (TimeValidation = 'VALID') THEN 'VL'
                        WHEN (TimeValidation = 'INVALID') THEN 'IV'
                        WHEN (TimeValidation = 'LATE < 10') THEN 'LT'
                        WHEN (TimeValidation = 'LESS WORKING HOURS') THEN 'LS'
                    END TimeCategory
                FROM (
                    SELECT a.Nip, b.Nama, b.`Kode Divisi` AS KodeDivisi, b.`Kode Bagian` AS KodeBagian, b.`Kode Group` AS KodeGroup,
                    a.`Tgl In` AS TglIn, a.`Jam In` AS JamIn, a.`Tgl Out` AS TglOut, a.`Jam Out` AS JamOut, a.`Lama Kerja` AS LamaKerja, a.`Jam Lembur` AS JamLembur,
                    a.Shift, a.`Lama Off` AS LamaOff, a.`No Kasus` AS NoKasus, a.CardX, c.`Jam In` AS ShiftIn, c.`Jam Out` AS ShiftOut,
                    CASE
                        WHEN (b.`Kode Group` != '' && a.`No Kasus` = '') 
                        THEN
                            CASE
                            WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN 'VALID'
                            WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN 'VALID'
                            WHEN (a.`Lama Kerja` < 7) THEN 'LESS WORKING HOURS'
                            WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN 'LATE < 10'
                            ELSE 'INVALID'
                            END
                        ELSE 'VALID'
                    END TimeValidation
                    FROM personalia.absensi AS a 
                    INNER JOIN personalia.masteremployee AS b ON a.Nip = b.Nip AND b.Begda <= a.`Tgl In` AND b.Endda >= a.`Tgl In`
                    LEFT OUTER JOIN personalia.mastershift AS c ON a.Shift = c.`Kode Shift`
                    WHERE a.`Tgl In` >= :start AND a.`Tgl In` <= :end
                    AND b.`Kode Divisi` LIKE :divisi
                    AND b.`Kode Bagian` LIKE :bagian
                    AND b.`Kode Group` LIKE :group
                    AND b.`Kode Admin` LIKE :admins
                    AND b.`Kode Periode` LIKE :periode
                    AND b.`Kode Kontrak` LIKE :kontrak
                ) a  WHERE TimeValidation IN ('INVALID', 'LATE', 'LESS WORKING HOURS')  LIMIT :limit, :page", $bindings);
            } else {
                $count = DB::connection('mysql2')->table('personalia.absensi as a')
                    ->join('personalia.masteremployee as b', function($q){
                            $q->on('a.Nip', '=', 'b.Nip')
                            ->on( 'b.Begda', '<=', 'a.Tgl In' )
                            ->on('b.Endda', '>=', 'a.Tgl In');
                    })
                    ->leftjoin('personalia.mastershift as c', 'a.Shift', '=', 'c.Kode Shift')
                    ->select('a.Nip', 'b.Nama', 'b.Kode Divisi AS KodeDivisi', 'b.Kode Bagian AS KodeBagian', 'b.Kode Group AS KodeGroup', 'a.Tgl In AS TglIn', 'a.Jam In AS JamIn',
                            'a.Tgl Out AS TglOut', 'a.Jam Out AS JamOut', 'a.Lama Kerja AS LamaKerja', 'a.Jam Lembur AS JamLembur', 'a.Shift', 'a.Lama Off AS LamaOff', 'a.No Kasus AS NoKasus',
                            'a.CardX', 'c.Jam In AS ShiftIn', 'c.Jam Out AS ShiftOut', \DB::raw('(
                                CASE WHEN b.`Kode Group` != "" && a.`No Kasus` = "" THEN 
                                    CASE 
                                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN "VALID" 
                                        WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN "VALID"
                                        WHEN (a.`Lama Kerja` <= 7) THEN "LESS WORKING HOURS"
                                        WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN "LATE < 10"
                                    ELSE "INVALID" END 
                                ELSE "VALID" END) AS TimeValidation'))
                    ->whereIn('a.Nip', $nip)
                    ->whereBetween('a.Tgl In', [$start_date, $end_date])
                    ->where('b.Kode Divisi', 'LIKE', '%'.$kodedivisi.'%')
                    ->where('b.Kode Bagian', 'LIKE', '%'.$kodebagian.'%')
                    ->where('b.Kode Group', 'LIKE', '%'.$kodegroup.'%')
                    ->where('b.Kode Periode', 'LIKE', '%'.$kodeperiode.'%')
                    ->selectRaw('WHERE TimeValidation IN ("INVALID", "LATE", "LESS WORKING HOURS")')
                    ->count();
                $data = DB::connection('mysql2')->select("SELECT Nip, Nama, KodeDivisi, KodeBagian, KodeGroup, TglIn, JamIn, TglOut, JamOut, LamaKerja, JamLembur, Shift, LamaOff, NoKasus, CardX, ShiftIn, ShiftOut, TimeValidation,
                ( 
                    SELECT `Kode Shift` FROM personalia.mastershift 
                    WHERE `Jam In` >= JamIn AND `Jam Out` <= JamOut 
                    AND `Kode Shift` REGEXP '^S.$'
                    AND (TIME_TO_SEC(TIMEDIFF(`Jam In`, JamIn))/60) <= 60 
                    AND (TIME_TO_SEC(TIMEDIFF(JamOut, `Jam Out`))/60) <= 360
                ) AS NewShift,
                    CASE 
                        WHEN (TimeValidation = 'VALID') THEN 'VL'
                        WHEN (TimeValidation = 'INVALID') THEN 'IV'
                        WHEN (TimeValidation = 'LATE < 10') THEN 'LT'
                        WHEN (TimeValidation = 'LESS WORKING HOURS') THEN 'LS'
                    END TimeCategory
                FROM (
                    SELECT a.Nip, b.Nama, b.`Kode Divisi` AS KodeDivisi, b.`Kode Bagian` AS KodeBagian, b.`Kode Group` AS KodeGroup,
                    a.`Tgl In` AS TglIn, a.`Jam In` AS JamIn, a.`Tgl Out` AS TglOut, a.`Jam Out` AS JamOut, a.`Lama Kerja` AS LamaKerja, a.`Jam Lembur` AS JamLembur,
                    a.Shift, a.`Lama Off` AS LamaOff, a.`No Kasus` AS NoKasus, a.CardX, c.`Jam In` AS ShiftIn, c.`Jam Out` AS ShiftOut,
                    CASE
                        WHEN (b.`Kode Group` != '' && a.`No Kasus` = '') 
                        THEN
                            CASE
                            WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN 'VALID'
                            WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN 'VALID'
                            WHEN (a.`Lama Kerja` < 7) THEN 'LESS WORKING HOURS'
                            WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN 'LATE < 10'
                            ELSE 'INVALID'
                            END
                        ELSE 'VALID'
                    END TimeValidation
                    FROM personalia.absensi AS a 
                    INNER JOIN personalia.masteremployee AS b ON a.Nip = b.Nip AND b.Begda <= a.`Tgl In` AND b.Endda >= a.`Tgl In`
                    LEFT OUTER JOIN personalia.mastershift AS c ON a.Shift = c.`Kode Shift`
                    WHERE a.Nip  IN :nip
                    AND a.`Tgl In` >= :start AND a.`Tgl In` <= :end
                    AND b.`Kode Divisi` LIKE :divisi
                    AND b.`Kode Bagian` LIKE :bagian
                    AND b.`Kode Group` LIKE :group
                    AND b.`Kode Admin` LIKE :admins
                    AND b.`Kode Periode` LIKE :periode
                    AND b.`Kode Kontrak` LIKE :kontrak
                ) a  WHERE TimeValidation IN ('INVALID', 'LATE', 'LESS WORKING HOURS') LIMIT :limit, :page", $bindings);


                // $count = DB::connection('mysql2')->table('personalia.masteremployee as a')
                //     ->join('personalia.absensi as c', 'a.Nip', '=', 'c.Nip')
                //     ->select('c.Nip', 'a.Nama', 'a.Kode Divisi', 'a.Kode Bagian', 'a.Kode Group', 'a.Kode Admin', 'a.Kode Periode', 'c.Tgl In', 'c.Jam In', 'c.Tgl Out', 'c.Jam Out', 'c.Lama Kerja',
                //         'c.Jam Lembur', 'c.Shift', 'c.Lama Off', 'c.No Kasus', 'c.CardX')
                //     ->whereIn('c.Nip', $nip)
                //     ->whereBetween('c.Tgl In', [$start_date, $end_date])
                //     ->where('a.Kode Divisi', 'LIKE', '%'.$kodedivisi.'%')
                //     ->where('a.Kode Bagian', 'LIKE', '%'.$kodebagian.'%')
                //     ->where('a.Kode Group', 'LIKE', '%'.$kodegroup.'%')
                //     ->where('a.Kode Periode', 'LIKE', '%'.$kodeperiode.'%')
                //     ->count();

                // $data = DB::connection('mysql2')->table('personalia.masteremployee as a')
                //     ->join('personalia.absensi as c', 'a.Nip', '=', 'c.Nip')
                //     ->select('c.Nip', 'a.Nama', 'a.Kode Divisi', 'a.Kode Bagian', 'a.Kode Group', 'a.Kode Admin', 'a.Kode Periode', 'a.tipekaryawan', 'c.Tgl In', 'c.Jam In', 'c.Tgl Out', 'c.Jam Out', 'c.Lama Kerja',
                //             'c.Jam Lembur', 'c.Shift', 'c.Lama Off', 'c.No Kasus', 'c.CardX')
                //     ->whereIn('c.Nip', $nip)
                //     ->whereBetween('c.Tgl In', [$start_date, $end_date])
                //     ->where('a.Kode Divisi', 'LIKE', '%'.$kodedivisi.'%')
                //     ->where('a.Kode Bagian', 'LIKE', '%'.$kodebagian.'%')
                //     ->where('a.Kode Group', 'LIKE', '%'.$kodegroup.'%')
                //     ->where('a.Kode Periode', 'LIKE', '%'.$kodeperiode.'%')
                //     ->limit(10)
                //     ->simplePaginate($count);
            }
            
            $response = array(
                'rc' => '00',
                'msg' => 'success',
                'data' => $data,
                'total' => $count
            );
            
        } catch(\Exception $e) {
            return $e->getMessage();
        }  
        return json_encode($response);   
    }

    public static function UPDATETUKARSHIFT($nip, $shift, $no_kasus, $jamin, $tglin, $jamout, $tglout, $timevalid, $jamlembur, $lamaoff)
    {
        try{
            $date = date('Y-m-d');
            if($timevalid == "INVALID"){
                $update1 = DB::connection('mysql2')->table('personalia.absensi')->where('Nip', $nip)->where('Tgl In', $tglin)
                ->update([
                    'Shift' => $shift
                ]);
            
                $insert = DB::connection('mysql2')->table('personalia.tukarshift')->insert([
                    'Tanggal' => $date,
                    'Nip' => $nip,
                    'Shift' => $shift
                ]);

                DB::commit();
                return response()->json([
                    'rc' => '00',
                    'desc' => 'success',
                    'msg' => 'success',
                    'data' => ''
                ]);
            } else {
                $update = DB::connection('mysql2')->table('personalia.absensi')->where('Nip', $nip)->where('Tgl In', $tglin)
                ->update([
                    'No Kasus' => $no_kasus
                ]);

                $insert = DB::connection('mysql2')->table('personalia.kasus')->insert([
                    'No Kasus' => $no_kasus,
                    'Tgl Kasus' => date('Y-m-d'),
                    'Nip' => $nip,
                    'Tgl In' => $tglin,
                    'Jam In' => $jamin,
                    'Tgl Out' => $tglout,
                    'Jam Out' => $jamout,
                    'Shift' => $timevalid,
                    'Tipe' => 'ADD',
                    'Jam Kurang' => 'b1',
                    'Bayar Jam' => 'b1',
                    'Bayar Penuh' =>'b1',
                    'Keterangan' => 'Shift yang Bermasalah',
                    'Jam Lembur' => $jamlembur,
                    'Lama Off' => $lamaoff,
                    'Jam Dibayar' => '0'
                ]);

                DB::commit();
                return response()->json([
                    'rc' => '00',
                    'desc' => 'success',
                    'msg' => 'success',
                    'data' => ''
                ]);
            }   
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'rc' => '01',
                'desc' => 'failed',
                'msg' => 'failed',
                'data' => $e->getMessage()
            ]);
        }
    }

    public static function UPDATETUKARSHIFTBULK($nip, $kodedivisi, $kodebagian, $kodegroup, $kodeadmin, $kodeperiode, $kontrak, $start_date, $end_date, $no_kasus)
    {
        try{
            $start_date = '2023-06-02';
            $end_date = '2023-06-02';
            
            /* get Data TimeValidation */
            $validation = DB::connection('mysql2')->table('personalia.absensi as a')
                ->join('personalia.masteremployee as b', function($q){
                        $q->on('a.Nip', '=', 'b.Nip')
                        ->on( 'b.Begda', '<=', 'a.Tgl In' )
                        ->on('b.Endda', '>=', 'a.Tgl In');
                })
                ->leftjoin('personalia.mastershift as c', 'a.Shift', '=', 'c.Kode Shift')
                ->select('a.Nip', 'b.Nama', 'b.Kode Divisi AS KodeDivisi', 'b.Kode Bagian AS KodeBagian', 'b.Kode Group AS KodeGroup', 'a.Tgl In AS TglIn', 'a.Jam In AS JamIn',
                        'a.Tgl Out AS TglOut', 'a.Jam Out AS JamOut', 'a.Lama Kerja AS LamaKerja', 'a.Jam Lembur AS JamLembur', 'a.Shift', 'a.Lama Off AS LamaOff', 'a.No Kasus AS NoKasus',
                        'a.CardX', 'c.Jam In AS ShiftIn', 'c.Jam Out AS ShiftOut', \DB::raw('(
                            CASE WHEN b.`Kode Group` != "" && a.`No Kasus` = "" THEN 
                                CASE 
                                    WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` = 0) THEN "VALID" 
                                    WHEN (a.`Jam In` <= c.`Jam In` AND a.`Jam Out` >= c.`Jam Out`) AND (a.`Jam Lembur` > 0) THEN "VALID"
                                    WHEN (a.`Lama Kerja` <= 7) THEN "LESS WORKING HOURS"
                                    WHEN ((TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) > 0 AND (TIME_TO_SEC(TIMEDIFF(a.`Jam In`, c.`Jam In`))/60) <= 10 ) THEN "LATE < 10"
                                ELSE "INVALID" END 
                            ELSE "VALID" END) AS TimeValidation'))
                ->whereIn('a.Nip', $nip)
                ->whereBetween('a.Tgl In', [$start_date, $end_date])
                ->get();

            foreach($validation as $valid){
                if($valid->TimeValidation == "INVALID"){
                    /* Get data New Shift */
                    $result = DB::connection('mysql2')->table('personalia.mastershift')
                        ->where('Jam In', '>=', $valid->JamIn)
                        ->where('Jam Out', '<=', $valid->JamOut)
                        ->where('Kode Shift', 'REGEXP', '^S.$')
                        ->whereRaw('((TIME_TO_SEC(TIMEDIFF(`Jam In`, ? ))/60) <= 60) AND ((TIME_TO_SEC(TIMEDIFF(`Jam Out`, ? ))/60) <= 360)', [$valid->JamIn, $valid->JamOut])
                        ->select('Kode Shift AS NewShift')
                        ->get();
                    
                    $arr_newshift = [];
                    foreach ($result as $key => $value) {
                        array_push($arr_newshift, $value->NewShift);
                    }
                    
                    /* Update Into Table Absensi */
                    for ($a=0; $a < count($nip) ; $a++){
                        $update = DB::connection('mysql2')->table('personalia.absensi')->where('Nip', $nip[$a])->update([
                            'Shift' => $arr_newshift[$a]
                        ]);
                    }

                    DB::commit();
                    return response()->json([
                        'rc' => '00',
                        'desc' => 'success',
                        'msg' => 'success',
                        'data' => ''
                    ]);

                    /* Insert Into Table Tukar Shift */
                    // DB::beginTransaction();
                    // $arrayInsert = [];
                    // for ($i=0; $i < count($nip) ; $i++){
                    //     $draw = [
                    //         'Tanggal' => date('Y-m-d'),
                    //         'Shift' => $arr_newshift[$i],
                    //         'Nip' => $nip[$i],
                    //     ];
                    //     $arrayInsert[] = $draw; 
                    // }
                    // $insert = DB::connection('mysql2')->table('personalia.tukarshift')->insert($arrayInsert);
                    // DB::commit();

                } else {
                    /* Get data New Shift */
                    $result = DB::connection('mysql2')->table('personalia.mastershift')
                        ->where('Jam In', '>=', $valid->JamIn)
                        ->where('Jam Out', '<=', $valid->JamOut)
                        ->where('Kode Shift', 'REGEXP', '^S.$')
                        ->whereRaw('((TIME_TO_SEC(TIMEDIFF(`Jam In`, ? ))/60) <= 60) AND ((TIME_TO_SEC(TIMEDIFF(`Jam Out`, ? ))/60) <= 360)', [$valid->JamIn, $valid->JamOut])
                        ->select('Kode Shift AS NewShift')
                        ->get();

                    $arr_newshift = [];
                    foreach ($result as $key => $value) {
                        array_push($arr_newshift, $value->NewShift);
                    }

                    /* Insert Into Table Kasus */
                    $arrayInsert = [];
                    for ($i=0; $i < count($nip); $i++){
                        $draw = [
                            'No Kasus' => $no_kasus[$i],
                            'Tgl Kasus' => date('Y-m-d'),
                            'Nip' => $nip[$i],
                            'Tgl In' => $valid->TglIn,
                            'Jam In' => $valid->JamIn,
                            'Tgl Out' => $valid->TglOut,
                            'Jam Out' => $valid->JamOut,
                            'Shift' => $valid->TimeValidation,
                            'Tipe' => 'ADD',
                            'Jam Kurang' => 'b1',
                            'Bayar Jam' => 'b1',
                            'Bayar Penuh' =>'b1',
                            'Keterangan' => 'Shift yang Bermasalah',
                            'Jam Lembur' => $valid->JamLembur,
                            'Lama Off' => $valid->LamaOff,
                            'Jam Dibayar' => '0'
                        ];
                        
                        $arrayInsert[] = $draw;      
                    }
                    $insert = DB::connection('mysql2')->table('personalia.kasus')->insert($arrayInsert);

                    /* Update No Kasus Into Table Absensi */
                    for ($i=0; $i < count($no_kasus); $i++){
                        $update = DB::connection('mysql2')->table('personalia.absensi')->where('Nip', $nip[$i])->update(['No Kasus' => $no_kasus[$i]]);
                    }

                    DB::commit();
                    return response()->json([
                        'rc' => '00',
                        'desc' => 'success',
                        'msg' => 'success',
                        'data' => ''
                    ]);
                }       
            }
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'rc' => '01',
                'desc' => 'failed',
                'msg' => 'failed',
                'data' => $e->getMessage()
            ]);

        }
    }  
}
