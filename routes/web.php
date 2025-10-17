<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProfileController; // bawaan Breeze
use App\Models\Buku;
use App\Models\Profil;
use App\Models\Peminjaman;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Halaman awal → langsung ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 🔐 Semua route di bawah ini hanya bisa diakses jika sudah login
Route::middleware(['auth', 'verified'])->group(function () {

    // 📊 Dashboard
    Route::get('/dashboard', function () {
        $totalBuku = Buku::count();
        $totalProfil = Profil::count();
        $totalPeminjaman = Peminjaman::count();

        return view('dashboard', compact('totalBuku', 'totalProfil', 'totalPeminjaman'));
    })->name('dashboard');

    // ✅ CRUD utama kamu
    Route::resource('buku', BukuController::class);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::resource('profil', ProfilController::class);

    // 👤 Rute profil (user) bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔄 Rute autentikasi Breeze (login/register)
require __DIR__.'/auth.php';
