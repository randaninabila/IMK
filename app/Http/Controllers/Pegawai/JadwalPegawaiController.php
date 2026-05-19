<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JadwalPegawai;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $pegawaiId = auth()->user()->pegawai->pegawai_id;
        $filter    = $request->get('filter', 'harian');

        // ── HARIAN ──────────────────────────────────────────────
        if ($filter === 'harian') {

            $tanggal  = $request->get('tanggal', now()->toDateString());
            $carbon   = Carbon::parse($tanggal);

            $jadwals = Booking::with([
                'pelanggan.user',
                'bookingDetails.layananCabang.layanan'
            ])
                ->where('pegawai_id', $pegawaiId)
                ->whereDate('tanggal_booking', $tanggal)
                ->orderBy('jam_booking')
                ->get();

            return view('pegawai.jk.jkb', [
                'filter'          => $filter,
                'tanggal'         => $tanggal,
                'carbon'          => $carbon,
                'jadwals'         => $jadwals,
                'tanggalSebelum'  => $carbon->copy()->subDay()->toDateString(),
                'tanggalBerikut'  => $carbon->copy()->addDay()->toDateString(),
            ]);
        }

        // ── MINGGUAN ─────────────────────────────────────────────
        if ($filter === 'mingguan') {

            $tanggal      = $request->get('tanggal', now()->toDateString());
            $carbonAktif  = Carbon::parse($tanggal);

            // Senin minggu ini berdasarkan tanggal aktif
            $senin        = $carbonAktif->copy()->startOfWeek(Carbon::MONDAY);
            $minggu       = $senin->copy()->endOfWeek(Carbon::SUNDAY);

            // Bangun daftar 7 hari
            $hariList = [];
            for ($i = 0; $i < 7; $i++) {
                $hariList[] = $senin->copy()->addDays($i);
            }

            // Jadwal di hari yang aktif dipilih
            $jadwals = Booking::with([
                'pelanggan.user',
                'bookingDetails.layananCabang.layanan'
            ])
                ->where('pegawai_id', $pegawaiId)
                ->whereDate('tanggal_booking', $tanggal)
                ->orderBy('jam_booking')
                ->get();

            return view('pegawai.jk.jkb', [
                'filter'         => $filter,
                'tanggal'        => $tanggal,
                'carbonAktif'    => $carbonAktif,
                'hariList'       => $hariList,
                'jadwals'        => $jadwals,
                'seminuSebelum'  => $senin->copy()->subWeek()->toDateString(),
                'seminuBerikut'  => $senin->copy()->addWeek()->toDateString(),
            ]);
        }

        // ── BULANAN ──────────────────────────────────────────────
        if ($filter === 'bulanan') {

            $bulan  = $request->get('bulan', now()->month);
            $tahun  = $request->get('tahun', now()->year);
            $tanggal = $request->get('tanggal', now()->toDateString());

            $carbonBulan = Carbon::createFromDate($tahun, $bulan, 1);

            // Tanggal yang ada jadwal bulan ini
            $tanggalAdaJadwal = JadwalPegawai::where('pegawai_id', $pegawaiId)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->pluck('tanggal')
                ->map(fn($t) => Carbon::parse($t)->toDateString())
                ->unique()
                ->toArray();

            // Bangun grid kalender (mulai Senin)
            $kalender    = [];
            $hariPertama = $carbonBulan->copy()->startOfMonth();
            $offsetAwal  = ($hariPertama->dayOfWeek === 0) ? 6 : $hariPertama->dayOfWeek - 1;

            // Isi bulan lalu (muted)
            $hariAkhirBulanLalu = $carbonBulan->copy()->subMonth()->daysInMonth;
            for ($i = $offsetAwal - 1; $i >= 0; $i--) {
                $kalender[] = ['date' => $hariAkhirBulanLalu - $i, 'muted' => true, 'full_date' => null, 'has_jadwal' => false];
            }

            // Isi bulan ini
            for ($d = 1; $d <= $carbonBulan->daysInMonth; $d++) {
                $fullDate   = Carbon::createFromDate($tahun, $bulan, $d)->toDateString();
                $kalender[] = [
                    'date'       => $d,
                    'muted'      => false,
                    'full_date'  => $fullDate,
                    'has_jadwal' => in_array($fullDate, $tanggalAdaJadwal),
                    'is_today'   => $fullDate === now()->toDateString(),
                    'is_active'  => $fullDate === $tanggal,
                ];
            }

            // Isi bulan berikutnya (muted)
            $sisa = count($kalender) % 7;
            if ($sisa !== 0) {
                for ($i = 1; $i <= (7 - $sisa); $i++) {
                    $kalender[] = ['date' => $i, 'muted' => true, 'full_date' => null, 'has_jadwal' => false];
                }
            }

            $bulanSebelumnya = $carbonBulan->copy()->subMonth();
            $bulanBerikutnya = $carbonBulan->copy()->addMonth();

            return view('pegawai.jk.jkb', [
                'filter'          => $filter,
                'tanggal'         => $tanggal,
                'kalender'        => $kalender,
                'bulanLabel'      => $carbonBulan->locale('id')->translatedFormat('F'),
                'tahunKalender'   => $tahun,
                'bulanSebelumnya' => $bulanSebelumnya->month,
                'tahunSebelumnya' => $bulanSebelumnya->year,
                'bulanBerikutnya' => $bulanBerikutnya->month,
                'tahunBerikutnya' => $bulanBerikutnya->year,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
