<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cabangs = Cabang::where('status', 'BUKA')->get();
        $selectedCabang = $request->cabang;

        $stats = $this->getDashboardStats($selectedCabang);
        $revenues = $this->getRevenueChartData($selectedCabang);
        $chartData = $revenues['data'];
        $chartLabels = $revenues['labels'];
        $popularServices = $this->getPopularServices($selectedCabang);
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
                    'revenue'  => $this->formatCurrency($rev),
                    'bookings' => $bookingForCabang($cabang->cabang_id),
                    'customers'=> $customerForCabang($cabang->cabang_id),
                    'staff'    => $staffForCabang($cabang->cabang_id),
                ];
            }
        }

        // TOTAL STATS
        $todayRevenue = $this->getTodayRevenue($selectedCabang);
        $todayBookings = $this->getTodayBookings($selectedCabang);
        $todayCustomers = $this->getTodayCustomers($selectedCabang);
        $activeStaff = $this->getActiveStaff($selectedCabang);

        $selectedCabangName = $selectedCabang
            ? Cabang::where('cabang_id', $selectedCabang)->first()?->nama_cabang ?? 'Cabang Tidak Ditemukan'
            : 'Seluruh Cabang';

        return [
            'todayRevenue'       => $this->formatCurrency($todayRevenue),
            'todayBookings'      => $todayBookings,
            'todayCustomers'     => $todayCustomers,
            'activeStaff'        => $activeStaff,
            'selectedCabangName' => $selectedCabangName,
            'cabangBreakdown'    => $cabangBreakdown,
        ];
    }

    private function getTodayRevenue($selectedCabang = null)
    {
        $query = DB::table('pembayaran')
            ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
            ->where('pembayaran.status', 'verified')
            ->whereDate('booking.tanggal_booking', today());

        if ($selectedCabang) {
            $query->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }

        return $query->sum('pembayaran.jumlah');
    }

    private function getTodayBookings($selectedCabang = null)
    {
        $query = DB::table('booking')
            ->whereDate('tanggal_booking', today())
            ->where('status', '!=', 'batal');

        if ($selectedCabang) {
            $query->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }

        return $query->count();
    }

    private function getTodayCustomers($selectedCabang = null)
    {
        $query = DB::table('booking')
            ->join('pelanggan', 'booking.pelanggan_id', '=', 'pelanggan.pelanggan_id')
            ->whereDate('booking.tanggal_booking', today());

        if ($selectedCabang) {
            $query->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }

        return $query->distinct()->count('pelanggan.pelanggan_id');
    }

    private function getActiveStaff($selectedCabang = null)
    {
        return Pegawai::join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->where('pegawai.status_kerja', 'aktif')
            ->whereIn('users.role', ['pegawai', 'admin'])
            ->when($selectedCabang, fn($q) => $q->where('pegawai.cabang_id', $selectedCabang))
            ->count();
    }

    private function formatCurrency($amount)
    {
        if ($amount >= 1000000) {
            return number_format($amount / 1000000, 1) . 'jt';
        } elseif ($amount >= 1000) {
            return number_format($amount / 1000, 0) . 'k';
        }
        return number_format($amount);
    }

    private function getRevenueChartData($selectedCabang = null)
    {
        $query = DB::table('pembayaran')
            ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
            ->where('pembayaran.status', 'verified')
            ->where('booking.status', '!=', 'batal');

        if ($selectedCabang) {
            $query->whereExists(function ($sub) use ($selectedCabang) {
                $sub->select(DB::raw(1))
                    ->from('booking_detail')
                    ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                    ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                    ->where('layanan_cabang.cabang_id', $selectedCabang);
            });
        }

        $revenues = $query
            ->selectRaw('MONTH(booking.tanggal_booking) as bulan, SUM(pembayaran.jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartData = [];
        $chartLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->month;
            $chartData[] = $revenues[$month] ?? 0;
            $chartLabels[] = now()->subMonths($i)->format('M');
        }

        return ['data' => $chartData, 'labels' => $chartLabels];
    }

    private function getPopularServices($selectedCabang = null)
    {
        $services = DB::table('booking_detail')
            ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
            ->join('layanan', 'layanan_cabang.layanan_id', '=', 'layanan.layanan_id')
            ->join('booking', 'booking_detail.booking_id', '=', 'booking.booking_id')
            ->where('booking.status', '!=', 'batal')
            ->where(function ($q) {
                $q->whereNull('booking.booking_id')
                ->orWhereYear('booking.tanggal_booking', now()->year);
            })
            ->whereMonth('booking.tanggal_booking', now()->month)
            ->when($selectedCabang, fn($q) => $q->where('layanan_cabang.cabang_id', $selectedCabang))
            ->select('layanan.nama_layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('layanan.nama_layanan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return $services->map(function ($service) {
            return (object) [
                'nama_layanan' => $service->nama_layanan,
                'total' => $service->total
            ];
        });
    }

    private function getStaffPerformance($selectedCabang = null)
    {
        $staff = DB::table('pegawai')
            ->join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->whereIn('users.role', ['pegawai', 'admin'])
            ->where('pegawai.status_kerja', 'aktif')
            ->leftJoin('booking_detail', 'pegawai.pegawai_id', '=', 'booking_detail.pegawai_id')
            ->leftJoin('booking', 'booking_detail.booking_id', '=', 'booking.booking_id')
            ->when($selectedCabang, fn($q) => $q->where('pegawai.cabang_id', $selectedCabang))
            ->where(function ($q) {
                $q->whereNull('booking.booking_id')
                ->orWhereYear('booking.tanggal_booking', now()->year);
            })
            ->select(
                'pegawai.pegawai_id',
                'pegawai.cabang_id',
                'users.nama as nama_pegawai',
                'users.foto_profile',
                DB::raw('COUNT(DISTINCT booking.booking_id) as total_booking'),
            )
            ->groupBy('pegawai.pegawai_id', 'pegawai.cabang_id', 'users.nama')
            ->orderByDesc('total_booking')
            ->limit(4)
            ->get();

        return $staff->map(function ($staffData) {
            $cabang = Cabang::find($staffData->cabang_id);
            return (object) [
                'nama' => $staffData->nama_pegawai,
                'foto_profile' => $staffData->foto_profile,
                'cabang' => $cabang?->nama_cabang ?? '-',
                'total_booking' => $staffData->total_booking,
            ];
        });
    }

    public function exportPDF(Request $request)
    {
        $branch = $request->branch;
        $reports = json_decode($request->reports, true);
        $period = $request->period;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Validate date range
        $dateRange = $this->getDateRange($period, $startDate, $endDate);
        
        $data = [
            'branch' => $branch,
            'reports' => $reports,
            'date_range' => $dateRange,
            'branch_name' => $this->getBranchName($branch),
            'financial_data' => $this->getFinancialData($branch, $dateRange),
            'services_data' => $this->getServicesData($branch, $dateRange),
            'employees_data' => $this->getEmployeesData($branch, $dateRange),
            'customers_data' => $this->getCustomersData($branch, $dateRange),
        ];

        $pdf = Pdf::loadView('owner.reports.pdf-report', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        return $pdf->stream('salon-report-' . now()->format('Y-m-d-His') . '.pdf');
    }

    private function getDateRange($period, $startDate, $endDate)
    {
        return match($period) {
            'today' => [
                'start' => today()->format('Y-m-d'),
                'end' => today()->format('Y-m-d'),
                'label' => 'Today'
            ],
            'week' => [
                'start' => now()->startOfWeek()->format('Y-m-d'),
                'end' => now()->endOfWeek()->format('Y-m-d'),
                'label' => 'This Week'
            ],
            'month' => [
                'start' => now()->startOfMonth()->format('Y-m-d'),
                'end' => now()->endOfMonth()->format('Y-m-d'),
                'label' => 'This Month'
            ],
            'year' => [
                'start' => now()->startOfYear()->format('Y-m-d'),
                'end' => now()->endOfYear()->format('Y-m-d'),
                'label' => 'This Year'
            ],
            'custom' => [
                'start' => $startDate,
                'end' => $endDate,
                'label' => Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y')
            ],
            default => [
                'start' => today()->format('Y-m-d'),
                'end' => today()->format('Y-m-d'),
                'label' => 'Today'
            ]
        };
    }

    private function getBranchName($branch)
    {
        if ($branch === 'Semua') return 'All Branches';
        
        $cabang = Cabang::where('nama_cabang', 'LIKE', "%$branch%")->first();
        return $cabang?->nama_cabang ?? $branch;
    }

    private function getFinancialData($branch, $dateRange)
    {
        $query = DB::table('pembayaran')
            ->join('booking', 'pembayaran.booking_id', '=', 'booking.booking_id')
            ->where('pembayaran.status', 'verified')
            ->whereBetween('booking.tanggal_booking', [$dateRange['start'], $dateRange['end']]);

        if ($branch !== 'Semua') {
            $query->whereExists(function ($sub) use ($branch) {
                $cabangId = Cabang::where('nama_cabang', 'LIKE', "%$branch%")->first()?->cabang_id;
                if ($cabangId) {
                    $sub->select(DB::raw(1))
                        ->from('booking_detail')
                        ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
                        ->whereColumn('booking_detail.booking_id', 'booking.booking_id')
                        ->where('layanan_cabang.cabang_id', $cabangId);
                }
            });
        }

        return [
            'total_revenue' => $query->sum('pembayaran.jumlah'),
            'total_transactions' => $query->count(),
            'avg_transaction' => $query->avg('pembayaran.jumlah'),
            'daily_breakdown' => $query
                ->selectRaw('
                    DATE(booking.tanggal_booking) as date,
                    SUM(pembayaran.jumlah) as revenue,
                    COUNT(DISTINCT booking.booking_id) as transactions
                ')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];
    }

    private function getServicesData($branch, $dateRange)
    {
        $query = DB::table('booking_detail')
            ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
            ->join('layanan', 'layanan_cabang.layanan_id', '=', 'layanan.layanan_id')
            ->join('booking', 'booking_detail.booking_id', '=', 'booking.booking_id')
            ->where('booking.status', 'selesai')
            ->whereBetween('booking.tanggal_booking', [$dateRange['start'], $dateRange['end']]);

        if ($branch !== 'Semua') {
            $cabangId = Cabang::where('nama_cabang', 'LIKE', "%$branch%")->first()?->cabang_id;
            if ($cabangId) {
                $query->where('layanan_cabang.cabang_id', $cabangId);
            }
        }

        return $query->select('layanan.nama_layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('layanan.nama_layanan')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    private function getEmployeesData($branch, $dateRange)
    {
        $query = DB::table('pegawai')
            ->join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->join('cabang', 'pegawai.cabang_id', '=', 'cabang.cabang_id')
            ->leftJoin('booking_detail', 'pegawai.pegawai_id', '=', 'booking_detail.pegawai_id')
            ->leftJoin('booking', 'booking_detail.booking_id', '=', 'booking.booking_id')
            ->whereIn('users.role', ['pegawai', 'admin'])
            ->where('pegawai.status_kerja', 'aktif')

            ->where(function ($q) use ($dateRange) {
                $q->whereNull('booking.booking_id')
                ->orWhereBetween('booking.tanggal_booking', [
                    $dateRange['start'],
                    $dateRange['end']
                ]);
            });

        if ($branch !== 'Semua') {
            $cabangId = Cabang::where('nama_cabang', 'LIKE', "%$branch%")->first()?->cabang_id;
            if ($cabangId) {
                $query->where('pegawai.cabang_id', $cabangId);
            }
        }

        return $query->select(
                'pegawai.pegawai_id',
                'users.nama as nama_pegawai',
                'users.role',
                'cabang.nama_cabang',
                DB::raw('COUNT(DISTINCT booking.booking_id) as total_booking'),
                DB::raw('COUNT(DISTINCT booking_detail.booking_detail_id) as total_services'),
            )
            ->groupBy('pegawai.pegawai_id', 'users.nama', 'users.role', 'cabang.nama_cabang')
            ->orderByDesc('total_booking')
            ->get();
    }

    private function getCustomersData($branch, $dateRange)
    {
        $cabangCondition = '';
        $bindings = [];
        
        if ($branch !== 'Semua') {
            $cabangId = Cabang::where('nama_cabang', 'LIKE', "%$branch%")->first()?->cabang_id;
            if ($cabangId) {
                $cabangCondition = "AND EXISTS (
                    SELECT 1 FROM booking_detail bd 
                    JOIN layanan_cabang lc ON bd.layanan_cabang_id = lc.layanan_cabang_id 
                    WHERE bd.booking_id = booking.booking_id 
                    AND lc.cabang_id = ?
                )";
                $bindings[] = $cabangId;
            }
        }

        // Total Customers
        $total_customers = DB::selectOne("
            SELECT COUNT(DISTINCT pelanggan.pelanggan_id) as total 
            FROM booking 
            INNER JOIN pelanggan ON booking.pelanggan_id = pelanggan.pelanggan_id 
            WHERE booking.tanggal_booking BETWEEN ? AND ? 
            {$cabangCondition}
        ", array_merge([$dateRange['start'], $dateRange['end']], $bindings))->total;

        // New Customers
        $new_customers = DB::selectOne("
            SELECT COUNT(DISTINCT pelanggan.pelanggan_id) as total 
            FROM booking 
            INNER JOIN pelanggan ON booking.pelanggan_id = pelanggan.pelanggan_id 
            INNER JOIN users ON pelanggan.user_id = users.user_id 
            WHERE booking.tanggal_booking BETWEEN ? AND ? 
            AND users.created_at >= ? 
            {$cabangCondition}
        ", array_merge([$dateRange['start'], $dateRange['end'], $dateRange['start']], $bindings))->total;

        // Repeat
        $repeat_customers = DB::selectOne("
            SELECT COUNT(*) as total 
            FROM (
                SELECT pelanggan.pelanggan_id
                FROM booking 
                INNER JOIN pelanggan ON booking.pelanggan_id = pelanggan.pelanggan_id 
                INNER JOIN users ON pelanggan.user_id = users.user_id 
                WHERE booking.tanggal_booking BETWEEN ? AND ? 
                AND users.created_at < ? 
                {$cabangCondition}
                GROUP BY pelanggan.pelanggan_id 
                HAVING COUNT(DISTINCT booking.booking_id) > 1
            ) as repeat_customers
        ", array_merge([$dateRange['start'], $dateRange['end'], $dateRange['start']], $bindings))->total;

        return [
            'total_customers' => (int) $total_customers,
            'new_customers' => (int) $new_customers,
            'repeat_customers' => (int) $repeat_customers
        ];
    }
}