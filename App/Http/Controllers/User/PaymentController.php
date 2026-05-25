<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Untuk request ke gateway

class PaymentController extends Controller
{
    public function show($booking_id)
    {
        $user = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        if (!$pelanggan) abort(403);

        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->where('b.booking_id', $booking_id)
            ->where('b.pelanggan_id', $pelanggan->pelanggan_id)
            ->whereNotIn('b.status', ['cancelled'])
            ->select('b.*', 'pl.pelanggan_id')
            ->first();
        if (!$booking) abort(404, 'Booking tidak ditemukan.');

        $existingPayment = DB::table('pembayaran')
            ->where('booking_id', $booking_id)
            ->whereIn('status', ['pending', 'verified'])
            ->first();
        if ($existingPayment) {
            return redirect()->route('pelanggan.payment.success', $booking_id)
                ->with('info', 'Booking ini sudah memiliki pembayaran.');
        }

        $layananList = DB::table('booking_detail as bd')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('bd.booking_id', $booking_id)
            ->select('l.nama_layanan', 'l.durasi', 'lc.harga', 'lc.harga_promo', 'c.nama_cabang', 'c.alamat')
            ->get();

        $total = $layananList->sum(fn($item) => $item->harga_promo > 0 ? $item->harga_promo : $item->harga);
        $salon = DB::table('salon')->first();

        return view('user.booking.payment', compact('booking', 'layananList', 'total', 'salon', 'user'));
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

        $total = DB::table('booking_detail as bd')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->where('bd.booking_id', $booking_id)
            ->value(DB::raw('SUM(COALESCE(NULLIF(lc.harga_promo,0), lc.harga))'));

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

    /**
     * Webhook / Callback dari Payment Gateway
     * Route ini harus di-expose ke publik: POST /api/payment/callback
     */
    public function webhook(Request $request)
    {
        $gateway_ref_id = $request->input('order_id'); 
        $status         = $request->input('transaction_status'); 

        if (in_array($status, ['settlement', 'capture', 'success'])) {
            $pembayaran = DB::table('pembayaran')
                ->where('gateway_ref_id', $gateway_ref_id)
                ->where('status', 'pending')
                ->first();

            if ($pembayaran) {
                DB::table('pembayaran')
                    ->where('pembayaran_id', $pembayaran->pembayaran_id)
                    ->update([
                        'status'        => 'verified',
                        'verified_by'   => null, 
                        'tanggal_bayar' => now(),
                    ]);

            }
        }

        return response()->json(['status' => 'OK']);
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

        $layananList = DB::table('booking_detail as bd')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('bd.booking_id', $booking_id)
            ->select('l.nama_layanan', 'l.durasi', 'lc.harga', 'lc.harga_promo', 'c.nama_cabang', 'c.alamat')
            ->get();

        $total = $layananList->sum(fn($item) => $item->harga_promo > 0 ? $item->harga_promo : $item->harga);

        return view('user.booking.success', compact('booking', 'pembayaran', 'layananList', 'total', 'user'));
    }
}