<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun Terbit</th>
            <th>Penerbit</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($buku as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->penulis }}</td>
                <td>{{ $item->tahun_terbit }}</td>
                <td>{{ $item->penerbit }}</td>
                <td>
                    <button 
                        class="btn btn-sm btn-outline-primary me-1 btnEdit"
                        data-id="{{ $item->id }}"
                        data-judul="{{ $item->judul }}"
                        data-penulis="{{ $item->penulis }}"
                        data-tahun="{{ $item->tahun_terbit }}"
                        data-penerbit="{{ $item->penerbit }}">
                        Edit
                    </button>

                    <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Yakin hapus buku ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada data buku</td></tr>
        @endforelse
    </tbody>
</table>
