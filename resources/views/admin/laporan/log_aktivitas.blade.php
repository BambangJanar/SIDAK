<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Laporan Log Aktivitas</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Periode Rekaman: <span
                    class="font-semibold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($tglMulai)->isoFormat('DD MMM YYYY') }}
                    s/d {{ \Carbon\Carbon::parse($tglSampai)->isoFormat('DD MMM YYYY') }}</span>
            </p>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan.cetak.log-aktivitas', ['tgl_mulai' => $tglMulai, 'tgl_sampai' => $tglSampai]) }}"
                target="_blank"
                class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('laporan.log-aktivitas') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-slate-500 focus:border-slate-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Sampai Tanggal</label>
                <input type="date" name="tgl_sampai" value="{{ $tglSampai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-slate-500 focus:border-slate-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-slate-800 rounded-lg shadow-sm hover:bg-slate-900 transition flex justify-center items-center h-[42px]">
                <i class="fa-solid fa-filter mr-2"></i> Filter Data
            </button>
        </form>
    </div>

    <div
        class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-slate-600 border-y border-r border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Total Aktivitas Terekam</p>
        <p class="text-3xl font-bold text-slate-700 dark:text-slate-300">{{ number_format($total, 0, ',', '.') }} <span
                class="text-sm font-normal text-gray-400">tindakan</span></p>
    </div>

    <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-bold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-6 py-4 w-1/6">WAKTU (WITA)</th>
                        <th class="px-6 py-4 w-1/4">PENGGUNA</th>
                        <th class="px-6 py-4">DESKRIPSI AKTIVITAS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($log as $item)
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                    {{ $item->user->nama_lengkap ?? 'Sistem / Anonim' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->user->peran->nama_peran ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $item->aktivitas ?? ($item->keterangan ?? 'Melakukan tindakan di sistem') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">Tidak ada aktivitas
                                sistem yang terekam pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
