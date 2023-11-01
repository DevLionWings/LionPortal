<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kwintansibackup extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "hris.t_kwitansi_backup";

    protected $fillable = [
        'idkwitansi',
        'nik',
        'namakaryawan',
        'bagian',
        'tanggalcuti',
        'tanggalmasuk',
        'jumlahchh',
        'gaji',
        'jabatan',
        'jamsostek',
        'uangmakan',
        'uangspsi',
        'uangkoperasi',
        'lamacuti',
        'total',
        'untuk',
        'keterangan',
        'terbilang',
        'masakerja',
        'tglpisah',
        'type',
        'category',
        'selisih',
        'ket_selisih',
        'haribaru',
        'harilama',
        'createdon'
    ];
}
