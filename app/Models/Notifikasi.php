<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'surat_id',
        'disposisi_id',
        'url',
        'is_read',
        'read_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
