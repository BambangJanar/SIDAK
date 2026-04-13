<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Dokumen SIDAK Kalsel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

        <div class="bg-blue-900 p-6 text-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                <i class="fa-solid fa-building-columns text-3xl text-blue-900"></i>
            </div>
            <h1 class="text-xl font-bold text-white">SIDAK KALSEL</h1>
            <p class="text-blue-200 text-sm">Sistem Informasi Disposisi & Arsip</p>
        </div>

        <div class="p-6">
            @if ($log)
                <div class="text-center mb-6">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-3">
                        <i class="fa-solid fa-check text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">DOKUMEN VALID</h2>
                    <p class="text-sm text-green-600 font-medium">Tercatat Resmi di Database Sistem</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Jenis Laporan</p>
                        <p class="text-sm font-bold text-gray-800">{{ $log->jenis_laporan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Periode Data</p>
                        <p class="text-sm font-medium text-gray-800">{{ $log->periode }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Dicetak Oleh</p>
                        <p class="text-sm font-medium text-gray-800">{{ $log->user->nama_lengkap ?? 'Sistem / Anonim' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Waktu Cetak</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($log->waktu_cetak)->isoFormat('dddd, D MMMM YYYY - HH:mm:ss') }}
                            WITA</p>
                    </div>
                    <div class="pt-2 border-t border-gray-200 mt-2">
                        <p class="text-xs text-gray-400 text-center uppercase">ID Validasi:
                            {{ substr($log->id, 0, 13) }}...</p>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-red-100 text-red-600 rounded-full mb-3">
                        <i class="fa-solid fa-xmark text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">DOKUMEN TIDAK SAH</h2>
                    <p class="text-sm text-red-600 mt-2">Data laporan tidak ditemukan di database atau QR Code tidak
                        valid.</p>
                </div>
            @endif
        </div>

        <div class="bg-gray-100 p-4 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Bank Kalsel. All rights reserved.
            </p>
        </div>
    </div>

</body>

</html>
