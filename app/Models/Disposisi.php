<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisi';

    protected $fillable = [
        'surat_id',
        'dari_user_id',
        'ke_user_id',
        'status_disposisi',
        'catatan',
        'tanggal_disposisi',
        'tanggal_respon'
    ];

    // Relasi ke Surat
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    // Relasi ke Pengirim (User)
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    // Relasi ke Penerima (User)
    public function penerima()
    {
        return $this->belongsTo(User::class, 'ke_user_id');
    }
}
