<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function cetakMonitoring(Request $request)
    {
        // Proteksi Akses (Khusus Admin/Superadmin)
        if (!in_array(auth()->user()->peran_id, [1, 2])) {
            abort(403, 'Akses Ditolak.');
        }

        $query = Disposisi::with(['surat', 'pengirim', 'penerima']);

        // Terapkan filter yang sama dengan yang ada di view
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('surat', function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status_disposisi')) {
            $query->where('status_disposisi', $request->status_disposisi);
        }

        // Ambil semua data (tanpa pagination) untuk dicetak
        $disposisi = $query->orderBy('tanggal_disposisi', 'desc')->get();
        $pengaturan = Pengaturan::first(); // Ambil TTD dari tabel pengaturan

        // Render PDF menggunakan view 'pdf.monitoring'
        $pdf = Pdf::loadView('pdf.monitoring', [
            'title' => 'Laporan Monitoring Disposisi Surat',
            'disposisi' => $disposisi,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'landscape'); // Format lanskap agar tabel lebar muat

        // Tambahkan parameter ['Attachment' => false] di sini
        return $pdf->stream('Monitoring_Disposisi_' . time() . '.pdf', ['Attachment' => false]);
    }
}
