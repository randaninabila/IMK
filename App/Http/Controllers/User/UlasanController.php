<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function create($booking_id)
    {
        $user      = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        if (!$pelanggan) abort(403);

        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->where('b.status', 'completed')
            ->select('b.*')
            ->first();

        if (!$booking) abort(404);

        // Cek sudah ulasan belum
        if (DB::table('ulasan')->where('booking_id', $booking_id)->exists()) {
            return redirect()->route('pelanggan.booking.show', $booking_id)
                ->with('info', 'Kamu sudah memberikan ulasan untuk pesanan ini.');
        }

        // Cek tipe booking
        $detail  = DB::table('booking_detail')->where('booking_id', $booking_id)->first();
        $isPaket = !is_null($detail->paket_cabang_id ?? null);

        if ($isPaket) {
            $paketInfo  = DB::table('paket_cabang as pc')
                ->join('paket_layanan as pl2', 'pc.paket_id', '=', 'pl2.paket_id')
                ->join('cabang as c', 'pc.cabang_id', '=', 'c.cabang_id')
                ->where('pc.paket_cabang_id', $detail->paket_cabang_id)
                ->select('pl2.nama_paket', 'c.nama_cabang')
                ->first();
            $namaItem   = $paketInfo->nama_paket ?? 'Paket';
            $namaCabang = $paketInfo->nama_cabang ?? '';
        } else {
            $layananList = DB::table('booking_detail as bd')
                ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
                ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
                ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
                ->where('bd.booking_id', $booking_id)
                ->select('l.nama_layanan', 'c.nama_cabang')
                ->get();
            $namaItem   = $layananList->pluck('nama_layanan')->join(', ');
            $namaCabang = $layananList->first()->nama_cabang ?? '';
        }

        return view('user.ulasan.inputulasan', compact(
            'booking', 'namaItem', 'namaCabang', 'isPaket', 'user'
        ));
    }

    public function store(Request $request, $booking_id)
    {
        $request->validate([
            'komentar'   => 'required|string|max:1000',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nama_samar' => 'nullable|boolean', // ✅ Tambah validasi
        ], [
            'komentar.required' => 'Komentar wajib diisi.',
            'foto.image'        => 'Foto harus berupa gambar.',
            'foto.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        $user      = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        if (!$pelanggan) abort(403);

        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->where('b.status', 'completed')
            ->first();
        if (!$booking) abort(404);

        if (DB::table('ulasan')->where('booking_id', $booking_id)->exists()) {
            return redirect()->route('pelanggan.booking.show', $booking_id)
                ->with('info', 'Kamu sudah memberikan ulasan untuk pesanan ini.');
        }

        DB::beginTransaction();
        try {
            // ✅ Insert ulasan dengan kolom nama_samar
            $ulasanId = DB::table('ulasan')->insertGetId([
                'booking_id'   => $booking_id,
                'pelanggan_id' => $pelanggan->pelanggan_id,
                'rating'       => 5, // Default rating 5
                'komentar'     => $request->komentar,
                'nama_samar'   => $request->boolean('nama_samar'), // ✅ Simpan opsi samaran
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // Upload foto jika ada
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $file     = $request->file('foto');
                $filename = 'ulasan/ulasan_' . $ulasanId . '_' . time() . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('ulasan'))) {
                    mkdir(public_path('ulasan'), 0755, true);
                }
                $file->move(public_path('ulasan'), basename($filename));
                DB::table('ulasan_foto')->insert([
                    'ulasan_id'  => $ulasanId,
                    'url_foto'   => $filename,
                    'status'     => 'pending',
                    'created_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('pelanggan.booking.show', $booking_id)
                ->with('success', 'Ulasan kamu berhasil dikirim! Terima kasih 🌸');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan ulasan: ' . $e->getMessage()]);
        }
    }
}