<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Pusat Laporan
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Pilih jenis laporan untuk melihat pratinjau data dan mencetak PDF.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg mr-4 dark:bg-blue-900/30 dark:text-blue-400">
                    <i class="fa-solid fa-inbox text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Surat Masuk</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Pratinjau dan cetak rekapitulasi data surat
                masuk berdasarkan rentang tanggal.</p>

            <a href="{{ route('laporan.surat-masuk') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-lg mr-4 dark:bg-green-900/30 dark:text-green-400">
                    <i class="fa-solid fa-paper-plane text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Surat Keluar</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Pratinjau dan cetak rekapitulasi data surat
                keluar yang diterbitkan instansi.</p>

            <a href="{{ route('laporan.surat-keluar') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div
                    class="p-3 bg-purple-50 text-purple-600 rounded-lg mr-4 dark:bg-purple-900/30 dark:text-purple-400">
                    <i class="fa-solid fa-envelopes-bulk text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Semua Surat</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Pratinjau dan cetak rekapitulasi seluruh
                data surat (Masuk & Keluar) dalam satu laporan terpadu.</p>

            <a href="{{ route('laporan.semua-surat') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg shadow-sm hover:bg-purple-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div
                    class="p-3 bg-emerald-50 text-emerald-600 rounded-lg mr-4 dark:bg-emerald-900/30 dark:text-emerald-400">
                    <i class="fa-solid fa-check-double text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Surat Disetujui</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Pratinjau dan cetak data surat yang telah
                mendapatkan status persetujuan (ACC) dari pimpinan.</p>

            <a href="{{ route('laporan.surat-disetujui') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-lg mr-4 dark:bg-red-900/30 dark:text-red-400">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Surat Ditolak</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Pratinjau dan cetak data surat yang tidak
                disetujui beserta alasan penolakannya.</p>

            <a href="{{ route('laporan.surat-ditolak') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div
                    class="p-3 bg-purple-50 text-purple-600 rounded-lg mr-4 dark:bg-purple-900/30 dark:text-purple-400">
                    <i class="fa-solid fa-box-archive text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Arsip Digital</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Daftar seluruh surat yang telah masuk ke
                dalam
                brankas arsip digital instansi.</p>

            <a href="{{ route('laporan.arsip-surat') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-teal-50 text-teal-600 rounded-lg mr-4 dark:bg-teal-900/30 dark:text-teal-400">
                    <i class="fa-solid fa-share-nodes text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Disposisi Surat</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Laporan riwayat alur disposisi surat beserta
                status penanganannya oleh setiap divisi.</p>

            <a href="{{ route('laporan.monitoring-disposisi') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div
                    class="p-3 bg-orange-50 text-orange-600 rounded-lg mr-4 dark:bg-orange-900/30 dark:text-orange-400">
                    <i class="fa-solid fa-user-check text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Kinerja Pegawai</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Statistik penyelesaian tugas/disposisi oleh
                setiap pegawai untuk evaluasi beban kerja.</p>

            <a href="{{ route('laporan.kinerja-pegawai') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-slate-100 text-slate-700 rounded-lg mr-4 dark:bg-slate-800 dark:text-slate-300">
                    <i class="fa-solid fa-clipboard-list text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Log Aktivitas Sistem</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6 flex-1 dark:text-gray-400">Jejak rekam digital (audit trail) dari
                seluruh aktivitas pengguna di dalam aplikasi.</p>

            <a href="{{ route('laporan.log-aktivitas') }}"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-slate-700 rounded-lg shadow-sm hover:bg-slate-800 transition flex justify-center items-center mt-auto">
                <i class="fa-solid fa-eye mr-2"></i> Buka Laporan
            </a>
        </div>

    </div>

</x-app-layout>
