@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">📚 Daftar Buku</h4>
        <!-- Tombol buka modal -->
        <button class="btn btn-primary" id="btnTambah">
            + Tambah Buku
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Penerbit</th>
                <th>Pemilik (User)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($buku as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penulis }}</td>
                    <td>{{ $item->tahun_terbit }}</td>
                    <td>{{ $item->penerbit }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>
                        <button 
                            class="btn btn-sm btn-outline-primary me-1 btnEdit"
                            data-id="{{ $item->id }}"
                            data-judul="{{ $item->judul }}"
                            data-penulis="{{ $item->penulis }}"
                            data-tahun="{{ $item->tahun_terbit }}"
                            data-penerbit="{{ $item->penerbit }}">
                            Edit
                        </button>

                        <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Yakin hapus buku ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Belum ada data buku</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah/Edit Buku -->
<div class="modal fade" id="bukuModal" tabindex="-1" aria-labelledby="bukuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="bukuModalLabel">Tambah Buku</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="bukuForm" method="POST" action="{{ route('buku.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="penulis" id="penulis" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" id="penerbit" class="form-control" required>
                </div>

                {{-- kalau ingin pilih user pemilik --}}
                <div class="mb-3">
                    <label class="form-label">Pemilik Buku (User)</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
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
    const modalEl = document.getElementById('bukuModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('bukuForm');
    const formMethod = document.getElementById('formMethod');
    const formTitle = document.getElementById('bukuModalLabel');
    const btnSubmit = document.getElementById('btnSubmit');

    // Tombol Tambah
    document.getElementById('btnTambah').addEventListener('click', function() {
        form.reset();
        form.action = "{{ route('buku.store') }}";
        formMethod.value = "POST";
        formTitle.textContent = "Tambah Buku";
        btnSubmit.textContent = "Simpan";
        document.getElementById('user_id').disabled = false;
        modal.show();
    });

    // Tombol Edit
    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('judul').value = this.dataset.judul;
            document.getElementById('penulis').value = this.dataset.penulis;
            document.getElementById('tahun_terbit').value = this.dataset.tahun;
            document.getElementById('penerbit').value = this.dataset.penerbit;

            // Atur ulang select user_id kalau perlu (optional)
            // document.getElementById('user_id').value = this.dataset.userid;
            document.getElementById('user_id').disabled = true;

            form.action = "/buku/" + id;
            formMethod.value = "PUT";
            formTitle.textContent = "Edit Buku";
            btnSubmit.textContent = "Perbarui";
            modal.show();
        });
    });
});
</script>
@endsection
