<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PenjadwalanAdminController extends Controller
{
    public function index(Request $request)
    {
        $branches = $this->getBranches();

        $selectedCabangId = (int) $request->query('cabang_id', $branches->first()->cabang_id ?? 1);

        if (!$branches->contains('cabang_id', $selectedCabangId)) {
            $selectedCabangId = (int) ($branches->first()->cabang_id ?? 1);
        }

        $selectedBranch = $branches->firstWhere('cabang_id', $selectedCabangId);
        $selectedDate = $this->getSelectedDate($request);
        $dateOptions = $this->getDateOptions();

        $staffList = $this->getStaffList();
        $services = $this->getServices($selectedCabangId);
        $customers = $this->getCustomers();
        $bookings = $this->getBookings($selectedCabangId, $selectedDate);

        $times = $this->getTimes();
        $jadwalPegawai = $this->getJadwalPegawai($selectedDate, $staffList);

        $scheduleGrid = $this->buildScheduleGrid($times, $staffList, $bookings, $jadwalPegawai);
        $bookingList = $this->buildBookingList($bookings);

        return view('admin.penjadwalan.penjadwalanadmin', compact(
            'branches',
            'selectedBranch',
            'selectedCabangId',
            'selectedDate',
            'dateOptions',
            'staffList',
            'services',
            'customers',
            'times',
            'scheduleGrid',
            'bookingList'
        ));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|integer',
            'layanan_cabang_id' => 'required|integer',
            'pegawai_id' => 'required|integer',
            'tanggal_booking' => 'required|date',
            'jam_booking' => 'required',
            'metode_pembayaran' => 'required|in:cash,qris',
            'status' => 'required|in:pending,confirmed,assigned,proses,selesai,batal',
            'catatan' => 'nullable|string|max:500',
        ]);

        $service = $this->getServiceForBooking($request->layanan_cabang_id);

        if (!$service) {
            return back()->with('error', 'Layanan tidak ditemukan.');
        }

        $pegawai = $this->getValidPegawai($request->pegawai_id);

        if (!$pegawai) {
            return back()->with('error', 'Specialist tidak ditemukan atau bukan role pegawai.');
        }

        $alreadyBooked = DB::table('booking as b')
            ->join('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->where('b.pegawai_id', $request->pegawai_id)
            ->whereDate('b.tanggal_booking', $request->tanggal_booking)
            ->whereTime('b.jam_booking', $request->jam_booking)
            ->whereNotIn('b.status', ['batal'])
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'Specialist sudah memiliki booking pada jam tersebut.');
        }

        DB::transaction(function () use ($request, $service) {
            $newBookingId = (DB::table('booking')->max('booking_id') ?? 0) + 1;

            $bookingData = [
                'booking_id' => $newBookingId,
                'pelanggan_id' => $request->pelanggan_id,
                'tanggal_booking' => $request->tanggal_booking,
                'jam_booking' => $request->jam_booking,
                'status' => $request->status,
            ];

            if (Schema::hasColumn('booking', 'catatan')) {
                $bookingData['catatan'] = $request->catatan;
            }

            if (Schema::hasColumn('booking', 'keluhan')) {
                $bookingData['keluhan'] = $request->catatan;
            }

            if (Schema::hasColumn('booking', 'created_at')) {
                $bookingData['created_at'] = now();
            }

            if (Schema::hasColumn('booking', 'updated_at')) {
                $bookingData['updated_at'] = now();
            }

            DB::table('booking')->insert($bookingData);

            $bookingDetailData = [
                'booking_id' => $newBookingId,
                'layanan_cabang_id' => $request->layanan_cabang_id,
                'pegawai_id' => $request->pegawai_id,
            ];

            if (Schema::hasColumn('booking_detail', 'booking_detail_id')) {
                $bookingDetailData['booking_detail_id'] = (DB::table('booking_detail')->max('booking_detail_id') ?? 0) + 1;
            }

            if (Schema::hasColumn('booking_detail', 'created_at')) {
                $bookingDetailData['created_at'] = now();
            }

            if (Schema::hasColumn('booking_detail', 'updated_at')) {
                $bookingDetailData['updated_at'] = now();
            }

            DB::table('booking_detail')->insert($bookingDetailData);

            $this->insertPaymentIfTableExists($request, $service, $newBookingId);
        });

        return redirect()
            ->route('admin.penjadwalan', [
                'cabang_id' => $service->cabang_id,
                'tanggal' => $request->tanggal_booking,
            ])
            ->with('success', 'Booking berhasil ditambahkan.');
    }

    public function updateBookingStatus(Request $request, $booking_id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,assigned,proses,selesai,batal',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        if (Schema::hasColumn('booking', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('booking')
            ->where('booking_id', $booking_id)
            ->update($updateData);

        $this->updatePaymentStatusIfExists($booking_id, $request->status);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function cancelBooking($booking_id)
    {
        $updateData = [
            'status' => 'batal',
        ];

        if (Schema::hasColumn('booking', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('booking')
            ->where('booking_id', $booking_id)
            ->update($updateData);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    private function getBranches()
    {
        $branches = DB::table('cabang')
            ->select(
                'cabang_id',
                DB::raw('MIN(nama_cabang) as nama_cabang'),
                DB::raw('MIN(alamat) as alamat'),
                DB::raw('MIN(status) as status')
            )
            ->whereIn('cabang_id', [1, 2])
            ->groupBy('cabang_id')
            ->orderBy('cabang_id', 'asc')
            ->get()
            ->map(function ($branch) {
                $namaCabang = strtolower($branch->nama_cabang ?? '');

                $branch->label = str_contains($namaCabang, 'percut')
                    ? 'Cabang Percut'
                    : 'Cabang Tembung';

                return $branch;
            });

        if ($branches->isEmpty()) {
            $branches = collect([
                (object) [
                    'cabang_id' => 1,
                    'nama_cabang' => 'Salon Muslimah Dina - Tembung',
                    'alamat' => null,
                    'status' => 'BUKA',
                    'label' => 'Cabang Tembung',
                ],
                (object) [
                    'cabang_id' => 2,
                    'nama_cabang' => 'Salon Muslimah Dina - Percut',
                    'alamat' => null,
                    'status' => 'BUKA',
                    'label' => 'Cabang Percut',
                ],
            ]);
        }

        return $branches;
    }

    private function getSelectedDate(Request $request)
    {
        try {
            return Carbon::parse($request->query('tanggal', now()->toDateString()))->toDateString();
        } catch (\Exception $exception) {
            return now()->toDateString();
        }
    }

    private function getDateOptions()
    {
        return collect(range(0, 6))->map(function ($day) {
            $date = now()->addDays($day);

            return (object) [
                'date' => $date->toDateString(),
                'label' => $date->locale('id')->translatedFormat('d F Y'),
                'day' => $date->locale('id')->translatedFormat('l'),
            ];
        });
    }

    private function getStaffList()
    {
        return DB::table('pegawai as p')
            ->join('users as u', 'u.user_id', '=', 'p.user_id')
            ->where('u.role', 'pegawai')
            ->where(function ($query) {
                $query->whereNull('u.status_akun')
                    ->orWhere('u.status_akun', 'aktif');
            })
            ->where(function ($query) {
                $query->whereNull('p.status_kerja')
                    ->orWhere('p.status_kerja', '!=', 'resign');
            })
            ->select(
                'p.pegawai_id',
                'p.user_id',
                'p.cabang_id',
                'p.jabatan',
                'p.status_kerja',
                'u.nama',
                'u.email',
                'u.no_hp',
                'u.foto_profile'
            )
            ->orderBy('u.nama', 'asc')
            ->get();
    }

    private function getHargaSelect()
    {
        if (Schema::hasColumn('layanan_cabang', 'harga')) {
            return 'COALESCE(lc.harga, 0) as harga';
        }

        if (Schema::hasColumn('layanan', 'harga')) {
            return 'COALESCE(l.harga, 0) as harga';
        }

        return '0 as harga';
    }

    private function getServices($selectedCabangId)
    {
        return DB::table('layanan_cabang as lc')
            ->join('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->where('lc.cabang_id', $selectedCabangId)
            ->select(
                'lc.layanan_cabang_id',
                'lc.layanan_id',
                'lc.cabang_id',
                'l.nama_layanan',
                DB::raw($this->getHargaSelect())
            )
            ->orderBy('l.nama_layanan', 'asc')
            ->get();
    }

    private function getCustomers()
    {
        return DB::table('pelanggan as pl')
            ->leftJoin('users as u', 'u.user_id', '=', 'pl.user_id')
            ->select(
                'pl.pelanggan_id',
                'pl.user_id',
                'u.nama',
                'u.email',
                'u.no_hp'
            )
            ->orderBy('u.nama', 'asc')
            ->get();
    }

    private function getBookingNoteSelect()
    {
        if (Schema::hasColumn('booking', 'catatan')) {
            return 'MAX(b.catatan) as catatan';
        }

        if (Schema::hasColumn('booking', 'keluhan')) {
            return 'MAX(b.keluhan) as catatan';
        }

        return 'NULL as catatan';
    }

    private function getPaymentMethodSelect()
    {
        if (Schema::hasColumn('pembayaran', 'metode_pembayaran')) {
            return 'MAX(py.metode_pembayaran) as metode_pembayaran';
        }

        if (Schema::hasColumn('pembayaran', 'metode')) {
            return 'MAX(py.metode) as metode_pembayaran';
        }

        return 'NULL as metode_pembayaran';
    }

    private function getPaymentStatusSelect()
    {
        if (Schema::hasColumn('pembayaran', 'status')) {
            return 'MAX(py.status) as payment_status';
        }

        if (Schema::hasColumn('pembayaran', 'status_bayar')) {
            return 'MAX(py.status_bayar) as payment_status';
        }

        return 'NULL as payment_status';
    }

    private function getPaymentAmountSelect()
    {
        if (Schema::hasColumn('pembayaran', 'jumlah')) {
            return 'MAX(py.jumlah) as jumlah';
        }

        if (Schema::hasColumn('pembayaran', 'total_bayar')) {
            return 'MAX(py.total_bayar) as jumlah';
        }

        return '0 as jumlah';
    }

    private function getBookings($selectedCabangId, $selectedDate)
    {
        return DB::table('booking as b')
            ->leftJoin('pelanggan as pl', 'pl.pelanggan_id', '=', 'b.pelanggan_id')
            ->leftJoin('users as pelanggan_user', 'pelanggan_user.user_id', '=', 'pl.user_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')
            ->leftJoin('users as pegawai_user', 'pegawai_user.user_id', '=', 'pg.user_id')
            ->leftJoin('pembayaran as py', 'py.booking_id', '=', 'b.booking_id')
            ->whereDate('b.tanggal_booking', $selectedDate)
            ->where('pegawai_user.role', 'pegawai')
            ->where(function ($query) use ($selectedCabangId) {
                $query->where('lc.cabang_id', $selectedCabangId)
                    ->orWhere('pg.cabang_id', $selectedCabangId);
            })
            ->select(
                'b.booking_id',
                'b.pelanggan_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                DB::raw($this->getBookingNoteSelect()),
                'pelanggan_user.nama as pelanggan_nama',
                'pelanggan_user.no_hp as pelanggan_no_hp',
                DB::raw('GROUP_CONCAT(DISTINCT l.nama_layanan ORDER BY l.nama_layanan SEPARATOR ", ") as layanan_nama'),
                DB::raw('MAX(lc.layanan_cabang_id) as layanan_cabang_id'),
                DB::raw('MAX(pg.pegawai_id) as pegawai_id'),
                DB::raw('MAX(pegawai_user.nama) as pegawai_nama'),
                DB::raw($this->getPaymentMethodSelect()),
                DB::raw($this->getPaymentStatusSelect()),
                DB::raw($this->getPaymentAmountSelect())
            )
            ->groupBy(
                'b.booking_id',
                'b.pelanggan_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                'pelanggan_user.nama',
                'pelanggan_user.no_hp'
            )
            ->orderBy('b.jam_booking', 'asc')
            ->get();
    }

    private function getTimes()
    {
        return collect(range(9, 17))->map(function ($hour) {
            return sprintf('%02d:00', $hour);
        })->toArray();
    }

    private function getJadwalPegawai($selectedDate, $staffList)
    {
        $staffIds = $staffList->pluck('pegawai_id')->filter()->values();

        return DB::table('jadwal_pegawai')
            ->whereDate('tanggal', $selectedDate)
            ->whereIn('pegawai_id', $staffIds->isEmpty() ? [0] : $staffIds->all())
            ->select(
                'jadwal_pegawai_id',
                'pegawai_id',
                'tanggal',
                'jam_mulai',
                'jam_selesai',
                'status_ketersediaan'
            )
            ->get();
    }

    private function buildScheduleGrid($times, $staffList, $bookings, $jadwalPegawai)
    {
        $bookingsByStaffTime = $bookings->keyBy(function ($booking) {
            return ($booking->pegawai_id ?? '-') . '|' . substr((string) $booking->jam_booking, 0, 5);
        });

        $scheduleGrid = [];

        foreach ($times as $time) {
            foreach ($staffList as $staff) {
                $key = $staff->pegawai_id . '|' . $time;
                $booking = $bookingsByStaffTime->get($key);

                if ($booking) {
                    $isPending = ($booking->payment_status === 'pending')
                        || ($booking->payment_status === 'belum_bayar')
                        || ($booking->status === 'pending');

                    $scheduleGrid[$time][$staff->pegawai_id] = (object) [
                        'type' => $isPending ? 'pending' : 'booked',
                        'booking_id' => $booking->booking_id,
                        'service' => $booking->layanan_nama ?? '-',
                        'client' => $booking->pelanggan_nama ?? 'Pelanggan',
                        'customer' => $booking->pelanggan_nama ?? 'Pelanggan',
                        'phone' => $booking->pelanggan_no_hp ?? '-',
                        'staff' => $booking->pegawai_nama ?? ($staff->nama ?? 'Specialist'),
                        'time' => $time . '-' . sprintf('%02d:00', ((int) substr($time, 0, 2)) + 1),
                        'payment' => $booking->metode_pembayaran ? strtoupper($booking->metode_pembayaran) : '-',
                        'status' => $booking->status ?? 'confirmed',
                        'note' => $booking->catatan ?? '-',
                    ];

                    continue;
                }

                $currentSchedule = $jadwalPegawai->first(function ($jadwal) use ($staff, $time) {
                    $start = substr((string) $jadwal->jam_mulai, 0, 5);
                    $end = substr((string) $jadwal->jam_selesai, 0, 5);

                    return (int) $jadwal->pegawai_id === (int) $staff->pegawai_id
                        && $time >= $start
                        && $time < $end;
                });

                if ($currentSchedule && $currentSchedule->status_ketersediaan === 'tersedia') {
                    $scheduleGrid[$time][$staff->pegawai_id] = $this->makeEmptyScheduleCell(
                        'available',
                        'Tersedia',
                        $staff,
                        $time,
                        'Slot tersedia'
                    );
                } else {
                    $scheduleGrid[$time][$staff->pegawai_id] = $this->makeEmptyScheduleCell(
                        'break',
                        'Break',
                        $staff,
                        $time,
                        'Specialist tidak tersedia pada jam ini'
                    );
                }
            }
        }

        return $scheduleGrid;
    }

    private function makeEmptyScheduleCell($type, $service, $staff, $time, $note)
    {
        return (object) [
            'type' => $type,
            'booking_id' => null,
            'service' => $service,
            'client' => '-',
            'customer' => '-',
            'phone' => '-',
            'staff' => $staff->nama ?? 'Specialist',
            'time' => $time . '-' . sprintf('%02d:00', ((int) substr($time, 0, 2)) + 1),
            'payment' => '-',
            'status' => $type,
            'note' => $note,
        ];
    }

    private function buildBookingList($bookings)
    {
        return $bookings->map(function ($booking) {
            $statusLabel = match ($booking->status) {
                'pending' => 'Menunggu Pembayaran',
                'confirmed', 'assigned' => 'Dipesan',
                'proses' => 'Berjalan',
                'selesai' => 'Selesai',
                'batal' => 'Dibatalkan',
                default => 'Dipesan',
            };

            if ($booking->payment_status === 'pending' || $booking->payment_status === 'belum_bayar') {
                $statusLabel = 'Menunggu Pembayaran';
            }

            return (object) [
                'id' => $booking->booking_id,
                'time' => substr((string) $booking->jam_booking, 0, 5) . '-' . sprintf('%02d:00', ((int) substr((string) $booking->jam_booking, 0, 2)) + 1),
                'customer' => $booking->pelanggan_nama ?? 'Pelanggan',
                'phone' => $booking->pelanggan_no_hp ?? '-',
                'service' => $booking->layanan_nama ?? '-',
                'staff' => $booking->pegawai_nama ?? '-',
                'payment' => $booking->metode_pembayaran ? strtoupper($booking->metode_pembayaran) : '-',
                'status' => $statusLabel,
                'type' => $statusLabel === 'Menunggu Pembayaran' ? 'pending' : 'booked',
                'note' => $booking->catatan ?? '-',
            ];
        });
    }

    private function getServiceForBooking($layananCabangId)
    {
        return DB::table('layanan_cabang as lc')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->where('lc.layanan_cabang_id', $layananCabangId)
            ->select(
                'lc.layanan_cabang_id',
                'lc.cabang_id',
                DB::raw($this->getHargaSelect())
            )
            ->first();
    }

    private function getValidPegawai($pegawaiId)
    {
        return DB::table('pegawai as p')
            ->join('users as u', 'u.user_id', '=', 'p.user_id')
            ->where('p.pegawai_id', $pegawaiId)
            ->where('u.role', 'pegawai')
            ->where(function ($query) {
                $query->whereNull('u.status_akun')
                    ->orWhere('u.status_akun', 'aktif');
            })
            ->where(function ($query) {
                $query->whereNull('p.status_kerja')
                    ->orWhere('p.status_kerja', '!=', 'resign');
            })
            ->select('p.*')
            ->first();
    }

    private function insertPaymentIfTableExists(Request $request, $service, $newBookingId)
    {
        if (!Schema::hasTable('pembayaran')) {
            return;
        }

        $newPaymentStatus = $request->status === 'pending' ? 'pending' : 'verified';

        $paymentData = [
            'booking_id' => $newBookingId,
        ];

        if (Schema::hasColumn('pembayaran', 'pembayaran_id')) {
            $paymentData['pembayaran_id'] = (DB::table('pembayaran')->max('pembayaran_id') ?? 0) + 1;
        }

        if (Schema::hasColumn('pembayaran', 'metode_pembayaran')) {
            $paymentData['metode_pembayaran'] = $request->metode_pembayaran;
        }

        if (Schema::hasColumn('pembayaran', 'metode')) {
            $paymentData['metode'] = $request->metode_pembayaran;
        }

        if (Schema::hasColumn('pembayaran', 'status')) {
            $paymentData['status'] = $newPaymentStatus;
        }

        if (Schema::hasColumn('pembayaran', 'status_bayar')) {
            $paymentData['status_bayar'] = $newPaymentStatus;
        }

        if (Schema::hasColumn('pembayaran', 'jumlah')) {
            $paymentData['jumlah'] = $service->harga ?? 0;
        }

        if (Schema::hasColumn('pembayaran', 'total_bayar')) {
            $paymentData['total_bayar'] = $service->harga ?? 0;
        }

        if (Schema::hasColumn('pembayaran', 'created_at')) {
            $paymentData['created_at'] = now();
        }

        if (Schema::hasColumn('pembayaran', 'updated_at')) {
            $paymentData['updated_at'] = now();
        }

        DB::table('pembayaran')->insert($paymentData);
    }

    private function updatePaymentStatusIfExists($bookingId, $status)
    {
        if (!Schema::hasTable('pembayaran')) {
            return;
        }

        $paymentUpdate = [];

        if (Schema::hasColumn('pembayaran', 'status')) {
            $paymentUpdate['status'] = $status === 'pending' ? 'pending' : 'verified';
        }

        if (Schema::hasColumn('pembayaran', 'status_bayar')) {
            $paymentUpdate['status_bayar'] = $status === 'pending' ? 'pending' : 'verified';
        }

        if (Schema::hasColumn('pembayaran', 'updated_at')) {
            $paymentUpdate['updated_at'] = now();
        }

        if (!empty($paymentUpdate)) {
            DB::table('pembayaran')
                ->where('booking_id', $bookingId)
                ->update($paymentUpdate);
        }
    }
}