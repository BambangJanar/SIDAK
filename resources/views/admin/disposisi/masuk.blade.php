<x-app-layout>
    <x-slot name="header">Disposisi Masuk</x-slot>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Daftar Tugas / Disposisi Diterima</h3>
    </div>

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"><i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}</div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs border dark:border-gray-700">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Dari / Tanggal</th>
                        <th class="px-4 py-3">Info Surat</th>
                        <th class="px-4 py-3">Catatan Disposisi</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi Respon</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($disposisi as $item)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="text-sm font-bold text-purple-600">{{ $item->pengirim->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_disposisi)->isoFormat('DD/MM/YY HH:mm') }}
                                    WITA</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-semibold">{{ $item->surat->nomor_agenda }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($item->surat->perihal, 40) }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->catatan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center text-xs">
                                @php
                                    $statusClasses = [
                                        'dikirim' => 'bg-blue-100 text-blue-700',
                                        'diterima' => 'bg-orange-100 text-orange-700',
                                        'diproses' => 'bg-yellow-100 text-yellow-700',
                                        'selesai' => 'bg-green-100 text-green-700',
                                        'ditolak' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 font-semibold leading-tight rounded-full {{ $statusClasses[$item->status_disposisi] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($item->status_disposisi) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('disposisi.update-status', $item->id) }}" method="POST"
                                    class="inline-flex space-x-1">
                                    @csrf @method('PATCH')
                                    @if ($item->status_disposisi == 'dikirim')
                                        <button name="status_disposisi" value="diterima"
                                            class="px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded hover:bg-blue-600">Terima</button>
                                    @elseif($item->status_disposisi == 'diterima')
                                        <button name="status_disposisi" value="diproses"
                                            class="px-2 py-1 text-xs font-medium text-white bg-orange-500 rounded hover:bg-orange-600">Proses</button>
                                    @elseif($item->status_disposisi == 'diproses')
                                        <button name="status_disposisi" value="selesai"
                                            class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded hover:bg-green-600">Selesai</button>
                                    @endif
                                    <a href="{{ route('surat.show', $item->surat_id) }}"
                                        class="p-1 text-gray-500 hover:text-purple-600"><i
                                            class="fa-solid fa-eye"></i></a>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 italic">Belum ada disposisi
                                masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50 dark:bg-gray-800">{{ $disposisi->links() }}</div>
    </div>
</x-app-layout>
