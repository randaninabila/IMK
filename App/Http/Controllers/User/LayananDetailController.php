<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LayananDetailController extends Controller
{
    public function show($layanan_id)
    {
        $layanan = DB::table('layanan as l')
            ->join('layanan_cabang as lc', 'l.layanan_id', '=', 'lc.layanan_id')
            ->where('l.layanan_id', $layanan_id)
            ->where('lc.cabang_id', 1)
            ->select(
                'l.*',
                'lc.harga',
                'lc.harga_promo',
                'lc.layanan_cabang_id'
            )
            ->first();

        if (!$layanan) {
            abort(404);
        }

        $jenisLayanan = DB::table('jenis_layanan')
            ->where('jenis_layanan_id', $layanan->jenis_layanan_id)
            ->first();

        $layananList = collect([$layanan]);

        $paketList = collect();

        return view('user.service.detail', compact(
            'layanan',
            'layananList',
            'jenisLayanan',
            'paketList'
        ));
    }
}