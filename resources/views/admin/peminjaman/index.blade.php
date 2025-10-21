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

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjaman as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->buku->judul }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali ?? '-' }}</td>
                    <td>
                        {{-- <button 
                            class="btn btn-sm btn-outline-primary me-1 btnEdit"
                            data-id="{{ $item->id }}"
                            data-userid="{{ $item->user_id }}"
                            data-bukuid="{{ $item->buku_id }}"
                            data-tanggal_pinjam="{{ $item->tanggal_pinjam }}"
                            data-tanggal_kembali="{{ $item->tanggal_kembali }}">
                            Edit
                        </button> --}}

                        <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Yakin hapus data ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data peminjaman</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah/Edit Peminjaman -->
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
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="mb-3">
                    <label class="form-label">Pilih User</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">-- Pilih User --</option>
                        @foreach (\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Buku</label>
                    <select name="buku_id" id="buku_id" class="form-select" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach (\App\Models\Buku::all() as $buku)
                            <option value="{{ $buku->id }}">{{ $buku->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>

<!-- Script Modal Dinamis -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = new bootstrap.Modal(document.getElementById('peminjamanModal'));
    const form = document.getElementById('peminjamanForm');
    const formMethod = document.getElementById('formMethod');
    const formTitle = document.getElementById('peminjamanModalLabel');
    const btnSubmit = document.getElementById('btnSubmit');
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali');

    // Tombol Tambah
    document.getElementById('btnTambah').addEventListener('click', function() {
        form.reset();
        form.action = "{{ route('peminjaman.store') }}";
        formMethod.value = "POST";
        formTitle.textContent = "Tambah Peminjaman";
        btnSubmit.textContent = "Simpan";
        modal.show();
    });

    // Tombol Edit 🧩
    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const userId = this.dataset.userid;
            const bukuId = this.dataset.bukuid;
            const tglPinjam = this.dataset.tanggal_pinjam;
            const tglKembali = this.dataset.tanggal_kembali;

            // isi form modal
            document.getElementById('user_id').value = userId;
            document.getElementById('buku_id').value = bukuId;
            document.getElementById('tanggal_pinjam').value = tglPinjam;
            document.getElementById('tanggal_kembali').value = tglKembali;

            // ubah aksi form ke mode edit
            form.action = "/peminjaman/" + id;
            formMethod.value = "PUT";
            formTitle.textContent = "Edit Peminjaman";
            btnSubmit.textContent = "Perbarui";
            
            modal.show();
        });
    });

    // 🛡️ Validasi sebelum submit
    form.addEventListener('submit', function (e) {
        const pinjam = tanggalPinjam.value;
        const kembali = tanggalKembali.value;

        if (kembali < pinjam) {
            e.preventDefault(); // hentikan submit
            alert('❌ Tanggal kembali tidak boleh sebelum tanggal pinjam!');
            tanggalKembali.focus();
        }
    });
});

</script>
@endsection
