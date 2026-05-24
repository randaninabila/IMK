<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create($layanan_cabang_id)
    {
        $layanan = DB::table('layanan_cabang as lc')
            ->join('layanan as l', 'lc.layanan_id', '=', 'l.layanan_id')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('lc.layanan_cabang_id', $layanan_cabang_id)
            ->select(
                'lc.layanan_cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'l.nama_layanan',
                'l.durasi',
                'l.cover_foto',
                'c.nama_cabang',
                'c.alamat'
            )
            ->first();

        if (!$layanan) {
            abort(404);
        }

        $layanan->cover_foto = !empty($layanan->cover_foto)
            ? asset('layanan/' . basename($layanan->cover_foto))
            : asset('layanan/default.jpg');

        return view('user.booking.create', compact('layanan'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}