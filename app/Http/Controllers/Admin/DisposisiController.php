<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\Surat;
use App\Models\User;
use App\Http\Requests\Disposisi\StoreDisposisiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposisiController extends Controller
{
    // 1. Menampilkan Halaman Disposisi Masuk
    public function masuk()
    {
        $disposisi = Disposisi::with(['surat.jenisSurat', 'pengirim'])
            ->where('ke_user_id', Auth::id())
            ->orderBy('tanggal_disposisi', 'desc')
            ->paginate(10);

        return view('admin.disposisi.masuk', compact('disposisi'));
    }

    // 2. Menampilkan Halaman Disposisi Keluar
    public function keluar()
    {
        $disposisi = Disposisi::with(['surat.jenisSurat', 'penerima'])
            ->where('dari_user_id', Auth::id())
            ->orderBy('tanggal_disposisi', 'desc')
            ->paginate(10);

        return view('admin.disposisi.keluar', compact('disposisi'));
    }

    // 3. Menampilkan Form Kirim Disposisi
    public function create($surat_id)
    {
        $surat = Surat::findOrFail($surat_id);

        // Ambil daftar user selain diri sendiri untuk tujuan disposisi
        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('admin.disposisi.create', compact('surat', 'users'));
    }

    // 4. Menyimpan Data Disposisi Baru
    public function store(StoreDisposisiRequest $request)
    {
        $validatedData = $request->validated();

        // Pastikan status_disposisi default adalah 'dikirim'
        $validatedData['status_disposisi'] = 'dikirim';
        $validatedData['dari_user_id'] = Auth::id();
        $validatedData['tanggal_disposisi'] = now(); // Menggunakan Asia/Makassar dari .env

        Disposisi::create($validatedData);

        // Update status surat utama menjadi 'proses' karena sudah mulai didisposisikan
        Surat::where('id', $request->surat_id)->update(['status_surat' => 'proses']);

        return redirect()->route('disposisi.keluar')->with('success', 'Surat berhasil didisposisikan!');
    }

    // 5. Update Status Disposisi (Respon dari Penerima)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_disposisi' => 'required|in:diterima,diproses,selesai,ditolak'
        ]);

        $disposisi = Disposisi::findOrFail($id);

        // Keamanan: Hanya penerima yang boleh mengubah status
        if ($disposisi->ke_user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk merespon disposisi ini.');
        }

        $disposisi->update([
            'status_disposisi' => $request->status_disposisi,
            'tanggal_respon'   => now()
        ]);

        return back()->with('success', 'Status disposisi berhasil diperbarui menjadi: ' . ucfirst($request->status_disposisi));
    }
    public function monitoring(Request $request)
    {
        // Proteksi: Hanya Superadmin (1) & Admin (2) yang bisa akses
        if (!in_array(Auth::user()->peran_id, [1, 2])) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin melihat halaman monitoring.');
        }

        // Ambil semua disposisi dengan relasinya
        $query = Disposisi::with(['surat', 'pengirim', 'penerima']);

        // Fitur Pencarian (Search by Nomor Surat / Perihal)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('surat', function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Status
        if ($request->filled('status_disposisi')) {
            $query->where('status_disposisi', $request->status_disposisi);
        }

        $disposisi = $query->orderBy('tanggal_disposisi', 'desc')->paginate(10);

        return view('admin.disposisi.monitoring', compact('disposisi'));
    }
}
