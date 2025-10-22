@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="mb-4 text-center">
            <h2 class="fw-bold text-primary">📊 Dashboard Perpustakaan</h2>
            <p class="text-muted">Selamat datang di sistem perpustakaan sederhana 🎉</p>
        </div>

        <div class="row g-4">
            <!-- Total User -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="card-body text-center">
                        <div class="fs-1 text-info mb-2">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="fw-bold">Total User</h5>
                        <h3 class="fw-bolder text-dark">{{ $totalUser ?? 0 }}</h3>
                        <p class="text-muted small">Jumlah seluruh pengguna terdaftar</p>
                    </div>
                </div>
            </div>

            <!-- Total Buku -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="card-body text-center">
                        <div class="fs-1 text-primary mb-2">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h5 class="fw-bold">Total Buku</h5>
                        <h3 class="fw-bolder text-dark">{{ $totalBuku ?? 0 }}</h3>
                        <p class="text-muted small">Jumlah seluruh buku yang tersedia</p>
                    </div>
                </div>
            </div>

            <!-- Total Profil -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="card-body text-center">
                        <div class="fs-1 text-success mb-2">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h5 class="fw-bold">Total Profil</h5>
                        <h3 class="fw-bolder text-dark">{{ $totalProfil ?? 0 }}</h3>
                        <p class="text-muted small">Jumlah profil pengguna terdaftar</p>
                    </div>
                </div>
            </div>

            <!-- Total Peminjaman -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="card-body text-center">
                        <div class="fs-1 text-warning mb-2">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </div>
                        <h5 class="fw-bold">Total Peminjaman</h5>
                        <h3 class="fw-bolder text-dark">{{ $totalPeminjaman ?? 0 }}</h3>
                        <p class="text-muted small">Jumlah transaksi peminjaman aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambahan Bagian Grafik / Ringkasan -->
        <div class="mt-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Ringkasan Aktivitas 📅</h5>
                    <p class="text-muted">Kamu bisa menambahkan grafik aktivitas atau daftar terbaru di sini nanti
                        (misalnya: 5 buku terbaru, peminjaman terakhir, dsb).</p>
                </div>
            </div>
        </div>
    </div>
@endsection