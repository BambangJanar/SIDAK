<?php

use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\DisposisiController; // Tambahkan ini
use App\Http\Controllers\Admin\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Rute Modul Surat
    Route::patch('/surat/{surat}/archive', [SuratController::class, 'archive'])->name('surat.archive');
    Route::patch('/surat/{surat}/status', [SuratController::class, 'updateStatus'])->name('surat.update-status');
    Route::resource('surat', SuratController::class);

    // Rute Modul Disposisi (Kirim & Tracking)
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/masuk', [DisposisiController::class, 'masuk'])->name('masuk');
        Route::get('/keluar', [DisposisiController::class, 'keluar'])->name('keluar');

        // TAMBAHKAN INI UNTUK MONITORING ADMIN
        Route::get('/monitoring', [DisposisiController::class, 'monitoring'])->name('monitoring');

        Route::get('/buat/{surat_id}', [DisposisiController::class, 'create'])->name('create');
        Route::post('/store', [DisposisiController::class, 'store'])->name('store');
        Route::patch('/{id}/status', [DisposisiController::class, 'updateStatus'])->name('update-status');
    });

    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::patch('/arsip/{id}/restore', [ArsipController::class, 'restore'])->name('arsip.restore');
});

Route::prefix('laporan')->name('laporan.')->group(function () {
    // Rute cetak PDF Monitoring
    Route::get('/cetak/monitoring', [LaporanController::class, 'cetakMonitoring'])->name('cetak-monitoring');

    // TAMBAHAN: Route PDF Arsip
    Route::get('/cetak/arsip', [LaporanController::class, 'cetakArsip'])->name('cetak-arsip');
});

require __DIR__ . '/auth.php';
