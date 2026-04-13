<x-app-layout>
    <div x-data="{
        isModalOpen: false,
        modalMode: 'add',
        formData: { id: '', nama_jenis: '', keterangan: '' },
    
        openAddModal() {
            this.modalMode = 'add';
            this.formData = { id: '', nama_jenis: '', keterangan: '' };
            this.isModalOpen = true;
        },
        openEditModal(item) {
            this.modalMode = 'edit';
            this.formData = { id: item.id, nama_jenis: item.nama_jenis, keterangan: item.keterangan || '' };
            this.isModalOpen = true;
        }
    }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Master Jenis Surat</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola referensi tipe/jenis surat yang akan
                    digunakan pada sistem.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button @click="openAddModal()"
                    class="px-5 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg shadow-sm hover:bg-purple-700 transition flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Jenis Surat
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm"><i
                    class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm"><i
                    class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div
            class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 dark:bg-gray-800 dark:border-gray-700">
            <form action="{{ route('jenis-surat.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama jenis surat..."
                        class="w-full text-sm border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                </div>
                <button type="submit"
                    class="px-6 py-2 text-sm font-medium text-white bg-slate-800 rounded-lg shadow-sm hover:bg-slate-900 transition flex items-center justify-center">
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
                            <th class="px-6 py-4 w-16 text-center">NO</th>
                            <th class="px-6 py-4">NAMA JENIS SURAT</th>
                            <th class="px-6 py-4">KETERANGAN</th>
                            <th class="px-6 py-4 text-center w-32">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($jenisSurat as $key => $item)
                            <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">{{ $jenisSurat->firstItem() + $key }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $item->nama_jenis }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button @click="openEditModal({{ $item }})"
                                            class="p-1 text-blue-600 hover:bg-blue-100 rounded transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="{{ route('jenis-surat.destroy', $item->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis surat ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1 text-red-600 hover:bg-red-100 rounded transition"
                                                title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Data jenis surat
                                    belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t bg-gray-50 dark:bg-gray-800">
                {{ $jenisSurat->withQueryString()->links() }}
            </div>
        </div>

        <div x-show="isModalOpen" style="display: none;" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

            <div @click.away="isModalOpen = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden dark:bg-gray-800">

                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center dark:border-gray-700 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200"
                        x-text="modalMode === 'add' ? 'Tambah Jenis Surat' : 'Edit Jenis Surat'"></h3>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form
                    :action="modalMode === 'add' ? '{{ route('jenis-surat.store') }}' : '{{ url('jenis-surat') }}/' +
                        formData.id"
                    method="POST" class="p-6">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Jenis Surat
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jenis" x-model="formData.nama_jenis" required
                            placeholder="Cth: Surat Undangan"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan
                            (Opsional)</label>
                        <textarea name="keterangan" x-model="formData.keterangan" rows="3" placeholder="Tuliskan deskripsi singkat..."
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="isModalOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                            <i class="fa-solid fa-save mr-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
