<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SpecialistController extends Controller
{
    // Daftar semua specialist
    public function index()
    {
        $specialists = DB::table('pegawai')
            ->join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->leftJoin('cabang', 'pegawai.cabang_id', '=', 'cabang.cabang_id')
            ->where('users.role', 'pegawai')
            ->where('pegawai.status_kerja', 'aktif')
            ->select(
                'pegawai.pegawai_id',
                'pegawai.cabang_id',
                'users.nama',
                'users.foto_profile as foto',
                'cabang.nama_cabang'
            )
            ->get();

        return view('user.specialist.specialist', compact('specialists'));
    }

    // Detail satu specialist
    public function show($pegawai_id)
    {
        // Data pegawai + user + cabang
        $specialist = DB::table('pegawai')
            ->join('users', 'pegawai.user_id', '=', 'users.user_id')
            ->leftJoin('cabang', 'pegawai.cabang_id', '=', 'cabang.cabang_id')
            ->where('pegawai.pegawai_id', $pegawai_id)
            ->select(
                'pegawai.pegawai_id',
                'pegawai.cabang_id',
                'pegawai.status_kerja',
                'users.nama',
                'users.email',
                'users.no_hp',
                'users.foto_profile as foto',
                'cabang.nama_cabang',
                // jabatan & deskripsi jika ada di tabel pegawai (tambahkan jika tersedia)
                DB::raw("NULL as jabatan"),
                DB::raw("NULL as deskripsi")
            )
            ->firstOrFail();

        // Jadwal pegawai yang tersedia (mulai dari hari ini)
        $jadwal = DB::table('jadwal_pegawai')
            ->where('pegawai_id', $pegawai_id)
            ->where('status_ketersediaan', 'tersedia')
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('tanggal'); // key: 'YYYY-MM-DD', value: collection sesi

        // Layanan yang pernah dikerjakan pegawai ini
        // (melalui booking → booking_detail → layanan_cabang → layanan)
        $layananList = DB::table('booking')
            ->join('booking_detail', 'booking.booking_id', '=', 'booking_detail.booking_id')
            ->join('layanan_cabang', 'booking_detail.layanan_cabang_id', '=', 'layanan_cabang.layanan_cabang_id')
            ->join('layanan', 'layanan_cabang.layanan_id', '=', 'layanan.layanan_id')
            ->where('booking.pegawai_id', $pegawai_id)
            ->select('layanan.layanan_id', 'layanan.nama_layanan')
            ->distinct()
            ->get();

        return view('user.specialist.spdetail', compact('specialist', 'jadwal', 'layananList'));
    }
}