<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CustomerProfile;

class AuthController extends Controller
{
    // =====================
    // LOGIN
    // =====================
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'customer') {
                return redirect()->intended('/');
            }

            return $this->redirectByRole(
                Auth::user()->role,
                Auth::user()->branch_id
            );
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'customer') {
                return redirect()->intended('/');
            }

            return $this->redirectByRole(
                Auth::user()->role,
                Auth::user()->branch_id
            );
        }
        return view('auth.signin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'customer') {
                return redirect()->intended('/');
            }

            return $this->redirectByRole(
                Auth::user()->role,
                Auth::user()->branch_id
            );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    // =====================
    // REGISTER (customer)
    // =====================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'customer',
            'branch_id' => null,
        ]);

        CustomerProfile::create([
            'user_id' => $user->id,
            'phone'   => $request->phone,
        ]);

        $user->sendEmailVerificationNotification();

        Auth::login($user);
        $request->session()->regenerate();

        // Kalau register dari halaman booking, kembali ke booking
        return redirect()->intended('/');
    }

    // =====================
    // REGISTER SAAT BOOKING
    // (nama & email saja, tanpa password — auto-create akun)
    // =====================
    public function quickRegister(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        // Cek apakah email sudah terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Buat akun baru, password random (customer bisa set password nanti)
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make(\Str::random(16)),
                'role'      => 'customer',
                'branch_id' => null,
            ]);

            CustomerProfile::create(['user_id' => $user->id]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['success' => true, 'user' => $user->only('id', 'name', 'email')]);
    }

    // =====================
    // LOGOUT
    // =====================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // =====================
    // HELPER
    // =====================
    private function redirectByRole(string $role, ?int $branch_id = null)
    {
        return match($role) {
            'owner'      => redirect('/dashboard'),
            'admin'      => redirect("/admin/dashboard?branch_id={$branch_id}"),
            'specialist' => redirect("/specialist/dashboard?branch_id={$branch_id}"),
            'customer'   => redirect('/'),
            default      => redirect('/'),
        };
    }
}