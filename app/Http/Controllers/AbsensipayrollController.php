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

        $date_arr = $request->get('daterange');
        $nip = $request->get('data_nip');
        $kodedivisi = $request->get('divisi');
        $kodebagian = $request->get('bagian');
        $kodegroup = $request->get('group');
        $kodeadmin = $request->get('admin');
        $kodeperiode = $request->get('periode');
        $start = explode(' - ',$date_arr)[0];
        $start_date = date("Y-m-d", strtotime($start));
        $end = explode(' - ',$date_arr)[1];
        $end_date = date("Y-m-d", strtotime($end));
            
        /* Get Absen Perkas */
        $dataAbsenPerkas = $this->repository->GETFILTERPERSONALIA($nip, $kodedivisi, $kodebagian, $kodegroup, $kodeadmin, $kodeperiode, $start_date, $end_date);
        $json = json_decode($dataAbsenPerkas, true);
        
        if($json["rc"] == "00") 
        {   
            $data = $json['data']['data'];
        } else {
            $data = [];
            $json["total"] = [];
        }
        
        return DataTables::of($data)
            ->setTotalRecords($json["total"])
            ->setFilteredRecords($json["total"])
            ->make(true);

    }

    public function updateShift(Request $request)
    {
        $shift = $request->shift;
        $nokasus = $request->nokasus;
        $dat = '';

        $lastShift = DB::connection('mysql2')->table('personalia.kasus')
            ->select('No Kasus as nokasus')
            ->orderBy('No Kasus', 'DESC')
            ->limit(1)
            ->get();
            
        $prefix = $lastShift[0]->nokasus; 
        // $prefixArray = [];
        // foreach ($lastShift as $key => $value) {
        //     array_push($prefixArray, [
        //         "nokasus" => explode('   ', $value['No Kasus']),
        //     ]);
        // }
        // $data['dat'] = $prefixArray;
        // return $data;
    }

}
