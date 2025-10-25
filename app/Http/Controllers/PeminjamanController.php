<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // 📄 Tampilkan semua data peminjaman (untuk admin)
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Query utama + relasi
        $peminjaman = Peminjaman::with(['buku', 'user'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                })
                ->orWhere('tanggal_pinjam', 'like', "%{$search}%")
                ->orWhere('tanggal_kembali', 'like', "%{$search}%");
            })
            ->get();

        $buku = Buku::all();

        // Hanya ambil user dengan role 'user' (bukan admin/petugas)
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'user');
        })->get();

        // Jika request-nya AJAX (live search)
        if ($request->ajax()) {
            return view('admin.peminjaman.table', compact('peminjaman'))->render();
        }

        // Tampilan normal
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
