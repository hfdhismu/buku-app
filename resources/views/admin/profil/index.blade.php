@extends('layouts.app')

@section('title', 'Daftar Profil')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">👤 Daftar Profil</h4>
        <button class="btn btn-primary" id="btnTambah">+ Tambah Profil</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($profil as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->user->email ?? '-' }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->telepon }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1 btnEdit"
                            data-id="{{ $item->id }}"
                            data-alamat="{{ $item->alamat }}"
                            data-telepon="{{ $item->telepon }}"
                            data-username="{{ $item->user->name }}">
                            Edit
                        </button>

                        <form action="{{ route('profil.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Yakin hapus profil ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada profil</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah/Edit Profil -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="profileModalLabel">Tambah Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm" method="POST" action="{{ route('profil.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="mb-3" id="userSelectContainer">
                        <label class="form-label">Pilih User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Pilih User --</option>
                            @foreach(\App\Models\User::doesntHave('profil')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" required>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = new bootstrap.Modal(document.getElementById('profileModal'));
    const form = document.getElementById('profileForm');
    const formMethod = document.getElementById('formMethod');
    const formTitle = document.getElementById('profileModalLabel');
    const userSelectContainer = document.getElementById('userSelectContainer');
    const userSelect = document.getElementById('user_id');

    // TOMBOL TAMBAH
    document.getElementById('btnTambah').addEventListener('click', function() {
        form.reset();
        form.action = "{{ route('profil.store') }}";
        formMethod.value = "POST";
        formTitle.textContent = "Tambah Profil";
        userSelectContainer.style.display = "block";
        userSelect.required = true;
        modal.show();
    });

    // TOMBOL EDIT
    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('alamat').value = this.dataset.alamat;
            document.getElementById('telepon').value = this.dataset.telepon;

            // route update
            form.action = "{{ url('profil') }}/" + id;
            formMethod.value = "PUT"; // method spoofing
            formTitle.textContent = "Edit Profil (" + this.dataset.username + ")";
            userSelectContainer.style.display = "none";
            userSelect.required = false;

            modal.show();
        });
    });
});
</script>
@endsection
