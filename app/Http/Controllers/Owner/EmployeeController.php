<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $selectedCabang = $request->get('cabang', 'all');

        $selectedMonth = $request->get(
            'bulan',
            Carbon::now()->format('Y-m')
        );

        $perPage = $request->get('show', 10);

        if ($perPage !== 'all') {
            $perPage = (int) $perPage;
        }

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

        $employees = $this->getEmployeePerformance(
            $selectedCabang,
            $selectedMonth,
            $perPage
        );

        $topPerformers =
            collect(
                $perPage === 'all'
                    ? $employees
                    : $employees->items()
            )
            ->sortByDesc('total_clients')
            ->take(3);

        return view('owner.employees.employee', compact(
            'employees',
            'topPerformers',
            'cabangs',
            'months',
            'selectedCabang',
            'selectedMonth',
            'perPage'
        ));
    }

    private function getEmployeePerformance($cabangId, $month, $perPage = 10)
    {
        $parsedMonth = Carbon::parse($month);

        $currentMonth = $parsedMonth;

        // =========================
        // CURRENT MONTH
        // =========================

        $query = DB::table('pegawai as p')

            ->join(
                'users as u',
                'p.user_id',
                '=',
                'u.user_id'
            )

            ->join(
                'cabang as c',
                'p.cabang_id',
                '=',
                'c.cabang_id'
            )

            ->leftJoin(
                'booking_detail as bd',
                'p.pegawai_id',
                '=',
                'bd.pegawai_id'
            )

            ->leftJoin(
                'booking as b',
                'bd.booking_id',
                '=',
                'b.booking_id'
            )

            ->leftJoin(
                'ulasan as ul',
                'b.booking_id',
                '=',
                'ul.booking_id'
            )

            ->whereIn('u.role', ['pegawai', 'admin'])

            ->where('p.status_kerja', 'aktif')

            ->where('u.status_akun', 'aktif')

            ->whereDate(
                'u.created_at',
                '<=',
                $currentMonth->copy()->endOfMonth()
            )

            ->where(function ($q) use ($currentMonth) {

                $q->whereNull('b.booking_id')

                    ->orWhere(function ($query) use ($currentMonth) {

                        $query->whereMonth(
                            'b.tanggal_booking',
                            $currentMonth->month
                        )

                        ->whereYear(
                            'b.tanggal_booking',
                            $currentMonth->year
                        )

                        ->where('b.status', 'selesai');
                    });
            });

        if ($cabangId != 'all') {

            $query->where(
                'c.cabang_id',
                $cabangId
            );
        }

        $query = $query

            ->select(
                'p.pegawai_id',
                'p.status_kerja',

                'u.nama',
                'u.role',
                'u.foto_profile',

                'c.nama_cabang',
                'c.cabang_id',

                DB::raw('
                    COUNT(DISTINCT b.booking_id)
                    as total_clients
                '),

                DB::raw('
                    COUNT(DISTINCT bd.booking_detail_id)
                    as total_services
                '),

                DB::raw('
                    ROUND(AVG(ul.rating), 1)
                    as avg_rating
                '),

                DB::raw('
                    DATE_FORMAT(
                        u.created_at,
                        "%M %Y"
                    ) as since_joined
                ')
            )

            ->groupBy(
                'p.pegawai_id',
                'p.status_kerja',

                'u.nama',
                'u.role',
                'u.foto_profile',
                'u.created_at',

                'c.nama_cabang',
                'c.cabang_id'
            )

            ->orderByDesc('p.pegawai_id');


        $employees = $perPage === 'all'

            ? $query->get()

            : $query
                ->paginate($perPage)
                ->withQueryString();


        if ($perPage === 'all') {

            $employees = $employees->map(function ($item) {

                return [

                    'pegawai_id' => $item->pegawai_id,

                    'nama' => $item->nama,

                    'foto_profile' => $item->foto_profile,

                    'initial' => collect(explode(' ', $item->nama))
                        ->filter()
                        ->take(2)
                        ->map(
                            fn ($word)
                                => strtoupper(substr($word, 0, 1))
                        )
                        ->implode(''),

                    'role' => $item->role,

                    'status_kerja' => $item->status_kerja,

                    'nama_cabang' => $item->nama_cabang,

                    'total_clients' => $item->total_clients,

                    'total_services' => $item->total_services,

                    'avg_rating' => $item->avg_rating ?? 0,

                    'since_joined' => $item->since_joined,
                ];
            });

        } else {

            $employees->getCollection()->transform(function ($item) {

                return [

                    'pegawai_id' => $item->pegawai_id,

                    'nama' => $item->nama,

                    'foto_profile' => $item->foto_profile,

                    'initial' => collect(explode(' ', $item->nama))
                        ->filter()
                        ->take(2)
                        ->map(
                            fn ($word)
                                => strtoupper(substr($word, 0, 1))
                        )
                        ->implode(''),

                    'role' => $item->role,

                    'status_kerja' => $item->status_kerja,

                    'nama_cabang' => $item->nama_cabang,

                    'total_clients' => $item->total_clients,

                    'total_services' => $item->total_services,

                    'avg_rating' => $item->avg_rating ?? 0,

                    'since_joined' => $item->since_joined,
                ];
            });
        }

            return $employees;
    }

    public function edit(Request $request)
    {
        $selectedCabang = $request->get('cabang', 'all');

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

        $employees = $this->getEmployeePerformance(
            $selectedCabang,
            $selectedMonth
        );

        $totalEmployees =
            $employees->count();

        $activeEmployees =
            $employees
                ->where('total_clients', '>', 0)
                ->count();

        $branchTotals = DB::table('pegawai as p')

            ->join(
                'cabang as c',
                'p.cabang_id',
                '=',
                'c.cabang_id'
            )

            ->join(
                'users as u',
                'p.user_id',
                '=',
                'u.user_id'
            )

            ->whereIn('u.role', ['pegawai', 'admin'])

            ->where('p.status_kerja', 'aktif')

            ->where('u.status_akun', 'aktif')

            ->whereDate(
                'u.created_at',
                '<=',
                Carbon::parse($selectedMonth)
                    ->endOfMonth()
            )

            ->select(
                'c.nama_cabang',
                DB::raw('COUNT(*) as total')
            )

            ->groupBy('c.nama_cabang')

            ->pluck(
                'total',
                'c.nama_cabang'
            );

        return view(
            'owner.employees.eemployee',
            compact(
                'employees',
                'cabangs',
                'months',
                'selectedCabang',
                'selectedMonth',
                'totalEmployees',
                'activeEmployees',
                'branchTotals'
            )
        );
    }
}