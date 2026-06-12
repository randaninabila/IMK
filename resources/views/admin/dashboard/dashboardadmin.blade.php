<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dina Salon Muslimah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            overflow-x: hidden;
        }

        .admin-shadow {
            box-shadow: 0 8px 18px rgba(58, 55, 46, 0.13);
        }

        .soft-shadow {
            box-shadow: 0 12px 28px rgba(58, 55, 46, 0.10);
        }

        .modal-bg {
            background: rgba(0, 0, 0, 0.35);
        }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B3A36]">

@php
    $branches = $branches ?? collect();
    $selectedBranch = $selectedBranch ?? null;
    $selectedCabangId = $selectedCabangId ?? 1;
    $selectedDate = $selectedDate ?? null;

    $isAllDate = empty($selectedDate);

    $dateOptions = $dateOptions ?? collect(range(0, 6))->map(function ($day) {
        $date = now()->addDays($day);

        return (object) [
            'date' => $date->toDateString(),
            'label' => $date->locale('id')->translatedFormat('d F Y'),
            'day' => $date->locale('id')->translatedFormat('l'),
        ];
    });

    if ($isAllDate) {
        $selectedDateLabel = 'Semua Tanggal';
        $selectedDayLabel = 'Semua Booking';
        $summaryDateLabel = 'Semua tanggal';
    } else {
        $selectedDateCarbon = \Carbon\Carbon::parse($selectedDate);
        $selectedDateLabel = $selectedDateCarbon->locale('id')->translatedFormat('d F Y');
        $selectedDayLabel = $selectedDateCarbon->locale('id')->translatedFormat('l');
        $summaryDateLabel = $selectedDateLabel;
    }

    $dashboardParams = function ($cabangId, $tanggal = null) {
        $params = ['cabang_id' => $cabangId];

        if (!empty($tanggal)) {
            $params['tanggal'] = $tanggal;
        }

        return $params;
    };

    $summary = $summary ?? [
        'total_booking' => 0,
        'completed_booking' => 0,
        'running_booking' => 0,
        'pending_payment' => 0,
        'pending_qris' => 0,
        'pending_cash' => 0,
        'total_income' => 0,
        'cash_income' => 0,
        'qris_income' => 0,
    ];

    $latestBookings = $latestBookings ?? collect();
    $todaySchedules = $todaySchedules ?? collect();
    $allSchedules = $allSchedules ?? collect();

    $formatMoney = function ($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    };

    $formatTime = function ($value) {
        return $value ? \Carbon\Carbon::parse($value)->format('H:i') : '-';
    };

    $formatDate = function ($value) {
        return $value ? \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('d F Y') : '-';
    };

    $formatDateWithDay = function ($value) {
        return $value ? \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('l, d F Y') : '-';
    };

    $normalizeStatus = function ($status) {
        return match ($status) {
            'proses' => 'in_progress',
            'selesai' => 'completed',
            'batal' => 'cancelled',
            'assigned' => 'confirmed',
            default => $status,
        };
    };

    $statusLabel = function ($status) use ($normalizeStatus) {
        $status = $normalizeStatus($status);

        return match ($status) {
            'pending' => 'Pending',
            'confirmed' => 'Dipesan',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'cancelled' => 'Batal',
            default => $status ? ucfirst($status) : '-',
        };
    };

    $statusClass = function ($status) use ($normalizeStatus) {
        $status = $normalizeStatus($status);

        return match ($status) {
            'completed' => 'bg-[#A8BD8C] text-white text-[18px]',
            'in_progress' => 'bg-[#F6E4A5] text-[#C77A45] text-[15px] font-bold',
            'confirmed' => 'bg-[#FDE3E8] text-[#B85C6A] text-[15px] font-bold',
            'pending' => 'bg-[#FFF4D5] text-[#7A6335] text-[14px] font-bold',
            'cancelled' => 'bg-[#F8C2CA] text-[#B85C6A] text-[15px] font-bold',
            default => 'bg-[#F8C2CA] text-[#B85C6A] text-[14px] font-bold',
        };
    };

    $scheduleStatusClass = function ($status) {
        return $status === 'tersedia'
            ? 'text-[#91AD71]'
            : 'text-[#B85C6A]';
    };

    $scheduleStatusLabel = function ($status) {
        return $status === 'tersedia'
            ? 'Tersedia'
            : 'Tidak Tersedia';
    };
@endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="ml-[235px] w-[calc(100%-235px)] min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        <header class="h-[92px] px-[44px] flex items-center justify-between">

            <h2 class="text-[22px] font-extrabold text-[#3F3838] tracking-[-0.03em]">
                Halo, <span class="italic">Admin</span> Salon Dina Muslimah 👋
            </h2>

            <div class="flex items-center gap-[22px]">

                <div class="relative">
                    <button type="button"
                            onclick="toggleDropdown('branchDropdown')"
                            class="h-[50px] min-w-[202px] bg-[#E8A9B4] text-white rounded-[7px] px-[12px] flex items-center justify-between gap-[12px] font-extrabold hover:bg-[#D995A1] transition">
                        <span class="flex items-center gap-[8px]">
                            <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                                <path d="M12 21S5 14.7 5 8.8C5 4.9 8.1 2 12 2C15.9 2 19 4.9 19 8.8C19 14.7 12 21 12 21Z" stroke="white" stroke-width="2"/>
                                <circle cx="12" cy="8.8" r="2.5" stroke="white" stroke-width="2"/>
                            </svg>

                            <span id="branchText" class="text-[13px]">
                                {{ $selectedBranch->label ?? 'Cabang Tembung' }}
                            </span>
                        </span>

                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div id="branchDropdown"
                         class="hidden absolute top-[58px] left-0 w-full bg-white rounded-[12px] shadow-xl border border-[#F1D9DD] overflow-hidden z-50">
                        @foreach($branches as $branch)
                            <a href="{{ route('admin.dashboard', $dashboardParams($branch->cabang_id, $selectedDate)) }}"
                               class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ (int) $selectedCabangId === (int) $branch->cabang_id ? 'bg-[#FFF0F2]' : '' }}">
                                {{ $branch->label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="relative">
                    <button type="button"
                            onclick="toggleDropdown('dateDropdown')"
                            class="h-[50px] min-w-[202px] bg-[#E8A9B4] text-white rounded-[7px] px-[12px] flex items-center justify-between gap-[12px] font-extrabold hover:bg-[#D995A1] transition">
                        <span class="flex items-center gap-[8px]">
                            <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="5" width="18" height="16" rx="2" stroke="white" stroke-width="2"/>
                                <path d="M8 3V7M16 3V7M3 10H21" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            </svg>

                            <span id="dateText" class="text-[13px] leading-tight text-left">
                                {{ $selectedDateLabel }}<br>
                                {{ $selectedDayLabel }}
                            </span>
                        </span>

                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div id="dateDropdown"
                         class="hidden absolute top-[58px] left-0 w-full bg-white rounded-[12px] shadow-xl border border-[#F1D9DD] overflow-hidden z-50">

                        <a href="{{ route('admin.dashboard', ['cabang_id' => $selectedCabangId]) }}"
                           class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ $isAllDate ? 'bg-[#FFF0F2]' : '' }}">
                            Semua Tanggal - Semua Booking
                        </a>

                        @foreach($dateOptions as $dateOption)
                            <a href="{{ route('admin.dashboard', ['cabang_id' => $selectedCabangId, 'tanggal' => $dateOption->date]) }}"
                               class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ $selectedDate === $dateOption->date ? 'bg-[#FFF0F2]' : '' }}">
                                {{ $dateOption->label }} - {{ $dateOption->day }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="relative flex items-center">
                    @include('admin.partial.dropdownadmin')
                </div>

            </div>

        </header>

        <section class="px-[44px] mt-[-12px] grid grid-cols-4 gap-[24px]">

            <button type="button"
                    onclick="showToast(@js('Total booking: ' . $summary['total_booking']))"
                    class="h-[100px] bg-white rounded-[12px] border border-[#E3D2D2] admin-shadow flex items-center px-[12px] gap-[10px] text-left hover:-translate-y-1 transition">
                <div class="w-[60px] h-[60px] rounded-full bg-[#F4A1AC] shrink-0"></div>

                <div>
                    <p class="text-[13px] font-extrabold leading-none mb-[4px] text-[#4B3A36]">
                        Total Booking
                    </p>

                    <h3 class="text-[36px] font-extrabold leading-[0.85] text-[#4B3A36]">
                        {{ $summary['total_booking'] }}
                    </h3>

                    <p class="text-[11px] font-serif mt-[4px] text-[#7B6A62]">
                        {{ $summaryDateLabel }}
                    </p>
                </div>
            </button>

            <button type="button"
                    onclick="showToast(@js('Booking selesai: ' . $summary['completed_booking']))"
                    class="h-[100px] bg-white rounded-[12px] border border-[#E3D2D2] admin-shadow flex items-center px-[12px] gap-[16px] text-left hover:-translate-y-1 transition">
                <div class="w-[60px] h-[60px] rounded-full bg-[#A8BD8C] shrink-0"></div>

                <div>
                    <p class="text-[13px] font-extrabold leading-none mb-[4px] text-[#4B3A36]">
                        Selesai
                    </p>

                    <h3 class="text-[36px] font-extrabold leading-[0.85] text-[#4B3A36]">
                        {{ $summary['completed_booking'] }}
                    </h3>

                    <p class="text-[11px] font-serif mt-[4px] text-[#7B6A62]">
                        {{ $summaryDateLabel }}
                    </p>
                </div>
            </button>

            <button type="button"
                    onclick="showToast(@js('Booking berjalan: ' . $summary['running_booking']))"
                    class="h-[100px] bg-white rounded-[12px] border border-[#E3D2D2] admin-shadow flex items-center px-[12px] gap-[16px] text-left hover:-translate-y-1 transition">
                <div class="w-[60px] h-[60px] rounded-full bg-[#D98973] shrink-0"></div>

                <div>
                    <p class="text-[13px] font-extrabold leading-none mb-[4px] text-[#4B3A36]">
                        Sedang Berjalan
                    </p>

                    <h3 class="text-[36px] font-extrabold leading-[0.85] text-[#4B3A36]">
                        {{ $summary['running_booking'] }}
                    </h3>

                    <p class="text-[11px] font-serif mt-[4px] text-[#7B6A62]">
                        {{ $summaryDateLabel }}
                    </p>
                </div>
            </button>

            <button type="button"
                    onclick="showToast(@js('Menunggu pembayaran: ' . $summary['pending_payment']))"
                    class="h-[100px] bg-white rounded-[12px] border border-[#E3D2D2] admin-shadow flex items-center px-[10px] gap-[12px] text-left hover:-translate-y-1 transition">
                <div class="w-[60px] h-[60px] rounded-full bg-[#9A6272] shrink-0"></div>

                <div>
                    <p class="text-[13px] font-extrabold leading-none mb-[4px] text-[#4B3A36]">
                        Menunggu Pembayaran
                    </p>

                    <div class="flex items-center gap-[8px]">
                        <h3 class="text-[36px] font-extrabold leading-[0.85] text-[#4B3A36]">
                            {{ $summary['pending_payment'] }}
                        </h3>

                        <span class="bg-[#8E4358] text-white text-[13px] font-extrabold rounded-[6px] px-[8px] py-[2px] whitespace-nowrap">
                            {{ $summary['pending_qris'] }} Qris, {{ $summary['pending_cash'] }} Cash
                        </span>
                    </div>

                    <p class="text-[11px] font-serif mt-[4px] text-[#7B6A62]">
                        {{ $summaryDateLabel }}
                    </p>
                </div>
            </button>

        </section>

        <section class="px-[12px] mt-[50px] pb-[60px]">

            <div class="grid grid-cols-[minmax(0,1fr)_370px] gap-[20px]">

                <div class="bg-white rounded-[14px] border border-[#E3D2D2] min-h-[700px] px-[30px] pt-[26px] pb-[20px] soft-shadow">

                    <h2 class="text-[25px] font-extrabold mb-[34px] text-[#4B3A36]">
                        {{ $isAllDate ? 'Ringkasan Semua Tanggal' : 'Ringkasan Hari Ini' }}
                    </h2>

                    <div class="flex items-center justify-between border-b border-[#D7C6C6] pb-[18px]">

                        <button type="button"
                                onclick="showToast(@js('Total Pendapatan: ' . $formatMoney($summary['total_income'])))"
                                class="flex items-center gap-[12px] text-left">
                            <div class="w-[60px] h-[60px] rounded-[16px] bg-[#F4A1AC] shrink-0"></div>

                            <div>
                                <p class="text-[13px] font-extrabold text-[#4B3A36]">
                                    Total Pendapatan
                                </p>

                                <p class="text-[19px] font-extrabold mt-[12px] text-[#4B3A36]">
                                    {{ $formatMoney($summary['total_income']) }}
                                </p>
                            </div>
                        </button>

                        <div class="h-[53px] w-[1px] bg-[#D7C6C6]"></div>

                        <button type="button"
                                onclick="showToast(@js('Pembayaran Cash: ' . $formatMoney($summary['cash_income'])))"
                                class="flex items-center gap-[16px] text-left">
                            <div class="w-[60px] h-[60px] rounded-[18px] bg-[#DDF6C3] shrink-0"></div>

                            <div>
                                <p class="text-[13px] font-extrabold text-[#4B3A36]">
                                    Pembayaran Cash
                                </p>

                                <p class="text-[19px] font-extrabold mt-[12px] text-[#4B3A36]">
                                    {{ $formatMoney($summary['cash_income']) }}
                                </p>
                            </div>
                        </button>

                        <div class="h-[53px] w-[1px] bg-[#D7C6C6]"></div>

                        <button type="button"
                                onclick="showToast(@js('Pembayaran QRIS: ' . $formatMoney($summary['qris_income'])))"
                                class="flex items-center gap-[16px] text-left">
                            <div class="w-[60px] h-[60px] rounded-[18px] bg-[#F1C7EF] shrink-0"></div>

                            <div>
                                <p class="text-[13px] font-extrabold text-[#4B3A36]">
                                    Pembayaran QRIS
                                </p>

                                <p class="text-[19px] font-extrabold mt-[12px] text-[#4B3A36]">
                                    {{ $formatMoney($summary['qris_income']) }}
                                </p>
                            </div>
                        </button>

                    </div>

                    <h3 class="text-[19px] font-extrabold mt-[22px] pb-[14px] border-b border-[#D7C6C6] text-[#4B3A36]">
                        {{ $isAllDate ? 'Semua Booking' : 'Booking Tanggal Ini' }}
                    </h3>

                    <div class="divide-y divide-[#E3D2D2]">

                        @forelse($latestBookings as $booking)
                            <div class="grid grid-cols-[95px_1fr_145px_110px_150px] items-center py-[25px] text-[16px] text-[#4B3A36] gap-[8px]">

                                <span class="bg-[#EFEAEA] text-[#4B3A36] rounded-[8px] px-[12px] py-[2px] w-fit tracking-[0.08em]">
                                    {{ $formatTime($booking->jam_booking) }}
                                </span>

                                <span class="font-semibold">
                                    {{ $booking->pelanggan_nama ?? 'Pelanggan' }}

                                    <span class="block text-[11px] font-serif text-[#7B6A62] mt-[3px]">
                                        {{ $formatDate($booking->tanggal_booking) }}
                                    </span>
                                </span>

                                <span class="text-[14px] leading-tight">
                                    {{ $booking->layanan_nama ?? '-' }}
                                </span>

                                <span class="text-[14px] font-semibold">
                                    {{ $booking->pegawai_nama ?? 'Belum assign' }}
                                </span>

                                <button type="button"
                                        onclick="showToast(@js('Status booking: ' . $statusLabel($booking->status)))"
                                        class="status-btn {{ $statusClass($booking->status) }} rounded-[8px] py-[4px] px-[8px]">
                                    {{ $statusLabel($booking->status) }}
                                </button>

                            </div>
                        @empty
                            <div class="py-[35px] text-center text-[14px] font-semibold text-[#8B7777]">
                                {{ $isAllDate ? 'Belum ada booking untuk cabang ini.' : 'Belum ada booking pada tanggal ini.' }}
                            </div>
                        @endforelse

                    </div>

                    <div class="text-center mt-[28px]">
                        <a href="{{ route('admin.penjadwalan', ['cabang_id' => $selectedCabangId, 'tanggal' => $selectedDate ?: now()->toDateString()]) }}"
                           class="text-[#D88998] text-[18px] font-extrabold hover:text-[#B85C6A] transition">
                            Lihat Penjadwalan →
                        </a>
                    </div>

                </div>

                <div class="bg-white rounded-[14px] border border-[#E3D2D2] min-h-[700px] px-[26px] pt-[26px] pb-[20px] soft-shadow">

                    <h2 class="text-[25px] font-extrabold mb-[34px] text-[#4B3A36]">
                        Jadwal Terjadwal
                    </h2>

                    <div class="border-t border-[#D7C6C6] pt-[20px] space-y-[28px]">

                        @forelse($todaySchedules as $schedule)
                            <div class="grid grid-cols-[80px_1fr_70px] items-center gap-[12px] text-[#4B3A36]">

                                <span class="bg-[#EFEAEA] rounded-[8px] px-[12px] py-[6px] text-[13px] font-extrabold tracking-[0.08em] text-center">
                                    {{ $formatTime($schedule->jam_mulai) }}
                                </span>

                                <button type="button"
                                        onclick="showToast(@js(($schedule->pegawai_nama ?? 'Pegawai') . ' - ' . $formatDate($schedule->tanggal)))"
                                        class="text-left">
                                    <p class="text-[13px] font-extrabold leading-none">
                                        {{ $schedule->pegawai_nama ?? 'Pegawai' }}
                                    </p>

                                    <p class="text-[10px] font-serif leading-none mt-[4px] text-[#7B6A62]">
                                        {{ $formatDate($schedule->tanggal) }}
                                    </p>

                                    <p class="text-[10px] font-serif leading-none mt-[4px] text-[#7B6A62]">
                                        {{ $formatTime($schedule->jam_mulai) }} - {{ $formatTime($schedule->jam_selesai) }}
                                    </p>
                                </button>

                                <button type="button"
                                        onclick="showToast(@js('Status jadwal: ' . $scheduleStatusLabel($schedule->status_ketersediaan)))"
                                        class="mini-status text-[12px] font-extrabold {{ $scheduleStatusClass($schedule->status_ketersediaan) }}">
                                    {{ $scheduleStatusLabel($schedule->status_ketersediaan) }}
                                </button>

                            </div>
                        @empty
                            <div class="text-center text-[13px] font-semibold text-[#8B7777] py-[35px]">
                                Belum ada jadwal untuk cabang ini.
                            </div>
                        @endforelse

                    </div>

                    <div class="text-center mt-[34px]">
                        <button type="button"
                                onclick="openAllSchedulesModal()"
                                class="text-[#D88998] text-[16px] font-extrabold hover:text-[#B85C6A] transition">
                            Lihat Semua Jadwal →
                        </button>
                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

<div id="allScheduleModal"
     onclick="closeAllSchedulesByOverlay(event)"
     class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">

    <div class="w-full max-w-[980px] max-h-[82vh] bg-white rounded-[18px] shadow-2xl overflow-hidden">

        <div class="px-[26px] py-[20px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <div>
                <h2 class="text-[24px] font-extrabold text-[#4B3A36]">
                    Semua Jadwal
                </h2>

                <p class="text-[13px] font-semibold text-[#7B6A62] mt-[4px]">
                    {{ $selectedBranch->label ?? 'Cabang Salon' }} - semua hari yang sudah terjadwalkan
                </p>
            </div>

            <button type="button"
                    onclick="closeAllSchedulesModal()"
                    class="w-[38px] h-[38px] rounded-full bg-[#4B3A36] text-white text-[26px] leading-none flex items-center justify-center">
                ×
            </button>
        </div>

        <div class="max-h-[62vh] overflow-y-auto px-[26px] py-[20px]">

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#F1D9DD] text-[14px] font-extrabold text-[#4B3A36]">
                        <th class="py-[12px] w-[210px]">Tanggal</th>
                        <th class="py-[12px] w-[190px]">Jam</th>
                        <th class="py-[12px]">Pegawai</th>
                        <th class="py-[12px] w-[150px]">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($allSchedules as $schedule)
                        <tr class="border-b border-[#F6E2E6] text-[14px]">
                            <td class="py-[14px] font-semibold">
                                {{ $formatDateWithDay($schedule->tanggal) }}
                            </td>

                            <td class="py-[14px]">
                                {{ $formatTime($schedule->jam_mulai) }} - {{ $formatTime($schedule->jam_selesai) }}
                            </td>

                            <td class="py-[14px]">
                                {{ $schedule->pegawai_nama ?? 'Pegawai' }}
                            </td>

                            <td class="py-[14px]">
                                <span class="font-extrabold {{ $scheduleStatusClass($schedule->status_ketersediaan) }}">
                                    {{ $scheduleStatusLabel($schedule->status_ketersediaan) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-[35px] text-center text-[#8B7777] font-semibold">
                                Belum ada jadwal tersimpan untuk cabang ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>
</div>

<div id="toast"
     class="hidden fixed right-[28px] bottom-[28px] z-[999] bg-[#4B3A36] text-white px-5 py-3 rounded-xl shadow-xl text-sm font-bold">
    Action
</div>

<script>
    function toggleDropdown(id) {
        const target = document.getElementById(id);
        const dropdowns = ['branchDropdown', 'dateDropdown'];

        dropdowns.forEach((dropdownId) => {
            const dropdown = document.getElementById(dropdownId);

            if (dropdown && dropdownId !== id) {
                dropdown.classList.add('hidden');
            }
        });

        if (target) {
            target.classList.toggle('hidden');
        }
    }

    function openAllSchedulesModal() {
        const modal = document.getElementById('allScheduleModal');

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAllSchedulesModal() {
        const modal = document.getElementById('allScheduleModal');

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function closeAllSchedulesByOverlay(event) {
        if (event.target.id === 'allScheduleModal') {
            closeAllSchedulesModal();
        }
    }

    function showToast(message) {
        const toast = document.getElementById('toast');

        toast.textContent = message;
        toast.classList.remove('hidden');

        clearTimeout(window.toastTimeout);

        window.toastTimeout = setTimeout(() => {
            toast.classList.add('hidden');
        }, 1800);
    }

    document.addEventListener('click', function(event) {
        const insideDropdown = event.target.closest('#branchDropdown, #dateDropdown');
        const dropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');

        if (!insideDropdown && !dropdownButton) {
            document.getElementById('branchDropdown')?.classList.add('hidden');
            document.getElementById('dateDropdown')?.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAllSchedulesModal();
        }
    });
</script>

</body>
</html>