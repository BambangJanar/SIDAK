<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'app_name',
        'app_description',
        'app_logo',
        'app_favicon',
        'theme_color',
        'instansi_nama',
        'instansi_alamat',
        'instansi_telepon',
        'instansi_email',
        'instansi_logo',
        'ttd_nama_penandatangan',
        'ttd_nip',
        'ttd_jabatan',
        'ttd_kota',
        'ttd_image',
        'kop_instansi',
        'kop_divisi',
        'kop_alamat',
        'kop_kontak',
        'logo_instansi',
    ];
}
