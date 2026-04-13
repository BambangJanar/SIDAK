<table width="100%" style="margin-top: 40px; page-break-inside: avoid;">
    <tr>
        <td width="65%" style="vertical-align: bottom;">
            @if (isset($qrCode))
                <table style="border: 2px solid #1e3a8a; padding: 5px; border-radius: 5px; background: #f8fafc;">
                    <tr>
                        <td width="110" style="text-align: center; vertical-align: middle;">
                            <img src="{{ $qrCode }}" alt="QR Code" width="90" height="90">
                        </td>
                        <td style="vertical-align: middle; font-family: sans-serif;">
                            <p style="margin: 0; font-size: 10px; font-weight: bold; color: #1e3a8a;">TANDA TANGAN
                                ELEKTRONIK</p>
                            <p style="margin: 3px 0; font-size: 9px; color: #333;">Dokumen ini telah dicetak dan
                                ditandatangani<br>secara elektronik melalui sistem SIDAK Kalsel.</p>
                            <p style="margin: 0; font-size: 8px; color: #666;">Scan QR Code untuk verifikasi keaslian
                                dokumen.</p>
                        </td>
                    </tr>
                </table>
            @endif
        </td>

        <td width="35%" style="text-align: center; vertical-align: bottom;">
            <p style="margin: 0; font-size: 12px; font-family: sans-serif;">
                Banjarmasin, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}
            </p>

            <p
                style="margin: 5px 0 5px 0; font-size: 12px; font-weight: bold; font-family: sans-serif; text-transform: uppercase;">
                {{ $pengaturan->ttd_jabatan ?? 'KEPALA BAGIAN' }}
            </p>

            @if (isset($pengaturan->ttd_image) && file_exists(public_path('images/' . $pengaturan->ttd_image)))
                <div style="margin: 5px 0;">
                    <img src="{{ public_path('images/' . $pengaturan->ttd_image) }}" height="65" alt="Tanda Tangan">
                </div>
            @else
                <div style="height: 70px;"></div>
            @endif

            <p
                style="margin: 0; font-size: 12px; font-weight: bold; text-decoration: underline; font-family: sans-serif; text-transform: uppercase;">
                {{ $pengaturan->ttd_nama_penandatangan ?? 'NINDRI YUWANI' }}
            </p>

            @if (isset($pengaturan->ttd_nip) && $pengaturan->ttd_nip != '')
                <p style="margin: 0; font-size: 12px; font-family: sans-serif;">
                    NIP. {{ $pengaturan->ttd_nip }}
                </p>
            @endif
        </td>
    </tr>
</table>
