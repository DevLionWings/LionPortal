<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use DataTables;

class CounterController extends Controller
{
    public function __construct(Repository $repository, Response $response)
    {
        $this->repository = $repository;
        $this->response = $response;
    }

    public function index(Request $request)
    {
        // $isLogin = Session::get('status_login');
        // if($isLogin != 1) {
        //     return redirect()->route('login-page');
        // }

        return view('fitur.mastercounter');
    }

    public function dataList(Request $request)
    {
        $datacounter = DB::connection('pgsql')->table('master_data.m_counter')->get();
        $dataTrimArray = [];
        foreach ($datacounter as $key => $value) {
            array_push($dataTrimArray, [
                "counterid" => trim($value->counterid),
                "prefix" => trim($value->prefix),
                "period" => trim($value->period),
                "description" => trim($value->description),
            ]);
        }
        $data['dat'] = $dataTrimArray;

        return DataTables::of($data['dat'])
        ->addColumn('action', function($row){
            $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-counterid="'.$row["counterid"].'" data-prefix="'.$row["prefix"].'" data-description="'.$row["description"].'" data-period="'.$row["period"].'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            return $deleteBtn;
            
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function insert(Request $request)
    {

        $insert = DB::connection('pgsql')->table('master_data.m_counter')->insert([
            'counterid' => $request->counterid,
            'prefix' => $request->prefix,
            'period' => $request->period,
            'description' => $request->desc,
            'start_number' => '0',
            'end_number' => '9999',
            'last_number' => '0'
        ]);

        if($insert == true){
            return redirect()->route('counter')->with("success", "Data insert successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function delete(Request $request)
    {
        $deletedata = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', $request->id)->delete();
        if($deletedata == true){
            return redirect()->back()->with("success", "Data deleted successfully");
        } else {
            return redirect()->back()->with("error", "Failed");
        }
        
    }

}
