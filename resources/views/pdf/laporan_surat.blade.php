<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        /* CSS Khusus untuk DomPDF */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        /* Styling Judul Laporan */
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

        /* Styling Tabel Data */
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
            background-color: #e5e7eb;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
        }

        table.data-table td {
            font-size: 10px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
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
                <th width="4%">NO</th>
                <th width="16%">NOMOR AGENDA</th>
                <th width="12%">JENIS SURAT</th>
                <th width="20%">ASAL / TUJUAN INSTANSI</th>
                <th width="28%">PERIHAL SINGKAT</th>
                <th width="10%">TGL TERIMA</th>
                <th width="10%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surat as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="font-bold">{{ $item->nomor_agenda }}<br>
                        <span
                            style="font-size: 8px; font-weight: normal; color: #555;">{{ $item->nomor_surat ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        {{ $item->jenisSurat->nama_jenis ?? '-' }}
                    </td>
                    <td>
                        {{ $item->dari_instansi ?? ($item->ke_instansi ?? '-') }}
                    </td>
                    <td>{{ $item->perihal }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_diterima)->format('d/m/Y') }}
                    </td>
                    <td class="text-center badge">
                        @if ($item->status_surat === 'disetujui')
                            <span style="color: #059669;">DISETUJUI</span>
                        @elseif($item->status_surat === 'ditolak')
                            <span style="color: #dc2626;">DITOLAK</span>
                        @elseif($item->status_surat === 'arsip')
                            <span style="color: #7c3aed;">ARSIP</span>
                        @else
                            <span style="color: #4b5563;">{{ strtoupper($item->status_surat) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        <i>Tidak ada data surat pada periode ini.</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('pdf.layouts._signature')

</body>

</html>
