<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Perpustakaan')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="{{ url('dashboard/user') }}">
                📚 Perpustakaan
            </a>

            <!-- Tombol hamburger (mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser"
                aria-controls="navbarUser" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu utama -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarUser">
                <ul class="navbar-nav mx-auto text-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.user') ? 'active fw-semibold' : '' }}"
                            href="{{ route('dashboard.user') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profil.user') ? 'active fw-semibold' : '' }}"
                            href="{{ route('profil.user') }}">
                            Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('peminjaman.user') ? 'active fw-semibold' : '' }}"
                            href="{{ route('peminjaman.user') }}">
                            Peminjaman
                        </a>
                    </li>
                </ul>

                <!-- User & Logout -->
                <ul class="navbar-nav ms-lg-auto align-items-lg-center text-center mt-3 mt-lg-0">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link text-light fw-semibold">
                                👤 {{ auth()->user()->name }}
                            </span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-light ms-2">
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-sm btn-light fw-semibold ms-lg-3" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten Halaman --}}
    <div class="container">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="text-center py-3 text-muted small mt-5">
        &copy; {{ date('Y') }} Perpustakaan Sederhana. Semua Hak Dilindungi.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
