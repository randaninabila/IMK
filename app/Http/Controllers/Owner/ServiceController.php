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
        $selectedCabang   = $request->get('cabang', 'all');
        $selectedMonth    = $request->get('bulan', Carbon::now()->format('Y-m'));
        $selectedCategory = $request->get('kategori', 'all');
        
        $perPage = $request->get('show', 10);

        if ($perPage !== 'all') {
            $perPage = (int) $perPage;
        }

        $cabangs = DB::table('cabang')->where('status', 'BUKA')->get();

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push([
                'value' => $month->format('Y-m'),
                'label' => $month->translatedFormat('F Y'),
            ]);
        }

        $jenisLayanan = DB::table('jenis_layanan')->get(['jenis_layanan_id', 'nama_jenis']);

        $topLayanan  = $this->getTopLayanan($selectedCabang, $selectedMonth, $selectedCategory);
        $leaderboard = $this->getLeaderboardData($selectedCabang, $selectedMonth, $selectedCategory, $perPage);

        return view('owner.service.service', compact(
            'cabangs', 'months', 'jenisLayanan',
            'selectedCabang', 'selectedMonth', 'selectedCategory',
            'topLayanan', 'leaderboard', 'perPage'
        ));
    }

    private function revenueSubquery(): string
    {
        return "
            SELECT
                bd_sub.booking_detail_id,
                COALESCE(
                    py.jumlah
                    * (COALESCE(lc_sub.harga_promo, lc_sub.harga)
                       / NULLIF(total_harga.total, 0)),
                    0
                ) AS revenue_proporsional
            FROM booking_detail bd_sub
            JOIN layanan_cabang lc_sub
                ON bd_sub.layanan_cabang_id = lc_sub.layanan_cabang_id
            JOIN (
                SELECT
                    bd2.booking_id,
                    SUM(COALESCE(lc2.harga_promo, lc2.harga)) AS total
                FROM booking_detail bd2
                JOIN layanan_cabang lc2
                    ON bd2.layanan_cabang_id = lc2.layanan_cabang_id
                GROUP BY bd2.booking_id
            ) total_harga ON bd_sub.booking_id = total_harga.booking_id
            LEFT JOIN pembayaran py
                ON bd_sub.booking_id = py.booking_id
               AND py.status = 'verified'
        ";
    }

    private function getTopLayanan($cabangId, $month, $category)
    {
        $parsedMonth = Carbon::parse($month);

        $query = DB::table('booking_detail as bd')
            ->join('booking as b', 'bd.booking_id', '=', 'b.booking_id')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->leftJoin('album as a', 'l.layanan_id', '=', 'a.layanan_id')
            ->leftJoin('album_foto as af', function ($join) {
                $join->on('a.album_id', '=', 'af.album_id')
                    ->where('af.tipe', 'cover');
            })
            ->joinSub($this->revenueSubquery(), 'rev', function ($join) {
                $join->on('bd.booking_detail_id', '=', 'rev.booking_detail_id');
            })
            ->where('b.status', 'selesai')
            ->whereMonth('b.tanggal_booking', $parsedMonth->month)
            ->whereYear('b.tanggal_booking', $parsedMonth->year)
            ->select(
                'l.layanan_id',
                'l.nama_layanan',
                'jl.nama_jenis',
                'af.url_foto as cover',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(rev.revenue_proporsional) as total_revenue')
            );

        if ($cabangId != 'all') {
            $query->where('c.cabang_id', $cabangId);
        }
        if ($category != 'all') {
            $query->where('jl.nama_jenis', $category);
        }

        return $query
            ->groupBy('l.layanan_id', 'l.nama_layanan', 'jl.nama_jenis', 'af.url_foto')
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'title'   => $item->nama_layanan,
                'cat'     => $item->nama_jenis,
                'cover'   => $item->cover,
                'total'   => $item->total,
                'revenue' => 'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
            ]);
    }

    private function getLeaderboardData($cabangId, $month, $category, $perPage = 10)
    {
        $currentMonth = Carbon::parse($month);
        $prevMonth    = $currentMonth->copy()->subMonth();

        // ── BULAN INI
        $currentQuery = DB::table('booking_detail as bd')
            ->join('booking as b', 'bd.booking_id', '=', 'b.booking_id')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->joinSub($this->revenueSubquery(), 'rev', function ($join) {
                $join->on('bd.booking_detail_id', '=', 'rev.booking_detail_id');
            })
            ->where('b.status', 'selesai')
            ->whereMonth('b.tanggal_booking', $currentMonth->month)
            ->whereYear('b.tanggal_booking', $currentMonth->year);

        if ($cabangId != 'all') {
            $currentQuery->where('c.cabang_id', $cabangId);
        }
        if ($category != 'all') {
            $currentQuery->where('jl.nama_jenis', $category);
        }

        $currentQuery = $currentQuery
            ->select(
                'l.layanan_id',
                'l.nama_layanan',
                'jl.nama_jenis',

                DB::raw('SUM(CASE WHEN c.cabang_id = 1 THEN 1 ELSE 0 END) as cabang1_count'),

                DB::raw('
                    SUM(
                        CASE
                            WHEN c.cabang_id = 1
                            THEN rev.revenue_proporsional
                            ELSE 0
                        END
                    ) as cabang1_revenue
                '),

                DB::raw('SUM(CASE WHEN c.cabang_id = 2 THEN 1 ELSE 0 END) as cabang2_count'),

                DB::raw('
                    SUM(
                        CASE
                            WHEN c.cabang_id = 2
                            THEN rev.revenue_proporsional
                            ELSE 0
                        END
                    ) as cabang2_revenue
                '),

                DB::raw('SUM(rev.revenue_proporsional) as total_revenue'),

                DB::raw('COUNT(*) as total_count')
            )

            ->groupBy(
                'l.layanan_id',
                'l.nama_layanan',
                'jl.nama_jenis'
            )

            ->orderByDesc('total_count')
            ->orderByDesc('total_revenue');


        $leaderboard = $perPage === 'all'

            ? $currentQuery->get()

            : $currentQuery
                ->paginate($perPage)
                ->withQueryString();

        // ── BULAN LALU (untuk growth)
        $prevData = DB::table('booking_detail as bd')
            ->join('booking as b', 'bd.booking_id', '=', 'b.booking_id')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('b.status', 'selesai')
            ->whereMonth('b.tanggal_booking', $prevMonth->month)
            ->whereYear('b.tanggal_booking', $prevMonth->year)
            ->when($cabangId != 'all', fn($q) => $q->where('c.cabang_id', $cabangId))
            ->when($category != 'all', fn($q) => $q->where('jl.nama_jenis', $category))
            ->select('l.nama_layanan', DB::raw('COUNT(*) as total_count'))
            ->groupBy('l.nama_layanan')
            ->pluck('total_count', 'l.nama_layanan');

        if ($leaderboard instanceof \Illuminate\Pagination\LengthAwarePaginator) {

            $leaderboard->getCollection()->transform(function ($item) use ($prevData) {

                $prevCount = $prevData[$item->nama_layanan] ?? 0;

                $growth = $prevCount > 0
                    ? round((($item->total_count - $prevCount) / $prevCount) * 100, 1)
                    : 100;

                return [
                    'service'          => $item->nama_layanan,
                    'category'         => $item->nama_jenis,
                    'cabang1_count'    => $item->cabang1_count,
                    'cabang1_revenue'  => number_format($item->cabang1_revenue, 0, ',', '.'),
                    'cabang2_count'    => $item->cabang2_count,
                    'cabang2_revenue'  => number_format($item->cabang2_revenue, 0, ',', '.'),
                    'selected_count'   => $item->total_count,
                    'selected_revenue' => number_format($item->total_revenue, 0, ',', '.'),
                    'revenue'          => 'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
                    'growth'           => $growth,
                    'growth_class'     => $growth >= 0 ? 'text-green-500' : 'text-red-500',
                ];
            });

        } else {

            $leaderboard = $leaderboard->map(function ($item) use ($prevData) {

                $prevCount = $prevData[$item->nama_layanan] ?? 0;

                $growth = $prevCount > 0
                    ? round((($item->total_count - $prevCount) / $prevCount) * 100, 1)
                    : 100;

                return [
                    'service'          => $item->nama_layanan,
                    'category'         => $item->nama_jenis,
                    'cabang1_count'    => $item->cabang1_count,
                    'cabang1_revenue'  => number_format($item->cabang1_revenue, 0, ',', '.'),
                    'cabang2_count'    => $item->cabang2_count,
                    'cabang2_revenue'  => number_format($item->cabang2_revenue, 0, ',', '.'),
                    'selected_count'   => $item->total_count,
                    'selected_revenue' => number_format($item->total_revenue, 0, ',', '.'),
                    'revenue'          => 'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
                    'growth'           => $growth,
                    'growth_class'     => $growth >= 0 ? 'text-green-500' : 'text-red-500',
                ];
            });
        }

        return $leaderboard;
    }

    private function getTotalRevenue($cabangId, $month)
    {
        $parsedMonth = Carbon::parse($month);

        $query = DB::table('pembayaran')
            ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
            ->where('pembayaran.status', 'verified')
            ->where('booking.status', 'selesai')
            ->whereMonth('booking.tanggal_booking', $parsedMonth->month)
            ->whereYear('booking.tanggal_booking', $parsedMonth->year);

        if ($cabangId != 'all') {
            $query->whereExists(function ($sub) use ($cabangId) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $cabangId);
            });
        }

        return $query->sum('pembayaran.jumlah');
    }

    public function edit(Request $request)
    {
        $selectedCabang = $request->get('cabang', 'all');
        $selectedMonth  = $request->get('bulan', Carbon::now()->format('Y-m'));

        $cabangs      = DB::table('cabang')->where('status', 'BUKA')->get();
        $jenisLayanan = DB::table('jenis_layanan')->get();
        $leaderboard  = $this->getLeaderboardData($selectedCabang, $selectedMonth, 'all');
        $totalRevenue = $this->getTotalRevenue($selectedCabang, $selectedMonth);

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push([
                'value' => $month->format('Y-m'),
                'label' => $month->translatedFormat('F Y'),
            ]);
        }

        return view('owner.service.eservice', compact(
            'cabangs', 'selectedCabang', 'selectedMonth',
            'jenisLayanan', 'leaderboard', 'totalRevenue', 'months'
        ));
    }
}