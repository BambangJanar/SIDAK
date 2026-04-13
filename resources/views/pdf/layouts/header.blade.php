<table width="100%" style="border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px;">
    <tr>
        <td width="15%" style="text-align: center; vertical-align: middle;">
            @php
                $pengaturan = \App\Models\Pengaturan::first();
                $logoPdf = $pengaturan->logo_instansi ?? null;
            @endphp
            @if ($logoPdf && file_exists(public_path('images/' . $logoPdf)))
                <img src="{{ public_path('images/' . $logoPdf) }}" width="90" alt="Logo">
            @else
                <div style="font-size:24px; font-weight:bold; color:#1e3a8a;">BPD</div>
            @endif
        </td>
        <td width="85%" style="text-align: center; vertical-align: middle;">
            <h2 style="margin: 0; font-size: 20px; font-weight: bold;">
                {{ $pengaturan->kop_divisi ?? 'DIVISI SEKRETARIS PERUSAHAAN' }}
            </h2>
            <h1 style="margin: 0; font-size: 26px; font-weight: bold; color: #1e3a8a;">
                {{ $pengaturan->kop_instansi ?? 'BANK KALSEL' }}
            </h1>
            <p style="margin: 5px 0 0 0; font-size: 12px; font-family: sans-serif;">
                {!! nl2br(
                    e(
                        $pengaturan->kop_alamat ??
                            'Jl. Lambung Mangkurat No.7, Kertak Baru Ilir, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70111',
                    ),
                ) !!}<br>
                {{ $pengaturan->kop_kontak ?? 'Telp: 05113350725 | Email: costumercare@bankkalsel.co.id' }}
            </p>
        </td>
    </tr>
</table>
