<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $salon      = $this->getSalon();
        $cabangList = $this->getCabangList();
        $avgRating  = $this->getAvgRating();
        $promos     = $this->getPromos();
        $totalPromo = $promos->count();
        $maxDiskon  = $promos->max('diskon') ?? 0;

        return view('user.home.home', compact(
            'salon',
            'cabangList',
            'avgRating',
            'promos',
            'totalPromo',
            'maxDiskon'
        ));

        $cabangList = DB::table('cabang')
        ->where('status', 'BUKA')
        ->orderBy('nama_cabang')
        ->get();

    // ... kode query promos sebelumnya ...

        return view('user.home', compact(
            'salon',
            'cabangList', // ✅ Kirim data cabang ke view
            'avgRating',
            'promos',
            'totalPromo',
            'maxDiskon'
        ));
    }
    

    /**
     * Data salon utama (tabel salon, ambil baris pertama).
     */
    private function getSalon()
    {
        return DB::table('salon')->first();
    }

    /**
     * Semua cabang aktif.
     * Kolom status di tabel cabang: 'aktif' / 'nonaktif'
     */
    private function getCabangList()
    {
        return DB::table('cabang')
            ->where('status', 'aktif')
            ->orderBy('cabang_id')
            ->get();
    }

    /**
     * Rata-rata rating dari tabel ulasan.
     * Dikembalikan dalam format "4.8" atau "0.0" jika belum ada ulasan.
     */
    private function getAvgRating(): string
    {
        $avg = DB::table('ulasan')->avg('rating');
        return $avg ? number_format((float) $avg, 1) : '0.0';
    }

    /**
     * Ambil semua layanan yang sedang promo (harga_promo > 0 dan status tersedia).
     * Diurutkan berdasarkan persentase diskon terbesar.
     *
     * Kolom yang dikembalikan (dipakai di blade):
     *   - nama        : nama layanan
     *   - harga_normal: harga asli
     *   - harga_promo : harga setelah diskon
     *   - cabang      : nama cabang
     *   - diskon      : persentase diskon (integer)
     *   - kategori    : jenis layanan (dari tabel jenis_layanan)
     */
    private function getPromos()
    {
        return DB::table('layanan_cabang as lc')
            ->join('layanan as l',        'l.layanan_id',        '=', 'lc.layanan_id')
            ->join('jenis_layanan as jl', 'jl.jenis_layanan_id', '=', 'l.jenis_layanan_id')
            ->join('cabang as c',         'c.cabang_id',         '=', 'lc.cabang_id')
            ->where('lc.harga_promo', '>', 0)
            ->where('lc.status', 'tersedia')
            ->select(
                'l.layanan_id as id',
                'l.nama_layanan as nama',
                'lc.harga as harga_normal',
                'lc.harga_promo',
                'c.nama_cabang as cabang',
                'jl.nama_jenis as kategori',
                DB::raw('ROUND(((lc.harga - lc.harga_promo) / lc.harga) * 100) as diskon')
            )
            ->orderByDesc('diskon')
            ->get();
    }
}