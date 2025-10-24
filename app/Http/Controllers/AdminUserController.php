<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth; // ✅ Tambahan penting untuk tahu siapa yang login

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index()
    {
        // Ambil semua user beserta relasi role
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        $role = Role::where('name', $request->role)->first();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->role()->associate($role);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $loggedInUser = Auth::user(); // ✅ Ambil data user yang sedang login

        // 🚫 Cegah siapa pun mengubah user admin
        if ($user->role->name === 'admin') {
            return redirect()->route('users.index')->with('error', 'User admin tidak boleh diubah.');
        }

        // 🚫 Jika yang login staff, hanya boleh ubah user dengan role "user"
        if ($loggedInUser->role->name === 'staff' && $user->role->name !== 'user') {
            return redirect()->route('users.index')->with('error', 'Staff hanya dapat mengubah role user.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|exists:roles,name',
            'password' => 'nullable|string|min:6',
        ]);

        $role = Role::where('name', $request->role)->first();

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->role()->associate($role);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $loggedInUser = Auth::user(); // ✅ Ambil user login

        // 🚫 Cegah hapus user dengan role admin
        if ($user->role->name === 'admin') {
            return redirect()->route('users.index')->with('error', 'User admin tidak boleh dihapus.');
        }

        // 🚫 Jika staff login, hanya boleh hapus user dengan role "user"
        if ($loggedInUser->role->name === 'staff' && $user->role->name !== 'user') {
            return redirect()->route('users.index')->with('error', 'Staff hanya dapat menghapus role user.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
