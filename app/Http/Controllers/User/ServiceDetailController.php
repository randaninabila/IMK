<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ServiceDetailController extends Controller
{
    public function show($jenis_layanan_id)
    {
        // Data jenis layanan (hero section)
        $jenisLayanan = DB::table('jenis_layanan')
            ->where('jenis_layanan_id', $jenis_layanan_id)
            ->firstOrFail();

        // Layanan + harga cabang + foto album
        $layananList = DB::table('layanan as l')
            ->join('layanan_cabang as lc', 'l.layanan_id', '=', 'lc.layanan_id')
            ->leftJoin('album as a', 'a.layanan_id', '=', 'l.layanan_id')
            ->leftJoin('album_foto as af', function ($join) {
                $join->on('af.album_id', '=', 'a.album_id')
                    ->whereRaw('af.foto_id = (
                        SELECT MIN(foto_id) FROM album_foto
                        WHERE album_id = a.album_id
                    )');
            })
            ->where('l.jenis_layanan_id', $jenis_layanan_id)
            ->where('lc.cabang_id', 1)
            ->where('lc.status', 'tersedia')
            ->select(
                'l.layanan_id',
                'l.nama_layanan',
                'l.deskripsi',
                'l.durasi',
                'l.kategori_pelanggan',
                'lc.layanan_cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'af.url_foto'
            )
            ->get();

        // Paket yang relevan dengan jenis layanan ini
        $paketList = DB::table('paket_layanan as pl')
            ->join('paket_detail as pd', 'pl.paket_id', '=', 'pd.paket_id')
            ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
            ->where('l.jenis_layanan_id', $jenis_layanan_id)
            ->select('pl.paket_id', 'pl.nama_paket', 'pl.deskripsi')
            ->distinct()
            ->get();

        return view('user.service.sdetail', compact('jenisLayanan', 'layananList', 'paketList'));
    }
}