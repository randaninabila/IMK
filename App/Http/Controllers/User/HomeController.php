<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Pastikan Carbon di-import untuk real-time jam

class HomeController extends Controller
{
    public function index()
    {
        $salon      = $this->getSalon();
        $cabangList = $this->getCabangList(); // Mengambil cabang dengan status real-time
        $avgRating  = $this->getAvgRating();
        $promos     = $this->getPromos();
        $totalPromo = $promos->count();
        $maxDiskon  = $promos->max('diskon') ?? 0;

        // Return tunggal ke view home kamu yang benar
        return view('user.home.home', compact(
            'salon',
            'cabangList',
            'avgRating',
            'promos',
            'totalPromo',
            'maxDiskon'
        ));
    }

    /**
     * Mengambil data salon utama
     */
    private function getSalon()
    {
        return DB::table('salon')->first();
    }

    /**
     * Ambil semua cabang dan tentukan status buka/tutup secara real-time
     */
    private function getCabangList()
    {
        // 1. Ambil data cabang yang master statusnya 'BUKA' di database
        $cabangs = DB::table('cabang')
            ->where('status', 'BUKA')
            ->orderBy('nama_cabang')
            ->get();

        // 2. Dapatkan waktu saat ini di zona WIB (Asia/Jakarta)
        $waktuSekarang = Carbon::now('Asia/Jakarta');
        $jamSekarang   = $waktuSekarang->format('H:i:s');

        // 3. Modifikasi data tiap cabang untuk menentukan status operasional real-time-nya
        $cabangList = $cabangs->map(function($cabang) use ($jamSekarang) {
            // Jam operasional default salon (09:00:00 s/d 19:00:00)
            $jamBuka  = '09:00:00'; 
            $jamTutup = '19:00:00';

            // Cek apakah jam sekarang berada di dalam rentang jam operasional
            $cabang->is_buka_realtime = ($jamSekarang >= $jamBuka && $jamSekarang <= $jamTutup);
            
            return $cabang;
        });

        return $cabangList;
    }

    /**
     * Menghitung rata-rata rating ulasan
     */
    private function getAvgRating()
    {
        $avg = DB::table('ulasan')->avg('rating');
        return $avg ? number_format((float) $avg, 1) : '0.0';
    }

    /**
     * Mengambil daftar layanan yang sedang promo
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
                'lc.layanan_cabang_id',
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