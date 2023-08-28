<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use DataTables;

class KaryawanController extends Controller
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

        return view('fitur.masterkaryawan');
    }

    public function dataList(Request $request)
    {
        $datakaryawan = DB::connection('pgsql')->table('hris.t_karyawan')->get();
        $dataTrimArray = [];
        foreach ($datakaryawan as $key => $value) {
            array_push($dataTrimArray, [
                "idsmu" => trim($value->idsmu),
                "id" => trim($value->id),
                "nama" => trim($value->nama),
                "tgl_in" => trim($value->tgl_in),
                "sex" => trim($value->sex),
                "bagian" => trim($value->bagian),
                "tgl_lahir" => trim($value->tgl_lahir),
                "gaji" => trim($value->gaji),
                "turun_gaji" => trim($value->turun_gaji),
                "jabatan" => trim($value->jabatan),
                "spsi" => trim($value->spsi),
                "koperasi" => trim($value->koperasi),
                "date_update" => trim($value->date_update),
            ]);
        }
        $data['dat'] = $dataTrimArray;
        
        return DataTables::of($data['dat'])
        ->addColumn('action', function($row){
            $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-idsmu="'.$row["idsmu"].'" ><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $editBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" 
            data-idsmu="'.$row["idsmu"].'" data-id="'.$row["id"].'" data-nama="'.$row["nama"].'" data-tgl_in="'.$row["tgl_in"].'" data-sex="'.$row["sex"].'" data-bagian="'.$row["bagian"].'" 
            data-tgl_lahir="'.$row["tgl_lahir"].'" data-gaji="'.$row["gaji"].'" data-turun_gaji="'.$row["turun_gaji"].'" data-jabatan="'.$row["jabatan"].'" data-spsi="'.$row["spsi"].'" data-koperasi="'.$row["koperasi"].'"
            data-date_update="'.$row["date_update"].'"><i class="fas fa-edit"></i></a>';
            return $deleteBtn.$editBtn;
            
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function insert(Request $request)
    {

        // generate ID // 
        $insert = DB::connection('pgsql')->table('hris.t_karyawan')->insert([
            'idsmu' => $request->idsmu,
            'id' => $request->id,
            'nama' => $request->nama,
            'tgl_in' => $request->tgl_in,
            'sex' => $request->sex,
            'bagian' => $request->bagian,
            'tgl_lahir' => $request->tgl_lahir,
            'gaji' => $request->gaji,
            'turun_gaji' => $request->turun_gaji,
            'jabatan' => $request->jabatan,
            'spsi' => $request->spsi,
            'koperasi' => $request->koperasi,
            'date_update' => date('Y-m-d'),
        ]);

        if($insert == true){
            return redirect()->route('karyawan')->with("success", "Data insert successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function update(Request $request)
    {
        $update = DB::connection('pgsql')->table('hris.t_karyawan')->where('idsmu', $request->idsmu)->update([
            'idsmu' => $request->idsmu,
            'id' => $request->id,
            'nama' => $request->nama,
            'tgl_in' => $request->tgl_in,
            'sex' => $request->sex,
            'bagian' => $request->bagian,
            'tgl_lahir' => $request->tgl_lahir,
            'gaji' => $request->gaji,
            'turun_gaji' => $request->turun_gaji,
            'jabatan' => $request->jabatan,
            'spsi' => $request->spsi,
            'koperasi' => $request->koperasi,
            'date_update' => date('Y-m-d'),
        ]);

        if($update == true){
            return redirect()->route('karyawan')->with("success", "Data update successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function delete(Request $request)
    {   
        $deletedata = DB::connection('pgsql')->table('hris.t_karyawan')->where('idsmu', $request->id)->delete();
        if($deletedata == true){
            return redirect()->back()->with("success", "Data deleted successfully");
        } else {
            return redirect()->back()->with("error", "Failed");
        }
        
    }
}
