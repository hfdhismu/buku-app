@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">👤 Profil Saya</h4>
        <button class="btn btn-primary" id="btnEdit">Edit Profil</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
        <tbody>
            <tr>
                <th style="width: 200px;">Nama</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $profil->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <th>Telepon</th>
                <td>{{ $profil->telepon ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Edit Profil -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="profileModalLabel">Edit Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm" method="POST" action="{{ route('profil.user.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" 
                            value="{{ $profil->alamat ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" 
                            value="{{ $profil->telepon ?? '' }}" required>
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

    document.getElementById('btnEdit').addEventListener('click', function() {
        modal.show();
    });
});
</script>
@endsection
