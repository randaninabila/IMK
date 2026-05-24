<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Pelanggan;

class AuthController extends Controller
{
    // =====================
    // SHOW LOGIN
    // =====================
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    // =====================
    // SHOW REGISTER
    // =====================
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    // =====================
    // LOGIN
    // =====================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        // User tidak ditemukan
        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.'
                ]);
        }

        // Cek password support multi hash
        if (!$this->checkPassword($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.'
                ]);
        }

        // Rehash otomatis ke bcrypt jika masih hash lama
        if (!str_starts_with($user->password, '$2y$')
            && !str_starts_with($user->password, '$2a$')) {

            $user->password = Hash::make($request->password);
            $user->save();
        }

        if (!$user->email_verified_at) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email belum diverifikasi.'])
                ->with('unverified_email', $request->email);
        }
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

        // =====================
        // HELPER — cek password support multi-algoritma
        // =====================
        private function checkPassword(string $plain, string $hashed): bool
        {
            // Bcrypt
            if (str_starts_with($hashed, '$2y$') || str_starts_with($hashed, '$2a$')) {
                return Hash::check($plain, $hashed);
            }

            // MD5 (32 char hex)
            if (strlen($hashed) === 32 && ctype_xdigit($hashed)) {
                return md5($plain) === $hashed;
            }

            // SHA1 (40 char hex)
            if (strlen($hashed) === 40 && ctype_xdigit($hashed)) {
                return sha1($plain) === $hashed;
            }

            // SHA256 (64 char hex)
            if (strlen($hashed) === 64 && ctype_xdigit($hashed)) {
                return hash('sha256', $plain) === $hashed;
            }

            return false;
        }

    // =====================
    // REGISTER (pelanggan)
    // =====================
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'no_hp'    => ['nullable', 'string', 'max:20'],
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'nama'               => $request->nama,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'no_hp'              => $request->no_hp,
            'role'               => 'pelanggan',
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
            'email_verify_token' => $otp,
            'token_expires_at'   => now()->addMinutes(60),
        ]);

        Pelanggan::create(['user_id' => $user->user_id]);

        return redirect()
            ->route('verification.notice')
            ->with('success', 'Kode verifikasi telah dikirim ke email.');
    }

    // =====================
    // LOGOUT
    // =====================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // =====================
    // HELPER — redirect berdasarkan role
    // =====================
    private function redirectByRole(string $role)
    {
        return match($role) {
            'owner'    => redirect('/dashboard'),
            'admin'    => redirect('/admin/dashboard'),
            'pegawai'  => redirect('/pegawai/dashboard'),
            'pelanggan'=> redirect('/'),
            default    => redirect('/login'),
        };
    }

    // =====================
    // VERIFIKASI EMAIL
    // =====================

    // Halaman cek email
    public function verifyEmailNotice()
    {
        if (Auth::user()?->hasVerifiedEmail()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.verif');
    }

    // Handler klik link dari email
    public function verifyEmail(string $otp)
    {
        $user = User::where('email_verify_token', $otp)->first();

        if (!$user) {
            return redirect('/login')->withErrors([
                'email' => 'Kode verifikasi tidak valid.'
            ]);
        }

        if ($user->token_expires_at && $user->token_expires_at->isPast()) {

            return redirect('/login')->withErrors([
                'email' => 'Kode verifikasi sudah kadaluarsa.'
            ]);
        }

        $user->update([
            'email_verified_at'  => now(),
            'email_verify_token' => null,
            'token_expires_at'   => null,
        ]);

        Auth::login($user);

        return redirect('/')
            ->with('success', 'Email berhasil diverifikasi 🎉');
    }

    // Resend email verifikasi
    public function resendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email kamu sudah terverifikasi.');
        }

        $otp = rand(100000, 999999);
        $user->update([
            'email_verify_token' => $otp,
            'token_expires_at'   => now()->addMinutes(60),
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($user));

        return back()->with('success', 'Email verifikasi baru telah dikirim.');
    }

    // Resend verifikasi untuk user yang belum login
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

        $otp = rand(100000, 999999);
        $user->update([
            'email_verify_token' => $otp,
            'token_expires_at'   => now()->addMinutes(60),
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('success', 'Kode verifikasi baru telah dikirim ke email kamu.');
    }

    // Verify OTP dari halaman ini
    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp' => ['required', 'string', 'size:6']]);

        $user = Auth::user();

        if ($user->email_verify_token !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode verifikasi tidak valid.']);
        }

        if ($user->token_expires_at && $user->token_expires_at->isPast()) {
            return back()->withErrors(['otp' => 'Kode sudah kadaluarsa. Minta kode baru.']);
        }

        $user->update([
            'email_verified_at'  => now(),
            'email_verify_token' => null,
            'token_expires_at'   => null,
        ]);

        return redirect('/')->with('success', 'Email berhasil diverifikasi!');
    }
}