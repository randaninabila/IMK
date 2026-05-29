<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\JenisLayanan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PBookingController extends Controller
{
    public function index(Request $request)
    {
        $pegawaiId = auth()->user()->pegawai->pegawai_id;
        $today     = now()->toDateString();

        // Ongoing: status 'ongoing' = sedang berjalan (setelah tekan mulai servis)
        $ongoing = Booking::with([
                'details.layananCabang.layanan.jenisLayanan',
                'pelanggan.user',
            ])
            ->where('pegawai_id', $pegawaiId)
            ->whereDate('tanggal_booking', $today)
            ->where('status', 'ongoing')
            ->orderBy('jam_booking')
            ->first();

        // Upcoming: confirmed = telah ditugaskan, masuk jadwal pegawai, belum mulai
        $upcoming = Booking::with([
            'details.layananCabang.layanan.jenisLayanan',
            'pelanggan.user',
        ])
        ->where('pegawai_id', $pegawaiId)
        ->where('status', 'confirmed')
        ->whereDate('tanggal_booking', '>=', $today)  // ← Hari ini dan setelahnya
        ->orderBy('tanggal_booking', 'asc')
        ->orderBy('jam_booking', 'asc')
        ->limit(3)  // ← Hanya 3 terdekat
        ->get();

        return view('pegawai.booking.book1', compact('ongoing', 'upcoming'));
    }

     public function history(Request $request)
{
    $pegawaiId = auth()->user()->pegawai->pegawai_id;

    $search          = $request->get('search');
    $filter          = $request->get('filter', 'semua');
    $jenisLayananId  = $request->get('jenis_layanan');
    $tanggal         = $request->get('tanggal'); // ← Single parameter tanggal

    /*
    |--------------------------------------------------------------------------
    | QUERY HISTORY
    |--------------------------------------------------------------------------
    */

    $query = Booking::with([
            'details.layananCabang.layanan.jenisLayanan',
            'pelanggan.user',
        ])
        ->where('pegawai_id', $pegawaiId)
        ->whereIn('status', ['completed', 'cancelled']);

    /*
    |--------------------------------------------------------------------------
    | FILTER WAKTU (tab)
    |--------------------------------------------------------------------------
    */

    if ($filter == 'hariini') {
        $query->whereDate('tanggal_booking', now());
    } elseif ($filter == 'bulanan') {
        $query->whereMonth('tanggal_booking', now()->month)
              ->whereYear('tanggal_booking', now()->year);
    } elseif ($filter == 'tahunan') {
        $query->whereYear('tanggal_booking', now()->year);
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL CUSTOM (single date picker)
    |--------------------------------------------------------------------------
    */

    // Jika ada tanggal spesifik, filter exact match
    // Prioritas lebih tinggi dari filter tab (karena lebih spesifik)
    if ($tanggal) {
        $query->whereDate('tanggal_booking', $tanggal);
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER JENIS LAYANAN
    |--------------------------------------------------------------------------
    */

    if ($jenisLayananId) {
        $query->whereHas('details.layananCabang.layanan.jenisLayanan', function ($q) use ($jenisLayananId) {
            $q->where('jenis_layanan_id', $jenisLayananId);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH (nama klien atau nama layanan)
    |--------------------------------------------------------------------------
    */

    if ($search) {
        $query->where(function ($q) use ($search) {
            // Search nama klien
            $q->whereHas('pelanggan.user', function ($q2) use ($search) {
                $q2->where('nama', 'like', "%{$search}%");
            });

            // Search nama layanan
            $q->orWhereHas('details', function ($q2) use ($search) {
                $q2->whereHas('layananCabang.layanan', function ($q3) use ($search) {
                    $q3->where('nama_layanan', 'like', "%{$search}%");
                });
            });

            // Search booking_id — support format #00021 atau 21
            $cleanId = ltrim(preg_replace('/[^0-9]/', '', $search), '0') ?: '0';
            $q->orWhere('booking_id', $cleanId);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

    $bookings = $query
        ->orderBy('tanggal_booking', 'desc')
        ->orderBy('jam_booking', 'desc')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | GROUP BY TANGGAL
    |--------------------------------------------------------------------------
    */

    $history = $bookings->groupBy(function ($b) {
        return Carbon::parse($b->tanggal_booking)
            ->translatedFormat('d F Y');
    });

    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    $totalSesi = $bookings->count();

    $totalDurasi = $bookings
        ->where('status', 'completed')
        ->sum(function ($b) {
            return $b->details->sum(function ($d) {
                return optional($d->layananCabang->layanan)->durasi ?? 0;
            });
        });

    $totalKlien = $bookings
        ->pluck('pelanggan_id')
        ->unique()
        ->count();

    // $totalPendapatan = $bookings
    //     ->where('status', 'completed')
    //     ->sum(function ($b) {
    //         return $b->details->sum(function ($d) {
    //             return optional($d->layananCabang)->harga ?? 0;
    //         });
    //     });

    /*
    |--------------------------------------------------------------------------
    | JENIS LAYANAN untuk dropdown filter
    |--------------------------------------------------------------------------
    */

    $jenisLayananList = JenisLayanan::orderBy('nama_jenis')->get();

    return view('pegawai.history.his1', compact(
        'history',
        'totalSesi',
        'totalDurasi',
        'totalKlien',
        'filter',
        'jenisLayananList',
        'jenisLayananId',
        'tanggal',  // ← Updated: pass single $tanggal
    ));
}

    public function updateStatus(Request $request, $booking_id)
    {
        $request->validate([
            'status' => ['required', 'in:confirmed,ongoing,completed,cancelled,pending'],
        ]);

        $pegawaiId  = auth()->user()->pegawai->pegawai_id;
        $newStatus  = $request->status;

        $booking = Booking::where('booking_id', $booking_id)
            ->where('pegawai_id', $pegawaiId)
            ->findOrFail($booking_id);

        // Validasi transisi status yang diizinkan
        // confirmed → ongoing (mulai servis), pending (lepas dari pegawai ini, bisa ditugaskan ulang)
        // ongoing   → completed (selesai)
        $allowed = [
            'confirmed' => ['ongoing', 'pending'],
            'ongoing'   => ['completed'],
        ];

        if (!isset($allowed[$booking->status]) || !in_array($newStatus, $allowed[$booking->status])) {
            return back()->withErrors(['status' => 'Perubahan status tidak diizinkan.']);
        }

        // Kalau mau mulai servis (confirmed → ongoing), cek jam booking belum terlewat
        if ($booking->status === 'confirmed' && $newStatus === 'ongoing') {
            $jamBooking = \Carbon\Carbon::parse(
                $booking->tanggal_booking . ' ' . $booking->jam_booking
            );
            if (\Carbon\Carbon::now()->lt($jamBooking)) {
                return back()->withErrors(['status' => 'Belum waktunya memulai layanan ini.']);
            }
        }

        $booking->status = $newStatus;

        // Kalau dikembalikan ke pending, lepas dari pegawai ini supaya bisa ditugaskan ulang
        if ($newStatus === 'pending') {
            $booking->pegawai_id = null;
        }

        $booking->save();

        return back();
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}