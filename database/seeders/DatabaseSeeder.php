<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\Pengaturan;
use App\Models\Peran;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Peran
        $superadmin = Peran::create(['nama_peran' => 'superadmin', 'keterangan' => 'Kepala Bagian']);
        $admin = Peran::create(['nama_peran' => 'admin', 'keterangan' => 'Karyawan']);
        $user = Peran::create(['nama_peran' => 'user', 'keterangan' => 'Anak Magang']);

        // 2. Seed Pengguna Default (Sesuai SQL Referensi)
        User::create([
            'nama_lengkap' => 'Nindri Yuwani',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('admin123'),
            'peran_id' => $superadmin->id,
            'nama_bagian_kustom' => 'Kepala Bagian Divisi Corporate Communication Costumer Care',
            'status' => 'active',
            'status_aktif' => true,
        ]);

        User::create([
            'nama_lengkap' => 'Tri Yadi',
            'email' => 'karyawan@gmail.com',
            'password' => Hash::make('admin123'),
            'peran_id' => $admin->id,
            'nama_bagian_kustom' => 'Staff CCC',
            'status' => 'active',
            'status_aktif' => true,
        ]);

        User::create([
            'nama_lengkap' => 'Bella',
            'email' => 'magang@gmail.com',
            'password' => Hash::make('admin123'),
            'peran_id' => $user->id,
            'nama_bagian_kustom' => 'Internship Divisi Sekretaris Perusahaan Bagian CCC',
            'status' => 'active',
            'status_aktif' => true,
        ]);

        // 3. Seed Jenis Surat
        JenisSurat::create(['nama_jenis' => 'Surat Masuk', 'keterangan' => 'Surat dari pihak luar']);
        JenisSurat::create(['nama_jenis' => 'Surat Keluar', 'keterangan' => 'Surat ke pihak luar']);

        // 4. Seed Pengaturan Aplikasi
        Pengaturan::create([
            'app_name' => 'Tracking Disposisi Surat',
            'instansi_nama' => 'BANK KALSEL',
            'instansi_alamat' => 'Jl. Lambung Mangkurat No.7, Banjarmasin',
            'ttd_nama_penandatangan' => 'NINDRI YUWANI',
            'ttd_jabatan' => 'KEPALA BAGIAN CORPORATE COMMUNICATION',
        ]);
    }
}
