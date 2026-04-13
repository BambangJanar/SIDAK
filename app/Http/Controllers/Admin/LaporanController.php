<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\LogAktivitas;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\PdfReportTrait;

class LaporanController extends Controller
{
    use PdfReportTrait;

    public function index()
    {
        return view('admin.laporan.index');
    }

    /* =========================================================
       0. CETAK LAPORAN MONITORING DISPOSISI (OPERASIONAL)
       ========================================================= */
    public function cetakMonitoring(Request $request)
    {
        if (!in_array(auth()->user()->peran_id, [1, 2])) {
            abort(403, 'Akses Ditolak.');
        }

        $query = Disposisi::with(['surat', 'pengirim', 'penerima']);

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

        $disposisi = $query->orderBy('tanggal_disposisi', 'desc')->get();

        return $this->streamPdf('pdf.monitoring', [
            'title' => 'LAPORAN MONITORING DISPOSISI SURAT',
            'periode' => 'Semua Waktu',
            'disposisi' => $disposisi
        ], 'Monitoring_Disposisi_' . time(), 'landscape');
    }

    /* =========================================================
       1. LAPORAN SURAT MASUK
       ========================================================= */
    public function suratMasuk(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat'])->where('jenis_surat_id', 1)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])->orderBy('tanggal_diterima', 'desc')->get();

        $total = $surat->count();
        $baru = $surat->where('status_surat', 'baru')->count();
        $proses = $surat->where('status_surat', 'proses')->count();
        $selesai = $surat->whereIn('status_surat', ['disetujui', 'selesai', 'arsip'])->count();

        return view('admin.laporan.surat_masuk', compact('surat', 'tglMulai', 'tglSampai', 'total', 'baru', 'proses', 'selesai'));
    }

    public function cetakSuratMasuk(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->where('jenis_surat_id', 1)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')->get();

        return $this->streamPdf('pdf.laporan_surat', [
            'title' => 'LAPORAN DATA SURAT MASUK',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat
        ], 'Laporan_Surat_Masuk_' . $tglMulai, 'landscape');
    }

    /* =========================================================
       2. LAPORAN SURAT KELUAR
       ========================================================= */
    public function suratKeluar(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat'])->where('jenis_surat_id', 2)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])->orderBy('tanggal_diterima', 'desc')->get();

        $total = $surat->count();
        $baru = $surat->where('status_surat', 'baru')->count();
        $proses = $surat->where('status_surat', 'proses')->count();
        $selesai = $surat->whereIn('status_surat', ['disetujui', 'selesai', 'arsip'])->count();

        return view('admin.laporan.surat_keluar', compact('surat', 'tglMulai', 'tglSampai', 'total', 'baru', 'proses', 'selesai'));
    }

    public function cetakSuratKeluar(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->where('jenis_surat_id', 2)
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')->get();

        return $this->streamPdf('pdf.laporan_surat', [
            'title' => 'LAPORAN DATA SURAT KELUAR',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat
        ], 'Laporan_Surat_Keluar_' . $tglMulai, 'landscape');
    }

