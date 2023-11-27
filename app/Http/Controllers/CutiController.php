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
use App\Models\Masterbagian;
use App\Models\Counter;
use Carbon\Carbon;
use DateTime;
use PDF;

class CutiController extends Controller
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
        
        return view('fitur.kwitansicuti');
    }

    public function getSimulasi(Request $request)
    {
        $type = $request->type;
        $typecuti = $request->typecuti;
        $lamacuti = $request->lamacuti;
        $jmlhchh = $request->jmlhchh;
        $id = $request->idkaryawan;
        $idrapel = $request->idrapel;
        $uangmakanReq = $request->uangmakan;
        $spsiReq = $request->spsi;
        $koperasiReq = $request->koperasi;
        $bpjs = $request->bpjs;
        $totalAmount = $request->totalamount;
        $newCountDate = $request->amountDay1;
        $selisih = $request->selisih;
        $bpjs = $request->bpjs;
        $bulangajilama = $request->bulangajilama;
        $bulangajibaru = $request->bulangajibaru;
     
        if(DB::connection('mysql2')->table('personalia.masteremployee')->where('Nip', $id)->exists()){
            $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee as a')
                            ->join('personalia.masterjabatan as b', 'a.Kode Jabatan', '=', 'b.Kode Jabatan')
                            ->join('personalia.masterbagian as c', 'c.Kode Bagian', '=', 'a.Kode Bagian')
                            ->where('a.Nip', $id)
                            ->where('a.Endda', '9998-12-31')
                            ->select('a.Nip','a.Nama', 'a.Tgl Masuk as tglmasuk', 'a.Gaji per Bulan as gaji', 'b.Tunjangan', 'c.Nama Bagian as bagian')
                            ->first();
            
            $datakaryawan2 = DB::connection('pgsql')->table('hris.t_karyawan')->where('idsmu', $id)->first();
            if($datakaryawan2 == null){
                $data = 'notfound';
                return $data;
            }

            if(DB::connection('pgsql')->table('hris.t_kwitansi_backup')->where('nik', $id)->exists()){
                $datahistory = DB::connection('pgsql')->table('hris.t_kwitansi_backup')->where('nik', $id)->first();
                $gajihistory = $datahistory->gaji;
                $gajiSehariHist = $datahistory->gaji / 30;
            } else {
                $gajihistory = 0;
                $gajiSehariHist = 0;
            }
        
            $uangmakan = '20000';
            $spsi = $datakaryawan2->spsi; 
            $koperasi = $datakaryawan2->koperasi;
        
            $nip = $datakaryawan->Nip;
            $nama = trim($datakaryawan->Nama);
            $tglmasuk = $datakaryawan->tglmasuk;
            $gaji = $datakaryawan->gaji; 
            $gajisehari = $datakaryawan->gaji / 30;
            $gajiUm = $gajisehari - $uangmakan;
            $gajiUmHist = $gajiSehariHist - $uangmakan;
            $formatGaji = 'Rp.'.number_format($gaji,0,',','.');
            $tunjangan = $datakaryawan->Tunjangan;
            $formatTj = 'Rp.'.number_format($tunjangan,0,',','.');
            $jamsostek = round($gaji * 2/100);
            $formatJamsostek = 'Rp.'.number_format($jamsostek,0,',','.');
            $bagian = $datakaryawan->bagian;
            $item = ($gajiUm + $uangmakan) * 30;
            $itemHistory = ($gajiUmHist + $uangmakan) * 30;

            /* Formula CHH */
            if($jmlhchh == 1){
                $chh = ($gaji / 30 ) * 2;
            } else if ( $jmlhchh == 2){
                $chh = ($gaji / 30 ) * 4;
            } else if ($jmlhchh == 3){
                $chh = ($gaji / 30) * 6;
            } else {
                $chh = $jmlhchh;
            }
            /* End */
            if($type == "0"){
                if($typecuti == "3"){
                    /* Count Months */
                    $tglcuti = $request->tglcuti;
                    $date = new DateTime($tglcuti);
                    $date->modify('+90 day'); 
                    $tglmasuk = $date->format('Y-m-d');

                    $subs1 = substr($tglmasuk, 0, 4);
                    $subs2 = substr($tglcuti, 0, 4);
                    $monthItem = substr($tglmasuk, 9, 4);

                    $newDate = date($subs2.'-'.'12-31');
                        
                    $formatted_dt1=Carbon::parse($tglmasuk);
                    $formatted_dt2=Carbon::parse($tglcuti); 
                    
                    if($monthItem == 1){
                        $monthNew = $monthItem;
                        $monthOld = 3 - $monthItem;
                    } else if ($monthItem == 2){
                        $monthNew = $monthItem;
                        $monthOld = 3 - $monthItem;
                    } else {
                        $monthNew = 0;
                        $monthOld = 3;
                    }
                    $diffInDaysOld = $formatted_dt2->diffInDays($newDate) + 1;
                    $newDiff = 90 - $diffInDaysOld;
                    $diffInDays = $formatted_dt1->diffInDays($formatted_dt2);
                    $diffInMonths = $formatted_dt2->diffInMonths($formatted_dt1);

                    $gajiBaru = ($newDiff * $gajisehari);
                    $gajiLama = ($diffInDaysOld * $gajiSehariHist);
                    $gajiBaruLama = $gajiBaru + $gajiLama;
                    if($bpjs == 1) {
                        /* Kwitansi lama Item */
                        $gajiLamaAwal = round((90 * $gajiSehariHist));
                        $jstkLama = round(($gajiSehariHist * 30) * 2/100 * 3);
                        $jhtLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $bpjsLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $totalGajiLama = round($gajiLamaAwal - $jstkLama - $jhtLama - $bpjsLama);

                        $ketjstkLama = round(($gajiSehariHist * 30) * 2/100);
                        $ketjhtLama =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsLama =  round(($gajiSehariHist * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.'90'.'*'.$gajiSehariHist.')'.' - '.'('.'3* '.$ketjstkLama.')'.' - '.'('.'3* '.$ketjhtLama.')'.' - '.'('.'3* '.$ketjhtLama.')';
                        $ketLama = $keterangan;
                        /* Kwitansi baru Item */
                        $jstk = round(($gajiSehariHist * 30) * 2/100 * 2);
                        $jstkBaru = round(($gajisehari * 30) * 2/100 * 1);
                        $jht =  round(($gajiSehariHist * 30) * 1/100 * 2);
                        $jhtBaru =  round(($gajisehari * 30) * 1/100 * 1);
                        $bpjs =  round(($gajiSehariHist * 30) * 1/100 * 2);
                        $bpjsBaru =  round(($gajisehari * 30) * 1/100 * 1);
                        $totalGajiBaru = round($gajiBaruLama - $jstk - $jstkBaru - $jht - $jhtBaru - $bpjs - $bpjsBaru);

                        $ketjstk = round(($gajiSehariHist * 30) * 2/100);
                        $ketjstkbaru = round(($gajisehari * 30) * 2/100);
                        $ketjht =  round(($gajiSehariHist * 30) * 1/100);
                        $ketjhtBaru =  round(($gajisehari * 30) * 1/100 );
                        $ketbpjs =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsBaru =  round(($gajisehari * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.$newDiff.'*'.$gajisehari.'+'.$diffInDaysOld.'*'.$gajiSehariHist.')'.' - '.'('.'2'.'*'.$ketjstk.'+'.'1'.'*'.$ketjstkbaru.')'.' - '.'('.'2'.'*'.$ketbpjs.'+'.'1'.'*'.$ketbpjsBaru.')';
                        $ketBaru = $keterangan;
                        /* End kwitansi */ 
                    } else {
                        /* Kwitansi lama Item */
                        $gajiLamaAwal = round((90 * $gajiSehariHist));
                        $jstkLama = round(($gajiSehariHist * 30) * 2/100 * 3);
                        $jhtLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $bpjsLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $totalGajiLama = round($gajiLamaAwal - $jstkLama - $jhtLama - $bpjsLama);

                        $ketjstkLama = round(($gajiSehariHist * 30) * 2/100);
                        $ketjhtLama =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsLama =  round(($gajiSehariHist * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.'90'.'*'.$gajiSehariHist.')'.' - '.'('.'3* '.$ketjstkLama.')'.' - '.'('.'3* '.$ketjhtLama.')'.' - '.'('.'3* '.$ketjhtLama.')';
                        $ketLama = $keterangan;
                        /* Kwitansi baru Item */

                        $jstkLama = round(($gajiSehariHist * 30) * 2/100 * 3);
                        $jhtLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $bpjsLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $totalGajiBaru = round($gajiBaruLama - $jstkLama - $jhtLama - $bpjsLama);
                        
                        
                        $ketjstkLama = round(($gajiSehariHist * 30) * 2/100);
                        $ketjhtLama =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsLama =  round(($gajiSehariHist * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.$newDiff.'*'.$gajisehari.'+'.$diffInDaysOld.'*'.$gajiSehariHist.')'.' - '.'('.'3* '.$ketjstkLama.')'.' - '.'('.'3* '.$ketjhtLama.')'.' - '.'('.'3* '.$ketjhtLama.')';
                        $ketBaru = $keterangan;
                        /* End kwitansi */ 
                    }
                
                    $ket = $untuk.PHP_EOL.$keterangan;

                    $totalSelisih = $totalGajiBaru - $totalGajiLama;
                    $formatSelisih = 'Rp.'.number_format($totalSelisih,0,',','.');
                } else {
                    /* Count Months */
                    $tglcuti = $request->tglcuti;
                    $date = new DateTime($tglcuti);
                    $date->modify('+45 day'); 
                    $tglmasuk = $date->format('Y-m-d');

                    $subs1 = substr($tglmasuk, 0, 4);
                    $subs2 = substr($tglcuti, 0, 4);
                    $monthItem = substr($tglmasuk, 9, 4);

                    $newDate = date($subs2.'-'.'12-31');
                        
                    $formatted_dt1=Carbon::parse($tglmasuk);
                    $formatted_dt2=Carbon::parse($tglcuti); 
        
                    if($monthItem == 1){
                        $monthNew = $monthItem;
                        $monthOld = 3 - $monthItem;
                    } else if ($monthItem == 2){
                        $monthNew = $monthItem;
                        $monthOld = 3 - $monthItem;
                    } else {
                        $monthNew = 0;
                        $monthOld = 3;
                    }
                    $diffInDaysOld = $formatted_dt2->diffInDays($newDate) + 1;
                    $newDiff = 45 - $diffInDaysOld;
                    $diffInDays = $formatted_dt1->diffInDays($formatted_dt2);
                    $diffInMonths = $formatted_dt2->diffInMonths($formatted_dt1);

                    $gajiBaru = ($newDiff * $gajisehari);
                    $gajiLama = ($diffInDaysOld * $gajiSehariHist);
                    $gajiBaruLama = $gajiBaru + $gajiLama;
                    /* Kwitansi lama Item */
                    $gajiLamaAwal = round((45 * $gajiSehariHist));
                    $jstkLama = round(($gajiSehariHist * 30) * 2/100 * 2);
                    $jhtLama =  round(($gajiSehariHist * 30) * 1/100 * 2);
                    $bpjsLama =  round(($gajiSehariHist * 30) * 1/100 * 2);
                    $totalGajiLama = round($gajiLamaAwal - $jstkLama - $jhtLama - $bpjsLama);

                    $ketjstkLama = round(($gajiSehariHist * 30) * 2/100);
                    $ketjhtLama =  round(($gajiSehariHist * 30) * 1/100);
                    $ketbpjsLama =  round(($gajiSehariHist * 30) * 1/100);
                    $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                    $keterangan = $gajiLamaAwal.' - '.'('.'3* '.$ketjstkLama.')'.' - '.'('.'3* '.$ketjhtLama.')'.' - '.'('.'3* '.$ketjhtLama.')';
                    $ketLama = $untuk.PHP_EOL.$keterangan;
                    /* Kwitansi baru Item */
                    $jstk = round(($gajiSehariHist * 30) * 2/100 * $monthOld);
                    $jstkBaru = round(($gajisehari * 30) * 2/100 * $monthNew);
                    $jht =  round(($gajiSehariHist * 30) * 1/100 * $monthOld);
                    $jhtBaru =  round(($gajisehari * 30) * 1/100 * $monthNew);
                    $bpjs =  round(($gajiSehariHist * 30) * 1/100 * $monthOld);
                    $bpjsBaru =  round(($gajisehari * 30) * 1/100 * $monthNew);
                    $totalGajiBaru = round($gajiBaruLama - $jstk - $jstkBaru - $jht - $jhtBaru - $bpjs - $bpjsBaru);

                    $ketjstk = round(($gajiSehariHist * 30) * 2/100);
                    $ketjstkbaru = round(($gajisehari * 30) * 2/100);
                    $ketjht =  round(($gajiSehariHist * 30) * 1/100);
                    $ketjhtBaru =  round(($gajisehari * 30) * 1/100 );
                    $ketbpjs =  round(($gajiSehariHist * 30) * 1/100);
                    $ketbpjsBaru =  round(($gajisehari * 30) * 1/100);
            
                    $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                    $keterangan = '('.$newDiff.'*'.$gajisehari.'+'.$diffInDaysOld.'*'.$gajiSehariHist.')'.' - '.'('.$bulangajilama.'*'.$ketjstk.'+'.$bulangajibaru.'*'.$ketjstkbaru.')'.' - '.'('.$bulangajilama.'*'.$ketbpjs.'+'.$bulangajibaru.'*'.$ketbpjsBaru.')';
                    $ketBaru = $untuk.PHP_EOL.$keterangan;

                    if($bpjs == 1) {
                        /* Kwitansi lama Item */
                        $gajiLamaAwal = round((90 * $gajiSehariHist));
                        $jstkLama = round(($gajiSehariHist * 30) * 2/100 * 3);
                        $jhtLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $bpjsLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $totalGajiLama = round($gajiLamaAwal - $jstkLama - $jhtLama - $bpjsLama);

                        $ketjstkLama = round(($gajiSehariHist * 30) * 2/100);
                        $ketjhtLama =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsLama =  round(($gajiSehariHist * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.'45'.'*'.$gajiSehariHist.')'.' - '.'('.'3* '.$ketjstkLama.')'.' - '.'('.'3* '.$ketjhtLama.')'.' - '.'('.'3* '.$ketjhtLama.')';
                        $ketLama = $keterangan;
                        /* Kwitansi baru Item */
                        $jstk = round(($gajiSehariHist * 30) * 2/100 * 2);
                        $jstkBaru = round(($gajisehari * 30) * 2/100 * 1);
                        $jht =  round(($gajiSehariHist * 30) * 1/100 * 2);
                        $jhtBaru =  round(($gajisehari * 30) * 1/100 * 1);
                        $bpjs =  round(($gajiSehariHist * 30) * 1/100 * 2);
                        $bpjsBaru =  round(($gajisehari * 30) * 1/100 * 1);
                        $totalGajiBaru = round($gajiBaruLama - $jstk - $jstkBaru - $jht - $jhtBaru - $bpjs - $bpjsBaru);

                        $ketjstk = round(($gajiSehariHist * 30) * 2/100);
                        $ketjstkbaru = round(($gajisehari * 30) * 2/100);
                        $ketjht =  round(($gajiSehariHist * 30) * 1/100);
                        $ketjhtBaru =  round(($gajisehari * 30) * 1/100 );
                        $ketbpjs =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsBaru =  round(($gajisehari * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.$newDiff.'*'.$gajisehari.'+'.$diffInDaysOld.'*'.$gajiSehariHist.')'.' - '.'('.'2'.'*'.$ketjstk.'+'.'1'.'*'.$ketjstkbaru.')'.' - '.'('.'2'.'*'.$ketbpjs.'+'.'1'.'*'.$ketbpjsBaru.')';
                        $ketBaru = $keterangan;
                        /* End kwitansi */ 
                    } else {
                        /* Kwitansi lama Item */
                        $gajiLamaAwal = round((90 * $gajiSehariHist));
                        $jstkLama = round(($gajiSehariHist * 30) * 2/100 * 3);
                        $jhtLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $bpjsLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $totalGajiLama = round($gajiLamaAwal - $jstkLama - $jhtLama - $bpjsLama);

                        $ketjstkLama = round(($gajiSehariHist * 30) * 2/100);
                        $ketjhtLama =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsLama =  round(($gajiSehariHist * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.'90'.'*'.$gajiSehariHist.')'.' - '.'('.'3* '.$ketjstkLama.')'.' - '.'('.'3* '.$ketjhtLama.')'.' - '.'('.'3* '.$ketjhtLama.')';
                        $ketLama = $keterangan;
                        /* Kwitansi baru Item */

                        $jstkLama = round(($gajiSehariHist * 30) * 2/100 * 3);
                        $jhtLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $bpjsLama =  round(($gajiSehariHist * 30) * 1/100 * 3);
                        $totalGajiBaru = round($gajiBaruLama - $jstkLama - $jhtLama - $bpjsLama);
                        
                        
                        $ketjstkLama = round(($gajiSehariHist * 30) * 2/100);
                        $ketjhtLama =  round(($gajiSehariHist * 30) * 1/100);
                        $ketbpjsLama =  round(($gajiSehariHist * 30) * 1/100);
                        $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                        $keterangan = '('.$newDiff.'*'.$gajisehari.'+'.$diffInDaysOld.'*'.$gajiSehariHist.')'.' - '.'('.'3* '.$ketjstkLama.')'.' - '.'('.'3* '.$ketjhtLama.')'.' - '.'('.'3* '.$ketjhtLama.')';
                        $ketBaru = $keterangan;
                        /* End kwitansi */ 
                    }

                    $ket = $untuk.PHP_EOL.$keterangan;

                    $totalSelisih = $totalGajiBaru - $totalGajiLama;
                    $formatSelisih = 'Rp.'.number_format($totalSelisih,0,',','.');
                }
            } else if($type == "3"){
                $hitung1 = 3 * $item;
                $hitung2 = 3 * $jamsostek;
                $hitung3 = $chh; 
                $hitung4 = 3 * $spsi;
                $hitung5 = 3 * $koperasi;
                $hitung6 = round(3 * ($gaji * 2/100));
                $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                $keterangan = "3x(GP + UM) - 3x(JHT) - 3x(SPSI) - 3x(Koperasi) - 3x(Bpjs + Pensiun) - ".$jmlhchh."x(Cuti Haid)". PHP_EOL .$hitung1.' - '.$hitung2.' - '.$hitung3.' - '.$hitung4.' - '.$hitung5.' - '.$hitung6;
                $ket = $untuk.PHP_EOL.$keterangan;
                
                /* Count Months */
                $tglcuti = $request->tglcuti;
                $date = new DateTime($tglcuti);
                $date->modify('+90 day'); 
                $tglmasuk = $date->format('Y-m-d');

                $subs1 = substr($tglmasuk, 0, 4);
                $subs2 = substr($tglcuti, 0, 4);
                
                if($subs1 == $subs2){
                    $diffInDays = "0";
                    $diffInMonths = "0";
                    $diffInDaysOld = "0";
                    $newDiff = "0";
                    $selisihGajiLama = "0";
                    $selisihGajiBaru = "0";
                    $gajiLama = "0";
                    $gajiBaru = "0";
                    $ketLama = "";
                    $ketBaru = "";
                    $totalSelisih = "0";
                    $formatSelisih = 'Rp.'.number_format(0,0,',','.');
                    $gajiLamaAwal = "0";
                    $gajiBaruLama = "0";
                    $ketLama = "0";
                    $ketBaru = "0";
                    $totalGajiLama = "0";
                    $totalGajiBaru = "0";
                    $monthOld = "0";
                    $monthNew = "0";
                } else {
                    $newDate = date($subs2.'-'.'12-31');
                    $formatted_dt1=Carbon::parse($tglmasuk);
                    $formatted_dt2=Carbon::parse($tglcuti); 
                    $diffInDaysOld = $formatted_dt2->diffInDays($newDate);
                    $newDiff = 90 - $diffInDaysOld;
                    $diffInDays = $formatted_dt1->diffInDays($formatted_dt2);
                    $diffInMonths = $formatted_dt2->diffInMonths($formatted_dt1);
                    $gajiLama = ($diffInDaysOld * $gajiSehariHist);
                    $gajiBaru = ($newDiff * $gajisehari);$ketLama = $untuk.PHP_EOL.$keterangan;
                    $ketLama = "";
                    $ketBaru = "";
                    $totalSelisih = "0";
                    $formatSelisih = 'Rp.'.number_format(0,0,',','.');
                    $gajiLamaAwal = "0";
                    $gajiBaruLama = "0";
                    $ketLama = "0";
                    $ketBaru = "0";
                    $totalGajiLama = "0";
                    $totalGajiBaru = "0";
                    $monthOld = "0";
                    $monthNew = "0";
                } 
            } else {
                $hitung1 = 1.5 * $item;
                $hitung2 = 2 * $jamsostek;
                $hitung3 = $chh; 
                $hitung4 = 2 * $spsi;
                $hitung5 = 2 * $koperasi;
                $hitung6 = round(2 * ($gaji * 2/100));
                $untuk = "Uang Pengganti Cuti Keguguran a/n ".$nama.' bagian: '.$bagian.' id: '.$id;
                $keterangan = "1.5x(GP + UM) - 2x(JHT) - (Cuti Haid) - 2x(SPSI) - 2x(Koperasi) - 2x(Bpjs + Pensiun) - ".$jmlhchh."x(Cuti Haid)". PHP_EOL . $hitung1.' - '.$hitung2.' - '.$hitung3.' - '.$hitung4.' - '.$hitung5.' - '.$hitung6;
                $ket = $untuk.PHP_EOL.$keterangan;
                
                /* Count Months */
                $tglcuti = $request->tglcuti;
                $date = new DateTime($tglcuti);
                $date->modify('+45 day'); 
                $tglmasuk = $date->format('Y-m-d');
                $subs1 = substr($tglmasuk, 0, 4);
                $subs2 = substr($tglcuti, 0, 4);
 
                if($subs1 == $subs2){
                    $diffInDays = "0";
                    $diffInMonths = "0";
                    $diffInDaysOld = "0";
                    $newDiff = "0";
                    $selisihGajiLama = "0";
                    $selisihGajiBaru = "0";
                    $gajiLama = "0";
                    $gajiBaru = "0";
                    $ketLama = "";
                    $ketBaru = "";
                    $totalSelisih = "0";
                    $formatSelisih = 'Rp.'.number_format(0,0,',','.');
                    $gajiLamaAwal = "0";
                    $gajiBaruLama = "0";
                    $ketLama = "0";
                    $ketBaru = "0";
                    $totalGajiLama = "0";
                    $totalGajiBaru = "0";
                    $monthOld = "0";
                    $monthNew = "0";
                } else {
                    $newDate = date($subs2.'-'.'12-31');
                    $formatted_dt1=Carbon::parse($tglmasuk);
                    $formatted_dt2=Carbon::parse($tglcuti); 
                    $diffInDaysOld = $formatted_dt2->diffInDays($newDate);
                    $newDiff = 45 - $diffInDaysOld;
                    $diffInDays = $formatted_dt1->diffInDays($formatted_dt2);
                    $diffInMonths = $formatted_dt2->diffInMonths($formatted_dt1);
                    $gajiLama = ($diffInDaysOld * $gajiSehariHist);
                    $gajiBaru = ($newDiff * $gajisehari);
                    $ketLama = "";
                    $ketBaru = "";
                    $totalSelisih = "0";
                    $formatSelisih = 'Rp.'.number_format(0,0,',','.');
                    $gajiLamaAwal = "0";
                    $gajiBaruLama = "0";
                    $ketLama = "0";
                    $ketBaru = "0";
                    $totalGajiLama = "0";
                    $totalGajiBaru = "0";
                    $monthOld = "0";
                    $monthNew = "0";
                } 
            }
            if($type == "0"){
                $total = round($totalGajiBaru);
                $formatTotal = 'Rp.'.number_format($total,0,',','.');
                $terbilang =  $this->convertion->TERBILANG($total).' '.'RUPIAH';
            } else {
                $total = round($hitung1 - $hitung2 - $hitung3 - $hitung4 - $hitung5 - $hitung6);
                $formatTotal = 'Rp.'.number_format($total,0,',','.');
                $terbilang =  $this->convertion->TERBILANG($total).' '.'RUPIAH';
            }
            
            $dataArray = [];  
            $dataAll = array_push($dataArray, [
                        "NAME" => $nama,
                        "BAGIAN" => $bagian,
                        "GAJI" => $formatGaji,
                        "TGLMASUK" => $tglmasuk,
                        "TGLCUTI" => $tglcuti,
                        "JABATAN" => $formatTj,
                        "UANGMAKAN" => $uangmakan,
                        "JAMSOSTEK" => $formatJamsostek,
                        "SPSI" => $spsi,
                        "KOPERASI" => $koperasi,
                        "UNTUK" => $untuk,
                        "KETERANGAN" => $keterangan,
                        "KETSELISIH" => $keterangan,
                        "TOTAL" => $formatTotal,
                        "TERBILANG" => $terbilang,
                        "SELISIH" => $formatSelisih,
                        "HARI LAMA" => $diffInDaysOld,
                        "HARI BARU" => $newDiff,
                        "BULAN" => $diffInMonths,
                        "COUNT" => '0',
                        "GAJILAMA" => number_format($totalGajiLama,0,',','.'),
                        "GAJIBARU" => number_format($totalGajiBaru,0,',','.'),
                        "KETERANGANGAJILAMA" => $ketLama,
                        "KETERANGANGAJIBARU" => $ketBaru,
                        "BULANLAMA" => $monthOld,
                        "BULANBARU" => $monthNew,
                    ]);

            return $dataArray;
        } else {
            $dataArray = [''];
        }
    }

    public function print(Request $request)
    {
        $datakwitansi = DB::connection('pgsql')->table('hris.t_kwitansi')->get();
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
