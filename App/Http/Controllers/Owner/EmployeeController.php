<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $selectedCabang = $request->get('cabang', 'all');
        $selectedMonth  = $request->get('bulan', Carbon::now()->format('Y-m'));
        $perPage        = $request->get('show', 10);

        if ($perPage !== 'all') {
            $perPage = (int) $perPage;
        }

        $cabangs = DB::table('cabang')->where('status', 'BUKA')->get();

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push([
                'value' => $month->format('Y-m'),
                'label' => $month->locale('id')->translatedFormat('F Y'),
            ]);
        }

        $employees = $this->getEmployeePerformance($selectedCabang, $selectedMonth, $perPage);

        $topPerformers = collect(
            $perPage === 'all' ? $employees : $employees->items()
        )
            ->filter(fn($employee) => $employee['total_clients'] > 0)
            ->sortByDesc('total_clients')
            ->take(3);

        return view('owner.employees.employee', compact(
            'employees', 'topPerformers', 'cabangs', 'months',
            'selectedCabang', 'selectedMonth', 'perPage'
        ));
    }

    private function getEmployeePerformance($cabangId, $month, $perPage = 10)
    {
        $parsedMonth = Carbon::parse($month);

        $query = DB::table('pegawai as p')
            ->join('users as u', 'p.user_id', '=', 'u.user_id')
            ->join('cabang as c', 'p.cabang_id', '=', 'c.cabang_id')
            ->leftJoin('booking as b', function ($join) use ($parsedMonth) {
                $join->on('p.pegawai_id', '=', 'b.pegawai_id')
                    ->where('b.status', '=', 'completed')
                    ->whereMonth('b.tanggal_booking', '=', $parsedMonth->month)
                    ->whereYear('b.tanggal_booking', '=', $parsedMonth->year)
                    ->whereExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('pembayaran')
                            ->whereColumn('pembayaran.booking_id', 'b.booking_id')
                            ->where('pembayaran.status', 'verified');
                    });
            })
            ->leftJoin('booking_detail as bd', 'b.booking_id', '=', 'bd.booking_id')
            ->whereIn('u.role', ['pegawai', 'admin'])
            ->where('p.status_kerja', '!=', 'resign')
            ->whereDate('u.created_at', '<=', $parsedMonth->copy()->endOfMonth());

        if ($cabangId != 'all') {
            $query->where('c.cabang_id', $cabangId);
        }

        $query->select(
            'p.pegawai_id',
            'p.status_kerja',
            'u.nama',
            'u.role',
            'u.foto_profile',
            DB::raw("CASE WHEN p.status_kerja = 'aktif' THEN 'tersedia' ELSE 'tidak_tersedia' END as today_status"),
            'c.nama_cabang',
            'c.cabang_id',
            DB::raw('COUNT(DISTINCT b.booking_id) as total_clients'),
            DB::raw('
                SUM(
                    CASE
                        WHEN bd.booking_detail_id IS NULL THEN 0
                        WHEN bd.layanan_cabang_id IS NOT NULL THEN 1
                        WHEN bd.paket_cabang_id IS NOT NULL THEN (
                            SELECT COUNT(*)
                            FROM paket_detail pdt2
                            JOIN paket_cabang pc2 ON pc2.paket_id = pdt2.paket_id
                            WHERE pc2.paket_cabang_id = bd.paket_cabang_id
                        )
                        ELSE 0
                    END
                ) as total_services
            '),
            'u.created_at'
        )->groupBy(
            'p.pegawai_id',
            'p.status_kerja',
            'u.nama',
            'u.role',
            'u.foto_profile',
            'u.created_at',
            'c.nama_cabang',
            'c.cabang_id'
        )->orderByDesc('p.pegawai_id');

        $employees = $perPage === 'all'
            ? $query->get()
            : $query->paginate($perPage)->withQueryString();

        $transform = function ($item) {
            return [
                'pegawai_id'     => $item->pegawai_id,
                'nama'           => $item->nama,
                'foto_profile'   => $item->foto_profile,
                'initial'        => collect(explode(' ', $item->nama))->filter()->take(2)
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode(''),
                'role'           => $item->role,
                'status_kerja'   => $item->status_kerja,
                'today_status'   => $item->today_status,
                'nama_cabang'    => $item->nama_cabang,
                'total_clients'  => $item->role === 'admin' ? '-' : (int) $item->total_clients,
                'total_services' => $item->role === 'admin' ? '-' : (int) $item->total_services,
                'since_joined'   => Carbon::parse($item->created_at)->locale('id')->translatedFormat('F Y'),
            ];
        };

        if ($perPage === 'all') {
            $employees = $employees->map($transform);
        } else {
            $employees->getCollection()->transform($transform);
        }

        return $employees;
    }

    //  EDIT / DIRECTORY PAGE

    public function edit(Request $request)
    {
        $selectedSort       = $request->get('sort', 'clients');
        $selectedDir        = $request->get('dir', 'desc');
        $selectedCabang     = $request->get('cabang', 'all');
        $selectedMonth      = $request->get('bulan', Carbon::now()->format('Y-m'));
        $selectedSortCabang = $request->get('sort_cabang');
        $perPage            = $request->get('show', 10);

        if ($perPage !== 'all') {
            $perPage = (int) $perPage;
        }

        $cabangs = DB::table('cabang')->where('status', 'BUKA')->get();
        $months  = $this->getMonthOptions();

        $employees = $this->getEmployeeLeaderboardData(
            $selectedCabang,
            $selectedMonth,
            $perPage,
            $selectedSort,
            $selectedDir,
            $selectedSortCabang
        );

        $totalEmployees = method_exists($employees, 'total')
            ? $employees->total()
            : $employees->count();

        $activeEmployees = collect(
            method_exists($employees, 'items') ? $employees->items() : $employees
        )->where('today_status', 'tersedia')->count();

        $parsedMonth = Carbon::parse($selectedMonth);

        $cabangStats = $cabangs->map(function ($cabang) use ($parsedMonth) {
            $total = DB::table('pegawai as p')
                ->join('users as u', 'p.user_id', '=', 'u.user_id')
                ->where('p.cabang_id', $cabang->cabang_id)
                ->whereIn('u.role', ['pegawai', 'admin'])
                ->where('p.status_kerja', '!=', 'resign')
                ->count();

            $active = DB::table('pegawai as p')
                ->join('users as u', 'p.user_id', '=', 'u.user_id')
                ->where('p.cabang_id', $cabang->cabang_id)
                ->whereIn('u.role', ['pegawai', 'admin'])
                ->where('p.status_kerja', 'aktif')
                ->count();

            return [
                'cabang_id'   => $cabang->cabang_id,
                'nama_cabang' => $cabang->nama_cabang,
                'total'       => $total,
                'active'      => $active,
                'off_today'   => $total - $active,
            ];
        });

        return view('owner.employees.eemployee', compact(
            'employees', 'cabangs', 'months',
            'selectedCabang', 'selectedMonth',
            'selectedSort', 'selectedDir', 'selectedSortCabang',
            'totalEmployees', 'activeEmployees', 'perPage',
            'cabangStats'
        ));
    }

    private function getEmployeeLeaderboardData(
        $cabangId,
        $month,
        $perPage      = 10,
        $selectedSort = 'clients',
        $dir          = 'desc',
        $sortCabang   = null
    ) {
        $parsedMonth = Carbon::parse($month);
        $dir         = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $query = DB::table('pegawai as p')
            ->join('users as u', 'p.user_id', '=', 'u.user_id')
            ->join('cabang as c', 'p.cabang_id', '=', 'c.cabang_id')
            ->leftJoin('booking as b', function ($join) use ($parsedMonth) {
                $join->on('p.pegawai_id', '=', 'b.pegawai_id')
                    ->where('b.status', '=', 'completed')
                    ->whereMonth('b.tanggal_booking', '=', $parsedMonth->month)
                    ->whereYear('b.tanggal_booking', '=', $parsedMonth->year)
                    ->whereExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('pembayaran')
                            ->whereColumn('pembayaran.booking_id', 'b.booking_id')
                            ->where('pembayaran.status', 'verified');
                    });
            })
            ->leftJoin('booking_detail as bd', 'b.booking_id', '=', 'bd.booking_id')
            ->whereIn('u.role', ['pegawai', 'admin'])
            ->where('p.status_kerja', '!=', 'resign')
            ->whereDate('u.created_at', '<=', $parsedMonth->copy()->endOfMonth());

        if ($cabangId != 'all') {
            $query->where('c.cabang_id', $cabangId);
        }

        $cabangList = DB::table('cabang')->where('status', 'BUKA')->get();

        $dynamicCabangSelect = [];
        foreach ($cabangList as $cabang) {
            $dynamicCabangSelect[] = DB::raw("
                COUNT(DISTINCT CASE WHEN c.cabang_id = {$cabang->cabang_id} THEN b.booking_id ELSE NULL END)
                as cabang{$cabang->cabang_id}_clients
            ");
        }

        $totalServicesExpr = DB::raw('
            SUM(
                CASE
                    WHEN bd.booking_detail_id IS NULL THEN 0
                    WHEN bd.layanan_cabang_id IS NOT NULL THEN 1
                    WHEN bd.paket_cabang_id IS NOT NULL THEN (
                        SELECT COUNT(*)
                        FROM paket_detail pdt2
                        JOIN paket_cabang pc2 ON pc2.paket_id = pdt2.paket_id
                        WHERE pc2.paket_cabang_id = bd.paket_cabang_id
                    )
                    ELSE 0
                END
            ) as total_services
        ');

        $query->select(array_merge([
            'p.pegawai_id',
            'p.status_kerja',
            'u.nama',
            'u.role',
            'u.foto_profile',
            'u.created_at',
            DB::raw("CASE WHEN p.status_kerja = 'aktif' THEN 'tersedia' ELSE 'tidak_tersedia' END as today_status"),
            'c.nama_cabang',
            'c.cabang_id',
            DB::raw('COUNT(DISTINCT b.booking_id) as total_clients'),
            $totalServicesExpr,
        ], $dynamicCabangSelect))
            ->groupBy(
                'p.pegawai_id',
                'p.status_kerja',
                'u.nama',
                'u.role',
                'u.foto_profile',
                'u.created_at',
                'c.nama_cabang',
                'c.cabang_id'
            );

        // APPLY SORT
        switch ($selectedSort) {
            case 'employee':
                $query->orderBy('u.nama', $dir);
                break;
            case 'services':
                $query->orderBy('total_services', $dir);
                break;
            case 'since':
                $query->orderBy('u.created_at', $dir);
                break;
            case 'clients':
            default:
                if ($cabangId === 'all' && $sortCabang && is_numeric($sortCabang)) {
                    $sortCabangId = (int) $sortCabang;
                    $dirSql       = strtoupper($dir);
                    $query->orderByRaw("cabang{$sortCabangId}_clients {$dirSql}");
                } else {
                    $query->orderBy('total_clients', $dir);
                }
                break;
        }

        $employees = $perPage === 'all'
            ? $query->get()
            : $query->paginate($perPage)->withQueryString();

        $transformData = function ($item) use ($cabangList) {
            $initial = collect(explode(' ', $item->nama))->filter()->take(2)
                ->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('');

            return [
                'pegawai_id'     => $item->pegawai_id,
                'nama'           => $item->nama,
                'foto_profile'   => $item->foto_profile,
                'initial'        => $initial,
                'role'           => $item->role,
                'status_kerja'   => $item->status_kerja,
                'today_status'   => $item->today_status,
                'nama_cabang'    => $item->nama_cabang,
                'cabang_id'      => $item->cabang_id,
                'total_clients'  => $item->role === 'admin' ? '-' : (int) $item->total_clients,
                'total_services' => $item->role === 'admin' ? '-' : (int) $item->total_services,
                'since_joined'   => Carbon::parse($item->created_at)->locale('id')->translatedFormat('F Y'),
                'created_at_raw' => $item->created_at,
                'branches'       => $cabangList->mapWithKeys(function ($cabang) use ($item) {
                    return [$cabang->cabang_id => [
                        'clients' => $item->{'cabang' . $cabang->cabang_id . '_clients'} ?? 0,
                    ]];
                }),
                'selected_clients' => (int) $item->total_clients,
            ];
        };

        if ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $employees->setCollection($employees->getCollection()->map($transformData));
        } else {
            $employees = $employees->map($transformData);
        }

        return $employees;
    }

    //  CRUD

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:100', 'unique:users,email'],
            'no_hp'     => ['required', 'max:20', 'unique:users,no_hp', 'regex:/^[0-9+\-\s]+$/'],
            'role'      => ['required', 'in:pegawai,admin'],
            'cabang_id' => ['required', 'exists:cabang,cabang_id'],
        ], [
            'email.unique'     => 'Email sudah digunakan.',
            'no_hp.unique'     => 'Nomor HP sudah digunakan.',
            'no_hp.regex'      => 'Format nomor HP tidak valid.',
            'cabang_id.exists' => 'Cabang tidak ditemukan.',
        ]);

        DB::beginTransaction();
        try {
            $phone         = preg_replace('/[^0-9+]/', '', $validated['no_hp']);
            $plainPassword = Str::slug($validated['nama'], '.');

            $userId = DB::table('users')->insertGetId([
                'nama'              => trim($validated['nama']),
                'email'             => strtolower(trim($validated['email'])),
                'password'          => Hash::make($plainPassword),
                'no_hp'             => $phone,
                'role'              => $validated['role'],
                'status_akun'       => 'aktif',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('pegawai')->insert([
                'user_id'      => $userId,
                'cabang_id'    => $validated['cabang_id'],
                'status_kerja' => 'aktif',
            ]);

            DB::commit();

            return redirect()
                ->route('owner.employee.edit', [
                    'cabang' => request('cabang', 'all'),
                    'bulan'  => request('bulan', Carbon::now()->format('Y-m')),
                ])
                ->with('success', 'Pegawai berhasil ditambahkan. Password default: ' . $plainPassword);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal tambah employee', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menambahkan pegawai.');
        }
    }

    public function updateTodayStatus(Request $request, $pegawai_id)
    {
        $request->validate(['status_ketersediaan' => 'required|in:tersedia,tidak_tersedia']);

        $statusKerja = $request->status_ketersediaan === 'tersedia' ? 'aktif' : 'cuti';

        DB::beginTransaction();
        try {
            $pegawai = DB::table('pegawai')->where('pegawai_id', $pegawai_id)->first();
            if (!$pegawai) {
                return redirect()->back()->with('error', 'Pegawai tidak ditemukan.');
            }

            DB::table('pegawai')
                ->where('pegawai_id', $pegawai_id)
                ->update(['status_kerja' => $statusKerja]);

            DB::commit();
            return redirect()->back()->with('success', 'Status hari ini berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status hari ini.');
        }
    }

    public function updateRole(Request $request, $pegawai_id)
    {
        $request->validate(['role' => 'required|in:pegawai,admin']);

        DB::beginTransaction();
        try {
            $pegawai = DB::table('pegawai')->where('pegawai_id', $pegawai_id)->first();
            if (!$pegawai) return redirect()->back()->with('error', 'Pegawai tidak ditemukan');

            DB::table('users')->where('user_id', $pegawai->user_id)
                ->update(['role' => $request->role, 'updated_at' => now()]);

            DB::commit();
            return redirect()->back()->with('success', 'Role berhasil diupdate');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui peran');
        }
    }

    public function resign($pegawai_id)
    {
        DB::beginTransaction();
        try {
            $pegawai = DB::table('pegawai')->where('pegawai_id', $pegawai_id)->first();
            if (!$pegawai) return redirect()->back()->with('error', 'Pegawai tidak ditemukan');

            DB::table('pegawai')
                ->where('pegawai_id', $pegawai_id)
                ->update(['status_kerja' => 'resign']);

            DB::commit();
            return redirect()->back()->with('success', 'Pegawai berhasil diresign');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengeluarkan pegawai');
        }
    }

    public function editEmployee($pegawai_id)
    {
        $pegawai = DB::table('pegawai')
            ->join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->where('pegawai_id', $pegawai_id)
            ->first();

        if (!$pegawai) return redirect()->back()->with('error', 'Pegawai tidak ditemukan');

        $cabangs = DB::table('cabang')->where('status', 'BUKA')->get();
        return view('owner.employees.edit-employee', compact('pegawai', 'cabangs'));
    }

    public function updateEmployee(Request $request, $pegawai_id)
    {
        $pegawai = DB::table('pegawai')->where('pegawai_id', $pegawai_id)->first();
        if (!$pegawai) return redirect()->back()->with('error', 'Pegawai tidak ditemukan');

        $userId = $pegawai->user_id;

        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:users,email,' . $userId . ',user_id',
            'no_hp'    => 'required|max:20|unique:users,no_hp,' . $userId . ',user_id|regex:/^[0-9+\-\s]+$/',
            'role'     => 'required|in:pegawai,admin',
            'password' => 'nullable|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'nama'       => trim($validated['nama']),
                'email'      => strtolower(trim($validated['email'])),
                'no_hp'      => preg_replace('/[^0-9+]/', '', $validated['no_hp']),
                'role'       => $validated['role'],
                'updated_at' => now(),
            ];
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            DB::table('users')->where('user_id', $userId)->update($updateData);
            DB::commit();
            return redirect()->route('owner.employee.edit')->with('success', 'Pegawai berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pegawai');
        }
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
}