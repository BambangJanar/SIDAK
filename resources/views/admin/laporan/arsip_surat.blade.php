<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Laporan Arsip Surat</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Filter berdasarkan Tanggal Diarsipkan: <span
                    class="font-semibold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($tglMulai)->isoFormat('DD MMM YYYY') }}
                    s/d {{ \Carbon\Carbon::parse($tglSampai)->isoFormat('DD MMM YYYY') }}</span>
            </p>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan.cetak.arsip-surat', ['tgl_mulai' => $tglMulai, 'tgl_sampai' => $tglSampai]) }}"
                target="_blank"
                class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('laporan.arsip-surat') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Dari Tanggal (Arsip)</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Sampai Tanggal (Arsip)</label>
                <input type="date" name="tgl_sampai" value="{{ $tglSampai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-slate-800 rounded-lg shadow-sm hover:bg-slate-900 transition flex justify-center items-center h-[42px]">
                <i class="fa-solid fa-filter mr-2"></i> Filter Arsip
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-purple-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Total Diarsipkan</p>
            <p class="text-3xl font-bold text-purple-600">{{ $total }}</p>
        </div>
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Arsip Surat Masuk</p>
            <p class="text-3xl font-bold text-blue-600">{{ $arsipMasuk }}</p>
        </div>
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Arsip Surat Keluar</p>
            <p class="text-3xl font-bold text-green-600">{{ $arsipKeluar }}</p>
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
                        <th class="px-6 py-4">PERIHAL</th>
                        <th class="px-6 py-4">TGL SURAT</th>
                        <th class="px-6 py-4">TGL ARSIP</th>
                        <th class="px-6 py-4 text-center">STATUS ASAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($surat as $item)
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-800 dark:text-gray-200">
                                {{ $item->nomor_agenda }}</td>
                            <td class="px-6 py-4 text-xs font-semibold">
                                {{ $item->jenisSurat->nama_jenis ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">{{ Str::limit($item->perihal, 30) }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ \Carbon\Carbon::parse($item->tanggal_diterima)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-purple-600">
                                {{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-2 py-1 text-[10px] font-bold uppercase rounded border border-gray-200 bg-gray-50">
                                    {{ $item->status_sebelum_arsip ?? 'Baru' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data arsip
                                pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
