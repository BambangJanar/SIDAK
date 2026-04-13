<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya user yang login yang boleh akses
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'nomor_surat'    => 'nullable|string|max:100',
            'nomor_agenda'   => 'required|string|max:50|unique:surat,nomor_agenda',
            'tanggal_diterima' => 'required|date',
            'dari_instansi'  => 'nullable|string|max:200',
            'ke_instansi'    => 'nullable|string|max:200',
            'alamat_surat'   => 'nullable|string',
            'perihal'        => 'required|string',
            // Validasi file: pdf, jpg, jpeg, png max 5MB
            'lampiran_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_surat_id.required' => 'Jenis surat wajib dipilih.',
            'nomor_agenda.required'   => 'Nomor agenda wajib diisi.',
            'nomor_agenda.unique'     => 'Nomor agenda ini sudah digunakan, silakan masukkan nomor lain.',
            'perihal.required'        => 'Perihal surat wajib diisi.',
            'lampiran_file.mimes'     => 'Format file harus berupa PDF, JPG, atau PNG.',
            'lampiran_file.max'       => 'Ukuran file maksimal adalah 5MB.',
        ];
    }
}
