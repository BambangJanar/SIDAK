<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pengaturan;

trait PdfReportTrait
{
    /**
     * Reusable method untuk mencetak PDF
     * * @param string $view Nama file blade (cth: 'pdf.laporan_surat')
     * @param array $data Data yang akan dikirim ke view
     * @param string $filename Nama file saat di-stream
     * @param string $orientation Orientasi kertas ('portrait' atau 'landscape')
     */
    public function streamPdf($view, $data, $filename, $orientation = 'landscape')
    {
        // Otomatis inject data pengaturan untuk Tanda Tangan
        $data['pengaturan'] = Pengaturan::first();

        // Generate PDF
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', $orientation);

        // Render preview inline di browser
        return $pdf->stream($filename . '.pdf', ['Attachment' => false]);
    }
}
