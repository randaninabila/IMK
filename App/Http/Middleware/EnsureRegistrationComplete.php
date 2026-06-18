<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRegistrationComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Izinkan akses ke route auth (logout, verifikasi, set-password)
        $allowedRoutes = [
            'verification.notice',
            'verification.verify-otp',
            'verification.resend',
            'register.set-password',
            'logout',
        ];

        if (in_array($request->route()?->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // Belum verifikasi email
        if (!$user->email_verified_at) {
            return redirect()->route('verification.notice');
        }

        // Sudah verifikasi tapi belum set password
        if (!$user->password) {
            return redirect()->route('register.set-password');
        }

        return $next($request);
    }
}
