<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjadwalanAdminController extends Controller
{
    public function index(Request $request)
    {
        $branches = $this->getBranches();

        $selectedCabangId = (int) $request->query(
            'cabang_id',
            $branches->first()->cabang_id ?? 1
        );

        if (!$branches->contains('cabang_id', $selectedCabangId)) {
            $selectedCabangId = (int) ($branches->first()->cabang_id ?? 1);
        }

        $selectedBranch  = $branches->firstWhere('cabang_id', $selectedCabangId);
        $selectedDate    = $this->getSelectedDate($request);
        $dateOptions     = $this->getDateOptions();
        $staffList       = $this->getStaffList($selectedCabangId);
        $services        = $this->getServices($selectedCabangId);
        $packages        = $this->getPackages($selectedCabangId);
        $customers       = $this->getCustomers();
        $bookings        = $this->getBookings($selectedCabangId, $selectedDate);
        $times           = $this->getTimes();
        $jadwalPegawai   = $this->getJadwalPegawai($selectedDate, $staffList);
        $scheduleGrid    = $this->buildScheduleGrid($times, $staffList, $bookings, $jadwalPegawai);
        $bookingList     = $this->buildBookingList($bookings);

        return view('admin.penjadwalan.penjadwalanadmin', compact(
            'branches', 'selectedBranch', 'selectedCabangId',
            'selectedDate', 'dateOptions', 'staffList',
            'services', 'packages', 'customers',
            'times', 'scheduleGrid', 'bookingList'
        ));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'booking_type'      => 'required|in:layanan,paket',
            'layanan_cabang_id' => 'required_if:booking_type,layanan|nullable|integer',
            'paket_cabang_id'   => 'required_if:booking_type,paket|nullable|integer',
            'pelanggan_id'      => 'required|integer',
            'pegawai_id'        => 'required|integer',
            'tanggal_booking'   => 'required|date',
            'jam_booking'       => 'required',
            'metode_pembayaran' => 'required|in:cash,qris',
            'status'            => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $isPaket  = $request->booking_type === 'paket';
        $cabangId = null;
        $harga    = 0;

        if ($isPaket) {
            $paket = $this->getPaketForBooking($request->paket_cabang_id);
            if (!$paket) {
                return back()->with('error', 'Paket tidak ditemukan.');
            }
            $cabangId = $paket->cabang_id;
            $harga    = $paket->harga;
        } else {
            $service = $this->getServiceForBooking($request->layanan_cabang_id);
            if (!$service) {
                return back()->with('error', 'Layanan tidak ditemukan.');
            }
            $cabangId = $service->cabang_id;
            $harga    = $service->harga;
        }

        $pegawai = $this->getValidPegawai($request->pegawai_id, $cabangId);
        if (!$pegawai) {
            return back()->with('error', 'Specialist tidak ditemukan atau tidak sesuai cabang.');
        }

        try {
            DB::transaction(function () use ($request, $isPaket, $harga) {
                // Cek 1: pelanggan sudah booking di jam yang sama (sesuai uniq_booking DB)
                $pelangganBooked = DB::table('booking')
                    ->where('pelanggan_id', $request->pelanggan_id)
                    ->whereDate('tanggal_booking', $request->tanggal_booking)
                    ->whereTime('jam_booking', $request->jam_booking)
                    ->whereNotIn('status', ['cancelled'])
                    ->lockForUpdate()
                    ->exists();

                if ($pelangganBooked) {
                    throw new \RuntimeException('Pelanggan sudah memiliki booking pada jam tersebut.');
                }

                // Cek 2: specialist sudah ada booking di jam yang sama
                $pegawaiBooked = DB::table('booking')
                    ->where('pegawai_id', $request->pegawai_id)
                    ->whereDate('tanggal_booking', $request->tanggal_booking)
                    ->whereTime('jam_booking', $request->jam_booking)
                    ->whereNotIn('status', ['cancelled'])
                    ->lockForUpdate()
                    ->exists();

                if ($pegawaiBooked) {
                    throw new \RuntimeException('Specialist sudah memiliki booking pada jam tersebut.');
                }

                // Gunakan AUTO_INCREMENT — tidak perlu manual max()+1
                $newBookingId = DB::table('booking')->insertGetId([
                    'pelanggan_id'    => $request->pelanggan_id,
                    'tanggal_booking' => $request->tanggal_booking,
                    'jam_booking'     => $request->jam_booking,
                    'status'          => $request->status,
                    'tipe_booking'    => 'offline',
                    'pegawai_id'      => $request->pegawai_id,
                    'created_by'      => auth()->id() ?? 1,
                    'created_at'      => now(),
                ], 'booking_id');

                DB::table('booking_detail')->insert([
                    'booking_id'        => $newBookingId,
                    'layanan_cabang_id' => $isPaket ? null : $request->layanan_cabang_id,
                    'paket_cabang_id'   => $isPaket ? $request->paket_cabang_id : null,
                    'harga_snapshot'    => $harga,
                ]);

                DB::table('pembayaran')->insert([
                    'booking_id'        => $newBookingId,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'jumlah'            => $harga,
                    'status'            => 'pending',
                    'tanggal_bayar'     => null,
                    'verified_by'       => null,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            return back()->with('error', 'Pelanggan sudah memiliki booking pada jam tersebut.');
        }

        return redirect()
            ->route('admin.penjadwalan', [
                'cabang_id' => $cabangId,
                'tanggal'   => $request->tanggal_booking,
            ])
            ->with('success', 'Booking berhasil ditambahkan.');
    }

    public function updateBooking(Request $request, $booking_id)
    {
        $request->validate([
            'booking_type'      => 'required|in:layanan,paket',
            'layanan_cabang_id' => 'required_if:booking_type,layanan|nullable|integer',
            'paket_cabang_id'   => 'required_if:booking_type,paket|nullable|integer',
            'pelanggan_id'      => 'required|integer',
            'pegawai_id'        => 'required|integer',
            'tanggal_booking'   => 'required|date',
            'jam_booking'       => 'required',
            'metode_pembayaran' => 'required|in:cash,qris',
            'status'            => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $booking = DB::table('booking')->where('booking_id', $booking_id)->first();
        if (!$booking) {
            return back()->with('error', 'Booking tidak ditemukan.');
        }

        $isPaket  = $request->booking_type === 'paket';
        $cabangId = null;
        $harga    = 0;

        if ($isPaket) {
            $paket = $this->getPaketForBooking($request->paket_cabang_id);
            if (!$paket) {
                return back()->with('error', 'Paket tidak ditemukan.');
            }
            $cabangId = $paket->cabang_id;
            $harga    = $paket->harga;
        } else {
            $service = $this->getServiceForBooking($request->layanan_cabang_id);
            if (!$service) {
                return back()->with('error', 'Layanan tidak ditemukan.');
            }
            $cabangId = $service->cabang_id;
            $harga    = $service->harga;
        }

        $pegawai = $this->getValidPegawai($request->pegawai_id, $cabangId);
        if (!$pegawai) {
            return back()->with('error', 'Specialist tidak ditemukan atau tidak sesuai cabang.');
        }

        try {
            DB::transaction(function () use ($request, $booking_id, $isPaket, $harga) {
                // Cek 1: pelanggan lain (bukan booking ini) sudah ada di jam yang sama
                $pelangganBooked = DB::table('booking')
                    ->where('booking_id', '!=', $booking_id)
                    ->where('pelanggan_id', $request->pelanggan_id)
                    ->whereDate('tanggal_booking', $request->tanggal_booking)
                    ->whereTime('jam_booking', $request->jam_booking)
                    ->whereNotIn('status', ['cancelled'])
                    ->lockForUpdate()
                    ->exists();

                if ($pelangganBooked) {
                    throw new \RuntimeException('Pelanggan sudah memiliki booking pada jam tersebut.');
                }

                // Cek 2: specialist sudah ada di jam yang sama
                $pegawaiBooked = DB::table('booking')
                    ->where('booking_id', '!=', $booking_id)
                    ->where('pegawai_id', $request->pegawai_id)
                    ->whereDate('tanggal_booking', $request->tanggal_booking)
                    ->whereTime('jam_booking', $request->jam_booking)
                    ->whereNotIn('status', ['cancelled', 'completed'])
                    ->lockForUpdate()
                    ->exists();

                if ($pegawaiBooked) {
                    throw new \RuntimeException('Specialist sudah memiliki booking pada jam tersebut.');
                }

                DB::table('booking')->where('booking_id', $booking_id)->update([
                    'pelanggan_id'    => $request->pelanggan_id,
                    'tanggal_booking' => $request->tanggal_booking,
                    'jam_booking'     => $request->jam_booking,
                    'status'          => $request->status,
                    'tipe_booking'    => 'offline',
                    'pegawai_id'      => $request->pegawai_id,
                ]);

                $existingDetail = DB::table('booking_detail')
                    ->where('booking_id', $booking_id)
                    ->orderBy('booking_detail_id', 'asc')
                    ->first();

                if ($existingDetail) {
                    DB::table('booking_detail')
                        ->where('booking_detail_id', $existingDetail->booking_detail_id)
                        ->update([
                            'layanan_cabang_id' => $isPaket ? null : $request->layanan_cabang_id,
                            'paket_cabang_id'   => $isPaket ? $request->paket_cabang_id : null,
                            'harga_snapshot'    => $harga,
                        ]);
                } else {
                    DB::table('booking_detail')->insert([
                        'booking_id'        => $booking_id,
                        'layanan_cabang_id' => $isPaket ? null : $request->layanan_cabang_id,
                        'paket_cabang_id'   => $isPaket ? $request->paket_cabang_id : null,
                        'harga_snapshot'    => $harga,
                    ]);
                }

                $existingPayment = DB::table('pembayaran')
                    ->where('booking_id', $booking_id)
                    ->orderByDesc('pembayaran_id')
                    ->first();

                if ($existingPayment) {
                    DB::table('pembayaran')
                        ->where('pembayaran_id', $existingPayment->pembayaran_id)
                        ->update([
                            'metode_pembayaran' => $request->metode_pembayaran,
                            'jumlah'            => $harga,
                        ]);
                } else {
                    DB::table('pembayaran')->insert([
                        'booking_id'        => $booking_id,
                        'metode_pembayaran' => $request->metode_pembayaran,
                        'jumlah'            => $harga,
                        'status'            => 'pending',
                        'tanggal_bayar'     => null,
                        'verified_by'       => null,
                    ]);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            return back()->with('error', 'Pelanggan sudah memiliki booking pada jam tersebut.');
        }

        return redirect()
            ->route('admin.penjadwalan', [
                'cabang_id' => $cabangId,
                'tanggal'   => $request->tanggal_booking,
            ])
            ->with('success', 'Booking berhasil diperbarui.');
    }

    public function updateBookingStatus(Request $request, $booking_id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled,payment_pending,payment_verified',
        ]);

        $booking = DB::table('booking')->where('booking_id', $booking_id)->first();
        if (!$booking) {
            return back()->with('error', 'Booking tidak ditemukan.');
        }

        if (in_array($request->status, ['payment_pending', 'payment_verified'], true)) {
            $paymentStatus = $request->status === 'payment_verified' ? 'verified' : 'pending';

            DB::transaction(function () use ($booking_id, $paymentStatus) {
                $existingPayment = DB::table('pembayaran')
                    ->where('booking_id', $booking_id)
                    ->orderByDesc('pembayaran_id')
                    ->first();

                if ($existingPayment) {
                    DB::table('pembayaran')
                        ->where('pembayaran_id', $existingPayment->pembayaran_id)
                        ->update([
                            'status'        => $paymentStatus,
                            'tanggal_bayar' => $paymentStatus === 'verified' ? now() : null,
                            'verified_by'   => $paymentStatus === 'verified' ? (auth()->id() ?? 1) : null,
                        ]);
                }

                if ($paymentStatus === 'verified') {
                    DB::table('booking')
                        ->where('booking_id', $booking_id)
                        ->update(['status' => 'completed']);
                }
            });

            return back()->with('success',
                $paymentStatus === 'verified'
                    ? 'Pembayaran berhasil diverifikasi.'
                    : 'Status pembayaran diubah ke pending.'
            );
        }

        DB::table('booking')
            ->where('booking_id', $booking_id)
            ->update(['status' => $request->status]);

        if ($request->status === 'cancelled') {
            DB::table('pembayaran')
                ->where('booking_id', $booking_id)
                ->whereIn('status', ['pending', 'verified'])
                ->update([
                    'status'        => 'on_hold',
                    'tanggal_bayar' => null,
                    'verified_by'   => null,
                ]);
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function cancelBooking($booking_id)
    {
        $booking = DB::table('booking')->where('booking_id', $booking_id)->first();
        if (!$booking) {
            return back()->with('error', 'Booking tidak ditemukan.');
        }

        DB::table('booking')
            ->where('booking_id', $booking_id)
            ->update(['status' => 'cancelled']);

        DB::table('pembayaran')
            ->where('booking_id', $booking_id)
            ->whereIn('status', ['pending', 'verified'])
            ->update([
                'status'        => 'on_hold',
                'tanggal_bayar' => null,
                'verified_by'   => null,
            ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    private function getBranches()
    {
        return DB::table('cabang')
            ->select('cabang_id', 'nama_cabang', 'alamat', 'status')
            ->orderBy('cabang_id', 'asc')
            ->get()
            ->map(function ($branch) {
                $branch->label = $branch->nama_cabang;
                return $branch;
            });
    }

    private function getSelectedDate(Request $request)
    {
        try {
            return Carbon::parse($request->query('tanggal', now()->toDateString()))->toDateString();
        } catch (\Throwable $e) {
            return now()->toDateString();
        }
    }

    private function getDateOptions()
    {
        return collect(range(0, 6))->map(function ($day) {
            $date = now()->addDays($day);
            return (object) [
                'date'  => $date->toDateString(),
                'label' => $date->locale('id')->translatedFormat('d F Y'),
                'day'   => $date->locale('id')->translatedFormat('l'),
            ];
        });
    }

    private function getStaffList($selectedCabangId = null)
    {
        $query = DB::table('pegawai as p')
            ->join('users as u', 'u.user_id', '=', 'p.user_id')
            ->where('u.role', 'pegawai')
            ->where(function ($q) {
                $q->whereNull('u.status_akun')->orWhere('u.status_akun', 'aktif');
            })
            ->where(function ($q) {
                $q->whereNull('p.status_kerja')->orWhere('p.status_kerja', '!=', 'resign');
            });

        if ($selectedCabangId) {
            $query->where('p.cabang_id', $selectedCabangId);
        }

        return $query
            ->select(
                'p.pegawai_id',
                'p.user_id',
                'p.cabang_id',
                'p.status_kerja',
                DB::raw("NULL as jabatan"),
                'u.nama',
                'u.email',
                'u.no_hp',
                'u.foto_profile'
            )
            ->orderBy('u.nama', 'asc')
            ->get();
    }

    private function getServices($selectedCabangId)
    {
        return DB::table('layanan_cabang as lc')
            ->join('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->where('lc.cabang_id', $selectedCabangId)
            ->where(function ($q) {
                $q->whereNull('lc.status')->orWhere('lc.status', 'tersedia');
            })
            ->select(
                'lc.layanan_cabang_id',
                'lc.layanan_id',
                'lc.cabang_id',
                'l.nama_layanan',
                DB::raw('COALESCE(lc.harga_promo, lc.harga, 0) as harga')
            )
            ->orderBy('l.nama_layanan', 'asc')
            ->get();
    }

    private function getPackages($selectedCabangId)
    {
        return DB::table('paket_cabang as pc')
            ->join('paket_layanan as pl', 'pl.paket_id', '=', 'pc.paket_id')
            ->where('pc.cabang_id', $selectedCabangId)
            ->where(function ($q) {
                $q->whereNull('pc.status')->orWhere('pc.status', 'tersedia');
            })
            ->select(
                'pc.paket_cabang_id',
                'pc.paket_id',
                'pc.cabang_id',
                'pl.nama_paket',
                DB::raw('COALESCE(pc.harga_promo, pc.harga_normal, 0) as harga')
            )
            ->orderBy('pl.nama_paket', 'asc')
            ->get();
    }

    private function getCustomers()
    {
        return DB::table('pelanggan as pl')
            ->leftJoin('users as u', 'u.user_id', '=', 'pl.user_id')
            ->select('pl.pelanggan_id', 'pl.user_id', 'u.nama', 'u.email', 'u.no_hp')
            ->orderBy('u.nama', 'asc')
            ->get();
    }

    private function getBookings($selectedCabangId, $selectedDate)
    {
        return DB::table('booking as b')
            ->leftJoin('pelanggan as pl', 'pl.pelanggan_id', '=', 'b.pelanggan_id')
            ->leftJoin('users as pelanggan_user', 'pelanggan_user.user_id', '=', 'pl.user_id')
            ->leftJoin('booking_detail as bd', 'bd.booking_id', '=', 'b.booking_id')
            ->leftJoin('layanan_cabang as lc', 'lc.layanan_cabang_id', '=', 'bd.layanan_cabang_id')
            ->leftJoin('layanan as l', 'l.layanan_id', '=', 'lc.layanan_id')
            ->leftJoin('paket_cabang as pc', 'pc.paket_cabang_id', '=', 'bd.paket_cabang_id')
            ->leftJoin('paket_layanan as pkt', 'pkt.paket_id', '=', 'pc.paket_id')
            ->leftJoin('pegawai as pg', 'pg.pegawai_id', '=', 'b.pegawai_id')
            ->leftJoin('users as pegawai_user', 'pegawai_user.user_id', '=', 'pg.user_id')
            ->leftJoin('pembayaran as py', 'py.booking_id', '=', 'b.booking_id')
            ->whereDate('b.tanggal_booking', $selectedDate)
            ->where(function ($q) use ($selectedCabangId) {
                $q->where('lc.cabang_id', $selectedCabangId)
                  ->orWhere('pc.cabang_id', $selectedCabangId)
                  ->orWhere('pg.cabang_id', $selectedCabangId);
            })
            ->select(
                'b.booking_id',
                'b.pelanggan_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                'b.pegawai_id',
                'pelanggan_user.nama as pelanggan_nama',
                'pelanggan_user.no_hp as pelanggan_no_hp',
                DB::raw('NULL as catatan'),
                DB::raw("COALESCE(MAX(l.nama_layanan), MAX(pkt.nama_paket)) as layanan_nama"),
                DB::raw("CASE WHEN MAX(bd.paket_cabang_id) IS NOT NULL THEN 'paket' ELSE 'layanan' END as booking_type"),
                DB::raw('MAX(bd.layanan_cabang_id) as layanan_cabang_id'),
                DB::raw('MAX(bd.paket_cabang_id) as paket_cabang_id'),
                DB::raw('MAX(pg.pegawai_id) as resolved_pegawai_id'),
                DB::raw('MAX(pegawai_user.nama) as pegawai_nama'),
                DB::raw('MAX(py.metode_pembayaran) as metode_pembayaran'),
                DB::raw('MAX(py.status) as payment_status'),
                DB::raw('MAX(py.jumlah) as jumlah'),
                DB::raw('MAX(py.pembayaran_id) as pembayaran_id')
            )
            ->groupBy(
                'b.booking_id',
                'b.pelanggan_id',
                'b.tanggal_booking',
                'b.jam_booking',
                'b.status',
                'b.pegawai_id',
                'pelanggan_user.nama',
                'pelanggan_user.no_hp'
            )
            ->orderBy('b.jam_booking', 'asc')
            ->get();
    }

    private function getTimes()
    {
        return collect(range(9, 17))
            ->map(fn($h) => sprintf('%02d:00', $h))
            ->toArray();
    }

    private function getJadwalPegawai($selectedDate, $staffList)
    {
        $staffIds = $staffList->pluck('pegawai_id')->filter()->values();

        return DB::table('jadwal_pegawai')
            ->whereDate('tanggal', $selectedDate)
            ->whereIn('pegawai_id', $staffIds->isEmpty() ? [0] : $staffIds->all())
            ->select('jadwal_pegawai_id', 'pegawai_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'status_ketersediaan')
            ->get();
    }

    private function buildScheduleGrid($times, $staffList, $bookings, $jadwalPegawai)
    {
        $firstStaffId   = $staffList->first()->pegawai_id ?? null;
        $bookingsByTime = $bookings->groupBy(fn($b) => substr((string) $b->jam_booking, 0, 5));
        $scheduleGrid   = [];

        foreach ($times as $time) {
            foreach ($staffList as $staff) {
                $bookingsAtTime = $bookingsByTime->get($time, collect());

                $booking = $bookingsAtTime->first(
                    fn($item) => (int) $item->pegawai_id === (int) $staff->pegawai_id
                );

                if (!$booking && (int) $staff->pegawai_id === (int) $firstStaffId) {
                    $booking = $bookingsAtTime->first(fn($item) => empty($item->pegawai_id));
                }

                if ($booking) {
                    $bookingStatus = strtolower($booking->status ?? '');

                    $cellType = match ($bookingStatus) {
                        'in_progress' => 'in_progress',
                        'completed'   => 'completed',
                        'cancelled'   => 'cancelled',
                        'pending'     => 'pending',
                        default       => 'booked',
                    };

                    $timeLabel = $time . '-' . sprintf('%02d:00', ((int) substr($time, 0, 2)) + 1);

                    $scheduleGrid[$time][$staff->pegawai_id] = (object) [
                        'type'              => $cellType,
                        'booking_id'        => $booking->booking_id,
                        'pelanggan_id'      => $booking->pelanggan_id,
                        'layanan_cabang_id' => $booking->layanan_cabang_id,
                        'paket_cabang_id'   => $booking->paket_cabang_id,
                        'booking_type'      => $booking->booking_type,
                        'pegawai_id'        => $booking->pegawai_id,
                        'tanggal_booking'   => $booking->tanggal_booking,
                        'jam_booking'       => substr((string) $booking->jam_booking, 0, 5),
                        'payment_raw'       => strtolower($booking->metode_pembayaran ?? 'cash'),
                        'status_raw'        => $booking->status ?? 'pending',
                        'service'           => $booking->layanan_nama ?? '-',
                        'client'            => $booking->pelanggan_nama ?? 'Pelanggan',
                        'customer'          => $booking->pelanggan_nama ?? 'Pelanggan',
                        'phone'             => $booking->pelanggan_no_hp ?? '-',
                        'staff'             => $booking->pegawai_nama ?? 'Belum assign',
                        'time'              => $timeLabel,
                        'payment'           => strtoupper($booking->metode_pembayaran ?? '-'),
                        'status'            => $this->statusLabel($booking->status ?? 'pending'),
                        'note'              => '-',
                    ];
                    continue;
                }

                $currentSchedule = $jadwalPegawai->first(function ($j) use ($staff, $time) {
                    $start = substr((string) $j->jam_mulai,   0, 5);
                    $end   = substr((string) $j->jam_selesai, 0, 5);
                    return (int) $j->pegawai_id === (int) $staff->pegawai_id
                        && $time >= $start
                        && $time < $end;
                });

                $slotType = ($currentSchedule && $currentSchedule->status_ketersediaan === 'tidak_tersedia')
                    ? 'break'
                    : 'available';

                $scheduleGrid[$time][$staff->pegawai_id] = $this->makeEmptyScheduleCell(
                    $slotType, $staff, $time
                );
            }
        }

        return $scheduleGrid;
    }

    private function makeEmptyScheduleCell($type, $staff, $time)
    {
        $timeLabel = $time . '-' . sprintf('%02d:00', ((int) substr($time, 0, 2)) + 1);
        return (object) [
            'type'              => $type,
            'booking_id'        => null,
            'pelanggan_id'      => null,
            'layanan_cabang_id' => null,
            'paket_cabang_id'   => null,
            'booking_type'      => 'layanan',
            'pegawai_id'        => $staff->pegawai_id ?? null,
            'tanggal_booking'   => null,
            'jam_booking'       => substr((string) $time, 0, 5),
            'payment_raw'       => 'cash',
            'status_raw'        => $type,
            'service'           => $type === 'break' ? 'Break' : 'Tersedia',
            'client'            => '-',
            'customer'          => '-',
            'phone'             => '-',
            'staff'             => $staff->nama ?? 'Specialist',
            'time'              => $timeLabel,
            'payment'           => '-',
            'status'            => $type,
            'note'              => $type === 'break' ? 'Specialist tidak tersedia' : 'Slot tersedia',
        ];
    }

    private function buildBookingList($bookings)
    {
        return $bookings->map(function ($booking) {
            $startTime = substr((string) $booking->jam_booking, 0, 5);
            $endTime   = sprintf('%02d:00', ((int) substr((string) $booking->jam_booking, 0, 2)) + 1);

            return (object) [
                'id'                => $booking->booking_id,
                'pelanggan_id'      => $booking->pelanggan_id,
                'layanan_cabang_id' => $booking->layanan_cabang_id,
                'paket_cabang_id'   => $booking->paket_cabang_id,
                'booking_type'      => $booking->booking_type,
                'pegawai_id'        => $booking->pegawai_id,
                'tanggal_booking'   => $booking->tanggal_booking,
                'jam_booking'       => $startTime,
                'payment_raw'       => strtolower($booking->metode_pembayaran ?? 'cash'),
                'status_raw'        => $booking->status ?? 'pending',
                'time'              => $startTime . '-' . $endTime,
                'customer'          => $booking->pelanggan_nama ?? 'Pelanggan',
                'phone'             => $booking->pelanggan_no_hp ?? '-',
                'service'           => $booking->layanan_nama ?? '-',
                'staff'             => $booking->pegawai_nama ?? 'Belum assign',
                'payment'           => strtoupper($booking->metode_pembayaran ?? '-'),
                'status'            => $this->statusLabel($booking->status ?? 'pending'),
                'type'              => in_array($booking->status, ['pending']) ? 'pending' : 'booked',
                'note'              => '-',
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
                DB::raw('COALESCE(lc.harga_promo, lc.harga, 0) as harga')
            )
            ->first();
    }

    private function getPaketForBooking($paketCabangId)
    {
        return DB::table('paket_cabang as pc')
            ->join('paket_layanan as pl', 'pl.paket_id', '=', 'pc.paket_id')
            ->where('pc.paket_cabang_id', $paketCabangId)
            ->select(
                'pc.paket_cabang_id',
                'pc.cabang_id',
                'pl.nama_paket',
                DB::raw('COALESCE(pc.harga_promo, pc.harga_normal, 0) as harga')
            )
            ->first();
    }

    private function getValidPegawai($pegawaiId, $cabangId = null)
    {
        $query = DB::table('pegawai as p')
            ->join('users as u', 'u.user_id', '=', 'p.user_id')
            ->where('p.pegawai_id', $pegawaiId)
            ->where('u.role', 'pegawai')
            ->where(function ($q) {
                $q->whereNull('u.status_akun')->orWhere('u.status_akun', 'aktif');
            })
            ->where(function ($q) {
                $q->whereNull('p.status_kerja')->orWhere('p.status_kerja', '!=', 'resign');
            });

        if ($cabangId) {
            $query->where('p.cabang_id', $cabangId);
        }

        return $query->select('p.*')->first();
    }

    private function statusLabel($status)
    {
        return match (strtolower($status)) {
            'pending'     => 'Tunda',
            'confirmed'   => 'Dikonfirmasi',
            'in_progress' => 'Berjalan',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
            default       => 'Dikonfirmasi',
        };
    }
}