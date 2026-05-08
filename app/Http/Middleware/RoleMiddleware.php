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
            if (Auth::user()->role === 'customer') {
                return redirect()->intended('/');
            }

            return $this->redirectByRole(
                Auth::user()->role,
                Auth::user()->branch_id
            );
        }

        return $next($request);
    }

    private function redirectByRole(string $role)
    {
        return match($role) {
            'owner'      => redirect('/dashboard'),
            'admin'      => redirect('/admin/dashboard'),
            'specialist' => redirect('/specialist/dashboard'),
            'customer'   => redirect('/'),
            default      => redirect('/login'),
        };
    }
}