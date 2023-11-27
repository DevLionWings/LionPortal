<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use App\Helpers\Convertion;
use Carbon\Carbon;
use DataTables;
use DateTime;
use PDF;

class HistoryController extends Controller
{
    public function __construct(Repository $repository, Response $response, Convertion $convertion)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->convertion = $convertion;
    }

    public function index(Request $request)
    {
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        }

        return view('fitur.historykwitansi');
    }

    public function dataList(Request $request)
    {
        $datakaryawan = DB::connection('pgsql')->table('hris.t_kwitansi_backup')->get();
        $dataTrimArray = [];
        foreach ($datakaryawan as $key => $value) {
            array_push($dataTrimArray, [
                "idkwitansi" => trim($value->idkwitansi),
                "nik" => trim($value->nik),
                "namakaryawan" => trim($value->namakaryawan),
                "bagian" => trim($value->bagian),
                "tanggalcuti" => trim($value->tanggalcuti),
                "tanggalmasuk" => trim($value->tanggalmasuk),
                "jumlahchh" => trim($value->jumlahchh),
                "gaji" => trim($value->gaji),
                "jabatan" => trim($value->jabatan),
                "jamsostek" => trim($value->jamsostek),
                "uangmakan" => trim($value->uangmakan),
                "uangspsi" => trim($value->uangspsi),
                "uangkoperasi" => trim($value->uangkoperasi),
                "lamacuti" => trim($value->lamacuti),
                "total" => trim($value->total),
                "untuk" => trim($value->untuk),
                "keterangan" => trim($value->keterangan),
                "terbilang" => trim($value->terbilang),
                "masakerja" => trim($value->masakerja),
                "tglpisah" => trim($value->tglpisah),
                "type" => trim($value->type),
                "category" => trim($value->category),
                "selisih" => trim($value->selisih),
                "ket_selisih" => trim($value->ket_selisih),
                "haribaru" => trim($value->haribaru),
                "harilama" => trim($value->harilama),
                "datecreated" => trim($value->createdon)
            ]);
        }
        $data['dat'] = $dataTrimArray;
        
        return DataTables::of($data['dat'])
        ->addColumn('action', function($row){
            $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-nik="'.$row["nik"].'" data-idkwitansi="'.$row["idkwitansi"].'"><i class="fa fa-trash" aria-hidden="true"></i></a>';

            $reprintBtn = ' <a href="javascript:void(0)" class="reprint btn btn-success btn-sm" data-nik="'.$row["nik"].'" data-idkwitansi="'.$row["idkwitansi"].'"><i class="fa fa-print" aria-hidden="true"></i></a>';
            return $reprintBtn. $deleteBtn;
            
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function delete(Request $request)
    {   
        $deletedata = DB::connection('pgsql')->table('hris.t_kwitansi_backup')->where('nik', $request->id)->where('idkwitansi', $request->idkwitansi)->delete();
        if($deletedata == true){
            return redirect()->back()->with("success", "Data deleted successfully");
        } else {
            return redirect()->back()->with("error", "Failed");
        }
        
    }

    public function reprint(Request $request)
    {   
        $idkwitansi = $request->idkwitansi;
        $nik = $request->nik;

        $datakwitansi = DB::connection('pgsql')->table('hris.t_kwitansi_backup')->where('nik', $nik)->where('idkwitansi', $idkwitansi)->get();
     
        $datajson =  array(
                        "data" => array()
                    );
        if($datakwitansi == true){
            $counter = 0;
            foreach($datakwitansi as $key => $value){
                ++$counter;
                if($value->type == 3 ||  $value->type == 1.5 || $value->type == 0){
                    if($value->lamacuti == 0 ){
                        array_push($datajson["data"], [
                            "counter" => $counter,
                            "type" => trim($value->type),
                            "nokwitansi" => trim($value->idkwitansi),
                            "nama" => trim($value->namakaryawan),
                            "terimadari" => 'PT.LION WINGS, JAKARTA',
                            "nominal" => number_format($value->total,0,',','.').',-',
                            "terbilang" => $this->convertion->TERBILANG($value->selisih).' '.'RUPIAH',
                            "tanggal" =>  "Jakarta".", ".\Carbon\Carbon::today()->translatedFormat('d F Y'),
                            "keterangan" => "Selisih"." ".$value->untuk.PHP_EOL.$value->keterangan,
                            "lamakerja" => trim($value->masakerja),
                            "tglmasuk" => Carbon::parse(trim($value->tanggalmasuk))->translatedFormat('d F Y'),
                            "selisih" => number_format($value->selisih,0,',','.').',-',
                            "periode" => "Periode Cuti : ".Carbon::parse(trim($value->tanggalcuti))->translatedFormat('d F Y').' - '.Carbon::parse(trim(date($value->tanggalmasuk)))->subDays(1)->translatedFormat('d F Y')
                        ]);
                    } else {
                        array_push($datajson["data"], [
                            "counter" => $counter,
                            "type" => trim($value->type),
                            "nokwitansi" => trim($value->idkwitansi),
                            "nama" => trim($value->namakaryawan),
                            "terimadari" => 'PT.LION WINGS, JAKARTA',
                            "nominal" => number_format($value->total,0,',','.').',-',
                            "terbilang" => $this->convertion->TERBILANG($value->total).' '.'RUPIAH',
                            "tanggal" =>  "Jakarta".", ".\Carbon\Carbon::today()->translatedFormat('d F Y'),
                            "keterangan" => $value->untuk.PHP_EOL.$value->keterangan,
                            "lamakerja" => trim($value->masakerja),
                            "tglmasuk" => Carbon::parse(trim($value->tanggalmasuk))->translatedFormat('d F Y'),
                            "periode" => "Periode Cuti : ".Carbon::parse(trim($value->tanggalcuti))->translatedFormat('d F Y').' - '.Carbon::parse(trim(date($value->tanggalmasuk)))->subDays(1)->translatedFormat('d F Y')
                        ]);
                    }
                } else {
                    array_push($datajson["data"], [
                            "counter" => $counter,
                            "rapel" => "",
                            "type" => trim($value->type),
                            "nokwitansi" => trim($value->idkwitansi),
                            "nama" => trim($value->namakaryawan),
                            "terimadari" => 'PT.LION WINGS, JAKARTA',
                            "nominal" => number_format($value->total,0,',','.').',-',
                            "terbilang" => $this->convertion->TERBILANG($value->total).' '.'RUPIAH',
                            "tanggal" => "Jakarta".", ".\Carbon\Carbon::today()->translatedFormat('d F Y'),
                            "keterangan" => trim($value->keterangan),
                            "lamakerja" => trim($value->masakerja),
                            "tglpisah" => Carbon::parse(trim($value->tglpisah))->translatedFormat('d F Y')
                    ]);  
                }
            }
            $datajson = json_decode(json_encode($datajson));
            $data['alldata'] = $datajson->data;
        } else {
            $data = ['']; 
            
        }

        $pdf = PDF::setOptions(['isRemoteEnabled' => true])->loadview('receipt.templatekwitansi', $data)->setPaper('f4', 'portrait');
        return $pdf->stream('Print_Kwitansi' . date('dmYHis') . '.pdf');
    }
}
