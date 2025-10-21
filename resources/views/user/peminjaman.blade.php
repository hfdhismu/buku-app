@extends('layouts.user')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="container mt-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">📦 Daftar Peminjaman</h2>
        <p class="text-muted">Lihat semua buku yang sedang kamu pinjam.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Batas Pengembalian</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->buku->judul }}</td>
                        <td>{{ $item->tanggal_pinjam->format('d M Y') }}</td>
                        <td>{{ $item->tanggal_kembali->format('d M Y') }}</td>
                        <td>
                            @if($item->status == 'dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
