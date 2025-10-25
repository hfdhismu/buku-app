@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">📚 Daftar Buku</h4>
        <div class="d-flex align-items-center gap-2">
            
            <!-- Tombol buka modal -->
            <button class="btn btn-primary" id="btnTambah">
                + Tambah Buku
            </button>            
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔹 Input Search --}}
    <div class="mb-3">
        <input type="text" class="form-control" id="searchInput" placeholder="Cari Buku berdasarkan Judul, Penulis, Tahun Terbit, atau Penerbit...">
    </div>

    <!-- Container tabel yang bisa berubah -->
    <div id="tableContainer">
        @include('admin.buku.table', ['buku' => $buku])
    </div>
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

                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>

<!-- Script Modal + Live Search -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const modalEl = document.getElementById('bukuModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('bukuForm');
    const formMethod = document.getElementById('formMethod');
    const formTitle = document.getElementById('bukuModalLabel');
    const btnSubmit = document.getElementById('btnSubmit');
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('tableContainer');

    // 🔹 Tombol Tambah
    document.getElementById('btnTambah').addEventListener('click', function() {
        form.reset();
        form.action = "{{ route('buku.store') }}";
        formMethod.value = "POST";
        formTitle.textContent = "Tambah Buku";
        btnSubmit.textContent = "Simpan";
        modal.show();
    });

    // 🔹 Live Search
    searchInput.addEventListener('keyup', function() {
        const keyword = this.value;

        fetch(`{{ route('buku.index') }}?search=${keyword}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            attachEditButtons(); // re-attach event setelah tabel diperbarui
        });
    });

    // 🔹 Fungsi untuk re-bind tombol edit setelah tabel reload
    function attachEditButtons() {
        document.querySelectorAll('.btnEdit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('judul').value = this.dataset.judul;
                document.getElementById('penulis').value = this.dataset.penulis;
                document.getElementById('tahun_terbit').value = this.dataset.tahun;
                document.getElementById('penerbit').value = this.dataset.penerbit;

                form.action = "/buku/" + id;
                formMethod.value = "PUT";
                formTitle.textContent = "Edit Buku";
                btnSubmit.textContent = "Perbarui";
                modal.show();
            });
        });
    }

    // Panggil pertama kali (saat load awal)
    attachEditButtons();
});
</script>
@endsection
