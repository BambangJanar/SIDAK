<x-app-layout>
    <div x-data="{ isModalDisposisiOpen: false }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <a href="{{ route('surat.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-purple-600 transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Daftar Surat
                </a>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-2">Detail Surat</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi lengkap dan riwayat disposisi surat.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($surat->lampiran_file)
                    <a href="{{ asset('uploads/' . $surat->lampiran_file) }}" target="_blank"
                        class="px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-200 rounded-lg shadow-sm hover:bg-blue-50 transition">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Lihat Surat
                    </a>
                @endif

                <button onclick="window.print()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                    <i class="fa-solid fa-print mr-1"></i> Cetak
                </button>

                @if (Auth::user()->peran_id == 1 || Auth::user()->peran_id == 2)

                    @if (in_array($surat->status_surat, ['baru', 'proses']))
                        <form action="{{ route('surat.update-status', $surat->id) }}" method="POST"
                            class="inline-block">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_surat" value="disetujui">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-lg shadow-sm hover:bg-green-600 transition"
                                onclick="return confirm('Anda yakin ingin menyetujui surat ini?')">
                                <i class="fa-solid fa-check-circle mr-1"></i> Setujui
                            </button>
                        </form>

                        <button type="button"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg shadow-sm hover:bg-red-600 transition"
                            onclick="promptAlasanPenolakan()">
                            <i class="fa-solid fa-circle-xmark mr-1"></i> Tolak
                        </button>

                        <form id="form-tolak" action="{{ route('surat.update-status', $surat->id) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_surat" value="ditolak">
                            <input type="hidden" name="alasan_penolakan" id="alasan_penolakan_input">
                        </form>
                    @endif
                @endif

                <button @click="isModalDisposisiOpen = true"
                    class="px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg shadow-sm hover:bg-teal-600 transition">
                    <i class="fa-solid fa-share-nodes mr-1"></i> Disposisi
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 p-6">
                    <div
                        class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200 flex items-center">
                            <i class="fa-regular fa-envelope text-gray-400 mr-2 text-lg"></i> Atribut Surat
                        </h3>
                        @php
                            $statusColors = [
                                'baru' => 'bg-blue-100 text-blue-700',
                                'proses' => 'bg-yellow-100 text-yellow-700',
                                'disetujui' => 'bg-green-100 text-green-700',
                                'ditolak' => 'bg-red-100 text-red-700',
                                'arsip' => 'bg-gray-200 text-gray-700',
                            ];
                            $badgeClass = $statusColors[$surat->status_surat] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold uppercase rounded-full {{ $badgeClass }}">
                            {{ $surat->status_surat }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Nomor Agenda</p>
                            <div
                                class="inline-block px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm font-bold text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                {{ $surat->nomor_agenda }}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Nomor Surat</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{ $surat->nomor_surat ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Jenis Surat</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{ $surat->jenisSurat->nama_jenis ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Tanggal Diterima</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 flex items-center">
                                <i class="fa-regular fa-calendar text-gray-400 mr-2"></i>
                                {{ \Carbon\Carbon::parse($surat->tanggal_diterima)->isoFormat('D MMM YYYY') }}
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Dari Instansi</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                {{ $surat->dari_instansi ?? ($surat->ke_instansi ?? '-') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Perihal</p>
                            <div
                                class="p-4 bg-gray-50 border border-gray-100 rounded-lg dark:bg-gray-700/50 dark:border-gray-600">
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                    {{ $surat->perihal }}</p>
                            </div>
                        </div>

                        @if ($surat->alasan_penolakan)
                            <div class="md:col-span-2">
                                <div class="px-4 py-3 border-l-4 border-red-500 bg-red-50 rounded-r-lg">
                                    <p class="text-xs font-bold text-red-700 uppercase mb-1"><i
                                            class="fa-solid fa-triangle-exclamation mr-1"></i> Alasan Penolakan</p>
                                    <p class="text-sm text-red-800">{{ $surat->alasan_penolakan }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                            <i class="fa-solid fa-clock-rotate-left text-gray-400 mr-2"></i> Riwayat Disposisi
                        </h4>
                        <span class="px-2 py-1 text-xs font-semibold text-teal-700 bg-teal-100 rounded-full">
                            {{ $surat->disposisi->count() }} Aktivitas
                        </span>
                    </div>

                    <div class="p-0">
                        @if ($surat->disposisi->count() > 0)
                            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($surat->disposisi as $disp)
                                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    Dari: <span
                                                        class="text-purple-600">{{ $disp->pengirim->nama_lengkap ?? 'Sistem' }}</span>
                                                    <i class="fa-solid fa-arrow-right mx-2 text-gray-300"></i>
                                                    Ke: <span
                                                        class="text-blue-600">{{ $disp->penerima->nama_lengkap ?? 'Unknown' }}</span>
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fa-regular fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($disp->tanggal_disposisi)->isoFormat('dddd, D MMM YYYY HH:mm') }}
                                                </p>
                                                @if ($disp->catatan)
                                                    <div
                                                        class="mt-3 p-3 bg-gray-50 border border-gray-100 rounded text-sm text-gray-600 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 italic border-l-2 border-l-purple-400">
                                                        "{{ $disp->catatan }}"
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                @php
                                                    $dispStatusColors = [
                                                        'dikirim' => 'bg-blue-100 text-blue-700',
                                                        'diterima' => 'bg-orange-100 text-orange-700',
                                                        'diproses' => 'bg-yellow-100 text-yellow-700',
                                                        'selesai' => 'bg-green-100 text-green-700',
                                                        'ditolak' => 'bg-red-100 text-red-700',
                                                    ];
                                                    $dispClass =
                                                        $dispStatusColors[$disp->status_disposisi] ??
                                                        'bg-gray-100 text-gray-700';
                                                @endphp
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded {{ $dispClass }}">
                                                    {{ ucfirst($disp->status_disposisi) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center text-gray-500 text-sm">
                                Belum ada riwayat disposisi untuk surat ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4">
                        Ringkasan
                    </h3>

                    <div
                        class="bg-teal-50 border border-teal-100 rounded-xl p-4 flex items-center mb-6 dark:bg-teal-900/30 dark:border-teal-800">
                        <div
                            class="p-3 bg-teal-100 text-teal-600 rounded-full mr-4 flex items-center justify-center w-12 h-12 dark:bg-teal-800 dark:text-teal-300">
                            <i class="fa-solid fa-share-nodes text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-teal-800 font-semibold uppercase tracking-wide dark:text-teal-400">
                                Total Disposisi</p>
                            <p class="text-2xl font-bold text-teal-900 dark:text-teal-100">
                                {{ $surat->disposisi->count() ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p class="mb-3 flex items-center"><i class="fa-solid fa-circle-info mr-2 text-gray-400"></i>
                            Surat ini dibuat oleh:</p>
                        <div
                            class="flex items-center p-3 border border-gray-100 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            @php
                                $namaPembuat = $surat->pembuat->nama_lengkap ?? 'Sistem';
                                $inisial = strtoupper(substr($namaPembuat, 0, 1));
                            @endphp
                            <div
                                class="w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center font-bold mr-3 text-xs">
                                {{ $inisial }}
                            </div>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $namaPembuat }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4">
                        Lampiran File
                    </h3>
                    @if ($surat->lampiran_file)
                        <div
                            class="p-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="flex items-center mb-3">
                                <i class="fa-solid fa-file-pdf text-3xl text-red-500 mr-3"></i>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate"
                                        title="{{ $surat->lampiran_file }}">{{ $surat->lampiran_file }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('uploads/' . $surat->lampiran_file) }}" target="_blank"
                                class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                Buka File
                            </a>
                        </div>
                    @else
                        <div class="p-4 border border-dashed border-gray-300 rounded-lg bg-gray-50 text-center">
                            <i class="fa-solid fa-file-circle-xmark text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500 italic">Tidak ada lampiran.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div x-show="isModalDisposisiOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
            style="display: none;">

            <div @click.away="isModalDisposisiOpen = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden dark:bg-gray-800">

                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center dark:border-gray-700 bg-teal-500 text-white">
                    <h3 class="text-lg font-bold flex items-center">
                        <i class="fa-solid fa-share-nodes mr-2"></i> Kirim Disposisi
                    </h3>
                    <button @click="isModalDisposisiOpen = false" class="text-white hover:text-teal-200 transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('disposisi.store') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="surat_id" value="{{ $surat->id }}">

                    <div
                        class="mb-5 p-3 bg-gray-50 rounded border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Agenda</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            {{ $surat->nomor_agenda }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Tujuan Instansi</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                            {{ $surat->dari_instansi ?? ($surat->ke_instansi ?? '-') }}</p>
                    </div>

                    <div class="mb-4">
                        <x-forms.label value="Pilih Penerima Disposisi" :required="true" />
                        <select name="ke_user_id" required
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:bg-gray-700 border-gray-300 focus:border-teal-400 focus:ring focus:ring-teal-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option value="">-- Pilih Rekan Kerja / Pejabat --</option>
                            @foreach ($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->nama_lengkap }}
                                    ({{ $user->peran->nama_peran ?? 'User' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <x-forms.label value="Instruksi / Catatan Tambahan" />
                        <textarea name="catatan" rows="4" placeholder="Tuliskan instruksi pekerjaan di sini..."
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:bg-gray-700 border-gray-300 focus:border-teal-400 focus:ring focus:ring-teal-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="isModalDisposisiOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @if (Auth::user()->peran_id == 1 || Auth::user()->peran_id == 2)
        <script>
            function promptAlasanPenolakan() {
                let alasan = prompt("Masukkan alasan penolakan surat ini:");
                if (alasan != null && alasan.trim() !== "") {
                    // Di sini Anda bisa men-submit form via fetch API atau mengubah input hidden dan submit form
                    alert("Fitur update status tolak sedang dikembangkan. Alasan yang dimasukkan: " + alasan);
                }
            }
        </script>
    @endif
</x-app-layout>
