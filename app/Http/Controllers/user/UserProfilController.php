<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Profil;

class UserProfilController extends Controller
{
    // Menampilkan profil user yang sedang login
    public function index()
    {
        $user = Auth::user(); // ambil user yang login
        $profil = $user->profil; // ambil relasi profilnya

        return view('user.profil.index', compact('user', 'profil'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
        ]);

        // Jika profil belum ada, buat baru
        $profil = $user->profil ?? new Profil(['user_id' => $user->id]);
        $profil->alamat = $request->alamat;
        $profil->telepon = $request->telepon;
        $profil->save();

        return redirect()->route('profil.user')->with('success', 'Profil berhasil diperbarui!');
    }
}
