<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OwnerProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('owner.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama'         => ['required', 'string', 'max:100'],
            'no_hp'        => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'foto_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama.required'       => 'Nama lengkap wajib diisi.',
            'no_hp.regex'         => 'Format nomor HP tidak valid.',
            'foto_profile.image'  => 'File harus berupa gambar.',
            'foto_profile.max'    => 'Ukuran foto maksimal 2MB.',
        ]);

        $updateData = [
            'nama'       => trim($validated['nama']),
            'no_hp'      => $validated['no_hp'] ? preg_replace('/[^0-9+]/', '', $validated['no_hp']) : null,
            'updated_at' => now(),
        ];

        // Hapus foto lama jika ada foto baru
        if ($request->hasFile('foto_profile')) {
            if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
                Storage::disk('public')->delete($user->foto_profile);
            }
            $path = $request->file('foto_profile')->store('profile', 'public');
            $updateData['foto_profile'] = $path;
        }

        // Hapus foto jika diminta
        if ($request->input('hapus_foto') === '1') {
            if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
                Storage::disk('public')->delete($user->foto_profile);
            }
            $updateData['foto_profile'] = null;
        }

        DB::table('users')->where('user_id', $user->user_id)->update($updateData);

        return redirect()->route('owner.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password_lama'               => ['required'],
            'password_baru'               => ['required', 'min:8', 'confirmed'],
            'password_baru_confirmation'  => ['required'],
        ], [
            'password_lama.required'      => 'Kata sandi lama wajib diisi.',
            'password_baru.required'      => 'Kata sandi baru wajib diisi.',
            'password_baru.min'           => 'Kata sandi baru minimal 8 karakter.',
            'password_baru.confirmed'     => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Kata sandi lama tidak sesuai.'])
                ->withInput();
        }

        DB::table('users')->where('user_id', $user->user_id)->update([
            'password'   => Hash::make($request->password_baru),
            'updated_at' => now(),
        ]);

        return redirect()->route('owner.profile')->with('success', 'Kata sandi berhasil diubah.');
    }
}