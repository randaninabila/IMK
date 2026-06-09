<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PegawaiAdminController extends Controller
{
    public function index(Request $request)
    {
        $branches = $this->getBranches();

        $selectedCabangId = $request->query('cabang_id');

        if ($selectedCabangId !== null) {
            $selectedCabangId = (int) $selectedCabangId;
        }

        if ($selectedCabangId && !$branches->contains('cabang_id', $selectedCabangId)) {
            $selectedCabangId = null;
        }

        $selectedBranch = $selectedCabangId
            ? $branches->firstWhere('cabang_id', $selectedCabangId)
            : null;

        $employeesQuery = DB::table('pegawai as p')
            ->join('users as u', 'u.user_id', '=', 'p.user_id')
            ->leftJoinSub(
                DB::table('cabang')
                    ->select(
                        'cabang_id',
                        DB::raw('MIN(nama_cabang) as nama_cabang')
                    )
                    ->groupBy('cabang_id'),
                'c',
                function ($join) {
                    $join->on('c.cabang_id', '=', 'p.cabang_id');
                }
            )
            ->where('u.role', 'pegawai')
            ->where(function ($query) {
                $query->whereNull('u.status_akun')
                    ->orWhere('u.status_akun', 'aktif');
            })
            ->where(function ($query) {
                $query->whereNull('p.status_kerja')
                    ->orWhere('p.status_kerja', '!=', 'resign');
            });

        if ($selectedCabangId) {
            $employeesQuery->where('p.cabang_id', $selectedCabangId);
        }

        $employees = $employeesQuery
            ->select(
                'p.pegawai_id',
                'p.user_id',
                'p.cabang_id',
                'p.jabatan',
                'p.deskripsi',
                'p.foto',
                'p.status_kerja',
                'u.nama',
                'u.email',
                'u.no_hp',
                'u.foto_profile',
                'u.status_akun',
                'u.role',
                'c.nama_cabang'
            )
            ->orderBy('u.nama', 'asc')
            ->get();

        return view('admin.pegawai.pegawaiadmin', compact(
            'employees',
            'branches',
            'selectedCabangId',
            'selectedBranch'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'cabang_id' => 'required|integer',
            'jabatan' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'status_kerja' => 'required|in:aktif,cuti',
        ]);

        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->exists();

        if ($emailExists) {
            return back()
                ->withInput()
                ->with('error', 'Email pegawai sudah digunakan.');
        }

        $branchExists = DB::table('cabang')
            ->where('cabang_id', $request->cabang_id)
            ->exists();

        if (!$branchExists) {
            return back()
                ->withInput()
                ->with('error', 'Cabang tidak ditemukan.');
        }

        DB::transaction(function () use ($request) {
            $newUserId = (DB::table('users')->max('user_id') ?? 0) + 1;
            $newPegawaiId = (DB::table('pegawai')->max('pegawai_id') ?? 0) + 1;

            $userData = [
                'user_id' => $newUserId,
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make('password123'),
                'no_hp' => $request->no_hp,
                'role' => 'pegawai',
                'status_akun' => $request->status_kerja === 'aktif' ? 'aktif' : 'nonaktif',
            ];

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $userData['email_verified_at'] = null;
            }

            if (Schema::hasColumn('users', 'foto_profile')) {
                $userData['foto_profile'] = null;
            }

            if (Schema::hasColumn('users', 'created_at')) {
                $userData['created_at'] = now();
            }

            if (Schema::hasColumn('users', 'updated_at')) {
                $userData['updated_at'] = now();
            }

            DB::table('users')->insert($userData);

            $pegawaiData = [
                'pegawai_id' => $newPegawaiId,
                'user_id' => $newUserId,
                'cabang_id' => $request->cabang_id,
                'jabatan' => $request->jabatan,
                'deskripsi' => $request->deskripsi,
                'status_kerja' => $request->status_kerja,
            ];

            if (Schema::hasColumn('pegawai', 'foto')) {
                $pegawaiData['foto'] = null;
            }

            if (Schema::hasColumn('pegawai', 'created_at')) {
                $pegawaiData['created_at'] = now();
            }

            if (Schema::hasColumn('pegawai', 'updated_at')) {
                $pegawaiData['updated_at'] = now();
            }

            DB::table('pegawai')->insert($pegawaiData);
        });

        return redirect()
            ->route('admin.pegawai', ['cabang_id' => $request->cabang_id])
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, $pegawai_id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'cabang_id' => 'required|integer',
            'jabatan' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'status_kerja' => 'required|in:aktif,cuti',
        ]);

        $employee = DB::table('pegawai as p')
            ->join('users as u', 'u.user_id', '=', 'p.user_id')
            ->where('p.pegawai_id', $pegawai_id)
            ->where('u.role', 'pegawai')
            ->select('p.*', 'u.role')
            ->first();

        if (!$employee) {
            return back()->with('error', 'Data pegawai tidak ditemukan atau bukan role pegawai.');
        }

        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->where('user_id', '!=', $employee->user_id)
            ->exists();

        if ($emailExists) {
            return back()
                ->withInput()
                ->with('error', 'Email pegawai sudah digunakan oleh akun lain.');
        }

        $branchExists = DB::table('cabang')
            ->where('cabang_id', $request->cabang_id)
            ->exists();

        if (!$branchExists) {
            return back()
                ->withInput()
                ->with('error', 'Cabang tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $employee, $pegawai_id) {
            $userUpdateData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'status_akun' => $request->status_kerja === 'aktif' ? 'aktif' : 'nonaktif',
            ];

            if (Schema::hasColumn('users', 'updated_at')) {
                $userUpdateData['updated_at'] = now();
            }

            DB::table('users')
                ->where('user_id', $employee->user_id)
                ->where('role', 'pegawai')
                ->update($userUpdateData);

            $pegawaiUpdateData = [
                'cabang_id' => $request->cabang_id,
                'jabatan' => $request->jabatan,
                'deskripsi' => $request->deskripsi,
                'status_kerja' => $request->status_kerja,
            ];

            if (Schema::hasColumn('pegawai', 'updated_at')) {
                $pegawaiUpdateData['updated_at'] = now();
            }

            DB::table('pegawai')
                ->where('pegawai_id', $pegawai_id)
                ->update($pegawaiUpdateData);
        });

        return redirect()
            ->route('admin.pegawai', ['cabang_id' => $request->cabang_id])
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($pegawai_id)
    {
        $employee = DB::table('pegawai as p')
            ->join('users as u', 'u.user_id', '=', 'p.user_id')
            ->where('p.pegawai_id', $pegawai_id)
            ->where('u.role', 'pegawai')
            ->select('p.*', 'u.role')
            ->first();

        if (!$employee) {
            return back()->with('error', 'Data pegawai tidak ditemukan atau bukan role pegawai.');
        }

        DB::transaction(function () use ($employee, $pegawai_id) {
            $pegawaiUpdateData = [
                'status_kerja' => 'resign',
            ];

            if (Schema::hasColumn('pegawai', 'updated_at')) {
                $pegawaiUpdateData['updated_at'] = now();
            }

            DB::table('pegawai')
                ->where('pegawai_id', $pegawai_id)
                ->update($pegawaiUpdateData);

            $userUpdateData = [
                'status_akun' => 'nonaktif',
            ];

            if (Schema::hasColumn('users', 'updated_at')) {
                $userUpdateData['updated_at'] = now();
            }

            DB::table('users')
                ->where('user_id', $employee->user_id)
                ->where('role', 'pegawai')
                ->update($userUpdateData);
        });

        return back()->with('success', 'Pegawai berhasil dihapus dari daftar.');
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

                $branch->label = str_contains($namaCabang, 'percut')
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
}