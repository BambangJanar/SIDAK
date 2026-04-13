<x-app-layout>
    <x-slot name="header">
        Edit Surat: {{ $surat->nomor_agenda }}
    </x-slot>

    <div class="py-4">
        <form action="{{ route('surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.surat._form')
        </form>
    </div>
</x-app-layout>