<table width="100%" style="margin-top: 40px; page-break-inside: avoid;">
    <tr>
        <td width="65%"></td>
        <td width="35%" style="text-align: center;">
            <p style="margin: 0; font-size: 12px; font-family: sans-serif;">
                Banjarmasin, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}
            </p>
            <p style="margin: 5px 0 70px 0; font-size: 12px; font-weight: bold; font-family: sans-serif;">
                {{ $pengaturan->ttd_jabatan ?? 'KEPALA BAGIAN' }}
            </p>

            <p
                style="margin: 0; font-size: 12px; font-weight: bold; text-decoration: underline; font-family: sans-serif;">
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
