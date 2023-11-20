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
use App\Helpers\Validate;
use App\Models\Counter;
use DataTables;
Use Redirect;

class MyticketController extends Controller
{
    public function __construct(Repository $repository, Response $response, Mail $mail, Validate $validate)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->mail = $mail;
        $this->validate = $validate;
    }

    public function myTiket(Request $request)
    {
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        }


        $usreq = '';
        $categ = '';
        $prior = '';
        $assn = '';
        $stat = '';
        $tick = '';
        $trq = '';

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
        
        $dataSystem = DB::connection('pgsql')->table('master_data.m_system')->get();
        $dataTrimArray = [];
        foreach ($dataSystem as $key => $value) {
            array_push($dataTrimArray, [
                "NAME" => trim($value->description),
                "ID" => trim($value->systemid)
            ]);
        }
        $data['sys'] = $dataTrimArray; 

        $dataModule = DB::connection('pgsql')->table('master_data.m_module')->get();
        $dataTrimArray = [];
        foreach ($dataModule as $key => $value) {
            array_push($dataTrimArray, [
                "NAME" => trim($value->description),
                "ID" => trim($value->moduleid)
            ]);
        }
        $data['mdl'] = $dataTrimArray;
        
        $dataObject = DB::connection('pgsql')->table('master_data.m_object_type')->get();
        $dataTrimArray = [];
        foreach ($dataObject as $key => $value) {
            array_push($dataTrimArray, [
                "NAME" => trim($value->description),
                "ID" => trim($value->objectid)
            ]);
        }
        $data['obj'] = $dataTrimArray; 

        $dataTransport = DB::connection('pgsql')->table('helpdesk.t_transport')->get();
        $dataTrimArray = [];
        foreach ($dataTransport as $key => $value) {
            array_push($dataTrimArray, [
                "ID" => trim($value->transportid)
            ]);
        }
        $data['trq'] = $dataTrimArray; 
        
        return view('fitur.mytiket', $data);
    }

    public function myTiketList(Request $request)
    {
        $userid = Session::get('userid');
        $roleid = Session::get('roleid');

        /* Get Data Ticket */
        $dataTicket = $this->repository->GETMYTICKET($userid);
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
                    "targetdate" => trim($value['target_date']),
                    "approvedby1Name" => trim($value['approved1']),
                    "approvedbyitName" => trim($value['approvedit']),
                    "createdname" => trim($value['created']),
                    "systemid" => trim($value['systemid']),
                    "moduleid" => trim($value['moduleid']),
                    "modulename" => trim($value['modulename']),
                    "objectid" => trim($value['objectid']),
                    "objectname" => trim($value['objectname']),
                    "systemname" => trim($value['systemname']),
                ]);
            }
            $data['dat'] = $dataTrimArray;

        } else {
            $data = []; 
            $json = ["total" => 0];
        }   
        $resp = json_encode($data);
        
        return DataTables::of($data['dat'])
            ->addColumn('action', function($row){
                $dataTransport = DB::connection('pgsql')->table('helpdesk.t_transport')->where('ticketid', $row["ticketno"])->get();
                $dataTransArray = [];

                foreach ($dataTransport as $key => $value1) {
                    array_push($dataTransArray, [
                        "transportid" => trim($value1->transportid),
                        "transportno" => trim($value1->transportno),
                        "sendto_lqa" => trim($value1->sendto_lqa),
                        "sendto_lpr" => trim($value1->sendto_lpr),
                        "approveby_lqa" => trim($value1->approveby_lqa), 
                        "approveby_lqa_date" => trim($value1->approveby_lqa_date),
                        "approveby_lpr" => trim($value1->approveby_lpr),
                        "approveby_lpr_date" => trim($value1->approveby_lpr_date),
                        "status_lqa" => trim($value1->status_lqa),
                        "status_lpr" => trim($value1->status_lpr),
                        "transportby_lqa" => trim($value1->transportby_lqa),
                        "date_trans_lqa" => trim($value1->date_trans_lqa),
                        "transportby_lpr" => trim($value1->transportby_lpr),
                        "date_trans_lpr" => trim($value1->date_trans_lpr),
                        "status_trans_lqa" => trim($value1->status_trans_lqa),
                        "status_trans_lpr" => trim($value1->status_trans_lpr),
                        "createdTrans" => trim($value1->createdon),
                    ]);
                }
         
                $userid = Session::get('userid');
                $roleid = Session::get('roleid');
                $mgrid = Session::get('mgrid');
                $document_name = str_replace("storage/", "", $row["attachment"]);
                $parentBtn = ' <a href="javascript:void(0)" class="view btn btn-outline-info btn-xs" data-ticket="'.$row["ticketno"].'" data-id="'.$row["userid"].'" data-statusid="'.$row["statusid"].'"
                data-requestor="'.$row["requestor"].'" data-status="'.$row["status"].'" data-category="'.$row["category"].'" data-priority="'.$row["priority"].'" data-priorid="'.$row["priorid"].'" data-subject="'.$row["subject"].'" 
                data-detail="'.$row["detail"].'" data-assignto="'.$row["assigned_to"].'" data-created="'.$row["createdby"].'" data-approve="'.$row["approvedby_1"].'" data-upload="'.$document_name.'" 
                data-approve1name="'.$row["approvedby1Name"].'" data-approveitname="'.$row["approvedbyitName"].'" data-createdname="'.$row["createdname"].'" data-targetdate="'.$row["targetdate"].'" 
                data-approvedby1="'.$row["approvedby1_date"].'" data-approvedbyit="'.$row["approvedbyit_date"].'" data-systemid="'.$row["systemid"].'" data-systemname="'.$row["systemname"].'" data-moduleid="'.$row["moduleid"].'" 
                data-objectid="'.$row["objectid"].'"  data-objectname="'.$row["objectname"].'" data-createdon="'.$row["createdon"].'"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                $updateBtn = ' <a href="javascript:void(0)" class="update btn btn-outline-success btn-xs" data-ticket="'.$row["ticketno"].'" data-id="'.$row["userid"].'" data-statusid="'.$row["statusid"].'"
                data-requestor="'.$row["requestor"].'" data-status="'.$row["status"].'" data-category="'.$row["category"].'" data-categoryid="'.$row["categoryid"].'" data-priority="'.$row["priority"].'" data-priorid="'.$row["priorid"].'" data-subject="'.$row["subject"].'" 
                data-detail="'.$row["detail"].'" data-assignto="'.$row["assigned_to"].'"  data-assignedto="'.$row["assignedto"].'" data-created="'.$row["createdby"].'" data-approve="'.$row["approvedby_1"].'" data-upload="'.$document_name.'" 
                data-approve1name="'.$row["approvedby1Name"].'" data-approveitname="'.$row["approvedbyitName"].'" data-createdname="'.$row["createdname"].'" data-targetdate="'.$row["targetdate"].'" 
                data-approvedby1="'.$row["approvedby1_date"].'" data-approvedbyit="'.$row["approvedbyit_date"].'" data-systemid="'.$row["systemid"].'" data-systemname="'.$row["systemname"].'" data-moduleid="'.$row["moduleid"].'" 
                data-objectid="'.$row["objectid"].'"  data-createdon="'.$row["createdon"].'"><i class="fas fa-edit"></i></a>';

                $transportBtn = ' <a href="javascript:void(0)" class="trans btn btn-outline-dark btn-xs" data-ticket="'.$row["ticketno"].'" data-id="'.$row["userid"].'" data-statusid="'.$row["statusid"].'"
                data-requestor="'.$row["requestor"].'" data-status="'.$row["status"].'" data-category="'.$row["category"].'" data-categoryid="'.$row["categoryid"].'" data-priority="'.$row["priority"].'" data-subject="'.$row["subject"].'" 
                data-detail="'.$row["detail"].'" data-assignto="'.$row["assigned_to"].'"  data-assignedto="'.$row["assignedto"].'" data-created="'.$row["createdby"].'" data-approve="'.$row["approvedby_1"].'" data-upload="'.$document_name.'" 
                data-approve1name="'.$row["approvedby1Name"].'" data-approveitname="'.$row["approvedbyitName"].'" data-createdname="'.$row["createdname"].'" data-targetdate="'.$row["targetdate"].'" 
                data-approvedby1="'.$row["approvedby1_date"].'" data-approvedbyit="'.$row["approvedbyit_date"].'" data-systemid="'.$row["systemid"].'" data-systemname="'.$row["systemname"].'" data-moduleid="'.$row["moduleid"].'" 
                data-objectid="'.$row["objectid"].'"  data-createdon="'.$row["createdon"].'"><i class="fa fa-truck" aria-hidden="true"></i></a>';
    
                $viewTransBtn = ' <button href="javascript:void(0)" class="viewtrans btn btn-outline-dark btn-xs" data-ticket="'.$row["ticketno"].'" ><i class="fa fa-truck" aria-hidden="true"></i></button>';

                $download_btn = '<a  download="'.explode(";",$row["attachment"])[0].'" href="'.Storage::url(explode(";",$document_name)[0]).'" target="_blank" class="btn btn-outline-primary btn-xs" 
                style="margin-left: 5px"><i class="fa fa-download" aria-hidden="true"></i></a>';

                $approveMgrBtn = ' <button href="javascript:void(0)" class="update btn btn-outline-warning btn-xs" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$row["assignedto"].'"
                data-approvedby1="'.$row["approvedby_1"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'" data-approvedby1_date ="'.$row["approvedby1_date"].'">approve<i class="fa fa-ticket" aria-hidden="true"></i></button>';

                $approveBtn = ' <button href="javascript:void(0)" class="update btn btn-outline-success btn-xs" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$row["assignedto"].'"
                data-approvedbyit="'.$row["approvedby_it"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'" data-approvedbyit_date ="'.$row["approvedbyit_date"].'"><i class="fa fa-check" aria-hidden="true"></i></button>';

                $rejectBtn = ' <button href="javascript:void(0)" class="reject btn btn-outline-danger btn-xs" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-status="'.$row["status"].'" data-assignto="'.$userid.'"
                data-approvedby1="'.$row["approvedby_it"].'" data-approvedbyit="'.$row["approvedby_it"].'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$row["userid"].'"><i class="fa fa-ban" aria-hidden="true"></i></button>';

                $closedBtn = ' <button href="javascript:void(0)" class="closed btn btn-outline-danger btn-xs" data-status="'.$row["status"].'" data-statusid="SD003" data-status="'.$row["status"].'" data-assignto="'.$userid.'"
                data-approvedby1="'.$row["approvedby_1"].'" data-approvedbyit="'.$mgrid.'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" data-userid="'.$userid.'"><i class="fa fa-window-close" aria-hidden="true"></i></button>';
                
                $pickedBtn = ' <button href="javascript:void(0)" class="update btn btn-outline-warning btn-xs" data-status="'.$row["status"].'" data-statusid="'.$row["statusid"].'" data-assignto="'.$row["assignedto"].'"
                data-approvedby1="'.$row["approvedby_1"].'" data-approvedbyit="'.$mgrid.'" data-rejectedby="'.$row["rejectedby"].'" data-ticketno="'.$row["ticketno"].'" ><i class="fas fa-user-plus"></i></button>';
                
                if($row["categoryid"] == 'CD001' && $row["statusid"] == 'SD006' && $row["assignedto"] == '' ){
                    $itBtn = $download_btn. $pickedBtn;
                    $sapBtn = $download_btn. $pickedBtn;
                    $infBtn = $download_btn. $pickedBtn;
                    $headBtn = $updateBtn. $download_btn. $pickedBtn;
                    $managerBtn = $viewTransBtn. $download_btn;
                    $managerItBtn = $updateBtn. $download_btn. $approveBtn. $rejectBtn;
                } else  if($row["statusid"] == 'SD002' && $userid == $row["assignedto"]){
                    $itBtn = $download_btn. $updateBtn. $closedBtn;
                    $sapBtn = $transportBtn. $updateBtn. $download_btn. $closedBtn;
                    $infBtn = $download_btn. $updateBtn. $closedBtn;
                    $headBtn = $transportBtn. $updateBtn. $download_btn. $closedBtn;
                    $managerBtn = $viewTransBtn. $download_btn; 
                    $managerItBtn = $updateBtn. $download_btn. $closedBtn;
                } else if($row["approvedby_1"] == null && $row["statusid"] == 'SD001' && $userid == $row["assignedto"]){
                    $managerBtn = $download_btn. $approveMgrBtn. $rejectBtn;
                    $itBtn = $download_btn;
                    $infBtn = $download_btn;
                    $sapBtn = $download_btn;
                    $managerItBtn = $viewTransBtn. $updateBtn. $download_btn;
                    $headBtn = $updateBtn. $download_btn;
                } else if($row["approvedby_1"] != null && $row["statusid"] == 'SD001' && $userid == $row["assignedto"] ){
                    $managerItBtn = $updateBtn. $download_btn. $approveBtn. $rejectBtn;
                    $itBtn = $download_btn;
                    $infBtn = $download_btn;
                    $sapBtn = $download_btn;
                    $managerBtn = $viewTransBtn. $download_btn;
                    $headBtn = $updateBtn. $download_btn;
                } else if ($row["statusid"] == 'SD002'){
                    $itBtn = $download_btn;
                    $infBtn = $download_btn;
                    $sapBtn = $download_btn;
                    $managerBtn = $download_btn;
                    $managerItBtn = $viewTransBtn. $updateBtn. $download_btn. $closedBtn;
                    $headBtn = $updateBtn. $download_btn;
                } else {
                    $itBtn = $download_btn;
                    $infBtn = $download_btn;
                    $sapBtn = $download_btn;
                    $managerBtn = $download_btn;
                    $managerItBtn = $viewTransBtn. $updateBtn. $download_btn;
                    $headBtn = $updateBtn. $download_btn;
                }
                
                /* button transport & approve */
                foreach ($dataTransArray as $key => $value) {
                    $transportedBtn = ' <button href="javascript:void(0)" class="transted btn btn-outline-success btn-xs" data-ticket="'.$row["ticketno"].'" data-id="'.$row["userid"].'" data-transportid="'.$value['transportid'].'" data-transportno="'.$value['transportno'].'" 
                    data-status_lqa="'.$value['status_lqa'].'" data-status_lpr="'.$value['status_lpr'].'"><i class="fa fa-truck" aria-hidden="true"></i></button>';

                    $approveTransBtn = ' <button href="javascript:void(0)" class="approvetrans btn btn-outline-primary btn-xs" data-ticket="'.$row["ticketno"].'" data-transportid="'.$value['transportid'].'" data-transportno="'.$value['transportno'].'" 
                    data-sendto_lqa="'.$value['sendto_lqa'].'" data-sendto_lpr="'.$value['sendto_lpr'].'"><i class="fa fa-truck" aria-hidden="true"></i></button>';
              
                    if($row["statusid"] == 'SD002' && $userid == $row["assignedto"]){
                        if( $value['sendto_lqa'] == '1' && $value['status_lqa'] == '0' ){
                            $infBtn = $viewTransBtn. $download_btn. $closedBtn;
                            $managerItBtn = $approveTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else if( $value['sendto_lpr'] == '1' && $value['status_lpr'] == '0' ){
                            $infBtn = $viewTransBtn. $download_btn. $closedBtn;
                            $managerItBtn = $approveTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else if($value['status_lqa'] == '1' && $value['status_trans_lqa'] == '0' ){
                            $infBtn = $transportedBtn. $download_btn. $closedBtn;
                            $managerItBtn = $viewTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else if($value['status_lpr'] == '1' && $value['status_trans_lpr'] == '0'){
                            $infBtn = $transportedBtn. $download_btn. $closedBtn;
                            $managerItBtn = $viewTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else {
                            $infBtn = $viewTransBtn. $download_btn. $closedBtn;
                            $managerItBtn = $viewTransBtn. $updateBtn. $download_btn. $closedBtn;
                        }
                    } else {
                         if( $value['sendto_lqa'] == '1' && $value['status_lqa'] == '0' ){
                            $infBtn = $viewTransBtn. $download_btn;
                            $managerItBtn = $approveTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else if( $value['sendto_lpr'] == '1' && $value['status_lpr'] == '0' ){
                            $infBtn = $viewTransBtn. $download_btn;
                            $managerItBtn = $approveTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else if($value['status_lqa'] == '1' && $value['status_trans_lqa'] == '0' ){
                            $infBtn = $transportedBtn. $download_btn;
                            $managerItBtn = $viewTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else if($value['status_lpr'] == '1' && $value['status_trans_lpr'] == '0'){
                            $infBtn = $transportedBtn. $download_btn;
                            $managerItBtn = $viewTransBtn. $updateBtn. $download_btn. $closedBtn;
                        } else {
                            $infBtn = $viewTransBtn. $download_btn;
                            $managerItBtn = $viewTransBtn. $updateBtn. $download_btn. $closedBtn;
                        }
                    }
                }
                /* End */
              
                if($roleid == 'RD005'){
                    return $infBtn;
                }
                if($roleid == 'RD007'){
                    return $sapBtn;
                }
                if($roleid == 'RD004' || $roleid == 'RD008'){
                    return $itBtn;
                }
                if($roleid == 'RD009'){
                    return $headBtn;
                }
                if($roleid == 'RD002'){ 
                    return $managerBtn;
                }
                if($roleid == 'RD006' || $roleid == 'RD001'){
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

    public function closedTiket(Request $request)
    {   

        $userid = Session::get('userid');
        $roleid = Session::get('roleid');
        $mgrid = Session::get('mgrid');
        $ticketno = $request->ticketno;
        // $assignto = $request->assignto; #assign to mgrIT#
        $assign = $request->assignto;
        $approvedby1 = $request->approvedby1;
        $approveby_it = $request->approvedbyit;
        $rejectedby = $request->rejectedby;
        $statusid = $request->statusid;
        $status = $request->status;
        $approveby_1_date = $request->approvedby1_date;
        $approveby_it_date = $request->approvedbyit_date;
        $note = $request->remark;
    
        /* Get Data Ticket */
        $dataTicketapprove = $this->repository->GETTICKETAPPROVE($userid, $ticketno, $roleid);
        $json = json_decode($dataTicketapprove, true);
        $userreq = $json['data'][0]['userid'];
        $mgrUser = $json['data'][0]['approvedby_1'];
        $mgrIt = $json['data'][0]['approvedby_it'];
        $category = $json['data'][0]['categoryid'];
        $cateName= $json['data'][0]['category'];
        $priority = $json['data'][0]['priorid'];
        $priorityName = $json['data'][0]['priority'];
        $subject = $json['data'][0]['subject'];
        $remark = $json['data'][0]['detail'];
        $assignto = $json['data'][0]['assignedto'];
        // $status = $json['data'][0]['status'];
        $mgrApp = $json['data'][0]['mgrid'];
        $flag = 'CLS';

        /* Get User Email */ 
        $dataCLS = $this->validate->GETUSEREMAIL($flag, $userreq, $assignto, $mgrIt, $mgrUser, $userid, $category, $roleid);
        $emailSign = $dataCLS['emailSign'];
        $emailReq = $dataCLS['emailReq'];
        $assignNameSign = $dataCLS['assignNameSign'];
        $emailApprove1 = $dataCLS['emailApprove1'];
        $emailApproveit =  $dataCLS['emailApproveit'];
        /* End */
        
        /* Update Ticket */
        $updateTicket = $this->repository->CLOSEDTICKET($ticketno, $assignto, $statusid, $note);
        /* Send Mail */
        $SendMail = $this->mail->SENDMAIL($ticketno, $category, $cateName, $priority, $priorityName, $subject, $remark, $note, $status, $statusid, $assign, $assignNameSign, $emailSign, $emailReq, $emailApprove1, $flag); 
    
        return redirect()->route('mytiket')->with("success", "successfully");
    }


}
