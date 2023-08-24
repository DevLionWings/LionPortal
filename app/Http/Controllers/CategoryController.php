<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use DataTables;

class CategoryController extends Controller
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

        return view('fitur.mastercategory');
    }

    public function dataList(Request $request)
    {
        $datacategory = DB::connection('pgsql')->table('master_data.m_category')->get();
        $dataTrimArray = [];
        foreach ($datacategory as $key => $value) {
            array_push($dataTrimArray, [
                "categoryid" => trim($value->categoryid),
                "description" => trim($value->description),
            ]);
        }
        $data['dat'] = $dataTrimArray;

        return DataTables::of($data['dat'])
        ->addColumn('action', function($row){
            $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-categoryid="'.$row["categoryid"].'" ><i class="fa fa-trash" aria-hidden="true"></i></a>';
            return $deleteBtn;
            
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function insert(Request $request)
    {
        $insert = DB::connection('pgsql')->table('master_data.m_category')->insert([
            'categoryid' => $request->categoryid,
            'description' => $request->desc,
            'approval' => $request->approve
        ]);

        if($insert == true){
            return redirect()->route('category')->with("success", "Data insert successfully");
        } else { 
            return redirect()->back()->with("error", "error");
        }
    }

    public function delete(Request $request)
    {
        $deletedata = DB::connection('pgsql')->table('master_data.m_category')->where('categoryid', $request->id)->delete();
        if($deletedata == true){
            return redirect()->back()->with("success", "Data deleted successfully");
        } else {
            return redirect()->back()->with("error", "Failed");
        }
        
    }
}
