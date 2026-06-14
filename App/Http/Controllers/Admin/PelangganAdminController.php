<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PelangganAdminController extends Controller
{
    private function getBranches()
    {
        return DB::table('cabang')
            ->select('cabang_id', 'nama_cabang', 'alamat', 'status')
            ->orderBy('cabang_id', 'asc')
            ->get()
            ->map(function ($branch) {
                $branch->label = $branch->nama_cabang;
                return $branch;
            });
    }

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

        $customersQuery = DB::table('pelanggan as pl')
            ->join('users as u', 'u.user_id', '=', 'pl.user_id')
            ->where('u.role', 'pelanggan');

        if ($selectedCabangId) {
            $customersQuery->whereExists(function ($query) use ($selectedCabangId) {
                $query->select(DB::raw(1))
                    ->from('booking as b')
                    ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
                    ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
                    ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')
                    ->whereColumn('b.pelanggan_id', 'pl.pelanggan_id')
                    ->where(function ($branchQuery) use ($selectedCabangId) {
                        $branchQuery->where('lc.cabang_id', $selectedCabangId)
                            ->orWhere('pg.cabang_id', $selectedCabangId);
                    });
            });
        }

        $customers = $customersQuery
            ->select(
                'pl.pelanggan_id',
                'pl.user_id',
                'u.nama',
                'u.email',
                'u.no_hp',
                'u.status_akun',
                'u.created_at',
                DB::raw('(SELECT COUNT(*) FROM booking WHERE booking.pelanggan_id = pl.pelanggan_id) as total_booking')
            )
            ->orderBy('pl.pelanggan_id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pelanggan.pelangganadmin', compact(
            'branches',
            'selectedCabangId',
            'selectedBranch',
            'customers'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'status_akun' => 'required|in:aktif,nonaktif',
            'redirect_cabang_id' => 'nullable|integer',
        ]);

        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->exists();

        if ($emailExists) {
            return back()->with('error', 'Email pelanggan sudah digunakan.');
        }

        DB::transaction(function () use ($request) {
            $newUserId = (DB::table('users')->max('user_id') ?? 0) + 1;
            $newPelangganId = (DB::table('pelanggan')->max('pelanggan_id') ?? 0) + 1;

            $userData = [
                'user_id' => $newUserId,
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make('password123'),
                'no_hp' => $request->no_hp,
                'foto_profile' => null,
                'role' => 'pelanggan',
                'status_akun' => $request->status_akun,
            ];

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $userData['email_verified_at'] = null;
            }

            if (Schema::hasColumn('users', 'created_at')) {
                $userData['created_at'] = now();
            }

            if (Schema::hasColumn('users', 'updated_at')) {
                $userData['updated_at'] = now();
            }

            DB::table('users')->insert($userData);

            $pelangganData = [
                'pelanggan_id' => $newPelangganId,
                'user_id' => $newUserId,
            ];

            if (Schema::hasColumn('pelanggan', 'created_at')) {
                $pelangganData['created_at'] = now();
            }

            if (Schema::hasColumn('pelanggan', 'updated_at')) {
                $pelangganData['updated_at'] = now();
            }

            DB::table('pelanggan')->insert($pelangganData);
        });

        return redirect()
            ->route('admin.pelanggan', [
                'cabang_id' => $request->redirect_cabang_id,
            ])
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, $pelanggan_id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'status_akun' => 'required|in:aktif,nonaktif',
            'redirect_cabang_id' => 'nullable|integer',
        ]);

        $customer = DB::table('pelanggan as pl')
            ->join('users as u', 'u.user_id', '=', 'pl.user_id')
            ->where('pl.pelanggan_id', $pelanggan_id)
            ->where('u.role', 'pelanggan')
            ->select('pl.*', 'u.email')
            ->first();

        if (!$customer) {
            return back()->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->where('user_id', '!=', $customer->user_id)
            ->exists();

        if ($emailExists) {
            return back()->with('error', 'Email pelanggan sudah digunakan oleh akun lain.');
        }

        $updateData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status_akun' => $request->status_akun,
        ];

        if (Schema::hasColumn('users', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('users')
            ->where('user_id', $customer->user_id)
            ->where('role', 'pelanggan')
            ->update($updateData);

        return redirect()
            ->route('admin.pelanggan', [
                'cabang_id' => $request->redirect_cabang_id,
            ])
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Request $request, $pelanggan_id)
    {
        $customer = DB::table('pelanggan as pl')
            ->join('users as u', 'u.user_id', '=', 'pl.user_id')
            ->where('pl.pelanggan_id', $pelanggan_id)
            ->where('u.role', 'pelanggan')
            ->select('pl.*')
            ->first();

        if (!$customer) {
            return back()->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $updateData = [
            'status_akun' => 'nonaktif',
        ];

        if (Schema::hasColumn('users', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('users')
            ->where('user_id', $customer->user_id)
            ->where('role', 'pelanggan')
            ->update($updateData);

        return redirect()
            ->route('admin.pelanggan', [
                'cabang_id' => $request->redirect_cabang_id,
            ])
            ->with('success', 'Pelanggan berhasil dinonaktifkan.');
    }
}