<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use DataTables;

class AbsensiController extends Controller
{
    public function __construct(Repository $repository, Response $response)
    {
        $this->repository = $repository;
        $this->response = $response;
    }

    public function absensi(Request $request)
    {
        // $isLogin = Session::get('status_login');
        // if($isLogin != 1) {
        //     return redirect()->route('login-page');
        // }

        $divisionid = Session::get('divisionid');
        $team = '';
        $depart = '';

        $dataTeam = DB::connection('pgsql')->table('master_data.m_user')->where('divisionid', $divisionid)->get();
        $dataTrimArray = [];
        foreach ($dataTeam as $key => $value) {
            array_push($dataTrimArray, [
                "ID" => trim($value->userid),
                "NAME" => trim($value->username)
            ]);
        }
        $data['team'] = $dataTrimArray; 

        
        $dataDepart = DB::connection('pgsql')->table('master_data.m_user as a')
        ->join('master_data.m_department as b', 'a.departmentid', '=', 'b.departmentid')
        ->whereIn('b.departmentid', ['DD001'])
        ->orderBy('a.username', 'asc')
        ->get();
        $dataTrimArray = [];
        foreach ($dataDepart as $key => $value) {
            array_push($dataTrimArray, [
                "ID" => trim($value->userid),
                "NAME" => trim($value->username)
            ]);
        }
        $data['depart'] = $dataTrimArray; 

        return view('fitur.absensi', $data);
    }
    
    public function getAbsensi(Request $request)
    {

        $userid = Session::get('userid');
        $roleid = Session::get('roleid');
        $departementid = Session::get('departementid');
        $mgrid = Session::get('mgrid');
        $id =  substr($userid, 2);
        $date_arr = $request->get('daterange');
        if(empty($date_arr)){
            $start = explode(' - ',$date_arr);
            $start_date = date("Y-m-d");
            $end = explode(' - ',$date_arr);
            $end_date = date("Y-m-d");
        } else {
            $start = explode(' - ',$date_arr)[0];
            $start_date = date("Y-m-d", strtotime($start));
            $end = explode(' - ',$date_arr)[1];
            $end_date = date("Y-m-d", strtotime($end));
        }
        
        if($roleid == 'RD009'){
            $myteam = $request->myteam;
        } else if($roleid == 'RD006'){
            $myteam = $request->mydepart;
        } else {
            $myteam = '%';
        }

        $start = $request->start;
        $length = $request->length;
        /* GET DATA ABSEN */
        $dataAbsen = $this->repository->GETABSEN($id, $userid, $start_date, $end_date, $roleid, $departementid, $mgrid, $myteam, $start, $length);
        
        $json = json_decode($dataAbsen, true);
        
        if($json["rc"] == "00") 
        {   
            $data = $json['data'];
        } else {
            $data = [];
            $json = ["total" => 0];
        }
        
        return DataTables::of($data)
            ->setTotalRecords($json["total"])
            ->setFilteredRecords($json["total"])
            ->skipPaging()
            ->make(true);
    }

}
