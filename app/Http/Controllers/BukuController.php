<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    // Menampilkan semua data buku
    public function index(Request $request)
    {
        $search = $request->get('search');

        $buku = Buku::when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('tahun_terbit', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%");
            })
            ->get();

        // Jika request-nya AJAX, hanya kirim tabel (untuk live search)
        if ($request->ajax()) {
            return view('admin.buku.table', compact('buku'))->render();
        }

        // Tampilan normal
        $users = User::all();
        return view('admin.buku.index', compact('buku', 'users'));
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

        // Use the authenticated user ID
        $request['user_id'] = Auth::id();

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
