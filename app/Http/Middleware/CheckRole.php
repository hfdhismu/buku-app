<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');  // Jika user belum login
        }

        $user = Auth::user();

        // Pastikan user memiliki role yang valid
        if (!$user->role) {
            abort(403, 'User tidak memiliki role.');
        }

        // Cocokkan nama role
        if ($user->role->name !== $role && !in_array($role, ['admin', 'staff'])) {
            abort(403, 'Akses ditolak! Role Anda: ' . $user->role->name);  // Jika role tidak sesuai
        }

        return $next($request);  // Lanjutkan ke route jika role sesuai
    }
}
