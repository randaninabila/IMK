<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InputPromoAdminController extends Controller
{
    private function promoKey(int $cabangId, string $field): string
    {
        return 'dina_salon_active_promo_' . $field . '_cabang_' . $cabangId;
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

                $branch->label = ((int) $branch->cabang_id === 2 || str_contains($namaCabang, 'percut'))
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

    private function serviceQuery()
    {
        return DB::table('layanan_cabang as lc')
            ->join('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->leftJoin('jenis_layanan as jl', 'jl.jenis_layanan_id', '=', 'l.jenis_layanan_id')
            ->leftJoin('cabang as c', 'c.cabang_id', '=', 'lc.cabang_id')
            ->select(
                'lc.layanan_cabang_id',
                'lc.layanan_id',
                'lc.cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'lc.status',
                'l.nama_layanan',
                'l.jenis_layanan_id',
                'l.durasi',
                'l.cover_foto',
                'jl.nama_jenis',
                DB::raw('MIN(c.nama_cabang) as nama_cabang')
            )
            ->groupBy(
                'lc.layanan_cabang_id',
                'lc.layanan_id',
                'lc.cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'lc.status',
                'l.nama_layanan',
                'l.jenis_layanan_id',
                'l.durasi',
                'l.cover_foto',
                'jl.nama_jenis'
            );
    }

    private function getServiceById($layananCabangId)
    {
        return $this->serviceQuery()
            ->where('lc.layanan_cabang_id', $layananCabangId)
            ->first();
    }

    private function getCacheValue(string $key)
    {
        if (!Schema::hasTable('cache')) {
            return session($key);
        }

        $cache = DB::table('cache')
            ->where('key', $key)
            ->first();

        if (!$cache) {
            return null;
        }

        if ((int) $cache->expiration > 0 && (int) $cache->expiration < time()) {
            DB::table('cache')
                ->where('key', $key)
                ->delete();

            return null;
        }

        return $cache->value;
    }

    private function setCacheValue(string $key, string $value): void
    {
        if (!Schema::hasTable('cache')) {
            session([$key => $value]);
            return;
        }

        DB::table('cache')->updateOrInsert(
            ['key' => $key],
            [
                'value' => $value,
                'expiration' => now()->addYears(5)->timestamp,
            ]
        );
    }

    private function deleteCacheValue(string $key): void
    {
        if (!Schema::hasTable('cache')) {
            session()->forget($key);
            return;
        }

        DB::table('cache')
            ->where('key', $key)
            ->delete();
    }

    private function getActivePromo(int $cabangId)
    {
        $activePromoId = $this->getCacheValue($this->promoKey($cabangId, 'layanan_cabang_id'));

        if (!$activePromoId) {
            return null;
        }

        $activePromo = $this->getServiceById((int) $activePromoId);

        if (!$activePromo || (int) $activePromo->cabang_id !== (int) $cabangId) {
            return null;
        }

        $activePromo->judul_promo = $this->getCacheValue($this->promoKey($cabangId, 'judul'))
            ?: 'Promo ' . $activePromo->nama_layanan;

        $activePromo->deskripsi_promo = $this->getCacheValue($this->promoKey($cabangId, 'deskripsi'))
            ?: 'Harga spesial untuk layanan ' . $activePromo->nama_layanan . ' di ' . $activePromo->nama_cabang . '.';

        return $activePromo;
    }

    public function index(Request $request)
    {
        $branches = $this->getBranches();

        $selectedCabangId = (int) $request->query('cabang_id', $branches->first()->cabang_id ?? 1);

        if (!$branches->contains('cabang_id', $selectedCabangId)) {
            $selectedCabangId = (int) ($branches->first()->cabang_id ?? 1);
        }

        $selectedBranch = $branches->firstWhere('cabang_id', $selectedCabangId);

        $services = $this->serviceQuery()
            ->where('lc.cabang_id', $selectedCabangId)
            ->where('lc.status', 'tersedia')
            ->orderBy('jl.nama_jenis', 'asc')
            ->orderBy('l.nama_layanan', 'asc')
            ->get();

        $promoServices = $services
            ->filter(function ($service) {
                return $service->harga_promo !== null && (float) $service->harga_promo > 0;
            })
            ->values();

        $activePromo = $this->getActivePromo($selectedCabangId);

        $requestedServiceId = $request->query('layanan_cabang_id');

        if ($requestedServiceId) {
            $selectedService = $promoServices->firstWhere('layanan_cabang_id', (int) $requestedServiceId);
        } elseif ($activePromo) {
            $selectedService = $promoServices->firstWhere('layanan_cabang_id', (int) $activePromo->layanan_cabang_id);
        } else {
            $selectedService = $promoServices->first();
        }

        return view('admin.inputpromo.inputpromo', compact(
            'branches',
            'selectedCabangId',
            'selectedBranch',
            'services',
            'promoServices',
            'selectedService',
            'activePromo'
        ));
    }

    public function activate(Request $request)
    {
        $request->validate([
            'layanan_cabang_id' => 'required|integer',
            'judul_promo' => 'required|string|max:100',
            'deskripsi_promo' => 'required|string|max:500',
        ]);

        $service = $this->getServiceById($request->layanan_cabang_id);

        if (!$service) {
            return back()->withInput()->with('error', 'Promo layanan tidak ditemukan.');
        }

        if (!$service->harga_promo || (float) $service->harga_promo <= 0) {
            return back()->withInput()->with('error', 'Layanan ini belum punya harga promo di database.');
        }

        $cabangId = (int) $service->cabang_id;

        $this->setCacheValue($this->promoKey($cabangId, 'layanan_cabang_id'), (string) $service->layanan_cabang_id);
        $this->setCacheValue($this->promoKey($cabangId, 'judul'), $request->judul_promo);
        $this->setCacheValue($this->promoKey($cabangId, 'deskripsi'), $request->deskripsi_promo);

        return redirect()
            ->route('admin.inputpromo', [
                'cabang_id' => $cabangId,
                'layanan_cabang_id' => $service->layanan_cabang_id,
            ])
            ->with('success', 'Promo berhasil diaktifkan.');
    }

    public function deactivate(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|integer',
        ]);

        $cabangId = (int) $request->cabang_id;

        $this->deleteCacheValue($this->promoKey($cabangId, 'layanan_cabang_id'));
        $this->deleteCacheValue($this->promoKey($cabangId, 'judul'));
        $this->deleteCacheValue($this->promoKey($cabangId, 'deskripsi'));

        return redirect()
            ->route('admin.inputpromo', ['cabang_id' => $cabangId])
            ->with('success', 'Promo berhasil dinonaktifkan.');
    }
}