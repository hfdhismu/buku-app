<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // 📄 Tampilkan semua data peminjaman (untuk admin)
    public function index()
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])->get();
        $buku = Buku::all();
        $users = User::all();

        return view('admin.peminjaman.index', compact('peminjaman', 'buku', 'users'));
    }

    // ➕ Tambah peminjaman baru oleh admin
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        // Admin hanya menentukan tanggal pinjam, tanggal kembali dikosongkan
        Peminjaman::create([
            'buku_id' => $request->buku_id,
            'user_id' => $request->user_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => null,
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil ditambahkan!');
    }

    // 🗑️ Hapus data peminjaman
    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
