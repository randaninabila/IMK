<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $branches = $this->getBranches();

        $selectedCabangId = (int) $request->query('cabang_id', $branches->first()->cabang_id ?? 1);

        if (!$branches->contains('cabang_id', $selectedCabangId)) {
            $selectedCabangId = (int) ($branches->first()->cabang_id ?? 1);
        }

        $selectedBranch = $branches->firstWhere('cabang_id', $selectedCabangId);
        $today = Carbon::now()->toDateString();

        $summary = $this->getSummary($selectedCabangId, $today);
        $latestBookings = $this->getLatestBookings($selectedCabangId);
        $todaySchedules = $this->getTodaySchedules($selectedCabangId, $today);
        $allSchedules = $this->getAllSchedules($selectedCabangId);

        return view('admin.dashboard.dashboardadmin', compact(
            'branches',
            'selectedBranch',
            'selectedCabangId',
            'summary',
            'latestBookings',
            'todaySchedules',
            'allSchedules'
        ));
    }

    private function getBranches()
    {
        return DB::table('cabang')
            ->select('cabang_id', 'nama_cabang', 'alamat', 'status')
            ->orderBy('cabang_id', 'asc')
            ->get()
            ->map(function ($branch) {
                $branch->label = $branch->nama_cabang;
                return $branch;
            });
    }

    private function bookingBaseQuery(int $selectedCabangId)
    {
        return DB::table('booking as b')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')
            ->where(function ($query) use ($selectedCabangId) {
                $query->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            })
            ->select(
                'b.booking_id',
                'b.status',
                'b.tanggal_booking'
            )
            ->distinct();
    }

    private function getSummary(int $selectedCabangId, string $today)
    {
        $totalBookingToday = DB::query()
            ->fromSub(
                $this->bookingBaseQuery($selectedCabangId)
                    ->whereDate('b.tanggal_booking', $today),
                'booking_today'
            )
            ->count();

        $completedBookingToday = DB::query()
            ->fromSub(
                $this->bookingBaseQuery($selectedCabangId)
                    ->whereDate('b.tanggal_booking', $today)
                    ->where('b.status', 'selesai'),
                'booking_completed'
            )
            ->count();

        $runningBookingToday = DB::query()
            ->fromSub(
                $this->bookingBaseQuery($selectedCabangId)
                    ->whereDate('b.tanggal_booking', $today)
                    ->whereIn('b.status', ['confirmed', 'assigned', 'proses']),
                'booking_running'
            )
            ->count();

        $pendingPayments = DB::query()
            ->fromSub(
                $this->paymentBaseQuery($selectedCabangId, $today)
                    ->where('py.status', 'pending'),
                'pending_payment'
            )
            ->get();

        $verifiedPayments = DB::query()
            ->fromSub(
                $this->paymentBaseQuery($selectedCabangId, $today)
                    ->where('py.status', 'verified'),
                'verified_payment'
            )
            ->get();

        return [
            'total_booking' => $totalBookingToday,
            'completed_booking' => $completedBookingToday,
            'running_booking' => $runningBookingToday,

            'pending_payment' => $pendingPayments->count(),
            'pending_qris' => $pendingPayments->where('metode_pembayaran', 'qris')->count(),
            'pending_cash' => $pendingPayments->where('metode_pembayaran', 'cash')->count(),

            'total_income' => $verifiedPayments->sum('jumlah'),
            'cash_income' => $verifiedPayments->where('metode_pembayaran', 'cash')->sum('jumlah'),
            'qris_income' => $verifiedPayments->where('metode_pembayaran', 'qris')->sum('jumlah'),
        ];
    }

    private function paymentBaseQuery(int $selectedCabangId, string $today)
    {
        return DB::table('pembayaran as py')
            ->join('booking as b', 'b.booking_id', '=', 'py.booking_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')
            ->whereDate('b.tanggal_booking', $today)
            ->where(function ($query) use ($selectedCabangId) {
                $query->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            })
            ->select(
                'py.pembayaran_id',
                'py.metode_pembayaran',
                'py.jumlah'
            )
            ->distinct();
    }

    private function getLatestBookings(int $selectedCabangId)
    {
        return DB::table('booking as b')
            ->leftJoin('pelanggan as pl', 'pl.pelanggan_id', '=', 'b.pelanggan_id')
            ->leftJoin('users as pelanggan_user', 'pelanggan_user.user_id', '=', 'pl.user_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')
            ->leftJoin('users as pegawai_user', 'pegawai_user.user_id', '=', 'pg.user_id')
            ->where(function ($query) use ($selectedCabangId) {
                $query->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            })
            ->select(
                'b.booking_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                'pelanggan_user.nama as pelanggan_nama',
                DB::raw('GROUP_CONCAT(DISTINCT l.nama_layanan ORDER BY l.nama_layanan SEPARATOR ", ") as layanan_nama'),
                DB::raw('COALESCE(MAX(pegawai_user.nama), "-") as pegawai_nama')
            )
            ->groupBy(
                'b.booking_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                'pelanggan_user.nama'
            )
            ->orderByDesc('b.tanggal_booking')
            ->orderByDesc('b.jam_booking')
            ->limit(5)
            ->get();
    }

    private function scheduleBaseQuery(int $selectedCabangId)
    {
        return DB::table('jadwal_pegawai as jp')
            ->leftJoin('pegawai as p', 'p.pegawai_id', '=', 'jp.pegawai_id')
            ->leftJoin('users as u', 'u.user_id', '=', 'p.user_id')
            ->leftJoin('cabang as c', 'c.cabang_id', '=', 'p.cabang_id')
            ->where('p.cabang_id', $selectedCabangId)
            ->where('u.role', 'pegawai')
            ->select(
                'jp.jadwal_pegawai_id',
                'jp.tanggal',
                'jp.jam_mulai',
                'jp.jam_selesai',
                'jp.status_ketersediaan',
                'p.pegawai_id',
                'u.nama as pegawai_nama',
                DB::raw('MIN(c.nama_cabang) as nama_cabang')
            )
            ->groupBy(
                'jp.jadwal_pegawai_id',
                'jp.tanggal',
                'jp.jam_mulai',
                'jp.jam_selesai',
                'jp.status_ketersediaan',
                'p.pegawai_id',
                'u.nama'
            );
    }

    private function getTodaySchedules(int $selectedCabangId, string $today)
    {
        $todaySchedules = $this->scheduleBaseQuery($selectedCabangId)
            ->whereDate('jp.tanggal', $today)
            ->orderBy('jp.jam_mulai', 'asc')
            ->limit(8)
            ->get();

        if ($todaySchedules->isNotEmpty()) {
            return $todaySchedules;
        }

        $upcomingSchedules = $this->scheduleBaseQuery($selectedCabangId)
            ->whereDate('jp.tanggal', '>=', $today)
            ->orderBy('jp.tanggal', 'asc')
            ->orderBy('jp.jam_mulai', 'asc')
            ->limit(8)
            ->get();

        if ($upcomingSchedules->isNotEmpty()) {
            return $upcomingSchedules;
        }

        return $this->scheduleBaseQuery($selectedCabangId)
            ->orderByDesc('jp.tanggal')
            ->orderBy('jp.jam_mulai', 'asc')
            ->limit(8)
            ->get();
    }

    private function getAllSchedules(int $selectedCabangId)
    {
        return $this->scheduleBaseQuery($selectedCabangId)
            ->orderBy('jp.tanggal', 'asc')
            ->orderBy('jp.jam_mulai', 'asc')
            ->get();
    }
}