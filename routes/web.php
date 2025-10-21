<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfilController;
use App\Models\Buku;
use App\Models\Profil;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DashboardController;

// 🏠 Halaman awal
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 🔐 Semua route login + verified
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['CheckRole:admin'])->group(function () {
        Route::get('/dashboard', function () {
            $totalBuku = Buku::count();
            $totalProfil = Profil::count();
            $totalPeminjaman = Peminjaman::count();
            return view('admin.dashboard', compact('totalBuku', 'totalProfil', 'totalPeminjaman'));
        })->name('dashboard');

        // ✅ Admin CRUD
        Route::resource('buku', BukuController::class);
        Route::resource('profil', ProfilController::class);
        Route::resource('peminjaman', PeminjamanController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | USER ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['CheckRole:user'])->group(function () {
        Route::get('/dashboard/user', [DashboardController::class, 'index'])
            ->middleware('CheckRole:user')
            ->name('dashboard.user');

        // ✅ Profil user
        Route::get('/profil-saya', [ProfilController::class, 'edit'])->name('profil.saya');
        Route::patch('/profil-saya', [ProfilController::class, 'update'])->name('profil.saya.update');

        // ✅ Peminjaman user
        Route::get('/peminjaman-saya', function () {
            $user = Auth::user();
            $peminjaman = $user?->profil?->peminjaman()->get() ?? collect();
            return view('user.peminjaman.index', compact('peminjaman'));
        })->name('peminjaman.saya');
    });
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');

require __DIR__.'/auth.php';
