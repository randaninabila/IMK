<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // CREATE — Booking layanan tunggal
    public function create(Request $request, $layanan_cabang_id = null)
    {
        $user = Auth::user();

        if (!$layanan_cabang_id) {
            abort(404, 'Layanan tidak ditemukan.');
        }

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

        $jadwalOperasional = DB::table('jadwal_operasional')
            ->where('cabang_id', $layanan->cabang_id)
            ->get()
            ->keyBy('hari');

        $specialists = DB::table('pegawai as p')
            ->join('users as u', 'p.user_id', '=', 'u.user_id')
            ->where('p.cabang_id', $layanan->cabang_id)
            ->where('p.status_kerja', 'aktif')
            ->select('p.pegawai_id', 'u.nama as nama_spesialis')
            ->get();

        return view('user.booking.create', compact('layanan', 'user', 'jadwalOperasional', 'specialists'));
    }

    // CREATE FROM PAKET — Booking dari halaman paket
    public function createFromPaket($paket_id, $cabang_id)
    {
        $user = Auth::user();

        $paket = DB::table('paket_layanan')
            ->where('paket_id', $paket_id)
            ->firstOrFail();

        $paketCabang = DB::table('paket_cabang')
            ->where('paket_id', $paket_id)
            ->where('cabang_id', $cabang_id)
            ->first();

        if (!$paketCabang || $paketCabang->status !== 'tersedia') {
            abort(404, 'Paket tidak tersedia di cabang ini.');
        }

        $layananList = DB::table('paket_detail as pd')
            ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
            ->join('layanan_cabang as lc', function ($join) use ($cabang_id) {
                $join->on('l.layanan_id', '=', 'lc.layanan_id')
                    ->where('lc.cabang_id', $cabang_id)
                    ->where('lc.status', 'tersedia');
            })
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('pd.paket_id', $paket_id)
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
                'c.nama_cabang',
                'c.alamat'
            )
            ->get();

        if ($layananList->isEmpty()) {
            abort(404, 'Layanan dalam paket tidak tersedia.');
        }

        foreach ($layananList as $item) {
            $item->cover_foto = !empty($item->cover_foto)
                ? asset('layanan/' . basename($item->cover_foto))
                : asset('layanan/default.jpg');
        }

        $hargaPaket = $paketCabang->harga_promo > 0
            ? $paketCabang->harga_promo
            : $paketCabang->harga_normal;

        $totalHarga = $hargaPaket;

        $totalSatuan = $layananList->sum(function ($item) {
            return $item->harga_promo > 0 ? $item->harga_promo : $item->harga;
        });
        $hemat = max(0, $totalSatuan - $totalHarga);

        $jadwalOperasional = DB::table('jadwal_operasional')
            ->where('cabang_id', $cabang_id)
            ->get()
            ->keyBy('hari');

        $specialists = DB::table('pegawai as p')
            ->join('users as u', 'p.user_id', '=', 'u.user_id')
            ->where('p.cabang_id', $cabang_id)
            ->where('p.status_kerja', 'aktif')
            ->select('p.pegawai_id', 'u.nama as nama_spesialis')
            ->get();

        $packageData = [
            'paket'           => $paket,
            'paketCabang'     => $paketCabang,
            'paket_cabang_id' => $paketCabang->paket_cabang_id,
            'totalHarga'      => $totalHarga,
            'hemat'           => $hemat,
        ];

        $isPackageBooking = true;

        return view('user.booking.create', compact(
            'layananList',
            'user',
            'jadwalOperasional',
            'packageData',
            'isPackageBooking',
            'cabang_id',
            'totalHarga',
            'specialists'
        ));
    }

    // STORE — Simpan booking layanan / paket
    public function store(Request $request)
    {
        $isPaket = $request->boolean('is_paket');

        if ($isPaket) {
            $request->validate([
                'paket_cabang_id' => 'required|integer|exists:paket_cabang,paket_cabang_id',
                'tanggal'         => 'required|date|after_or_equal:today',
                'jam'             => 'required',
                'pegawai_id'      => 'nullable|integer|exists:pegawai,pegawai_id',
            ], [
                'tanggal.after_or_equal'   => 'Tanggal booking tidak boleh di masa lalu.',
                'tanggal.required'         => 'Tanggal booking wajib diisi.',
                'jam.required'             => 'Jam booking wajib dipilih.',
                'paket_cabang_id.required' => 'Paket tidak boleh kosong.',
                'paket_cabang_id.exists'   => 'Paket tidak ditemukan.',
            ]);
        } else {
            $request->validate([
                'layanan_cabang_id'   => 'required|array|min:1|max:1',
                'layanan_cabang_id.*' => 'integer|exists:layanan_cabang,layanan_cabang_id',
                'tanggal'             => 'required|date|after_or_equal:today',
                'jam'                 => 'required',
                'pegawai_id'          => 'nullable|integer|exists:pegawai,pegawai_id',
            ], [
                'tanggal.after_or_equal'      => 'Tanggal booking tidak boleh di masa lalu.',
                'tanggal.required'            => 'Tanggal booking wajib diisi.',
                'jam.required'                => 'Jam booking wajib dipilih.',
                'layanan_cabang_id.required'  => 'Layanan tidak boleh kosong.',
                'layanan_cabang_id.exists'    => 'Layanan tidak ditemukan.',
            ]);
        }

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $pelanggan = DB::table('pelanggan')
            ->where('user_id', $user->user_id)
            ->first();

        if (!$pelanggan) {
            return back()
                ->withErrors(['error' => 'Data pelanggan tidak ditemukan. Hubungi admin.'])
                ->withInput();
        }

        // Cek double booking pelanggan
        $conflict = DB::table('booking')
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->whereDate('tanggal_booking', $request->tanggal)
            ->whereTime('jam_booking', $request->jam)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['error' => 'Anda sudah memiliki booking pada tanggal dan jam tersebut.'])
                ->withInput();
        }

        // Cek double booking spesialis (jika dipilih)
        if ($request->filled('pegawai_id')) {
            $conflictPegawai = DB::table('booking')
                ->where('pegawai_id', $request->pegawai_id)
                ->where('tanggal_booking', $request->tanggal)
                ->where('jam_booking', $request->jam)
                ->whereNotIn('status', ['cancelled'])
                ->exists();

            if ($conflictPegawai) {
                return back()
                    ->withErrors(['error' => 'Spesialis yang Anda pilih sudah memiliki booking pada tanggal dan jam tersebut. Silakan pilih spesialis lain atau waktu lain.'])
                    ->withInput();
            }
        }

        // Validasi ketersediaan & hitung harga snapshot
        $lcId          = null;
        $paketCabangId = null;
        $hargaSnapshot = 0;

        if ($isPaket) {
            $paketCabang = $this->resolvePaketCabang($request->paket_cabang_id);

            if (!$paketCabang) {
                return back()
                    ->withErrors(['error' => 'Paket tidak tersedia.'])
                    ->withInput();
            }

            $paketCabangId = $paketCabang->paket_cabang_id;
            $hargaSnapshot = $paketCabang->harga_promo > 0
                ? $paketCabang->harga_promo
                : $paketCabang->harga_normal;
        } else {
            $lcId = $request->layanan_cabang_id[0];

            $layananHarga = DB::table('layanan_cabang')
                ->where('layanan_cabang_id', $lcId)
                ->where('status', 'tersedia')
                ->first();

            if (!$layananHarga) {
                return back()
                    ->withErrors(['error' => 'Layanan tidak tersedia.'])
                    ->withInput();
            }

            $hargaSnapshot = $layananHarga->harga_promo > 0
                ? $layananHarga->harga_promo
                : $layananHarga->harga;
        }

        DB::beginTransaction();

        try {
            $bookingId = DB::table('booking')->insertGetId([
                'pelanggan_id'    => $pelanggan->pelanggan_id,
                'tanggal_booking' => $request->tanggal,
                'jam_booking'     => $request->jam,
                'status'          => 'pending',
                'tipe_booking'    => 'online',
                'pegawai_id'      => $request->filled('pegawai_id') ? $request->pegawai_id : null,
                'created_by'      => $user->user_id,
                'created_at'      => now(),
            ]);

            DB::table('booking_detail')->insert([
                'booking_id'        => $bookingId,
                'layanan_cabang_id' => $isPaket ? null : $lcId,
                'paket_cabang_id'   => $isPaket ? $paketCabangId : null,
                'harga_snapshot'    => $hargaSnapshot,
            ]);

            DB::commit();

            return redirect()
                ->route('pelanggan.payment.show', $bookingId)
                ->with('success', 'Booking berhasil dibuat! Silakan lanjutkan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Booking store FAILED', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()
                ->withErrors(['error' => 'Gagal membuat booking: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // HISTORY — Riwayat booking pelanggan
    public function history(Request $request)
    {
        $user = Auth::user();

        $pelanggan = DB::table('pelanggan')
            ->where('user_id', $user->user_id)
            ->first();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $query = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->leftJoin('pembayaran as p', function ($join) {
                $join->on('p.booking_id', '=', 'b.booking_id')
                    ->whereIn('p.status', ['pending', 'verified']);
            })
            ->leftJoin('booking_detail as bd',  'bd.booking_id',         '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc',  'lc.layanan_cabang_id',  '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l',          'l.layanan_id',          '=', 'lc.layanan_id')
            ->leftJoin('cabang as c',           'c.cabang_id',           '=', 'lc.cabang_id')
            ->leftJoin('paket_cabang as pc',    'pc.paket_cabang_id',    '=', 'bd.paket_cabang_id')
            ->leftJoin('paket_layanan as pak',  'pak.paket_id',          '=', 'pc.paket_id')
            ->leftJoin('cabang as cp',          'cp.cabang_id',          '=', 'pc.cabang_id')
            ->leftJoin('ulasan as u',           'u.booking_id',          '=', 'b.booking_id')
            ->leftJoin('booking_reschedule as br', 'br.booking_id',      '=', 'b.booking_id')
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
                'p.bukti_pembayaran'
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
                DB::raw("COALESCE(
                    NULLIF(GROUP_CONCAT(DISTINCT l.nama_layanan ORDER BY l.nama_layanan SEPARATOR ', '), ''),
                    MAX(pak.nama_paket),
                    'Layanan'
                ) as layanan_nama"),
                DB::raw('COUNT(DISTINCT bd.booking_detail_id) as jumlah_layanan'),
                DB::raw('COALESCE(MAX(c.nama_cabang), MAX(cp.nama_cabang)) as nama_cabang'),
                DB::raw('COALESCE(MAX(c.alamat), MAX(cp.alamat)) as alamat'),
                DB::raw('IF(COUNT(DISTINCT u.ulasan_id) > 0, 1, 0) as sudah_ulasan'),
                DB::raw('IF(COUNT(DISTINCT br.reschedule_id) > 0, 1, 0) as is_rescheduled')
            );

        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }

        $bookings = $query
            ->orderByDesc('b.created_at')
            ->paginate(10);

        return view('user.booking.history', compact('bookings', 'user'));
    }

    // SHOW — Detail booking
    public function show($booking_id)
    {
        $user = Auth::user();

        $pelanggan = DB::table('pelanggan')
            ->where('user_id', $user->user_id)
            ->first();

        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->leftJoin('pegawai as pg', 'b.pegawai_id', '=', 'pg.pegawai_id')
            ->leftJoin('users as u_pg', 'pg.user_id', '=', 'u_pg.user_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->select('b.*', 'u_pg.nama as nama_pegawai')
            ->first();

        if (!$booking) {
            abort(404);
        }

        $isPaketBooking = DB::table('booking_detail')
            ->where('booking_id', $booking_id)
            ->whereNotNull('paket_cabang_id')
            ->exists();

        if ($isPaketBooking) {
            $paketCabangId = DB::table('booking_detail')
                ->where('booking_id', $booking_id)
                ->value('paket_cabang_id');

            $paketCabang = DB::table('paket_cabang')
                ->where('paket_cabang_id', $paketCabangId)
                ->first();

            if (!$paketCabang) {
                abort(404, 'Data paket cabang tidak ditemukan.');
            }

            $cabang = DB::table('cabang')
                ->where('cabang_id', $paketCabang->cabang_id)
                ->first();

            $layananList = DB::table('paket_detail as pd')
                ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
                ->where('pd.paket_id', $paketCabang->paket_id)
                ->select(
                    'l.layanan_id',
                    'l.nama_layanan',
                    'l.deskripsi',
                    'l.durasi',
                    'l.kategori_pelanggan',
                    'l.cover_foto',
                    DB::raw("{$paketCabang->harga_normal} as harga_normal"),
                    DB::raw("{$paketCabang->harga_promo} as harga_promo")
                )
                ->get();

            foreach ($layananList as $item) {
                $item->nama_cabang = $cabang->nama_cabang ?? null;
                $item->alamat      = $cabang->alamat ?? null;
            }

            $total = $paketCabang->harga_promo > 0
                ? $paketCabang->harga_promo
                : $paketCabang->harga_normal;
        } else {
            $layananList = DB::table('booking_detail as bd')
                ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
                ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
                ->leftJoin('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
                ->where('bd.booking_id', $booking_id)
                ->select(
                    'l.layanan_id',
                    'l.nama_layanan',
                    'l.deskripsi',
                    'l.durasi',
                    'l.kategori_pelanggan',
                    'l.cover_foto',
                    'lc.harga',
                    'lc.harga_promo',
                    'c.nama_cabang',
                    'c.alamat'
                )
                ->get();

            $total = $layananList->sum(function ($item) {
                return $item->harga_promo > 0 ? $item->harga_promo : $item->harga;
            });
        }

        foreach ($layananList as $item) {
            $item->cover_foto = !empty($item->cover_foto)
                ? asset('layanan/' . basename($item->cover_foto))
                : null;
        }

        $pembayaran = DB::table('pembayaran')
            ->where('booking_id', $booking_id)
            ->orderByDesc('pembayaran_id')
            ->first();

        return view('user.booking.detail', compact(
            'booking',
            'layananList',
            'pembayaran',
            'total',
            'user'
        ));
    }

    // SHOW RESCHEDULE — Form reschedule
    public function showReschedule($booking_id)
    {
        $user = Auth::user();

        $pelanggan = DB::table('pelanggan')
            ->where('user_id', $user->user_id)
            ->first();

        $sudahReschedule = DB::table('booking_reschedule')
            ->where('booking_id', $booking_id)
            ->exists();

        if ($sudahReschedule) {
            return back()->withErrors(['error' => 'Booking ini sudah pernah direschedule.']);
        }

        $booking = DB::table('booking as b')
            ->join('pelanggan as pl', 'b.pelanggan_id', '=', 'pl.pelanggan_id')
            ->leftJoin('pembayaran as p', 'p.booking_id', '=', 'b.booking_id')
            ->where('b.booking_id', $booking_id)
            ->where('pl.user_id', $user->user_id)
            ->select('b.*', 'p.status as payment_status')
            ->first();

        if (!$booking) {
            abort(404);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'Booking ini tidak dapat direschedule.']);
        }

        $cabangId = DB::table('booking_detail as bd')
            ->leftJoin('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->leftJoin('paket_cabang as pc',   'bd.paket_cabang_id',   '=', 'pc.paket_cabang_id')
            ->where('bd.booking_id', $booking_id)
            ->selectRaw('COALESCE(lc.cabang_id, pc.cabang_id) as cabang_id')
            ->value('cabang_id');

        $jadwalOperasional = DB::table('jadwal_operasional')
            ->where('cabang_id', $cabangId)
            ->get()
            ->keyBy('hari');

        $layananList = DB::table('booking_detail as bd')
            ->leftJoin('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->leftJoin('layanan as l',         'lc.layanan_id',         '=', 'l.layanan_id')
            ->leftJoin('paket_cabang as pc',   'bd.paket_cabang_id',    '=', 'pc.paket_cabang_id')
            ->leftJoin('paket_layanan as pl',  'pc.paket_id',           '=', 'pl.paket_id')
            ->where('bd.booking_id', $booking_id)
            ->select(
                DB::raw('COALESCE(l.nama_layanan, pl.nama_paket) as nama_layanan'),
                DB::raw('COALESCE(l.durasi, 0) as durasi'),
                'bd.harga_snapshot'
            )
            ->distinct()
            ->get();

        $total = $layananList->sum('harga_snapshot');

        return view('user.booking.reschedule', compact(
            'booking',
            'jadwalOperasional',
            'layananList',
            'total',
            'user'
        ));
    }

    // PROCESS RESCHEDULE — Simpan jadwal baru
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

        $pelanggan = DB::table('pelanggan')
            ->where('user_id', $user->user_id)
            ->first();

        $booking = DB::table('booking')
            ->where('booking_id', $booking_id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->where('status', 'pending')
            ->first();

        if (!$booking) {
            return back()->withErrors([
                'error' => 'Booking tidak ditemukan atau tidak dapat direschedule.',
            ]);
        }

        if (
            $booking->tanggal_booking == $request->new_tanggal &&
            $booking->jam_booking == $request->new_jam
        ) {
            return back()
                ->withErrors(['error' => 'Silakan pilih tanggal atau jam yang berbeda dari jadwal saat ini.'])
                ->withInput();
        }

        $conflict = DB::table('booking')
            ->where('tanggal_booking', $request->new_tanggal)
            ->where('jam_booking', $request->new_jam)
            ->where('status', '!=', 'cancelled')
            ->where('booking_id', '!=', $booking_id)
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['error' => 'Slot waktu tersebut sudah terisi. Silakan pilih waktu lain.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            DB::table('booking_reschedule')->insert([
                'booking_id'  => $booking_id,
                'old_tanggal' => $booking->tanggal_booking,
                'old_jam'     => $booking->jam_booking,
                'new_tanggal' => $request->new_tanggal,
                'new_jam'     => $request->new_jam,
                'reason'      => $request->reason,
                'created_by'  => $user->user_id,
            ]);

            DB::table('booking')
                ->where('booking_id', $booking_id)
                ->update([
                    'tanggal_booking' => $request->new_tanggal,
                    'jam_booking'     => $request->new_jam,
                    'status'          => 'pending',
                ]);

            DB::table('notifikasi')->insert([
                'user_id'      => $user->user_id,
                'pesan'        => "Booking #{$booking_id} berhasil direschedule ke " .
                    \Carbon\Carbon::parse($request->new_tanggal)->isoFormat('D MMM Y') .
                    ' pukul ' . substr($request->new_jam, 0, 5) . ' WIB',
                'tipe'         => 'booking',
                'status_baca'  => 'belum',
            ]);

            DB::table('audit_log')->insert([
                'user_id'     => $user->user_id,
                'action'      => 'RESCHEDULE',
                'table_name'  => 'booking',
                'record_id'   => $booking_id,
                'description' => "Booking direschedule dari {$booking->tanggal_booking} {$booking->jam_booking} ke {$request->new_tanggal} {$request->new_jam}",
            ]);

            DB::commit();

            return redirect()
                ->route('pelanggan.booking.show', $booking_id)
                ->with('success', 'Booking berhasil direschedule!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Gagal reschedule: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // HELPER — Cek ketersediaan slot
    private function isSlotAvailable($cabang_id, $tanggal, $jam)
    {
        $totalSpecialists = DB::table('pegawai')
            ->where('cabang_id', $cabang_id)
            ->where('status_kerja', 'aktif')
            ->count();

        if ($totalSpecialists === 0) {
            return false;
        }

        $bookedCount = DB::table('booking')
            ->where('tanggal_booking', $tanggal)
            ->where('jam_booking', $jam)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->count();

        return $bookedCount < $totalSpecialists;
    }

    // HELPER — Resolve paket_cabang berdasarkan paket_cabang_id (PK)
    private function resolvePaketCabang($value)
    {
        return DB::table('paket_cabang')
            ->where('paket_cabang_id', $value)
            ->where('status', 'tersedia')
            ->first();
    }
}