<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Http\Requests\Surat\StoreSuratRequest;
use App\Http\Requests\Surat\UpdateSuratRequest;
use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // Untuk hapus file lama

class SuratController extends Controller
{
    public function index()
    {
        // Ambil data surat beserta relasi jenis dan pembuatnya (Eager Loading)
        $surat = Surat::with(['jenisSurat', 'pembuat'])->latest()->paginate(10);
        return view('admin.surat.index', compact('surat'));
    }

    public function create()
    {
        $jenisSurat = JenisSurat::all();
        // Generate Nomor Agenda Otomatis (Format: AGD-TahunBulanTanggal-Random)
        $autoAgenda = 'AGD-' . date('Ymd') . '-' . rand(100, 999);

        return view('admin.surat.create', compact('jenisSurat', 'autoAgenda'));
    }

    public function store(StoreSuratRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['dibuat_oleh'] = Auth::id();
        $validatedData['status_surat'] = 'baru'; // Default status

        // Handle File Upload (Old School Method)
        if ($request->hasFile('lampiran_file')) {
            $file = $request->file('lampiran_file');
            // Format nama: uniqid_timestamp.ekstensi
            $filename = uniqid('surat_') . '_' . time() . '.' . $file->getClientOriginalExtension();
            // Pindah ke folder public/uploads/
            $file->move(public_path('uploads'), $filename);

            $validatedData['lampiran_file'] = $filename;
        }

        Surat::create($validatedData);

        return redirect()->route('surat.index')->with('success', 'Data Surat berhasil ditambahkan!');
    }

    public function show($id)
    {
        $surat = Surat::with(['jenisSurat', 'pembuat', 'disposisi'])->findOrFail($id);

        // Ambil data user aktif untuk isi dropdown di modal
        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('admin.surat.show', compact('surat', 'users'));
    }

    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        $jenisSurat = JenisSurat::all();
        return view('admin.surat.edit', compact('surat', 'jenisSurat'));
    }

    public function update(UpdateSuratRequest $request, $id)
    {
        $surat = Surat::findOrFail($id);
        $validatedData = $request->validated();

        // Handle Update File
        if ($request->hasFile('lampiran_file')) {
            // 1. Hapus file lama jika ada
            if ($surat->lampiran_file && File::exists(public_path('uploads/' . $surat->lampiran_file))) {
                File::delete(public_path('uploads/' . $surat->lampiran_file));
            }

            // 2. Upload file baru
            $file = $request->file('lampiran_file');
            $filename = uniqid('surat_') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            $validatedData['lampiran_file'] = $filename;
        }

        $surat->update($validatedData);

        return redirect()->route('surat.index')->with('success', 'Data Surat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);

        // Hapus file fisik jika ada
        if ($surat->lampiran_file && File::exists(public_path('uploads/' . $surat->lampiran_file))) {
            File::delete(public_path('uploads/' . $surat->lampiran_file));
        }

        $surat->delete();

        return redirect()->route('surat.index')->with('success', 'Data Surat berhasil dihapus!');
    }

    // Tambahkan method ini untuk fitur Arsip
    public function archive($id)
    {
        $surat = Surat::findOrFail($id);

        // Jangan arsipkan jika sudah diarsipkan
        if ($surat->status_surat === 'arsip') {
            return redirect()->route('surat.index')->with('error', 'Surat ini sudah berada di arsip.');
        }

        // Simpan status saat ini ke kolom 'status_sebelum_arsip'
        $surat->update([
            'status_sebelum_arsip' => $surat->status_surat,
            'status_surat' => 'arsip'
        ]);

        return redirect()->route('surat.index')->with('success', 'Surat berhasil dipindahkan ke Arsip Digital!');
    }

    // Tambahkan method ini untuk fitur Setujui / Tolak
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_surat' => 'required|in:disetujui,ditolak',
            'alasan_penolakan' => 'nullable|string'
        ]);

        // Keamanan tambahan: Pastikan hanya Superadmin (1) & Admin (2) yang bisa mengubah
        if (!in_array(Auth::user()->peran_id, [1, 2])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyetujui/menolak surat.');
        }

        $surat = Surat::findOrFail($id);

        $surat->update([
            'status_surat' => $request->status_surat,
            'alasan_penolakan' => $request->status_surat === 'ditolak' ? $request->alasan_penolakan : null
        ]);

        $pesan = $request->status_surat === 'disetujui'
            ? 'Surat berhasil disetujui!'
            : 'Surat telah ditolak.';

        return back()->with('success', $pesan);
    }
}
