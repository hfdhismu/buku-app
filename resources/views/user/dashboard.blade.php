@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
<div class="container mt-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">👋 Halo, {{ Auth::user()->name }}</h2>
        <p class="text-muted">Selamat datang di dashboard perpustakaan kamu 📚</p>
    </div>

    <!-- Statistik pribadi -->
    <div class="row g-4">
        <!-- Buku yang sedang dipinjam -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="card-body text-center">
                    <div class="fs-1 text-primary mb-2"><i class="bi bi-journal-arrow-down"></i></div>
                    <h5 class="fw-bold">Buku Sedang Dipinjam</h5>
                    <h3 class="fw-bolder text-dark">{{ $bukuDipinjam ?? 0 }}</h3>
                    <p class="text-muted small">Buku yang masih kamu pinjam saat ini</p>
                </div>
            </div>
        </div>

        <!-- Total peminjaman -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="card-body text-center">
                    <div class="fs-1 text-success mb-2"><i class="bi bi-journal-check"></i></div>
                    <h5 class="fw-bold">Total Peminjaman</h5>
                    <h3 class="fw-bolder text-dark">{{ $totalPeminjaman ?? 0 }}</h3>
                    <p class="text-muted small">Total seluruh buku yang pernah kamu pinjam</p>
                </div>
            </div>
        </div>

        <!-- Buku terakhir -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="card-body text-center">
                    <div class="fs-1 text-warning mb-2"><i class="bi bi-clock-history"></i></div>
                    <h5 class="fw-bold">Buku Terakhir Dipinjam</h5>
                    <h6 class="fw-bolder text-dark">
                        {{ $bukuTerakhir?->buku->judul ?? 'Belum ada' }}
                    </h6>
                    <p class="text-muted small">
                        {{ $bukuTerakhir?->created_at?->format('d M Y') ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Peminjaman Terakhir -->
    <div class="mt-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">📖 Riwayat Peminjaman Terbaru</h5>

                @if(isset($riwayatPeminjaman) && $riwayatPeminjaman->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPeminjaman as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $p->buku->judul }}</td>
                                        <td>{{ $p->created_at?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $p->status == 'dipinjam' ? 'warning' : 'success' }}">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center m-0">Belum ada riwayat peminjaman 📭</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
