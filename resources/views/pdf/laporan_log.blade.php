<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .report-subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 20px;
            color: #555;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            vertical-align: top;
            /* Teks berada di atas agar rapi jika deskripsi panjang */
        }

        table.data-table th {
            background-color: #f1f5f9;
            /* Warna abu-abu kebiruan khas log sistem */
            text-align: center;
            text-transform: uppercase;
            font-size: 10px;
        }

        /* Styling khusus teks di tabel */
        .waktu-text {
            font-family: 'Courier New', Courier, monospace;
            /* Font ala kode/sistem */
            font-weight: bold;
            color: #475569;
        }

        .role-text {
            font-size: 9px;
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>

<body>

    @include('pdf.layouts.header')

    <div class="report-title">{{ $title }}</div>
    <div class="report-subtitle">
        PERIODE REKAMAN: {{ $periode }}<br>
        <span style="font-size: 9px; font-weight: normal;">Dicetak pada:
            {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY HH:mm:ss') }} WITA</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="20%">WAKTU (WITA)</th>
                <th width="25%">NAMA PENGGUNA</th>
                <th width="50%">DESKRIPSI AKTIVITAS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($log as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>

                    <td class="text-center waktu-text">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}<br>
                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}
                    </td>

                    <td>
                        <div class="font-bold" style="font-size: 11px;">
                            {{ $item->user->nama_lengkap ?? 'Sistem / Anonim' }}
                        </div>
                        <div class="role-text">
                            Hak Akses: {{ $item->user->peran->nama_peran ?? '-' }}
                        </div>
                    </td>

                    <td>
                        {{ $item->aktivitas ?? ($item->keterangan ?? 'Melakukan interaksi dengan sistem.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px;">
                        <i>Tidak ada catatan aktivitas sistem yang terekam pada periode ini.</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('pdf.layouts._signature')

</body>

</html>
