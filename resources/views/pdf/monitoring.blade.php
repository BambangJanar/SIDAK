<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 10px 20px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Styling Tabel ala Laporan Skripsi */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        table.data th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        table.data td.center {
            text-align: center;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-dikirim {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .status-diterima {
            background: #eef2ff;
            color: #4338ca;
        }

        .status-diproses {
            background: #fefce8;
            color: #a16207;
        }

        .status-selesai {
            background: #f0fdf4;
            color: #15803d;
        }

        .status-ditolak {
            background: #fef2f2;
            color: #b91c1c;
        }
    </style>
</head>

<body>

    @include('pdf.layouts.header')

    <div class="title">{{ $title }}</div>

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Tanggal & Waktu</th>
                <th width="20%">Alur Disposisi</th>
                <th width="25%">Informasi Surat</th>
                <th width="26%">Catatan Instruksi</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($disposisi as $key => $item)
                <tr>
                    <td class="center">{{ $key + 1 }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($item->tanggal_disposisi)->format('d/m/Y H:i') }}</td>
                    <td>
                        <b>Dari:</b> {{ $item->pengirim->nama_lengkap ?? 'Sistem' }}<br>
                        <b>Ke:</b> {{ $item->penerima->nama_lengkap ?? '-' }}
                    </td>
                    <td>
                        <b>Agenda:</b> {{ $item->surat->nomor_agenda }}<br>
                        <span style="font-size: 10px; color: #444;">{{ Str::limit($item->surat->perihal, 50) }}</span>
                    </td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                    <td class="center">
                        <span class="badge status-{{ $item->status_disposisi }}">
                            {{ $item->status_disposisi }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data disposisi yang ditemukan berdasarkan filter
                        tersebut.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('pdf.layouts._signature')

</body>

</html>
