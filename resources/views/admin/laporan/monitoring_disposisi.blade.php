<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Laporan Monitoring Disposisi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Periode: <span
                    class="font-semibold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($tglMulai)->isoFormat('DD MMM YYYY') }}
                    s/d {{ \Carbon\Carbon::parse($tglSampai)->isoFormat('DD MMM YYYY') }}</span>
            </p>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan.cetak.monitoring-disposisi', ['tgl_mulai' => $tglMulai, 'tgl_sampai' => $tglSampai, 'status_disposisi' => $status]) }}"
                target="_blank"
                class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('laporan.monitoring-disposisi') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Sampai Tanggal</label>
                <input type="date" name="tgl_sampai" value="{{ $tglSampai }}"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Status</label>
                <select name="status_disposisi"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    <option value="">Semua Status</option>
                    <option value="dikirim" {{ $status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="diterima" {{ $status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-slate-800 rounded-lg shadow-sm hover:bg-slate-900 transition flex justify-center items-center h-[42px]">
                <i class="fa-solid fa-filter mr-2"></i> Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-teal-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Total Disposisi</p>
            <p class="text-3xl font-bold text-teal-600">{{ $total }}</p>
        </div>
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Selesai</p>
            <p class="text-3xl font-bold text-green-600">{{ $selesai }}</p>
        </div>
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-orange-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Sedang Diproses</p>
            <p class="text-3xl font-bold text-orange-600">{{ $proses }}</p>
        </div>
        <div
            class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 border-y border-r border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Pending (Dikirim)</p>
            <p class="text-3xl font-bold text-blue-600">{{ $pending }}</p>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-bold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-6 py-4">ALUR (PENGIRIM > PENERIMA)</th>
                        <th class="px-6 py-4">INFO SURAT</th>
                        <th class="px-6 py-4">CATATAN</th>
                        <th class="px-6 py-4">TGL DISPOSISI</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($disposisi as $item)
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold">
                                {{ $item->pengirim->nama_lengkap ?? 'Sistem' }}
                                <i class="fa-solid fa-chevron-right text-[10px] mx-1 text-gray-300"></i>
                                {{ $item->penerima->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                {{ $item->surat->nomor_agenda }}
                            </td>
                            <td class="px-6 py-4 text-sm italic text-gray-500">
                                {{ Str::limit($item->catatan, 30) ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ \Carbon\Carbon::parse($item->tanggal_disposisi)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-2 py-1 text-[10px] font-bold uppercase rounded-full border border-gray-200 bg-gray-50">
                                    {{ $item->status_disposisi }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">Data disposisi tidak
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
