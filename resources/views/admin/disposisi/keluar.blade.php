<x-app-layout>
    <x-slot name="header">Disposisi Keluar</x-slot>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Monitoring Tugas yang Dikirim</h3>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs border dark:border-gray-700">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Tujuan / Tanggal</th>
                        <th class="px-4 py-3">Info Surat</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Respon Terakhir</th>
                        <th class="px-4 py-3 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($disposisi as $item)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                    {{ $item->penerima->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_disposisi)->isoFormat('DD/MM/YY HH:mm') }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium">{{ $item->surat->nomor_agenda }}</div>
                            </td>
                            <td class="px-4 py-3 text-center text-xs">
                                <span
                                    class="px-2 py-1 font-semibold leading-tight rounded-full bg-purple-100 text-purple-700">
                                    {{ ucfirst($item->status_disposisi) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm italic">
                                {{ $item->tanggal_respon ? \Carbon\Carbon::parse($item->tanggal_respon)->diffForHumans() : 'Belum direspon' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('surat.show', $item->surat_id) }}"
                                    class="text-purple-600 hover:underline text-sm font-medium">Lihat Surat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 italic">Anda belum pernah
                                mengirim disposisi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50 dark:bg-gray-800">{{ $disposisi->links() }}</div>
    </div>
</x-app-layout>
