<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PBookingController extends Controller
{
    /**
     * Tampilkan halaman booking pegawai.
     *  - ongoing  : booking hari ini yang jamnya sudah lewat, status pending/confirmed
     *  - upcoming : booking yang belum terjadi, status pending/confirmed
     */
    public function index()
    {
        $pegawaiId = auth()->user()->pegawai->pegawai_id;
        $today     = now()->toDateString();
        $now       = now()->format('H:i:s');

        // Ongoing: hari ini, jam sudah lewat/sedang berjalan, belum selesai
        $ongoingBooking = Booking::with([
                'pelanggan.user',
                'details.layananCabang.layanan.jenisLayanan',
            ])
            ->where('pegawai_id', $pegawaiId)
            ->whereDate('tanggal_booking', $today)
            ->whereTime('jam_booking', '<=', $now)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('jam_booking', 'desc')
            ->first();

        // Upcoming: jam lebih besar dari sekarang hari ini, atau tanggal setelah hari ini
        $upcomingBookings = Booking::with([
                'pelanggan.user',
                'details.layananCabang.layanan.jenisLayanan',
            ])
            ->where('pegawai_id', $pegawaiId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($today, $now) {
                $q->whereDate('tanggal_booking', '>', $today)
                  ->orWhere(function ($q2) use ($today, $now) {
                      $q2->whereDate('tanggal_booking', $today)
                         ->whereTime('jam_booking', '>', $now);
                  });
            })
            ->orderBy('tanggal_booking')
            ->orderBy('jam_booking')
            ->get();

        return view('pegawai.booking.book1', compact('ongoingBooking', 'upcomingBookings'));
    }

    /**
     * Pegawai mulai layanan → status: confirmed
     */
    public function startService(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Layanan dimulai.');
    }

    /**
     * Pegawai selesaikan layanan → status: completed
     */
    public function markDone(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->update(['status' => 'completed']);
        return back()->with('success', 'Booking ditandai selesai.');
    }

    /**
     * Pegawai batalkan booking → status: cancelled
     */
    public function cancel(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking dibatalkan.');
    }

    /**
     * Riwayat booking yang sudah selesai / dibatalkan.
     */
    public function history(Request $request)
    {
        $pegawaiId = auth()->user()->pegawai->pegawai_id;
        $filter    = $request->get('filter', 'semua');

        $query = Booking::with([
                'pelanggan.user',
                'details.layananCabang.layanan',
            ])
            ->where('pegawai_id', $pegawaiId)
            ->whereIn('status', ['completed', 'cancelled']);

        // Filter waktu
        if ($filter === 'hariini') {
            $query->whereDate('tanggal_booking', now()->toDateString());
        } elseif ($filter === 'bulanan') {
            $query->whereMonth('tanggal_booking', now()->month)
                  ->whereYear('tanggal_booking', now()->year);
        } elseif ($filter === 'tahunan') {
            $query->whereYear('tanggal_booking', now()->year);
        }

        $riwayat = $query->orderBy('tanggal_booking', 'desc')
                         ->orderBy('jam_booking', 'desc')
                         ->get();

        // Ringkasan (untuk filter semua & hariini)
        $ringkasan = [
            'total_layanan' => $riwayat->where('status', 'completed')->count(),
            'total_durasi'  => $riwayat->where('status', 'completed')
                                ->sum(fn($b) => $b->details->sum(
                                    fn($d) => $d->layananCabang?->layanan?->durasi ?? 0
                                )),
            'klien_dilayani' => $riwayat->where('status', 'completed')
                                ->pluck('pelanggan_id')->unique()->count(),
        ];

        return view('pegawai.his1', compact('riwayat', 'ringkasan', 'filter'));
    }

    // ── PRIVATE ──────────────────────────────────────────────────────────────

    private function authorizeBooking(Booking $booking): void
    {
        $pegawaiId = auth()->user()->pegawai->pegawai_id;
        abort_if($booking->pegawai_id !== $pegawaiId, 403, 'Akses ditolak.');
    }
}