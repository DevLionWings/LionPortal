<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use DataTables;

class HistoryController extends Controller
{
    public function __construct(Repository $repository, Response $response)
    {
        $this->repository = $repository;
        $this->response = $response;
    }

    public function index(Request $request)
    {
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        }

        return view('fitur.historykwitansi');
    }

    public function dataList(Request $request)
    {
        $datakaryawan = DB::connection('pgsql')->table('hris.t_kwitansi_backup')->get();
        $dataTrimArray = [];
        foreach ($datakaryawan as $key => $value) {
            array_push($dataTrimArray, [
                "idkwitansi" => trim($value->idkwitansi),
                "nik" => trim($value->nik),
                "namakaryawan" => trim($value->namakaryawan),
                "bagian" => trim($value->bagian),
                "tanggalcuti" => trim($value->tanggalcuti),
                "tanggalmasuk" => trim($value->tanggalmasuk),
                "jumlahchh" => trim($value->jumlahchh),
                "gaji" => trim($value->gaji),
                "jabatan" => trim($value->jabatan),
                "jamsostek" => trim($value->jamsostek),
                "uangmakan" => trim($value->uangmakan),
                "uangspsi" => trim($value->uangspsi),
                "uangkoperasi" => trim($value->uangkoperasi),
                "lamacuti" => trim($value->lamacuti),
                "total" => trim($value->total),
                "untuk" => trim($value->untuk),
                "keterangan" => trim($value->keterangan),
                "terbilang" => trim($value->terbilang),
                "masakerja" => trim($value->masakerja),
                "tglpisah" => trim($value->tglpisah),
                "type" => trim($value->type),
                "category" => trim($value->category),
                "selisih" => trim($value->selisih),
                "ket_selisih" => trim($value->ket_selisih),
                "haribaru" => trim($value->haribaru),
                "harilama" => trim($value->harilama),
            ]);
        }
        $data['dat'] = $dataTrimArray;
        
        return DataTables::of($data['dat'])
        ->addColumn('action', function($row){
            $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-nik="'.$row["nik"].'" ><i class="fa fa-trash" aria-hidden="true"></i></a>';
            return $deleteBtn;
            
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function delete(Request $request)
    {   
        $deletedata = DB::connection('pgsql')->table('hris.t_kwitansi_backup')->where('nik', $request->id)->delete();
        if($deletedata == true){
            return redirect()->back()->with("success", "Data deleted successfully");
        } else {
            return redirect()->back()->with("error", "Failed");
        }
        
    }
}
