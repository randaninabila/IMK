<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            return $this->redirectByRole(Auth::user()->role);
        }

        // Cek status akun — nonaktif tidak boleh masuk
        if (Auth::user()->status_akun === 'nonaktif') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan.'
            ]);
        }

        return $next($request);
    }

    private function redirectByRole(string $role)
    {
        return match($role) {
            'owner'     => redirect('/dashboard'),
            'admin'     => redirect('/admin/dashboard'),
            'pegawai'   => redirect('/pegawai/dashboard'),
            'pelanggan' => redirect('/'),
            default     => redirect('/login'),
        };
    }
}