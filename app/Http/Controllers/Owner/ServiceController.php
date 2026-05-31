<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $selectedSort     = $request->get('sort', 'performance');
        $selectedCabang   = $request->get('cabang', 'all');
        $selectedMonth    = $request->get('bulan', Carbon::now()->format('Y-m'));
        $selectedCategory = $request->get('kategori', 'all');

        $perPage = $request->get('show', 10);

        if ($perPage !== 'all') {
            $perPage = (int) $perPage;
        }

        $cabangs      = DB::table('cabang')->where('status', 'BUKA')->get();
        $months       = $this->getMonthOptions();
        $jenisLayanan = DB::table('jenis_layanan')->get(['jenis_layanan_id', 'nama_jenis']);

        $topLayanan  = $this->getTopLayanan($selectedCabang, $selectedMonth, $selectedCategory);
        $leaderboard = $this->getLeaderboardData($selectedCabang, $selectedMonth, $selectedCategory, $perPage, $selectedSort);

        return view('owner.service.service', compact(
            'cabangs', 'months', 'jenisLayanan',
            'selectedCabang', 'selectedMonth', 'selectedCategory',
            'topLayanan', 'leaderboard', 'perPage', 'selectedSort'
        ));
    }

    private function getTopLayanan($cabangId, $month, $category)
    {
        $parsedMonth = Carbon::parse($month);

        // layanan langsung
        $layananQuery = DB::table('booking_detail as bd')
            ->join('booking as b', 'bd.booking_id', '=', 'b.booking_id')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('b.status', 'completed')
            ->whereMonth('b.tanggal_booking', $parsedMonth->month)
            ->whereYear('b.tanggal_booking', $parsedMonth->year);

        // layanan dari paket
        $paketQuery = DB::table('booking_detail as bd')
            ->join('booking as b', 'bd.booking_id', '=', 'b.booking_id')
            ->join('paket_cabang as pc', 'bd.paket_cabang_id', '=', 'pc.paket_cabang_id')
            ->join('paket_detail as pd', 'pc.paket_id', '=', 'pd.paket_id')
            ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
            ->join('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->join('cabang as c', 'pc.cabang_id', '=', 'c.cabang_id')
            ->where('b.status', 'completed')
            ->whereMonth('b.tanggal_booking', $parsedMonth->month)
            ->whereYear('b.tanggal_booking', $parsedMonth->year);

        if ($cabangId != 'all') {
            $layananQuery->where('c.cabang_id', $cabangId);
            $paketQuery->where('c.cabang_id', $cabangId);
        }

        if ($category != 'all') {
            $layananQuery->where('jl.nama_jenis', $category);
            $paketQuery->where('jl.nama_jenis', $category);
        }

        $data = $layananQuery
            ->select(
                'l.layanan_id',
                'l.nama_layanan',
                'jl.nama_jenis'
            )
            ->unionAll(
                $paketQuery->select(
                    'l.layanan_id',
                    'l.nama_layanan',
                    'jl.nama_jenis'
                )
            );

        return DB::query()
            ->fromSub($data, 'x')
            ->select(
                'layanan_id',
                'nama_layanan',
                'nama_jenis',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(
                'layanan_id',
                'nama_layanan',
                'nama_jenis'
            )
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'title' => $item->nama_layanan,
                'cat'   => $item->nama_jenis,
                'cover' => null,
                'total' => $item->total,
                'revenue' => '-'
            ]);
    }

    private function getLeaderboardData(
        $cabangId,
        $month,
        $category,
        $perPage      = 10,
        $selectedSort  = 'performance',
        $dir           = 'desc',
        $sortCabang    = null
    ) {
        $currentMonth = Carbon::parse($month);
        $prevMonth    = $currentMonth->copy()->subMonth();

        // Normalise direction
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        // ── BULAN INI
        $currentQuery = DB::table('booking_detail as bd')
            ->join('booking as b', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('pembayaran as py', function ($join) { $join->on('b.booking_id', '=', 'py.booking_id')->where('py.status', '=', 'verified'); })
            ->leftJoin('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->leftJoin('layanan as l_direct', 'lc.layanan_id', '=', 'l_direct.layanan_id')
            ->leftJoin('paket_cabang as pc', 'bd.paket_cabang_id', '=', 'pc.paket_cabang_id')
            ->leftJoin('paket_detail as pd', 'pc.paket_id', '=', 'pd.paket_id')
            ->leftJoinSub(
                DB::table('paket_detail')
                    ->select(
                        'paket_id',
                        DB::raw('COUNT(*) as jumlah_layanan')
                    )
                    ->groupBy('paket_id'),
                'pd_count',
                function ($join) {
                    $join->on('pc.paket_id', '=', 'pd_count.paket_id');
                }
            )
            ->leftJoin('layanan as l_paket', 'pd.layanan_id', '=', 'l_paket.layanan_id')
            ->leftJoin(
                'jenis_layanan as jl',
                DB::raw('COALESCE(l_direct.jenis_layanan_id, l_paket.jenis_layanan_id)'),
                '=',
                'jl.jenis_layanan_id'
            )
            ->leftJoin(
                'cabang as c',
                DB::raw('COALESCE(lc.cabang_id, pc.cabang_id)'),
                '=',
                'c.cabang_id'
            )
            ->where('b.status', 'completed')
            ->whereMonth('b.tanggal_booking', $currentMonth->month)
            ->whereYear('b.tanggal_booking', $currentMonth->year);

        if ($cabangId != 'all') {
            $currentQuery->where('c.cabang_id', $cabangId);
        }
        if ($category != 'all') {
            $currentQuery->where('jl.nama_jenis', $category);
        }

        $cabangList = DB::table('cabang')
            ->where('status', 'BUKA')
            ->get();

        $dynamicCabangSelect = [];
        foreach ($cabangList as $cabang) {
            $dynamicCabangSelect[] = DB::raw("
                SUM(
                    CASE
                        WHEN c.cabang_id = {$cabang->cabang_id}
                        THEN 1 ELSE 0
                    END
                ) as cabang{$cabang->cabang_id}_count
            ");
            $dynamicCabangSelect[] = DB::raw("
                SUM(
                    CASE
                        WHEN c.cabang_id = {$cabang->cabang_id}
                        THEN
                        CASE
                            WHEN bd.paket_cabang_id IS NOT NULL
                            THEN COALESCE(py.jumlah,0) /
                                NULLIF(pd_count.jumlah_layanan,0)

                            ELSE COALESCE(py.jumlah,0)
                        END
                        ELSE 0
                    END
                ) as cabang{$cabang->cabang_id}_revenue
            ");
        }

        $currentQuery->select(array_merge(
            [
                DB::raw('COALESCE(l_direct.layanan_id, l_paket.layanan_id) as layanan_id'),
                DB::raw('COALESCE(l_direct.nama_layanan, l_paket.nama_layanan) as nama_layanan'),
                'jl.nama_jenis',
                DB::raw("
                SUM(
                    CASE
                        WHEN bd.paket_cabang_id IS NOT NULL
                        THEN COALESCE(py.jumlah,0) /
                            NULLIF(pd_count.jumlah_layanan,0)

                        ELSE COALESCE(py.jumlah,0)
                    END
                ) as total_revenue
                "),
                DB::raw('COUNT(*) as total_count'),
            ],
            $dynamicCabangSelect
        ))
        ->groupBy(
            DB::raw('COALESCE(l_direct.layanan_id, l_paket.layanan_id)'),
            DB::raw('COALESCE(l_direct.nama_layanan, l_paket.nama_layanan)'),
            'jl.nama_jenis'
        );

        if ($selectedSort === 'revenue') {
            $currentQuery->orderBy('total_revenue', $dir);
        } elseif ($selectedSort === 'performance') {
            // Saat cabang=all, bisa sort by booking count cabang tertentu
            if ($cabangId === 'all' && $sortCabang && is_numeric($sortCabang)) {
                $sortCabangId = (int) $sortCabang;
                $dirSql = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
                $currentQuery->orderByRaw("cabang{$sortCabangId}_count {$dirSql}");
            } else {
                $currentQuery->orderBy('total_count', $dir);
            }
        }

        $leaderboard = $perPage === 'all'
            ? $currentQuery->get()
            : $currentQuery->paginate($perPage)->withQueryString();

        // ── BULAN LALU (untuk growth)
        $prevData = DB::table('booking_detail as bd')
            ->join('booking as b', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('pembayaran as py', function ($join) { $join->on('b.booking_id', '=', 'py.booking_id')->where('py.status', '=', 'verified'); })
            ->leftJoin('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->leftJoin('layanan as l_direct', 'lc.layanan_id', '=', 'l_direct.layanan_id')
            ->leftJoin('paket_cabang as pc', 'bd.paket_cabang_id', '=', 'pc.paket_cabang_id')
            ->leftJoin('paket_detail as pd', 'pc.paket_id', '=', 'pd.paket_id')
            ->leftJoinSub(
                DB::table('paket_detail')
                    ->select(
                        'paket_id',
                        DB::raw('COUNT(*) as jumlah_layanan')
                    )
                    ->groupBy('paket_id'),
                'pd_count',
                function ($join) {
                    $join->on('pc.paket_id', '=', 'pd_count.paket_id');
                }
            )
            ->leftJoin('layanan as l_paket', 'pd.layanan_id', '=', 'l_paket.layanan_id')
            ->leftJoin(
                'jenis_layanan as jl',
                DB::raw('COALESCE(l_direct.jenis_layanan_id, l_paket.jenis_layanan_id)'),
                '=',
                'jl.jenis_layanan_id'
            )
            ->leftJoin(
                'cabang as c',
                DB::raw('COALESCE(lc.cabang_id, pc.cabang_id)'),
                '=',
                'c.cabang_id'
            )
            ->where('b.status', 'completed')
            ->whereMonth('b.tanggal_booking', $prevMonth->month)
            ->whereYear('b.tanggal_booking', $prevMonth->year)
            ->when(
                $cabangId != 'all',
                fn($q) => $q->where('c.cabang_id', $cabangId)
            )
            ->when(
                $category != 'all',
                fn($q) => $q->where('jl.nama_jenis', $category)
            )
            ->select(
                DB::raw('COALESCE(l_direct.nama_layanan, l_paket.nama_layanan) as nama_layanan'),
                DB::raw('COUNT(*) as total_count')
            )
            ->groupBy(
                DB::raw('COALESCE(l_direct.nama_layanan, l_paket.nama_layanan)')
            )
            ->pluck('total_count', 'nama_layanan');

        $transformData = function ($item) use ($prevData, $cabangList) {
            $prevCount = $prevData[$item->nama_layanan] ?? 0;
            $growth    = $prevCount > 0
                ? round((($item->total_count - $prevCount) / $prevCount) * 100, 1)
                : ($item->total_count > 0 ? 100 : 0);

            return [
                'service'  => $item->nama_layanan,
                'category' => $item->nama_jenis,

                'branches' => $cabangList->mapWithKeys(function ($cabang) use ($item) {
                    return [
                        $cabang->cabang_id => [
                            'count'   => $item->{'cabang' . $cabang->cabang_id . '_count'},
                            'revenue' => number_format(
                                $item->{'cabang' . $cabang->cabang_id . '_revenue'},
                                0, ',', '.'
                            ),
                        ],
                    ];
                }),

                'selected_count'   => $item->total_count,
                'selected_revenue' => number_format($item->total_revenue, 0, ',', '.'),
                'revenue'          => number_format($item->total_revenue, 0, ',', '.'),
                'total_revenue_raw' => (float) $item->total_revenue,
                'growth'           => $growth,
                'growth_class'     => $growth >= 0 ? 'text-green-500' : 'text-red-500',
            ];
        };

        if ($leaderboard instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $leaderboard->setCollection(
                $leaderboard->getCollection()->map($transformData)
            );
        } else {
            $leaderboard = $leaderboard->map($transformData);
        }

        if ($selectedSort === 'growth') {
            if ($leaderboard instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $sorted = $dir === 'asc'
                    ? $leaderboard->getCollection()->sortBy('growth')->values()
                    : $leaderboard->getCollection()->sortByDesc('growth')->values();
                $leaderboard->setCollection($sorted);
            } else {
                $leaderboard = $dir === 'asc'
                    ? $leaderboard->sortBy('growth')->values()
                    : $leaderboard->sortByDesc('growth')->values();
            }
        }

        return $leaderboard;
    }

    private function getMonthOptions(): \Illuminate\Support\Collection
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push([
                'value' => $month->format('Y-m'),
                'label' => $month->locale('id')->translatedFormat('F Y'),
            ]);
        }
        return $months;
    }

    private function getTotalRevenue($cabangId, $month)
    {
        $parsedMonth = Carbon::parse($month);

        $query = DB::table('pembayaran')
            ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
            ->where('pembayaran.status', 'verified')
            ->where('booking.status', 'completed')
            ->whereMonth('booking.tanggal_booking', $parsedMonth->month)
            ->whereYear('booking.tanggal_booking', $parsedMonth->year);

        if ($cabangId != 'all') {
            $query->whereExists(function ($sub) use ($cabangId) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->leftJoin(
                        'layanan_cabang',
                        'booking_detail.layanan_cabang_id',
                        '=',
                        'layanan_cabang.layanan_cabang_id'
                    )
                    ->leftJoin(
                        'paket_cabang',
                        'booking_detail.paket_cabang_id',
                        '=',
                        'paket_cabang.paket_cabang_id'
                    )
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where(function ($q) use ($cabangId) {
                        $q->where('layanan_cabang.cabang_id', $cabangId)
                        ->orWhere('paket_cabang.cabang_id', $cabangId);
                    });
            });
        }

        return $query->sum('pembayaran.jumlah');
    }

    public function edit(Request $request)
    {
        $selectedSort   = $request->get('sort', 'performance');
        $selectedDir    = $request->get('dir', 'desc');
        $selectedCabang = $request->get('cabang', 'all');
        $selectedMonth  = $request->get('bulan', Carbon::now()->format('Y-m'));
        $selectedSortCabang = $request->get('sort_cabang');

        $cabangs      = DB::table('cabang')->where('status', 'BUKA')->get();
        $jenisLayanan = DB::table('jenis_layanan')->get();
        $leaderboard  = $this->getLeaderboardData(
            $selectedCabang, $selectedMonth, 'all', 'all',
            $selectedSort, $selectedDir, $selectedSortCabang
        );
        $totalRevenue = $this->getTotalRevenue($selectedCabang, $selectedMonth);
        $months       = $this->getMonthOptions();

        return view('owner.service.eservice', compact(
            'cabangs', 'selectedCabang', 'selectedMonth', 'selectedSortCabang',
            'jenisLayanan', 'leaderboard', 'totalRevenue',
            'months', 'selectedSort', 'selectedDir'
        ));
    }
}