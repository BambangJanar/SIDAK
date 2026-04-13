<ul class="mt-6">
    <li class="relative px-6 py-3">
        @if (request()->routeIs('dashboard'))
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('dashboard') ? 'text-gray-800' : '' }}"
            href="{{ route('dashboard') }}">
            <i class="fa-solid fa-chart-line text-lg w-5 text-center"></i>
            <span class="ml-4">Dashboard</span>
        </a>
    </li>
</ul>

<div class="px-6 my-4 text-xs font-bold tracking-wider text-gray-400 uppercase">Persuratan</div>
<ul>
    <li class="relative px-6 py-3">
        @if (request()->routeIs('surat.*'))
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('surat.*') ? 'text-gray-800' : '' }}"
            href="{{ route('surat.index') }}">
            <i class="fa-solid fa-envelopes-bulk text-lg w-5 text-center"></i>
            <span class="ml-4">Semua Surat</span>
        </a>
    </li>

    <li class="relative px-6 py-3">
        @if (request()->routeIs('disposisi.masuk'))
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('disposisi.masuk') ? 'text-gray-800' : '' }}"
            href="{{ route('disposisi.masuk') }}">
            <i class="fa-solid fa-inbox text-lg w-5 text-center"></i>
            <span class="ml-4">Disposisi Masuk</span>
        </a>
    </li>

    <li class="relative px-6 py-3">
        @if (request()->routeIs('disposisi.keluar'))
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('disposisi.keluar') ? 'text-gray-800' : '' }}"
            href="{{ route('disposisi.keluar') }}">
            <i class="fa-solid fa-paper-plane text-lg w-5 text-center"></i>
            <span class="ml-4">Disposisi Keluar</span>
        </a>
    </li>

    @if (Auth::user()->peran_id == 1 || Auth::user()->peran_id == 2)
        <li class="relative px-6 py-3">
            @if (request()->routeIs('disposisi.monitoring'))
                <span class="absolute inset-y-0 left-0 w-1 bg-teal-500 rounded-tr-lg rounded-br-lg"
                    aria-hidden="true"></span>
            @endif
            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('disposisi.monitoring') ? 'text-teal-600' : '' }}"
                href="{{ route('disposisi.monitoring') }}">
                <i class="fa-solid fa-arrow-right-arrow-left text-lg w-5 text-center"></i>
                <span class="ml-4">Monitoring Disposisi</span>
            </a>
        </li>
    @endif
</ul>

<div class="px-6 my-4 text-xs font-bold tracking-wider text-gray-400 uppercase">Laporan & Arsip</div>
<ul>
    <li class="relative px-6 py-3">
        @if (request()->routeIs('laporan.*'))
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('laporan.*') ? 'text-gray-800' : '' }}"
            href="{{ route('laporan.index') }}">
            <i class="fa-solid fa-chart-pie text-lg w-5 text-center"></i>
            <span class="ml-4">Pusat Laporan</span>
        </a>
    </li>

    <li class="relative px-6 py-3">
        @if (request()->routeIs('arsip.*'))
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('arsip.*') ? 'text-gray-800' : '' }}"
            href="{{ route('arsip.index') }}">
            <i class="fa-solid fa-box-archive text-lg w-5 text-center"></i>
            <span class="ml-4">Arsip Digital</span>
        </a>
    </li>
</ul>

@if (Auth::user()->peran_id == 1 || Auth::user()->peran_id == 2)
    <div class="px-6 my-4 text-xs font-bold tracking-wider text-gray-400 uppercase">Administrator</div>
    <ul>
        <li class="relative px-6 py-3">
            @if (request()->routeIs('jenis-surat.*'))
                <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                    aria-hidden="true"></span>
            @endif
            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('jenis-surat.*') ? 'text-gray-800' : '' }}"
                href="{{ route('jenis-surat.index') }}">
                <i class="fa-solid fa-tags text-lg w-5 text-center"></i>
                <span class="ml-4">Master Jenis Surat</span>
            </a>
        </li>

        <li class="relative px-6 py-3">
            @if (request()->routeIs('users.*'))
                <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                    aria-hidden="true"></span>
            @endif
            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('users.*') ? 'text-gray-800' : '' }}"
                href="{{ route('users.index') }}">
                <i class="fa-solid fa-users-gear text-lg w-5 text-center"></i>
                <span class="ml-4">Manajemen User</span>
            </a>
        </li>
        <li class="relative px-6 py-3">
            @if (request()->routeIs('bagian.*'))
                <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                    aria-hidden="true"></span>
            @endif
            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('bagian.*') ? 'text-gray-800' : '' }}"
                href="{{ route('bagian.index') }}">
                <i class="fa-solid fa-sitemap text-lg w-5 text-center"></i>
                <span class="ml-4">Master Divisi/Bagian</span>
            </a>
        </li>
        <li class="relative px-6 py-3">
            @if (request()->routeIs('pengaturan.*'))
                <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                    aria-hidden="true"></span>
            @endif
            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 {{ request()->routeIs('pengaturan.*') ? 'text-gray-800' : '' }}"
                href="{{ route('pengaturan.index') }}">
                <i class="fa-solid fa-sliders text-lg w-5 text-center"></i>
                <span class="ml-4">Pengaturan Sistem</span>
            </a>
        </li>
    </ul>
@endif

<div class="px-6 my-8">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
            Keluar Sistem
            <i class="fa-solid fa-right-from-bracket ml-2"></i>
        </button>
    </form>
</div>
