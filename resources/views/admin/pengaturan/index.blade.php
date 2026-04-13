<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Pengaturan Sistem</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sesuaikan identitas aplikasi, format cetak laporan,
                dan data penandatangan.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200"><i
                            class="fa-solid fa-laptop-code mr-2 text-purple-500"></i> Identitas Aplikasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Aplikasi
                            Menu Atas</label>
                        <input type="text" name="app_name"
                            value="{{ old('app_name', $pengaturan->app_name ?? 'SIDAK Kalsel') }}" required
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="text-xs text-gray-500 mt-1">Nama ini akan muncul di sudut kiri atas layar.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo Instansi
                            (Untuk PDF)</label>
                        @if (isset($pengaturan->logo_instansi) && file_exists(public_path('images/' . $pengaturan->logo_instansi)))
                            <div class="mb-3 p-2 border border-gray-200 rounded-lg bg-gray-50 inline-block">
                                <img src="{{ asset('images/' . $pengaturan->logo_instansi) }}" alt="Logo"
                                    class="h-16 object-contain">
                            </div>
                        @endif
                        <input type="file" name="logo_instansi" accept="image/png, image/jpeg, image/jpg"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                        <p class="text-xs text-orange-500 mt-1">Biarkan kosong jika tidak ingin mengubah logo. (Format:
                            JPG/PNG, Maks: 2MB).</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200"><i
                            class="fa-solid fa-file-invoice mr-2 text-blue-500"></i> Header / Kop Cetak PDF</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Baris 1: Nama
                            Divisi / Kementrian</label>
                        <input type="text" name="kop_divisi"
                            value="{{ old('kop_divisi', $pengaturan->kop_divisi ?? 'DIVISI SEKRETARIS PERUSAHAAN') }}"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Baris 2: Nama
                            Instansi Utama</label>
                        <input type="text" name="kop_instansi"
                            value="{{ old('kop_instansi', $pengaturan->kop_instansi ?? 'BANK KALSEL') }}" required
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-bold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Baris 3: Alamat
                            Lengkap</label>
                        <textarea name="kop_alamat" rows="2"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('kop_alamat', $pengaturan->kop_alamat ?? 'Jl. Lambung Mangkurat No.7, Kertak Baru Ilir, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70111') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Baris 4: Kontak
                            (Telp / Email)</label>
                        <input type="text" name="kop_kontak"
                            value="{{ old('kop_kontak', $pengaturan->kop_kontak ?? 'Telp: 05113350725 | Email: costumercare@bankkalsel.co.id') }}"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200"><i
                            class="fa-solid fa-signature mr-2 text-teal-500"></i> Pengesahan / Penandatangan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan
                            Penandatangan</label>
                        <input type="text" name="ttd_jabatan"
                            value="{{ old('ttd_jabatan', $pengaturan->ttd_jabatan ?? 'KEPALA BAGIAN') }}" required
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap
                            Penandatangan</label>
                        <input type="text" name="ttd_nama_penandatangan"
                            value="{{ old('ttd_nama_penandatangan', $pengaturan->ttd_nama_penandatangan ?? 'NINDRI YUWANI') }}"
                            required
                            class="w-full text-sm font-bold border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIP / NIK
                            (Opsional)</label>
                        <input type="text" name="ttd_nip" value="{{ old('ttd_nip', $pengaturan->ttd_nip ?? '') }}"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika penandatangan tidak menggunakan NIP.
                        </p>
                    </div>

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Tanda
                            Tangan / Stempel</label>
                        @if (isset($pengaturan->ttd_image) && file_exists(public_path('images/' . $pengaturan->ttd_image)))
                            <div class="mb-3 p-2 border border-gray-200 rounded-lg bg-gray-50 inline-block">
                                <img src="{{ asset('images/' . $pengaturan->ttd_image) }}" alt="TTD"
                                    class="h-12 object-contain">
                            </div>
                        @endif
                        <input type="file" name="ttd_image" accept="image/png, image/jpeg, image/jpg"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                        <p class="text-xs text-orange-500 mt-1">Gunakan gambar background transparan (PNG) untuk hasil
                            cetak PDF terbaik.</p>
                    </div>

                </div>
            </div>

        </div>

        <div
            class="mt-6 p-6 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 flex justify-end">
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition flex items-center">
                <i class="fa-solid fa-save mr-2"></i> Simpan Seluruh Pengaturan
            </button>
        </div>
    </form>

</x-app-layout>
