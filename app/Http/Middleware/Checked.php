<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Closure;

class Checked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)

    {

        $userid = Session::get('userid');

        $dataLogin = DB::connection('pgsql')->table('master_data.m_user')->where('userid', $userid)->first();
        if($dataLogin == null) {
            return response()->json('You do not have access!!');
        } else {
            $isLogin = $dataLogin->status_login;

            if($isLogin != 1) {
                return response()->json('You do not have access!!');
            }
        }
        
        return $next($request);
    }
}
