<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard untuk user.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Jumlah buku yang sedang dipinjam (belum dikembalikan)
        $bukuDipinjam = Peminjaman::where('user_id', $userId)
            ->whereNull('tanggal_kembali')
            ->count();

        // Total semua peminjaman user
        $totalPeminjaman = Peminjaman::where('user_id', $userId)->count();

        // Buku terakhir yang dipinjam user
        $bukuTerakhir = Peminjaman::where('user_id', $userId)
            ->latest('created_at')
            ->with('buku')
            ->first();

        // Riwayat peminjaman terbaru (misalnya 5 terakhir)
        $riwayatPeminjaman = Peminjaman::where('user_id', $userId)
            ->latest('created_at')
            ->with('buku')
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'bukuDipinjam',
            'totalPeminjaman',
            'bukuTerakhir',
            'riwayatPeminjaman'
        ));
    }
}
