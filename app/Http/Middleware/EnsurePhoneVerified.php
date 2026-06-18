<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Tidak login → biarkan guard lain yang handle
        if (!$user) {
            return $next($request);
        }

        // Hanya berlaku untuk pelanggan
        if ($user->role !== 'pelanggan') {
            return $next($request);
        }

        // Nomor HP belum diisi → arahkan ke profil
        if (!$user->no_hp) {
            return redirect()->route('profile')
                ->with('warning', 'Tambahkan nomor HP kamu terlebih dahulu untuk melakukan booking.');
        }

        return $next($request);
    }
}
