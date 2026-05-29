<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\ChecksPasswords;
use Illuminate\Support\Facades\Auth;

class PProfileController extends Controller
{
    use ChecksPasswords;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // ambil data user yang login
        $pegawai = $user->pegawai; // relasi ke tabel pegawai

        return view('pegawai.profile.prof2', compact('user', 'pegawai'));
    }

    public function edit(string $id)
{
    $user = Auth::user();
    $pegawai = $user->pegawai;

    return view('pegawai.profile.edit', compact('user', 'pegawai'));
}

public function update(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'nama'  => ['required', 'string', 'max:255'],
        'no_hp' => ['nullable', 'string', 'max:20'],
    ], [
        'nama.required' => 'Nama lengkap wajib diisi.',
    ]);

    $user->nama  = $validated['nama'];
    $user->no_hp = $validated['no_hp'] ?? $user->no_hp;

    $user->save();

    return back()->with('success', 'Profile berhasil diperbarui!');
}

public function updatePassword(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'current_password' => [
            'required',
            function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }
        ],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ], [
        'current_password.required'  => 'Password saat ini wajib diisi.',
        'new_password.required'      => 'Password baru wajib diisi.',
        'new_password.min'           => 'Password baru harus minimal 8 karakter.',
        'new_password.confirmed'     => 'Konfirmasi password baru tidak cocok.',
    ]);

    $user->password = Hash::make($validated['new_password']);
    $user->save();

    return back()->with('success_password', 'Password berhasil diubah!');
}

    // ✅ COPY HELPER DARI AUTHCONTROLLER (atau buat shared helper)
    private function checkPassword(string $plain, string $hashed): bool
    {
        // Bcrypt
        if (str_starts_with($hashed, '$2y$') || str_starts_with($hashed, '$2a$')) {
            return Hash::check($plain, $hashed);
        }
        // MD5
        if (strlen($hashed) === 32 && ctype_xdigit($hashed)) {
            return md5($plain) === $hashed;
        }
        // SHA1
        if (strlen($hashed) === 40 && ctype_xdigit($hashed)) {
            return sha1($plain) === $hashed;
        }
        // SHA256
        if (strlen($hashed) === 64 && ctype_xdigit($hashed)) {
            return hash('sha256', $plain) === $hashed;
        }
        return false;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
