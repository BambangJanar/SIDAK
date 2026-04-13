<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil surat yang statusnya 'arsip'
        $query = Surat::with('jenisSurat')->where('status_surat', 'arsip');

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                    ->orWhere('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan waktu diarsipkan (updated_at)
        $arsip = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('admin.arsip.index', compact('arsip'));
    }

    public function restore($id)
    {
        $surat = Surat::findOrFail($id);

        if ($surat->status_surat !== 'arsip') {
            return back()->with('error', 'Data ini bukan arsip.');
        }

        // Kembalikan ke status sebelum diarsipkan, atau default ke 'baru' jika kosong
        $surat->update([
            'status_surat' => $surat->status_sebelum_arsip ?? 'baru',
            'status_sebelum_arsip' => null
        ]);

        return back()->with('success', 'Surat berhasil dikembalikan (Restore) ke daftar aktif!');
    }
}
