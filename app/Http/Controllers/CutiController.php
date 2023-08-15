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
        $lamacuti = $request->lamacuti;
        $jmlhchh = $request->jmlhchh;
        $id = $request->idkaryawan;
        $uangmakanReq = $request->uangmakan;
        $spsiReq = $request->spsi;
        $koperasiReq = $request->koperasi;
        $bpjs = $request->bpjs;
        $exp = explode(".",$request->amountrapel);
        $rapelAmount = implode("", $exp);
        $totalAmount = $request->totalamount;
        $newCountDate = $request->amountDay1;
        $selisih = $request->selisih;
        $bpjs = $request->bpjs;
     
        if(DB::connection('mysql2')->table('personalia.masteremployee')->where('Nip', $id)->exists()){
            $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee as a')
                            ->join('personalia.masterjabatan as b', 'a.Kode Jabatan', '=', 'b.Kode Jabatan')
                            ->join('personalia.masterbagian as c', 'a.Kode Bagian', '=', 'b.Kode Bagian')
                            ->where('a.Nip', $id)
                            ->where('a.Endda', '9998-12-31')
                            ->select('a.Nip','a.Nama', 'a.Tgl Masuk as tglmasuk', 'a.Gaji per Bulan as gaji', 'b.Tunjangan', 'c.Nama Bagian as bagian')
                            ->first();
                        
            // $mastertunjangan = DB::connection('pgsql')->table('master_data.m_tunjangan')
            //     ->where('categoryid', 'CA004')
            //     ->get();
        
            // $namaTj = $mastertunjangan->categorydescr;
            // $amountTj = $mastertunjangan->value;
           
            if (empty($uangmakanReq) && empty($spsiReq) && empty($koperasiReq)){
                $uangmakan = '20000';  
                $spsi = '27000'; 
                $koperasi = '20000';
            } else {
                $uangmakan = $uangmakanReq;
                $spsi = $spsiReq; 
                $koperasi = $koperasiReq;
                // $subs = substr($uangmakanReq, 3, 6);
                // $exp = explode('.', $subs);
                // $uangmakan = implode('', $exp);
                // $subs = substr($spsiReq, 3, 6);
                // $spsi = explode('.', $subs);
                // $subs = substr($koperasiReq, 3, 6);
                // $koperasi = explode('.', $subs);
            }
            
            // $formatUm = 'Rp.'.number_format($uangmakan,0,',','.');
            // $formatSpsi = 'Rp.'.number_format($spsi,0,',','.');
            // $formatKoperasi = 'Rp.'.number_format($koperasi,0,',','.');
          
            $nip = $datakaryawan->Nip;
            $nama = trim($datakaryawan->Nama);
            $tglmasuk = $datakaryawan->tglmasuk;
            $gaji = $datakaryawan->gaji; 
            $gajisehari = $datakaryawan->gaji / 30;
            $gajiUm = $gajisehari - $uangmakan;
            $formatGaji = 'Rp.'.number_format($gaji,0,',','.');
            $tunjangan = $datakaryawan->Tunjangan;
            $formatTj = 'Rp.'.number_format($tunjangan,0,',','.');
            $jamsostek = round($gaji * 2/100);
            $formatJamsostek = 'Rp.'.number_format($jamsostek,0,',','.');
            $bagian = $datakaryawan->bagian;
            $item = ($gajiUm + $uangmakan) * 30;

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
            
            if($type == "3"){
                $hitung1 = 3 * $item;
                $hitung2 = 3 * $jamsostek;
                $hitung3 = $chh; 
                $hitung4 = 3 * $spsi;
                $hitung5 = 3 * $koperasi;
                $hitung6 = 3 * ($gaji * 2/100);
                $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id.',';
                $keterangan = "3x(GP + UM) - 3x(JHT) - (Cuti Haid) - 3x(SPSI) - 3x(Koperasi) - 3x(Bpjs + Pensiun) : ". $hitung1.' - '.$hitung2.' - '.$hitung3.' - '.$hitung4.' - '.$hitung5.' - '.$hitung6;
                /* Count Months */
                $tglcuti = $request->tglcuti;
                $date = new DateTime($tglcuti);
                $date->modify('+90 day'); 
                $tglmasuk = $date->format('Y-m-d');

                $subs1 = substr($tglmasuk, 0, 4);
                $subs2 = substr($tglcuti, 0, 4);
                // $subs3 = substr($tglcuti, 5, 2);
                
                if($subs1 == $subs2){
                    $diffInDays = "0";
                    $diffInMonths = "0";
                    $diffInDaysOld = "0";
                    $newDiff = "0";
                } else {
                    // $countdate = 12 - $subs3;
                    // $formatSubs3 = Carbon::parse($tglcuti)->addmonth($countdate)->format('Y-m-d');
                    // $subs = substr($tglcuti, 5, 2);
                    $newDate = date($subs2.'-'.'12-31');
                    
                    $formatted_dt1=Carbon::parse($tglmasuk);
                    $formatted_dt2=Carbon::parse($tglcuti); 

                    $diffInDaysOld = $formatted_dt2->diffInDays($newDate);
                    $newDiff = 90 - $diffInDaysOld;
                    $diffInDays = $formatted_dt1->diffInDays($formatted_dt2);
                    $diffInMonths = $formatted_dt2->diffInMonths($formatted_dt1);
                } 
            } else {
                $hitung1 = 1.5 * $item;
                $hitung2 = 2 * $jamsostek;
                $hitung3 = $chh; 
                $hitung4 = 2 * $spsi;
                $hitung5 = 2 * $koperasi;
                $hitung6 = 2 * ($gaji * 2/100);
                $untuk = "Uang Pengganti Cuti Keguguran a/n ".$nama.' bagian: '.$bagian.' id: '.$id.',';
                $keterangan = "1.5x(GP + UM) - 2x(JHT) - (Cuti Haid) - 2x(SPSI) - 2x(Koperasi) - 2x(Bpjs + Pensiun) :". $hitung1.' - '.$hitung2.' - '.$hitung3.' - '.$hitung4.' - '.$hitung5.' - '.$hitung6;
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
                } else {
                    // $countdate = 12 - $subs3;
                    // $formatSubs3 = Carbon::parse($tglcuti)->addmonth($countdate)->format('Y-m-d');
                    // $subs = substr($tglcuti, 5, 2);
                    $newDate = date($subs2.'-'.'12-31');
                    
                    $formatted_dt1=Carbon::parse($tglmasuk);
                    $formatted_dt2=Carbon::parse($tglcuti); 

                    $diffInDaysOld = $formatted_dt2->diffInDays($newDate);
                    $newDiff = 45 - $diffInDaysOld;
                    $diffInDays = $formatted_dt1->diffInDays($formatted_dt2);
                    $diffInMonths = $formatted_dt2->diffInMonths($formatted_dt1);
                } 
            }
            $total = round($hitung1 - $hitung2 - $hitung3 - $hitung4 - $hitung5 - $hitung6);
            $formatTotal = 'Rp.'.number_format($total,0,',','.');
            $terbilang =  $this->convertion->TERBILANG($total).' '.'RUPIAH';
            /* Formula Rapel */
            if(!empty($rapelAmount)){
                $countSelisih = round((($total - $rapelAmount) / 90) * $diffInDaysOld);
            } else {
                $countSelisih = '0';
            }
            $formatSelisih = 'Rp.'.number_format($countSelisih,0,',','.');
        
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
                        "COUNT" => $countSelisih
                    ]);

            return $dataArray;
        } else {
            $dataArray = [''];
        }
    }

    public function print(Request $request)
    {
        
        $type = $request->opsi;
        $tglpisah = $request->tglpisah;
        $tglmasuk = $request->tglmasuk;
        $id = $request->idkaryawan;
        $category = $request->category;
        $keterangan = $request->keterangan;
        $total = $request->total;
        $to = $request->to;
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
        $selisih = $request->selisih;
        $subs = substr($selisih, 3, 30);
        $exp = explode('.', $subs);
        $convSelisih = implode('', $exp);
        $terbilangSelisih = $this->convertion->TERBILANG($convSelisih).' '.'RUPIAH';
        $rapel = $request->rapel;

        $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee')
            ->where('Nip', $id)
            ->where('Endda', '9998-12-31')
            ->select('Nip','Nama')
            ->first();

        $datakwintansi = DB::connection('pgsql')->table('master_data.m_counter')
            ->where('counterid', 'CT003')
            ->where('prefix', 'KWS')
            ->select('period','start_number', 'end_number', 'last_number')
            ->first();
        
        $format = \Carbon\Carbon::today()->translatedFormat('l, d F Y');
        $year = date('Y');
        $month = date('m');
        $convMonth = $this->convertion->ROMAWI($month);
        $last = $datakwintansi->last_number + 1;
        $nokwitansi = 'No.'.'000'.$last.'/LW'.'/'.$convMonth.'/'.$year;
        $last = $datakwintansi->last_number + 1;
        $nokwitansi = 'No.'.'000'.$last.'/LW'.'/'.$convMonth.'/'.$year;
        $update = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT003')->where('period', $year)->update([
            'last_number' => $last
        ]);
        
        if(empty($rapel)){
            $data = array(
                "type" => $type,
                "nokwitansi" => $nokwitansi,
                "nama" => trim($datakaryawan->Nama),
                "terimadari" => 'PT LION WINGS, JAKARTA',
                "nominal" => $nominal,
                "terbilang" => $terbilang,
                "tanggal" => $format,
                "keterangan" => $to.' '.$keterangan,
                "lamakerja" => $masakerja,
                "tglmasuk" => $tglmasuk,
                "rapel" => $rapel
            );
        } else {
            /* kwitansi rapel */
            $data = array(
                "type" => 'on',
                "nokwitansi" => $nokwitansi,
                "nama" => trim($datakaryawan->Nama),
                "terimadari" => 'PT LION WINGS, JAKARTA',
                "nominal" => $nominal,
                "terbilang" => $terbilangSelisih,
                "tanggal" => $format,
                "keterangan" => $to.' '.$keterangan,
                "lamakerja" => $masakerja,
                "tglmasuk" => $tglmasuk,
                "selisih" => $selisih,
                "rapel" => $rapel
            );
        }
        
        $pdf = PDF::loadview('receipt.templatekwitansi', $data)->setPaper('F4', 'portrait');
        return $pdf->stream('Print_Kwitansi' . date('dmYHis') . '.pdf');
    }
}
