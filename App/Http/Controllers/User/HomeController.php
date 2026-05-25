<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $salon = DB::table('salon')->first();

        $cabangList = DB::table('cabang')
            ->where('status', 'BUKA')
            ->orderBy('nama_cabang', 'asc')
            ->get();
        $avgRatingRaw = DB::table('ulasan')->avg('rating');
        $avgRating = $avgRatingRaw ? number_format($avgRatingRaw, 1) : '0.0';
        $hariIni = strtolower(Carbon::now()->isoFormat('dddd')); // senin, selasa, dll
        $jamSekarang = Carbon::now()->format('H:i');

        $jadwal = DB::table('jadwal_operasional')->where('hari', $hariIni)->first();

        $isBuka = false;
        $jamBuka = '09:00';
        $jamTutup = '19:00';

        if ($jadwal) {
            $jamBuka = date('H:i', strtotime($jadwal->jam_buka));
            $jamTutup = date('H:i', strtotime($jadwal->jam_tutup));
            
            $isBuka = ($jamSekarang >= $jamBuka && $jamSekarang < $jamTutup);
        }

        return view('user.home.home', compact('salon', 'cabangList', 'avgRating', 'isBuka', 'jamBuka', 'jamTutup'));
    }
}