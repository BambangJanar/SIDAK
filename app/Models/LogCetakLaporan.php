<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LogCetakLaporan extends Model
{
    protected $table = 'log_cetak_laporan';

    // Karena Primary Key kita UUID (String), bukan Auto-Increment Integer
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'jenis_laporan',
        'periode',
        'waktu_cetak'
    ];

    // Otomatis generate UUID saat data dibuat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
