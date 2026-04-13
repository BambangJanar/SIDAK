<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\LogAktivitas; // Persiapan untuk laporan log nanti
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\PdfReportTrait;

class LaporanController extends Controller
{
    use PdfReportTrait;
    // Halaman Menu Utama Pusat Laporan
    public function index()
    {
        return view('admin.laporan.index');
    }

    /* =========================================================
       0. CETAK LAPORAN MONITORING DISPOSISI (OPERASIONAL MENU)
       ========================================================= */
    public function cetakMonitoring(Request $request)
    {
        // Proteksi Akses (Khusus Admin/Superadmin)
        if (!in_array(auth()->user()->peran_id, [1, 2])) {
            abort(403, 'Akses Ditolak.');
        }

        $query = \App\Models\Disposisi::with(['surat', 'pengirim', 'penerima']);

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
        $pengaturan = \App\Models\Pengaturan::first(); // Ambil TTD dari tabel pengaturan

        // Render PDF menggunakan view 'pdf.monitoring'
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.monitoring', [
            'title' => 'Laporan Monitoring Disposisi Surat',
            'disposisi' => $disposisi,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'landscape'); // Format lanskap agar tabel lebar muat

        // Tambahkan parameter ['Attachment' => false] di sini
        return $pdf->stream('Monitoring_Disposisi_' . time() . '.pdf', ['Attachment' => false]);
    }

    /* =========================================================
       1. LAPORAN SURAT MASUK
       ========================================================= */
    public function suratMasuk(Request $request)
    {
        // Default filter: Bulan ini (tanggal 1 sampai akhir bulan)
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query Dasar (Surat Masuk = 1)
        $query = Surat::with(['jenisSurat'])->where('jenis_surat_id', 1)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai]);

        // Ambil data untuk tabel
        $surat = $query->orderBy('tanggal_diterima', 'desc')->get();

        // Hitung untuk Card Ringkasan
        $total = $surat->count();
        $baru = $surat->where('status_surat', 'baru')->count();
        $proses = $surat->where('status_surat', 'proses')->count();
        $selesai = $surat->whereIn('status_surat', ['disetujui', 'selesai', 'arsip'])->count();

        return view('admin.laporan.surat_masuk', compact(
            'surat',
            'tglMulai',
            'tglSampai',
            'total',
            'baru',
            'proses',
            'selesai'
        ));
    }

    public function cetakSuratMasuk(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->where('jenis_surat_id', 1)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')
            ->get();

        // Pakai fungsi dari Trait! Jauh lebih ringkas kan?
        return $this->streamPdf(
            'pdf.laporan_surat', // Nama View
            [                    // Data array
                'title' => 'LAPORAN DATA SURAT MASUK',
                'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
                'surat' => $surat
            ],
            'Laporan_Surat_Masuk_' . $tglMulai, // Nama File
            'landscape' // Orientasi
        );
    }

    /* =========================================================
       2. LAPORAN SURAT KELUAR
       ========================================================= */
    public function suratKeluar(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query Dasar (Surat Keluar = 2)
        $query = Surat::with(['jenisSurat'])->where('jenis_surat_id', 2)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai]);

        $surat = $query->orderBy('tanggal_diterima', 'desc')->get();

        $total = $surat->count();
        $baru = $surat->where('status_surat', 'baru')->count();
        $proses = $surat->where('status_surat', 'proses')->count();
        $selesai = $surat->whereIn('status_surat', ['disetujui', 'selesai', 'arsip'])->count();

        return view('admin.laporan.surat_keluar', compact(
            'surat',
            'tglMulai',
            'tglSampai',
            'total',
            'baru',
            'proses',
            'selesai'
        ));
    }

    public function cetakSuratKeluar(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->where('jenis_surat_id', 2)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')
            ->get();

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pdf.laporan_surat', [
            'title' => 'LAPORAN DATA SURAT KELUAR',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'landscape');

        // Parameter Attachment => false agar preview inline di browser
        return $pdf->stream('Laporan_Surat_Keluar_' . $tglMulai . '.pdf', ['Attachment' => false]);
    }


    /* =========================================================
       3. LAPORAN SEMUA SURAT (MASUK & KELUAR)
       ========================================================= */
    public function semuaSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query Dasar (Tanpa filter jenis_surat_id)
        $query = Surat::with(['jenisSurat'])
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai]);

        $surat = $query->orderBy('tanggal_diterima', 'desc')->get();

        // Hitung untuk Card Ringkasan
        $total = $surat->count();
        $totalMasuk = $surat->where('jenis_surat_id', 1)->count();
        $totalKeluar = $surat->where('jenis_surat_id', 2)->count();
        $arsip = $surat->where('status_surat', 'arsip')->count();

        return view('admin.laporan.semua_surat', compact(
            'surat',
            'tglMulai',
            'tglSampai',
            'total',
            'totalMasuk',
            'totalKeluar',
            'arsip'
        ));
    }

    public function cetakSemuaSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')
            ->get();

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pdf.laporan_surat', [
            'title' => 'LAPORAN REKAPITULASI SEMUA SURAT',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Semua_Surat_' . $tglMulai . '.pdf', ['Attachment' => false]);
    }

    /* =========================================================
       4. LAPORAN SURAT DISETUJUI
       ========================================================= */
    public function suratDisetujui(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query: Filter berdasarkan status_surat = 'disetujui'
        $query = Surat::with(['jenisSurat'])
            ->where('status_surat', 'disetujui')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai]);

        $surat = $query->orderBy('tanggal_diterima', 'desc')->get();

        // Hitung untuk Card Ringkasan
        $total = $surat->count();
        $suratMasuk = $surat->where('jenis_surat_id', 1)->count();
        $suratKeluar = $surat->where('jenis_surat_id', 2)->count();

        return view('admin.laporan.surat_disetujui', compact(
            'surat',
            'tglMulai',
            'tglSampai',
            'total',
            'suratMasuk',
            'suratKeluar'
        ));
    }

    public function cetakSuratDisetujui(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->where('status_surat', 'disetujui')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')
            ->get();

        $pengaturan = Pengaturan::first();

        // Tetap menggunakan template PDF yang sama, hanya beda judul
        $pdf = Pdf::loadView('pdf.laporan_surat', [
            'title' => 'LAPORAN DATA SURAT DISETUJUI (ACC)',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Surat_Disetujui_' . $tglMulai . '.pdf', ['Attachment' => false]);
    }

    /* =========================================================
       5. LAPORAN SURAT DITOLAK
       ========================================================= */
    public function suratDitolak(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query: Filter berdasarkan status_surat = 'ditolak'
        $query = Surat::with(['jenisSurat'])
            ->where('status_surat', 'ditolak')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai]);

        $surat = $query->orderBy('tanggal_diterima', 'desc')->get();

        // Hitung untuk Card Ringkasan
        $total = $surat->count();
        $suratMasuk = $surat->where('jenis_surat_id', 1)->count();
        $suratKeluar = $surat->where('jenis_surat_id', 2)->count();

        return view('admin.laporan.surat_ditolak', compact(
            'surat',
            'tglMulai',
            'tglSampai',
            'total',
            'suratMasuk',
            'suratKeluar'
        ));
    }

    public function cetakSuratDitolak(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->where('status_surat', 'ditolak')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')
            ->get();

        // Menggunakan PdfReportTrait
        return $this->streamPdf(
            'pdf.laporan_surat_ditolak',
            [
                'title' => 'LAPORAN DATA SURAT DITOLAK',
                'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
                'surat' => $surat
            ],
            'Laporan_Surat_Ditolak_' . $tglMulai
        );
    }
    /* =========================================================
       6. LAPORAN ARSIP SURAT
       ========================================================= */
    public function arsipSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query: Filter status 'arsip' berdasarkan tanggal diarsipkan (updated_at)
        $query = Surat::with(['jenisSurat'])
            ->where('status_surat', 'arsip')
            ->whereBetween('updated_at', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59']);

        $surat = $query->orderBy('updated_at', 'desc')->get();

        // Ringkasan data arsip
        $total = $surat->count();
        $arsipMasuk = $surat->where('jenis_surat_id', 1)->count();
        $arsipKeluar = $surat->where('jenis_surat_id', 2)->count();

        return view('admin.laporan.arsip_surat', compact(
            'surat',
            'tglMulai',
            'tglSampai',
            'total',
            'arsipMasuk',
            'arsipKeluar'
        ));
    }

    public function cetakArsipSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->where('status_surat', 'arsip')
            ->whereBetween('updated_at', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59'])
            ->orderBy('updated_at', 'asc')
            ->get();

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pdf.laporan_surat', [
            'title' => 'LAPORAN REKAPITULASI ARSIP SURAT DIGITAL',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Arsip_Surat_' . $tglMulai . '.pdf', ['Attachment' => false]);
    }

    /* =========================================================
       7. LAPORAN MONITORING DISPOSISI
       ========================================================= */
    public function monitoringDisposisi(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status_disposisi');

        $query = Disposisi::with(['surat', 'pengirim', 'penerima'])
            ->whereBetween('tanggal_disposisi', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59']);

        if ($request->filled('status_disposisi')) {
            $query->where('status_disposisi', $status);
        }

        $disposisi = $query->orderBy('tanggal_disposisi', 'desc')->get();

        // Statistik ringkasan disposisi
        $total = $disposisi->count();
        $selesai = $disposisi->where('status_disposisi', 'selesai')->count();
        $proses = $disposisi->whereIn('status_disposisi', ['diterima', 'diproses'])->count();
        $pending = $disposisi->where('status_disposisi', 'dikirim')->count();

        return view('admin.laporan.monitoring_disposisi', compact(
            'disposisi',
            'tglMulai',
            'tglSampai',
            'status',
            'total',
            'selesai',
            'proses',
            'pending'
        ));
    }

    public function cetakMonitoringDisposisi(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status_disposisi');

        $query = Disposisi::with(['surat', 'pengirim', 'penerima'])
            ->whereBetween('tanggal_disposisi', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59']);

        if ($request->filled('status_disposisi')) {
            $query->where('status_disposisi', $status);
        }

        $disposisi = $query->orderBy('tanggal_disposisi', 'asc')->get();
        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pdf.monitoring', [
            'title' => 'LAPORAN RIWAYAT MONITORING DISPOSISI SURAT',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'disposisi' => $disposisi,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Monitoring_Disposisi_' . $tglMulai . '.pdf', ['Attachment' => false]);
    }

    /* =========================================================
       8. LAPORAN KINERJA PEGAWAI
       ========================================================= */
    public function kinerjaPegawai(Request $request)
    {
        // Ambil semua user (kecuali Superadmin jika perlu) beserta hitungan disposisinya
        $pegawai = \App\Models\User::with(['peran', 'bagian'])
            ->withCount([
                'disposisiMasuk as total_tugas',
                'disposisiMasuk as selesai' => function ($query) {
                    $query->where('status_disposisi', 'selesai');
                },
                'disposisiMasuk as proses' => function ($query) {
                    $query->whereIn('status_disposisi', ['diterima', 'diproses']);
                },
                'disposisiMasuk as pending' => function ($query) {
                    $query->where('status_disposisi', 'dikirim');
                }
            ])
            ->where('peran_id', '!=', 1) // Contoh: Sembunyikan Superadmin
            ->orderBy('total_tugas', 'desc')
            ->get();

        return view('admin.laporan.kinerja_pegawai', compact('pegawai'));
    }

    public function cetakKinerjaPegawai(Request $request)
    {
        $pegawai = \App\Models\User::with(['peran', 'bagian'])
            ->withCount([
                'disposisiMasuk as total_tugas',
                'disposisiMasuk as selesai' => function ($query) {
                    $query->where('status_disposisi', 'selesai');
                },
                'disposisiMasuk as proses' => function ($query) {
                    $query->whereIn('status_disposisi', ['diterima', 'diproses']);
                }
            ])
            ->where('peran_id', '!=', 1)
            ->orderBy('total_tugas', 'desc')
            ->get();

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pdf.laporan_kinerja', [
            'title' => 'LAPORAN STATISTIK KINERJA PEGAWAI',
            'pegawai' => $pegawai,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'portrait'); // Potrait saja cukup untuk daftar user

        return $pdf->stream('Laporan_Kinerja_Pegawai_' . time() . '.pdf', ['Attachment' => false]);
    }

    /* =========================================================
       9. LAPORAN LOG AKTIVITAS SISTEM
       ========================================================= */
    public function logAktivitas(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query data log (Asumsi tabel log memiliki relasi 'user' dan kolom 'created_at')
        $query = LogAktivitas::with(['user'])
            ->whereBetween('created_at', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59']);

        $log = $query->orderBy('created_at', 'desc')->get();
        $total = $log->count();

        return view('admin.laporan.log_aktivitas', compact('log', 'tglMulai', 'tglSampai', 'total'));
    }

    public function cetakLogAktivitas(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $log = LogAktivitas::with(['user'])
            ->whereBetween('created_at', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59'])
            ->orderBy('created_at', 'asc')
            ->get();

        $pengaturan = Pengaturan::first();

        // Menggunakan template PDF terpisah karena struktur kolom log berbeda dengan surat
        $pdf = Pdf::loadView('pdf.laporan_log', [
            'title' => 'LAPORAN AUDIT TRAIL / LOG AKTIVITAS SISTEM',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'log' => $log,
            'pengaturan' => $pengaturan
        ])->setPaper('a4', 'portrait'); // Bisa portrait karena kolom log tidak terlalu banyak

        return $pdf->stream('Laporan_Log_Aktivitas_' . time() . '.pdf', ['Attachment' => false]);
    }
}
