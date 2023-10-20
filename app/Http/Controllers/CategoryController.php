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
                "systemid" => trim($value->systemid),
                "approval" => trim($value->approval),
                "flagging" => trim($value->flagging),
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
        // Checked option system //
        if ($request->SY001 == "on" && $request->SY002 == "on"){
            $systemid = ['SY001', 'SY002'];
            $flagging = [1, 1];
        } else if ($request->SY001 == "on"){
            $systemid = ['SY001', 'SY002'];
            $flagging = [1, 0];
        } else if ($request->SY002 == "on"){
            $systemid = ['SY001', 'SY002'];
            $flagging = [0, 1];
        } else {
            $systemid = ['SY001', 'SY002'];
            $flagging = [0, 0];
        }
        // end //

        $year = date("Y");
        $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT006')->where('period', $year)->first();
        // $last = $dataPrefix->last_number + 1;
        $last =  str_pad($dataPrefix->last_number + 1, 3, "0", STR_PAD_LEFT);
        $categoryid = 'CD'.$last;
        $update = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT006')->where('period', $year)->update([
            'last_number' => $last
        ]);

        $arrayInsert = [];
        for ($i=0; $i < count($systemid); $i++){
            $draw = [
                'systemid' => $systemid[$i],
                'categoryid' => $categoryid,
                'description' => $request->desc,
                'approval' => $request->approve,
                'flagging' => $flagging[$i]
            ];
            
            $arrayInsert[] = $draw;      
        }

        $insert = DB::connection('pgsql')->table('master_data.m_category')->insert($arrayInsert);

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

    public function categoryFilter(Request $request)
    {
        $dataCategory =  DB::connection('pgsql')->table('master_data.m_category')
        ->where('systemid', $request->systemid)
        ->where('flagging', 1)
        ->get();

        $jsonCategory = json_decode($dataCategory, true);

        /* Get Category */
        $category = $jsonCategory;
        $categoryArray = [];
        foreach ($category as $key => $value) {
            array_push($categoryArray, [
                "SYSTEMID" => trim($value['systemid']),
                "CATEGORYID" => trim($value['categoryid']),
                "FLAGGING" => trim($value['flagging']),
                "DESC" => trim($value['description']),
            ]);
        }

        $data['disc'] = $categoryArray; 
        
        return $data;
    }
}
