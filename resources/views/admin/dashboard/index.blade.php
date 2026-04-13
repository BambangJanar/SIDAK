<x-app-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <div class="w-full mb-8">
        @include('admin.dashboard._stat_cards')

        <div class="my-8"></div>

        @include('admin.dashboard._recent_surat')
    </div>
</x-app-layout>