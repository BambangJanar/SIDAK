<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisSurat::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_jenis', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
        }

        $jenisSurat = $query->latest()->paginate(10);

        return view('admin.jenis_surat.index', compact('jenisSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_surat,nama_jenis',
            'keterangan' => 'nullable|string'
        ], [
            'nama_jenis.unique' => 'Nama jenis surat ini sudah ada di database.'
        ]);

        JenisSurat::create($request->all());

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisSurat::findOrFail($id);

        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_surat,nama_jenis,' . $id,
            'keterangan' => 'nullable|string'
        ]);

        $jenis->update($request->all());

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenis = JenisSurat::findOrFail($id);

        // Opsional: Cek apakah jenis surat sedang dipakai di tabel surat
        if ($jenis->surat()->count() > 0) {
            return redirect()->route('jenis-surat.index')->with('error', 'Gagal dihapus! Jenis surat ini sedang digunakan pada data persuratan.');
        }

        $jenis->delete();

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil dihapus!');
    }
}
