<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PengaturanController extends Controller
{
    public function index()
    {
        // Ambil data pengaturan pertama, jika kosong buat instance baru
        $pengaturan = Pengaturan::first() ?? new Pengaturan();

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'             => 'required|string|max:100',       // ← sudah benar
            'kop_instansi'         => 'required|string|max:255',
            'kop_divisi'           => 'nullable|string|max:255',
            'kop_alamat'           => 'nullable|string',
            'kop_kontak'           => 'nullable|string',
            'ttd_jabatan'          => 'required|string|max:255',
            'ttd_nama_penandatangan' => 'required|string|max:255',
            'ttd_nip'              => 'nullable|string|max:50',
            'logo_instansi'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pengaturan = Pengaturan::first() ?? new Pengaturan();

        $pengaturan->app_name              = $request->app_name;
        $pengaturan->kop_instansi          = $request->kop_instansi;
        $pengaturan->kop_divisi            = $request->kop_divisi;
        $pengaturan->kop_alamat            = $request->kop_alamat;
        $pengaturan->kop_kontak            = $request->kop_kontak;
        $pengaturan->ttd_jabatan           = $request->ttd_jabatan;
        $pengaturan->ttd_nama_penandatangan = $request->ttd_nama_penandatangan;
        $pengaturan->ttd_nip               = $request->ttd_nip;

        // Handle Upload Logo
        if ($request->hasFile('logo_instansi')) {
            if ($pengaturan->logo_instansi && File::exists(public_path('images/' . $pengaturan->logo_instansi))) {
                File::delete(public_path('images/' . $pengaturan->logo_instansi));
            }
            $file = $request->file('logo_instansi');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $pengaturan->logo_instansi = $filename;
        }

        $pengaturan->save();

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan sistem berhasil diperbarui!');
    }
}
