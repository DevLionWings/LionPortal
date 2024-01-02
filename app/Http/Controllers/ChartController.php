<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Helpers\Repository;
use App\Models\Tiket;
use App\Models\Tiketdiscussion;
use App\Models\Tiketpriority;
use App\Models\Tiketstatus;
use Carbon\CarbonPeriod;

class ChartController extends Controller
{   
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getContainerCount()
    {
        return 20;
    }

    public static function getStatToday(Request $request)
    {
        $usr = '';
        $clsd = '';
        $prg = '';
        $trans = '';
        $notstr = '';
        $apprv = '';
        $hold = '';
        $purchs = '';
        $mntr = '';
        $systemid = $request->systemid;
        
        $date = \Carbon\Carbon::today()->subDays(7);
        $dateweek = date('Y-m-d', strtotime($date));
        $datenow = date('Y-m-d');
        $ticketForuser = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD008')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketForuser != ''){
                $dataResp = $ticketForuser;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['usr'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error1'
                ], 400);
            }
 
        $ticketProgress = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD002')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketProgress != "") {
                $dataResp = $ticketProgress;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['prg'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error2'
                ], 400);
            }
        $ticketClosed = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD003')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketClosed != "") {
                $dataResp = $ticketClosed;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['clsd'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketTransport = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD010')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketTransport != "") {
                $dataResp = $ticketTransport;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['trans'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketNotstarted = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD007')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketNotstarted != "") {
                $dataResp = $ticketNotstarted;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['notstr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketApproval = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD001')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketApproval != "") {
                $dataResp = $ticketApproval;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['apprv'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketHold = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD004')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketHold != "") {
                $dataResp = $ticketHold;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['hold'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketPurchase = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD013')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketPurchase != "") {
                $dataResp = $ticketPurchase;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['purchs'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketMonitoring = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD009')
            ->where('systemid', 'LIKE','%'.$systemid.'%')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketMonitoring != "") {
                $dataResp = $ticketMonitoring;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['mntr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        
        return $data;
    }

    public static function getDataTicketingAll()
    {
    
        $usr = '';
        $clsd = '';
        $prg = '';
        $trans = '';
        $notstr = '';
        $apprv = '';
        $hold = '';
        $purchs = '';
        $mntr = '';

        $ticketForuser = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD008')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketForuser != ''){
                $dataResp = $ticketForuser;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['usr'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error1'
                ], 400);
            }

        $ticketProgress = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD002')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketProgress != "") {
                $dataResp = $ticketProgress;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['prg'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error2'
                ], 400);
            }
        $ticketClosed = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD003')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketClosed != "") {
                $dataResp = $ticketClosed;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['clsd'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketTransport = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD010')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketTransport != "") {
                $dataResp = $ticketTransport;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['trans'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketNotstarted = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD007')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketNotstarted != "") {
                $dataResp = $ticketNotstarted;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['notstr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketApproval = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD001')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketApproval != "") {
                $dataResp = $ticketApproval;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['apprv'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketHold = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD004')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketHold != "") {
                $dataResp = $ticketHold;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['hold'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketPurchase = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD013')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketPurchase != "") {
                $dataResp = $ticketPurchase;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['purchs'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketMonitoring = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD009')
            // ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketMonitoring != "") {
                $dataResp = $ticketMonitoring;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['mntr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        
        return $data;
    }

    public static function getDataTicketingMonth()
    {
    
        $usr = '';
        $clsd = '';
        $prg = '';
        $trans = '';
        $notstr = '';
        $apprv = '';
        $hold = '';
        $purchs = '';
        $mntr = '';

        $date = \Carbon\Carbon::today()->subDays(30);
        $dateweek = date('Y-m-d', strtotime($date));
        $datenow = date('Y-m-d');
        $ticketForuser = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD008')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketForuser != ''){
                $dataResp = $ticketForuser;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['usr'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error1'
                ], 400);
            }

        $ticketProgress = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD002')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketProgress != "") {
                $dataResp = $ticketProgress;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['prg'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error2'
                ], 400);
            }
        $ticketClosed = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD003')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketClosed != "") {
                $dataResp = $ticketClosed;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['clsd'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketTransport = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD010')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketTransport != "") {
                $dataResp = $ticketTransport;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['trans'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketNotstarted = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD007')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketNotstarted != "") {
                $dataResp = $ticketNotstarted;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['notstr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketApproval = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD001')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketApproval != "") {
                $dataResp = $ticketApproval;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['apprv'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketHold = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD004')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketHold != "") {
                $dataResp = $ticketHold;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['hold'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketPurchase = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD013')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketPurchase != "") {
                $dataResp = $ticketPurchase;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['purchs'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketMonitoring = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD009')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
            ->count();
            if($ticketMonitoring != "") {
                $dataResp = $ticketMonitoring;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['mntr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        
        return $data;
    }

    public static function getDataTicketingToday()
    {
    
        $usr = '';
        $clsd = '';
        $prg = '';
        $trans = '';
        $notstr = '';
        $apprv = '';
        $hold = '';
        $purchs = '';
        $mntr = '';

        $datenow = date('Y-m-d');
        $ticketForuser = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD008')
            ->where('createdon', $datenow)
            ->count();
            if($ticketForuser != ''){
                $dataResp = $ticketForuser;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['usr'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error1'
                ], 400);
            }

        $ticketProgress = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD002')
            ->where('createdon', $datenow)
            ->count();
            if($ticketProgress != "") {
                $dataResp = $ticketProgress;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['prg'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error2'
                ], 400);
            }
        $ticketClosed = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD003')
            ->where('createdon', $datenow)
            ->count();
            if($ticketClosed != "") {
                $dataResp = $ticketClosed;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['clsd'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketTransport = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD010')
            ->where('createdon', $datenow)
            ->count();
            if($ticketTransport != "") {
                $dataResp = $ticketTransport;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['trans'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketNotstarted = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD007')
            ->where('createdon', $datenow)
            ->count();
            if($ticketNotstarted != "") {
                $dataResp = $ticketNotstarted;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['notstr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketApproval = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD001')
            ->where('createdon', $datenow)
            ->count();
            if($ticketApproval != "") {
                $dataResp = $ticketApproval;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['apprv'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketHold = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD004')
            ->where('createdon', $datenow)
            ->count();
            if($ticketHold != "") {
                $dataResp = $ticketHold;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['hold'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketPurchase = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD013')
            ->where('createdon', $datenow)
            ->count();
            if($ticketPurchase != "") {
                $dataResp = $ticketPurchase;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['purchs'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        $ticketMonitoring = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD009')
            ->where('createdon', $datenow)
            ->count();
            if($ticketMonitoring != "") {
                $dataResp = $ticketMonitoring;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['mntr'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        
        return $data;
    }

    public static function getDataTicketingYear()
    {
    
        $opn = '';
        $clsd = '';
        $prg = '';
        $date = '';

        // $date = \Carbon\Carbon::today()->subDays(365);
        $dateweek = date('Y-m-d', strtotime($date));
        $datenow = date('Y-m-d');
        $dateMin365 = date('Y-m-d', strtotime("-365 days"));

        $period = CarbonPeriod::create($dateMin365, $datenow);
        // Iterate over the period
        $dates = [];
        foreach ($period as $date) {
            $dates[] = date('Y-m-d', strtotime($date));
        }
        // Convert the period to an array of dates
        // $dataRespArray = $period->toArray();
        $data['date'] = $dates;

        $ticketOpen = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD006')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateMin365, $datenow])
            ->count();
            if($ticketOpen != ''){
                $dataResp = $ticketOpen;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['opn'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error1'
                ], 400);
            }
        
        $ticketProgress = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD002')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateMin365, $datenow])
            ->count();
            if($ticketProgress != "") {
                $dataResp = $ticketProgress;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['prg'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error2'
                ], 400);
            }
            
        $ticketClosed = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD003')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateMin365, $datenow])
            ->count();
            if($ticketClosed != "") {
                $dataResp = $ticketClosed;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['clsd'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error3'
                ], 400);
            }
        
        return $data;
    }

}
