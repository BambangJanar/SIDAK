<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bagian;
use Illuminate\Http\Request;

class BagianController extends Controller
{
    public function index(Request $request)
    {
        $query = Bagian::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_bagian', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
        }

        $bagian = $query->latest()->paginate(10);

        return view('admin.bagian.index', compact('bagian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bagian' => 'required|string|max:255|unique:bagian,nama_bagian',
            'keterangan' => 'nullable|string'
        ], [
            'nama_bagian.unique' => 'Nama divisi/bagian ini sudah ada di database.'
        ]);

        Bagian::create($request->all());

        return redirect()->route('bagian.index')->with('success', 'Divisi/Bagian berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $bagian = Bagian::findOrFail($id);

        $request->validate([
            'nama_bagian' => 'required|string|max:255|unique:bagian,nama_bagian,' . $id,
            'keterangan' => 'nullable|string'
        ]);

        $bagian->update($request->all());

        return redirect()->route('bagian.index')->with('success', 'Divisi/Bagian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bagian = Bagian::findOrFail($id);

        // Proteksi: Jangan hapus jika ada user yang masih terikat di divisi ini
        // (Pastikan kamu sudah membuat relasi hasMany 'users' di model Bagian)
        if ($bagian->users()->count() > 0) {
            return redirect()->route('bagian.index')->with('error', 'Gagal dihapus! Divisi ini masih memiliki pegawai yang aktif. Pindahkan pegawainya terlebih dahulu.');
        }

        $bagian->delete();

        return redirect()->route('bagian.index')->with('success', 'Divisi/Bagian berhasil dihapus!');
    }
}
