<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $selectedCabang = $request->get('cabang', 'all');
        $selectedMonth  = $request->get('bulan', Carbon::now()->format('Y-m'));

        $cabangs = DB::table('cabang')->where('status', 'BUKA')->get();

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push([
                'value' => $month->format('Y-m'),
                'label' => $month->locale('id')->translatedFormat('F Y'),
            ]);
        }

        $viewType = $request->get('view', 'daily');

        $stats             = $this->getStats($selectedCabang, $selectedMonth);
        $customerGrowth    = $this->getCustomerGrowth($selectedCabang, $selectedMonth, $viewType);
        $reservationHabits = $this->getReservationHabits($selectedCabang, $selectedMonth);

        return view('owner.customers', compact(
            'cabangs', 'months', 'selectedCabang', 'selectedMonth',
            'viewType', 'stats', 'customerGrowth', 'reservationHabits'
        ));
    }

    // =========================
    // BASE QUERY
    // =========================

    private function completedBookingBase()
    {
        return DB::table('booking as b')
            ->join('pelanggan as p', 'b.pelanggan_id', '=', 'p.pelanggan_id')
            ->join('users as u', 'p.user_id', '=', 'u.user_id')
            ->where('b.status', 'completed');
    }

    private function cabangSubquery(): string
    {
        return '(
            SELECT COALESCE(lc_sub.cabang_id, pc_sub.cabang_id)
            FROM booking_detail bd_sub
            LEFT JOIN layanan_cabang lc_sub ON lc_sub.layanan_cabang_id = bd_sub.layanan_cabang_id
            LEFT JOIN paket_cabang   pc_sub ON pc_sub.paket_cabang_id   = bd_sub.paket_cabang_id
            WHERE bd_sub.booking_id = b.booking_id
            LIMIT 1
        )';
    }

    // =========================
    // STATS
    // =========================

    private function getStats($cabangId, $month)
    {
        $parsedMonth = Carbon::parse($month);

        $query = $this->completedBookingBase()
            ->whereMonth('b.tanggal_booking', $parsedMonth->month)
            ->whereYear('b.tanggal_booking', $parsedMonth->year);

        if ($cabangId != 'all') {
            $query->whereRaw("{$this->cabangSubquery()} = ?", [$cabangId]);
        }

        $activeCustomers = (clone $query)->distinct('u.user_id')->count('u.user_id');

        $totalVisits = (clone $query)->count('b.booking_id');

        return [
            'active_customers' => $activeCustomers,
            'total_visits'     => $totalVisits,
        ];
    }

    // =========================
    // CUSTOMER GROWTH
    // =========================

    private function getCustomerGrowth($cabangId, $month, $viewType)
    {
        $parsedMonth  = Carbon::parse($month);
        $startOfMonth = $parsedMonth->copy()->startOfMonth();
        $endOfMonth   = $parsedMonth->copy()->endOfMonth();
        $useWeekly    = $viewType === 'monthly';

        $labels   = [];
        $datasets = [];
        $cabangs  = DB::table('cabang')->where('status', 'BUKA')->get();

        // ── Labels ──
        if ($useWeekly) {
            $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        } else {
            for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
                $labels[] = $date->format('d');
            }
        }

        $colors = ['#A00020', '#FF7096', '#f45b69', '#ff8fa3', '#fda4af'];

        foreach ($cabangs as $index => $cabang) {
            if ($cabangId != 'all' && $cabang->cabang_id != $cabangId) {
                continue;
            }

            $data = [];

            if ($useWeekly) {
                for ($week = 1; $week <= 4; $week++) {
                    $weekStart = $startOfMonth->copy()->addDays(($week - 1) * 7);
                    $weekEnd   = (clone $weekStart)->addDays(6);
                    if ($weekEnd > $endOfMonth) {
                        $weekEnd = $endOfMonth->copy();
                    }

                    $weekTotal = $this->completedBookingBase()
                        ->whereRaw("{$this->cabangSubquery()} = ?", [$cabang->cabang_id])
                        ->whereBetween('b.tanggal_booking', [
                            $weekStart->toDateString(),
                            $weekEnd->toDateString(),
                        ])
                        ->distinct('u.user_id')
                        ->count('u.user_id');

                    $data[] = $weekTotal;
                }
            } else {
                $rows = $this->completedBookingBase()
                    ->whereRaw("{$this->cabangSubquery()} = ?", [$cabang->cabang_id])
                    ->whereBetween('b.tanggal_booking', [
                        $startOfMonth->toDateString(),
                        $endOfMonth->toDateString(),
                    ])
                    ->select(
                        DB::raw('DATE(b.tanggal_booking) as tgl'),
                        DB::raw('COUNT(DISTINCT u.user_id) as jumlah')
                    )
                    ->groupBy(DB::raw('DATE(b.tanggal_booking)'))
                    ->get()
                    ->keyBy('tgl');

                for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
                    $key    = $date->format('Y-m-d');
                    $data[] = (int) ($rows->get($key)?->jumlah ?? 0);
                }
            }

            $datasets[] = [
                'label'           => $cabang->nama_cabang,
                'data'            => $data,
                'backgroundColor' => $colors[$index % count($colors)],
                'borderRadius'    => 5,
                'barThickness'    => 12,
            ];
        }

        return [
            'labels'   => $labels,
            'datasets' => $datasets,
            'isWeekly' => $useWeekly,
        ];
    }

    // =========================
    // RESERVATION HABITS
    // =========================

    private function getReservationHabits($cabangId, $month)
    {
        $parsedMonth = Carbon::parse($month);

        $query = $this->completedBookingBase()
            ->whereMonth('b.tanggal_booking', $parsedMonth->month)
            ->whereYear('b.tanggal_booking', $parsedMonth->year)
            ->select('b.booking_id', 'b.jam_booking')
            ->distinct();

        if ($cabangId != 'all') {
            $query->whereRaw("{$this->cabangSubquery()} = ?", [$cabangId]);
        }

        $bookings = $query->get();

        $morning   = 0;
        $afternoon = 0;
        $evening   = 0;

        foreach ($bookings as $booking) {
            $hour = (int) Carbon::parse($booking->jam_booking)->format('H');

            if ($hour < 12) {
                $morning++;
            } elseif ($hour < 18) {
                $afternoon++;
            } else {
                $evening++;
            }
        }

        $total = $morning + $afternoon + $evening;

        return [
            'morning'   => $total ? round(($morning / $total) * 100)   : 0,
            'afternoon' => $total ? round(($afternoon / $total) * 100) : 0,
            'evening'   => $total ? round(($evening / $total) * 100)   : 0,
        ];
    }
}