<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\DisposisiController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\LaporanController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Middleware Auth & Verified (Breeze)
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- MODUL PERSURATAN ---
    // Custom Actions Surat (Patch)
    Route::patch('/surat/{surat}/archive', [SuratController::class, 'archive'])->name('surat.archive');
    Route::patch('/surat/{surat}/status', [SuratController::class, 'updateStatus'])->name('surat.update-status');
    // CRUD Surat
    Route::resource('surat', SuratController::class);

    // --- MODUL DISPOSISI ---
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/masuk', [DisposisiController::class, 'masuk'])->name('masuk');
        Route::get('/keluar', [DisposisiController::class, 'keluar'])->name('keluar');
        Route::get('/monitoring', [DisposisiController::class, 'monitoring'])->name('monitoring');
        Route::get('/buat/{surat_id}', [DisposisiController::class, 'create'])->name('create');
        Route::post('/store', [DisposisiController::class, 'store'])->name('store');
        Route::patch('/{id}/status', [DisposisiController::class, 'updateStatus'])->name('update-status');
    });

    // --- MODUL ARSIP DIGITAL ---
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::patch('/arsip/{id}/restore', [ArsipController::class, 'restore'])->name('arsip.restore');

    // --- MODUL PUSAT LAPORAN (9 Laporan) ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');

        // ROUTE KEMBALIAN UNTUK MENU OPERASIONAL (Agar tombol lama tidak error)
        Route::get('/cetak/monitoring', [LaporanController::class, 'cetakMonitoring'])->name('cetak-monitoring');
        Route::get('/cetak/arsip', [LaporanController::class, 'cetakArsipSurat'])->name('cetak-arsip');

        // 1. Laporan Surat Masuk
        Route::get('/surat-masuk', [LaporanController::class, 'suratMasuk'])->name('surat-masuk');
        Route::get('/surat-masuk/cetak', [LaporanController::class, 'cetakSuratMasuk'])->name('cetak.surat-masuk');

        // 2. Laporan Surat Keluar
        Route::get('/surat-keluar', [LaporanController::class, 'suratKeluar'])->name('surat-keluar');
        Route::get('/surat-keluar/cetak', [LaporanController::class, 'cetakSuratKeluar'])->name('cetak.surat-keluar');

        // 3. Laporan Semua Surat 
        Route::get('/semua-surat', [LaporanController::class, 'semuaSurat'])->name('semua-surat');
        Route::get('/semua-surat/cetak', [LaporanController::class, 'cetakSemuaSurat'])->name('cetak.semua-surat');

        // 4. Laporan Surat Disetujui
        Route::get('/surat-disetujui', [LaporanController::class, 'suratDisetujui'])->name('surat-disetujui');
        Route::get('/cetak/surat-disetujui', [LaporanController::class, 'cetakSuratDisetujui'])->name('cetak-surat-disetujui');

        // 5. Laporan Surat Ditolak 
        Route::get('/surat-ditolak', [LaporanController::class, 'suratDitolak'])->name('surat-ditolak');
        Route::get('/surat-ditolak/cetak', [LaporanController::class, 'cetakSuratDitolak'])->name('cetak.surat-ditolak');

        // 6. Laporan Arsip Surat 
        Route::get('/arsip-surat', [LaporanController::class, 'arsipSurat'])->name('arsip-surat');
        Route::get('/arsip-surat/cetak', [LaporanController::class, 'cetakArsipSurat'])->name('cetak.arsip-surat');

        // 7. Laporan Monitoring Disposisi 
        Route::get('/monitoring-disposisi', [LaporanController::class, 'monitoringDisposisi'])->name('monitoring-disposisi');
        Route::get('/monitoring-disposisi/cetak', [LaporanController::class, 'cetakMonitoringDisposisi'])->name('cetak.monitoring-disposisi');

        // 8. Laporan Kinerja Pegawai 
        Route::get('/kinerja-pegawai', [LaporanController::class, 'kinerjaPegawai'])->name('kinerja-pegawai');
        Route::get('/kinerja-pegawai/cetak', [LaporanController::class, 'cetakKinerjaPegawai'])->name('cetak.kinerja-pegawai');

        // 9. Laporan Log Aktivitas
        Route::get('/log-aktivitas', [LaporanController::class, 'logAktivitas'])->name('log-aktivitas');
        Route::get('/log-aktivitas/cetak', [LaporanController::class, 'cetakLogAktivitas'])->name('cetak.log-aktivitas');
    });

    // --- ADMINISTRATOR (Placeholder) ---
    // Manajemen User & Pengaturan Sistem bisa ditambahkan di sini nanti

    // --- PROFILE (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
