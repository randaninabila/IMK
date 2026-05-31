<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show($booking_id)
    {
        $user = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->select('b.*')
            ->first();

        if (!$booking) {
            abort(404, 'Booking tidak ditemukan.');
        }

        // ✅ CEK TIPE BOOKING - PAKET ATAU SINGLE
        $bookingDetail = DB::table('booking_detail')
            ->where('booking_id', $booking_id)
            ->first();

        if (!$bookingDetail) {
            abort(404, 'Detail booking tidak ditemukan.');
        }

        $isPaket = !empty($bookingDetail->paket_cabang_id);

        if ($isPaket) {
            // 🔹 BOOKING PAKET: Ambil harga dari paket_cabang
            $paketInfo = DB::table('booking_detail as bd')
                ->join('paket_cabang as pc', 'bd.paket_cabang_id', '=', 'pc.paket_id')
                ->join('paket_layanan as pl', 'pc.paket_id', '=', 'pl.paket_id')
                ->join('paket_detail as pd', 'pl.paket_id', '=', 'pd.paket_id')
                ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
                ->where('bd.booking_id', $booking_id)
                ->select(
                    'l.nama_layanan',
                    'l.durasi',
                    'pl.nama_paket',
                    'pc.harga_normal',
                    'pc.harga_promo'
                )
                ->distinct()
                ->get();

            // Hitung total dari harga paket (bukan jumlah individual)
            $paketHarga = DB::table('paket_cabang')
                ->where('paket_id', $bookingDetail->paket_cabang_id)
                ->first();

            $total = $paketHarga 
                ? ($paketHarga->harga_promo > 0 ? $paketHarga->harga_promo : $paketHarga->harga_normal)
                : 0;

            $layananList = $paketInfo;

        } else {
            // 🔹 BOOKING SINGLE: Ambil dari layanan_cabang
            $layananList = DB::table('booking_detail as bd')
                ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
                ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
                ->where('bd.booking_id', $booking_id)
                ->select(
                    'l.nama_layanan',
                    'l.durasi',
                    'lc.harga',
                    'lc.harga_promo'
                )
                ->get();

            $total = $layananList->sum(function($item) {
                return $item->harga_promo > 0 ? $item->harga_promo : $item->harga;
            });
        }

        $pembayaran = DB::table('pembayaran')
            ->where('booking_id', $booking_id)
            ->orderByDesc('pembayaran_id')
            ->first();

        return view('user.booking.payment', compact(
            'booking',
            'layananList',
            'total',
            'pembayaran',
            'user'
        ));
    }

    public function process(Request $request, $booking_id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:qris,cash',
            'bukti_pembayaran'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'metode_pembayaran.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'bukti_pembayaran.image'     => 'Bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.max'       => 'Ukuran gambar maksimal 2MB.',
        ]);

        $user = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        if (!$pelanggan) abort(403);

        $booking = DB::table('booking')
            ->where('booking_id', $booking_id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->first();

        if (!$booking) abort(404);

        if (DB::table('pembayaran')
            ->where('booking_id', $booking_id)
            ->whereIn('status', ['pending', 'verified'])
            ->exists()) {
            return redirect()->route('pelanggan.payment.success', $booking_id)
                ->with('info', 'Pembayaran sudah tercatat sebelumnya.');
        }

        // ✅ CEK TIPE BOOKING UNTUK HITUNG TOTAL
        $bookingDetail = DB::table('booking_detail')
            ->where('booking_id', $booking_id)
            ->first();

        if (!$bookingDetail) {
            return back()->withErrors(['error' => 'Detail booking tidak ditemukan.']);
        }

        $isPaket = !empty($bookingDetail->paket_cabang_id);

        if ($isPaket) {
            // Paket: ambil harga dari paket_cabang
            $paketHarga = DB::table('paket_cabang')
                ->where('paket_id', $bookingDetail->paket_cabang_id)
                ->first();
            
            $total = $paketHarga 
                ? ($paketHarga->harga_promo > 0 ? $paketHarga->harga_promo : $paketHarga->harga_normal)
                : 0;
        } else {
            // Single: hitung dari layanan_cabang
            $total = DB::table('booking_detail as bd')
                ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
                ->where('bd.booking_id', $booking_id)
                ->value(DB::raw('SUM(COALESCE(NULLIF(lc.harga_promo, 0), lc.harga))'));
        }

        $buktiBayar = null;
        if ($request->metode_pembayaran === 'qris' && $request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('bukti'))) {
                mkdir(public_path('bukti'), 0755, true);
            }
            $file->move(public_path('bukti'), basename($filename));
            $buktiBayar = $filename;
        }

        DB::beginTransaction();
        try {
            $pembayaranId = DB::table('pembayaran')->insertGetId([
                'booking_id'        => $booking_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah'            => $total,
                'bukti_pembayaran'  => $buktiBayar,
                'status'            => 'pending',
                'tanggal_bayar'     => now(),
                'verified_by'       => null,
            ]);

            DB::table('notifikasi')->insert([
                'user_id'     => $user->user_id,
                'pesan'       => $request->metode_pembayaran === 'cash'
                    ? "Pembayaran tunai untuk Booking #{$booking_id} telah dicatat. Silakan bayar di lokasi saat kunjungan."
                    : "Bukti pembayaran QRIS untuk Booking #{$booking_id} telah diterima. Menunggu verifikasi admin.",
                'tipe'        => 'pembayaran',
                'status_baca' => 'belum',
            ]);

            DB::commit();
            return redirect()->route('pelanggan.payment.success', $booking_id)
                ->with('payment_method', $request->metode_pembayaran)
                ->with('pembayaran_id', $pembayaranId);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses pembayaran: ' . $e->getMessage()]);
        }
    }

    public function success($booking_id)
    {
        $user = Auth::user();
        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->select('b.*')
            ->first();

        if (!$booking) abort(404);

        $pembayaran = DB::table('pembayaran')
            ->where('booking_id', $booking_id)
            ->orderByDesc('pembayaran_id')
            ->first();

        // ✅ CEK TIPE BOOKING
        $bookingDetail = DB::table('booking_detail')
            ->where('booking_id', $booking_id)
            ->first();

        if (!$bookingDetail) {
            abort(404, 'Detail booking tidak ditemukan.');
        }

        $isPaket = !empty($bookingDetail->paket_cabang_id);

        if ($isPaket) {
            // 🔹 BOOKING PAKET
            $paketInfo = DB::table('booking_detail as bd')
                ->join('paket_cabang as pc', 'bd.paket_cabang_id', '=', 'pc.paket_id')
                ->join('paket_layanan as pl', 'pc.paket_id', '=', 'pl.paket_id')
                ->join('paket_detail as pd', 'pl.paket_id', '=', 'pd.paket_id')
                ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
                ->join('cabang as c', 'pc.cabang_id', '=', 'c.cabang_id')
                ->where('bd.booking_id', $booking_id)
                ->select(
                    'l.nama_layanan',
                    'l.durasi',
                    'pl.nama_paket',
                    'pc.harga_normal',
                    'pc.harga_promo',
                    'c.nama_cabang',
                    'c.alamat'
                )
                ->distinct()
                ->get();

            // Hitung total dari harga paket
            $paketHarga = DB::table('paket_cabang')
                ->where('paket_id', $bookingDetail->paket_cabang_id)
                ->first();

            $total = $paketHarga 
                ? ($paketHarga->harga_promo > 0 ? $paketHarga->harga_promo : $paketHarga->harga_normal)
                : 0;

            $layananList = $paketInfo;

        } else {
            // 🔹 BOOKING SINGLE
            $layananList = DB::table('booking_detail as bd')
                ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
                ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
                ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
                ->where('bd.booking_id', $booking_id)
                ->select(
                    'l.nama_layanan',
                    'l.durasi',
                    'lc.harga',
                    'lc.harga_promo',
                    'c.nama_cabang',
                    'c.alamat'
                )
                ->get();

            $total = $layananList->sum(function($item) {
                return $item->harga_promo > 0 ? $item->harga_promo : $item->harga;
            });
        }

        return view('user.booking.success', compact(
            'booking',
            'pembayaran',
            'layananList',
            'total',
            'user'
        ));
    }
}