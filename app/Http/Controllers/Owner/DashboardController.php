<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cabangs        = Cabang::where('status', 'BUKA')->get();
        $selectedCabang = $request->cabang;

        $stats = $this->getDashboardStats($selectedCabang);

        $revenues   = $this->getRevenueChartData($selectedCabang);
        $chartData  = $revenues['data'];
        $chartLabels = $revenues['labels'];

        $popularServices  = $this->getPopularServices($selectedCabang);
        $staffPerformance = $this->getStaffPerformance($selectedCabang);

        return view('owner.dashboard', compact(
            'cabangs',
            'selectedCabang',
            'stats',
            'chartData',
            'chartLabels',
            'popularServices',
            'staffPerformance'
        ));
    }

    private function getDashboardStats($selectedCabang = null)
    {
        $allCabangs = Cabang::where('status', 'BUKA')->get();

        $revenueForCabang = function($cabangId) {
            return DB::table('pembayaran')
                ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
                ->where('pembayaran.status', 'verified')
                ->whereDate('booking.tanggal_booking', today())
                ->whereExists(function ($sub) use ($cabangId) {
                    $sub->select(DB::raw(1))
                        ->from('booking_detail')
                        ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                        ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                        ->where('layanan_cabang.cabang_id', $cabangId);
                })
                ->sum('pembayaran.jumlah');
        };

        $bookingForCabang = function($cabangId) {
            return DB::table('booking')
                ->whereDate('tanggal_booking', today())
                ->where('status', '!=', 'batal')
                ->whereExists(function ($sub) use ($cabangId) {
                    $sub->select(DB::raw(1))
                        ->from('booking_detail')
                        ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                        ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                        ->where('layanan_cabang.cabang_id', $cabangId);
                })
                ->count();
        };

        $customerForCabang = function($cabangId) {
            return DB::table('booking')
                ->join('pelanggan', 'booking.pelanggan_id', '=', 'pelanggan.pelanggan_id')
                ->whereDate('booking.tanggal_booking', today())
                ->whereExists(function ($sub) use ($cabangId) {
                    $sub->select(DB::raw(1))
                        ->from('booking_detail')
                        ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                        ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                        ->where('layanan_cabang.cabang_id', $cabangId);
                })
                ->distinct()
                ->count('pelanggan.pelanggan_id');
        };

        $staffForCabang = function($cabangId) {
            return Pegawai::join('users', 'pegawai.user_id', '=', 'users.user_id')
                ->where('pegawai.status_kerja', 'aktif')
                ->whereIn('users.role', ['pegawai', 'admin'])
                ->where('pegawai.cabang_id', $cabangId)
                ->count();
        };

        $cabangBreakdown = [];
        if (!$selectedCabang) {
            foreach ($allCabangs as $cabang) {
                $rev = $revenueForCabang($cabang->cabang_id);
                $cabangBreakdown[] = [
                    'nama'     => $cabang->nama_cabang,
                    'revenue'  => number_format($rev / 1000, 0) . 'k',
                    'bookings' => $bookingForCabang($cabang->cabang_id),
                    'customers'=> $customerForCabang($cabang->cabang_id),
                    'staff'    => $staffForCabang($cabang->cabang_id),
                ];
            }
        }

        // === TOTAL ===
        $revenueQuery = DB::table('pembayaran')
            ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
            ->where('pembayaran.status', 'verified')
            ->whereDate('booking.tanggal_booking', today());

        if ($selectedCabang) {
            $revenueQuery->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }
        $todayRevenue = $revenueQuery->sum('pembayaran.jumlah');

        $bookingQuery = DB::table('booking')
            ->whereDate('tanggal_booking', today())
            ->where('status', '!=', 'batal');
        if ($selectedCabang) {
            $bookingQuery->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }
        $todayBookings = $bookingQuery->count();

        $customerQuery = DB::table('booking')
            ->join('pelanggan', 'booking.pelanggan_id', '=', 'pelanggan.pelanggan_id')
            ->whereDate('booking.tanggal_booking', today());
        if ($selectedCabang) {
            $customerQuery->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }
        $todayCustomers = $customerQuery->distinct()->count('pelanggan.pelanggan_id');

        $activeStaff = Pegawai::join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->where('pegawai.status_kerja', 'aktif')
            ->whereIn('users.role', ['pegawai', 'admin'])
            ->when($selectedCabang, fn($q) => $q->where('pegawai.cabang_id', $selectedCabang))
            ->count();

        $selectedCabangName = $selectedCabang
            ? Cabang::find($selectedCabang)?->nama_cabang
            : 'Seluruh Cabang';

        return [
            'todayRevenue'       => number_format($todayRevenue / 1000, 0) . 'k',
            'todayBookings'      => $todayBookings,
            'todayCustomers'     => $todayCustomers,
            'activeStaff'        => $activeStaff,
            'selectedCabangName' => $selectedCabangName,
            'cabangBreakdown'    => $cabangBreakdown,
        ];
    }

    private function getRevenueChartData($selectedCabang = null)
    {
        $query = DB::table('pembayaran')
            ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
            ->where('pembayaran.status', 'verified')
            ->where('booking.status', 'selesai');

        if ($selectedCabang) {
            $query->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join(
                        'layanan_cabang',
                        'booking_detail.layanan_cabang_id',
                        '=',
                        'layanan_cabang.layanan_cabang_id'
                    )
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }

        $revenues = $query
            ->selectRaw('MONTH(booking.tanggal_booking) as bulan, SUM(pembayaran.jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartData   = [];
        $chartLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month         = now()->subMonths($i)->month;
            $chartData[]   = $revenues[$month] ?? 0;
            $chartLabels[] = now()->subMonths($i)->format('M');
        }

        return ['data' => $chartData, 'labels' => $chartLabels];
    }

    private function getPopularServices($selectedCabang = null)
    {
        return DB::table('booking_detail')
            ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
            ->join('layanan', 'layanan_cabang.layanan_id', '=', 'layanan.layanan_id')
            ->join('booking', 'booking_detail.booking_id', '=', 'booking.booking_id')
            ->where('booking.status', 'selesai')
            ->whereMonth('booking.tanggal_booking', now()->month)
            ->when($selectedCabang, fn($q) =>
                $q->where('layanan_cabang.cabang_id', $selectedCabang)
            )
            ->select('layanan.nama_layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('layanan.nama_layanan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    private function getStaffPerformance($selectedCabang = null)
    {
        return DB::table('pegawai')
            ->join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->whereIn('users.role', ['pegawai', 'admin'])
            ->leftJoin('booking_detail', 'pegawai.pegawai_id', '=', 'booking_detail.pegawai_id')
            ->leftJoin('booking', 'booking_detail.booking_id', '=', 'booking.booking_id')
            ->leftJoin('ulasan', 'booking.booking_id', '=', 'ulasan.booking_id')
            ->where('pegawai.status_kerja', 'aktif')
            ->when($selectedCabang, fn($q) =>
                $q->where('pegawai.cabang_id', $selectedCabang)
            )
            ->select(
                'pegawai.pegawai_id',
                'pegawai.cabang_id',
                'users.nama as nama_pegawai',
                DB::raw('COUNT(DISTINCT booking.booking_id) as total_booking'),
                DB::raw('ROUND(AVG(ulasan.rating), 1) as avg_rating')
            )
            ->groupBy('pegawai.pegawai_id', 'pegawai.cabang_id', 'users.nama')
            ->orderByDesc('total_booking')
            ->limit(4)
            ->get()
            ->map(function ($staff) {
                $cabang = Cabang::find($staff->cabang_id);
                return [
                    'nama'          => $staff->nama_pegawai,
                    'cabang'        => $cabang?->nama_cabang ?? '-',
                    'total_booking' => $staff->total_booking,
                    'rating'        => $staff->avg_rating ?? 0,
                ];
            });
    }
}