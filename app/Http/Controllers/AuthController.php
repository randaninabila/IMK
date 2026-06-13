<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Pelanggan;

class AuthController extends Controller
{
    // SHOW LOGIN
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    // SHOW REGISTER
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$this->checkPassword($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // Rehash otomatis ke bcrypt jika masih hash lama
        if (!str_starts_with($user->password, '$2y$') && !str_starts_with($user->password, '$2a$')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Belum verifikasi email
        if (!$user->email_verified_at) {
            $otp = (string) rand(100000, 999999);
            $user->update([
                'email_verify_token' => $otp,
                'token_expires_at'   => now()->addMinutes(60),
            ]);
            Mail::to($user->email)->send(new VerifyEmailMail($user, $otp));

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('verification.notice')
                ->with('success', 'Kode OTP baru telah dikirim ke email kamu.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'no_hp'    => ['nullable', 'string', 'max:20'],
        ]);

        $otp = (string) rand(100000, 999999);

        $user = User::create([
            'nama'               => $request->nama,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'no_hp'              => $request->no_hp,
            'role'               => 'pelanggan',
            'status_akun'        => 'aktif',
            'email_verified_at'  => null,
            'email_verify_token' => $otp,
            'token_expires_at'   => now()->addMinutes(60),
        ]);

        Pelanggan::create(['user_id' => $user->user_id]);

        Auth::login($user);
        $request->session()->regenerate();

        Mail::to($user->email)->send(new VerifyEmailMail($user, $otp));

        return redirect()->route('verification.notice')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // =====================================================================
    // VERIFIKASI EMAIL
    // =====================================================================

    public function verifyEmailNotice()
    {
        if (Auth::check() && Auth::user()->email_verified_at) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.verify-email-otp');
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp' => ['required', 'string', 'size:6']]);

        $user = Auth::user();

        if ($user->email_verify_token !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        if ($user->token_expires_at && $user->token_expires_at->isPast()) {
            return back()->withErrors(['otp' => 'Kode sudah kadaluarsa. Minta kode baru.']);
        }

        $user->update([
            'email_verified_at'  => now(),
            'email_verify_token' => null,
            'token_expires_at'   => null,
        ]);

        return redirect('/')->with('success', 'Email berhasil diverifikasi! Selamat datang 🎉');
    }

    // Resend OTP email (user sudah login)
    public function resendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('info', 'Email kamu sudah terverifikasi.');
        }

        $otp = (string) rand(100000, 999999);
        $user->update([
            'email_verify_token' => $otp,
            'token_expires_at'   => now()->addMinutes(60),
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($user, $otp));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email kamu.');
    }

    // Handler verifyEmail via link lama
    public function verifyEmail(string $otp)
    {
        $user = User::where('email_verify_token', $otp)->first();

        if (!$user) {
            return redirect('/login')->withErrors(['email' => 'Kode verifikasi tidak valid.']);
        }
        if ($user->token_expires_at && $user->token_expires_at->isPast()) {
            return redirect('/login')->withErrors(['email' => 'Kode verifikasi sudah kadaluarsa.']);
        }

        $user->update([
            'email_verified_at'  => now(),
            'email_verify_token' => null,
            'token_expires_at'   => null,
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Email berhasil diverifikasi 🎉');
    }

    // Resend untuk guest (belum login)
    public function resendVerificationGuest(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Email tidak ditemukan atau sudah terverifikasi.']);
        }

        $otp = (string) rand(100000, 999999);
        $user->update([
            'email_verify_token' => $otp,
            'token_expires_at'   => now()->addMinutes(60),
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($user, $otp));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('success', 'Kode OTP baru telah dikirim ke email kamu.');
    }

    // =====================================================================
    // GOOGLE OAUTH
    // =====================================================================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id'         => $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                    'foto_profile'      => $user->foto_profile ?? $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'nama'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'password'          => null,
                    'role'              => 'pelanggan',
                    'status_akun'       => 'aktif',
                    'email_verified_at' => now(),
                    'foto_profile'      => $googleUser->getAvatar(),
                ]);

                Pelanggan::create(['user_id' => $user->user_id]);
            }
        }

        if ($user->status_akun !== 'aktif') {
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun kamu dinonaktifkan. Hubungi admin.']);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    // =====================================================================
    // HELPER
    // =====================================================================

    private function checkPassword(string $plain, string $hashed): bool
    {
        if (str_starts_with($hashed, '$2y$') || str_starts_with($hashed, '$2a$')) {
            return Hash::check($plain, $hashed);
        }
        if (strlen($hashed) === 32 && ctype_xdigit($hashed)) {
            return md5($plain) === $hashed;
        }
        if (strlen($hashed) === 40 && ctype_xdigit($hashed)) {
            return sha1($plain) === $hashed;
        }
        if (strlen($hashed) === 64 && ctype_xdigit($hashed)) {
            return hash('sha256', $plain) === $hashed;
        }
        return false;
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