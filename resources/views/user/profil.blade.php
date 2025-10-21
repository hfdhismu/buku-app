@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="container mt-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">👤 Profil Pengguna</h2>
        <p class="text-muted">Informasi akun kamu di sistem perpustakaan.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <th>Nama</th>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><span class="badge bg-info text-dark">{{ Auth::user()->role->name }}</span></td>
                    </tr>
                    <tr>
                        <th>Bergabung Sejak</th>
                        <td>{{ Auth::user()->created_at->format('d M Y') }}</td>
                    </tr>
                </table>

                <div class="text-center mt-4">
                    <a href="#" class="btn btn-outline-primary px-4">
                        <i class="bi bi-pencil-square"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
