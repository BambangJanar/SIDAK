<x-app-layout>
    <div x-data="{
        isModalOpen: false,
        modalMode: 'add',
        formData: { id: '', nama_lengkap: '', email: '', peran_id: '', bagian_id: '' },
    
        openAddModal() {
            this.modalMode = 'add';
            this.formData = { id: '', nama_lengkap: '', email: '', peran_id: '', bagian_id: '' };
            this.isModalOpen = true;
        },
        openEditModal(user) {
            this.modalMode = 'edit';
            this.formData = {
                id: user.id,
                nama_lengkap: user.nama_lengkap,
                email: user.email,
                peran_id: user.peran_id,
                bagian_id: user.bagian_id || ''
            };
            this.isModalOpen = true;
        }
    }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Manajemen Pengguna</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data pegawai, hak akses (role), dan
                    penempatan divisi.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button @click="openAddModal()"
                    class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex items-center">
                    <i class="fa-solid fa-user-plus mr-2"></i> Tambah Pengguna
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
            <form action="{{ route('users.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau email pegawai..."
                        class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
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
                            <th class="px-6 py-4">PEGAWAI</th>
                            <th class="px-6 py-4">HAK AKSES / PERAN</th>
                            <th class="px-6 py-4">DIVISI / BAGIAN</th>
                            <th class="px-6 py-4 text-center w-32">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($users as $key => $item)
                            <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center text-sm">{{ $users->firstItem() + $key }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                        {{ $item->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    @if ($item->peran_id == 1 || $item->peran_id == 2)
                                        <span
                                            class="text-purple-600 bg-purple-50 px-2 py-1 rounded">{{ $item->peran->nama_peran ?? 'Admin' }}</span>
                                    @else
                                        <span
                                            class="text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $item->peran->nama_peran ?? 'User' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $item->bagian->nama_bagian ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button @click="openEditModal({{ $item }})"
                                            class="p-1 text-blue-600 hover:bg-blue-100 rounded transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="{{ route('users.destroy', $item->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Hati-hati, ini dapat mempengaruhi riwayat disposisi yang bersangkutan.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1 text-red-600 hover:bg-red-100 rounded transition"
                                                title="Hapus" {{ Auth::id() === $item->id ? 'disabled' : '' }}>
                                                <i
                                                    class="fa-solid fa-trash {{ Auth::id() === $item->id ? 'opacity-50 cursor-not-allowed' : '' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">Data pengguna
                                    belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t bg-gray-50 dark:bg-gray-800">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>

        <div x-show="isModalOpen" style="display: none;" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto">

            <div @click.away="isModalOpen = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden dark:bg-gray-800 mt-10 mb-10">

                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center dark:border-gray-700 bg-blue-600 text-white">
                    <h3 class="text-lg font-bold flex items-center"
                        x-text="modalMode === 'add' ? 'Tambah Pengguna Baru' : 'Edit Data Pengguna'"></h3>
                    <button @click="isModalOpen = false" class="text-white hover:text-blue-200">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form
                    :action="modalMode === 'add' ? '{{ route('users.store') }}' : '{{ url('users') }}/' + formData.id"
                    method="POST" class="p-6">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" x-model="formData.nama_lengkap" required
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Email
                                <span class="text-red-500">*</span></label>
                            <input type="email" name="email" x-model="formData.email" required
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hak Akses
                                <span class="text-red-500">*</span></label>
                            <select name="peran_id" x-model="formData.peran_id" required
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">-- Pilih Peran --</option>
                                @foreach ($peran as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_peran }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Divisi /
                                Bagian</label>
                            <select name="bagian_id" x-model="formData.bagian_id"
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($bagian as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_bagian }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div
                            class="md:col-span-2 mt-2 p-4 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Password <span x-show="modalMode === 'add'" class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" :required="modalMode === 'add'"
                                placeholder="Minimal 8 karakter..."
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-500 dark:text-white">
                            <p x-show="modalMode === 'edit'" class="text-xs text-orange-600 mt-1 mt-1"><i
                                    class="fa-solid fa-circle-info"></i> Biarkan kosong jika tidak ingin mengubah
                                password.</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="isModalOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            <i class="fa-solid fa-save mr-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
