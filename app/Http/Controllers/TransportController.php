<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\File;
use App\Helpers\Mail;
use App\Models\Useraccount;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Counter;

class TransportController extends Controller
{
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function sendTransport(Request $request)
    {
        $validate = $request->validate([
                'transnumber' => 'required',
                'checkbox' => 'accepted'
            ],
            [
                'required'  => 'The :attribute field is required.'
            ]
        );

        if($validate){
            /* Checked Opsi Transport */
            if(empty($request->lpr)){
                $lqa = '1';
                $lpr = '0';
            } else if (empty($request->lqa)) {
                $lqa = '0';
                $lpr = '1';
            } else if (empty($request->lpr) && empty($request->lqa)){
                $lqa = '0';
                $lpr = '0';
            } else {
                $lqa = '1';
                $lpr = '1';
            }
            /* end checked */
            return $lqa;
            /* Generate Transport Id */
            $year = date("Y");
            $dataPrefix = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT007')->where('period', $year)->first();

            $subs1 = $dataPrefix->prefix;
            $subs2 =  str_pad($dataPrefix->last_number + 1, 4, "00", STR_PAD_LEFT);
            $transportId = $subs1.$subs2;
            return $transportId;
            $last = $dataPrefix->last_number + 1;
            $update = DB::connection('pgsql')->table('master_data.m_counter')
                ->where('counterid', 'CT007')
                ->where('period', $year)
                ->update([
                    'last_number' => $last
            ]);
            /* End */
        }
    }

}
