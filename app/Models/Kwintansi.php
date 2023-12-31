<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kwintansi extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "hris.t_kwitansi";

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
