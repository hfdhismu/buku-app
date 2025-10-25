<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($peminjaman as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->buku->judul }}</td>
                <td>{{ $item->tanggal_pinjam }}</td>
                <td>{{ $item->tanggal_kembali ?? '-' }}</td>
                <td>
                    @if (is_null($item->tanggal_kembali))
                        <span class="badge bg-warning text-dark">Dipinjam</span>
                    @else
                        <span class="badge bg-success">Dikembalikan</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Yakin hapus data ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Belum ada data peminjaman</td>
            </tr>
        @endforelse
    </tbody>
</table>
