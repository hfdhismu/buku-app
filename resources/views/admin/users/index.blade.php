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

        {{-- 🔍 Live Search --}}
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Cari User berdasarkan Nama atau Role...">
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div id="userTable">
            @include('admin.users.table', ['users' => $users])
        </div>
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

    {{-- Script Modal + Live Search --}}
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

            // ✅ LIVE SEARCH
            const searchInput = document.getElementById('search');
            searchInput.addEventListener('keyup', function () {
                const query = this.value;

                fetch(`{{ route('users.index') }}?search=${query}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('userTable').innerHTML = data.html;
                    });
            });

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
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('btnEdit')) {
                    const btn = e.target;
                    const id = btn.dataset.id;
                    document.getElementById('name').value = btn.dataset.name;
                    document.getElementById('email').value = btn.dataset.email;
                    document.getElementById('role').value = btn.dataset.role;
                    passwordInput.value = '';

                    form.action = "/users/" + id;
                    formMethod.value = "PUT";
                    formTitle.textContent = "Edit User";
                    btnSubmit.textContent = "Perbarui";

                    passwordInput.required = false;
                    passwordHelp.style.display = 'block';
                    modal.show();
                }
            });
        });
    </script>
@endsection