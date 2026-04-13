<aside class="z-20 hidden w-64 overflow-y-auto bg-white border-r border-gray-100 md:block flex-shrink-0">
    <div class="py-4 text-gray-500">
        <a class="flex items-center px-6 text-lg font-bold text-gray-800" href="{{ route('dashboard') }}">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            SIDAK Kalsel
        </a>
        
        @include('layouts._menu_items')
    </div>
</aside>

<div
    x-show="isSideMenuOpen"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center md:hidden"
    @click="isSideMenuOpen = false"
></div>

<aside
    class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white md:hidden"
    x-show="isSideMenuOpen"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20"
    @click.away="isSideMenuOpen = false"
    @keydown.escape="isSideMenuOpen = false"
>
    <div class="py-4 text-gray-500">
        <a class="flex items-center px-6 text-lg font-bold text-gray-800" href="{{ route('dashboard') }}">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            SIDAK Kalsel
        </a>
        
        @include('layouts._menu_items')
    </div>
</aside>