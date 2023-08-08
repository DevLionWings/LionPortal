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
use DateTime;
use Carbon\Carbon;
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
        $tglcuti = $request->tglcuti;
        $tglmasuk = $request->tglmasuk;
        $lamacuti = $request->lamacuti;
        $jmlhchh = $request->jmlhchh;
        $id = $request->idkaryawan;
        $uangmakan = $request->uangmakan;
        $spsi = $request->spsi;
        $koperasi = $request->koperasi;
        $bpjs = $request->bpjs;
        $exp = explode(".",$request->amountrapel);
        $rapelAmount = implode("", $exp);
        $totalAmount = $request->totalamount;
        $selisih = $request->selisih;
     
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
            if (empty($uangmakan) && empty($spsi) && empty($koperasi)){
                $uangmakan = '20000';  
                $spsi = '27000'; 
                $koperasi = '50000';
            } else if(!empty($uangmakan)){
                $uangmakan; 
                $spsi = '27000'; 
                $koperasi = '50000';
            } else if(!empty($spsi)){
                $uangmakan = '20000';  
                $spsi;
                $koperasi = '50000'; 
            } else if(!empty($koperasi)){
                $uangmakan = '20000';
                $spsi = '27000'; 
                $koperasi;
            } else {
                $uangmakan;
                $spsi;
                $koperasi;
            }
            $formatUm = 'Rp.'.number_format($uangmakan,0,',','.');
            $formatSpsi = 'Rp.'.number_format($spsi,0,',','.');
            $formatKoperasi = 'Rp.'.number_format($koperasi,0,',','.');
            $nip = $datakaryawan->Nip;
            $nama = trim($datakaryawan->Nama);
            $tglmasuk = $datakaryawan->tglmasuk;
            $gaji = $datakaryawan->gaji; 
            $formatGaji = 'Rp.'.number_format($gaji,0,',','.');
            $tunjangan = $datakaryawan->Tunjangan;
            $formatTj = 'Rp.'.number_format($tunjangan,0,',','.');
            $jamsostek = $gaji * 2/100;
            $formatJamsostek = 'Rp.'.number_format($jamsostek,0,',','.');
            $bagian = $datakaryawan->bagian;
            $item = $gaji + $uangmakan;
           
            if($type == "3"){
                $hitung1 = 3 * $item;
                $hitung2 = 3 * $jamsostek;
                $hitung3 = $jmlhchh; //di * 3 atau tidak
                $hitung4 = 3 * $spsi;
                $hitung5 = 3 * $koperasi;
                $hitung6 = 3 * ($gaji * 2/100);
                $untuk = "Uang Pengganti Cuti Hamil a/n ".$nama.' bagian: '.$bagian.' id: '.$id.',';
                $keterangan = "3x(GP + UM) - 3x(JHT) - (Cuti Haid) - 3x(SPSI) - 3x(Koperasi) - 3x(Bpjs + Pensiun) : ". $hitung1.' - '.$hitung2.' - '.$hitung3.' - '.$hitung4.' - '.$hitung5.' - '.$hitung6;
            } else {
                $hitung1 = 1.5 * $item;
                $hitung2 = 2 * $jamsostek;
                $hitung3 = $jmlhchh; //di * 3 atau tidak
                $hitung4 = 2 * $spsi;
                $hitung5 = 2 * $koperasi;
                $hitung6 = 2 * ($gaji * 2/100);
                $untuk = "Uang Pengganti Cuti Keguguran a/n ".$nama.' bagian: '.$bagian.' id: '.$id.',';
                $keterangan = "1.5x(GP + UM) - 2x(JHT) - (Cuti Haid) - 2x(SPSI) - 2x(Koperasi) - 2x(Bpjs + Pensiun) :". $hitung1.' - '.$hitung2.' - '.$hitung3.' - '.$hitung4.' - '.$hitung5.' - '.$hitung6;
            }
            $total = $hitung1 - $hitung2 - $hitung3 - $hitung4 - $hitung5 - $hitung6;
            $formatTotal = 'Rp.'.number_format($total,0,',','.');
            $terbilang =  $this->convertion->TERBILANG($total).' '.'RUPIAH';
            if(!empty($rapelAmount)){
                $countSelisih = (($total - $rapelAmount) / 3) * $totalAmount;
            } else {
                $countSelisih = '0';
            }
            $formatSelisih = 'Rp.'.number_format($countSelisih,0,',','.');
            $dataArray = [];  
            $dataAll = array_push($dataArray, [
                        "NAME" => $nama,
                        "BAGIAN" => $bagian,
                        "GAJI" => $formatGaji,
                        "JABATAN" => $formatTj,
                        "UANGMAKAN" => $formatUm,
                        "JAMSOSTEK" => $formatJamsostek,
                        "SPSI" => $formatSpsi,
                        "KOPERASI" => $formatKoperasi,
                        "UNTUK" => $untuk,
                        "KETERANGAN" => $keterangan,
                        "TOTAL" => $formatTotal,
                        "TERBILANG" => $terbilang,
                        "SELISIH" => $formatSelisih
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
        $rapel = $request->rapel;

        $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee')
            ->where('Nip', $id)
            ->where('Endda', '9998-12-31')
            ->select('Nip','Nama')
            ->first();
        
        if(empty($selisih)){
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
                "tglmasuk" => $tglmasuk
            );
        } else {
            $data = array(
                "type" => 'on',
                "nokwitansi" => $nokwitansi,
                "nama" => trim($datakaryawan->Nama),
                "terimadari" => 'PT LION WINGS, JAKARTA',
                "nominal" => $nominal,
                "terbilang" => $terbilang,
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
