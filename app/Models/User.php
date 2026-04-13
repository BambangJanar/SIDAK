<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'peran_id',
        'bagian_id',
        'nama_bagian_kustom',
        'status',
        'status_aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Peran
    public function peran()
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }

    // Relasi ke Bagian
    public function bagian()
    {
        return $this->belongsTo(Bagian::class, 'bagian_id');
    }

    public function disposisiMasuk()
    {
        // Berdasarkan form sebelumnya, sepertinya kamu menggunakan 'ke_user_id'
        return $this->hasMany(\App\Models\Disposisi::class, 'ke_user_id', 'id');
    }

    /**
     * Relasi untuk mendapatkan semua disposisi yang DIKIRIM oleh user ini (Tugas Keluar)
     */
    public function disposisiKeluar()
    {
        // Ganti 'dari_user_id' menjadi 'pengirim_id' jika nama kolom di databasemu berbeda
        return $this->hasMany(\App\Models\Disposisi::class, 'dari_user_id', 'id');
    }
}
