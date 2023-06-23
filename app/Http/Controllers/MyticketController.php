<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\DB;
use App\Helpers\Mail;
use App\Helpers\Response;
use App\Helpers\Repository;
use App\Models\Counter;
use DataTables;
Use Redirect;

class MyticketController extends Controller
{
    public function __construct(Repository $repository, Response $response, Mail $mail)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->mail = $mail;
    }

    public function myTiket(Request $request)
    {
        $usreq = '';
        $categ = '';
        $prior = '';
        $assn = '';
        $stat = '';
        $tick = '';

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
        //test
        return view('fitur.mytiket', $data);
    }

    public function myTiketList(Request $request)
    {
        $userid = Session::get('userid');
        $roleid = Session::get('roleid');

        /* Get Data Ticket */
        $dataTicket = $this->repository->GETMYTICKET($userid, $roleid);
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
                ]);
            }
            $data['dat'] = $dataTrimArray;
            
            $statusid = $data['dat'][0]['statusid'];
            $assignedto = $data['dat'][0]['assignedto'];

            /* Session Data */
            $session = array(
                'statusid' => $statusid,
                'assignedto' => $assignedto,
            );
            /* Set User Session */
            Session::put('statusid', $statusid);
            Session::put('assignedto', $assignedto);

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
                data-detail="'.$row["detail"].'" data-assignto="'.$row["assigned_to"].'" data-created="'.$row["createdby"].'" data-approve="'.$row["approvedby_1"].'" data-upload="'.$row["attachment"].'">View </button>';
            
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
                    $managerItBtn = $parentBtn;
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
            ->setTotalRecords($json["total"])
            ->setFilteredRecords($json["total"])
            ->make(true);
    }

}
