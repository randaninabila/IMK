<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserServiceController extends Controller
{
    // /service — list semua layanan
    public function index()
    {
        $layanan = DB::table('layanan as l')
            ->leftJoin('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->select('l.*', 'jl.nama_jenis', 'jl.jenis_layanan_id')
            ->orderBy('jl.jenis_layanan_id')
            ->orderBy('l.layanan_id')
            ->get();

        $jenisLayanan = DB::table('jenis_layanan')
            ->orderBy('jenis_layanan_id')
            ->get();

        $layanan = $layanan->map(function ($item) {
            $item->slug = Str::slug($item->nama_layanan);
            return $item;
        });

        // Ambil cover foto per jenis layanan
        $covers = [];
        foreach ($jenisLayanan as $jenis) {

            // Cari layanan pertama milik jenis ini
            $layananPertama = $layanan->firstWhere('jenis_layanan_id', $jenis->jenis_layanan_id);
            $coverUrl = null;

            if ($layananPertama) {
                // Coba ambil dari tabel album & album_foto
                $album = DB::table('album')
                    ->where('layanan_id', $layananPertama->layanan_id)
                    ->first();

                if ($album) {
                    $foto = DB::table('album_foto')
                        ->where('album_id', $album->album_id)
                        ->orderByRaw("FIELD(tipe, 'cover', 'before', 'result', 'after', 'catalog')")
                        ->first();

                    if ($foto && !empty($foto->url_foto)) {
                        // Jika url_foto sudah full URL (http/https), pakai langsung
                        // Jika path relatif, wrap dengan asset('storage/...')
                        $coverUrl = Str::startsWith($foto->url_foto, ['http://', 'https://'])
                            ? $foto->url_foto
                            : asset('storage/' . $foto->url_foto);
                    }
                }

                // Fallback ke cover_foto di tabel layanan
                if (!$coverUrl && !empty($layananPertama->cover_foto)) {
                    $coverUrl = Str::startsWith($layananPertama->cover_foto, ['http://', 'https://'])
                        ? $layananPertama->cover_foto
                        : asset('storage/' . $layananPertama->cover_foto);
                }
            }

            // Fallback akhir ke default.jpg
            $covers[$jenis->jenis_layanan_id] = $coverUrl ?? asset('storage/default.jpg');
        }

        return view('user.service.service', compact('layanan', 'jenisLayanan', 'covers'));
    }

    // /service/{slug} — detail layanan
    public function show(string $slug)
    {
        $semua = DB::table('layanan as l')
            ->leftJoin('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->select('l.*', 'jl.nama_jenis')
            ->get();

        $layanan = $semua->first(
            fn($row) => Str::slug($row->nama_layanan) === $slug
        );

        if (!$layanan) {
            abort(404);
        }

        // Harga per cabang
        $layananCabang = DB::table('layanan_cabang as lc')
            ->join('cabang as c', 'lc.cabang_id', '=', 'c.cabang_id')
            ->where('lc.layanan_id', $layanan->layanan_id)
            ->where('lc.status', 'tersedia')
            ->where('c.status', 'BUKA')
            ->select(
                'lc.layanan_cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'c.nama_cabang',
                'c.alamat'
            )
            ->orderBy('c.cabang_id')
            ->get();

        // Paket yang mengandung layanan ini
        $paket = DB::table('paket_detail as pd')
            ->join('paket_layanan as pl', 'pd.paket_id', '=', 'pl.paket_id')
            ->join('paket_cabang as pc', 'pl.paket_id', '=', 'pc.paket_id')
            ->join('cabang as c', 'pc.cabang_id', '=', 'c.cabang_id')
            ->where('pd.layanan_id', $layanan->layanan_id)
            ->where('pc.status', 'tersedia')
            ->where('c.status', 'BUKA')
            ->select(
                'pl.paket_id',
                'pl.nama_paket',
                'pl.deskripsi',
                'pc.harga_normal',
                'pc.harga_promo',
                'c.nama_cabang'
            )
            ->orderBy('pc.harga_normal')
            ->get();

        // Ulasan
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

        // Foto album layanan ini
        $album = DB::table('album')
            ->where('layanan_id', $layanan->layanan_id)
            ->first();

        $coverFoto = null;

        if ($album) {
            $foto = DB::table('album_foto')
                ->where('album_id', $album->album_id)
                ->orderByRaw("FIELD(tipe, 'cover', 'before', 'result', 'after', 'catalog')")
                ->first();

            if ($foto && !empty($foto->url_foto)) {
                $coverFoto = Str::startsWith($foto->url_foto, ['http://', 'https://'])
                    ? $foto->url_foto
                    : asset('storage/' . $foto->url_foto);
            }
        }

        // Fallback ke cover_foto di tabel layanan
        if (!$coverFoto && !empty($layanan->cover_foto)) {
            $coverFoto = Str::startsWith($layanan->cover_foto, ['http://', 'https://'])
                ? $layanan->cover_foto
                : asset('storage/' . $layanan->cover_foto);
        }

        $coverFoto = $coverFoto ?? asset('storage/default.jpg');

        return view('user.service.sdetail', compact(
            'layanan',
            'layananCabang',
            'paket',
            'ulasan',
            'avgRating',
            'coverFoto'
        ));
    }
}