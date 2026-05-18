<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ServiceDetailController extends Controller
{
    public function index()
    {
        // Ambil daftar jenis layanan
        $jenisLayanan = DB::table('jenis_layanan')
            ->orderBy('jenis_layanan_id')
            ->get();

        // Ambil cover foto untuk setiap jenis layanan
        $covers = [];
        foreach ($jenisLayanan as $jenis) {
            $layanan = DB::table('layanan')
                ->where('jenis_layanan_id', $jenis->jenis_layanan_id)
                ->whereNotNull('cover_foto')
                ->where('cover_foto', '!=', '')
                ->first();

            $covers[$jenis->jenis_layanan_id] = $layanan 
                ? asset('storage/' . $layanan->cover_foto) 
                : asset('storage/default.jpg');
        }

        return view('user.service.service', compact('covers', 'jenisLayanan'));
    }

    public function show($jenis_layanan_id)
    {
        $jenisLayanan = DB::table('jenis_layanan')
            ->where('jenis_layanan_id', $jenis_layanan_id)
            ->firstOrFail();

        $layananList = DB::table('layanan as l')
            ->join('layanan_cabang as lc', 'l.layanan_id', '=', 'lc.layanan_id')
            ->where('l.jenis_layanan_id', $jenis_layanan_id)
            ->where('lc.cabang_id', 1)
            ->where('lc.status', 'tersedia')
            ->select(
                'l.layanan_id',
                'l.nama_layanan',
                'l.deskripsi',
                'l.durasi',
                'l.kategori_pelanggan',
                'l.cover_foto',
                'lc.layanan_cabang_id',
                'lc.harga',
                'lc.harga_promo'
            )
            ->get()
            ->map(function ($item) {
                $item->cover_foto = !empty($item->cover_foto)
                    ? 'storage/' . $item->cover_foto
                    : 'storage/default.jpg';
                return $item;
            });

        $paketList = DB::table('paket_layanan as pl')
            ->join('paket_detail as pd', 'pl.paket_id', '=', 'pd.paket_id')
            ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
            ->where('l.jenis_layanan_id', $jenis_layanan_id)
            ->select(
                'pl.paket_id',
                'pl.nama_paket',
                'pl.deskripsi'
            )
            ->distinct()
            ->get();

        return view('user.service.sdetail', compact(
            'jenisLayanan',
            'layananList',
            'paketList'
        ));
    }
}