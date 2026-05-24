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
        $selectedCabang = $request->get(
            'cabang',
            'all'
        );

        $selectedMonth = $request->get(
            'bulan',
            Carbon::now()->format('Y-m')
        );

        $cabangs = DB::table('cabang')
            ->where('status', 'BUKA')
            ->get();

        $months = collect();

        for ($i = 5; $i >= 0; $i--) {

            $month = Carbon::now()->subMonths($i);

            $months->push([
                'value' => $month->format('Y-m'),
                'label' => $month->translatedFormat('F Y')
            ]);
        }

        $viewType = $request->get(
            'view',
            'daily'
        );

        $stats = $this->getStats(
            $selectedCabang,
            $selectedMonth
        );

        $customerGrowth = $this->getCustomerGrowth(
            $selectedCabang,
            $selectedMonth,
            $viewType
        );

        $reservationHabits = $this->getReservationHabits(
            $selectedCabang,
            $selectedMonth
        );

        return view(
            'owner.customers',
            compact(
                'cabangs',
                'months',
                'selectedCabang',
                'selectedMonth',
                'viewType',
                'stats',
                'customerGrowth',
                'reservationHabits'
            )
        );
    }

    // =========================
    // BASE QUERY
    // =========================

    private function bookingBaseQuery()
    {
        return DB::table('booking as b')

            ->join(
                'booking_detail as bd',
                'b.booking_id',
                '=',
                'bd.booking_id'
            )

            ->join(
                'layanan_cabang as lc',
                'bd.layanan_cabang_id',
                '=',
                'lc.layanan_cabang_id'
            )

            ->join(
                'pelanggan as p',
                'b.pelanggan_id',
                '=',
                'p.pelanggan_id'
            )

            ->join(
                'users as u',
                'p.user_id',
                '=',
                'u.user_id'
            )

            ->where('b.status', 'completed');
    }

    // =========================
    // STATS
    // =========================

    private function getStats($cabangId, $month)
    {
        $parsedMonth = Carbon::parse($month);

        $query = $this->bookingBaseQuery()

            ->whereMonth(
                'b.tanggal_booking',
                $parsedMonth->month
            )

            ->whereYear(
                'b.tanggal_booking',
                $parsedMonth->year
            );

        if ($cabangId != 'all') {

            $query->where(
                'lc.cabang_id',
                $cabangId
            );
        }

        $activeCustomers = $query

            ->distinct('u.user_id')

            ->count('u.user_id');

        return [

            'active_customers' =>
                $activeCustomers

        ];
    }

    // =========================
    // CUSTOMER GROWTH
    // =========================

    private function getCustomerGrowth(
        $cabangId,
        $month,
        $viewType
    ) {
        $parsedMonth = Carbon::parse($month);

        $startOfMonth =
            $parsedMonth->copy()->startOfMonth();

        $endOfMonth =
            $parsedMonth->copy()->endOfMonth();

        $daysInMonth =
            $parsedMonth->daysInMonth;

        $useWeekly =
            $viewType === 'monthly';

        $labels = [];

        $datasets = [];

        $cabangs = DB::table('cabang')
            ->where('status', 'BUKA')
            ->get();

        // =========================
        // LABELS
        // =========================

        if ($useWeekly) {

            $labels = [
                'Week 1',
                'Week 2',
                'Week 3',
                'Week 4'
            ];

        } else {

            for (
                $date = $startOfMonth->copy();
                $date <= $endOfMonth;
                $date->addDay()
            ) {

                $labels[] =
                    $date->format('d');
            }
        }

        // =========================
        // DATASETS
        // =========================

        foreach ($cabangs as $index => $cabang) {

            if (
                $cabangId != 'all' &&
                $cabang->cabang_id != $cabangId
            ) {
                continue;
            }

            $data = [];

            if ($useWeekly) {

                for ($week = 1; $week <= 4; $week++) {

                    $weekStart =
                        $startOfMonth
                            ->copy()
                            ->addDays(($week - 1) * 7);

                    $weekEnd =
                        $weekStart
                            ->copy()
                            ->addDays(6);

                    $count = $this->bookingBaseQuery()

                        ->where(
                            'lc.cabang_id',
                            $cabang->cabang_id
                        )

                        ->whereBetween(
                            'b.tanggal_booking',
                            [
                                $weekStart,
                                $weekEnd
                            ]
                        )

                        ->distinct('u.user_id')

                        ->count('u.user_id');

                    $data[] = $count;
                }

            } else {

                for (
                    $date = $startOfMonth->copy();
                    $date <= $endOfMonth;
                    $date->addDay()
                ) {

                    $count = $this->bookingBaseQuery()

                        ->where(
                            'lc.cabang_id',
                            $cabang->cabang_id
                        )

                        ->whereDate(
                            'b.tanggal_booking',
                            $date
                        )

                        ->distinct('u.user_id')

                        ->count('u.user_id');

                    $data[] = $count;
                }
            }

            $colors = [
                '#A00020',
                '#FF7096',
                '#f45b69',
                '#ff8fa3',
                '#fda4af'
            ];

            $datasets[] = [

                'label' =>
                    $cabang->nama_cabang,

                'data' => $data,

                'backgroundColor' =>
                    $colors[
                        $index % count($colors)
                    ],

                'borderRadius' => 5,

                'barThickness' => 12

            ];
        }

        return [

            'labels' => $labels,

            'datasets' => $datasets,

            'isWeekly' => $useWeekly

        ];
    }

    // =========================
    // RESERVATION HABITS
    // =========================

    private function getReservationHabits(
        $cabangId,
        $month
    ) {
        $parsedMonth = Carbon::parse($month);

        $query = $this->bookingBaseQuery()

            ->whereMonth(
                'b.tanggal_booking',
                $parsedMonth->month
            )

            ->whereYear(
                'b.tanggal_booking',
                $parsedMonth->year
            )

            ->select(
                'b.tanggal_booking'
            )

            ->distinct();

        if ($cabangId != 'all') {

            $query->where(
                'lc.cabang_id',
                $cabangId
            );
        }

        $bookings = $query->get();

        $morning = 0;

        $afternoon = 0;

        $evening = 0;

        foreach ($bookings as $booking) {

            $hour = Carbon::parse(
                $booking->tanggal_booking
            )->format('H');

            if ($hour < 12) {

                $morning++;

            } elseif ($hour < 18) {

                $afternoon++;

            } else {

                $evening++;
            }
        }

        $total =
            $morning +
            $afternoon +
            $evening;

        return [

            'morning' => $total
                ? round(($morning / $total) * 100)
                : 0,

            'afternoon' => $total
                ? round(($afternoon / $total) * 100)
                : 0,

            'evening' => $total
                ? round(($evening / $total) * 100)
                : 0,

        ];
    }
}