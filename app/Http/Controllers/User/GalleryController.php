<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = DB::table('layanan as l')
            ->leftJoin('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->leftJoin('album as a', 'l.layanan_id', '=', 'a.layanan_id')
            ->leftJoinSub(
                DB::table('album_foto')
                    ->select(
                        'album_id',
                        DB::raw("
                            SUBSTRING_INDEX(
                                GROUP_CONCAT(
                                    url_foto
                                    ORDER BY FIELD(tipe,'cover','before','result','after','catalog')
                                    SEPARATOR '|||'
                                ),
                                '|||', 1
                            ) as cover_foto
                        ")
                    )
                    ->groupBy('album_id'),
                'af',
                'a.album_id',
                '=',
                'af.album_id'
            )
            ->select(
                'l.layanan_id',
                'l.nama_layanan',
                'l.deskripsi as layanan_deskripsi',
                'jl.nama_jenis',
                'a.album_id',
                'a.deskripsi as album_deskripsi',
                'l.cover_foto as layanan_cover',
                DB::raw('af.cover_foto as cover_foto')
            )
            ->orderBy('jl.jenis_layanan_id')
            ->orderBy('l.layanan_id')
            ->get();

        $albums = $albums->map(function ($album) {
            $album->slug = Str::slug($album->nama_layanan ?? 'layanan-' . $album->layanan_id);
            return $album;
        });

        return view('user.gallery.gallery', compact('albums'));
    }


    public function show(string $slug)
    {
        $semua = DB::table('layanan as l')
            ->leftJoin('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->select('l.*', 'jl.nama_jenis')
            ->get();

        $layanan = $semua->first(
            fn($row) => Str::slug($row->nama_layanan) === $slug
        );

        if (! $layanan) {
            abort(404);
        }

        $album = DB::table('album')
            ->where('layanan_id', $layanan->layanan_id)
            ->first();

        $beforeFoto  = null;
        $afterFoto   = null;
        $resultFotos = collect();

        if ($album) {
            $allFotos = DB::table('album_foto as af')
                ->join('album as a', 'af.album_id', '=', 'a.album_id')
                ->where('a.layanan_id', $layanan->layanan_id)
                ->select('af.*')
                ->get()
                ->map(function ($foto) {
                    $foto->url_foto = !empty($foto->url_foto)
                        ? 'storage/' . $foto->url_foto
                        : 'storage/default.jpg';
                    return $foto;
                });

            $beforeFoto  = $allFotos->firstWhere('tipe', 'before');
            $afterFoto   = $allFotos->firstWhere('tipe', 'after');
            $resultFotos = $allFotos->where('tipe', 'result')->values();

            if (!$afterFoto && !empty($layanan->cover_foto)) {
                $afterFoto = (object)['url_foto' => 'storage/' . $layanan->cover_foto];
            }
        }

        $layananCabang = DB::table('layanan_cabang as lc')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('lc.layanan_id', $layanan->layanan_id)
            ->where('lc.status', 'tersedia')
            ->where('c.status', 'BUKA')
            ->select(
                'lc.layanan_cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'lc.status',
                'c.nama_cabang',
                'c.alamat'
            )
            ->orderBy('c.cabang_id')
            ->get();

        $ulasan = DB::table('ulasan as u')
            ->join('booking as b', 'u.booking_id', '=', 'b.booking_id')
            ->join('booking_detail as bd', 'b.booking_id', '=', 'bd.booking_id')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->join('pelanggan as pl', 'u.pelanggan_id', '=', 'pl.pelanggan_id')
            ->join('users as usr', 'pl.user_id', '=', 'usr.user_id')
            ->where('lc.layanan_id', $layanan->layanan_id)
            ->whereNotNull('u.rating')
            ->select(
                'u.ulasan_id',
                'u.rating',
                'u.komentar',
                'u.created_at',
                'usr.nama as nama_pelanggan'
            )
            ->distinct()
            ->orderByDesc('u.created_at')
            ->limit(10)
            ->get();

        $avgRating = $ulasan->avg('rating');

        return view('user.gallery.gdetail', compact(
            'layanan',
            'beforeFoto',
            'afterFoto',
            'resultFotos',
            'layananCabang',
            'avgRating',
            'ulasan'
        ));
    }
}