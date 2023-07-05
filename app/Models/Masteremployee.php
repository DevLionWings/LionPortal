<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masteremployee extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "personalia.masteremployee";

    protected $fillable = [
        'Nip', 
        'Nama', 
        'Alamat', 
        'No Telp', 
        'Pengalaman', 
        'Tempat Lahir', 
        'Tgl Lahir', 
        'Agama', 
        'Jenis Kelamin', 
        'Status Nikah', 
        'Jumlah Anak', 
        'Status PPh21', 
        'Pendidikan', 
        'Kode Divisi', 
        'Kode Bagian', 
        'Kode Jabatan', 
        'Kode Group', 
        'Kode Admin', 
        'Kode Periode', 
        'Kode Kontrak', 
        'Tgl Masuk', 
        'Tgl Keluar', 
        'Aktif', 
        'Gaji per Bulan', 
        'Jumlah SP', 
        'No KPJ', 
        'No KTP', 
        'No HLD', 
        'Astek', 
        'Kode Wings', 
        'No Rekening', 
        'Jari Bermasalah', 
        'Catatan', 
        'NPWP', 
        'Email', 
        'Begda', 
        'Endda', 
        'User', 
        'Business Area', 
        'Kelompok JKK', 
        'Alamat 2', 
        'Alamat 3', 
        'Alasan Keluar', 
        'Journal Group', 
        'Loan', 
        'OverTime', 
        'Level Karyawan', 
        'sapuser', 
        'tipekaryawan', 
        'plant'
    ];
}
