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

        // in_progress: status 'in_progress' = sedang berjalan (setelah tekan mulai servis)
        $in_progress = Booking::with([
                'details.layananCabang.layanan.jenisLayanan',
                'pelanggan.user', 'details.paketCabang.paketLayanan', 'details.paketCabang.details.layanan',
            ])
            ->where('pegawai_id', $pegawaiId)
            ->whereDate('tanggal_booking', $today)
            ->where('status', 'in_progress')
            ->orderBy('jam_booking')
            ->first();

        // Upcoming: confirmed = telah ditugaskan, masuk jadwal pegawai, belum mulai
        $upcoming = Booking::with([
            'details.layananCabang.layanan.jenisLayanan',
            'pelanggan.user', 'details.paketCabang.paketLayanan',
        ])
        ->where('pegawai_id', $pegawaiId)
        ->where('status', 'confirmed')
        ->whereDate('tanggal_booking', '>=', $today)  // ← Hari ini dan setelahnya
        ->orderBy('tanggal_booking', 'asc')
        ->orderBy('jam_booking', 'asc')
        ->limit(3)  // ← Hanya 3 terdekat
        ->get();

        return view('pegawai.booking.book1', compact('in_progress', 'upcoming'));
    }

     public function history(Request $request)
{
    $pegawaiId = auth()->user()->pegawai->pegawai_id;

    $search          = $request->get('search');
    $filter          = $request->get('filter', 'semua');
    $jenisLayananId  = $request->get('jenis_layanan');
    $tanggal         = $request->get('tanggal'); // ← Single parameter tanggal
    $bulan          = $request->get('bulan');   // ← TAMBAH
$tahun          = $request->get('tahun');   // ← TAMBAH

    /*
    |--------------------------------------------------------------------------
    | QUERY HISTORY
    |--------------------------------------------------------------------------
    */

    $query = Booking::with([
    'details.layananCabang.layanan.jenisLayanan',
    'details.paketCabang.paketLayanan',
    'details.paketCabang.details.layanan', // ✅ untuk hitung durasi paket
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
    // Kalau ada pilihan bulan+tahun spesifik, pakai itu
    // Kalau tidak, default bulan ini
    $filterBulan = $bulan ?: now()->month;
    $filterTahun = $tahun ?: now()->year;
    $query->whereMonth('tanggal_booking', $filterBulan)
          ->whereYear('tanggal_booking', $filterTahun);

} elseif ($filter == 'tahunan') {
    // Kalau ada pilihan tahun spesifik, pakai itu
    // Kalau tidak, default tahun ini
    $filterTahun = $tahun ?: now()->year;
    $query->whereYear('tanggal_booking', $filterTahun);
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
    $query->where(function ($q) use ($jenisLayananId) {
        // Filter layanan single
        $q->whereHas('details.layananCabang.layanan.jenisLayanan', function ($q2) use ($jenisLayananId) {
            $q2->where('jenis_layanan_id', $jenisLayananId);
        });

        // ✅ Filter paket — cari lewat layanan di dalam paket
        $q->orWhereHas('details.paketCabang.details.layanan.jenisLayanan', function ($q2) use ($jenisLayananId) {
            $q2->where('jenis_layanan_id', $jenisLayananId);
        });
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

        // Search nama layanan (single)
        $q->orWhereHas('details.layananCabang.layanan', function ($q2) use ($search) {
            $q2->where('nama_layanan', 'like', "%{$search}%");
        });

        // ✅ Search nama paket
        $q->orWhereHas('details.paketCabang.paketLayanan', function ($q2) use ($search) {
            $q2->where('nama_paket', 'like', "%{$search}%");
        });

        // Search booking_id
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

    $totalDurasi = $bookings
    ->where('status', 'completed')
    ->sum(function ($b) {
        return $b->details->sum(function ($d) {
            if ($d->layanan_cabang_id) {
                return optional($d->layananCabang->layanan)->durasi ?? 0;
            } else {
                return $d->paketCabang?->details->sum(fn($pd) => $pd->layanan?->durasi ?? 0) ?? 0;
            }
        });
    });

    $totalKlien = $bookings
        ->pluck('pelanggan_id')
        ->unique()
        ->count();

    $totalSesi = $bookings->where('status', 'completed')->count();

    $totalSesis = $bookings
    ->where('status', 'completed')
    ->filter(function ($b) {
        return $b->details->contains(fn($d) => !is_null($d->layanan_cabang_id));
    })
    ->count();

$totalPaket = $bookings
    ->where('status', 'completed')
    ->filter(function ($b) {
        return $b->details->contains(fn($d) => !is_null($d->paket_cabang_id));
    })
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
        'totalPaket',
        'totalSesis',
        'filter',
        'jenisLayananList',
        'jenisLayananId',
        'tanggal',  // ← Updated: pass single $tanggal
        'bulan',    // ← TAMBAH
    'tahun', 
    ));
}

    public function updateStatus(Request $request, $booking_id)
    {
        $request->validate([
            'status' => ['required', 'in:confirmed,in_progress,completed,cancelled,pending'],
        ]);

        $pegawaiId  = auth()->user()->pegawai->pegawai_id;
        $newStatus  = $request->status;

        $booking = Booking::where('booking_id', $booking_id)
            ->where('pegawai_id', $pegawaiId)
            ->findOrFail($booking_id);

        // Validasi transisi status yang diizinkan
        // confirmed → in_progress (mulai servis), pending (lepas dari pegawai ini, bisa ditugaskan ulang)
        // in_progress   → completed (selesai)
        $allowed = [
            'confirmed' => ['in_progress', 'pending'],
            'in_progress'   => ['completed'],
        ];

        if (!isset($allowed[$booking->status]) || !in_array($newStatus, $allowed[$booking->status])) {
            return back()->withErrors(['status' => 'Perubahan status tidak diizinkan.']);
        }

        // Kalau mau mulai servis (confirmed → in_progress), cek jam booking belum terlewat
        if ($booking->status === 'confirmed' && $newStatus === 'in_progress') {
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