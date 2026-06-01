<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgotpw');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $key = 'forgot-password:' . Str::lower($request->email);

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar di sistem kami.',
            ])->withInput();
        }

        $otp = (string) rand(100000, 999999);

        $user->update([
            'email_verify_token' => $otp,
            'token_expires_at'   => now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new ResetPasswordOtpMail($user, $otp));

        RateLimiter::hit($key, 600);

        $request->session()->put('reset_email', $request->email);

        return redirect()->route('password.otp')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function showOtpForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi.']);
        }

        return view('auth.verif');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'   => ['required', 'string', 'size:6'],
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)
            ->where('email_verify_token', $request->otp)
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        if ($user->token_expires_at->isPast()) {
            $user->update([
                'email_verify_token' => null,
                'token_expires_at'   => null,
            ]);
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Minta kode baru.'])->withInput();
        }

        $request->session()->put('reset_ticket', Str::random(40));
        $request->session()->put('reset_email', $user->email);
        $request->session()->save();

        return redirect()->route('password.reset.form');
    }

    public function resendOtp(Request $request)
    {
        $email = $request->session()->get('reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi.']);
        }

        $key = 'forgot-password:' . Str::lower($email);

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'otp' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $otp = (string) rand(100000, 999999);
            $user->update([
                'email_verify_token' => $otp,
                'token_expires_at'   => now()->addMinutes(15),
            ]);
            Mail::to($user->email)->send(new ResetPasswordOtpMail($user, $otp));
        }

        RateLimiter::hit($key, 600);

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    public function showNewPasswordForm(Request $request)
    {
        if (!$request->session()->has('reset_ticket') || !$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi dari awal.']);
        }

        return view('auth.newpw');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if (!$request->session()->has('reset_ticket') || !$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi dari awal.']);
        }

        $email = $request->session()->get('reset_email');

        $user = User::where('email', $email)
            ->whereNotNull('email_verify_token')
            ->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid atau sudah digunakan.']);
        }

        $user->update([
            'password'           => Hash::make($request->password),
            'email_verify_token' => null,
            'token_expires_at'   => null,
        ]);

        $request->session()->forget(['reset_ticket', 'reset_email']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login.');
    }
}