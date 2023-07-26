<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use DataTables;

class AbsensipayrollController extends Controller
{
    public function __construct(Repository $repository, Response $response)
    {
        ini_set('max_execution_time', 300);
        $this->repository = $repository;
        $this->response = $response;
    }

    public function absenpayroll()
    {   
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        }

        $div = '';
        $bag = '';
        $grp = '';
        $adm = '';
        $period = '';
        $nip = '';

        $dataIndex = $this->repository->INDEXFILTERPERSNOLIA();
        $json = json_decode($dataIndex, true);

        if($json["rc"] == "00") {
            /* Get Divisi */
            $divisi = $json['divisi'];
            $divisiArray = [];
            foreach ($divisi as $key => $value) {
                if(!in_array($value, $divisiArray)){
                    array_push($divisiArray, [
                        "NAME" => trim($value['Nama Divisi']),
                        "ID" => trim($value['Kode Divisi'])
                    ]);
                }
            }
            $data['div'] = $divisiArray; 

            /* Get Bagian */
            $divisi = $json['bagian'];
            $divisiArray = [];
            foreach ($divisi as $key => $value) {
                if(!in_array($value, $divisiArray)){
                    array_push($divisiArray, [
                        "NAME" => trim($value['Nama Bagian']),
                        "ID" => trim($value['Kode Bagian'])
                    ]);
                }
            }
            $data['bag'] = $divisiArray; 

            /* Get Group */
            $divisi = $json['group'];
            $divisiArray = [];
            foreach ($divisi as $key => $value) {
                if(!in_array($value, $divisiArray)){
                    array_push($divisiArray, [
                        "NAME" => trim($value['Nama Group']),
                        "ID" => trim($value['Kode Group'])
                    ]);
                }
            }
            $data['grp'] = $divisiArray; 

            /* Get Admin */
            $divisi = $json['admin'];
            $divisiArray = [];
            foreach ($divisi as $key => $value) {
                if(!in_array($value, $divisiArray)){
                    array_push($divisiArray, [
                        "NAME" => trim($value['Nama Admin']),
                        "ID" => trim($value['Kode Admin'])
                    ]);
                }
            }
            $data['adm'] = $divisiArray; 

            /* Get Periode */
            $divisi = $json['periode'];
            $divisiArray = [];
            foreach ($divisi as $key => $value) {
                if(!in_array($value, $divisiArray)){
                    array_push($divisiArray, [
                        "NAME" => trim($value['Nama Periode']),
                        "ID" => trim($value['Kode Periode'])
                    ]);
                }
            }
            $data['period'] = $divisiArray;
            
            /* Get Nip */
            $divisi = $json['nip'];
            $divisiArray = [];
            foreach ($divisi as $key => $value) {
                if(!in_array($value, $divisiArray)){
                    array_push($divisiArray, [
                        "NAME" => trim($value['Nama']),
                        "ID" => trim($value['Nip']),
                        "DIV" => trim($value['Kode Divisi']),
                        "BAG" => trim($value['Kode Bagian']),
                        "GRP" => trim($value['Kode Group'])
                    ]);
                }
            }
            $data['nip'] = $divisiArray; 
        }
        
