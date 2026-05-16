<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SpecialistController extends Controller
{
    // ========================
    // Halaman daftar specialist
    // ========================
    public function index()
    {
        $specialists = DB::table('pegawai as p')
            ->join('users as u', 'p.user_id', '=', 'u.user_id')
            ->where('u.role', 'pegawai')
            ->where('u.status_akun', 'aktif')
            ->where('p.status_kerja', 'aktif')
            ->select(
                'p.pegawai_id',
                'u.foto_profile as foto',
                'u.nama',
                'u.no_hp'
            )
            ->get();

        return view('user.specialist.specialist', compact('specialists'));
    }

    // ========================
    // Halaman detail specialist
    // ========================
    public function show($pegawai_id)
    {
        // Data utama pegawai
        $specialist = DB::table('pegawai as p')
            ->join('users as u', 'p.user_id', '=', 'u.user_id')
            ->where('p.pegawai_id', $pegawai_id)
            ->where('u.status_akun', 'aktif')
            ->select(
                'p.pegawai_id',
                'u.foto_profile as foto',
                'p.cabang_id',
                'u.nama',
                'u.no_hp'
            )
            ->firstOrFail();

        // Jadwal kerja pegawai
        $jadwal = DB::table('jadwal_pegawai')
            ->where('pegawai_id', $pegawai_id)
            ->where('tanggal', '>=', now()->toDateString())
            ->where('status_ketersediaan', 'tersedia')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->select('tanggal', 'jam_mulai', 'jam_selesai')
            ->get()
            ->groupBy('tanggal');

        // Layanan yang pernah dikerjakan
        $layananList = DB::table('layanan as l')
            ->join('layanan_cabang as lc', 'l.layanan_id', '=', 'lc.layanan_id')
            ->join('booking_detail as bd', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->where('bd.pegawai_id', $pegawai_id)
            ->select('l.layanan_id', 'l.nama_layanan')
            ->distinct()
            ->get();

        return view('user.specialist.spdetail', compact('specialist', 'jadwal', 'layananList'));
    }
}