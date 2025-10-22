@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">👤 Daftar User</h4>
            <button class="btn btn-primary" id="btnTambah">
                + Tambah User
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    {{-- <th>Email</th> --}}
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key => $user)
                    <tr class="text-center">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        {{-- <td>{{ $user->email }}</td> --}}
                        <td>{{ $user->role ? ucfirst($user->role->name) : '-' }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-outline-primary me-1 btnEdit" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                data-role="{{ $user->role ? $user->role->name : '' }}">
                                Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Yakin hapus user ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah/Edit User -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <small id="passwordHelp" class="text-muted" style="display: none;">
                                Kosongkan jika tidak ingin mengubah password saat edit.
                            </small>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">Pilih Role</option>
                                @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
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
        document.addEventListener("DOMContentLoaded", function () {
            const modalEl = document.getElementById('userModal');
            const modal = new bootstrap.Modal(modalEl);
            const form = document.getElementById('userForm');
            const formMethod = document.getElementById('formMethod');
            const formTitle = document.getElementById('userModalLabel');
            const btnSubmit = document.getElementById('btnSubmit');
            const passwordInput = document.getElementById('password');
            const passwordHelp = document.getElementById('passwordHelp'); // <small> di form

            // Tombol Tambah
            document.getElementById('btnTambah').addEventListener('click', function () {
                form.reset();
                form.action = "{{ route('users.store') }}";
                formMethod.value = "POST";
                formTitle.textContent = "Tambah User";
                btnSubmit.textContent = "Simpan";

                // Password wajib saat tambah
                passwordInput.required = true;

                // Sembunyikan teks bantuan password saat tambah
                if (passwordHelp) passwordHelp.style.display = 'none';

                modal.show();
            });

            // Tombol Edit
            document.querySelectorAll('.btnEdit').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    document.getElementById('name').value = this.dataset.name;
                    document.getElementById('email').value = this.dataset.email;
                    document.getElementById('role').value = this.dataset.role;
                    passwordInput.value = '';

                    form.action = "/users/" + id; // route resource users
                    formMethod.value = "PUT";
                    formTitle.textContent = "Edit User";
                    btnSubmit.textContent = "Perbarui";

                    // Password optional saat edit
                    passwordInput.required = false;

                    // Tampilkan teks bantuan password saat edit
                    if (passwordHelp) passwordHelp.style.display = 'block';

                    modal.show();
                });
            });
        });
    </script>
@endsection