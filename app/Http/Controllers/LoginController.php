<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Useraccount;
use App\Models\User;
use App\Helpers\Response;
use App\Helpers\Repository;

class LoginController extends Controller
{
    protected $response;
    protected $repository;

    public function __construct(Repository $repository, Response $response)
    {
        $this->repository = $repository;
        $this->response = $response;
    }
    
    public function showLoginPage()
    {
        return view('auth.login');
    }

    public function updateLogin ($userid, $password){
        $date = date('Y-m-d H:i:s');
       
        try {
            $update = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->update([
                'status_login' => 1,
                'createdon' => $date
            ]);
            DB::commit();
            if(!empty($update)){
                return response()->json([
                    'rc' => '00',
                    'desc' => 'success',
                    'msg' => 'success',
                    'data' => $update
                ]);
            } else {
                return response()->json([
                    'rc' => '01',
                    'desc' => 'failed',
                    'msg' => 'failed',
                    'data' => $update
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function updateLogout ($userid, $password){
        $date = date('Y-m-d H:i:s');

        try {
            DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->update([
                'status_login' => 0,
                'createdon' => $date
            ]);
            DB::commit();
            return $this->response->SUCCESS('');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(Request $request){
        $userid = $request->userid;
        $password = trim($request->password);

        $credentials = $request->validate([
            "userid"=>"required",
            "password"=>"required"
        ]);

        $datLogin = $this->repository->GETUSER($userid, $password);
        $json = json_decode($datLogin);
        $data = $json->data;
        if($json->rc == '00'){ 
            /* Update status */
            $update = $this->updateLogin($userid, $password);
            $json = json_decode($update);
       
            if(!empty($update)){
                /* checked status login */
                if ($data->status_login == 1){
                    return back()->withErrors([
                        'userid' => 'If you are already logged in, please log out first ',
                    ]);
                }
            
                $userid = $data->userid;
                $username = $data->username;
                $password = $data->pass;
                $departmentid = $data->departmentid;
                $usermail = $data->usermail;
                $status = $data->status_login;
                $roleid = $data->roleid;
                $plantid = $data->plantid;
                $spvid = $data->spvid;
                $headid = $data->headid;
                $mgrid = $data->mgrid;

                $datTicket = $this->repository->GETMYTICKET($userid, $roleid);
                $json = json_decode($datTicket);
                $data = $json->data->data;
                // $request->session()->put($data);

                /* Session Data */
                $session = array(
                    'userid' => $userid,
                    'username' => $username,
                    'password' => $password,
                    'departmentid' => $departmentid,
                    'usermail' => $usermail,
                    'roleid' => $roleid,
                    'plantud' => $plantid,
                    'spvid' => $spvid,
                    'headid' => $headid,
                    'mgrid' => $mgrid,
                    'status' => $status
                );
                /* Set User Session */
                Session::put('login', true);
                Session::put('userid', $userid);
                Session::put('username', $username);
                Session::put('password', $password);
                Session::put('departmentid', $departmentid);
                Session::put('usermail', $usermail);
                Session::put('roleid', $roleid);
                Session::put('plantid', $plantid);
                Session::put('spvid', $spvid);
                Session::put('headid', $headid);
                Session::put('mgrid', $mgrid);
                Session::put('status', $status);
        
                return redirect()->route('home')
                    ->withSuccess('You have successfully logged in!');
            } else {
                return back()->withErrors(['error' => 'If you are already logged in, please log out first or call admin',]);
            }           
        } else {
            return back()->withErrors(['error' => 'Wrong Password',]);
        }
    } 

    public function logout(Request $request)
    {
        $userid = Session::get('userid');
        $password = Session::get('password');
     
        $datLogin = $this->repository->GETUSER($userid, $password);
        $json = json_decode($datLogin);
        $data = $json->data;

        /* Update Status Login */
        $update = $this->updateLogout($userid, $password); 
        /* End */ 

        return redirect()->route('login');
    }  
    
}
