<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlbumAdminController extends Controller
{
    // Halaman kelola album per layanan
    public function index($layanan_id)
    {
        $layanan = Layanan::findOrFail($layanan_id);

        $album = DB::table('album')
            ->where('layanan_id', $layanan_id)
            ->first();

        $fotos = $album
            ? DB::table('album_foto')
                ->where('album_id', $album->album_id)
                ->orderBy('tipe')
                ->orderBy('created_at')
                ->get()
                ->groupBy('tipe')
            : collect();

        return view('admin.layanan.album', compact('layanan', 'album', 'fotos'));
    }

    // Upload foto — buat album dulu kalau belum ada
    public function store(Request $request, $layanan_id)
    {
        $request->validate([
            'fotos'       => 'required|array|min:1',
            'fotos.*'     => 'image|mimes:jpeg,png,webp|max:3072',
            'tipe'        => 'required|in:before,after',
        ]);

        Layanan::findOrFail($layanan_id);

        // Cari atau buat album untuk layanan ini
        $album = DB::table('album')->where('layanan_id', $layanan_id)->first();

        if (!$album) {
            $albumId = DB::table('album')->insertGetId([
                'layanan_id'  => $layanan_id,
                'deskripsi'   => 'Album ' . Layanan::find($layanan_id)->nama_layanan,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } else {
            $albumId = $album->album_id;
        }

        foreach ($request->file('fotos') as $file) {
            $path = $file->store('album', 'public');

            DB::table('album_foto')->insert([
                'album_id'   => $albumId,
                'url_foto'   => $path,
                'tipe'       => $request->tipe,
                'created_at' => now(),
            ]);
        }

        return back()->with('success', 'Foto berhasil diupload.');
    }

    // Hapus satu foto
    public function destroyFoto($foto_id)
    {
        $foto = DB::table('album_foto')->where('foto_id', $foto_id)->first();

        if (!$foto) {
            return back()->with('error', 'Foto tidak ditemukan.');
        }

        // Hapus file dari storage
        if ($foto->url_foto) {
            Storage::disk('public')->delete($foto->url_foto);
        }

        DB::table('album_foto')->where('foto_id', $foto_id)->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}