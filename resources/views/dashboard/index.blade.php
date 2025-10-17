@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <p>Selamat datang di sistem perpustakaan sederhana 🎉</p>

    <ul>
        <li>Total Buku: {{ $totalBuku ?? 0 }}</li>
        <li>Total Profil: {{ $totalProfile ?? 0 }}</li>
        <li>Total Peminjaman: {{ $totalPeminjaman ?? 0 }}</li>
    </ul>
@endsection
