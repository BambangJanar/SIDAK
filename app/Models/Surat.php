<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'jenis_surat_id',
        'nomor_surat',
        'nomor_agenda',
        'tanggal_diterima',
        'dari_instansi',
        'ke_instansi',
        'alamat_surat',
        'perihal',
        'lampiran_file',
        'status_surat',
        'alasan_penolakan',
        'status_sebelum_arsip',
        'dibuat_oleh'
    ];

    // Relasi ke Jenis Surat
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    // Relasi ke User pembuat
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Relasi ke tabel Disposisi
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'surat_id');
    }

    // Relasi ke Stakeholder
    public function stakeholders()
    {
        return $this->hasMany(StakeholderSurat::class, 'surat_id');
    }
}
