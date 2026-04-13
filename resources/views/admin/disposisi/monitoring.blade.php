<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Monitoring Disposisi
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Monitoring semua disposisi surat
            </p>
            <div
                class="inline-flex items-center mt-3 px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-800/30 dark:text-green-400">
                <i class="fa-solid fa-shield-halved mr-1.5"></i> Mode Admin: Melihat semua disposisi
            </div>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan.cetak-monitoring', request()->all()) }}" target="_blank"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg shadow-sm hover:bg-red-700 transition flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('disposisi.monitoring') }}" method="GET" class="flex flex-col md:flex-row gap-4">

            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor surat, perihal..."
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>

            <div class="w-full md:w-48">
                <select name="status_disposisi"
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    <option value="">Semua Status</option>
                    <option value="dikirim" {{ request('status_disposisi') == 'dikirim' ? 'selected' : '' }}>Dikirim
                    </option>
                    <option value="diterima" {{ request('status_disposisi') == 'diterima' ? 'selected' : '' }}>Diterima
                    </option>
                    <option value="diproses" {{ request('status_disposisi') == 'diproses' ? 'selected' : '' }}>Diproses
                    </option>
                    <option value="selesai" {{ request('status_disposisi') == 'selesai' ? 'selected' : '' }}>Selesai
                    </option>
                    <option value="ditolak" {{ request('status_disposisi') == 'ditolak' ? 'selected' : '' }}>Ditolak
                    </option>
                </select>
            </div>

            <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg shadow-sm hover:bg-teal-700 transition flex items-center justify-center">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            @if (request()->anyFilled(['search', 'status_disposisi']))
                <a href="{{ route('disposisi.monitoring') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition flex items-center justify-center"
                    title="Reset Filter">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-bold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-6 py-4">Alur</th>
                        <th class="px-6 py-4">Surat</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($disposisi as $item)
                        <tr
                            class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center text-sm">
                                    <span
                                        class="font-semibold text-gray-800 dark:text-gray-200">{{ $item->pengirim->nama_lengkap ?? 'Sistem' }}</span>
                                    <i class="fa-solid fa-arrow-right mx-2 text-gray-400"></i>
                                    <span
                                        class="font-semibold text-gray-800 dark:text-gray-200">{{ $item->penerima->nama_lengkap ?? '-' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                    {{ $item->surat->nomor_agenda }}</div>
                                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs"
                                    title="{{ $item->surat->perihal }}">{{ $item->surat->perihal }}</div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->catatan ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($item->tanggal_disposisi)->isoFormat('D MMM YYYY HH:mm') }}
                            </td>

                            <td class="px-6 py-4 text-xs">
                                @php
                                    $statusColors = [
                                        'dikirim' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                        'diterima' => 'bg-indigo-50 text-indigo-600 border border-indigo-100', // Sesuai warna ungu di screenshot
                                        'diproses' => 'bg-yellow-50 text-yellow-600 border border-yellow-100',
                                        'selesai' => 'bg-green-50 text-green-600 border border-green-100',
                                        'ditolak' => 'bg-red-50 text-red-600 border border-red-100',
                                    ];
                                    $badge = $statusColors[$item->status_disposisi] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-3 py-1 font-semibold rounded-full {{ $badge }}">
                                    {{ ucfirst($item->status_disposisi) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('surat.show', $item->surat_id) }}"
                                    class="inline-flex items-center justify-center p-2 text-teal-600 bg-teal-50 rounded-full hover:bg-teal-100 hover:text-teal-700 transition"
                                    title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                                Tidak ada data disposisi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t bg-gray-50 dark:bg-gray-800">
            {{ $disposisi->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
