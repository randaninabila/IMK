<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ServiceDetailController extends Controller
{
    public function index()
    {
        $jenisLayanan = DB::table('jenis_layanan')->orderBy('jenis_layanan_id')->get();
        $covers = [];

        foreach ($jenisLayanan as $jenis) {
            $layanan = DB::table('layanan')
                ->where('jenis_layanan_id', $jenis->jenis_layanan_id)
                ->whereNotNull('cover_foto')
                ->where('cover_foto', '!=', '')
                ->first();
            $covers[$jenis->jenis_layanan_id] = $layanan
                ? asset('layanan/' . basename($layanan->cover_foto))
                : asset('layanan/default.jpg');
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
                'l.layanan_id', 'l.nama_layanan', 'l.deskripsi', 'l.durasi',
                'l.kategori_pelanggan', 'l.cover_foto',
                'lc.layanan_cabang_id', 'lc.harga', 'lc.harga_promo'
            )
            ->get()
            ->map(function ($item) {
                $item->cover_foto = !empty($item->cover_foto)
                    ? asset('layanan/' . basename($item->cover_foto))
                    : asset('layanan/default.jpg');
                return $item;
            });

        $paketList = DB::table('paket_layanan as pl')
            ->join('paket_detail as pd', 'pl.paket_id', '=', 'pd.paket_id')
            ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
            ->where('l.jenis_layanan_id', $jenis_layanan_id)
            ->select('pl.paket_id', 'pl.nama_paket', 'pl.deskripsi')
            ->distinct()
            ->get();

        return view('user.service.sdetail', compact('jenisLayanan', 'layananList', 'paketList'));
    }

    public function showPaketDetail($jenis_layanan_id, $paket_id)
{
    // Ambil cabang pertama yang tersedia untuk paket ini (tidak hardcoded)
    $paketCabangDefault = DB::table('paket_cabang')
        ->where('paket_id', $paket_id)
        ->where('status', 'tersedia')
        ->first();

    $defaultCabangId = $paketCabangDefault?->cabang_id ?? null;

    // Ambil info paket + harga dari cabang default
    $paket = DB::table('paket_layanan as pl')
        ->leftJoin('paket_cabang as pc', function($join) use ($defaultCabangId) {
            $join->on('pl.paket_id', '=', 'pc.paket_id')
                 ->where('pc.cabang_id', $defaultCabangId);
        })
        ->where('pl.paket_id', $paket_id)
        ->select(
            'pl.paket_id', 'pl.nama_paket', 'pl.deskripsi', 'pl.kategori_pelanggan',
            'pc.harga_normal', 'pc.harga_promo', 'pc.status'
        )
        ->firstOrFail();

    $hargaPaket = $paket->harga_promo > 0 ? $paket->harga_promo : $paket->harga_normal;

    // ✅ leftJoin supaya semua layanan dalam paket tetap keluar
    $layananDalamPaket = DB::table('paket_detail as pd')
        ->join('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
        ->leftJoin('layanan_cabang as lc', function($join) use ($defaultCabangId) {
            $join->on('l.layanan_id', '=', 'lc.layanan_id')
                 ->where('lc.cabang_id', $defaultCabangId);
        })
        ->where('pd.paket_id', $paket_id)
        ->select(
            'l.layanan_id', 'l.nama_layanan', 'l.deskripsi', 'l.durasi',
            'l.kategori_pelanggan', 'l.cover_foto',
            'lc.layanan_cabang_id', 'lc.harga', 'lc.harga_promo'
        )
        ->get()
        ->map(function($item) {
            $item->cover_foto = !empty($item->cover_foto)
                ? asset('layanan/' . basename($item->cover_foto))
                : asset('layanan/default.jpg');
            return $item;
        });

    $totalIndividual = $layananDalamPaket->sum(function($l) {
        return $l->harga_promo > 0 ? $l->harga_promo : $l->harga;
    });

    $hemat = $totalIndividual - $hargaPaket;

    $jenisLayanan = DB::table('jenis_layanan')
        ->where('jenis_layanan_id', $jenis_layanan_id)
        ->first();

    $cabangList = DB::table('cabang')
        ->where('status', 'BUKA')
        ->orderBy('nama_cabang')
        ->get();

    return view('user.service.paket-detail', compact(
        'paket',
        'layananDalamPaket',
        'totalIndividual',
        'hemat',
        'jenisLayanan',
        'hargaPaket',
        'cabangList'
    ));
}
}