<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPegawai;
use App\Models\Booking;
use App\Models\Notifikasi;
use Carbon\Carbon;

class PegawaiDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
     Carbon::setLocale('id');
    $pegawaiId = auth()->user()->pegawai->pegawai_id;

    // Bulan & tahun yang ditampilkan (default: bulan ini)
    $bulan = $request->get('bulan', now()->month);
    $tahun = $request->get('tahun', now()->year);

    $carbonBulan = Carbon::createFromDate($tahun, $bulan, 1);

    $today = now()->toDateString();
    // Ambil semua tanggal yang ada jadwal di bulan ini
    $tanggalAdaJadwal = JadwalPegawai::where('pegawai_id', $pegawaiId)
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan)
        ->pluck('tanggal')
        ->map(fn($t) => Carbon::parse($t)->toDateString())
        ->unique()
        ->toArray();

    // Bangun array kalender
    $kalender = [];

    // Kalender kita mulai dari Senin, jadi hitung offset
    $hariPertama = $carbonBulan->copy()->startOfMonth();
    $offsetAwal = ($hariPertama->dayOfWeek === 0) ? 6 : $hariPertama->dayOfWeek - 1;

    // Isi tanggal dari bulan sebelumnya (muted)
    $bulanSebelum = $carbonBulan->copy()->subMonth();
    $hariAkhirBulanLalu = $bulanSebelum->daysInMonth;
    for ($i = $offsetAwal - 1; $i >= 0; $i--) {
        $kalender[] = [
            'date'       => $hariAkhirBulanLalu - $i,
            'muted'      => true,
            'full_date'  => null,
            'has_jadwal' => false,
        ];
    }

    // Isi tanggal bulan ini
    $hariDalamBulan = $carbonBulan->daysInMonth;
    for ($d = 1; $d <= $hariDalamBulan; $d++) {
        $fullDate = Carbon::createFromDate($tahun, $bulan, $d)->toDateString();
        $kalender[] = [
            'date'       => $d,
            'muted'      => false,
            'full_date'  => $fullDate,
            'has_jadwal' => in_array($fullDate, $tanggalAdaJadwal),
        ];
    }

    // Isi sisa kotak dengan bulan berikutnya (muted), sampai genap 7 kolom
    $sisaKotak = count($kalender) % 7;
    if ($sisaKotak !== 0) {
        $sisaKotak = 7 - $sisaKotak;
        for ($i = 1; $i <= $sisaKotak; $i++) {
            $kalender[] = [
                'date'       => $i,
                'muted'      => true,
                'full_date'  => null,
                'has_jadwal' => false,
            ];
        }
    }

    // Navigasi bulan
    $bulanBerikutnya  = $carbonBulan->copy()->addMonth();
    $bulanSebelumnya  = $carbonBulan->copy()->subMonth();

    // Ongoing: status 'ongoing' = sedang berjalan (setelah tekan mulai servis)
    $ongoing = Booking::where('pegawai_id', $pegawaiId)
    ->whereDate('tanggal_booking', $today)
    ->where('status', 'ongoing')
    ->orderBy('jam_booking')
    ->first();
    

    // Upcoming: confirmed = telah ditugaskan, masuk jadwal pegawai, belum mulai
    $upcoming = Booking::with([
            'details.layananCabang.layanan.jenisLayanan',
            'pelanggan.user',
        ])
        ->where('pegawai_id', $pegawaiId)
        ->where('status', 'confirmed')
        ->whereDate('tanggal_booking', '>=', $today)  // ← Hari ini dan setelahnya
        ->orderBy('tanggal_booking', 'asc')
        ->orderBy('jam_booking', 'asc')
        ->limit(3)  // ← Hanya 3 terdekat
        ->get();

        // ── SUMMARY HARI INI ──────────────────────────────────────
        $totalBooking   = Booking::where('pegawai_id', $pegawaiId)
                            ->whereDate('tanggal_booking', $today)->count();
        $totalSelesai   = Booking::where('pegawai_id', $pegawaiId)
                            ->whereDate('tanggal_booking', $today)
                            ->where('status', 'completed')->count();
        $totalBerjalan  = Booking::where('pegawai_id', $pegawaiId)
                            ->whereDate('tanggal_booking', $today)
                            ->where('status', 'ongoing')->count();
        $totalMenunggu  = Booking::where('pegawai_id', $pegawaiId)
                            ->whereDate('tanggal_booking', $today)
                            ->where('status', 'confirmed')->count();
        $jadwalBerikutnya = JadwalPegawai::where('pegawai_id', auth()->id())
    ->where('status_ketersediaan', 'tersedia')
    ->where(function ($q) {
        $q->where('tanggal', '>', now()->toDateString())
          ->orWhere(function ($q2) {
              $q2->where('tanggal', now()->toDateString())
                 ->where('jam_mulai', '>=', now()->format('H:i:s'));
          });
    })
    ->orderBy('tanggal')
    ->orderBy('jam_mulai')
    ->first();

$jadwalText = $jadwalBerikutnya
    ? Carbon::parse($jadwalBerikutnya->jam_mulai)->format('H:i')
        . ' - ' .
      Carbon::parse($jadwalBerikutnya->jam_selesai)->format('H:i')
    : 'Tidak ada jadwal';

    if ($jadwalBerikutnya) {

    $tanggalJadwal = Carbon::parse($jadwalBerikutnya->tanggal);

    $jam = Carbon::parse($jadwalBerikutnya->jam_mulai)->format('H:i')
        . ' - ' .
        Carbon::parse($jadwalBerikutnya->jam_selesai)->format('H:i');

    if ($tanggalJadwal->isToday()) {

        $jadwalText = $jam;

    } else {

        $jadwalText =
            $tanggalJadwal->translatedFormat('l, d M')
            . ' • ' .
            $jam;
    }

} else {

    $jadwalText = 'Tidak ada jadwal';
}

    // ── NOTIFIKASI ──────────────────────────────────────                      
    $notifikasi = Notifikasi::where('user_id', auth()->id())
    ->where('status_baca', 'belum')
    ->latest()
    ->take(3)
    ->get();

        return view('pegawai.dashboard', [
            // kalender
            'kalender'        => $kalender,
            'bulanLabel'      => $carbonBulan->locale('id')->translatedFormat('F'),
            'tahunKalender'   => $tahun,
            'bulanSebelumnya' => $bulanSebelumnya->month,
            'tahunSebelumnya' => $bulanSebelumnya->year,
            'bulanBerikutnya' => $bulanBerikutnya->month,
            'tahunBerikutnya' => $bulanBerikutnya->year,
            // booking
            'ongoing'         => $ongoing,
            'upcoming'        => $upcoming,
            // summary
            'totalBooking'    => $totalBooking,
            'totalSelesai'    => $totalSelesai,
            'totalBerjalan'   => $totalBerjalan,
            'totalMenunggu'   => $totalMenunggu,
            'jadwalText'    => $jadwalText,
            //notifikasi
            'notifikasi'      => $notifikasi,
        ]);
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