    /* =========================================================
       3. LAPORAN SEMUA SURAT
       ========================================================= */
    public function semuaSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat'])
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])->orderBy('tanggal_diterima', 'desc')->get();

        $total = $surat->count();
        $totalMasuk = $surat->where('jenis_surat_id', 1)->count();
        $totalKeluar = $surat->where('jenis_surat_id', 2)->count();
        $arsip = $surat->where('status_surat', 'arsip')->count();

        return view('admin.laporan.semua_surat', compact('surat', 'tglMulai', 'tglSampai', 'total', 'totalMasuk', 'totalKeluar', 'arsip'));
    }

    public function cetakSemuaSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])
            ->orderBy('tanggal_diterima', 'asc')->get();

        return $this->streamPdf('pdf.laporan_surat', [
            'title' => 'LAPORAN REKAPITULASI SEMUA SURAT',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat
        ], 'Laporan_Semua_Surat_' . $tglMulai, 'landscape');
    }

    /* =========================================================
       4. LAPORAN SURAT DISETUJUI
       ========================================================= */
    public function suratDisetujui(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat'])->where('status_surat', 'disetujui')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])->orderBy('tanggal_diterima', 'desc')->get();

        $total = $surat->count();
        $suratMasuk = $surat->where('jenis_surat_id', 1)->count();
        $suratKeluar = $surat->where('jenis_surat_id', 2)->count();

        return view('admin.laporan.surat_disetujui', compact('surat', 'tglMulai', 'tglSampai', 'total', 'suratMasuk', 'suratKeluar'));
    }

    public function cetakSuratDisetujui(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])->where('status_surat', 'disetujui')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])->orderBy('tanggal_diterima', 'asc')->get();

        return $this->streamPdf('pdf.laporan_surat', [
            'title' => 'LAPORAN DATA SURAT DISETUJUI (ACC)',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat
        ], 'Laporan_Surat_Disetujui_' . $tglMulai, 'landscape');
    }

    /* =========================================================
       5. LAPORAN SURAT DITOLAK
       ========================================================= */
    public function suratDitolak(Request $request)
    {
        // View Method (Tetap sama)
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat'])->where('status_surat', 'ditolak')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])->orderBy('tanggal_diterima', 'desc')->get();

        $total = $surat->count();
        $suratMasuk = $surat->where('jenis_surat_id', 1)->count();
        $suratKeluar = $surat->where('jenis_surat_id', 2)->count();

        return view('admin.laporan.surat_ditolak', compact('surat', 'tglMulai', 'tglSampai', 'total', 'suratMasuk', 'suratKeluar'));
    }

    public function cetakSuratDitolak(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])->where('status_surat', 'ditolak')
            ->whereBetween('tanggal_diterima', [$tglMulai, $tglSampai])->orderBy('tanggal_diterima', 'asc')->get();

        return $this->streamPdf('pdf.laporan_surat_ditolak', [
            'title' => 'LAPORAN DATA SURAT DITOLAK',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat
        ], 'Laporan_Surat_Ditolak_' . $tglMulai, 'landscape');
    }

    /* =========================================================
       6. LAPORAN ARSIP SURAT
       ========================================================= */
    public function arsipSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat'])->where('status_surat', 'arsip')
            ->whereBetween('updated_at', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59'])->orderBy('updated_at', 'desc')->get();

        $total = $surat->count();
        $arsipMasuk = $surat->where('jenis_surat_id', 1)->count();
        $arsipKeluar = $surat->where('jenis_surat_id', 2)->count();

        return view('admin.laporan.arsip_surat', compact('surat', 'tglMulai', 'tglSampai', 'total', 'arsipMasuk', 'arsipKeluar'));
    }

    public function cetakArsipSurat(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $surat = Surat::with(['jenisSurat', 'pembuat'])->where('status_surat', 'arsip')
            ->whereBetween('updated_at', [$tglMulai . ' 00:00:00', $tglSampai . ' 23:59:59'])->orderBy('updated_at', 'asc')->get();

        return $this->streamPdf('pdf.laporan_surat', [
            'title' => 'LAPORAN REKAPITULASI ARSIP SURAT DIGITAL',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'surat' => $surat
        ], 'Laporan_Arsip_Surat_' . $tglMulai, 'landscape');
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

        $total = $disposisi->count();
        $selesai = $disposisi->where('status_disposisi', 'selesai')->count();
        $proses = $disposisi->whereIn('status_disposisi', ['diterima', 'diproses'])->count();
        $pending = $disposisi->where('status_disposisi', 'dikirim')->count();

        return view('admin.laporan.monitoring_disposisi', compact('disposisi', 'tglMulai', 'tglSampai', 'status', 'total', 'selesai', 'proses', 'pending'));
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

        return $this->streamPdf('pdf.monitoring', [
            'title' => 'LAPORAN RIWAYAT MONITORING DISPOSISI SURAT',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'disposisi' => $disposisi
        ], 'Laporan_Monitoring_Disposisi_' . $tglMulai, 'landscape');
    }

    /* =========================================================
       8. LAPORAN KINERJA PEGAWAI
       ========================================================= */
    public function kinerjaPegawai(Request $request)
    {
        $pegawai = User::with(['peran', 'bagian'])
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
            ])->where('peran_id', '!=', 1)->orderBy('total_tugas', 'desc')->get();

        return view('admin.laporan.kinerja_pegawai', compact('pegawai'));
    }

    public function cetakKinerjaPegawai(Request $request)
    {
        $pegawai = User::with(['peran', 'bagian'])
            ->withCount([
                'disposisiMasuk as total_tugas',
                'disposisiMasuk as selesai' => function ($query) {
                    $query->where('status_disposisi', 'selesai');
                },
                'disposisiMasuk as proses' => function ($query) {
                    $query->whereIn('status_disposisi', ['diterima', 'diproses']);
                }
            ])->where('peran_id', '!=', 1)->orderBy('total_tugas', 'desc')->get();

        return $this->streamPdf('pdf.laporan_kinerja', [
            'title' => 'LAPORAN STATISTIK KINERJA PEGAWAI',
            'periode' => 'Semua Waktu',
            'pegawai' => $pegawai
        ], 'Laporan_Kinerja_Pegawai_' . time(), 'portrait');
    }

    /* =========================================================
       9. LAPORAN LOG AKTIVITAS SISTEM
       ========================================================= */
    public function logAktivitas(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSampai = $request->input('tgl_sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));

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
            ->orderBy('created_at', 'asc')->get();

        return $this->streamPdf('pdf.laporan_log', [
            'title' => 'LAPORAN AUDIT TRAIL / LOG AKTIVITAS SISTEM',
            'periode' => Carbon::parse($tglMulai)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($tglSampai)->isoFormat('D MMM YYYY'),
            'log' => $log
        ], 'Laporan_Log_Aktivitas_' . time(), 'portrait');
    }
}
