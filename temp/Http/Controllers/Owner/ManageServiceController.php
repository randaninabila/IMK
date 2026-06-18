<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\LayananCabang;
use App\Models\JenisLayanan;
use App\Models\PaketLayanan;
use App\Models\PaketDetail;
use App\Models\PaketCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManageServiceController extends Controller
{
    public function index()
    {
        $layanan = DB::table('layanan as l')
            ->join('jenis_layanan as jl', 'l.jenis_layanan_id', '=', 'jl.jenis_layanan_id')
            ->crossJoin('cabang as c')
            ->leftJoin('layanan_cabang as lc', function ($join) {
                $join->on('lc.layanan_id', '=', 'l.layanan_id')
                    ->on('lc.cabang_id',  '=', 'c.cabang_id');
            })
            ->select(
                'l.layanan_id',
                'l.nama_layanan',
                'l.deskripsi',
                'l.durasi',
                'l.kategori_pelanggan',
                'l.jenis_layanan_id',
                'l.cover_foto',
                'jl.nama_jenis',
                'lc.layanan_cabang_id',
                'lc.harga',
                'lc.harga_promo',
                'lc.status as status_cabang',
                'c.cabang_id',
                'c.nama_cabang'
            )
            ->orderBy('l.nama_layanan')
            ->get()
            ->groupBy('layanan_id');

        $jenisLayanan = JenisLayanan::orderBy('nama_jenis')->get();

        $paketLayanan = DB::table('paket_layanan as pl')
            ->leftJoin('paket_detail as pd', 'pl.paket_id', '=', 'pd.paket_id')
            ->leftJoin('layanan as l', 'pd.layanan_id', '=', 'l.layanan_id')
            ->leftJoin('paket_cabang as pc', 'pl.paket_id', '=', 'pc.paket_id')
            ->select(
                'pl.paket_id',
                'pl.nama_paket',
                'pl.deskripsi',
                'pl.kategori_pelanggan',
                DB::raw("MIN(pc.harga_normal) as harga_normal"),
                DB::raw("MIN(pc.harga_promo) as harga_promo"),
                DB::raw("GROUP_CONCAT(DISTINCT l.nama_layanan ORDER BY l.nama_layanan SEPARATOR ', ') as layanan_list")
            )
            ->groupBy('pl.paket_id', 'pl.nama_paket', 'pl.deskripsi', 'pl.kategori_pelanggan')
            ->orderBy('pl.nama_paket')
            ->get();

        $cabang = DB::table('cabang')->where('status', 'BUKA')->get();

        $layananAktif = DB::table('layanan as l')
            ->join('layanan_cabang as lc', 'l.layanan_id', '=', 'lc.layanan_id')
            ->where('lc.status', 'tersedia')
            ->select('l.layanan_id', 'l.nama_layanan', 'l.kategori_pelanggan')
            ->distinct()
            ->orderBy('l.nama_layanan')
            ->get();

        return view('owner.service.manage', compact(
            'layanan',
            'jenisLayanan',
            'cabang',
            'paketLayanan',
            'layananAktif'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan'       => 'required|max:100',
            'cover_foto'         => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'jenis_layanan_id'   => 'required|exists:jenis_layanan,jenis_layanan_id',
            'deskripsi'          => 'nullable|string',
            'durasi'             => 'required|integer|min:1',
            'kategori_pelanggan' => 'required|in:umum,anak',
            'harga'              => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $layanan = Layanan::create([
                'jenis_layanan_id'   => $request->jenis_layanan_id,
                'nama_layanan'       => $request->nama_layanan,
                'deskripsi'          => $request->deskripsi,
                'durasi'             => $request->durasi,
                'kategori_pelanggan' => $request->kategori_pelanggan,
                'cover_foto'         => null,
            ]);

            if ($request->hasFile('cover_foto')) {
                $path = $request->file('cover_foto')->store('layanan', 'public');
                $layanan->update(['cover_foto' => $path]);
            }

            $cabangList = DB::table('cabang')->pluck('cabang_id');

            foreach ($cabangList as $cabangId) {
                LayananCabang::create([
                    'layanan_id'  => $layanan->layanan_id,
                    'cabang_id'   => $cabangId,
                    'harga'       => $request->harga,
                    'harga_promo' => null,
                    'status'      => 'tersedia',
                ]);
            }
        });

        session(['active_tab' => $request->input('active_tab', 'layanan')]);
        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $request->validate([
            'nama_layanan'       => 'required|max:100',
            'cover_foto'         => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'jenis_layanan_id'   => 'required|exists:jenis_layanan,jenis_layanan_id',
            'deskripsi'          => 'nullable|string',
            'durasi'             => 'required|integer|min:1',
            'kategori_pelanggan' => 'required|in:umum,anak',
            'harga'              => 'required|numeric|min:0',
            'cabang_id'          => 'nullable|exists:cabang,cabang_id',
        ]);

        DB::transaction(function () use ($request, $layanan, $id) {
            $layanan->update([
                'jenis_layanan_id'   => $request->jenis_layanan_id,
                'nama_layanan'       => $request->nama_layanan,
                'deskripsi'          => $request->deskripsi,
                'durasi'             => $request->durasi,
                'kategori_pelanggan' => $request->kategori_pelanggan,
            ]);

            if ($request->hasFile('cover_foto')) {
                if ($layanan->cover_foto) {
                    Storage::disk('public')->delete($layanan->cover_foto);
                }
                $path = $request->file('cover_foto')->store('layanan', 'public');
                $layanan->update(['cover_foto' => $path]);
            }

            $query = LayananCabang::where('layanan_id', $id);

            if ($request->filled('cabang_id')) {
                $query->where('cabang_id', $request->cabang_id);
            }

            $query->update(['harga' => $request->harga]);
        });

        session(['active_tab' => $request->input('active_tab', 'layanan')]);
        return back()->with('success', 'Layanan berhasil diperbarui.');
    }

    public function deactivate(Request $request, $id)
    {
        Layanan::findOrFail($id);

        $request->validate([
            'cabang_id'   => 'required|array|min:1',
            'cabang_id.*' => 'exists:cabang,cabang_id',
        ]);

        LayananCabang::where('layanan_id', $id)
            ->whereIn('cabang_id', $request->cabang_id)
            ->update(['status' => 'tidak_tersedia']);

        session(['active_tab' => $request->input('active_tab', 'layanan')]);
        return back()->with('success', 'Layanan berhasil dinonaktifkan.');
    }

    public function activate(Request $request, $id)
    {
        Layanan::findOrFail($id);

        $request->validate([
            'cabang_id'   => 'required|array|min:1',
            'cabang_id.*' => 'exists:cabang,cabang_id',
        ]);

        LayananCabang::where('layanan_id', $id)
            ->whereIn('cabang_id', $request->cabang_id)
            ->update(['status' => 'tersedia']);

        session(['active_tab' => $request->input('active_tab', 'layanan')]);
        return back()->with('success', 'Layanan berhasil diaktifkan.');
    }

    public function updateJenis(Request $request, $id)
    {
        $jenis = JenisLayanan::findOrFail($id);

        $request->validate([
            'nama_jenis' => 'required|max:100|unique:jenis_layanan,nama_jenis,' . $id . ',jenis_layanan_id',
            'deskripsi'  => 'nullable|string',
        ]);

        $jenis->update([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi'  => $request->deskripsi,
        ]);

        session(['active_tab' => $request->input('active_tab', 'jenis')]);
        return back()->with('success', 'Jenis layanan berhasil diperbarui.');
    }

    public function storeJenis(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|max:100|unique:jenis_layanan,nama_jenis',
            'deskripsi'  => 'nullable|string',
        ]);

        JenisLayanan::create([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi'  => $request->deskripsi,
        ]);

        session(['active_tab' => $request->input('active_tab', 'jenis')]);
        return back()->with('success', 'Jenis layanan berhasil ditambahkan.');
    }

    public function storePaket(Request $request)
    {
        $request->validate([
            'nama_paket'         => 'required|max:255',
            'deskripsi'          => 'nullable|string',
            'kategori_pelanggan' => 'required|in:umum,anak',
            'layanan_id'         => 'required|array|min:1',
            'layanan_id.*'       => 'exists:layanan,layanan_id',
            'harga_normal'       => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $paket = PaketLayanan::create([
                'nama_paket'         => $request->nama_paket,
                'deskripsi'          => $request->deskripsi,
                'kategori_pelanggan' => $request->kategori_pelanggan,
            ]);

            foreach ($request->layanan_id as $layananId) {
                PaketDetail::create([
                    'paket_id'   => $paket->paket_id,
                    'layanan_id' => $layananId,
                ]);
            }

            $cabangList = DB::table('cabang')->pluck('cabang_id');

            foreach ($cabangList as $cabangId) {
                PaketCabang::create([
                    'paket_id'     => $paket->paket_id,
                    'cabang_id'    => $cabangId,
                    'harga_normal' => $request->harga_normal,
                    'harga_promo'  => null,
                    'status'       => 'tersedia',
                ]);
            }
        });

        session(['active_tab' => $request->input('active_tab', 'paket')]);
        return back()->with('success', 'Paket berhasil ditambahkan.');
    }

    public function updatePaket(Request $request, $id)
    {
        $request->validate([
            'nama_paket'         => 'required|max:255',
            'deskripsi'          => 'nullable|string',
            'kategori_pelanggan' => 'required|in:umum,anak',
            'harga_normal'       => 'required|numeric|min:0',
        ]);

        PaketLayanan::findOrFail($id)->update([
            'nama_paket'         => $request->nama_paket,
            'deskripsi'          => $request->deskripsi,
            'kategori_pelanggan' => $request->kategori_pelanggan,
        ]);

        PaketCabang::where('paket_id', $id)
            ->update(['harga_normal' => $request->harga_normal]);

        session(['active_tab' => 'paket']);
        return back()->with('success', 'Paket berhasil diperbarui.');
    }
}