<x-app-layout>
    <x-slot name="header">
        Tambah Surat Baru
    </x-slot>

    <div class="py-4">
        <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.surat._form')
        </form>
    </div>
</x-app-layout>