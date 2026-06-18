<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\LayananCabang;
use App\Models\PaketCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabangController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::with('jadwalOperasional')->orderBy('cabang_id')->get();
        return view('owner.cabang', compact('cabangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cabang' => 'required|string|max:100',
            'alamat'      => 'required|string',
            'status'      => 'required|in:BUKA,TUTUP',
            'jadwal'      => 'nullable|array',
            'jadwal.*.hari'      => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jadwal.*.jam_buka'  => 'required|date_format:H:i',
            'jadwal.*.jam_tutup' => 'required|date_format:H:i|after:jadwal.*.jam_buka',
        ]);

        $cabangRef = DB::table('cabang')->value('cabang_id');

        DB::transaction(function () use ($validated, $cabangRef) {
            $cabang = Cabang::create([
                'nama_cabang' => $validated['nama_cabang'],
                'alamat'      => $validated['alamat'],
                'status'      => $validated['status'],
            ]);

            if (!empty($validated['jadwal'])) {
                foreach ($validated['jadwal'] as $j) {
                    $cabang->jadwalOperasional()->create([
                        'hari'      => $j['hari'],
                        'jam_buka'  => $j['jam_buka'],
                        'jam_tutup' => $j['jam_tutup'],
                    ]);
                }
            }

            $statusLayanan = $validated['status'] === 'TUTUP' ? 'tidak_tersedia' : 'tersedia';
            $statusPaket   = $validated['status'] === 'TUTUP' ? 'tidak tersedia' : 'tersedia';

            $semuaLayanan = DB::table('layanan')->pluck('layanan_id');
            foreach ($semuaLayanan as $layananId) {
                $hargaRef = $cabangRef
                    ? DB::table('layanan_cabang')
                        ->where('layanan_id', $layananId)
                        ->where('cabang_id', $cabangRef)
                        ->value('harga') ?? 0
                    : 0;

                DB::table('layanan_cabang')->insert([
                    'layanan_id'  => $layananId,
                    'cabang_id'   => $cabang->cabang_id,
                    'harga'       => $hargaRef,
                    'harga_promo' => null,
                    'status'      => $statusLayanan,
                ]);
            }

            $semuaPaket = DB::table('paket_layanan')->pluck('paket_id');
            foreach ($semuaPaket as $paketId) {
                $hargaRef = $cabangRef
                    ? DB::table('paket_cabang')
                        ->where('paket_id', $paketId)
                        ->where('cabang_id', $cabangRef)
                        ->value('harga_normal') ?? 0
                    : 0;

                DB::table('paket_cabang')->insert([
                    'paket_id'     => $paketId,
                    'cabang_id'    => $cabang->cabang_id,
                    'harga_normal' => $hargaRef,
                    'harga_promo'  => null,
                    'status'       => $statusPaket,
                ]);
            }
        });

        return redirect()->route('owner.cabang')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function toggleStatus($cabang_id)
    {
        $cabang = Cabang::findOrFail($cabang_id);
        $cabang->status = $cabang->status === 'BUKA' ? 'TUTUP' : 'BUKA';
        $cabang->save();

        $statusLayanan = $cabang->status === 'TUTUP' ? 'tidak_tersedia' : 'tersedia';
        $statusPaket   = $cabang->status === 'TUTUP' ? 'tidak tersedia' : 'tersedia';

        DB::table('layanan_cabang')
            ->where('cabang_id', $cabang_id)
            ->update(['status' => $statusLayanan]);

        DB::table('paket_cabang')
            ->where('cabang_id', $cabang_id)
            ->update(['status' => $statusPaket]);

        $label = $cabang->status === 'BUKA' ? 'dibuka' : 'ditutup';
        return redirect()->route('owner.cabang')
            ->with('success', "Cabang berhasil {$label}. Semua layanan otomatis di{$label}.");
    }

    public function update(Request $request, $cabang_id)
    {
        $cabang = Cabang::findOrFail($cabang_id);

        $validated = $request->validate([
            'nama_cabang' => 'required|string|max:100',
            'alamat'      => 'required|string',
            'status'      => 'required|in:BUKA,TUTUP',
            'jadwal'      => 'nullable|array',
            'jadwal.*.jadwal_id' => 'nullable|integer',
            'jadwal.*.hari'      => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jadwal.*.jam_buka'  => 'required|date_format:H:i',
            'jadwal.*.jam_tutup' => 'required|date_format:H:i|after:jadwal.*.jam_buka',
        ]);

        DB::transaction(function () use ($cabang, $validated) {
            $cabang->update([
                'nama_cabang' => $validated['nama_cabang'],
                'alamat'      => $validated['alamat'],
                'status'      => $validated['status'],
            ]);

            // Hapus jadwal lama, timpa dengan yang baru
            $cabang->jadwalOperasional()->delete();

            if (!empty($validated['jadwal'])) {
                foreach ($validated['jadwal'] as $j) {
                    $cabang->jadwalOperasional()->create([
                        'hari'      => $j['hari'],
                        'jam_buka'  => $j['jam_buka'],
                        'jam_tutup' => $j['jam_tutup'],
                    ]);
                }
            }
        });

        return redirect()->route('owner.cabang')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy($cabang_id)
    {
        $cabang = Cabang::findOrFail($cabang_id);

        $pegawaiCount = $cabang->pegawai()->count();

        $bookingAktifCount = DB::table('booking as b')
            ->join('booking_detail as bd', 'b.booking_id', '=', 'bd.booking_id')
            ->join('layanan_cabang as lc', 'bd.layanan_cabang_id', '=', 'lc.layanan_cabang_id')
            ->where('lc.cabang_id', $cabang_id)
            ->whereIn('b.status', ['pending', 'confirmed', 'in_progress'])
            ->count();

        if ($pegawaiCount > 0) {
            return redirect()->route('owner.cabang')
                ->with('error', "Cabang tidak bisa dihapus karena masih memiliki {$pegawaiCount} pegawai. Pindahkan atau nonaktifkan pegawai terlebih dahulu.");
        }

        if ($bookingAktifCount > 0) {
            return redirect()->route('owner.cabang')
                ->with('error', "Cabang tidak bisa dihapus karena masih ada {$bookingAktifCount} booking aktif.");
        }

        $cabang->delete();
        return redirect()->route('owner.cabang')->with('success', 'Cabang berhasil dihapus.');
    }
}