<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;

class UserPeminjamanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman milik user yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua peminjaman user ini
        $peminjaman = Peminjaman::where('user_id', $user->id)->latest()->get();

        return view('user.peminjaman.index', compact('peminjaman'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($peminjaman->tanggal_kembali) {
            return redirect()->back()->with('success', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }

}
