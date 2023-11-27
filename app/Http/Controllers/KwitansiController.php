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
use App\Models\Kwintansi;
use App\Models\Kwintansibackup;
use Carbon\Carbon;
use DateTime;
use PDF;
use DataTables;

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

        $mastertunjangan = DB::connection('pgsql')->table('master_data.m_tunjangan')->where('tunjanganid', "DUKA")->get();
    
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
        
        if($type == "UangPisah"){
            if(DB::connection('mysql2')->table('personalia.masteremployee')->where('Nip', $id)->exists()){
                $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee as a')
                                ->join('personalia.masterjabatan as b', 'a.Kode Jabatan', '=', 'b.Kode Jabatan')
                                ->join('personalia.masterbagian as c', 'c.Kode Bagian', '=', 'a.Kode Bagian')
                                ->where('a.Nip', $id)
                                ->where('a.Endda', '9998-12-31')
                                ->select('a.Nip','a.Nama', 'a.Tgl Masuk as tglmasuk', 'a.Gaji per Bulan as gaji', 'b.Tunjangan', 'c.Nama Bagian as bagian')
                                ->first();
                
                $nip = $datakaryawan->Nip;
                $bagian = $datakaryawan->bagian;
                $nama = trim($datakaryawan->Nama);
                $tglmasuk = $datakaryawan->tglmasuk;
                $gaji = $datakaryawan->gaji; 
                $tunjangan = $datakaryawan->Tunjangan;
                $formatTj = 'Rp.'.number_format($tunjangan,0,',','.');
                /* Date Count */
                $datetime1 = new DateTime($tglmasuk);
                $datetime2 = new DateTime($tglpisah);
                $interval = $datetime1->diff($datetime2);
                $year = $interval->format('%y');
    
                if($year >= 1 && $year < 3){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 1);
                    $month = 1;
                } else if ($year >= 3 && $year < 6){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 2);
                    $month = 2;
                } else if ($year >= 6 && $year < 9){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 3);
                    $month = 3;
                } else if ($year >= 9 && $year < 12){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 4);
                    $month = 4;
                } else if ($year >= 12 && $year < 15){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 5);
                    $month = 5;
                } else if ($year >= 15 && $year < 18){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 6);
                    $month = 6;
                } else if ($year >= 18 && $year < 21){
                    $gajibersih = $gaji + $tunjangan;
                    $formatgaji = 'Rp.'.number_format($gajibersih,0,',','.');
                    $totalpisah = round($gajibersih * 7);
                    $month = 7;
                } else if ($year >= 21 && $year < 24){
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
                $keterangan = 'Uang Pisah a/n '.$nama.' , '.' bagian: '.$bagian.', ID: '.$id. PHP_EOL .$gaji.' + '.$tunjangan.' * '.$month.' . ';
    
                $dataArray = [];  
                $dataAll = array_push($dataArray, [
                            "NAME" => $nama,
                            "BAGIAN" => $bagian,
                            "TGLMASUK" => $tglmasuk,
                            "TGLPISAH" => $tglpisah,
                            "GAJI" => $formatgaji,
                            "TUNJANGAN" => $formatTj,
                            "TOTAL" => $formatrupiah,
                            "LAMAKERJA" => $lamakerja,
                            "KETERANGAN" => $keterangan,
                            "JAMSOSTEK" => "Rp.0",
                            "SPSI" => "Rp.0",
                            "KOPERASI" => "Rp.0",
                            "UANGMAKAN" => "Rp.0",
                            "CHH" => "Rp.0"
        
                        ]);
    
                return $dataArray;
            } else {
                return $dataArray = [''];
            }
        } else {
            $datakaryawan = DB::connection('mysql2')->table('personalia.masteremployee as a')
                ->join('personalia.masterjabatan as b', 'a.Kode Jabatan', '=', 'b.Kode Jabatan')
                ->join('personalia.masterbagian as c', 'a.Kode Bagian', '=', 'c.Kode Bagian')
                ->where('a.Nip', $id)
                ->where('a.Endda', '9998-12-31')
                ->select('a.Nip','a.Nama', 'a.Tgl Masuk as tglmasuk', 'a.Gaji per Bulan as gaji', 'b.Tunjangan', 'c.Nama Bagian as bagian')
                ->first();

            if($type == "DUKA"){
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
            $bagian = $datakaryawan->bagian;
            $nama = trim($datakaryawan->Nama);
            $categoryname = trim($mastertunjangan->categorydescr);
            $nominal = 'Rp.'.number_format($mastertunjangan->value,0,',','.');

            if($type == "DUKA"){
                $keterangan = 'Uang Tunjangan Duka Cita atas Meninggalnya '.$categoryname.' dari '.$nama.', '.' bagian: '.$bagian.', ID: '.$id;
            } else if($type == "PERNIKAHAN"){
                $keterangan = 'Uang Tunjangan Pernikahan a/n '.$nama.', '.' bagian: '.$bagian.' ID: '.$id;
            } else {
                $keterangan = 'Uang Tunjangan Kelahiran Anak ke-() :( nama anak ) dari: '.$nama.', '.' bagian: '.$bagian.', ID: '.$id;
            }
        
            $dataArray = [];  
            $dataAll = array_push($dataArray, [
                    "NAME" => $nama,
                    "BAGIAN" => $bagian,
                    "TGLMASUK" => " ",
                    "TGLPISAH" => " ",
                    "GAJI" => "Rp.0",
                    "TUNJANGAN" => "Rp.0",
                    "TOTAL" => $nominal,
                    "LAMAKERJA" => "0",
                    "KETERANGAN" => $keterangan

                ]);

            return $dataArray;
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

    public function temporer(Request $request)
    {
        $type = $request->opsi;
        $id = $request->idkaryawan; 
        $nama = $request->nama;  
        $category = $request->category; 
        $datenow = date('Y-m-d H:i:s');
        /* Rapel */

        /* General */
        $tglmasuk = $request->tglmasuk;
        $keterangan = $request->keterangan;

        $datatotal = $request->total;
        $substotal = substr($datatotal, 3, 12);
        $exp = explode(".", $substotal);
        $total = implode("", $exp);

        $datagaji = $request->gaji;
        $subsgaji = substr($datagaji, 3, 12);
        $exp = explode(".", $subsgaji);
        $gaji = implode("", $exp);

        $datajabatan = $request->jabatan;
        $subsjabatan = substr($datajabatan, 3, 12);
        $exp = explode(".", $subsjabatan);
        $jabatan = implode("", $exp);
    
        $isExist  = DB::connection('pgsql')->table('hris.t_kwitansi_backup')
            ->where('nik', $id)
            ->where('type', $type)
            ->where('category', $category)
            ->exists();

        if (!$isExist) {
            $count = DB::connection('pgsql')->table('hris.t_kwitansi')->count();
            $datakwitansi = DB::connection('pgsql')->table('hris.t_kwitansi')->get();
        
            $firstkwitansi = DB::connection('pgsql')->table('hris.t_kwitansi')->first();
            if($firstkwitansi == null){
                $typedb = '';
                $nik = '';
            } else {
                $typedb = trim($firstkwitansi->type);
                $nik = trim($firstkwitansi->nik);
            }

            $datacounter = DB::connection('pgsql')->table('master_data.m_counter')
                ->where('counterid', 'CT003')
                ->where('prefix', 'KWS')
                ->select('period','start_number', 'end_number', 'last_number')
                ->first();

            $year = date('Y');
            $month = date('m');
            $convMonth = $this->convertion->ROMAWI($month);
            $last = $datacounter->last_number + 1;
            $nokwitansi = 'No.'.'000'.$last.'/LW'.'/'.$convMonth.'/'.$year;
            $update = DB::connection('pgsql')->table('master_data.m_counter')->where('counterid', 'CT003')->where('period', $year)->update([
                'last_number' => $last
            ]);
            
            if($type == 3 || $type == 1.5 || $type == 0){
                /* Non Cuti */
                $tglpisah = "";
                $category ="";
                $masakerja = "";
                /* Cuti */      
                $tglcuti = $request->tglcuti;
                $lamacuti = $request->lamacuti;
                $chh = $request->chh;
                $uangspsi = $request->uangspsi;
                $uangmakan = $request->uangmakan;
                $uangkoperasi = $request->uangkoperasi;
                $bagian = $request->bagian;
                $untuk = $request->untuk;
                $terbilang = $request->terbilang;
                $datajamsostek = $request->jamsostek;
                $subjamsostek = substr($datajamsostek, 3, 12);
                $exp = explode(".", $subjamsostek);
                $jamsostek = implode("", $exp);
                $haribaru = $request->haribaru;
                $harilama = $request->harilama;
                $dataselisih = $request->selisih;
                $subselisih = substr($dataselisih, 3, 12);
                $exp = explode(".", $subselisih);
                $selisih = implode("", $exp);
            
                if($type == $typedb && $id == $nik){
                    return $data = 'Duplicate';
                // } else if ($count == 4){
                //     return $data = 'Max';
                } else {
                    $insert = DB::connection('pgsql')->table('hris.t_kwitansi')->insert([
                        'idkwitansi' => $nokwitansi,
                        'type' => $type,
                        'nik' => $id,
                        'namakaryawan' => $nama,
                        'bagian' => $bagian,
                        'tanggalcuti' => $tglcuti,
                        'tanggalmasuk' => $tglmasuk,
                        'jumlahchh' => $chh,
                        'gaji' => $gaji,
                        'jabatan' => $jabatan,
                        'jamsostek' => $jamsostek,
                        'uangmakan' => $uangmakan,
                        'uangspsi' => $uangspsi,
                        'uangkoperasi' => $uangkoperasi,
                        'lamacuti' => $lamacuti,
                        'total' => $total,
                        'untuk' => $untuk,
                        'keterangan' => $keterangan,
                        'terbilang' => $terbilang,
                        'haribaru' => $haribaru,
                        'harilama' => $harilama,
                        'selisih' => $selisih,
                        'createdon' => $datenow
                    ]);
        
                    $kwn = '';
                    $dataTrim = [];
                    if($insert == true){
                        foreach($datakwitansi as $key => $value){
                            array_push($dataTrim, [
                                "idkwitansi" => trim($value->idkwitansi),
                                "type" => trim($value->type),
                                "nik" => trim($value->nik),
                                "nama" => trim($value->namakaryawan),
                                "gaji" => trim($value->gaji),
                                "total" => trim($value->total),
                                "datecreated" => trim($value->createdon)
                            ]);
                        }
            
                        $data['kwn'] = $dataTrim;
                        return DataTables::of($data['kwn'])
                        ->make(true);
                    } else {
                        $data = ['']; 
                    }
                }

            } else {
                /* Non Cuti */
                $tglpisah = $request->tglpisah;
                $masakerja = $request->masakerja;
                /* Cuti */
                $tglcuti = "";
                $lamacuti = 0;
                $chh = 0;
                $uangspsi = 0;
                $uangmakan = 0;
                $uangkoperasi = 0;
                $bagian = $request->bagian;
                $untuk = "";
                $terbilang = "";
                $jamsostek = 0;

                if($type == $typedb && $id == $nik){
                    return $data = 'Duplicate';
                // } else if ($count == 4){
                //     return $data = 'Max';
                } else {
                    $insert = DB::connection('pgsql')->table('hris.t_kwitansi')->insert([
                        'idkwitansi' => $nokwitansi,
                        'type' => $type,
                        'nik' => $id,
                        'bagian' => $bagian,
                        'namakaryawan' => $nama,
                        'tanggalmasuk' => $tglmasuk,
                        'category' => $category,
                        'gaji' => $gaji,
                        'jabatan' => $jabatan,
                        'total' => $total,
                        'keterangan' => $keterangan,
                        'masakerja' => $masakerja,
                        'tglpisah' => $tglpisah,
                        'createdon' => $datenow
                    ]);
                
                    if($insert == true){
                        $dataTrim = [];
                        foreach($datakwitansi as $key => $value){
                            array_push($dataTrim, [
                                "idkwitansi" => trim($value->idkwitansi),
                                "type" => trim($value->type),
                                "nik" => trim($value->nik),
                                "nama" => trim($value->namakaryawan),
                                "gaji" => trim($value->gaji),
                                "total" => trim($value->total),
                                "datecreated" => trim($value->createdon)
                            ]);
                        }
            
                        $data['kwn'] = $dataTrim;
                        return DataTables::of($data['kwn'])
                        ->make(true);
                    } else {
                        $data = ['']; 
                    }
                }
            } 
        } else {
            $data = 'exists';
            return $data;
        }
    }

    public function getList(Request $request)
    {
        $kwn = '';
        $datakwitansi = DB::connection('pgsql')->table('hris.t_kwitansi')->get();
        $dataTrim = [];
        if($datakwitansi == true){
            foreach($datakwitansi as $key => $value){
                array_push($dataTrim, [
                    "idkwitansi" => trim($value->idkwitansi),
                    "type" => trim($value->type),
                    "nik" => trim($value->nik),
                    "nama" => trim($value->namakaryawan),
                    "gaji" => trim($value->gaji),
                    "total" => trim($value->total),
                    "datecreated" => trim($value->createdon)

                ]);
            }

            $data['kwn'] = $dataTrim;
        } else {
            $data = ['']; 
        }
       
        return DataTables::of($data['kwn'])
            ->make(true);
    }

    public function delete(Request $request)
    {   
        $datakwitansi = DB::connection('pgsql')->table('hris.t_kwitansi')->get();
        $datenow = date('Y-m-d H:i:s');

        foreach($datakwitansi as $data){
            $arrayInsert = [];
            $draw = [
                'idkwitansi' => $data->idkwitansi,
                'nik' => $data->nik,
                'namakaryawan' => $data->namakaryawan,
                'bagian' => $data->bagian,
                'tanggalcuti' => $data->tanggalcuti,
                'tanggalmasuk' => $data->tanggalmasuk,
                'jumlahchh' => $data->jumlahchh,
                'gaji' => $data->gaji,
                'jabatan' => $data->jabatan,
                'jamsostek' => $data->jamsostek,
                'uangmakan' => $data->uangmakan,
                'uangspsi' => $data->uangspsi,
                'uangkoperasi' => $data->uangkoperasi,
                'lamacuti' => $data->lamacuti,
                'total' => $data->total,
                'untuk' => $data->untuk,
                'keterangan' => $data->keterangan,
                'terbilang' => $data->terbilang,
                'masakerja' => $data->masakerja,
                'type' => $data->type,
                'category' => $data->category,
                'selisih' => $data->selisih,
                'ket_selisih' => $data->ket_selisih,
                'haribaru' => $data->haribaru,
                'harilama' => $data->harilama,
                'createdon' => $datenow
            ];
            $arrayInsert[] = $draw;      
            $insert_bulk = DB::connection('pgsql')->table('hris.t_kwitansi_backup')->insert($arrayInsert);
        }
        if ($insert_bulk == true){
            $deletedata = DB::connection('pgsql')->table('hris.t_kwitansi')->delete();
            return $data = 'success';
        } else {
            return $data = 'failed';
        }
       
    }
}
