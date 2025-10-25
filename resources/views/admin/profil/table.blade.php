<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($profil as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->user->email ?? '-' }}</td>
                <td>{{ $item->alamat }}</td>
                <td>{{ $item->telepon }}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1 btnEdit"
                        data-id="{{ $item->id }}"
                        data-alamat="{{ $item->alamat }}"
                        data-telepon="{{ $item->telepon }}"
                        data-username="{{ $item->user->name }}">
                        Edit
                    </button>

                    <form action="{{ route('profil.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Yakin hapus profil ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada profil</td>
            </tr>
        @endforelse
    </tbody>
</table>
