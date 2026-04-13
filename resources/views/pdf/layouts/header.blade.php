<table width="100%" style="border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px;">
    <tr>
        <td width="15%" style="text-align: center; vertical-align: middle;">
            {{-- Panggil logo Bank Kalsel dari folder public/images. Jika file tidak ada, gunakan teks --}}
            @if (file_exists(public_path('images/logo.png')))
                <img src="{{ public_path('images/logo.png') }}" width="90" alt="Logo">
            @else
                <div style="font-size:24px; font-weight:bold; color:#1e3a8a;">BPD</div>
            @endif
        </td>
        <td width="85%" style="text-align: center; vertical-align: middle;">
            <h2 style="margin: 0; font-size: 20px; font-weight: bold;">DIVISI SEKRETARIS PERUSAHAAN</h2>
            <h1 style="margin: 0; font-size: 26px; font-weight: bold; color: #1e3a8a;">BANK KALSEL</h1>
            <p style="margin: 5px 0 0 0; font-size: 12px; font-family: sans-serif;">
                Jl. Lambung Mangkurat No.7, Kertak Baru Ilir, Kec. Banjarmasin Tengah<br>
                Kota Banjarmasin, Kalimantan Selatan 70111<br>
                Telp: 05113350725 | Email: costumercare@bankkalsel.co.id
            </p>
        </td>
    </tr>
</table>
