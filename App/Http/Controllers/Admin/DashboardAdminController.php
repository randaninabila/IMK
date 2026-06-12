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
        $selectedDate = $this->getSelectedDate($request);
        $dateOptions = $this->getDateOptions();

        $summary = $this->getSummary($selectedCabangId, $selectedDate);
        $latestBookings = $this->getLatestBookings($selectedCabangId, $selectedDate);
        $todaySchedules = $this->getTodaySchedules($selectedCabangId, $selectedDate);
        $allSchedules = $this->getAllSchedules($selectedCabangId);

        return view('admin.dashboard.dashboardadmin', compact(
            'branches',
            'selectedBranch',
            'selectedCabangId',
            'selectedDate',
            'dateOptions',
            'summary',
            'latestBookings',
            'todaySchedules',
            'allSchedules'
        ));
    }

    private function getBranches()
    {
        $branches = DB::table('cabang')
            ->select(
                'cabang_id',
                DB::raw('MIN(nama_cabang) as nama_cabang'),
                DB::raw('MIN(alamat) as alamat'),
                DB::raw('MIN(status) as status')
            )
            ->whereIn('cabang_id', [1, 2])
            ->groupBy('cabang_id')
            ->orderBy('cabang_id', 'asc')
            ->get()
            ->map(function ($branch) {
                $namaCabang = strtolower($branch->nama_cabang ?? '');

                $branch->label = ((int) $branch->cabang_id === 2 || str_contains($namaCabang, 'percut'))
                    ? 'Cabang Percut'
                    : 'Cabang Tembung';

                return $branch;
            });

        if ($branches->isEmpty()) {
            $branches = collect([
                (object) [
                    'cabang_id' => 1,
                    'nama_cabang' => 'Salon Muslimah Dina - Tembung',
                    'alamat' => null,
                    'status' => 'BUKA',
                    'label' => 'Cabang Tembung',
                ],
                (object) [
                    'cabang_id' => 2,
                    'nama_cabang' => 'Salon Muslimah Dina - Percut',
                    'alamat' => null,
                    'status' => 'BUKA',
                    'label' => 'Cabang Percut',
                ],
            ]);
        }

        return $branches;
    }

    private function getSelectedDate(Request $request)
    {
        $tanggal = $request->query('tanggal');

        if (!$tanggal || $tanggal === 'semua' || $tanggal === 'all') {
            return null;
        }

        try {
            return Carbon::parse($tanggal)->toDateString();
        } catch (\Exception $exception) {
            return null;
        }
    }

    private function getDateOptions()
    {
        return collect(range(0, 6))->map(function ($day) {
            $date = now()->addDays($day);

            return (object) [
                'date' => $date->toDateString(),
                'label' => $date->locale('id')->translatedFormat('d F Y'),
                'day' => $date->locale('id')->translatedFormat('l'),
            ];
        });
    }

    private function applyDateFilter($query, ?string $selectedDate)
    {
        if ($selectedDate) {
            $query->whereDate('b.tanggal_booking', $selectedDate);
        }

        return $query;
    }

    private function bookingBaseQuery(int $selectedCabangId)
    {
        return DB::table('booking as b')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')

            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')

            ->leftJoin('paket_cabang as pc', 'pc.paket_cabang_id', '=', 'bd.paket_cabang_id')
            ->leftJoin('paket_layanan as pkt', 'pkt.paket_id', '=', 'pc.paket_id')

            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')

            ->where(function ($query) use ($selectedCabangId) {
                $query->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            })
            ->select(
                'b.booking_id',
                'b.status',
                'b.tanggal_booking'
            )
            ->distinct();
    }

    private function getSummary(int $selectedCabangId, ?string $selectedDate)
    {
        $totalBookingQuery = $this->applyDateFilter(
            $this->bookingBaseQuery($selectedCabangId),
            $selectedDate
        );

        $completedBookingQuery = $this->applyDateFilter(
            $this->bookingBaseQuery($selectedCabangId)
                ->whereIn('b.status', ['completed', 'selesai']),
            $selectedDate
        );

        $runningBookingQuery = $this->applyDateFilter(
            $this->bookingBaseQuery($selectedCabangId)
                ->whereIn('b.status', ['confirmed', 'assigned', 'in_progress', 'proses']),
            $selectedDate
        );

        $pendingPaymentQuery = $this->paymentBaseQuery($selectedCabangId, $selectedDate)
            ->where(function ($query) {
                $query->where('py.status', 'pending')
                    ->orWhere('b.status', 'pending');
            });

        $verifiedPaymentQuery = $this->paymentBaseQuery($selectedCabangId, $selectedDate)
            ->where(function ($query) {
                $query->where('py.status', 'verified')
                    ->orWhereIn('b.status', ['completed', 'selesai']);
            });

        $totalBooking = DB::query()
            ->fromSub($totalBookingQuery, 'booking_total')
            ->count();

        $completedBooking = DB::query()
            ->fromSub($completedBookingQuery, 'booking_completed')
            ->count();

        $runningBooking = DB::query()
            ->fromSub($runningBookingQuery, 'booking_running')
            ->count();

        $pendingPayments = DB::query()
            ->fromSub($pendingPaymentQuery, 'pending_payment')
            ->get();

        $verifiedPayments = DB::query()
            ->fromSub($verifiedPaymentQuery, 'verified_payment')
            ->get();

        return [
            'total_booking' => $totalBooking,
            'completed_booking' => $completedBooking,
            'running_booking' => $runningBooking,

            'pending_payment' => $pendingPayments->count(),
            'pending_qris' => $pendingPayments->where('metode_pembayaran', 'qris')->count(),
            'pending_cash' => $pendingPayments->where('metode_pembayaran', 'cash')->count(),

            'total_income' => $verifiedPayments->sum('jumlah'),
            'cash_income' => $verifiedPayments->where('metode_pembayaran', 'cash')->sum('jumlah'),
            'qris_income' => $verifiedPayments->where('metode_pembayaran', 'qris')->sum('jumlah'),
        ];
    }

    private function paymentBaseQuery(int $selectedCabangId, ?string $selectedDate)
    {
        $query = DB::table('booking as b')
            ->leftJoin('pembayaran as py', 'py.booking_id', '=', 'b.booking_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')

            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')

            ->leftJoin('paket_cabang as pc', 'pc.paket_cabang_id', '=', 'bd.paket_cabang_id')
            ->leftJoin('paket_layanan as pkt', 'pkt.paket_id', '=', 'pc.paket_id')

            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')

            ->where(function ($query) use ($selectedCabangId) {
                $query->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            });

        if ($selectedDate) {
            $query->whereDate('b.tanggal_booking', $selectedDate);
        }

        return $query
            ->select(
                DB::raw('COALESCE(py.pembayaran_id, b.booking_id) as pembayaran_id'),
                DB::raw('COALESCE(py.metode_pembayaran, "cash") as metode_pembayaran'),
                DB::raw('COALESCE(py.status, b.status) as payment_status'),
                DB::raw('COALESCE(py.jumlah, MAX(COALESCE(lc.harga_promo, lc.harga, pc.harga_promo, pc.harga_normal, bd.harga_snapshot, 0))) as jumlah'),
                'b.status'
            )
            ->groupBy(
                'py.pembayaran_id',
                'b.booking_id',
                'py.metode_pembayaran',
                'py.status',
                'py.jumlah',
                'b.status'
            );
    }

    private function getLatestBookings(int $selectedCabangId, ?string $selectedDate)
    {
        $query = DB::table('booking as b')
            ->leftJoin('pelanggan as pl', 'pl.pelanggan_id', '=', 'b.pelanggan_id')
            ->leftJoin('users as pelanggan_user', 'pelanggan_user.user_id', '=', 'pl.user_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')

            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')

            ->leftJoin('paket_cabang as pc', 'pc.paket_cabang_id', '=', 'bd.paket_cabang_id')
            ->leftJoin('paket_layanan as pkt', 'pkt.paket_id', '=', 'pc.paket_id')

            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')
            ->leftJoin('users as pegawai_user', 'pegawai_user.user_id', '=', 'pg.user_id')

            ->where(function ($query) use ($selectedCabangId) {
                $query->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            });

        if ($selectedDate) {
            $query->whereDate('b.tanggal_booking', $selectedDate);
        }

        return $query
            ->select(
                'b.booking_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                'pelanggan_user.nama as pelanggan_nama',
                DB::raw('GROUP_CONCAT(DISTINCT COALESCE(l.nama_layanan, pkt.nama_paket) ORDER BY COALESCE(l.nama_layanan, pkt.nama_paket) SEPARATOR ", ") as layanan_nama'),
                DB::raw('COALESCE(MAX(pegawai_user.nama), "Belum assign") as pegawai_nama')
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

    private function getTodaySchedules(int $selectedCabangId, ?string $selectedDate)
    {
        if ($selectedDate) {
            $selectedSchedules = $this->scheduleBaseQuery($selectedCabangId)
                ->whereDate('jp.tanggal', $selectedDate)
                ->orderBy('jp.jam_mulai', 'asc')
                ->limit(8)
                ->get();

            if ($selectedSchedules->isNotEmpty()) {
                return $selectedSchedules;
            }
        }

        $upcomingSchedules = $this->scheduleBaseQuery($selectedCabangId)
            ->whereDate('jp.tanggal', '>=', now()->toDateString())
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