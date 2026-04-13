<h2 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
    Surat Terbaru
</h2>

<div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-100 dark:border-gray-700">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3">No. Agenda</th>
                    <th class="px-4 py-3">Instansi</th>
                    <th class="px-4 py-3">Perihal</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                @forelse($suratTerbaru as $surat)
                <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 transition duration-150">
                    <td class="px-4 py-3 text-sm font-medium">
                        {{ $surat->nomor_agenda }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ $surat->dari_instansi ?? $surat->ke_instansi ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ Str::limit($surat->perihal, 40) }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                        <span class="px-2 py-1 font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full dark:bg-purple-700 dark:text-purple-100">
                            {{ $surat->jenisSurat->nama_jenis ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs">
                        @if($surat->status_surat == 'baru')
                            <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Baru</span>
                        @elseif($surat->status_surat == 'proses')
                            <span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full">Proses</span>
                        @else
                            <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">{{ ucfirst($surat->status_surat) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-sm text-center text-gray-500">
                        Belum ada data surat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>