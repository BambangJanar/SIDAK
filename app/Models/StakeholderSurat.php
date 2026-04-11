<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakeholderSurat extends Model
{
    use HasFactory;

    protected $table = 'surat_stakeholders'; // Sesuai nama di SQL referensimu

    protected $fillable = [
        'surat_id',
        'user_id',
        'role_type',
        'assigned_by',
        'assigned_at',
        'is_active'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pemberiTugas()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
