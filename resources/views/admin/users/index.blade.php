@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">👤 Daftar User</h4>

            {{-- Tombol tambah hanya muncul untuk admin dan staff --}}
            @if (auth()->user()->role->name === 'admin' || auth()->user()->role->name === 'staff')
                <button class="btn btn-primary" id="btnTambah">+ Tambah User</button>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key => $user)
                    <tr class="text-center">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->role ? strtolower($user->role->name) : '-' }}</td>
                        <td>
                            @php
                                $loginRole = auth()->user()->role->name;
                                $targetRole = $user->role ? $user->role->name : null;
                            @endphp

                            {{-- 🔹 ADMIN --}}
                            @if ($loginRole === 'admin')
                                @if ($targetRole !== 'admin')
                                    <button class="btn btn-sm btn-outline-primary me-1 btnEdit"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $targetRole }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Yakin hapus user ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted fst-italic">Tidak dapat diubah</span>
                                @endif

                            {{-- 🔹 STAFF --}}
                            @elseif ($loginRole === 'staff')
                                @if ($targetRole === 'user')
                                    <button class="btn btn-sm btn-outline-primary me-1 btnEdit"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $targetRole }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Yakin hapus user ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted fst-italic">Tidak dapat diubah</span>
                                @endif

                            {{-- 🔹 ROLE LAIN (seharusnya tidak ada akses ke sini) --}}
                            @else
                                <span class="text-muted">-</span>
                            @endif
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

    {{-- Modal Tambah/Edit User --}}
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
                                    {{-- Staff tidak boleh menambah admin/staff --}}
                                    @if(auth()->user()->role->name === 'staff' && $role->name !== 'user')
                                        @continue
                                    @endif
                                    <option value="{{ $role->name }}">{{ strtolower($role->name) }}</option>
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

    {{-- Script Modal --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modalEl = document.getElementById('userModal');
            const modal = new bootstrap.Modal(modalEl);
            const form = document.getElementById('userForm');
            const formMethod = document.getElementById('formMethod');
            const formTitle = document.getElementById('userModalLabel');
            const btnSubmit = document.getElementById('btnSubmit');
            const passwordInput = document.getElementById('password');
            const passwordHelp = document.getElementById('passwordHelp');

            // Validasi password
            form.addEventListener('submit', function (e) {
                const method = formMethod.value;
                const password = passwordInput.value.trim();

                if (method === "POST" && password.length < 6) {
                    e.preventDefault();
                    alert("Password minimal 6 karakter!");
                    passwordInput.focus();
                }

                if (method === "PUT" && password !== "" && password.length < 6) {
                    e.preventDefault();
                    alert("Password minimal 6 karakter!");
                    passwordInput.focus();
                }
            });

            // Tombol Tambah
            const btnTambah = document.getElementById('btnTambah');
            if (btnTambah) {
                btnTambah.addEventListener('click', function () {
                    form.reset();
                    form.action = "{{ route('users.store') }}";
                    formMethod.value = "POST";
                    formTitle.textContent = "Tambah User";
                    btnSubmit.textContent = "Simpan";

                    passwordInput.required = true;
                    passwordHelp.style.display = 'none';
                    modal.show();
                });
            }

            // Tombol Edit
            document.querySelectorAll('.btnEdit').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    document.getElementById('name').value = this.dataset.name;
                    document.getElementById('email').value = this.dataset.email;
                    document.getElementById('role').value = this.dataset.role;
                    passwordInput.value = '';

                    form.action = "/users/" + id;
                    formMethod.value = "PUT";
                    formTitle.textContent = "Edit User";
                    btnSubmit.textContent = "Perbarui";

                    passwordInput.required = false;
                    passwordHelp.style.display = 'block';
                    modal.show();
                });
            });
        });
    </script>
@endsection