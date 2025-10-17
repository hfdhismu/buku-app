<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])->get();
        $buku = Buku::all();
        $users = User::all();

        return view('peminjaman.index', compact('peminjaman', 'buku', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required',
            'user_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ]);

        Peminjaman::create($request->all());

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil ditambahkan!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
