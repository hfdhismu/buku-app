<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\User\UserProfilController;
use App\Http\Controllers\User\UserPeminjamanController;
use App\Models\Buku;
use App\Models\Profil;
use App\Models\Peminjaman;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DashboardController;
use App\Models\User;

// 🏠 Halaman awal
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 🔐 Semua route login + verified
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | ADMIN ROUTES
    |----------------------------------------------------------------------
    */
    Route::middleware(['CheckRole:admin'])->group(function () {
        Route::get('/dashboard', function () {
            $totalUser = User::count();
            $totalBuku = Buku::count();
            $totalProfil = Profil::count();
            $totalPeminjaman = Peminjaman::whereNull('tanggal_kembali')->count();
            return view('admin.dashboard', compact('totalUser', 'totalBuku', 'totalProfil', 'totalPeminjaman'));
        })->name('dashboard');

        // ✅ Admin & Staff CRUD
        Route::resource('buku', BukuController::class);
        Route::resource('profil', ProfilController::class);
        Route::resource('peminjaman', PeminjamanController::class);
        Route::resource('users', AdminUserController::class)->except(['create', 'edit', 'show']);
    });

    /*
    |----------------------------------------------------------------------
    | USER ROUTES
    |----------------------------------------------------------------------
    */
    Route::middleware(['CheckRole:user'])->group(function () {
        Route::get('/dashboard/user', [DashboardController::class, 'index'])
            ->middleware('CheckRole:user')
            ->name('dashboard.user');

        // ✅ Profil User
        Route::get('/dashboard/user/profil', [UserProfilController::class, 'index'])
        ->name('profil.user');
        Route::put('/dashboard/user/profil', [UserProfilController::class, 'update'])
            ->name('profil.user.update');

        // ✅ ROUTE PEMINJAMAN (kalau nanti dipakai)
        Route::get('/dashboard/user/peminjaman', [UserPeminjamanController::class, 'index'])
            ->name('peminjaman.user');

        Route::put('/dashboard/user/peminjaman/{id}/kembalikan', [UserPeminjamanController::class, 'kembalikan'])
            ->name('peminjaman.kembalikan');
    });
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');

require __DIR__.'/auth.php';
