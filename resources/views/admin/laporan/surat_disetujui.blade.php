<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Laporan Surat Disetujui
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Periode: <span
                    class="font-semibold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($tglMulai)->isoFormat('DD MMM YYYY') }}
                    s/d {{ \Carbon\Carbon::parse($tglSampai)->isoFormat('DD MMM YYYY') }}</span>
            </p>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan.cetak-surat-disetujui', ['tgl_mulai' => $tglMulai, 'tgl_sampai' => $tglSampai]) }}"
                target="_blank"
                class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('laporan.surat-disetujui') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Sampai Tanggal</label>
                <input type="date" name="tgl_sampai" value="{{ $tglSampai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>

            <div class="w-full md:w-auto">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-2 text-sm font-medium text-white bg-slate-800 rounded-lg shadow-sm hover:bg-slate-900 transition flex justify-center items-center h-[42px]">
                    <i class="fa-solid fa-filter mr-2"></i> Filter Data
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-emerald-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Total Disetujui</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $total }}</p>
        </div>
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Surat Masuk (ACC)</p>
            <p class="text-3xl font-bold text-blue-600">{{ $suratMasuk }}</p>
        </div>
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Surat Keluar (ACC)</p>
            <p class="text-3xl font-bold text-green-600">{{ $suratKeluar }}</p>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-bold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-6 py-4">NO. AGENDA</th>
                        <th class="px-6 py-4">JENIS</th>
                        <th class="px-6 py-4">INSTANSI TERKAIT</th>
                        <th class="px-6 py-4">PERIHAL</th>
                        <th class="px-6 py-4">TANGGAL</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($surat as $item)
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-800 dark:text-gray-200">
                                {{ $item->nomor_agenda }}</td>
                            <td class="px-6 py-4 text-xs font-semibold">
                                @if ($item->jenis_surat_id == 1)
                                    <span class="text-blue-600 bg-blue-50 px-2 py-1 rounded">Masuk</span>
                                @else
                                    <span class="text-green-600 bg-green-50 px-2 py-1 rounded">Keluar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $item->dari_instansi ?? ($item->ke_instansi ?? '-') }}</td>
                            <td class="px-6 py-4 text-sm">{{ Str::limit($item->perihal, 30) }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ \Carbon\Carbon::parse($item->tanggal_diterima)->isoFormat('DD MMM YYYY') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                    <i class="fa-solid fa-check mr-1"></i> Disetujui
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                                Tidak ada surat yang disetujui pada periode tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