        return view('fitur.absensipayroll', $data);
    }
    
    public function getAbsenPerkas(Request $request)
    {   

        $nip = $request->data_nip;
        $date_arr = $request->get('daterange');
        $start = explode(' - ',$date_arr)[0];
        $start_date = date("Y-m-d", strtotime($start));
        $end = explode(' - ',$date_arr)[1];
        $end_date = date("Y-m-d", strtotime($end));
        $limit = $request->start;
        $page = $request->length;
            
        /* Get Absen Perkas */
        $dataAbsenPerkas = $this->repository->GETPERSONALIA($nip, $limit, $page, $start_date, $end_date);
        $json = json_decode($dataAbsenPerkas, true);
        
        if($json["rc"] == "00") 
        {   
            $data = $json['data'];
            if(!empty($json["total"]) || $data == []){
                $total = $json["total"];
            }
            $total = $json["total"];
        } else {
            $data = [];
            $total = $json["total"];
        }
        
        return DataTables::of($data)
            ->addColumn('action', function($row){
                $updateBtn = ' <a href="javascript:void(0)" class="newshift btn btn-success" data-nip="'.$row["Nip"].'" data-newshift="'.$row["NewShift"].'" data-category="'.$row["TimeCategory"].'"><i class="fas fa-edit"></i></a>';
                return $updateBtn;
                
            })
            ->rawColumns(['checkbox', 'action'])
            ->setTotalRecords($total)
            ->setFilteredRecords($total)
            ->skipPaging()
            ->make(true);

    }

    public function filterAbsenPerkas(Request $request)
    {   

        $date_arr = $request->get('daterange');
        $nip = $request->data_nip;
        $kodedivisi = $request->data_divisi;
        $kodebagian = $request->data_bagian;
        $kodegroup = $request->data_group;
        $kodeadmin = $request->data_admin;
        $kodeperiode = $request->data_periode;
        $kontrak = $request->data_kontrak;
        $start = explode(' - ',$date_arr)[0];
        $start_date = date("Y-m-d", strtotime($start));
        $end = explode(' - ',$date_arr)[1];
        $end_date = date("Y-m-d", strtotime($end));
        $limit = $request->start;
        $page = $request->length;
            
        /* Get Absen Perkas */
        $dataAbsenPerkas = $this->repository->GETFILTERPERSONALIA($nip, $kodedivisi, $kodebagian, $kodegroup, $kodeadmin, $kodeperiode, $kontrak, $start_date, $end_date, $limit, $page);
        $json = json_decode($dataAbsenPerkas, true);
        
        if($json["rc"] == "00") 
        {   
            $data = $json['data'];
            if(!empty($json["total"]) || $data == []){
                $total = $json["total"];
            }
            $total = $json["total"];
            // $total = $json["total"][0]["count"];
        } else {
            $data = [];
            $total = $json["total"];
        }
        
        return DataTables::of($data)
            ->addColumn('action', function($row){
                $updateBtn = ' <a href="javascript:void(0)" class="newshift btn btn-success" data-nip="'.$row["Nip"].'" data-newshift="'.$row["NewShift"].'" data-category="'.$row["TimeCategory"].'"
                data-jamin="'.$row["JamIn"].'" data-tglin="'.$row["TglIn"].'" data-tglout="'.$row["TglOut"].'" data-jamout="'.$row["JamOut"].'" data-timevalid="'.$row["TimeValidation"].'"
                data-jamlembur="'.$row["JamLembur"].'" data-off="'.$row["LamaOff"].'"><i class="fas fa-edit"></i></a>';
                return $updateBtn;
            })
            ->rawColumns(['action'])
            ->setTotalRecords($total)
            ->setFilteredRecords($total)
            ->skipPaging()
            ->make(true);

    }

    public function updateShift(Request $request)
    {
        $shift = $request->selectshift;
        $nip = $request->nip;
        $jamin = $request->jamin;
        $tglin = $request->tglin;
        $jamout = $request->jamout;
        $tglout = $request->tglout;
        $timevalid = $request->timevalid;
        $jamlembur = $request->jamlembur;
        $lamaoff = $request->off;
        
        $lastShift = DB::connection('mysql2')->table('personalia.kasus')
            ->where('No Kasus', 'LIKE','Z'.'%')
            ->select('No Kasus as nokasus')
            ->max('No Kasus');
   
        if(empty($lastShift) ){
            $nokasus = 'Z'.'000000000';
            // $var = $i + 1;
            $subs = substr($nokasus, 1, 10) ;
            $int = intval($subs);
            $newint = $int+1;
            $str_pad = str_pad($newint, 9, "0", STR_PAD_LEFT);
            $last = 'Z'.$str_pad;
            $no_kasus = $last;
        } else {
            // $var = $i + 1;
            $subs = substr($lastShift, 1, 10) ;
            $int = intval($subs);
            $newint = $int+1;
            $str_pad = str_pad($newint, 9, "0", STR_PAD_LEFT);
            $last = 'Z'.$str_pad;
            $no_kasus = $last;
        }
        
        /* Update Shift & Insert Tukar Shift */
        $queryShift = $this->repository->UPDATETUKARSHIFT($nip, $shift, $no_kasus, $jamin, $tglin, $jamout, $tglout, $timevalid, $jamlembur, $lamaoff);
      
        return redirect()->route('absensipayroll')->with("success", "successfully");
    }

    public function updateShiftBulk(Request $request)
    {
        $nip = $request->nip;
        $kodedivisi = $request->data_divisi;
        $kodebagian = $request->data_bagian;
        $kodegroup = $request->data_group;
        $kodeadmin = $request->data_admin;
        $kodeperiode = $request->data_periode;
        $kontrak = $request->data_kontrak;
        $date_arr = $request->get('daterange');
        $start = explode(' - ',$date_arr)[0];
        $start_date = date("Y-m-d", strtotime($start));
        $end = explode(' - ',$date_arr)[1];
        $end_date = date("Y-m-d", strtotime($end));
    
        $lastShift = DB::connection('mysql2')->table('personalia.kasus')
            ->where('No Kasus', 'LIKE','Z'.'%')
            ->select('No Kasus as nokasus')
            ->max('No Kasus');
        $count = count($nip);
   
        $no_kasus = [];
        for ($i=1; $i <= $count; $i++){
            if(empty($lastShift) ){
                $nokasus = 'Z'.'000000000';
                // $var = $i + 1;
                $subs = substr($nokasus, 1, 10) ;
                $int = intval($subs);
                $newint = $int+$i;
                $str_pad = str_pad($newint, 9, "0", STR_PAD_LEFT);
                $last = 'Z'.$str_pad;
            } else {
                // $var = $i + 1;
                $subs = substr($lastShift, 1, 10) ;
                $int = intval($subs);
                $newint = $int+$i;
                $str_pad = str_pad($newint, 9, "0", STR_PAD_LEFT);
                $last = 'Z'.$str_pad;
            }
            array_push($no_kasus, $last);
        } 
     
        /* Update Shift & Insert Tukar Shift */
        $queryShift = $this->repository->UPDATETUKARSHIFTBULK($nip, $kodedivisi, $kodebagian, $kodegroup, $kodeadmin, $kodeperiode, $kontrak, $start_date, $end_date, $no_kasus);
        return $queryShift;
        return redirect()->route('absensipayroll')->with("success", "successfully");
    }

    public function getShift(Request $request)
    {   
        $shft = '';
        $shift = $request->shift;

        $dataShift = DB::connection('mysql2')->table('personalia.mastershift') 
                ->where('Kode Shift', 'LIKE','%'.$shift.'%') 
                ->get();
        $jsonShift = json_decode($dataShift, true);

        /* Get Shift */
        $shift = $jsonShift;
        $shiftArray = [];
        foreach ($shift as $key => $value) {
            if(!in_array($value, $shiftArray)){
                array_push($shiftArray, [
                    "NAME" => trim($value['Nama Shift']),
                    "CODE" => trim($value['Kode Shift']),
                ]);
            }
        }
        $data['shft'] = $shiftArray; 
        
        return $data;
    }

}
