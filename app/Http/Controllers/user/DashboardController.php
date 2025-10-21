<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard untuk user.
     */
    public function index()
    {
        $user = Auth::user();

        // contoh data yang biasa dipakai di dashboard user
        $bukuTerakhir = Buku::latest()->take(5)->first();
        $totalBuku = Buku::count();

        return view('user.dashboard', compact('user', 'bukuTerakhir', 'totalBuku'));
    }
}
