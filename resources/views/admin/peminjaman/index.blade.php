@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">🔁 Daftar Peminjaman</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#peminjamanModal" id="btnTambah">
            + Tambah Peminjaman
        </button>
    </div>

    {{-- 🔍 Input Pencarian --}}
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari Peminjaman berdasarkan Nama User, Judul Buku, atau Tanggal...">
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabel akan dimuat di sini secara dinamis --}}
    <div id="tableData">
        @include('admin.peminjaman.table', ['peminjaman' => $peminjaman])
    </div>
</div>

<!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="peminjamanModal" tabindex="-1" aria-labelledby="peminjamanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="peminjamanModalLabel">Tambah Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="peminjamanForm" method="POST" action="{{ route('peminjaman.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pilih User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Pilih User --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Buku</label>
                        <select name="buku_id" id="buku_id" class="form-select" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($buku as $b)
                                <option value="{{ $b->id }}">{{ $b->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" required>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 🔁 Script untuk Modal dan Live Search --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('peminjamanModal'));
    const form = document.getElementById('peminjamanForm');

    document.getElementById('btnTambah').addEventListener('click', function () {
        form.reset();
        modal.show();
    });

    // 🔍 Live Search
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keyup', function () {
        let query = this.value;
        fetch(`{{ route('peminjaman.index') }}?search=${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('tableData').innerHTML = data;
        })
        .catch(err => console.error(err));
    });
});
</script>
@endsection
