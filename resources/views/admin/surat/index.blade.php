<x-app-layout>
    <x-slot name="header">
        Semua Surat
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-600">Daftar Arsip Surat</h3>
        <a href="{{ route('surat.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Surat
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
        <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs border dark:border-gray-700">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">No. Agenda</th>
                        <th class="px-4 py-3">Tanggal Terbit</th>
                        <th class="px-4 py-3">Instansi / Perihal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($surat as $item)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3 text-sm font-bold">{{ $item->nomor_agenda }}</td>
                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_diterima)->isoFormat('DD MMMM YYYY') }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold">{{ $item->dari_instansi ?? $item->ke_instansi ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($item->perihal, 50) }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="px-2 py-1 font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full">
                                {{ ucfirst($item->status_surat) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
    <div class="flex items-center justify-center space-x-1">
        <a href="{{ route('surat.show', $item->id) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Detail">
            <i class="fa-solid fa-eye"></i>
        </a>
        
        <a href="{{ route('surat.edit', $item->id) }}" class="p-2 text-gray-500 hover:text-orange-600 hover:bg-orange-100 rounded-lg transition" title="Edit">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>

        @if($item->status_surat !== 'arsip')
        <form action="{{ route('surat.archive', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin memindahkan surat ini ke Arsip?')" class="inline-block">
            @csrf @method('PATCH')
            <button type="submit" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-100 rounded-lg transition" title="Arsipkan">
                <i class="fa-solid fa-box-archive"></i>
            </button>
        </form>
        @endif

        <form action="{{ route('surat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini secara permanen?')" class="inline-block">
            @csrf @method('DELETE')
            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-100 rounded-lg transition" title="Hapus">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>
    </div>
    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Data belum tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50 dark:bg-gray-800">
            {{ $surat->links() }}
        </div>
    </div>
</x-app-layout>