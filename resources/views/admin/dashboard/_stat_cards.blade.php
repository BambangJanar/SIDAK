<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500 flex items-center justify-center w-12 h-12">
            <i class="fa-solid fa-envelope-open-text text-xl"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Surat Masuk</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalSuratMasuk }}</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500 flex items-center justify-center w-12 h-12">
            <i class="fa-solid fa-paper-plane text-xl"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Surat Keluar</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalSuratKeluar }}</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500 flex items-center justify-center w-12 h-12">
            <i class="fa-solid fa-share-nodes text-xl"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Disposisi</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalDisposisi }}</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500 flex items-center justify-center w-12 h-12">
            <i class="fa-solid fa-bell text-xl"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Surat Baru (Pending)</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $suratBaru }}</p>
        </div>
    </div>
</div>