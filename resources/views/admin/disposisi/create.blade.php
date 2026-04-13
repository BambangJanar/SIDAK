<x-app-layout>
    <x-slot name="header">Kirim Disposisi Surat</x-slot>

    <div class="py-4">
        <div class="p-4 mb-6 bg-purple-50 border-l-4 border-purple-500 rounded dark:bg-gray-700">
            <h4 class="text-sm font-bold text-purple-800 dark:text-purple-300 uppercase">Surat yang akan didisposisikan:
            </h4>
            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                <strong>Agenda:</strong> {{ $surat->nomor_agenda }} <br>
                <strong>Perihal:</strong> {{ $surat->perihal }}
            </p>
        </div>

        <form action="{{ route('disposisi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="surat_id" value="{{ $surat->id }}">

            <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="mb-6">
                    <x-forms.label for="ke_user_id" value="Pilih Penerima (Tujuan Disposisi)" :required="true" />
                    <x-forms.dropdown name="ke_user_id">
                        <option value="">-- Pilih Rekan Kerja / Pejabat --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nama_lengkap }}
                                ({{ $user->peran->nama_peran }})</option>
                        @endforeach
                    </x-forms.dropdown>
                </div>

                <div class="mb-6">
                    <x-forms.label for="catatan" value="Instruksi / Catatan Tambahan" />
                    <x-forms.textarea name="catatan" rows="5"
                        placeholder="Tuliskan instruksi pekerjaan di sini..."></x-forms.textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('surat.show', $surat->id) }}"
                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Disposisi
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
