<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Penjadwalan - Dina Salon Muslimah</title>

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

        .card-shadow {
            box-shadow: 0 8px 18px rgba(58, 55, 46, 0.10);
        }

        .panel-shadow {
            box-shadow: 0 10px 24px rgba(58, 55, 46, 0.10);
        }

        .selected-slot {
            outline: 3px solid #D995A1;
            outline-offset: -3px;
        }

        .active-tab {
            background-color: #FFF0F2;
        }

        .inactive-tab {
            background-color: white;
        }

        .selected-booking-row {
            background-color: #FFF4F5;
        }

        .modal-bg {
            background: rgba(0, 0, 0, 0.35);
        }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

@php
    $branches = $branches ?? collect();
    $selectedBranch = $selectedBranch ?? null;
    $selectedCabangId = $selectedCabangId ?? 1;
    $selectedDate = $selectedDate ?? now()->toDateString();
    $dateOptions = $dateOptions ?? collect();
    $staffList = $staffList ?? collect();
    $services = $services ?? collect();
    $customers = $customers ?? collect();
    $times = $times ?? [];
    $scheduleGrid = $scheduleGrid ?? [];
    $bookingList = $bookingList ?? collect();

    $selectedDateCarbon = \Carbon\Carbon::parse($selectedDate);
    $selectedDateLabel = $selectedDateCarbon->locale('id')->translatedFormat('d F Y');
    $selectedDayLabel = $selectedDateCarbon->locale('id')->translatedFormat('l');

    $gridColumnStyle = 'grid-template-columns: 90px repeat(' . max($staffList->count(), 1) . ', minmax(0, 1fr));';
@endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="ml-[235px] w-[calc(100%-235px)] min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        <header class="h-[92px] px-[58px] flex items-center justify-between">

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
                            <a href="{{ route('admin.penjadwalan', ['cabang_id' => $branch->cabang_id, 'tanggal' => $selectedDate]) }}"
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
                        @foreach($dateOptions as $dateOption)
                            <a href="{{ route('admin.penjadwalan', ['cabang_id' => $selectedCabangId, 'tanggal' => $dateOption->date]) }}"
                               class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ $selectedDate === $dateOption->date ? 'bg-[#FFF0F2]' : '' }}">
                                {{ $dateOption->label }} - {{ $dateOption->day }}
                            </a>
                        @endforeach
                    </div>
                </div>


                {{-- PROFILE DROPDOWN PARTIAL --}}
                <div class="relative flex items-center">
                    @include('admin.partial.dropdownadmin')
                </div>

            </div>
        </header>

        @if(session('success'))
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-green-100 text-green-700 px-5 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="pl-[42px] pr-[0px] mt-[14px] pb-[40px]">

            <div class="grid grid-cols-[minmax(0,1fr)_215px] gap-[8px]">

                <div class="bg-[#FDE7EC] rounded-[8px] px-[16px] pt-[22px] pb-[12px] panel-shadow">

                    <div class="h-[64px] bg-[#FFF0F2] rounded-[8px] flex items-center justify-between px-[16px] mb-[22px]">

                        <div class="flex items-center">
                            <button type="button"
                                    id="staffTab"
                                    onclick="showStaffView()"
                                    class="tab-btn h-[46px] px-[38px] active-tab rounded-[8px] font-extrabold text-[13px] text-[#4A4242] card-shadow">
                                Lihat per Specialist
                            </button>

                            <button type="button"
                                    id="bookingTab"
                                    onclick="showBookingListView()"
                                    class="tab-btn h-[46px] px-[18px] inactive-tab rounded-[8px] font-extrabold text-[13px] text-[#4A4242] card-shadow flex items-center gap-[8px]">
                                <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                                    <rect x="4" y="5" width="16" height="16" rx="2" stroke="black" stroke-width="2"/>
                                    <path d="M8 3V7M16 3V7M4 10H20" stroke="black" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Daftar Booking
                            </button>
                        </div>

                        <div class="flex items-center gap-[14px] text-[13px] font-extrabold">

                            <button type="button" onclick="filterBookingByLegend('Tersedia')" class="legend-btn flex items-center gap-[5px]">
                                <span class="w-[20px] h-[20px] rounded-full bg-[#A8BD8C]"></span>
                                Tersedia
                            </button>

                            <button type="button" onclick="filterBookingByLegend('Dikonfirmasi')" class="legend-btn flex items-center gap-[5px]">
                                <span class="w-[20px] h-[20px] rounded-full bg-[#E8A9B4]"></span>
                                Dikonfirmasi
                            </button>

                            <button type="button" onclick="filterBookingByLegend('Break')" class="legend-btn flex items-center gap-[5px]">
                                <span class="w-[20px] h-[20px] rounded-full bg-[#D7D7D7]"></span>
                                Break
                            </button>

                            <button type="button" onclick="filterBookingByLegend('Tunda')" class="legend-btn flex items-center gap-[5px]">
                                <span class="w-[20px] h-[20px] rounded-full bg-[#F7E9BC]"></span>
                                Tunda
                            </button>

                        </div>

                        <button type="button"
                                onclick="openAddBookingModal()"
                                class="h-[42px] px-[22px] rounded-[7px] bg-[#3F372E] text-white text-[13px] font-extrabold hover:opacity-90 transition">
                            + Tambah Booking
                        </button>

                    </div>

                    <div id="staffScheduleView" class="bg-white rounded-[9px] panel-shadow overflow-hidden">

                        <div class="grid border-b border-[#F1C7CE]" style="{{ $gridColumnStyle }}">

                            <div class="h-[64px] flex items-center justify-center border-r border-[#F1C7CE]">
                                <span class="text-[20px] font-extrabold text-black">
                                    Waktu
                                </span>
                            </div>

                            @forelse($staffList as $staff)
                                <div class="h-[64px] flex items-center gap-[12px] px-[12px] border-r border-[#F7E0E4] last:border-r-0">
                                    <div class="w-[40px] h-[40px] rounded-full bg-[#E8A9B4] shrink-0 overflow-hidden">
                                        @if($staff->foto_profile)
                                            <img src="{{ asset($staff->foto_profile) }}" class="w-full h-full object-cover" alt="{{ $staff->nama }}">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[18px] font-extrabold leading-none">
                                            {{ $staff->nama ?? 'Specialist' }}
                                        </p>
                                        <p class="text-[13px] italic font-extrabold leading-none mt-[4px]">
                                            {{ $staff->jabatan ?? 'Specialist' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="h-[64px] flex items-center px-[12px]">
                                    <p class="text-[14px] font-bold text-[#8B7777]">
                                        Belum ada specialist
                                    </p>
                                </div>
                            @endforelse

                        </div>

                        @foreach($times as $time)
                            <div class="grid border-b border-[#F1C7CE] last:border-b-0" style="{{ $gridColumnStyle }}">

                                <div class="h-[84px] flex items-center justify-center border-r border-[#F1C7CE]">
                                    <span class="text-[20px] text-black">
                                        {{ $time }}
                                    </span>
                                </div>

                                @forelse($staffList as $staff)
                                    @php
                                        $cell = $scheduleGrid[$time][$staff->pegawai_id] ?? null;
                                    @endphp

                                    <div class="h-[84px] px-[10px] py-[8px] border-r border-[#F7E0E4] last:border-r-0">

                                        @if(!$cell)
                                            <button type="button"
                                                    class="schedule-cell w-full h-full rounded-[8px] bg-[#F3F0F0] text-[#8B7777] text-[16px] font-extrabold flex items-center justify-center">
                                                -
                                            </button>
                                        @elseif($cell->type === 'available')
                                            <button type="button"
                                                    onclick="selectSlot(this)"
                                                    data-type="available"
                                                    data-time="{{ $cell->time }}"
                                                    data-staff="{{ $cell->staff }}"
                                                    class="schedule-cell w-full h-full rounded-[8px] bg-[#EEF7E6] text-[#7E9D62] text-[18px] font-extrabold flex items-center justify-center">
                                                Tersedia
                                            </button>
                                        @elseif($cell->type === 'break')
                                            <button type="button"
                                                    onclick="selectSlot(this)"
                                                    data-type="break"
                                                    data-time="{{ $cell->time }}"
                                                    data-staff="{{ $cell->staff }}"
                                                    class="schedule-cell w-full h-full rounded-[8px] bg-[#E7E2E2] text-[#6B6B6B] text-[18px] font-extrabold flex items-center justify-center">
                                                Break
                                            </button>
                                        @else
                                            @php
                                                $bookingCellClass = match ($cell->type) {
                                                    'pending' => 'bg-[#FFF4D5]',
                                                    'in_progress' => 'bg-[#F6E4A5]',
                                                    'completed' => 'bg-[#EEF7E6]',
                                                    'cancelled' => 'bg-[#E7E2E2]',
                                                    default => 'bg-[#FDE3E8]',
                                                };

                                                $bookingTextClass = match ($cell->type) {
                                                    'pending' => 'text-[#7A6335]',
                                                    'in_progress' => 'text-[#C77A45]',
                                                    'completed' => 'text-[#7E9D62]',
                                                    'cancelled' => 'text-[#6B6B6B]',
                                                    default => 'text-[#B85C6A]',
                                                };
                                            @endphp

                                            <button type="button"
                                                    onclick="selectSlot(this)"
                                                    data-type="{{ $cell->type }}"
                                                    data-booking-id="{{ $cell->booking_id }}"
                                                    data-service="{{ $cell->service }}"
                                                    data-client="{{ $cell->client }}"
                                                    data-customer="{{ $cell->customer }}"
                                                    data-phone="{{ $cell->phone }}"
                                                    data-staff="{{ $cell->staff }}"
                                                    data-time="{{ $cell->time }}"
                                                    data-payment="{{ $cell->payment }}"
                                                    data-status="{{ $cell->status }}"
                                                    data-note="{{ $cell->note }}"
                                                    class="schedule-cell relative w-full h-full rounded-[8px] text-left px-[8px] pt-[8px] {{ $bookingCellClass }}">
                                                <p class="{{ $bookingTextClass }} text-[16px] leading-none font-extrabold">
                                                    {{ $cell->service }}
                                                </p>

                                                <p class="mt-[10px] text-black text-[13px] leading-none italic font-extrabold">
                                                    {{ $cell->client }}
                                                </p>

                                                <p class="absolute right-[8px] bottom-[8px] text-black text-[13px] leading-none font-extrabold">
                                                    {{ $cell->payment }}
                                                </p>
                                            </button>
                                        @endif

                                    </div>
                                @empty
                                    <div class="h-[84px] flex items-center px-[12px]">
                                        <p class="text-[13px] font-semibold text-[#8B7777]">
                                            Belum ada specialist yang terdaftar.
                                        </p>
                                    </div>
                                @endforelse

                            </div>
                        @endforeach
                    </div>

                    <div id="bookingListView" class="hidden bg-white rounded-[9px] panel-shadow overflow-hidden">

                        <div class="px-[24px] pt-[22px] pb-[16px] bg-[#FFF8F9] border-b border-[#F1C7CE]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-[24px] font-extrabold text-[#3F3838]">
                                        Daftar Booking Hari Ini
                                    </h2>

                                    <p class="text-[13px] font-semibold text-[#7A6A63] mt-[4px]">
                                        List booking berdasarkan tanggal dan cabang yang sedang dipilih.
                                    </p>
                                </div>

                                <div class="flex items-center gap-[10px]">
                                    <input
                                        id="bookingSearch"
                                        type="text"
                                        oninput="filterBookingList()"
                                        placeholder="Cari pelanggan..."
                                        class="w-[210px] h-[38px] rounded-[10px] bg-[#FFF0F2] px-[14px] text-[13px] font-semibold outline-none"
                                    >

                                    <select
                                        id="bookingStatusFilter"
                                        onchange="filterBookingList()"
                                        class="h-[38px] rounded-[10px] bg-[#FFF0F2] px-[12px] text-[13px] font-bold outline-none"
                                    >
                                        <option value="Semua">Semua Status</option>
                                        <option value="Dikonfirmasi">Dikonfirmasi</option>
                                        <option value="Berjalan">Berjalan</option>
                                        <option value="Tunda">Tunda</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-[80px_135px_1fr_130px_125px_105px_135px] px-[18px] py-[14px] bg-[#FFF0F2] border-b border-[#F1C7CE] text-[13px] font-extrabold text-[#4B4242]">
                            <span>ID</span>
                            <span>Waktu</span>
                            <span>Pelanggan</span>
                            <span>Layanan</span>
                            <span>Specialist</span>
                            <span>Bayar</span>
                            <span>Status</span>
                        </div>

                        <div id="bookingRows">
                            @forelse($bookingList as $booking)
                                <button type="button"
                                        onclick="selectBookingRow(this)"
                                        data-id="{{ $booking->id }}"
                                        data-type="{{ $booking->type }}"
                                        data-pelanggan-id="{{ $booking->pelanggan_id }}"
                                        data-layanan-cabang-id="{{ $booking->layanan_cabang_id }}"
                                        data-pegawai-id="{{ $booking->pegawai_id }}"
                                        data-tanggal-booking="{{ $booking->tanggal_booking }}"
                                        data-jam-booking="{{ $booking->jam_booking }}"
                                        data-payment-raw="{{ $booking->payment_raw }}"
                                        data-status-raw="{{ $booking->status_raw }}"
                                        data-time="{{ $booking->time }}"
                                        data-customer="{{ $booking->customer }}"
                                        data-phone="{{ $booking->phone }}"
                                        data-service="{{ $booking->service }}"
                                        data-staff="{{ $booking->staff }}"
                                        data-payment="{{ $booking->payment }}"
                                        data-status="{{ $booking->status }}"
                                        data-note="{{ $booking->note }}"
                                        class="booking-row w-full grid grid-cols-[80px_135px_1fr_130px_125px_105px_135px] items-center px-[18px] py-[16px] text-left border-b border-[#F1C7CE] hover:bg-[#FFF4F5] transition">
                                    <span class="text-[13px] font-extrabold text-[#8A4357]">
                                        BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>

                                    <span class="text-[13px] font-bold">
                                        {{ $booking->time }}
                                    </span>

                                    <span>
                                        <p class="text-[14px] font-extrabold text-black">
                                            {{ $booking->customer }}
                                        </p>
                                        <p class="text-[11px] font-semibold text-[#7A6A63] mt-[2px]">
                                            {{ $booking->phone }}
                                        </p>
                                    </span>

                                    <span class="text-[13px] font-extrabold">
                                        {{ $booking->service }}
                                    </span>

                                    <span class="text-[13px] font-extrabold">
                                        {{ $booking->staff }}
                                    </span>

                                    <span>
                                        <span class="{{ strtolower($booking->payment) === 'cash' ? 'bg-[#FFE5E9]' : 'bg-[#F4ECFF]' }} border border-[#D6B8C0] rounded-[6px] px-[10px] py-[4px] text-[12px] font-extrabold">
                                            {{ $booking->payment }}
                                        </span>
                                    </span>

                                    <span>
                                        @if($booking->status === 'Tunda')
                                            <span class="bg-[#FFF4D5] text-[#7A6335] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">
                                                Tunda
                                            </span>
                                        @elseif($booking->status === 'Berjalan')
                                            <span class="bg-[#F6E4A5] text-[#C77A45] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">
                                                Berjalan
                                            </span>
                                        @elseif($booking->status === 'Selesai')
                                            <span class="bg-[#EEF7E6] text-[#7E9D62] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">
                                                Selesai
                                            </span>
                                        @elseif($booking->status === 'Dibatalkan')
                                            <span class="bg-[#F8D7DD] text-[#B85C6A] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">
                                                Batal
                                            </span>
                                        @else
                                            <span class="bg-[#FDE3E8] text-[#B85C6A] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">
                                                Dikonfirmasi
                                            </span>
                                        @endif
                                    </span>
                                </button>
                            @empty
                                <div class="px-[24px] py-[45px] text-center">
                                    <p class="text-[18px] font-extrabold text-[#3F3838]">
                                        Belum ada booking
                                    </p>

                                    <p class="text-[13px] font-semibold text-[#7A6A63] mt-[6px]">
                                        Booking pada tanggal dan cabang ini belum tersedia.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        <div id="emptyBookingList" class="hidden px-[24px] py-[45px] text-center">
                            <p class="text-[18px] font-extrabold text-[#3F3838]">
                                Booking tidak ditemukan
                            </p>

                            <p class="text-[13px] font-semibold text-[#7A6A63] mt-[6px]">
                                Coba ubah kata kunci pencarian atau filter status.
                            </p>
                        </div>

                    </div>
                </div>

                <aside class="bg-white min-h-[900px] px-[12px] py-[24px] card-shadow">

                    <h2 class="text-[19px] font-extrabold text-black">
                        Detail Booking
                    </h2>

                    <div id="detailBadge" class="inline-flex mt-[8px] bg-[#E8B5BC] text-white rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold">
                        Dikonfirmasi
                    </div>

                    <div class="mt-[20px] space-y-[29px]">

                        <div class="grid grid-cols-[25px_1fr] gap-[6px] items-start">
                            <div></div>
                            <div>
                                <p class="text-[13px] font-extrabold leading-none">Nama Pelanggan</p>
                                <p id="detailCustomer" class="text-[13px] font-extrabold leading-none mt-[3px] text-black">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-[25px_1fr] gap-[6px] items-start">
                            <svg class="w-[24px] h-[24px]" viewBox="0 0 24 24" fill="none">
                                <path d="M22 16.5V20A2 2 0 0120 22C10.1 22 2 13.9 2 4A2 2 0 014 2H7.5L9.2 6.2L7.1 8.3C8.3 11.2 10.8 13.7 13.7 14.9L15.8 12.8L20 14.5C20.8 14.8 22 15.4 22 16.5Z" stroke="#4B4242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div>
                                <p class="text-[13px] font-extrabold leading-none">No. HP</p>
                                <p id="detailPhone" class="text-[13px] font-extrabold leading-none mt-[3px] text-black">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-[25px_1fr] gap-[6px] items-start">
                            <svg class="w-[24px] h-[24px]" viewBox="0 0 24 24" fill="none">
                                <circle cx="8" cy="7" r="3" stroke="#4B4242" stroke-width="2"/>
                                <path d="M2 20C2.7 16.5 5 14.5 8 14.5C9.6 14.5 11 15.1 12 16" stroke="#4B4242" stroke-width="2" stroke-linecap="round"/>
                                <path d="M15 11L17 13L22 7" stroke="#4B4242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div>
                                <p class="text-[13px] font-extrabold leading-none">Layanan</p>
                                <p id="detailService" class="text-[13px] font-extrabold leading-none mt-[3px] text-black">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-[25px_1fr] gap-[6px] items-start">
                            <svg class="w-[24px] h-[24px]" viewBox="0 0 24 24" fill="none">
                                <circle cx="8" cy="7" r="3" stroke="#4B4242" stroke-width="2"/>
                                <path d="M2 20C2.7 16.5 5 14.5 8 14.5C9.6 14.5 11 15.1 12 16" stroke="#4B4242" stroke-width="2" stroke-linecap="round"/>
                                <path d="M15 11L17 13L22 7" stroke="#4B4242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div>
                                <p class="text-[13px] font-extrabold leading-none">Specialist</p>
                                <p id="detailStaff" class="text-[13px] font-extrabold leading-none mt-[3px] text-black">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-[25px_1fr] gap-[6px] items-start">
                            <svg class="w-[24px] h-[24px]" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="#4B4242" stroke-width="2"/>
                                <path d="M12 6V12L16 14" stroke="#4B4242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div>
                                <p class="text-[13px] font-extrabold leading-none">Waktu</p>
                                <p id="detailDate" class="text-[13px] font-extrabold leading-none mt-[3px] text-black">{{ $selectedDayLabel }}, {{ $selectedDateLabel }}</p>
                                <p id="detailTime" class="text-[13px] font-extrabold leading-none mt-[3px] text-black">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-[25px_1fr] gap-[6px] items-start">
                            <svg class="w-[24px] h-[24px]" viewBox="0 0 24 24" fill="none">
                                <path d="M4 20H8L19 9L15 5L4 16V20Z" stroke="#4B4242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.5 6.5L17.5 10.5" stroke="#4B4242" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <div>
                                <p class="text-[13px] font-extrabold leading-none">Catatan</p>
                                <p id="detailNote" class="text-[13px] font-extrabold leading-tight mt-[3px] text-black">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-[45px] border-t border-[#F1C7CE] pt-[13px]">

                        <p class="text-[13px] font-extrabold mb-[10px]">
                            Metode Pembayaran
                        </p>

                        <div class="grid grid-cols-2 gap-[20px] px-[22px]">
                            <button type="button"
                                    onclick="selectPayment(this)"
                                    class="payment-btn bg-[#FFE5E9] border border-[#D6B8C0] rounded-[6px] h-[30px] text-[13px] font-extrabold">
                                Cash
                            </button>

                            <button type="button"
                                    onclick="selectPayment(this)"
                                    class="payment-btn bg-white border border-[#D6B8C0] rounded-[6px] h-[30px] text-[13px] font-extrabold">
                                Qris
                            </button>
                        </div>
                    </div>

                    <div class="mt-[30px] border-t border-[#F1C7CE] pt-[13px]">

                        <p class="text-[13px] font-extrabold mb-[9px]">
                            Status Booking / Pembayaran
                        </p>

                        <form id="statusUpdateForm" method="POST" action="#" onsubmit="return validateStatusUpdate(event)" class="space-y-[10px]">
                            @csrf
                            @method('PUT')

                            

                            <select id="statusSelect"
                                    name="status"
                                    onchange="updateBadgeByStatus(this.value)"
                                    class="w-full h-[42px] bg-white border border-[#D6B8C0] rounded-[6px] px-[12px] text-[13px] font-extrabold card-shadow outline-none">
                                <option value="payment_pending">Bayar Pending</option>
                                <option value="payment_verified">Bayar Terverifikasi</option>
                                <option disabled>──────────</option>
                                <option value="dikonfirmasi">Dikonfirmasi</option>
                                <option value="proses">Berjalan</option>
                                <option value="selesai">Selesai</option>
                                <option value="tunda">Tunda</option>
                                <option value="batal">Dibatalkan</option>
                                <option value="available">Tersedia</option>
                                <option value="break">Break</option>
                            </select>

                            <button type="submit"
                                    class="w-full h-[40px] rounded-[6px] bg-[#3F372E] text-white text-[13px] font-extrabold">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <div class="mt-[10px] space-y-[10px]">

                        <button type="button"
        onclick="openEditBookingModal()"
        class="w-full h-[40px] rounded-[6px] bg-[#5A4B4B] text-white text-[13px] font-extrabold">
    Edit Pemesanan
</button>

                        <form id="cancelBookingForm" method="POST" action="#">
                            @csrf
                            @method('DELETE')

    <button type="button"
            onclick="openCancelModal()"
            class="w-full h-[40px] rounded-[6px] bg-[#B85C6A] text-white text-[13px] font-extrabold">
        Batalkan
    </button>
</form>

                        <button type="button"
                                onclick="window.print()"
                                class="w-full h-[40px] rounded-[6px] bg-white border border-[#D6B8C0] text-[#4B4242] text-[13px] font-extrabold">
                            Cetak Nota
                        </button>
                    </div>

                </aside>
            </div>
        </section>
    </main>
</div>

<div id="addBookingModal"
     onclick="closeAddBookingByOverlay(event)"
     class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">

    <form action="{{ route('admin.penjadwalan.booking.store') }}"
          method="POST"
          class="w-full max-w-[650px] bg-white rounded-[18px] shadow-2xl overflow-hidden">
        @csrf

        <div class="px-[26px] py-[20px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <div>
               <h2 id="bookingModalTitle" class="text-[24px] font-extrabold text-[#4B3A36]">
    Tambah Booking
</h2>
                <p class="text-[13px] font-semibold text-[#7B6A62] mt-[4px]">
                    {{ $selectedBranch->label ?? 'Cabang Salon' }} - {{ $selectedDayLabel }}, {{ $selectedDateLabel }}
                </p>
            </div>

            <button type="button"
                    onclick="closeAddBookingModal()"
                    class="w-[38px] h-[38px] rounded-full bg-[#4B3A36] text-white text-[26px] leading-none flex items-center justify-center">
                ×
            </button>
        </div>

        <div class="px-[26px] py-[22px] grid grid-cols-2 gap-[16px]">

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Pelanggan</label>
                <select name="pelanggan_id"
        id="bookingPelangganId"
        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
        required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->pelanggan_id }}">
                            {{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Layanan</label>
                <select name="layanan_cabang_id"
        id="bookingLayananCabangId"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="">Pilih Layanan</option>
                    @foreach($services as $service)
                        <option value="{{ $service->layanan_cabang_id }}">
                            {{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Specialist</label>
               <select name="pegawai_id"
        id="bookingPegawaiId"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="">Pilih Specialist</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->pegawai_id }}">
                            {{ $staff->nama ?? 'Specialist' }} - {{ $staff->jabatan ?? 'Specialist' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Tanggal</label>
               <input type="date"
       name="tanggal_booking"
       id="bookingTanggal"
                       value="{{ $selectedDate }}"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                       required>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Jam</label>
             <select name="jam_booking"
        id="bookingJam"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="">Pilih Jam</option>
                    @foreach($times as $time)
                        <option value="{{ $time }}">
                            {{ $time }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Metode Pembayaran</label>
               <select name="metode_pembayaran"
        id="bookingMetodePembayaran"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Status</label>
               <select name="status"
        id="bookingStatus"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="tunda">Tunda</option>
                    <option value="proses">Berjalan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <div class="col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Catatan</label>
                <textarea name="catatan"
          id="bookingCatatan"
                          rows="3"
                          placeholder="Masukkan catatan booking..."
                          class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[12px] py-[10px] outline-none resize-none"></textarea>
            </div>

        </div>

        <div class="px-[26px] pb-[24px] flex justify-end gap-[12px]">
            <button type="button"
                    onclick="closeAddBookingModal()"
                    class="h-[42px] px-[20px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] font-extrabold">
                Batal
            </button>

            <button type="submit"
                    class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] text-white font-extrabold hover:bg-[#D995A1] transition">
                Simpan Booking
            </button>
        </div>
    </form>
</div>

<div id="editBookingModal"
     onclick="closeEditBookingByOverlay(event)"
     class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">

    <form id="editBookingForm"
          action="#"
          method="POST"
          class="w-full max-w-[650px] bg-white rounded-[18px] shadow-2xl overflow-hidden">
        @csrf
        @method('PUT')

        <div class="px-[26px] py-[20px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <div>
                <h2 class="text-[24px] font-extrabold text-[#4B3A36]">
                    Edit Pemesanan
                </h2>
                <p class="text-[13px] font-semibold text-[#7B6A62] mt-[4px]">
                    Ubah data booking yang sedang dipilih.
                </p>
            </div>

            <button type="button"
                    onclick="closeEditBookingModal()"
                    class="w-[38px] h-[38px] rounded-full bg-[#4B3A36] text-white text-[26px] leading-none flex items-center justify-center">
                ×
            </button>
        </div>

        <div class="px-[26px] py-[22px] grid grid-cols-2 gap-[16px]">

            <input type="hidden" id="edit_booking_id">

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Pelanggan</label>
                <select id="edit_pelanggan_id"
                        name="pelanggan_id"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->pelanggan_id }}">
                            {{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Layanan</label>
                <select id="edit_layanan_cabang_id"
                        name="layanan_cabang_id"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="">Pilih Layanan</option>
                    @foreach($services as $service)
                        <option value="{{ $service->layanan_cabang_id }}">
                            {{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Specialist</label>
                <select id="edit_pegawai_id"
                        name="pegawai_id"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="">Pilih Specialist</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->pegawai_id }}">
                            {{ $staff->nama ?? 'Specialist' }} - {{ $staff->jabatan ?? 'Specialist' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Tanggal</label>
                <input id="edit_tanggal_booking"
                       type="date"
                       name="tanggal_booking"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                       required>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Jam</label>
                <select id="edit_jam_booking"
                        name="jam_booking"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="">Pilih Jam</option>
                    @foreach($times as $time)
                        <option value="{{ $time }}">
                            {{ $time }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Metode Pembayaran</label>
                <select id="edit_metode_pembayaran"
                        name="metode_pembayaran"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Status</label>
                <select id="edit_status"
                        name="status"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none"
                        required>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="tunda">Tunda</option>
                    <option value="proses">Berjalan</option>
                    <option value="selesai">Selesai</option>
                    <option value="batal">Dibatalkan</option>
                </select>
            </div>

            <div class="col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Catatan</label>
                <textarea id="edit_catatan"
                          name="catatan"
                          rows="3"
                          placeholder="Masukkan catatan booking..."
                          class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[12px] py-[10px] outline-none resize-none"></textarea>
            </div>

        </div>

        <div class="px-[26px] pb-[24px] flex justify-end gap-[12px]">
            <button type="button"
                    onclick="closeEditBookingModal()"
                    class="h-[42px] px-[20px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] font-extrabold">
                Batal
            </button>

            <button type="submit"
                    class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] text-white font-extrabold hover:bg-[#D995A1] transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    let selectedBooking = null;

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

    document.addEventListener('click', function (event) {
        const insideDropdown = event.target.closest('#branchDropdown, #dateDropdown');
        const dropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');

        if (!insideDropdown && !dropdownButton) {
            document.getElementById('branchDropdown')?.classList.add('hidden');
            document.getElementById('dateDropdown')?.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAddBookingModal();
        }
    });

    // ============ TAB SWITCH ============
    function showStaffView() {
        document.getElementById('staffScheduleView').classList.remove('hidden');
        document.getElementById('bookingListView').classList.add('hidden');

        document.getElementById('staffTab').classList.add('active-tab');
        document.getElementById('staffTab').classList.remove('inactive-tab');

        document.getElementById('bookingTab').classList.add('inactive-tab');
        document.getElementById('bookingTab').classList.remove('active-tab');
    }

    function showBookingListView() {
        document.getElementById('staffScheduleView').classList.add('hidden');
        document.getElementById('bookingListView').classList.remove('hidden');

        document.getElementById('bookingTab').classList.add('active-tab');
        document.getElementById('bookingTab').classList.remove('inactive-tab');

        document.getElementById('staffTab').classList.add('inactive-tab');
        document.getElementById('staffTab').classList.remove('active-tab');
    }

    // ============ FILTER BOOKING LIST ============
    function filterBookingByLegend(status) {
        showBookingListView();

        const filter = document.getElementById('bookingStatusFilter');

        if (status === 'Dikonfirmasi') {
            filter.value = 'Dikonfirmasi';
        } else if (status === 'Tunda') {
            filter.value = 'Tunda';
        } else {
            filter.value = 'Semua';
        }

        filterBookingList();
    }

    function filterBookingList() {
        const keyword = document.getElementById('bookingSearch')?.value.toLowerCase() || '';
        const status = document.getElementById('bookingStatusFilter')?.value || 'Semua';

        const rows = document.querySelectorAll('.booking-row');
        let visibleCount = 0;

        rows.forEach((row) => {
            const customer = (row.dataset.customer || '').toLowerCase();
            const service = (row.dataset.service || '').toLowerCase();
            const staff = (row.dataset.staff || '').toLowerCase();
            const rowStatus = row.dataset.status;

            const matchKeyword =
                customer.includes(keyword) ||
                service.includes(keyword) ||
                staff.includes(keyword);

            const matchStatus =
                status === 'Semua' ||
                rowStatus === status;

            if (matchKeyword && matchStatus) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        if (visibleCount === 0 && rows.length > 0) {
            document.getElementById('emptyBookingList').classList.remove('hidden');
        } else {
            document.getElementById('emptyBookingList').classList.add('hidden');
        }
    }

    function setSelectedBookingFromDataset(source) {
        selectedBooking = {
            id: source.dataset.bookingId || source.dataset.id || '',
            pelangganId: source.dataset.pelangganId || '',
            layananCabangId: source.dataset.layananCabangId || '',
            pegawaiId: source.dataset.pegawaiId || '',
            tanggalBooking: source.dataset.tanggalBooking || "{{ $selectedDate }}",
            jamBooking: source.dataset.jamBooking || (source.dataset.time ? source.dataset.time.substring(0, 5) : ''),
            metodePembayaran: source.dataset.paymentRaw || (source.dataset.payment ? source.dataset.payment.toLowerCase() : 'cash'),
            status: source.dataset.statusRaw || source.dataset.status || 'dikonfirmasi',
            catatan: source.dataset.note || ''
        };
    }

    function resetSelectedBooking() {
        selectedBooking = null;
    }

    function selectSlot(button) {
        document.querySelectorAll('.schedule-cell').forEach((item) => {
            item.classList.remove('selected-slot');
        });

        button.classList.add('selected-slot');

        const type = button.dataset.type;
        const badge = document.getElementById('detailBadge');

        document.getElementById('detailTime').textContent = button.dataset.time || '-';
        document.getElementById('detailStaff').textContent = button.dataset.staff || '-';

        const bookingId = button.dataset.bookingId;

        if (bookingId) {
            setSelectedBookingFromDataset(button);
            document.getElementById('statusUpdateForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + bookingId + "/status";
            document.getElementById('cancelBookingForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + bookingId;
        } else {
            resetSelectedBooking();
            document.getElementById('statusUpdateForm').action = '#';
            document.getElementById('cancelBookingForm').action = '#';
        }

        if (type === 'available') {
            badge.textContent = 'Available';
            badge.className = 'inline-flex mt-[8px] bg-[#EEF7E6] text-[#7E9D62] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';

            document.getElementById('detailCustomer').textContent = '-';
            document.getElementById('detailPhone').textContent = '-';
            document.getElementById('detailService').textContent = 'Slot tersedia';
            document.getElementById('detailNote').innerHTML = 'Belum ada booking pada slot ini';
            document.getElementById('statusSelect').value = 'confirmed';

            window.currentBookingData = null;
            return;
        }

        if (type === 'break') {
            badge.textContent = 'Break';
            badge.className = 'inline-flex mt-[8px] bg-[#E7E2E2] text-[#6B6B6B] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';

            document.getElementById('detailCustomer').textContent = '-';
            document.getElementById('detailPhone').textContent = '-';
            document.getElementById('detailService').textContent = 'Break';
            document.getElementById('detailNote').innerHTML = 'Specialist tidak tersedia pada jam ini';
            document.getElementById('statusSelect').value = 'confirmed';

            window.currentBookingData = null;
            return;
        }

        document.getElementById('detailCustomer').textContent = button.dataset.customer || '-';
        document.getElementById('detailPhone').textContent = button.dataset.phone || '-';
        document.getElementById('detailService').textContent = button.dataset.service || '-';
        document.getElementById('detailNote').innerHTML = (button.dataset.note || '-').replace(',', ',<br>');

        const statusValue = button.dataset.statusRaw || button.dataset.status || 'dikonfirmasi';
        document.getElementById('statusSelect').value = statusValue;

        updatePaymentButton(button.dataset.payment || 'Cash');

        // simpan data lengkap untuk edit modal
        window.currentBookingData = { ...button.dataset };
    }

    // ============ BOOKING ROW (LIST) ============
    function selectBookingRow(row) {
        document.querySelectorAll('.booking-row').forEach((item) => {
            item.classList.remove('selected-booking-row');
        });

        row.classList.add('selected-booking-row');
        setSelectedBookingFromDataset(row);

        document.getElementById('detailCustomer').textContent = row.dataset.customer || '-';
        document.getElementById('detailPhone').textContent = row.dataset.phone || '-';
        document.getElementById('detailService').textContent = row.dataset.service || '-';
        document.getElementById('detailStaff').textContent = row.dataset.staff || '-';
        document.getElementById('detailTime').textContent = row.dataset.time || '-';
        document.getElementById('detailNote').innerHTML = (row.dataset.note || '-').replace(',', ',<br>');

        document.getElementById('statusUpdateForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + row.dataset.id + "/status";
        document.getElementById('cancelBookingForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + row.dataset.id;

        window.currentBookingId = row.dataset.id;
        window.currentBookingData = { ...row.dataset };

        document.getElementById('statusUpdateForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + row.dataset.id + "/status";
        document.getElementById('cancelBookingForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + row.dataset.id;

        document.getElementById('statusSelect').value = row.dataset.statusRaw || 'dikonfirmasi';
        const statusMap = {
            'Dipesan': 'confirmed',
            'Berjalan': 'in_progress',
            'Selesai': 'completed',
            'Menunggu Pembayaran': 'pending',
            'Dibatalkan': 'cancelled'
        };
        document.getElementById('statusSelect').value = statusMap[row.dataset.status] || 'confirmed';

        updatePaymentButton(row.dataset.payment || 'Cash');
        updateBadgeByStatus(document.getElementById('statusSelect').value);
    }

    // ============ PAYMENT BUTTONS (DETAIL PANEL) ============
    function selectPayment(button) {
        document.querySelectorAll('.payment-btn').forEach((btn) => {
            btn.classList.remove('bg-[#FFE5E9]');
            btn.classList.add('bg-white');
        });

        button.classList.remove('bg-white');
        button.classList.add('bg-[#FFE5E9]');
    }

    function updatePaymentButton(payment) {
        document.querySelectorAll('.payment-btn').forEach((btn) => {
            btn.classList.remove('bg-[#FFE5E9]');
            btn.classList.add('bg-white');

            if (btn.textContent.trim().toLowerCase() === payment.toLowerCase()) {
                btn.classList.remove('bg-white');
                btn.classList.add('bg-[#FFE5E9]');
            }
        });
    }

    function updateBadgeByStatus(status) {
        const badge = document.getElementById('detailBadge');

        if (status === 'payment_pending') {
            badge.textContent = 'Bayar Pending';
            badge.className = 'inline-flex mt-[8px] bg-[#FFF4D5] text-[#6B6040] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else if (status === 'payment_verified') {
            badge.textContent = 'Bayar Terverifikasi';
            badge.className = 'inline-flex mt-[8px] bg-[#EEF7E6] text-[#7E9D62] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else if (status === 'available') {
            badge.textContent = 'Tersedia';
            badge.className = 'inline-flex mt-[8px] bg-[#EEF7E6] text-[#7E9D62] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else if (status === 'break') {
            badge.textContent = 'Break';
            badge.className = 'inline-flex mt-[8px] bg-[#E7E2E2] text-[#6B6B6B] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else if (status === 'selesai' || status === 'completed') {
            badge.textContent = 'Selesai';
            badge.className = 'inline-flex mt-[8px] bg-[#EEF7E6] text-[#7E9D62] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else if (status === 'pending' || status === 'tunda') {
            badge.textContent = 'Tunda';
            badge.className = 'inline-flex mt-[8px] bg-[#FFF4D5] text-[#6B6040] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else if (status === 'cancelled') {
            badge.textContent = 'Batal';
            badge.className = 'inline-flex mt-[8px] bg-[#B85C6A] text-white rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else if (status === 'in_progress') {
            badge.textContent = 'Berjalan';
            badge.className = 'inline-flex mt-[8px] bg-[#F6E4A5] text-[#C77A45] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        } else {
            badge.textContent = 'Dikonfirmasi';
            badge.className = 'inline-flex mt-[8px] bg-[#E8B5BC] text-white rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        }
    }

    function validateStatusUpdate(event) {
        if (!selectedBooking || !selectedBooking.id) {
            event.preventDefault();
            alert('Slot ini tidak ada pelanggan yang memesan.');
            return false;
        }

        const statusValue = document.getElementById('statusSelect')?.value || '';

        if (statusValue === 'available' || statusValue === 'break') {
            event.preventDefault();
            alert('Status Tersedia/Break hanya untuk slot kosong, bukan booking pelanggan.');
            return false;
        }

        if ((statusValue === 'dikonfirmasi' || statusValue === 'confirmed') && !selectedBooking.pegawaiId) {
            event.preventDefault();
            alert('Pilih specialist dulu lewat Edit Pemesanan sebelum booking dikonfirmasi.');
            return false;
        }

        return true;
    }

    function openAddBookingModal() {
        resetBookingForm();

        document.getElementById('bookingModalTitle').textContent = 'Tambah Booking';
        document.getElementById('bookingForm').action = "{{ route('admin.penjadwalan.booking.store') }}";
        document.getElementById('bookingFormMethod').value = 'POST';

        const modal = document.getElementById('addBookingModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAddBookingModal() {
        const modal = document.getElementById('addBookingModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function closeAddBookingByOverlay(event) {
        if (event.target.id === 'addBookingModal') {
            closeAddBookingModal();
        }
    }

    function enableEditMode() {
        if (!selectedBooking || !selectedBooking.id) {
            alert('Pilih booking dulu sebelum edit pemesanan.');
            return;
        }

        document.getElementById('detailBadge').textContent = 'Editing';
        document.getElementById('detailBadge').className = 'inline-flex mt-[8px] bg-[#3F372E] text-white rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';

        document.getElementById('editBookingForm').action =
            "{{ url('/admin/penjadwalan/booking') }}/" + selectedBooking.id;

        document.getElementById('edit_booking_id').value = selectedBooking.id;
        document.getElementById('edit_pelanggan_id').value = selectedBooking.pelangganId;
        document.getElementById('edit_layanan_cabang_id').value = selectedBooking.layananCabangId;
        document.getElementById('edit_pegawai_id').value = selectedBooking.pegawaiId;
        document.getElementById('edit_tanggal_booking').value = selectedBooking.tanggalBooking;
        document.getElementById('edit_jam_booking').value = selectedBooking.jamBooking;
        document.getElementById('edit_metode_pembayaran').value = selectedBooking.metodePembayaran;
        document.getElementById('edit_status').value = selectedBooking.status;
        document.getElementById('edit_catatan').value = selectedBooking.catatan === '-' ? '' : selectedBooking.catatan;

        openEditBookingModal();
    }

    function openEditBookingModal() {
        const modal = document.getElementById('editBookingModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // ============ VALIDASI FORM ============
    function validateStatusForm() {
        const form = document.getElementById('statusUpdateForm');

        if (!form.action.includes('/admin/penjadwalan/booking/')) {
            alert('Pilih data booking terlebih dahulu sebelum update status');
            return false;
        }

        return true;
    }

    function validateBookingAction() {
        const statusForm = document.getElementById('statusUpdateForm');
        const cancelForm = document.getElementById('cancelBookingForm');

        if (!statusForm.action.includes('/admin/penjadwalan/booking/') &&
            !cancelForm.action.includes('/admin/penjadwalan/booking/')) {
            alert('Pilih data booking terlebih dahulu, bukan slot break/kosong');
            return false;
        }

        return true;
    }

function openCancelModal() {

    if (!validateBookingAction()) {
        return;
    }

    const modal = document.getElementById('cancelModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCancelModal() {

    const modal = document.getElementById('cancelModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function submitCancelBooking() {
    document.getElementById('cancelBookingForm').submit();
}

</script>

<div id="cancelModal"
     class="hidden fixed inset-0 z-[9999] bg-black/40 items-center justify-center">

    <div class="bg-white w-[420px] rounded-[16px] p-6 shadow-2xl">

        <h2 class="text-[22px] font-extrabold text-[#B85C6A]">
            ⚠️ Batalkan Booking
        </h2>

        <p class="mt-4 text-[14px] text-[#4B4242] leading-relaxed">
            Apakah Anda yakin ingin membatalkan booking ini?
        </p>

        <p class="mt-2 text-[13px] text-[#8A7B7B]">
            Tindakan ini tidak dapat dikembalikan.
        </p>

        <div class="flex justify-end gap-3 mt-6">

            <button type="button"
                    onclick="closeCancelModal()"
                    class="px-5 h-[40px] rounded-[8px] bg-[#EFE4E4] font-bold">
                Kembali
            </button>

            <button type="button"
                    onclick="submitCancelBooking()"
                    class="px-5 h-[40px] rounded-[8px] bg-[#B85C6A] text-white font-bold">
                Ya, Batalkan
            </button>

        </div>
    </div>
</div>

</body>
</html>