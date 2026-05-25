<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create($layanan_cabang_id)
    {
        $layanan = DB::table('layanan_cabang as lc')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->join('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->where('lc.layanan_cabang_id', $layanan_cabang_id)
            ->where('lc.status', 'tersedia')
            ->select(
                'lc.layanan_cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'lc.cabang_id',
                'l.nama_layanan',
                'l.deskripsi',
                'l.durasi',
                'l.cover_foto',
                'l.kategori_pelanggan',
                'jl.nama_jenis',
                'c.nama_cabang',
                'c.alamat'
            )
            ->first();

        if (!$layanan) {
            abort(404, 'Layanan tidak ditemukan atau tidak tersedia.');
        }

        $layanan->cover_foto = !empty($layanan->cover_foto)
            ? asset($layanan->cover_foto)
            : asset('layanan/default.jpg');

        // Ambil data user yang login
        $user = Auth::user();

        // Ambil jadwal operasional cabang ini
        $jadwalOperasional = DB::table('jadwal_operasional')
            ->where('cabang_id', $layanan->cabang_id)
            ->get()
            ->keyBy('hari');

        return view('user.booking.create', compact('layanan', 'user', 'jadwalOperasional'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_cabang_id' => 'required|integer|exists:layanan_cabang,layanan_cabang_id',
            'tanggal'           => 'required|date|after_or_equal:today',
            'jam'               => 'required',
        ], [
            'tanggal.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'tanggal.required'       => 'Tanggal booking wajib diisi.',
            'jam.required'           => 'Jam booking wajib dipilih.',
        ]);

        $user = Auth::user();

        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        
        if (!$pelanggan) {
            \Log::error('Pelanggan not found', ['user_id' => $user->user_id]);
            return back()->withErrors(['error' => 'Data pelanggan tidak ditemukan. Hubungi admin.'])->withInput();
        }

        $conflict = DB::table('booking')
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->where('tanggal_booking', $request->tanggal)
            ->where('jam_booking', $request->jam)
            ->whereNotIn('status', ['cancelled'])
            ->exists();
        
        if ($conflict) {
            return back()->withErrors(['error' => 'Anda sudah memiliki booking pada tanggal dan jam tersebut.'])->withInput();
        }

        $layananCabang = DB::table('layanan_cabang')
            ->where('layanan_cabang_id', $request->layanan_cabang_id)
            ->where('status', 'tersedia')
            ->first();
        
        if (!$layananCabang) {
            return back()->withErrors(['error' => 'Layanan tidak tersedia.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $bookingId = DB::table('booking')->insertGetId([
                'pelanggan_id'    => $pelanggan->pelanggan_id,
                'tanggal_booking' => $request->tanggal,
                'jam_booking'     => $request->jam,
                'status'          => 'pending',
                'tipe_booking'    => 'online',
                'pegawai_id'      => null,
                'created_by'      => $user->user_id,
            ]);

            DB::table('booking_detail')->insert([
                'booking_id'        => $bookingId,
                'layanan_cabang_id' => $request->layanan_cabang_id,
            ]);

            DB::commit();

            return redirect()->route('pelanggan.payment.show', $bookingId)
                ->with('success', 'Booking berhasil dibuat! Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking store FAILED', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Gagal membuat booking: ' . $e->getMessage()])->withInput();
        }
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        
        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $query = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->leftJoin('pembayaran as p', function($join) {
                $join->on('p.booking_id', '=', 'b.booking_id')
                    ->whereIn('p.status', ['pending', 'verified']);
            })
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->leftJoin('cabang as c', 'c.cabang_id', '=', 'lc.cabang_id')
            ->where('pl.user_id', $user->user_id)
            ->groupBy(
                'b.booking_id',
                'b.tanggal_booking',
                'b.jam_booking', 
                'b.status',
                'b.tipe_booking',
                'b.created_at',
                'p.pembayaran_id',
                'p.metode_pembayaran',
                'p.jumlah',
                'p.status',
                'p.bukti_pembayaran',
                'c.nama_cabang',
                'c.alamat'
            )
            ->select(
                'b.booking_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status as booking_status',
                'b.tipe_booking',
                'b.created_at',
                'p.pembayaran_id',
                'p.metode_pembayaran',
                'p.jumlah as total_bayar',
                'p.status as payment_status',
                'p.bukti_pembayaran',
                DB::raw('GROUP_CONCAT(l.nama_layanan SEPARATOR ", ") as layanan_nama'),
                DB::raw('COUNT(DISTINCT bd.booking_detail_id) as jumlah_layanan'),
                'c.nama_cabang',
                'c.alamat'
            );

        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }

        $bookings = $query->orderBy('b.created_at', 'desc')->paginate(10);

        return view('user.booking.history', compact('bookings', 'user'));
    }

    public function show($booking_id)
    {
        $user = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        
        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->leftJoin('pegawai as pg', 'b.pegawai_id', '=', 'pg.pegawai_id')
            ->leftJoin('users as u_pg', 'pg.user_id', '=', 'u_pg.user_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->select('b.*', 'u_pg.nama as nama_pegawai')
            ->first();
        
        if (!$booking) abort(404);

        $layananList = DB::table('booking_detail as bd')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('bd.booking_id', $booking_id)
            ->select('l.*', 'lc.harga', 'lc.harga_promo', 'c.nama_cabang', 'c.alamat')
            ->get();

        $pembayaran = DB::table('pembayaran')
            ->where('booking_id', $booking_id)
            ->orderByDesc('pembayaran_id')
            ->first();

        $total = $layananList->sum(fn($item) => $item->harga_promo > 0 ? $item->harga_promo : $item->harga);

        return view('user.booking.detail', compact('booking', 'layananList', 'pembayaran', 'total', 'user'));
    }

    public function showReschedule($booking_id)
    {
        $user = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        
        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->leftJoin('pembayaran as p', 'p.booking_id', '=', 'b.booking_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->select('b.*', 'p.status as payment_status')
            ->first();
        
        if (!$booking) abort(404);
        
        // Hanya bisa reschedule jika status pending/confirmed dan belum completed/cancelled
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'Booking ini tidak dapat direschedule.']);
        }
        
        // Ambil jadwal operasional cabang
        $cabangId = DB::table('booking_detail as bd')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->where('bd.booking_id', $booking_id)
            ->value('lc.cabang_id');
        
        $jadwalOperasional = DB::table('jadwal_operasional')
            ->where('cabang_id', $cabangId)
            ->get()
            ->keyBy('hari');
        
        // Ambil layanan untuk ditampilkan
        $layananList = DB::table('booking_detail as bd')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->where('bd.booking_id', $booking_id)
            ->select('l.nama_layanan', 'l.durasi', 'lc.harga', 'lc.harga_promo')
            ->get();
        
        $total = $layananList->sum(fn($item) => $item->harga_promo > 0 ? $item->harga_promo : $item->harga);
        
        return view('user.booking.reschedule', compact('booking', 'jadwalOperasional', 'layananList', 'total', 'user'));
    }


    public function processReschedule(Request $request, $booking_id)
    {
        $request->validate([
            'new_tanggal' => 'required|date|after_or_equal:today',
            'new_jam'     => 'required',
            'reason'      => 'nullable|string|max:500',
        ], [
            'new_tanggal.after_or_equal' => 'Tanggal baru tidak boleh di masa lalu.',
        ]);
        
        $user = Auth::user();
        $pelanggan = DB::table('pelanggan')->where('user_id', $user->user_id)->first();
        
        $booking = DB::table('booking')
            ->where('booking_id', $booking_id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->where('status', 'pending')
            ->first();
        
        if (!$booking) {
            return back()->withErrors(['error' => 'Booking tidak ditemukan atau tidak dapat direschedule.']);
        }
        
        // Cek conflict slot baru
        $conflict = DB::table('booking')
            ->where('tanggal_booking', $request->new_tanggal)
            ->where('jam_booking', $request->new_jam)
            ->where('status', '!=', 'cancelled')
            ->where('booking_id', '!=', $booking_id)
            ->exists();
        
        if ($conflict) {
            return back()->withErrors(['error' => 'Slot waktu tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }
        
        DB::beginTransaction();
        try {
            // Insert ke booking_reschedule
            DB::table('booking_reschedule')->insert([
                'booking_id'    => $booking_id,
                'old_tanggal'   => $booking->tanggal_booking,
                'old_jam'       => $booking->jam_booking,
                'new_tanggal'   => $request->new_tanggal,
                'new_jam'       => $request->new_jam,
                'reason'        => $request->reason,
                'created_by'    => $user->user_id,
            ]);
            
            DB::table('notifikasi')->insert([
                'user_id'     => $user->user_id,
                'pesan'       => "Booking #{$booking_id} berhasil direschedule ke " . 
                                \Carbon\Carbon::parse($request->new_tanggal)->isoFormat('D MMM Y') . 
                                ' pukul ' . substr($request->new_jam, 0, 5) . ' WIB',
                'tipe'        => 'booking',
                'status_baca' => 'belum',
            ]);
            
            DB::table('audit_log')->insert([
                'user_id'     => $user->user_id,
                'action'      => 'RESCHEDULE',
                'table_name'  => 'booking',
                'record_id'   => $booking_id,
                'description' => "Booking direschedule dari {$booking->tanggal_booking} {$booking->jam_booking} ke {$request->new_tanggal} {$request->new_jam}",
            ]);
            
            DB::commit();
            
            return redirect()->route('pelanggan.booking.show', $booking_id)
                ->with('success', 'Booking berhasil direschedule!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal reschedule: ' . $e->getMessage()])->withInput();
        }
    }
}