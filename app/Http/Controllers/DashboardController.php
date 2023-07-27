<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\Response;
use App\Helpers\Repository;
use Auth;

class DashboardController extends Controller
{
    protected $response;
    protected $repository;

    public function __construct(Repository $repository, Response $response)
    {
        $this->repository = $repository;
        $this->response = $response;
    }

    public function index(Request $request)
    {   
        // $isLogin = session('login');
        // if(!$isLogin) {
        //     return redirect()->route('login-page');
        // }
        // $isLogin = Session::get('status');
        // if($isLogin != 1) {
        //     return redirect()->route('login-page');
        // }
        // $allSessions = session()->all();
        $datLogin = $this->repository->GETUSER(Session::get('userid'), Session::get('password'));
        $json = json_decode($datLogin);
       
        if ($json->rc == 00){
            $data = $json->data;
            $status_login = $data->status_login;
            if ($data == [] || $status_login == 0) {
                return redirect()->route('login')
                ->withErrors('please login first');
            }
        } else {
            // $flushSessions = session()->flush();
            return redirect()->route('login')
                ->withErrors('Wrong Password');
        }
        $session = array(
            'status_login' => $status_login
        );
        Session::put('status_login', $status_login);
        
        return view('auth.dashboard');
    }
    
}
