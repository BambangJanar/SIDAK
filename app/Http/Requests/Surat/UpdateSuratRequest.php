<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        // Ambil ID surat yang sedang diedit dari URL route
        $suratId = $this->route('surat');

        return [
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'nomor_surat'    => 'nullable|string|max:100',
            // Ignore unique rule untuk ID ini
            'nomor_agenda'   => 'required|string|max:50|unique:surat,nomor_agenda,' . $suratId,
            'tanggal_diterima' => 'required|date',
            'dari_instansi'  => 'nullable|string|max:200',
            'ke_instansi'    => 'nullable|string|max:200',
            'alamat_surat'   => 'nullable|string',
            'perihal'        => 'required|string',
            'lampiran_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
