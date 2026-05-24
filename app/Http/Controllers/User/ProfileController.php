<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ========================
    // Tampilkan halaman profile
    // ========================
    public function index()
    {
        $user = Auth::user();

        // Data pelanggan (alamat, tanggal_lahir)
        $pelanggan = DB::table('pelanggan')
            ->where('user_id', $user->user_id)
            ->first();

        // Riwayat booking beserta layanan & pembayaran
        $bookings = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->leftJoin('pembayaran as py', 'b.booking_id', '=', 'py.booking_id')
            ->where('pl.user_id', $user->user_id)
            ->select(
                'b.booking_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                'b.tipe_booking',
                'b.created_at',
                'py.jumlah',
                'py.metode_pembayaran',
                'py.status as status_bayar'
            )
            ->orderByDesc('b.created_at')
            ->get();

        // Ambil detail layanan per booking
        $bookings = $bookings->map(function ($booking) {
            $booking->layanan = DB::table('booking_detail as bd')
                ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
                ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
                ->where('bd.booking_id', $booking->booking_id)
                ->pluck('l.nama_layanan')
                ->implode(', ');
            return $booking;
        });

        return view('user.profile.profile', compact('user', 'pelanggan', 'bookings'));
    }

    // ========================
    // Update data profile
    // ========================
    public function update(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'foto_profile'  => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        // Default foto lama
        $path = $user->foto_profile ?? null;

        // Hapus foto
        if ($request->hapus_foto) {

            if (
                $user->foto_profile &&
                Storage::disk('public')->exists($user->foto_profile)
            ) {

                Storage::disk('public')
                    ->delete($user->foto_profile);
            }

            $path = null;
        }

        // Upload foto baru
        elseif (
            $request->hasFile('foto_profile') ||
            $request->hasFile('camera_profile')
        ) {

            $file = $request->file('foto_profile')
                ?? $request->file('camera_profile');

            $path = $file->store('profile', 'public');

            if (
                $user->foto_profile &&
                Storage::disk('public')->exists($user->foto_profile)
            ) {
                Storage::disk('public')
                    ->delete($user->foto_profile);
            }
        }

        // Update users
        $userUpdated = DB::table('users')
            ->where('user_id', $user->user_id)
            ->update([
                'nama'         => $request->nama,
                'no_hp'        => $request->no_hp,
                'foto_profile' => $path,
                'updated_at'   => now(),
            ]);

        Auth::setUser(
            \App\Models\User::find($user->user_id)
        );

        // cek pelanggan
        $pelanggan = DB::table('pelanggan')
            ->where('user_id', $user->user_id)
            ->first();

        $pelangganUpdated = 0;

        if ($pelanggan) {

            $pelangganUpdated = DB::table('pelanggan')
                ->where('user_id', $user->user_id)
                ->update([
                    'alamat'        => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                ]);

        } else {

            DB::table('pelanggan')
                ->insert([
                    'user_id'       => $user->user_id,
                    'alamat'        => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    // 'created_at'    => now(),
                    // 'updated_at'    => now(),
                    
                ]);

            $pelangganUpdated = 1;
        }

        if (
            $userUpdated ||
            $pelangganUpdated ||
            $request->hasFile('foto_profile')
        ) {

            return back()->with(
                'success',
                'Profile berhasil diperbarui.'
            );
        }

        return back()->with(
            'error',
            'Tidak ada data yang diubah.'
        );
    }

    // ========================
    // Ganti password
    // ========================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',

            'password_baru' => [
                'required',
                'min:6',
                'different:password_lama',
                'confirmed'
            ],
        ], [
            'password_baru.different' =>
                'Password baru tidak boleh sama dengan password lama.',

            'password_baru.confirmed' =>
                'Konfirmasi password baru tidak cocok.',

            'password_baru.min' =>
                'Password baru minimal 6 karakter.',
        ]);

        $user = Auth::user();

        $passwordValid = Hash::check($request->password_lama, $user->password)
            || hash('sha256', $request->password_lama) === $user->password;

        if (!$passwordValid) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withFragment('password');
        }

        if ($request->password_lama === $request->password_baru) {

            return back()
                ->withErrors([
                    'password_baru' =>
                        'Password baru tidak boleh sama dengan password lama.'
                ])
                ->withFragment('password');
        }

        DB::table('users')
            ->where('user_id', $user->user_id)
            ->update([
                'password'   => Hash::make($request->password_baru),
                'updated_at' => now(),
            ]);

        return back()->with('success_password', 'Password berhasil diubah.')->withFragment('password');
    }
}