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
        $dateNow = date('Y-m-d');
        $opn = '';
        $clsd = '';
        $prg = '';

        $ticketOpen = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            ->where('createdon', '>', now()->subDays(7)->endOfDay())
            ->orWhere('statusid', 'SD006')
            ->count();
            if(!empty($ticketOpen)){
                $dataResp = $ticketOpen;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['opn'] = $dataRespArray; 

            } else {
                return response()->json([
                    'message'=>'error1'
                ], 400);
            }
        $ticketProgress = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            ->where('createdon', '>', now()->subDays(7)->endOfDay())
            ->orWhere('statusid', 'SD002')
            ->count();
            if(!empty($ticketProgress)) {
                $dataResp = $ticketProgress;
                $dataRespArray = [];
                array_push($dataRespArray, $dataResp);
                $data['prg'] = $dataRespArray; 
            } else {
                return response()->json([
                    'message'=>'error2'
                ], 400);
            }
        $ticketClosed = DB::connection('pgsql')->table('helpdesk.t_ticket as a')
            ->where('createdon', '>', now()->subDays(7)->endOfDay())
            ->orWhere('statusid', 'SD003')
            ->count();
            if(!empty($ticketClosed)) {
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
