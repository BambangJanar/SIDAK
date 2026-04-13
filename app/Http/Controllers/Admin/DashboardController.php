<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Disposisi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil statistik dasar
        // Asumsi: jenis_surat_id 1 = Masuk, 2 = Keluar (Sesuai seeder kita)
        $totalSuratMasuk = Surat::where('jenis_surat_id', 1)->count();
        $totalSuratKeluar = Surat::where('jenis_surat_id', 2)->count();
        $totalDisposisi = Disposisi::count();
        $suratBaru = Surat::where('status_surat', 'baru')->count();

        // Mengambil 5 surat terbaru untuk tabel preview
        $suratTerbaru = Surat::with('jenisSurat')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalDisposisi',
            'suratBaru',
            'suratTerbaru'
        ));
    }
}
