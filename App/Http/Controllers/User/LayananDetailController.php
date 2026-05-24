<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LayananDetailController extends Controller
{
    public function show($layanan_id)
    {
        $layanan = DB::table('layanan as l')
            ->leftJoin('layanan_cabang as lc', function($join) {
                $join->on('l.layanan_id', '=', 'lc.layanan_id')
                     ->where('lc.cabang_id', 1);
            })
            ->where('l.layanan_id', $layanan_id)
            ->select('l.*', 'lc.harga', 'lc.harga_promo', 'lc.layanan_cabang_id')
            ->first();

        if (!$layanan) {
            abort(404);
        }

        // Fix path cover foto
        $layanan->cover_foto = !empty($layanan->cover_foto)
            ? asset('layanan/' . basename($layanan->cover_foto))
            : asset('layanan/default.jpg');

        $jenisLayanan = DB::table('jenis_layanan')
            ->where('jenis_layanan_id', $layanan->jenis_layanan_id)
            ->first();

        // Ambil semua foto album (before, after, result, catalog)
        $album = DB::table('album')
            ->where('layanan_id', $layanan_id)
            ->first();

        $albumFotos = collect();
        if ($album) {
            $albumFotos = DB::table('album_foto')
                ->where('album_id', $album->album_id)
                ->orderByRaw("FIELD(tipe, 'cover', 'before', 'after', 'result', 'catalog')")
                ->get()
                ->map(function ($foto) {
                    // foto ada di public/album/
                    $foto->url_foto = asset('album/' . basename($foto->url_foto));
                    return $foto;
                });
        }

        // Kelompokkan foto berdasarkan tipe
        $fotoByTipe = $albumFotos->groupBy('tipe');

        // Ulasan layanan ini
        $ulasan = DB::table('ulasan as u')
            ->join('booking as b', 'u.booking_id', '=', 'b.booking_id')
            ->join('booking_detail as bd', 'b.booking_id', '=', 'bd.booking_id')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('pelanggan as pl', 'u.pelanggan_id', '=', 'pl.pelanggan_id')
            ->join('users as usr', 'pl.user_id', '=', 'usr.user_id')
            ->where('lc.layanan_id', $layanan_id)
            ->whereNotNull('u.rating')
            ->select(
                'u.rating',
                'u.komentar',
                'u.created_at',
                'usr.nama as nama_pelanggan'
            )
            ->distinct()
            ->orderByDesc('u.created_at')
            ->limit(6)
            ->get();

        $avgRating = $ulasan->avg('rating');

        return view('user.service.detail', compact(
            'layanan',
            'jenisLayanan',
            'albumFotos',
            'fotoByTipe',
            'ulasan',
            'avgRating'
        ));
    }
}