<?php

namespace App\Http\Requests\Disposisi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDisposisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'surat_id'   => 'required|exists:surat,id',
            'ke_user_id' => 'required|exists:users,id|different:dari_user_id',
            'catatan'    => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'surat_id.required'   => 'Data surat tidak ditemukan.',
            'ke_user_id.required' => 'Tujuan disposisi (Penerima) wajib dipilih.',
            'ke_user_id.different' => 'Tidak dapat mendisposisikan surat ke diri sendiri.',
        ];
    }

    // Menambahkan data pengirim secara otomatis ke dalam request
    protected function prepareForValidation()
    {
        $this->merge([
            'dari_user_id' => Auth::id(),
        ]);
    }
}
