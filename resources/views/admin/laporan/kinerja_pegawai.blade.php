<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Laporan Kinerja Pegawai</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Statistik distribusi dan penyelesaian disposisi
                surat per pegawai.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan.cetak.kinerja-pegawai') }}" target="_blank"
                class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition flex items-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-bold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-6 py-4">PEGAWAI / JABATAN</th>
                        <th class="px-6 py-4 text-center">TOTAL TUGAS</th>
                        <th class="px-6 py-4 text-center">SELESAI</th>
                        <th class="px-6 py-4 text-center">PROSES</th>
                        <th class="px-6 py-4 text-center">PENDING</th>
                        <th class="px-6 py-4 text-center">PROGRES (%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($pegawai as $p)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold">{{ $p->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $p->peran->nama_peran }} -
                                    {{ $p->bagian->nama_bagian ?? 'Umum' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold">{{ $p->total_tugas }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-green-600 font-bold">{{ $p->selesai }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-orange-500 font-bold">{{ $p->proses }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-blue-500 font-bold">{{ $p->pending }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $persen = $p->total_tugas > 0 ? round(($p->selesai / $p->total_tugas) * 100) : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-emerald-500 h-2 rounded-full"
                                            style="width: {{ $persen }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">{{ $persen }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
