<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PBookingController extends Controller
{
    public function index(Request $request)
{
    $pegawaiId = auth()->user()->pegawai->pegawai_id;
    $today     = now()->toDateString();

    // Ongoing: confirmed = sedang dijadwalkan / berjalan hari ini
    $ongoing = Booking::where('pegawai_id', $pegawaiId)
    ->whereDate('tanggal_booking', $today)
    ->where('status', 'confirmed')
    ->orderBy('jam_booking')
    ->first();

    // Upcoming: pending = menunggu konfirmasi / belum mulai
    $upcoming = Booking::with([
        'details.layananCabang.layanan.jenisLayanan',
        'pelanggan.user',
    ])
    ->where('pegawai_id', $pegawaiId)
    ->whereDate('tanggal_booking', '>=', $today)
    ->whereIn('status', ['pending', 'confirmed']) // optional kalau mau lebih realistis
    ->when($ongoing, function ($q) use ($ongoing) {
        $q->where('booking_id', '!=', $ongoing->booking_id);
    })
    ->orderBy('tanggal_booking') // 🔥 penting
    ->orderBy('jam_booking')
    ->get();
    return view('pegawai.booking.book1', compact('ongoing', 'upcoming'));
}

public function history(Request $request)
{
    $pegawaiId = auth()->user()->pegawai->pegawai_id;

    $search = $request->get('search');
    $filter = $request->get('filter', 'semua');

    /*
    |--------------------------------------------------------------------------
    | QUERY HISTORY
    |--------------------------------------------------------------------------
    */

    $query = Booking::with([
            'details.layananCabang.layanan',
            'pelanggan.user',
        ])
        ->where('pegawai_id', $pegawaiId)
        ->whereIn('status', ['completed', 'cancelled']);

    /*
    |--------------------------------------------------------------------------
    | FILTER
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
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search) {

        $query->where(function ($q) use ($search) {

            $q->whereHas('pelanggan.user', function ($q2) use ($search) {

                $q2->where('name', 'like', "%{$search}%");

            })

            ->orWhereHas('details.layananCabang.layanan', function ($q2) use ($search) {

                $q2->where('nama_layanan', 'like', "%{$search}%");

            });

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

    $totalPendapatan = $bookings
        ->where('status', 'completed')
        ->sum(function ($b) {

            return $b->details->sum(function ($d) {

                return optional($d->layananCabang)->harga ?? 0;
            });
        });

    return view('pegawai.history.his1', compact(
        'history',
        'totalSesi',
        'totalDurasi',
        'totalKlien',
        'totalPendapatan',
        'filter'
    ));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
