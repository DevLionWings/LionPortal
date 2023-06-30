<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use App\Helpers\Mail;
use App\Helpers\Response;
use App\Helpers\Repository;
use App\Models\Counter;
use App\Models\Tiketdiscussion;
use DataTables;
Use Redirect;

class TiketController extends Controller
{
    public function __construct(Repository $repository, Response $response, Mail $mail)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->mail = $mail;
    }

    public function tiket(Request $request)
    {
        $usreq = '';
        $categ = '';
        $prior = '';
        $assn = '';
        $stat = '';
        $tick = '';
        $disc = ''; 
        
        $dataCommnt = DB::connection('pgsql')->table('helpdesk.t_discussion as b')
                ->join('master_data.m_user as a', 'b.senderid', '=', 'a.userid')
                ->select('a.userid', 'a.username', 'a.createdon', 'a.mgrid', 'b.comment')
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
        /* End */

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

            /* Get Category */
            $category = $json['category'];
            $categoryArray = [];

            foreach ($category as $key => $value) {
                array_push($categoryArray, [
                    "NAME" => trim($value['description']),
                    "ID" => trim($value['categoryid'])
                ]);
            }
            $data['categ'] = $categoryArray; 
            /* End */

            /* Get Priority */
            $priority = $json['priority'];
            $priorityArray = [];

            foreach ($priority as $key => $value) {
                array_push($priorityArray, [
                    "NAME" => trim($value['description']),
                    "ID" => trim($value['priorid'])
                ]);
            }
            $data['prior'] = $priorityArray; 
            /* End */

            /* Get Assigned To */
            $assign = $json['assign'];
            $assignArray = [];

            foreach ($assign as $key => $value) {
                array_push($assignArray, [
                    "NAME" => trim($value['username']),
                    "ID" => trim($value['userid'])
                ]);
            }
            $data['assn'] = $assignArray; 
            /* End */

            /* Get status */
            $status = $json['status'];
            $statusArray = [];
            
            foreach ($status as $key => $value) {
                array_push($statusArray, [
                    "NAME" => trim($value['description']),
                    "ID" => trim($value['statusid'])
                ]);
            }
            $data['stat'] = $statusArray; 
            /* End */

            /* Get Ticket Number */
            $ticketno = $json['ticketno'];
            $ticketnoArray = [];
            
            foreach ($ticketno as $key => $value) {
                array_push($ticketnoArray, [
                    "NAME" => trim($value['ticketno']),
                    "ID" => trim($value['ticketno'])
                ]);
            }
            $data['tick'] = $ticketnoArray; 
            /* End */
        }   
        
        return view('fitur.tiket', $data);
    }

    public function tiketList(Request $request)
    {
        $userid = Session::get('userid');
        $roleid = Session::get('roleid');

        /* Get Data Ticket */
        $dataTicket = $this->repository->GETTIKET($userid, $roleid);
        $json = json_decode($dataTicket, true);
        $dat = '';

        if($json["rc"] == "00") 
        {   
            $dataTrim = $json["data"]['data'];
            $dataTrimArray = [];
            
            foreach ($dataTrim as $key => $value) {
                array_push($dataTrimArray, [
                    "ticketno" => trim($value['ticketno']),
                    "userid" => trim($value['userid']),
                    "requestor" => trim($value['requestor']),
                    "categoryid" => trim($value['categoryid']),
                    "category" => trim($value['category']),
                    "subject" => trim($value['subject']),
                    "attachment" => trim($value['attachment']),
                    "statusid" => trim($value['statusid']),
                    "status" => trim($value['status']),
                    "priorid" => trim($value['priorid']),
                    "priority" => trim($value['priority']),
                    "detail" => trim($value['detail']),
                    "assignedto" => trim($value['assignedto']),
                    "assigned_to" => trim($value['assigned_to']),
                    "createdon" => trim($value['createdon']),
                    "departmentid" => trim($value['departmentid']),
                    "approvedby_1" => trim($value['approvedby_1']),
                    "approvedby_2" => trim($value['approvedby_2']),
                    "approvedby_3" => trim($value['approvedby_3']),
                    "approvedby_it" => trim($value['approvedby_it']),
                    "rejectedby" => trim($value['rejectedby']),
                    "approvedby1_date" => trim($value['approvedby1_date']),
                    "approvedby2_date" => trim($value['approvedby2_date']),
                    "approvedby3_date" => trim($value['approvedby3_date']),
                    "approvedbyit_date" => trim($value['approvedbyit_date']),
                    "createdby" => trim($value['createdby']),
                    "approvedby1Name" => trim($value['approved1']),
                    "approvedbyitName" => trim($value['approvedit']),
                    
                ]);
            }
            $data['dat'] = $dataTrimArray;
            
        } else {
            $data = ['']; 
        }   
        $resp = json_encode($data);
        
        return DataTables::of($data['dat'])
            ->addColumn('action', function($row){
                $userid = Session::get('userid');
                $roleid = Session::get('roleid');
                $mgrid = Session::get('mgrid');
                $parentBtn = '<a href="javascript:void(0)" class="view btn btn-info" data-ticket="'.$row["ticketno"].'" data-id="'.$row["userid"].'" data-statusid="'.$row["statusid"].'"
                data-requestor="'.$row["requestor"].'" data-status="'.$row["status"].'" data-category="'.$row["category"].'" data-priority="'.$row["priority"].'" data-subject="'.$row["subject"].'" 
                data-detail="'.$row["detail"].'" data-assignto="'.$row["assigned_to"].'" data-created="'.$row["createdby"].'" data-approve="'.$row["approvedby_1"].'" data-upload="'.$row["attachment"].'" 
                data-approve1name="'.$row["approvedby1Name"].'" data-approveitname="'.$row["approvedbyitName"].'"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                $document_name = str_replace("storage/", "", $row["attachment"]);
                $download_btn = '<a download="'.explode(";",$row["attachment"])[0].'" href="'.Storage::url(explode(";",$document_name)[0]).'" target="_blank" class="btn btn-default" 
                style="margin-left: 5px"><i class="fa fa-download" aria-hidden="true"></i><i class="far fa-file-pdf"></i></a>';
            
                $approveMgrBtn = ' <button href="javascript:void(0)" class="update btn btn-success" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$row["assignedto"].'"
                data-approvedby1="'.$row["approvedby_1"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'" data-approvedby1_date ="'.$row["approvedby1_date"].'"><i class="fa fa-check" aria-hidden="true"></i></button>';

                $approveBtn = ' <button href="javascript:void(0)" class="update btn btn-success" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$row["assignedto"].'"
                data-approvedbyit="'.$row["approvedby_it"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'" data-approvedbyit_date ="'.$row["approvedbyit_date"].'"><i class="fa fa-check" aria-hidden="true"></i></button>';

                $rejectBtn = ' <button href="javascript:void(0)" class="reject btn btn-danger" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$userid.'"
                data-approvedby1="'.$row["approvedby_it"].'" data-approvedbyit="'.$row["approvedby_it"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'"><i class="fa fa-ban" aria-hidden="true"></i></button>';
                
                // $superAdminBtn = $parentBtn. $approveBtn. $rejectBtn;
                if($row["categoryid"] == 'CD001' && $row["statusid"] == 'SD006' && $row["assignedto"] == '' ){
                    $itBtn = $parentBtn. $download_btn. ' <button href="javascript:void(0)" class="update btn btn-warning" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-assignto="'.$row["assignedto"].'"
                    data-approvedby1="'.$row["approvedby_1"].'" data-approvedbyit="'.$mgrid.'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" ><i class="fas fa-user-plus"></i></button>';
                    $managerBtn = $parentBtn. $download_btn;
                    $managerItBtn = $parentBtn. $download_btn. $approveBtn. $rejectBtn;
                } else  if($row["statusid"] == 'SD002' && $userid == $row["assignedto"]){
                    $itBtn = $parentBtn. $download_btn. ' <button href="javascript:void(0)" class="closed btn btn-danger" data-status="'.$row["status"].'" data-statusid="SD003" data-status="'.$row["status"].'" data-assignto="'.$userid.'"
                    data-approvedby1="'.$row["approvedby_1"].'" data-approvedbyit="'.$mgrid.'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$userid.'"><i class="fa fa-window-close" aria-hidden="true"></i></button>';
                    $managerBtn = $parentBtn. $download_btn;
                    $managerItBtn = $parentBtn. $download_btn;
                } else if($row["approvedby_1"] == null && $row["statusid"] == 'SD001' && $userid == $row["assignedto"]){
                    $managerBtn = $parentBtn. $download_btn. $approveMgrBtn. $rejectBtn;
                    $itBtn = $parentBtn. $download_btn;
                    $managerItBtn = $parentBtn. $download_btn;
                } else if($row["approvedby_1"] != null && $row["statusid"] == 'SD001' && $userid == $row["assignedto"] ){
                    $managerItBtn = $parentBtn. $download_btn. $approveBtn. $rejectBtn;
                    $itBtn = $parentBtn. $download_btn;
                    $managerBtn = $parentBtn. $download_btn;
                } else {
                    $itBtn = $parentBtn. $download_btn;
                    $managerBtn = $parentBtn. $download_btn;
                    $managerItBtn = $parentBtn. $download_btn;
                }
                
                if($roleid == 'RD004' || $roleid == 'RD005'){
                    return $itBtn;
                }
                if($roleid == 'RD002'){ 
                    return $managerBtn;
                }
                if($roleid == 'RD006'){
                    return $managerItBtn;
                }
                if($roleid == 'RD003'){
                    return $parentBtn. $download_btn;
                }

            })
            ->rawColumns(['action'])
            ->setTotalRecords($json["total"])
            ->setFilteredRecords($json["total"])
            ->make(true);
    }

    public function tiketFilter(Request $request)
    {
        $userid = Session::get('userid');
        $roleid = Session::get('roleid');
        $requestor = $request->requestor;
        $assignto = $request->assignto;
        $status = $request->status;
        $ticketno = $request->ticketno;
        $date_arr = $request->get('daterange');
        $start = explode(' - ',$date_arr)[0];
        $start_date = date("Y-m-d", strtotime($start));
        $end = explode(' - ',$date_arr)[1];
        $end_date = date("Y-m-d", strtotime($end));

        /* Get Filter Ticket */
        $dataFilter = $this->repository->GETFILTERTIKET($userid, $ticketno, $requestor, $assignto, $status, $start_date, $end_date, $roleid);
        // return $dataFilter;
        $json = json_decode($dataFilter, true);
        
        $dat = '';

        if($json["rc"] == "00") 
        {   
            $dataTrim = $json["data"]['data'];
            $dataTrimArray = [];
            
            foreach ($dataTrim as $key => $value) {
                array_push($dataTrimArray, [
                    "ticketno" => trim($value['ticketno']),
                    "userid" => trim($value['userid']),
                    "requestor" => trim($value['requestor']),
                    "categoryid" => trim($value['categoryid']),
                    "category" => trim($value['category']),
                    "subject" => trim($value['subject']),
                    "attachment" => trim($value['attachment']),
                    "statusid" => trim($value['statusid']),
                    "status" => trim($value['status']),
                    "priorid" => trim($value['priorid']),
                    "priority" => trim($value['priority']),
                    "detail" => trim($value['detail']),
                    "assignedto" => trim($value['assignedto']),
                    "assigned_to" => trim($value['assigned_to']),
                    "createdon" => trim($value['createdon']),
                    "departmentid" => trim($value['departmentid']),
                    "approvedby_1" => trim($value['approvedby_1']),
                    "approvedby_2" => trim($value['approvedby_2']),
                    "approvedby_3" => trim($value['approvedby_3']),
                    "approvedby_it" => trim($value['approvedby_it']),
                    "rejectedby" => trim($value['rejectedby']),
                    "approvedby1_date" => trim($value['approvedby1_date']),
                    "approvedby2_date" => trim($value['approvedby2_date']),
                    "approvedby3_date" => trim($value['approvedby3_date']),
                    "approvedbyit_date" => trim($value['approvedbyit_date']),
                    "createdby" => trim($value['createdby']),
                ]);
            }
            $data['dat'] = $dataTrimArray;

        } else {
            $data = [];
        }   
        $resp = json_encode($data);
    
        return DataTables::of($data['dat'])
            ->addColumn('action', function($row){
                $userid = Session::get('userid');
                $roleid = Session::get('roleid');
                $mgrid = Session::get('mgrid');
                $parentBtn = '<button href="javascript:void(0)" class="view btn btn-success" data-ticket="'.$row["ticketno"].'" data-id="'.$row["userid"].'" data-statusid="'.$row["statusid"].'"
                data-requestor="'.$row["requestor"].'" data-status="'.$row["status"].'" data-category="'.$row["category"].'" data-priority="'.$row["priority"].'" data-subject="'.$row["subject"].'" 
                data-detail="'.$row["detail"].'" data-assignto="'.$row["assigned_to"].'" data-created="'.$row["createdby"].'" data-approve="'.$row["approvedby_1"].'">View </button>';
            
                $approveMgrBtn = ' <button href="javascript:void(0)" class="update btn btn-default" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$row["assignedto"].'"
                data-approvedby1="'.$row["approvedby_1"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'" data-approvedby1_date ="'.$row["approvedby1_date"].'">Approve</button>';

                $approveBtn = ' <button href="javascript:void(0)" class="update btn btn-default" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$row["assignedto"].'"
                data-approvedbyit="'.$row["approvedby_it"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'" data-approvedbyit_date ="'.$row["approvedbyit_date"].'">Approve</button>';

                $rejectBtn = ' <button href="javascript:void(0)" class="reject btn btn-danger" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$userid.'"
                data-approvedby1="'.$row["approvedby_it"].'" data-approvedbyit="'.$row["approvedby_it"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'">Reject</button>';
                
                // $superAdminBtn = $parentBtn. $approveBtn. $rejectBtn;
                if($row["categoryid"] == 'CD001' && $row["statusid"] == 'SD006' && $row["assignedto"] == '' ){
                    $itBtn = $parentBtn.' <button href="javascript:void(0)" class="update btn btn-info" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-assignto="'.$row["assignedto"].'"
                    data-approvedby1="'.$row["approvedby_1"].'" data-approvedbyit="'.$mgrid.'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" >Assign To me</button>';
                    $managerBtn = $parentBtn;
                    $managerItBtn = $parentBtn. $approveBtn. $rejectBtn;
                } else  if($row["statusid"] == 'SD002' && $userid == $row["assignedto"]){
                    $itBtn = $parentBtn.' <button href="javascript:void(0)" class="closed btn btn-danger" data-status="'.$row["status"].'" data-statusid="SD003" data-status="'.$row["status"].'" data-assignto="'.$userid.'"
                    data-approvedby1="'.$row["approvedby_1"].'" data-approvedbyit="'.$mgrid.'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$userid.'">Closed</button>';
                    $managerBtn = $parentBtn;
                    $managerItBtn = $parentBtn;
                } else if($row["approvedby_1"] == null && $row["statusid"] == 'SD001' && $userid == $row["assignedto"]){
                    $managerBtn = $parentBtn. $approveMgrBtn. $rejectBtn;
                    $itBtn = $parentBtn;
                    $managerItBtn = $parentBtn;
                } else if($row["approvedby_1"] != null && $row["statusid"] == 'SD001' && $userid == $row["assignedto"] ){
                    $managerItBtn = $parentBtn. $approveBtn. $rejectBtn;
                    $itBtn = $parentBtn;
                    $managerBtn = $parentBtn;
                } else {
                    $itBtn = $parentBtn;
                    $managerBtn = $parentBtn;
                    $managerItBtn = $parentBtn;
                }
                
                if($roleid == 'RD004' || $roleid == 'RD005'){
                    return $itBtn;
                }
                if($roleid == 'RD002'){ 
                    return $managerBtn;
                }
                if($roleid == 'RD006'){
                    return $managerItBtn;
                }
                if($roleid == 'RD003'){
                    return $parentBtn;
                }
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->setTotalRecords($json["total"])
            ->setFilteredRecords($json["total"])
            ->make(true);
    }

    public function addTiket(Request $request)
    {
        
        $userid = Session::get('userid');
        $roleid = Session::get('roleid');
        $spvid = Session::get('spvid');
        $mgrid = Session::get('mgrid');
        $departmentid = Session::get('departmentid');
        $createdby = Session::get('userid');

        $createdon = date('Y-m-d');
        $userreq = $request->user;
        $category = $request->category;
        $priority = $request->priority;
        $priorityName = $request->priorityname;
        $subject = $request->subject;
        $remark = $request->detail;
        $assignto = $request->assignto;
    
        // foreach ($files as $key => $file) {
        //     File::create($file);
        // }
        /* End */

        /* Generate Ticket Number */ 
        $year = date("Y");
        $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT001')->where('period', $year)->get();
        $prefix = $dataPrefix[0]->prefix;
        $period = $dataPrefix[0]->period;
        $start_numb = $dataPrefix[0]->start_number;
        $end_numb = $dataPrefix[0]->end_number;
        $last = $dataPrefix[0]->last_number;
        /* Session Data */
        $session = array(
            'last_number' => $last
        );
        /* Set User Session */
        Session::put('last_number', $last);
        $lastSession = Session::get('last_number');
        if ($start_numb <= $end_numb && $last == $lastSession){
            $last_numb =  str_pad($dataPrefix[0]->last_number + 1, 4, "00", STR_PAD_LEFT);

        } else 
            $last_numb = '0000';
        
        $ticketno = $prefix. $period. $last_numb;
        /* End */

        /* Get File Upload */
        $upload = array();
        if (!empty($request->file('files'))){
            $doc = $request->file('files');
            $path = Storage::putFileAs("public/uploads/".$userid."/".$ticketno, new File($doc), $ticketno."_".date('Y-m-d').".".$doc->getClientOriginalExtension());
            $path = explode("/", $path);
            $path[0] = "storage";
            array_push($upload, join("/",$path));

            // $file = $request->file('files');
            // $file_name = $ticketno.'_'.date('Y-m-d').'.'.$file->extension();  
            // $file->move(public_path('uploads'), $file_name);
            // $upload[]= $file_name;
        } else {
            $upload = [''];
        }
        /* Validasi Approve manager by user login */
        $dataApprove = $this->repository->GETAPPROVEBYDEPARTMENT($departmentid, $userid);
        $mgridApprove = $dataApprove['data'][0]['mgrid'];
        $userApprove = $dataApprove['data'][0]['userid'];

        $dataMgrIt = DB::connection('pgsql')->table('master_data.m_user')->where('roleid', 'RD006')->get();
        $mgrIt = $dataMgrIt[0]->userid;
        if($roleid == 'RD002'){
            $assign = $mgrIt;
            $approvedby_1 = $userid;
            $approvedby_it = '';
            $auth = true;
        } else if ($roleid == 'RD006'){
            $assign = $request->assignto;
            $approvedby_1 = $userid;
            $approvedby_it = $userid;
            $auth = true;
        } else if($roleid == 'RD004' || $roleid == 'RD005') {
            $assign = $userid;
            $approvedby_1 = '';
            $approvedby_it = $mgrid;
            $auth = true;
        } else if($category == 'CD001'){
            $assign = '';
            $approvedby_1 = '';
            $approvedby_it = '';
            $auth = true;
        } else {
            $assign = $mgrid;
            $approvedby_1 = '';
            $approvedby_it = '';
            $auth = true;
        }
        /* End */

        /* Validasi Category Incident */
        $dataCategory = DB::connection('pgsql')->table('master_data.m_category')->where('categoryid', $category)->get();
        $flaggingCat =  $dataCategory[0]->approval;
        $cateName =  $dataCategory[0]->description;
        $cateId =  $dataCategory[0]->categoryid;
        if ($flaggingCat == 'X' ){
            if ($roleid == 'RD002'){
                $status = 'WAITING FOR APPROVAL';
                $statusid = 'SD001';
            } else if ($roleid == 'RD003'){
                $status = 'WAITING FOR APPROVAL';
                $statusid = 'SD001';
                $auth = true;
            } else if($roleid == 'RD004' || $roleid == 'RD005') {
                $status = 'IN PROGRESS';
                $statusid = 'SD002';
                $auth = true;
            } else {
                $status = 'IN PROGRESS';
                $statusid = 'SD002';
                $auth = true;
            }
        } else if($roleid == 'RD004' || $roleid == 'RD005') {
            $status = 'IN PROGRESS';
            $statusid = 'SD002';
            $auth = true;
        } else {
            $status = 'OPEN';
            $statusid = 'SD006';
            $auth = true;
        }
       
         /* Get User Email */ 
        if($cateId == "CD001"){
            $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrIt)->get();
            $emailSign = $dataEmail[0]->usermail;
            $assignNameSign = $dataEmail[0]->username;
            $emailReq = 'blank@lionwings.com';
            $emailApprove1 = 'it@lionwings.com';
            $auth = true;
        } else if ($roleid == "RD002"){
            $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('roleid', 'RD006')->get();
            $emailSign = $dataEmail[0]->usermail;
            $assignNameSign = $dataEmail[0]->username;
            $emailReq = 'blank@lionwings.com';
            $emailApprove1 = 'blank@lionwings.com';
            $auth = true;
        } else if ($roleid == "RD003"){
            $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrid)->get();
            $emailSign = $dataEmail[0]->usermail;
            $assignNameSign = $dataEmail[0]->username;
            $emailReq = 'blank@lionwings.com';
            $emailApprove1 = 'blank@lionwings.com';
            $auth = true;
        } else {
            $dataEmail = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->get();
            $emailSign = $dataEmail[0]->usermail;
            $assignNameSign = $dataEmail[0]->username;
            $emailReq = 'blank@lionwings.com';
            $emailApprove1 = 'blank@lionwings.com';
            $auth = true;
        }

        /* End */

        if ($auth){
            /* Insert Ticket */ 
            $addTicket = $this->repository->ADDTIKET($ticketno, $userreq, $category, $userid, $subject, $assign, $statusid, $createdon, $approvedby_1, $approvedby_it, $priority, $remark, $createdby, $departmentid, $upload, $roleid);
            /* Update Counter Prefix */
            $updateCounter = $this->repository->UPDATECOUNTER($last);
            /* Send Email */
            $SendMail = $this->mail->SENDMAIL($ticketno, $category, $cateName, $priority, $priorityName, $subject, $remark, $status, $statusid, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1);
        }
    
        return redirect()->route('tiket')->with("success", "successfully");

    }

    public function updateTiket(Request $request)
    {   

        $userid = Session::get('userid');
        $roleid = Session::get('roleid');
        $mgrid = Session::get('mgrid');
        $ticketno = $request->ticketno;
        $assignto = $request->assignto;
        $assign = $request->assignto;
        $approvedby1 = $request->approvedby1;
        $approveby_it = $request->approvedbyit;
        $rejectedby = $request->rejectedby;
        $statusid = $request->statusid;
        $status = $request->status;
        $approveby_1_date = $request->approvedby1_date;
        $approveby_it_date = $request->approvedbyit_date;
        
        /* Get Data Ticket */
        $dataTicketapprove = $this->repository->GETTICKETAPPROVE($userid, $ticketno, $roleid);
        $json = json_decode($dataTicketapprove, true);
        $requestor = $json['data'][0]['userid'];
        $approve1 = $json['data'][0]['approvedby_1'];
        $approveit = $json['data'][0]['approvedby_it'];
        $category = $json['data'][0]['categoryid'];
        $cateName= $json['data'][0]['category'];
        $priority = $json['data'][0]['priorid'];
        $priorityName = $json['data'][0]['priority'];
        $subject = $json['data'][0]['subject'];
        // $status = $json['data'][0]['status'];
        $remark = $json['data'][0]['detail'];
        $mgrApp = $json['data'][0]['mgrid'];
        
        /* Get User Email */ 
        if($category == 'CD001 '){//ketika kategori incindent
            if(!empty($mgrid)){
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->get();
                $emailSign = $dataEmailSign[0]->usermail;
                $assignNameSign = $dataEmailSign[0]->username;
            } else {
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $approveit)->get();
                $emailSign = $dataEmailSign[0]->usermail;
                $assignNameSign = $dataEmailSign[0]->username;
            }
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            /* Get Email Approve 1 */
            $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrApp)->get();
            $emailApprove1 = $dataEmailApprove1[0]->usermail;
            $assignNameApprove1 = $dataEmailApprove1[0]->username;
        } else if($roleid == 'RD002'){ //ketika kategori incindent
            /* Get Email Signto */
            $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->get();
            $emailSign = $dataEmailSign[0]->usermail;
            $assignNameSign = $dataEmailSign[0]->username;
            $emailApprove1 = 'blank@lionwings.com';
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            $emailApprove1 = 'blank@lionwings.com';
        } else if($roleid == 'RD006'){ 
            /* Get Email Signto */
            $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->get();
            $emailSign = $dataEmailSign[0]->usermail;
            $assignNameSign = $dataEmailSign[0]->username;
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            /* Get Email Approve 1 */
            $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $approve1)->get();
            $emailApprove1 = $dataEmailApprove1[0]->usermail;
            $assignNameApprove1 = $dataEmailApprove1[0]->username;
        } else {
            /* Get Email Signto */
            $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrid)->get();
            $emailSign = $dataEmailSign[0]->usermail;
            $assignNameSign = $dataEmailSign[0]->username;
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            /* Get Email Approve 1 */
            $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $approve1)->get();
            $emailApprove1 = $dataEmailApprove1[0]->usermail;
            $assignNameApprove1 = $dataEmailApprove1[0]->username;
        }
        /* Update Ticket */
        $updateTicket = $this->repository->UPDATETICKET($userid, $ticketno, $assignto, $approvedby1, $approveby_it, $rejectedby, $statusid, $approveby_1_date, $approveby_it_date, $roleid);
        /* Send Mail */
        $SendMail = $this->mail->SENDMAIL($ticketno, $category, $cateName, $priority, $priorityName, $subject, $remark, $status, $statusid, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1); 

        return redirect()->route('tiket')->with("success", "successfully");
    }

    public function closedTiket(Request $request)
    {   

        $userid = Session::get('userid');
        $roleid = Session::get('roleid');
        $mgrid = Session::get('mgrid');
        $ticketno = $request->ticketno;
        $assignto = $request->assignto;
        $assign = $request->assignto;
        $approvedby1 = $request->approvedby1;
        $approveby_it = $request->approvedbyit;
        $rejectedby = $request->rejectedby;
        $statusid = $request->statusid;
        $status = $request->status;
        $approveby_1_date = $request->approvedby1_date;
        $approveby_it_date = $request->approvedbyit_date;
        $remark = $request->remark;
        
        /* Get Data Ticket */
        $dataTicketapprove = $this->repository->GETTICKETAPPROVE($userid, $ticketno, $roleid);
        $json = json_decode($dataTicketapprove, true);
        $requestor = $json['data'][0]['userid'];
        $approve1 = $json['data'][0]['approvedby_1'];
        $approveit = $json['data'][0]['approvedby_it'];
        $category = $json['data'][0]['categoryid'];
        $cateName= $json['data'][0]['category'];
        $priority = $json['data'][0]['priorid'];
        $priorityName = $json['data'][0]['priority'];
        $subject = $json['data'][0]['subject'];
        // $status = $json['data'][0]['status'];
        $mgrApp = $json['data'][0]['mgrid'];
        
        /* Get User Email */ 
        if($category == 'CD001 '){//ketika kategori incindent
            if(!empty($mgrid)){
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrid)->get();
                $emailSign = $dataEmailSign[0]->usermail;
                $assignNameSign = $dataEmailSign[0]->username;
            } else {
                /* Get Email Signto */
                $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $approveit)->get();
                $emailSign = $dataEmailSign[0]->usermail;
                $assignNameSign = $dataEmailSign[0]->username;
            }
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            /* Get Email Approve 1 */
            $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrApp)->get();
            $emailApprove1 = $dataEmailApprove1[0]->usermail;
            $assignNameApprove1 = $dataEmailApprove1[0]->username;
        } else if($roleid == 'RD002'){ //ketika kategori incindent
            /* Get Email Signto */
            $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->get();
            $emailSign = $dataEmailSign[0]->usermail;
            $assignNameSign = $dataEmailSign[0]->username;
            $emailApprove1 = 'blank@lionwings.com';
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            $emailApprove1 = 'blank@lionwings.com';
        } else if($roleid == 'RD006'){ 
            /* Get Email Signto */
            $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $assignto)->get();
            $emailSign = $dataEmailSign[0]->usermail;
            $assignNameSign = $dataEmailSign[0]->username;
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            /* Get Email Approve 1 */
            $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $approve1)->get();
            $emailApprove1 = $dataEmailApprove1[0]->usermail;
            $assignNameApprove1 = $dataEmailApprove1[0]->username;
        } else {
            /* Get Email Signto */
            $dataEmailSign = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $mgrid)->get();
            $emailSign = $dataEmailSign[0]->usermail;
            $assignNameSign = $dataEmailSign[0]->username;
            /* Get Email Requestor */
            $dataEmailReq = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $requestor)->get();
            $emailReq = $dataEmailReq[0]->usermail;
            $assignNameReq = $dataEmailReq[0]->username;
            /* Get Email Approve 1 */
            $dataEmailApprove1 = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $approve1)->get();
            $emailApprove1 = $dataEmailApprove1[0]->usermail;
            $assignNameApprove1 = $dataEmailApprove1[0]->username;
        }
        /* Update Ticket */
        $updateTicket = $this->repository->CLOSEDTICKET($ticketno, $assignto, $statusid, $remark);
        /* Send Mail */
        $SendMail = $this->mail->SENDMAIL($ticketno, $category, $cateName, $priority, $priorityName, $subject, $remark, $status, $statusid, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1); 
    
        return redirect()->route('tiket')->with("success", "successfully");
    }

    // public function downloadFile(Request $request)
    // {    
    //     $dataFile = DB::connection('pgsql')->table('helpdesk.t_discussion')->where('ticketno', $request->ticketno)->get();

    //     if (!empty($dataFile) || $dataFile == ""){
    //         $filepath = public_path()."/uploads/".$dataFile[0];
            
    //         $headers = array(
    //             'Content-Type: application/pdf',
    //         );

    //         return response()->download($filepath, $dataFile[0], $headers);
    //     } else {
    //         return back()->withErrors([
    //             'File' => 'File Not Found',
    //         ]);
    //     } 
    // }
}
