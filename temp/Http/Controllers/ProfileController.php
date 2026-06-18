<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Ulasan;

class PegawaiProfileController extends Controller
{
    /**
     * Tampilkan halaman profile pegawai.
     */
    public function index()
    {
        $user    = Auth::user();
        $pegawai = $user->pegawai;

        // Rata-rata rating dari ulasan booking milik pegawai ini
        $ratingAvg = Ulasan::whereHas('booking', fn($q) =>
                $q->where('pegawai_id', $pegawai->pegawai_id)
            )
            ->avg('rating');

        // Jumlah ulasan
        $ratingCount = Ulasan::whereHas('booking', fn($q) =>
                $q->where('pegawai_id', $pegawai->pegawai_id)
            )
            ->count();

        // Total booking selesai
        $totalSelesai = Booking::where('pegawai_id', $pegawai->pegawai_id)
            ->where('status', 'completed')
            ->count();

        return view('pegawai.profile.prof1', compact(
            'user',
            'pegawai',
            'ratingAvg',
            'ratingCount',
            'totalSelesai',
        ));
    }

    /**
     * Update data profil (nama, no_hp, foto_profile).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'          => 'required|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
            'foto_profile'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama'  => $request->nama,
            'no_hp' => $request->no_hp,
        ];

        // Ganti foto jika ada upload baru
        if ($request->hasFile('foto_profile')) {
            // Hapus foto lama kalau bukan default
            if ($user->foto_profile) {
                Storage::disk('public')->delete($user->foto_profile);
            }
            $data['foto_profile'] = $request->file('foto_profile')
                ->store('profile', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'     => 'required',
            'password_baru'     => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Logout pegawai.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}