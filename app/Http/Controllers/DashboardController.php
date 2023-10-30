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
        $password = trim(Session::get('password'));
        $roleid = trim(Session::get('roleid'));
        $departid = trim(Session::get('departmentid'));

        $datLogin = $this->repository->GETUSER(Session::get('userid'), $password);
        $json = json_decode($datLogin);
        
        if ($json->rc == 00){
            $data = $json->data;
            $status_login = $data->status_login;
            if ($data == [] || $status_login == 0) {
                return redirect()->route('login')
                ->withErrors('please login first');
            } else {
                $session = array(
                    'status_login' => $status_login
                );
                Session::put('status_login', $status_login);
                
                if ($roleid == 'RD011'){
                    return redirect()->route('admin-index')
                    ->withSuccess('You have successfully logged in!');
                } else if ($departid != 'DD001'){
                    return view('fitur.absensi');
                } else {
                    return view('auth.dashboard');
                }
            }
        } else {
            // $flushSessions = session()->flush();
            return redirect()->route('login');
        }
        $session = array(
            'status_login' => $status_login
        );
        Session::put('status_login', $status_login);

        if ($roleid == 'RD011'){
            return redirect()->route('admin-index')
            ->withSuccess('You have successfully logged in!');
        } else if ($departid != 'DD001'){
            return view('fitur.absensi');
        } else {
            return view('auth.dashboard');
        }
    }
    
}
