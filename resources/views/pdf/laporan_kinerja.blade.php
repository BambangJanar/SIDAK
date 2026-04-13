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

        .text-right {
            text-align: right;
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
            vertical-align: middle;
        }

        table.data-table th {
            background-color: #f3f4f6;
            text-align: center;
            text-transform: uppercase;
            font-size: 10px;
        }

        /* Pewarnaan Angka Status */
        .text-selesai {
            color: #059669;
            font-weight: bold;
        }

        .text-proses {
            color: #d97706;
            font-weight: bold;
        }

        .text-pending {
            color: #2563eb;
            font-weight: bold;
        }
    </style>
</head>

<body>

    @include('pdf.layouts.header')

    <div class="report-title">{{ $title }}</div>
    <div class="report-subtitle">
        STATISTIK DISTRIBUSI DAN PENYELESAIAN TUGAS PER PEGAWAI<br>
        <span style="font-size: 9px; font-weight: normal;">Dicetak pada:
            {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY HH:mm') }} WITA</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="35%">NAMA PEGAWAI / JABATAN</th>
                <th width="12%">TOTAL TUGAS</th>
                <th width="12%">SELESAI</th>
                <th width="12%">PROSES</th>
                <th width="12%">PENDING</th>
                <th width="12%">PROGRES (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pegawai as $key => $p)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>
                        <div class="font-bold" style="font-size: 12px;">{{ $p->nama_lengkap }}</div>
                        <div style="font-size: 9px; color: #555;">{{ $p->peran->nama_peran ?? 'User' }} -
                            {{ $p->bagian->nama_bagian ?? 'Umum' }}</div>
                    </td>
                    <td class="text-center font-bold" style="font-size: 12px;">{{ $p->total_tugas }}</td>
                    <td class="text-center text-selesai">{{ $p->selesai }}</td>
                    <td class="text-center text-proses">{{ $p->proses }}</td>
                    <td class="text-center text-pending">{{ $p->pending }}</td>

                    <td class="text-center">
                        @php
                            // Menghitung persentase
                            $persen = $p->total_tugas > 0 ? round(($p->selesai / $p->total_tugas) * 100) : 0;
                        @endphp
                        <span style="font-size: 12px; font-weight: bold;">{{ $persen }}%</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        <i>Belum ada data pegawai atau disposisi yang dapat dievaluasi.</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('pdf.layouts._signature')

</body>

</html>
