<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Arsip Surat
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Daftar surat yang telah diarsipkan
            </p>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan.cetak-arsip', request()->all()) }}" target="_blank"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg shadow-sm hover:bg-red-700 transition flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm"><i
                class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}</div>
    @endif

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('arsip.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor agenda, perihal, atau nomor surat..."
                    class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg shadow-sm hover:bg-teal-700 transition flex items-center justify-center">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari
            </button>
        </form>
    </div>

    <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-bold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-6 py-4">No. Agenda</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Perihal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Tanggal Surat</th>
                        <th class="px-6 py-4">Diarsipkan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($arsip as $item)
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 transition">

                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                    {{ $item->nomor_agenda }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $item->nomor_surat ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ $item->jenisSurat->nama_jenis ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-800 dark:text-gray-200 line-clamp-1"
                                    title="{{ $item->perihal }}">{{ $item->perihal }}</div>
                                <div class="text-xs text-gray-500 mt-1">Dari:
                                    {{ $item->dari_instansi ?? ($item->ke_instansi ?? '-') }}</div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusAsal = $item->status_sebelum_arsip ?? 'baru';
                                    $badgeColor =
                                        $statusAsal == 'disetujui'
                                            ? 'bg-green-100 text-green-700'
                                            : ($statusAsal == 'ditolak'
                                                ? 'bg-red-100 text-red-700'
                                                : 'bg-gray-100 text-gray-700');
                                    $iconStatus =
                                        $statusAsal == 'disetujui'
                                            ? 'fa-circle-check'
                                            : ($statusAsal == 'ditolak'
                                                ? 'fa-circle-xmark'
                                                : 'fa-circle-info');
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badgeColor }}">
                                    <i class="fa-solid {{ $iconStatus }} mr-1"></i> {{ ucfirst($statusAsal) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($item->tanggal_diterima)->isoFormat('DD MMM YYYY') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($item->updated_at)->isoFormat('DD MMM YYYY') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('surat.show', $item->id) }}"
                                        class="p-1 text-green-600 hover:bg-green-100 rounded transition"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    @if ($item->lampiran_file)
                                        <a href="{{ asset('uploads/' . $item->lampiran_file) }}" target="_blank"
                                            class="p-1 text-green-600 hover:bg-green-100 rounded transition"
                                            title="Lihat Dokumen">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('arsip.restore', $item->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Keluarkan surat ini dari arsip dan kembalikan ke daftar aktif?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="p-1 text-yellow-600 hover:bg-yellow-100 rounded transition"
                                            title="Restore dari Arsip">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('surat.destroy', $item->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus permanen surat ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1 text-red-600 hover:bg-red-100 rounded transition"
                                            title="Hapus Permanen">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada surat yang diarsipkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t bg-gray-50 dark:bg-gray-800">
            {{ $arsip->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
