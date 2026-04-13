<div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div>
            <x-forms.label for="jenis_surat_id" value="Jenis Surat" :required="true" />
            <x-forms.dropdown name="jenis_surat_id">
                <option value="">-- Pilih Jenis --</option>
                @foreach($jenisSurat as $item)
                    <option value="{{ $item->id }}" {{ old('jenis_surat_id', $surat->jenis_surat_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_jenis }}
                    </option>
                @endforeach
            </x-forms.dropdown>
        </div>

        <div>
            <x-forms.label for="nomor_agenda" value="Nomor Agenda" :required="true" />
            <x-forms.input name="nomor_agenda" :value="old('nomor_agenda', $surat->nomor_agenda ?? $autoAgenda)" readonly class="bg-gray-100" />
        </div>

        <div>
            <x-forms.label for="nomor_surat" value="Nomor Surat (Opsional)" />
            <x-forms.input name="nomor_surat" :value="old('nomor_surat', $surat->nomor_surat ?? '')" placeholder="Contoh: 001/BKS/2026" />
        </div>

        <div>
            <x-forms.label for="tanggal_diterima" value="Tanggal Diterima" :required="true" />
            <x-forms.input type="date" name="tanggal_diterima" :value="old('tanggal_diterima', $surat->tanggal_diterima ?? date('Y-m-d'))" />
        </div>

        <div>
            <x-forms.label for="instansi" value="Asal / Tujuan Instansi" />
            <x-forms.input name="dari_instansi" :value="old('dari_instansi', $surat->dari_instansi ?? $surat->ke_instansi ?? '')" placeholder="Nama Instansi" />
        </div>

        <div>
            <x-forms.label for="lampiran_file" value="Lampiran File (PDF/Gambar)" />
            <x-forms.upload-file name="lampiran_file" accept=".pdf,.jpg,.jpeg,.png" />
            @if(isset($surat) && $surat->lampiran_file)
                <p class="mt-2 text-xs text-gray-500 italic">File saat ini: {{ $surat->lampiran_file }}</p>
            @endif
        </div>
    </div>

    <div class="mb-6">
        <x-forms.label for="perihal" value="Perihal / Ringkasan Isi Surat" :required="true" />
        <x-forms.textarea name="perihal" rows="4" placeholder="Masukkan ringkasan perihal surat...">{{ old('perihal', $surat->perihal ?? '') }}</x-forms.textarea>
    </div>

    <div class="mb-6">
        <x-forms.label for="alamat_surat" value="Alamat Pengirim/Penerima" />
        <x-forms.textarea name="alamat_surat" rows="2">{{ old('alamat_surat', $surat->alamat_surat ?? '') }}</x-forms.textarea>
    </div>

    <div class="flex justify-end gap-4">
        <a href="{{ route('surat.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fa-solid fa-arrow-left mr-2"></i> Batal
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
            <i class="fa-solid fa-save mr-2"></i> Simpan Data
        </button>
    </div>
</div>