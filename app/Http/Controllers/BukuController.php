<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Menampilkan semua data buku
    public function index()
    {
        $buku = Buku::with('user')->get();
        $users = User::all();
        return view('buku.index', compact('buku', 'users'));
    }

    // Menyimpan data buku baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'penerbit' => 'required',
        ]);

        // Kalau mau otomatis dikaitkan ke user tertentu (misalnya admin login)
        // Kamu bisa ganti dengan auth()->id()
        $request['user_id'] = $request->user_id ?? 1;

        Buku::create($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    // Memperbarui data buku
    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'penerbit' => 'required',
        ]);

        $buku->update($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    // Menghapus data buku
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
