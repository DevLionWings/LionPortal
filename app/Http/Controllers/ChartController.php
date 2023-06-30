<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public static function getStatToday()
    {
        $opn = '';
        $clsd = '';
        $prg = '';

        $date = \Carbon\Carbon::today()->subDays(7);
        $dateweek = date('Y-m-d', strtotime($date));
        $datenow = date('Y-m-d');
        $ticketOpen = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD006')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
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
        
        return $data;
    }

    public static function getDataTicketingMonth()
    {
    
        $opn = '';
        $clsd = '';
        $prg = '';

        $date = \Carbon\Carbon::today()->subDays(30);
        $dateweek = date('Y-m-d', strtotime($date));
        $datenow = date('Y-m-d');
        $ticketOpen = DB::connection('pgsql')->table('helpdesk.t_ticket')
            ->where('statusid', 'SD006')
            ->whereBetween(DB::raw('DATE(createdon)'), [$dateweek, $datenow])
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
