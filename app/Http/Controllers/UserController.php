<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use DataTables;

class UserController extends Controller
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

        $dept = '';
        $plnt = '';
        $rol = '';

        /* Get Department*/
        $datadept = DB::connection('pgsql')->table('master_data.m_department')->get();
        $dataArray = [];
        foreach ($datadept as $key => $value) {
            array_push($dataArray, [
                "NAME" => trim($value->description),
                "ID" => trim($value->departmentid)
            ]);
        }
        $data['dept'] = $dataArray;
        /* Get Plant*/
        $datadept = DB::connection('pgsql')->table('master_data.m_plant')->get();
        $dataArray = [];
        foreach ($datadept as $key => $value) {
            array_push($dataArray, [
                "NAME" => trim($value->description),
                "ID" => trim($value->plantid)
            ]);
        }
        $data['plnt'] = $dataArray;
        /* Get Role*/
        $datadept = DB::connection('pgsql')->table('master_data.m_role')->get();
        $dataArray = [];
        foreach ($datadept as $key => $value) {
            array_push($dataArray, [
                "NAME" => trim($value->description),
                "ID" => trim($value->roleid)
            ]);
        }
        $data['rol'] = $dataArray;



        return view('fitur.masteruser', $data);
    }

    public function dataList(Request $request)
    {
        $datacounter = DB::connection('pgsql')->table('master_data.m_user')->get();
        $dataTrimArray = [];
        foreach ($datacounter as $key => $value) {
            array_push($dataTrimArray, [
                "userid" => trim($value->userid),
                "username" => trim($value->username),
                "pass" => trim($value->pass),
                "departmentid" => trim($value->departmentid),
                "plantid" => trim($value->plantid),
                "roleid" => trim($value->roleid),
                "spvid" => trim($value->spvid),
                "mgrid" => trim($value->mgrid),
                "usermail" => trim($value->usermail),
            ]);
        }
        $data['dat'] = $dataTrimArray;
        
        return DataTables::of($data['dat'])
        ->addColumn('action', function($row){
            $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-userid="'.$row["userid"].'" ><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $editBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" 
            data-userid="'.$row["userid"].'" data-username="'.$row["username"].'" data-pass="'.$row["pass"].'" data-departmentid="'.$row["departmentid"].'" data-plantid="'.$row["plantid"].'" data-roleid="'.$row["roleid"].'" 
            data-spvid="'.$row["spvid"].'" data-mgrid="'.$row["mgrid"].'" data-usermail="'.$row["usermail"].'"><i class="fas fa-edit"></i></a>';
            return $deleteBtn.$editBtn;
            
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function insert(Request $request)
    {
        $insert = DB::connection('pgsql')->table('master_data.m_user')->insert([
            'userid' => $request->userid,
            'username' => $request->username,
            'pass' => $request->pass,
            'departmentid' => $request->deptid,
            'plantid' => $request->plantid,
            'roleid' => $request->roleid,
            'spvid' => $request->spvid,
            'mgrid' => $request->mgrid,
            'createdon' => date('Y-m-d'),
            'usermail' => $request->email,
            'status_login' => 0,
        ]);

        if($insert == true){
            return redirect()->route('user')->with("success", "Data insert successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function update(Request $request)
    {
        $update = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $request->userid)->update([
            'userid' => $request->userid,
            'username' => $request->username,
            'departmentid' => $request->deptid,
            'plantid' => $request->plantid,
            'roleid' => $request->roleid,
            'spvid' => $request->spvid,
            'mgrid' => $request->mgrid,
            'createdon' => date('Y-m-d'),
            'usermail' => $request->usermail,
        ]);

        if($update == true){
            return redirect()->route('user')->with("success", "Data update successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function delete(Request $request)
    {   
        $deletedata = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $request->id)->delete();
        if($deletedata == true){
            return redirect()->back()->with("success", "Data deleted successfully");
        } else {
            return redirect()->back()->with("error", "Failed");
        }
        
    }
}
