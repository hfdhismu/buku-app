<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    // Menampilkan semua data profil
    public function index()
    {
        $profil = Profil::with('user')->get();
        $users = User::all();
        return view('admin.profil.index', compact('profil', 'users'));
    }

    // Menyimpan data profil baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
        ]);

        Profil::create($request->all());

        return redirect()->route('profil.index')->with('success', 'Profil berhasil ditambahkan!');
    }

    // Memperbarui data profil
    public function update(Request $request, Profil $profil)
    {
        $request->validate([
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
        ]);

        $profil->update($request->only('alamat','telepon'));

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui!');
    }

    // Menghapus data profil
    public function destroy(Profil $profil)
    {
        $profil->delete();
        return redirect()->route('profil.index')->with('success', 'Profil berhasil dihapus!');
    }
}
