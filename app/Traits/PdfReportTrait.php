<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pengaturan;
use App\Models\LogCetakLaporan;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

trait PdfReportTrait
{
    public function streamPdf($view, $data, $filename, $orientation = 'landscape')
    {
        $data['pengaturan'] = Pengaturan::first();

        // 1. Generate UUID untuk laporan ini
        $uuid = (string) Str::uuid();

        // 2. Simpan jejak cetak ke Database
        LogCetakLaporan::create([
            'id' => $uuid,
            'user_id' => auth()->id() ?? null,
            'jenis_laporan' => $data['title'] ?? 'Laporan Sistem',
            'periode' => $data['periode'] ?? '-',
            'waktu_cetak' => Carbon::now()
        ]);

        // 3. Buat URL Validasi
        $urlValidasi = route('validasi.laporan', $uuid);

        // 4. Generate QR Code (Cast ke string sebelum encode Base64)
        $qrCodeSvg = (string) QrCode::format('svg')->size(100)->margin(0)->generate($urlValidasi);
        $data['qrCode'] = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
        $data['uuid'] = $uuid;

        // 5. Generate & Stream PDF
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', $orientation);
        return $pdf->stream($filename . '.pdf', ['Attachment' => false]);
    }
}
