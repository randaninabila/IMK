<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index()
    {
        // Ambil data salon
        $salon = DB::table('salon')->first();
        
        // Ambil cabang yang buka
        $cabangList = DB::table('cabang')
            ->where('status', 'BUKA')
            ->orderBy('nama_cabang')
            ->get();
        
        // Hitung rata-rata rating
        $avgRating = round(DB::table('ulasan')->avg('rating') ?? 4.9, 1);
        
        // ✅ AMBIL DATA PROMO AKTIF
        // 1. Promo dari PAKET
        $promoPaket = DB::table('paket_cabang as pc')
            ->join('paket_layanan as pl', 'pc.paket_id', '=', 'pl.paket_id')
            ->join('cabang as c', 'pc.cabang_id', '=', 'c.cabang_id')
            ->where('pc.harga_promo', '>', 0)
            ->where('pc.status', 'tersedia')
            ->select(
                'pl.paket_id as id',
                'pl.nama_paket as nama',
                'pc.harga_normal',
                'pc.harga_promo',
                'c.nama_cabang as cabang',
                DB::raw("'Paket' as kategori"),
                DB::raw("ROUND(((pc.harga_normal - pc.harga_promo) / pc.harga_normal) * 100) as diskon")
            )
            ->get();

        // 2. Promo dari LAYANAN
        $promoLayanan = DB::table('layanan_cabang as lc')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('lc.harga_promo', '>', 0)
            ->where('lc.status', 'tersedia')
            ->select(
                'l.layanan_id as id',
                'l.nama_layanan as nama',
                'lc.harga as harga_normal',
                'lc.harga_promo',
                'c.nama_cabang as cabang',
                DB::raw("'Layanan' as kategori"),
                DB::raw("ROUND(((lc.harga - lc.harga_promo) / lc.harga) * 100) as diskon")
            )
            ->get();

        // Gabungkan + urutkan berdasarkan diskon terbesar
        $promos = $promoPaket->merge($promoLayanan)
            ->sortByDesc('diskon')
            ->values();

        // ✅ KIRIM SEMUA VARIABEL KE VIEW
        return view('user.home', compact(
            'salon',
            'cabangList',
            'avgRating',
            'promos'  // ✅ Pastikan ini dikirim
        ));
    }
}