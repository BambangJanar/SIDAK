<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Peran;
use App\Models\Bagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['peran', 'bagian']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->latest()->paginate(10);

        // Ambil data untuk dropdown di form Modal
        $peran = Peran::all();
        $bagian = Bagian::all();

        return view('admin.users.index', compact('users', 'peran', 'bagian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'peran_id' => 'required|integer',
            'bagian_id' => 'nullable|integer'
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'peran_id' => $request->peran_id,
            'bagian_id' => $request->bagian_id,
            'status' => 'active' // Default status
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'peran_id' => 'required|integer',
            'bagian_id' => 'nullable|integer'
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'peran_id' => $request->peran_id,
            'bagian_id' => $request->bagian_id,
        ];

        // Update password hanya jika form password diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi: Jangan biarkan admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Gagal! Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
