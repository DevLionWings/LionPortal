<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use App\Helpers\Repository;
use App\Helpers\Convertion;
use App\Models\Masteremployee;
use App\Models\Mastertunjangan;
use DateTime;
use Carbon\Carbon;
use PDF;

class KwitansiController extends Controller
{
    public function __construct(Repository $repository, Response $response, Convertion $convertion)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->convertion = $convertion;
    }

    public function kwitansi(Request $request)
    {
        $isLogin = Session::get('status_login');
        if($isLogin != 1) {
            return redirect()->route('login-page');
        } 

        $cate = '';

        $mastertunjangan = DB::connection('pgsql')->table('master_data.m_tunjangan')->where('tunjanganid', "TJ001")->get();
    
        $dataArray = []; 
        foreach ($mastertunjangan as $key => $value) { 
            array_push($dataArray, [
                "ID" =>  $value->categoryid,
                "NAME" => $value->categorydescr
            ]);
        }
        $data['cate'] = $dataArray; 
        
        return view('fitur.kwitansi', $data);
    }

    public function getSimulasi(Request $request)
    {
        $type = $request->type;
        $tglpisah = $request->tglpisah;
        $id = $request->idkaryawan;
        $category = $request->category;
        
        if($type == "UP"){
            if(DB::connection('mysql2')->table('personalia.masteremployee')->where('Nip', $id)->exists()){
                $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee as a')
                                ->join('personalia.masterjabatan as b', 'a.Kode Jabatan', '=', 'b.Kode Jabatan')
                                ->where('a.Nip', $id)
                                ->where('a.Endda', '9998-12-31')
                                ->select('a.Nip','a.Nama', 'a.Tgl Masuk as tglmasuk', 'a.Gaji per Bulan as gaji', 'b.Tunjangan')
                                ->first();
                
                $nip = $datakaryawan->Nip;
                $nama = trim($datakaryawan->Nama);
                $tglmasuk = $datakaryawan->tglmasuk;
                $gaji = $datakaryawan->gaji; 
                $tunjangan = $datakaryawan->Tunjangan;
                /* Date Count */
                $datetime1 = new DateTime($tglmasuk);
                $datetime2 = new DateTime($tglpisah);
                $interval = $datetime1->diff($datetime2);
                $year = $interval->format('%y');
    
                if($year >= 1 && $year <= 3){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 1);
                    $month = 1;
                } else if ($year >= 3 && $year <= 6){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 2);
                    $month = 2;
                } else if ($year >= 6 && $year <= 9){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 3);
                    $month = 3;
                } else if ($year >= 9 && $year <= 12){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 4);
                    $month = 4;
                } else if ($year >= 12 && $year <= 15){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 5);
                    $month = 5;
                } else if ($year >= 15 && $year <= 18){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 6);
                    $month = 6;
                } else if ($year >= 18 && $year <= 21){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 7);
                    $month = 7;
                } else if ($year >= 21 && $year <= 24){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 8);
                    $month = 8;
                } else {
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 10);
                    $month = 10;
                }
                
                $formatrupiah = 'Rp.'.number_format($totalpisah,0,',','.');
                $lamakerja = $interval->format('%y Tahun %m Bulan %d Hari');
                $keterangan = 'Uang Pisah a/n '.$nama.','.'ID:'.$id.','.$gaji.'+'.$tunjangan.'*'.$month.'.';
    
                $dataArray = [];  
                $dataAll = array_push($dataArray, [
                            "NAME" => $nama,
                            "TGLMASUK" => $tglmasuk,
                            "TGLPISAH" => $tglpisah,
                            "GAJI" => $formatgaji,
                            "TOTAL" => $formatrupiah,
                            "LAMAKERJA" => $lamakerja,
                            "KETERANGAN" => $keterangan
        
                        ]);
    
                return $dataArray;
            } else {
                return $dataArray = [''];
            }
        } else {
            $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee')
                ->where('Nip', $id)
                ->where('Endda', '9998-12-31')
                ->select('Nip','Nama')
                ->first();

            if($type == "TJ001"){
                $mastertunjangan = DB::connection('pgsql')->table('master_data.m_tunjangan')
                    ->where('tunjanganid', $type)
                    ->where('categoryid', $category)
                    ->first();
            } else { 
                $mastertunjangan = DB::connection('pgsql')->table('master_data.m_tunjangan')
                    ->where('tunjanganid', $type)
                    ->first();
            }
            $nip = $datakaryawan->Nip;
            $nama = trim($datakaryawan->Nama);
            $categoryname = trim($mastertunjangan->categorydescr);
            $nominal = 'Rp.'.number_format($mastertunjangan->value,0,',','.');

            if($type == "TJ001"){
                $keterangan = 'Uang Tunjangan Duka Cita atas Meninggalnya '.$categoryname.' dari '.$nama.','.' ID:'.$id;
            } else if($type == "TJ002"){
                $keterangan = 'Uang Tunjangan Pernikahan a/n '.$nama.','.' ID:'.$id;
            } else {
                $keterangan = 'Uang Tunjangan Kelahiran Anak ke-1 dari: '.$nama.','.' ID:'.$id;
            }
        
            $dataArray = [];  
            $dataAll = array_push($dataArray, [
                    "NAME" => $nama,
                    "TGLMASUK" => '',
                    "GAJI" => '',
                    "TOTAL" => $nominal,
                    "LAMAKERJA" => '',
                    "KETERANGAN" => $keterangan

                ]);

            return $dataArray;
        }
        
    }

    public function print(Request $request)
    {
        $type = $request->opsi;
        $tglpisah = $request->tglpisah;
        $id = $request->idkaryawan;
        $category = $request->category;
        $keterangan = $request->keterangan;
        $total = $request->total;
        $masakerja = $request->masakerja;
        $nominal = $request->total.',-';
        $exp = explode('.', $total);
        $imp = implode('', $exp);
        $terbilang = $this->convertion->TERBILANG($imp).' '.'RUPIAH';
        $format = date('l, d F Y');
        $year = date('Y');
        $month = date('M');
        $numb = 0000 + 1;
        $nokwitansi = 'No.'.'000'.$numb.'/LW'.'/'.$month.'/'.$year;

        $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee')
            ->where('Nip', $id)
            ->where('Endda', '9998-12-31')
            ->select('Nip','Nama')
            ->first();

        $data = array(
            "rapel" => "",
            "type" => $type,
            "nokwitansi" => $nokwitansi,
            "nama" => trim($datakaryawan->Nama),
            "terimadari" => 'PT LION WINGS, JAKARTA',
            "nominal" => $nominal,
            "terbilang" => $terbilang,
            "tanggal" => $format,
            "keterangan" => $keterangan,
            "lamakerja" => $masakerja,
            "tglpisah" => $tglpisah
        );
        
        $pdf = PDF::loadview('receipt.templatekwitansi', $data)->setPaper('F4', 'portrait');
        return $pdf->stream('Print_Kwitansi' . date('dmYHis') . '.pdf');
    }
}
