<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
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

        .report-period {
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
            padding: 6px 8px;
            vertical-align: top;
        }

        table.data-table th {
            background-color: #fee2e2;
            /* Warna merah muda tipis untuk identitas ditolak */
            text-align: center;
            text-transform: uppercase;
        }

        .reason-text {
            color: #b91c1c;
            /* Warna merah gelap untuk teks alasan */
            font-style: italic;
        }
    </style>
</head>

<body>

    @include('pdf.layouts.header')

    <div class="report-title">{{ $title }}</div>
    <div class="report-period">PERIODE TANGGAL: {{ $periode }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="15%">NOMOR AGENDA</th>
                <th width="18%">INSTANSI TERKAIT</th>
                <th width="20%">PERIHAL</th>
                <th width="24%">ALASAN PENOLAKAN</th>
                <th width="10%">TGL TERIMA</th>
                <th width="10%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surat as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="font-bold">
                        {{ $item->nomor_agenda }}<br>
                        <span style="font-size: 8px; font-weight: normal;">{{ $item->nomor_surat ?? '-' }}</span>
                    </td>
                    <td>{{ $item->dari_instansi ?? ($item->ke_instansi ?? '-') }}</td>
                    <td>{{ $item->perihal }}</td>
                    <td class="reason-text">
                        {{ $item->alasan_penolakan ?? 'Tidak dicantumkan' }}
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_diterima)->format('d/m/Y') }}
                    </td>
                    <td class="text-center" style="color: #dc2626; font-weight: bold;">
                        DITOLAK
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        <i>Tidak ada data surat yang ditolak pada periode ini.</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('pdf.layouts._signature')

</body>

</html>
