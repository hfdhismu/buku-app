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
                            <button class="btn btn-sm btn-outline-primary me-1 btnEdit" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $targetRole }}">
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
                            <button class="btn btn-sm btn-outline-primary me-1 btnEdit" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $targetRole }}">
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