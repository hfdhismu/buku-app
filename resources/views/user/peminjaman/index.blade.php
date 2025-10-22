@extends('layouts.user')

@section('title', 'Peminjaman Saya')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">📚 Peminjaman Saya</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $key => $item)
                    <tr class="text-center">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->buku->judul ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                        <td>
                            @if ($item->tanggal_kembali)
                                {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                            @else
                                <span class="text-muted">Belum dikembalikan</span>
                            @endif
                        </td>
                        <td>
                            @if (!$item->tanggal_kembali)
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                        <td>
                            @if (!$item->tanggal_kembali)
                                <button class="btn btn-sm btn-success btnKembalikan" data-id="{{ $item->id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalKembalikan">
                                    Kembalikan
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Konfirmasi Pengembalian -->
    <div class="modal fade" id="modalKembalikan" tabindex="-1" aria-labelledby="modalKembalikanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalKembalikanLabel">Kembalikan Buku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengembalikan buku ini?</p>
                    <form id="formKembalikan" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tanggal_kembali" value="{{ now()->toDateString() }}">
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Ya, Kembalikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const formKembalikan = document.getElementById('formKembalikan');
            const btns = document.querySelectorAll('.btnKembalikan');

            // base URL => pastikan sesuai route yang kamu daftar
            const baseKembalikanUrl = "{{ url('/dashboard/user/peminjaman') }}";

            btns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    // hasil: /dashboard/user/peminjaman/123/kembalikan
                    formKembalikan.action = `${baseKembalikanUrl}/${id}/kembalikan`;
                });
            });
        });
    </script>

@endsection