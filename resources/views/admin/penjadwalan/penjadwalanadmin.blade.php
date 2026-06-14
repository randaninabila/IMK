<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Penjadwalan - Dina Salon Muslimah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { margin: 0; overflow-x: hidden; }
        .card-shadow   { box-shadow: 0 8px 18px rgba(58,55,46,.10); }
        .panel-shadow  { box-shadow: 0 10px 24px rgba(58,55,46,.10); }
        .selected-slot { outline: 3px solid #D995A1; outline-offset: -3px; }
        .active-tab    { background-color: #FFF0F2; }
        .inactive-tab  { background-color: white; }
        .selected-booking-row { background-color: #FFF4F5; }
        .modal-bg      { background: rgba(0,0,0,0.35); }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

@php
    $branches        = $branches        ?? collect();
    $selectedBranch  = $selectedBranch  ?? null;
    $selectedCabangId= $selectedCabangId?? 1;
    $selectedDate    = $selectedDate    ?? now()->toDateString();
    $dateOptions     = $dateOptions     ?? collect();
    $staffList       = $staffList       ?? collect();
    $services        = $services        ?? collect();
    $packages        = $packages        ?? collect();
    $customers       = $customers       ?? collect();
    $times           = $times           ?? [];
    $scheduleGrid    = $scheduleGrid    ?? [];
    $bookingList     = $bookingList     ?? collect();

    $selectedDateCarbon = \Carbon\Carbon::parse($selectedDate);
    $selectedDateLabel  = $selectedDateCarbon->locale('id')->translatedFormat('d F Y');
    $selectedDayLabel   = $selectedDateCarbon->locale('id')->translatedFormat('l');

    $gridColumnStyle = 'grid-template-columns: 90px repeat(' . max($staffList->count(), 1) . ', minmax(0, 1fr));';
@endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="lg:ml-[235px] lg:w-[calc(100%-235px)] w-full min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        {{-- ===== HEADER ===== --}}
        <header class="h-[92px] px-4 lg:px-[58px] flex items-center justify-between gap-3">
            
            <button type="button"
                    onclick="adminSidebarOpen()"
                    class="lg:hidden p-2 rounded-[8px] text-[#6B4D46] hover:bg-[#FFF1F1] transition shrink-0"
                    aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h2 class="text-[22px] font-extrabold text-[#3F3838] tracking-[-0.03em]">
                Halo, <span class="italic">Admin</span> Salon Dina Muslimah 👋
            </h2>

            <div class="flex items-center gap-[22px]">

                {{-- Cabang Dropdown --}}
                <div class="relative">
                    <button type="button" onclick="toggleDropdown('branchDropdown')"
                            class="h-[50px] min-w-[202px] bg-[#E8A9B4] text-white rounded-[7px] px-[12px] flex items-center justify-between gap-[12px] font-extrabold hover:bg-[#D995A1] transition">
                        <span class="flex items-center gap-[8px]">
                            <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                                <path d="M12 21S5 14.7 5 8.8C5 4.9 8.1 2 12 2C15.9 2 19 4.9 19 8.8C19 14.7 12 21 12 21Z" stroke="white" stroke-width="2"/>
                                <circle cx="12" cy="8.8" r="2.5" stroke="white" stroke-width="2"/>
                            </svg>
                            <span id="branchText" class="text-[13px]">{{ $selectedBranch->label ?? 'Cabang Tembung' }}</span>
                        </span>
                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div id="branchDropdown" class="hidden absolute top-[58px] left-0 w-full bg-white rounded-[12px] shadow-xl border border-[#F1D9DD] overflow-hidden z-50">
                        @foreach($branches as $branch)
                            <a href="{{ route('admin.penjadwalan', ['cabang_id' => $branch->cabang_id, 'tanggal' => $selectedDate]) }}"
                               class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ (int)$selectedCabangId === (int)$branch->cabang_id ? 'bg-[#FFF0F2]' : '' }}">
                                {{ $branch->label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Tanggal Dropdown --}}
                <div class="relative">
                    <button type="button" onclick="toggleDropdown('dateDropdown')"
                            class="h-[50px] min-w-[202px] bg-[#E8A9B4] text-white rounded-[7px] px-[12px] flex items-center justify-between gap-[12px] font-extrabold hover:bg-[#D995A1] transition">
                        <span class="flex items-center gap-[8px]">
                            <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="5" width="18" height="16" rx="2" stroke="white" stroke-width="2"/>
                                <path d="M8 3V7M16 3V7M3 10H21" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span id="dateText" class="text-[13px] leading-tight text-left">
                                {{ $selectedDateLabel }}<br>{{ $selectedDayLabel }}
                            </span>
                        </span>
                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div id="dateDropdown" class="hidden absolute top-[58px] left-0 w-full bg-white rounded-[12px] shadow-xl border border-[#F1D9DD] overflow-hidden z-50">
                        @foreach($dateOptions as $dateOption)
                            <a href="{{ route('admin.penjadwalan', ['cabang_id' => $selectedCabangId, 'tanggal' => $dateOption->date]) }}"
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

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-green-100 text-green-700 px-5 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ===== MAIN SECTION ===== --}}
        <section class="pl-[42px] pr-[0px] mt-[14px] pb-[40px]">
            <div class="grid grid-cols-[minmax(0,1fr)_215px] gap-[8px]">

                {{-- LEFT PANEL --}}
                <div class="bg-[#FDE7EC] rounded-[8px] px-[16px] pt-[22px] pb-[12px] panel-shadow">

                    {{-- Tab bar --}}
                    <div class="h-[64px] bg-[#FFF0F2] rounded-[8px] flex items-center justify-between px-[16px] mb-[22px]">
                        <div class="flex items-center">
                            <button type="button" id="staffTab" onclick="showStaffView()"
                                    class="tab-btn h-[46px] px-[38px] active-tab rounded-[8px] font-extrabold text-[13px] text-[#4A4242] card-shadow">
                                Lihat per Specialist
                            </button>
                            <button type="button" id="bookingTab" onclick="showBookingListView()"
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
                                <span class="w-[20px] h-[20px] rounded-full bg-[#A8BD8C]"></span>Tersedia
                            </button>
                            <button type="button" onclick="filterBookingByLegend('Dikonfirmasi')" class="legend-btn flex items-center gap-[5px]">
                                <span class="w-[20px] h-[20px] rounded-full bg-[#E8A9B4]"></span>Dikonfirmasi
                            </button>
                            <button type="button" onclick="filterBookingByLegend('Break')" class="legend-btn flex items-center gap-[5px]">
                                <span class="w-[20px] h-[20px] rounded-full bg-[#D7D7D7]"></span>Break
                            </button>
                            <button type="button" onclick="filterBookingByLegend('Tunda')" class="legend-btn flex items-center gap-[5px]">
                                <span class="w-[20px] h-[20px] rounded-full bg-[#F7E9BC]"></span>Tunda
                            </button>
                        </div>

                        <button type="button" onclick="openAddBookingModal()"
                                class="h-[42px] px-[22px] rounded-[7px] bg-[#3F372E] text-white text-[13px] font-extrabold hover:opacity-90 transition">
                            + Tambah Booking
                        </button>
                    </div>

                    {{-- ===== JADWAL GRID ===== --}}
                    <div id="staffScheduleView" class="bg-white rounded-[9px] panel-shadow overflow-hidden">
                        <div class="grid border-b border-[#F1C7CE]" style="{{ $gridColumnStyle }}">
                            <div class="h-[64px] flex items-center justify-center border-r border-[#F1C7CE]">
                                <span class="text-[20px] font-extrabold text-black">Waktu</span>
                            </div>
                            @forelse($staffList as $staff)
                                <div class="h-[64px] flex items-center gap-[12px] px-[12px] border-r border-[#F7E0E4] last:border-r-0">
                                    <div class="w-[40px] h-[40px] rounded-full bg-[#E8A9B4] shrink-0 overflow-hidden">
                                        @if($staff->foto_profile)
                                            <img src="{{ asset($staff->foto_profile) }}" class="w-full h-full object-cover" alt="{{ $staff->nama }}">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[18px] font-extrabold leading-none">{{ $staff->nama ?? 'Specialist' }}</p>
                                        {{-- pegawai tidak punya jabatan di DB --}}
                                        <p class="text-[13px] italic font-extrabold leading-none mt-[4px]">Specialist</p>
                                    </div>
                                </div>
                            @empty
                                <div class="h-[64px] flex items-center px-[12px]">
                                    <p class="text-[14px] font-bold text-[#8B7777]">Belum ada specialist</p>
                                </div>
                            @endforelse
                        </div>

                        @foreach($times as $time)
                            <div class="grid border-b border-[#F1C7CE] last:border-b-0" style="{{ $gridColumnStyle }}">
                                <div class="h-[84px] flex items-center justify-center border-r border-[#F1C7CE]">
                                    <span class="text-[20px] text-black">{{ $time }}</span>
                                </div>
                                @forelse($staffList as $staff)
                                    @php $cell = $scheduleGrid[$time][$staff->pegawai_id] ?? null; @endphp
                                    <div class="h-[84px] px-[10px] py-[8px] border-r border-[#F7E0E4] last:border-r-0">
                                        @if(!$cell)
                                            <button type="button" class="schedule-cell w-full h-full rounded-[8px] bg-[#F3F0F0] text-[#8B7777] text-[16px] font-extrabold flex items-center justify-center">-</button>
                                        @elseif($cell->type === 'available')
                                            <button type="button" onclick="selectSlot(this)"
                                                    data-type="available" data-time="{{ $cell->time }}" data-staff="{{ $cell->staff }}"
                                                    class="schedule-cell w-full h-full rounded-[8px] bg-[#EEF7E6] text-[#7E9D62] text-[18px] font-extrabold flex items-center justify-center">
                                                Tersedia
                                            </button>
                                        @elseif($cell->type === 'break')
                                            <button type="button" onclick="selectSlot(this)"
                                                    data-type="break" data-time="{{ $cell->time }}" data-staff="{{ $cell->staff }}"
                                                    class="schedule-cell w-full h-full rounded-[8px] bg-[#E7E2E2] text-[#6B6B6B] text-[18px] font-extrabold flex items-center justify-center">
                                                Break
                                            </button>
                                        @else
                                            @php
                                                $bookingCellClass = match($cell->type) {
                                                    'pending'     => 'bg-[#FFF4D5]',
                                                    'in_progress' => 'bg-[#F6E4A5]',
                                                    'completed'   => 'bg-[#EEF7E6]',
                                                    'cancelled'   => 'bg-[#E7E2E2]',
                                                    default       => 'bg-[#FDE3E8]',
                                                };
                                                $bookingTextClass = match($cell->type) {
                                                    'pending'     => 'text-[#7A6335]',
                                                    'in_progress' => 'text-[#C77A45]',
                                                    'completed'   => 'text-[#7E9D62]',
                                                    'cancelled'   => 'text-[#6B6B6B]',
                                                    default       => 'text-[#B85C6A]',
                                                };
                                            @endphp
                                            <button type="button" onclick="selectSlot(this)"
                                                    data-type="{{ $cell->type }}"
                                                    data-booking-id="{{ $cell->booking_id }}"
                                                    data-booking-type="{{ $cell->booking_type }}"
                                                    data-layanan-cabang-id="{{ $cell->layanan_cabang_id }}"
                                                    data-paket-cabang-id="{{ $cell->paket_cabang_id }}"
                                                    data-pelanggan-id="{{ $cell->pelanggan_id }}"
                                                    data-pegawai-id="{{ $cell->pegawai_id }}"
                                                    data-tanggal-booking="{{ $cell->tanggal_booking }}"
                                                    data-jam-booking="{{ $cell->jam_booking }}"
                                                    data-service="{{ $cell->service }}"
                                                    data-client="{{ $cell->client }}"
                                                    data-customer="{{ $cell->customer }}"
                                                    data-phone="{{ $cell->phone }}"
                                                    data-staff="{{ $cell->staff }}"
                                                    data-time="{{ $cell->time }}"
                                                    data-payment="{{ $cell->payment }}"
                                                    data-payment-raw="{{ $cell->payment_raw }}"
                                                    data-status="{{ $cell->status }}"
                                                    data-status-raw="{{ $cell->status_raw }}"
                                                    data-note="{{ $cell->note }}"
                                                    class="schedule-cell relative w-full h-full rounded-[8px] text-left px-[8px] pt-[8px] {{ $bookingCellClass }}">
                                                <p class="{{ $bookingTextClass }} text-[16px] leading-none font-extrabold">{{ $cell->service }}</p>
                                                <p class="mt-[6px] text-black text-[12px] leading-none italic font-bold opacity-60">
                                                    {{ $cell->booking_type === 'paket' ? '📦 Paket' : '✂️ Layanan' }}
                                                </p>
                                                <p class="mt-[4px] text-black text-[13px] leading-none italic font-extrabold">{{ $cell->client }}</p>
                                                <p class="absolute right-[8px] bottom-[8px] text-black text-[13px] leading-none font-extrabold">{{ $cell->payment }}</p>
                                            </button>
                                        @endif
                                    </div>
                                @empty
                                    <div class="h-[84px] flex items-center px-[12px]">
                                        <p class="text-[13px] font-semibold text-[#8B7777]">Belum ada specialist yang terdaftar.</p>
                                    </div>
                                @endforelse
                            </div>
                        @endforeach
                    </div>

                    {{-- ===== DAFTAR BOOKING ===== --}}
                    <div id="bookingListView" class="hidden bg-white rounded-[9px] panel-shadow overflow-hidden">
                        <div class="px-[24px] pt-[22px] pb-[16px] bg-[#FFF8F9] border-b border-[#F1C7CE]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-[24px] font-extrabold text-[#3F3838]">Daftar Booking Hari Ini</h2>
                                    <p class="text-[13px] font-semibold text-[#7A6A63] mt-[4px]">List booking berdasarkan tanggal dan cabang yang sedang dipilih.</p>
                                </div>
                                <div class="flex items-center gap-[10px]">
                                    <input id="bookingSearch" type="text" oninput="filterBookingList()" placeholder="Cari pelanggan..."
                                           class="w-[210px] h-[38px] rounded-[10px] bg-[#FFF0F2] px-[14px] text-[13px] font-semibold outline-none">
                                    <select id="bookingStatusFilter" onchange="filterBookingList()"
                                            class="h-[38px] rounded-[10px] bg-[#FFF0F2] px-[12px] text-[13px] font-bold outline-none">
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

                        <div class="grid grid-cols-[80px_135px_1fr_145px_125px_105px_135px] px-[18px] py-[14px] bg-[#FFF0F2] border-b border-[#F1C7CE] text-[13px] font-extrabold text-[#4B4242]">
                            <span>ID</span><span>Waktu</span><span>Pelanggan</span><span>Layanan/Paket</span><span>Specialist</span><span>Bayar</span><span>Status</span>
                        </div>

                        <div id="bookingRows">
                            @forelse($bookingList as $booking)
                                <button type="button" onclick="selectBookingRow(this)"
                                        data-id="{{ $booking->id }}"
                                        data-type="{{ $booking->type }}"
                                        data-booking-type="{{ $booking->booking_type }}"
                                        data-pelanggan-id="{{ $booking->pelanggan_id }}"
                                        data-layanan-cabang-id="{{ $booking->layanan_cabang_id }}"
                                        data-paket-cabang-id="{{ $booking->paket_cabang_id }}"
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
                                        class="booking-row w-full grid grid-cols-[80px_135px_1fr_145px_125px_105px_135px] items-center px-[18px] py-[16px] text-left border-b border-[#F1C7CE] hover:bg-[#FFF4F5] transition">
                                    <span class="text-[13px] font-extrabold text-[#8A4357]">BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[13px] font-bold">{{ $booking->time }}</span>
                                    <span>
                                        <p class="text-[14px] font-extrabold text-black">{{ $booking->customer }}</p>
                                        <p class="text-[11px] font-semibold text-[#7A6A63] mt-[2px]">{{ $booking->phone }}</p>
                                    </span>
                                    <span>
                                        <p class="text-[13px] font-extrabold">{{ $booking->service }}</p>
                                        <p class="text-[11px] font-semibold text-[#7A6A63] mt-[1px]">
                                            {{ $booking->booking_type === 'paket' ? '📦 Paket' : '✂️ Layanan' }}
                                        </p>
                                    </span>
                                    <span class="text-[13px] font-extrabold">{{ $booking->staff }}</span>
                                    <span>
                                        <span class="{{ strtolower($booking->payment) === 'cash' ? 'bg-[#FFE5E9]' : 'bg-[#F4ECFF]' }} border border-[#D6B8C0] rounded-[6px] px-[10px] py-[4px] text-[12px] font-extrabold">
                                            {{ $booking->payment }}
                                        </span>
                                    </span>
                                    <span>
                                        @if($booking->status === 'Tunda')
                                            <span class="bg-[#FFF4D5] text-[#7A6335] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">Tunda</span>
                                        @elseif($booking->status === 'Berjalan')
                                            <span class="bg-[#F6E4A5] text-[#C77A45] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">Berjalan</span>
                                        @elseif($booking->status === 'Selesai')
                                            <span class="bg-[#EEF7E6] text-[#7E9D62] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">Selesai</span>
                                        @elseif($booking->status === 'Dibatalkan')
                                            <span class="bg-[#F8D7DD] text-[#B85C6A] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">Batal</span>
                                        @else
                                            <span class="bg-[#FDE3E8] text-[#B85C6A] rounded-[7px] px-[10px] py-[5px] text-[12px] font-extrabold">Dikonfirmasi</span>
                                        @endif
                                    </span>
                                </button>
                            @empty
                                <div class="px-[24px] py-[45px] text-center">
                                    <p class="text-[18px] font-extrabold text-[#3F3838]">Belum ada booking</p>
                                    <p class="text-[13px] font-semibold text-[#7A6A63] mt-[6px]">Booking pada tanggal dan cabang ini belum tersedia.</p>
                                </div>
                            @endforelse
                        </div>

                        <div id="emptyBookingList" class="hidden px-[24px] py-[45px] text-center">
                            <p class="text-[18px] font-extrabold text-[#3F3838]">Booking tidak ditemukan</p>
                            <p class="text-[13px] font-semibold text-[#7A6A63] mt-[6px]">Coba ubah kata kunci pencarian atau filter status.</p>
                        </div>
                    </div>
                </div>

                {{-- ===== RIGHT SIDEBAR DETAIL ===== --}}
                <aside class="bg-white min-h-[900px] px-[12px] py-[24px] card-shadow">
                    <h2 class="text-[19px] font-extrabold text-black">Detail Booking</h2>
                    <div id="detailBadge" class="inline-flex mt-[8px] bg-[#E8B5BC] text-white rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold">Dikonfirmasi</div>

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
                                <p class="text-[13px] font-extrabold leading-none">Layanan / Paket</p>
                                <p id="detailService" class="text-[13px] font-extrabold leading-none mt-[3px] text-black">-</p>
                                <p id="detailServiceType" class="text-[11px] font-semibold mt-[2px] text-[#8A7B7B]">-</p>
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
                        {{-- booking tidak punya catatan di DB, ditampilkan tapi selalu '-' --}}
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
                        <p class="text-[13px] font-extrabold mb-[10px]">Metode Pembayaran</p>
                        <div class="grid grid-cols-2 gap-[20px] px-[22px]">
                            <button type="button" onclick="selectPayment(this)" class="payment-btn bg-[#FFE5E9] border border-[#D6B8C0] rounded-[6px] h-[30px] text-[13px] font-extrabold">Cash</button>
                            <button type="button" onclick="selectPayment(this)" class="payment-btn bg-white border border-[#D6B8C0] rounded-[6px] h-[30px] text-[13px] font-extrabold">Qris</button>
                        </div>
                    </div>

                    <div class="mt-[30px] border-t border-[#F1C7CE] pt-[13px]">
                        <p class="text-[13px] font-extrabold mb-[9px]">Status Booking / Pembayaran</p>
                        <form id="statusUpdateForm" method="POST" action="#" onsubmit="return validateStatusUpdate(event)" class="space-y-[10px]">
                            @csrf
                            @method('PUT')
                            {{-- Status sesuai ENUM booking di DB: pending, confirmed, in_progress, completed, cancelled --}}
                            <select id="statusSelect" name="status" onchange="updateBadgeByStatus(this.value)"
                                    class="w-full h-[42px] bg-white border border-[#D6B8C0] rounded-[6px] px-[12px] text-[13px] font-extrabold card-shadow outline-none">
                                <option value="payment_pending">Bayar Pending</option>
                                <option value="payment_verified">Bayar Terverifikasi</option>
                                <option disabled>──────────</option>
                                <option value="confirmed">Dikonfirmasi</option>
                                <option value="in_progress">Berjalan</option>
                                <option value="completed">Selesai</option>
                                <option value="pending">Tunda</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                            <button type="submit" class="w-full h-[40px] rounded-[6px] bg-[#3F372E] text-white text-[13px] font-extrabold">Update Status</button>
                        </form>
                    </div>

                    <div class="mt-[10px] space-y-[10px]">
                        <button type="button" onclick="enableEditMode()" class="w-full h-[40px] rounded-[6px] bg-[#5A4B4B] text-white text-[13px] font-extrabold">Edit Pemesanan</button>
                        <form id="cancelBookingForm" method="POST" action="#">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openCancelModal()" class="w-full h-[40px] rounded-[6px] bg-[#B85C6A] text-white text-[13px] font-extrabold">Batalkan</button>
                        </form>
                        <button type="button" onclick="window.print()" class="w-full h-[40px] rounded-[6px] bg-white border border-[#D6B8C0] text-[#4B4242] text-[13px] font-extrabold">Cetak Nota</button>
                    </div>
                </aside>

            </div>
        </section>
    </main>
</div>


{{-- ===================================================
     MODAL TAMBAH BOOKING
     ==================================================== --}}
<div id="addBookingModal" onclick="closeAddBookingByOverlay(event)"
     class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">

    <form id="bookingForm"
          action="{{ route('admin.penjadwalan.booking.store') }}"
          method="POST"
          class="w-full max-w-[650px] bg-white rounded-[18px] shadow-2xl overflow-hidden">
        @csrf

        <div class="px-[26px] py-[20px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <div>
                <h2 class="text-[24px] font-extrabold text-[#4B3A36]">Tambah Booking</h2>
                <p class="text-[13px] font-semibold text-[#7B6A62] mt-[4px]">
                    {{ $selectedBranch->label ?? 'Cabang Salon' }} - {{ $selectedDayLabel }}, {{ $selectedDateLabel }}
                </p>
            </div>
            <button type="button" onclick="closeAddBookingModal()"
                    class="w-[38px] h-[38px] rounded-full bg-[#4B3A36] text-white text-[26px] leading-none flex items-center justify-center">×</button>
        </div>

        <div class="px-[26px] py-[22px] grid grid-cols-2 gap-[16px]">

            {{-- Pelanggan - Searchable --}}
            <div class="relative">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Pelanggan</label>
                <input type="text" id="searchPelanggan" autocomplete="off"
                       placeholder="Ketik nama atau no. HP..."
                       oninput="filterSearchDropdown('searchPelanggan','dropdownPelanggan','bookingPelangganId')"
                       onfocus="openSearchDropdown('dropdownPelanggan')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select name="pelanggan_id" id="bookingPelangganId" class="hidden" required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->pelanggan_id }}"
                                data-label="{{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}">
                            {{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownPelanggan" class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @foreach($customers as $customer)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $customer->pelanggan_id }}"
                             data-label="{{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}"
                             onclick="pickSearchOption('searchPelanggan','dropdownPelanggan','bookingPelangganId',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $customer->nama ?? 'Pelanggan' }}</span>
                            <span class="text-[#8A7B7B] ml-1 text-[12px]">{{ $customer->no_hp ?? '-' }}</span>
                        </div>
                    @endforeach
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Specialist - Searchable --}}
            <div class="relative">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Specialist</label>
                <input type="text" id="searchPegawai" autocomplete="off"
                       placeholder="Ketik nama specialist..."
                       oninput="filterSearchDropdown('searchPegawai','dropdownPegawai','bookingPegawaiId')"
                       onfocus="openSearchDropdown('dropdownPegawai')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select name="pegawai_id" id="bookingPegawaiId" class="hidden" required>
                    <option value="">Pilih Specialist</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->pegawai_id }}" data-label="{{ $staff->nama ?? 'Specialist' }}">
                            {{ $staff->nama ?? 'Specialist' }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownPegawai" class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @foreach($staffList as $staff)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $staff->pegawai_id }}"
                             data-label="{{ $staff->nama ?? 'Specialist' }}"
                             onclick="pickSearchOption('searchPegawai','dropdownPegawai','bookingPegawaiId',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $staff->nama ?? 'Specialist' }}</span>
                        </div>
                    @endforeach
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Toggle Layanan / Paket (col-span-2) --}}
            <div class="col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Tipe Pesanan</label>
                <div class="mt-[6px] flex gap-[10px]">
                    <button type="button" id="btnTypeLayanan"
                            onclick="setBookingType('layanan')"
                            class="h-[36px] px-[20px] rounded-[8px] bg-[#E8A9B4] text-white text-[13px] font-extrabold transition">
                        ✂️ Layanan
                    </button>
                    <button type="button" id="btnTypePaket"
                            onclick="setBookingType('paket')"
                            class="h-[36px] px-[20px] rounded-[8px] bg-[#F0E0E4] text-[#4B3A36] text-[13px] font-extrabold transition">
                        📦 Paket
                    </button>
                </div>
                <input type="hidden" name="booking_type" id="bookingType" value="layanan">
            </div>

            {{-- Dropdown Layanan (searchable) --}}
            <div id="wrapLayanan" class="relative col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Layanan</label>
                <input type="text" id="searchLayanan" autocomplete="off"
                       placeholder="Ketik nama layanan..."
                       oninput="filterSearchDropdown('searchLayanan','dropdownLayanan','bookingLayananCabangId')"
                       onfocus="openSearchDropdown('dropdownLayanan')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select name="layanan_cabang_id" id="bookingLayananCabangId" class="hidden" required>
                    <option value="">Pilih Layanan</option>
                    @foreach($services as $service)
                        <option value="{{ $service->layanan_cabang_id }}"
                                data-label="{{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}">
                            {{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownLayanan"
                     class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @foreach($services as $service)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $service->layanan_cabang_id }}"
                             data-label="{{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}"
                             onclick="pickSearchOption('searchLayanan','dropdownLayanan','bookingLayananCabangId',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $service->nama_layanan }}</span>
                            <span class="text-[#8A7B7B] ml-1 text-[12px]">Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Dropdown Paket (searchable, hidden by default) --}}
            <div id="wrapPaket" class="relative hidden col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Paket</label>
                <input type="text" id="searchPaket" autocomplete="off"
                       placeholder="Ketik nama paket..."
                       oninput="filterSearchDropdown('searchPaket','dropdownPaket','bookingPaketCabangId')"
                       onfocus="openSearchDropdown('dropdownPaket')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select name="paket_cabang_id" id="bookingPaketCabangId" class="hidden">
                    <option value="">Pilih Paket</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->paket_cabang_id }}"
                                data-label="{{ $package->nama_paket }} - Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}">
                            {{ $package->nama_paket }} - Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownPaket"
                     class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @forelse($packages as $package)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $package->paket_cabang_id }}"
                             data-label="{{ $package->nama_paket }} - Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}"
                             onclick="pickSearchOption('searchPaket','dropdownPaket','bookingPaketCabangId',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $package->nama_paket }}</span>
                            <span class="text-[#8A7B7B] ml-1 text-[12px]">Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Belum ada paket tersedia</p>
                    @endforelse
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Tanggal</label>
                <input type="date" name="tanggal_booking" id="bookingTanggal" value="{{ $selectedDate }}"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
            </div>

            {{-- Jam --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Jam</label>
                <select name="jam_booking" id="bookingJam"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
                    <option value="">Pilih Jam</option>
                    @foreach($times as $time)
                        <option value="{{ $time }}">{{ $time }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Metode Pembayaran</label>
                {{-- pembayaran.metode_pembayaran ENUM: cash, qris --}}
                <select name="metode_pembayaran" id="bookingMetodePembayaran"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            {{-- Status — ENUM booking: pending, confirmed, in_progress, completed, cancelled --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Status</label>
                <select name="status" id="bookingStatus"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="pending">Tunda</option>
                    <option value="in_progress">Berjalan</option>
                    <option value="completed">Selesai</option>
                </select>
            </div>

        </div>

        <div class="px-[26px] pb-[24px] flex justify-end gap-[12px]">
            <button type="button" onclick="closeAddBookingModal()" class="h-[42px] px-[20px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] font-extrabold">Batal</button>
            <button type="submit" class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] text-white font-extrabold hover:bg-[#D995A1] transition">Simpan Booking</button>
        </div>
    </form>
</div>


{{-- ===================================================
     MODAL EDIT BOOKING
     ==================================================== --}}
<div id="editBookingModal" onclick="closeEditBookingByOverlay(event)"
     class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">

    <form id="editBookingForm" action="#" method="POST"
          class="w-full max-w-[650px] bg-white rounded-[18px] shadow-2xl overflow-hidden">
        @csrf
        @method('PUT')

        <div class="px-[26px] py-[20px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <div>
                <h2 class="text-[24px] font-extrabold text-[#4B3A36]">Edit Pemesanan</h2>
                <p class="text-[13px] font-semibold text-[#7B6A62] mt-[4px]">Ubah data booking yang sedang dipilih.</p>
            </div>
            <button type="button" onclick="closeEditBookingModal()"
                    class="w-[38px] h-[38px] rounded-full bg-[#4B3A36] text-white text-[26px] leading-none flex items-center justify-center">×</button>
        </div>

        <div class="px-[26px] py-[22px] grid grid-cols-2 gap-[16px]">
            <input type="hidden" id="edit_booking_id">

            {{-- Pelanggan - Searchable --}}
            <div class="relative">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Pelanggan</label>
                <input type="text" id="searchEditPelanggan" autocomplete="off"
                       placeholder="Ketik nama atau no. HP..."
                       oninput="filterSearchDropdown('searchEditPelanggan','dropdownEditPelanggan','edit_pelanggan_id')"
                       onfocus="openSearchDropdown('dropdownEditPelanggan')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select id="edit_pelanggan_id" name="pelanggan_id" class="hidden" required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->pelanggan_id }}"
                                data-label="{{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}">
                            {{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownEditPelanggan" class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @foreach($customers as $customer)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $customer->pelanggan_id }}"
                             data-label="{{ $customer->nama ?? 'Pelanggan' }} - {{ $customer->no_hp ?? '-' }}"
                             onclick="pickSearchOption('searchEditPelanggan','dropdownEditPelanggan','edit_pelanggan_id',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $customer->nama ?? 'Pelanggan' }}</span>
                            <span class="text-[#8A7B7B] ml-1 text-[12px]">{{ $customer->no_hp ?? '-' }}</span>
                        </div>
                    @endforeach
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Specialist - Searchable --}}
            <div class="relative">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Specialist</label>
                <input type="text" id="searchEditPegawai" autocomplete="off"
                       placeholder="Ketik nama specialist..."
                       oninput="filterSearchDropdown('searchEditPegawai','dropdownEditPegawai','edit_pegawai_id')"
                       onfocus="openSearchDropdown('dropdownEditPegawai')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select id="edit_pegawai_id" name="pegawai_id" class="hidden" required>
                    <option value="">Pilih Specialist</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->pegawai_id }}" data-label="{{ $staff->nama ?? 'Specialist' }}">
                            {{ $staff->nama ?? 'Specialist' }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownEditPegawai" class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @foreach($staffList as $staff)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $staff->pegawai_id }}"
                             data-label="{{ $staff->nama ?? 'Specialist' }}"
                             onclick="pickSearchOption('searchEditPegawai','dropdownEditPegawai','edit_pegawai_id',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $staff->nama ?? 'Specialist' }}</span>
                        </div>
                    @endforeach
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Toggle Layanan / Paket (edit) --}}
            <div class="col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Tipe Pesanan</label>
                <div class="mt-[6px] flex gap-[10px]">
                    <button type="button" id="edit_btnTypeLayanan"
                            onclick="setBookingType('layanan','edit_')"
                            class="h-[36px] px-[20px] rounded-[8px] bg-[#E8A9B4] text-white text-[13px] font-extrabold transition">
                        ✂️ Layanan
                    </button>
                    <button type="button" id="edit_btnTypePaket"
                            onclick="setBookingType('paket','edit_')"
                            class="h-[36px] px-[20px] rounded-[8px] bg-[#F0E0E4] text-[#4B3A36] text-[13px] font-extrabold transition">
                        📦 Paket
                    </button>
                </div>
                <input type="hidden" name="booking_type" id="edit_bookingType" value="layanan">
            </div>

            {{-- Dropdown Layanan (edit, searchable) --}}
            <div id="edit_wrapLayanan" class="relative col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Layanan</label>
                <input type="text" id="searchEditLayanan" autocomplete="off"
                       placeholder="Ketik nama layanan..."
                       oninput="filterSearchDropdown('searchEditLayanan','dropdownEditLayanan','edit_layanan_cabang_id')"
                       onfocus="openSearchDropdown('dropdownEditLayanan')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select id="edit_layanan_cabang_id" name="layanan_cabang_id" class="hidden" required>
                    <option value="">Pilih Layanan</option>
                    @foreach($services as $service)
                        <option value="{{ $service->layanan_cabang_id }}"
                                data-label="{{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}">
                            {{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownEditLayanan"
                     class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @foreach($services as $service)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $service->layanan_cabang_id }}"
                             data-label="{{ $service->nama_layanan }} - Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}"
                             onclick="pickSearchOption('searchEditLayanan','dropdownEditLayanan','edit_layanan_cabang_id',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $service->nama_layanan }}</span>
                            <span class="text-[#8A7B7B] ml-1 text-[12px]">Rp {{ number_format($service->harga ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Dropdown Paket (edit, searchable, hidden by default) --}}
            <div id="edit_wrapPaket" class="relative hidden col-span-2">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Paket</label>
                <input type="text" id="searchEditPaket" autocomplete="off"
                       placeholder="Ketik nama paket..."
                       oninput="filterSearchDropdown('searchEditPaket','dropdownEditPaket','edit_paket_cabang_id')"
                       onfocus="openSearchDropdown('dropdownEditPaket')"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none text-[13px]">
                <select id="edit_paket_cabang_id" name="paket_cabang_id" class="hidden">
                    <option value="">Pilih Paket</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->paket_cabang_id }}"
                                data-label="{{ $package->nama_paket }} - Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}">
                            {{ $package->nama_paket }} - Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <div id="dropdownEditPaket"
                     class="hidden absolute left-0 right-0 z-50 mt-[2px] max-h-[200px] overflow-y-auto bg-white border border-[#F1D9DD] rounded-[8px] shadow-lg">
                    @forelse($packages as $package)
                        <div class="search-option px-[12px] py-[9px] text-[13px] hover:bg-[#FFF0F2] cursor-pointer"
                             data-value="{{ $package->paket_cabang_id }}"
                             data-label="{{ $package->nama_paket }} - Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}"
                             onclick="pickSearchOption('searchEditPaket','dropdownEditPaket','edit_paket_cabang_id',this)">
                            <span class="font-extrabold text-[#3F3838]">{{ $package->nama_paket }}</span>
                            <span class="text-[#8A7B7B] ml-1 text-[12px]">Rp {{ number_format($package->harga ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Belum ada paket tersedia</p>
                    @endforelse
                    <p class="search-empty hidden px-[12px] py-[9px] text-[13px] text-[#8A7B7B] italic">Tidak ditemukan</p>
                </div>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Tanggal</label>
                <input id="edit_tanggal_booking" type="date" name="tanggal_booking"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
            </div>

            {{-- Jam --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Jam</label>
                <select id="edit_jam_booking" name="jam_booking"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
                    <option value="">Pilih Jam</option>
                    @foreach($times as $time)
                        <option value="{{ $time }}">{{ $time }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Metode Pembayaran</label>
                <select id="edit_metode_pembayaran" name="metode_pembayaran"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Status</label>
                <select id="edit_status" name="status"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none" required>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="pending">Tunda</option>
                    <option value="in_progress">Berjalan</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>

        </div>

        <div class="px-[26px] pb-[24px] flex justify-end gap-[12px]">
            <button type="button" onclick="closeEditBookingModal()" class="h-[42px] px-[20px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] font-extrabold">Batal</button>
            <button type="submit" class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] text-white font-extrabold hover:bg-[#D995A1] transition">Simpan Perubahan</button>
        </div>
    </form>
</div>


{{-- ===================================================
     MODAL KONFIRMASI BATAL
     ==================================================== --}}
<div id="cancelModal" class="hidden fixed inset-0 z-[9999] bg-black/40 items-center justify-center">
    <div class="bg-white w-[420px] rounded-[16px] p-6 shadow-2xl">
        <h2 class="text-[22px] font-extrabold text-[#B85C6A]">⚠️ Batalkan Booking</h2>
        <p class="mt-4 text-[14px] text-[#4B4242] leading-relaxed">Apakah Anda yakin ingin membatalkan booking ini?</p>
        <p class="mt-2 text-[13px] text-[#8A7B7B]">Tindakan ini tidak dapat dikembalikan.</p>
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeCancelModal()" class="px-5 h-[40px] rounded-[8px] bg-[#EFE4E4] font-bold">Kembali</button>
            <button type="button" onclick="submitCancelBooking()" class="px-5 h-[40px] rounded-[8px] bg-[#B85C6A] text-white font-bold">Ya, Batalkan</button>
        </div>
    </div>
</div>


{{-- ===================================================
     JAVASCRIPT
     ==================================================== --}}
<script>
let selectedBooking = null;

// ============ TOGGLE LAYANAN / PAKET ============
// prefix = '' untuk form tambah, 'edit_' untuk form edit
function setBookingType(type, prefix = '') {
    const p = prefix;
    const inputId = p + 'bookingType';
    const wrapLayananId = p + 'wrapLayanan';
    const wrapPaketId   = p + 'wrapPaket';
    const btnLayananId  = p + 'btnTypeLayanan';
    const btnPaketId    = p + 'btnTypePaket';
    const selectLayanan = document.getElementById(p + 'bookingLayananCabangId') || document.getElementById(p + 'layanan_cabang_id');
    const selectPaket   = document.getElementById(p + 'bookingPaketCabangId')   || document.getElementById(p + 'paket_cabang_id');

    document.getElementById(inputId).value = type;

    if (type === 'paket') {
        document.getElementById(wrapLayananId).classList.add('hidden');
        document.getElementById(wrapPaketId).classList.remove('hidden');
        if (selectLayanan) selectLayanan.removeAttribute('required');
        if (selectPaket)   selectPaket.setAttribute('required', '');
        document.getElementById(btnPaketId).classList.replace('bg-[#F0E0E4]',  'bg-[#E8A9B4]');
        document.getElementById(btnPaketId).classList.replace('text-[#4B3A36]', 'text-white');
        document.getElementById(btnLayananId).classList.replace('bg-[#E8A9B4]', 'bg-[#F0E0E4]');
        document.getElementById(btnLayananId).classList.replace('text-white',    'text-[#4B3A36]');
    } else {
        document.getElementById(wrapLayananId).classList.remove('hidden');
        document.getElementById(wrapPaketId).classList.add('hidden');
        if (selectLayanan) selectLayanan.setAttribute('required', '');
        if (selectPaket)   selectPaket.removeAttribute('required');
        document.getElementById(btnLayananId).classList.replace('bg-[#F0E0E4]',  'bg-[#E8A9B4]');
        document.getElementById(btnLayananId).classList.replace('text-[#4B3A36]', 'text-white');
        document.getElementById(btnPaketId).classList.replace('bg-[#E8A9B4]', 'bg-[#F0E0E4]');
        document.getElementById(btnPaketId).classList.replace('text-white',    'text-[#4B3A36]');
    }
}

// ============ SEARCHABLE DROPDOWN ============
function openSearchDropdown(dropdownId) {
    document.querySelectorAll('[id^="dropdown"]').forEach(el => {
        if (el.id !== 'branchDropdown' && el.id !== 'dateDropdown' && el.id !== dropdownId) {
            el.classList.add('hidden');
        }
    });
    document.getElementById(dropdownId).classList.remove('hidden');
}

function filterSearchDropdown(inputId, dropdownId, selectId) {
    const keyword  = document.getElementById(inputId).value.toLowerCase().trim();
    const dropdown = document.getElementById(dropdownId);
    const options  = dropdown.querySelectorAll('.search-option');
    const emptyEl  = dropdown.querySelector('.search-empty');

    document.getElementById(selectId).value = '';
    dropdown.classList.remove('hidden');
    let visible = 0;

    options.forEach(opt => {
        const label = (opt.dataset.label || '').toLowerCase();
        const match = !keyword || label.includes(keyword);
        opt.classList.toggle('hidden', !match);
        if (match) visible++;
    });

    if (emptyEl) emptyEl.classList.toggle('hidden', visible > 0);
}

function pickSearchOption(inputId, dropdownId, selectId, optEl) {
    document.getElementById(inputId).value  = optEl.dataset.label;
    document.getElementById(selectId).value = optEl.dataset.value;
    document.getElementById(dropdownId).classList.add('hidden');
}

document.addEventListener('click', function(e) {
    const insideSearch = e.target.closest('[id^="dropdown"],[id^="search"]');
    if (!insideSearch) {
        document.querySelectorAll('[id^="dropdown"]').forEach(el => {
            if (el.id !== 'branchDropdown' && el.id !== 'dateDropdown') {
                el.classList.add('hidden');
            }
        });
    }
    const insideBranchDate  = e.target.closest('#branchDropdown,#dateDropdown');
    const isDropdownTrigger = e.target.closest('button[onclick^="toggleDropdown"]');
    if (!insideBranchDate && !isDropdownTrigger) {
        document.getElementById('branchDropdown')?.classList.add('hidden');
        document.getElementById('dateDropdown')?.classList.add('hidden');
    }
});

// ============ BRANCH / DATE DROPDOWN ============
function toggleDropdown(id) {
    const target = document.getElementById(id);
    ['branchDropdown','dateDropdown'].forEach(did => {
        if (did !== id) document.getElementById(did)?.classList.add('hidden');
    });
    target?.classList.toggle('hidden');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeAddBookingModal(); closeEditBookingModal(); }
});

// ============ TAB SWITCH ============
function showStaffView() {
    document.getElementById('staffScheduleView').classList.remove('hidden');
    document.getElementById('bookingListView').classList.add('hidden');
    document.getElementById('staffTab').classList.replace('inactive-tab','active-tab');
    document.getElementById('bookingTab').classList.replace('active-tab','inactive-tab');
}
function showBookingListView() {
    document.getElementById('staffScheduleView').classList.add('hidden');
    document.getElementById('bookingListView').classList.remove('hidden');
    document.getElementById('bookingTab').classList.replace('inactive-tab','active-tab');
    document.getElementById('staffTab').classList.replace('active-tab','inactive-tab');
}

// ============ FILTER BOOKING LIST ============
function filterBookingByLegend(status) {
    showBookingListView();
    const filter = document.getElementById('bookingStatusFilter');
    filter.value = (status === 'Dikonfirmasi') ? 'Dikonfirmasi'
                 : (status === 'Tunda')        ? 'Tunda'
                 : 'Semua';
    filterBookingList();
}
function filterBookingList() {
    const keyword = (document.getElementById('bookingSearch')?.value || '').toLowerCase();
    const status  = document.getElementById('bookingStatusFilter')?.value || 'Semua';
    const rows    = document.querySelectorAll('.booking-row');
    let visible   = 0;
    rows.forEach(row => {
        const match =
            ((row.dataset.customer||'').toLowerCase().includes(keyword) ||
             (row.dataset.service ||'').toLowerCase().includes(keyword) ||
             (row.dataset.staff   ||'').toLowerCase().includes(keyword)) &&
            (status === 'Semua' || row.dataset.status === status);
        row.classList.toggle('hidden', !match);
        if (match) visible++;
    });
    document.getElementById('emptyBookingList').classList.toggle('hidden', !(visible === 0 && rows.length > 0));
}

// ============ SELECTED BOOKING STATE ============
function setSelectedBookingFromDataset(src) {
    selectedBooking = {
        id              : src.dataset.bookingId || src.dataset.id || '',
        pelangganId     : src.dataset.pelangganId     || '',
        layananCabangId : src.dataset.layananCabangId || '',
        paketCabangId   : src.dataset.paketCabangId   || '',
        bookingType     : src.dataset.bookingType     || 'layanan',
        pegawaiId       : src.dataset.pegawaiId       || '',
        tanggalBooking  : src.dataset.tanggalBooking  || "{{ $selectedDate }}",
        jamBooking      : src.dataset.jamBooking || (src.dataset.time ? src.dataset.time.substring(0,5) : ''),
        metodePembayaran: src.dataset.paymentRaw  || 'cash',
        status          : src.dataset.statusRaw   || 'confirmed',
        pelangganLabel  : src.dataset.customer    || '',
        pegawaiLabel    : src.dataset.staff       || '',
    };
}
function resetSelectedBooking() { selectedBooking = null; }

// ============ SELECT SLOT (GRID) ============
function selectSlot(button) {
    document.querySelectorAll('.schedule-cell').forEach(el => el.classList.remove('selected-slot'));
    button.classList.add('selected-slot');

    const type      = button.dataset.type;
    const bookingId = button.dataset.bookingId;
    const badge     = document.getElementById('detailBadge');

    document.getElementById('detailTime').textContent  = button.dataset.time  || '-';
    document.getElementById('detailStaff').textContent = button.dataset.staff || '-';

    if (bookingId) {
        setSelectedBookingFromDataset(button);
        document.getElementById('statusUpdateForm').action  = "{{ url('/admin/penjadwalan/booking') }}/" + bookingId + "/status";
        document.getElementById('cancelBookingForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + bookingId;
    } else {
        resetSelectedBooking();
        document.getElementById('statusUpdateForm').action  = '#';
        document.getElementById('cancelBookingForm').action = '#';
    }

    if (type === 'available') {
        badge.textContent = 'Tersedia';
        badge.className = 'inline-flex mt-[8px] bg-[#EEF7E6] text-[#7E9D62] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        document.getElementById('detailCustomer').textContent    = '-';
        document.getElementById('detailPhone').textContent       = '-';
        document.getElementById('detailService').textContent     = 'Slot tersedia';
        document.getElementById('detailServiceType').textContent = '-';
        document.getElementById('detailNote').textContent        = 'Belum ada booking pada slot ini';
        document.getElementById('statusSelect').value            = 'confirmed';
        return;
    }
    if (type === 'break') {
        badge.textContent = 'Break';
        badge.className = 'inline-flex mt-[8px] bg-[#E7E2E2] text-[#6B6B6B] rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold';
        document.getElementById('detailCustomer').textContent    = '-';
        document.getElementById('detailPhone').textContent       = '-';
        document.getElementById('detailService').textContent     = 'Break';
        document.getElementById('detailServiceType').textContent = '-';
        document.getElementById('detailNote').textContent        = 'Specialist tidak tersedia pada jam ini';
        document.getElementById('statusSelect').value            = 'confirmed';
        return;
    }

    document.getElementById('detailCustomer').textContent    = button.dataset.customer || '-';
    document.getElementById('detailPhone').textContent       = button.dataset.phone    || '-';
    document.getElementById('detailService').textContent     = button.dataset.service  || '-';
    document.getElementById('detailServiceType').textContent = button.dataset.bookingType === 'paket' ? '📦 Paket' : '✂️ Layanan';
    document.getElementById('detailNote').textContent        = button.dataset.note     || '-';

    const statusVal = button.dataset.statusRaw || 'confirmed';
    document.getElementById('statusSelect').value = statusVal;
    updateBadgeByStatus(statusVal);
    updatePaymentButton(button.dataset.payment || 'Cash');
}

// ============ SELECT BOOKING ROW (LIST) ============
function selectBookingRow(row) {
    document.querySelectorAll('.booking-row').forEach(el => el.classList.remove('selected-booking-row'));
    row.classList.add('selected-booking-row');
    setSelectedBookingFromDataset(row);

    document.getElementById('detailCustomer').textContent    = row.dataset.customer || '-';
    document.getElementById('detailPhone').textContent       = row.dataset.phone    || '-';
    document.getElementById('detailService').textContent     = row.dataset.service  || '-';
    document.getElementById('detailServiceType').textContent = row.dataset.bookingType === 'paket' ? '📦 Paket' : '✂️ Layanan';
    document.getElementById('detailStaff').textContent       = row.dataset.staff    || '-';
    document.getElementById('detailTime').textContent        = row.dataset.time     || '-';
    document.getElementById('detailNote').textContent        = row.dataset.note     || '-';

    document.getElementById('statusUpdateForm').action  = "{{ url('/admin/penjadwalan/booking') }}/" + row.dataset.id + "/status";
    document.getElementById('cancelBookingForm').action = "{{ url('/admin/penjadwalan/booking') }}/" + row.dataset.id;

    const statusVal = row.dataset.statusRaw || 'confirmed';
    document.getElementById('statusSelect').value = statusVal;
    updateBadgeByStatus(statusVal);
    updatePaymentButton(row.dataset.payment || 'Cash');
}

// ============ PAYMENT ============
function selectPayment(btn) {
    document.querySelectorAll('.payment-btn').forEach(b => {
        b.classList.remove('bg-[#FFE5E9]'); b.classList.add('bg-white');
    });
    btn.classList.remove('bg-white'); btn.classList.add('bg-[#FFE5E9]');
}
function updatePaymentButton(payment) {
    document.querySelectorAll('.payment-btn').forEach(btn => {
        const isMatch = btn.textContent.trim().toLowerCase() === payment.toLowerCase();
        btn.classList.toggle('bg-[#FFE5E9]', isMatch);
        btn.classList.toggle('bg-white', !isMatch);
    });
}

// ============ BADGE — sesuai ENUM DB ============
function updateBadgeByStatus(status) {
    const badge = document.getElementById('detailBadge');
    const map = {
        'payment_pending'  : ['Bayar Pending',      'bg-[#FFF4D5] text-[#6B6040]'],
        'payment_verified' : ['Bayar Terverifikasi', 'bg-[#EEF7E6] text-[#7E9D62]'],
        'confirmed'        : ['Dikonfirmasi',        'bg-[#E8B5BC] text-white'],
        'in_progress'      : ['Berjalan',            'bg-[#F6E4A5] text-[#C77A45]'],
        'completed'        : ['Selesai',             'bg-[#EEF7E6] text-[#7E9D62]'],
        'pending'          : ['Tunda',               'bg-[#FFF4D5] text-[#6B6040]'],
        'cancelled'        : ['Dibatalkan',          'bg-[#B85C6A] text-white'],
    };
    const [label, cls] = map[status] ?? ['Dikonfirmasi', 'bg-[#E8B5BC] text-white'];
    badge.textContent = label;
    badge.className   = `inline-flex mt-[8px] ${cls} rounded-[4px] px-[11px] py-[4px] text-[12px] font-extrabold`;
}

// ============ VALIDATE STATUS UPDATE ============
function validateStatusUpdate(event) {
    if (!selectedBooking?.id) {
        event.preventDefault();
        alert('Pilih booking dulu sebelum update status.');
        return false;
    }
    return true;
}

// ============ RESET FORM TAMBAH ============
function resetBookingForm() {
    ['searchPelanggan','searchPegawai'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    ['bookingPelangganId','bookingPegawaiId','bookingLayananCabangId','bookingPaketCabangId'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });

    // Reset search input text
    ['searchPelanggan','searchPegawai','searchLayanan','searchPaket'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });

    const els = {
        bookingJam             : '',
        bookingMetodePembayaran: 'cash',
        bookingStatus          : 'confirmed',
    };
    Object.entries(els).forEach(([id, val]) => {
        const el = document.getElementById(id);
        if (el) el.value = val;
    });

    const bookingTanggal = document.getElementById('bookingTanggal');
    if (bookingTanggal) bookingTanggal.value = "{{ $selectedDate }}";

    // Reset ke tipe layanan
    setBookingType('layanan');

    document.querySelectorAll('[id^="dropdown"]').forEach(el => {
        if (el.id !== 'branchDropdown' && el.id !== 'dateDropdown') el.classList.add('hidden');
    });
}

// ============ MODAL TAMBAH ============
function openAddBookingModal() {
    resetBookingForm();
    document.getElementById('addBookingModal').classList.remove('hidden');
    document.getElementById('addBookingModal').classList.add('flex');
}
function closeAddBookingModal() {
    document.getElementById('addBookingModal').classList.add('hidden');
    document.getElementById('addBookingModal').classList.remove('flex');
}
function closeAddBookingByOverlay(e) {
    if (e.target.id === 'addBookingModal') closeAddBookingModal();
}

// ============ MODAL EDIT ============
function openEditBookingModal() {
    document.getElementById('editBookingModal').classList.remove('hidden');
    document.getElementById('editBookingModal').classList.add('flex');
}
function closeEditBookingModal() {
    document.getElementById('editBookingModal').classList.add('hidden');
    document.getElementById('editBookingModal').classList.remove('flex');
    ['dropdownEditPelanggan','dropdownEditPegawai','dropdownEditLayanan','dropdownEditPaket'].forEach(id => {
        document.getElementById(id)?.classList.add('hidden');
    });
}
function closeEditBookingByOverlay(e) {
    if (e.target.id === 'editBookingModal') closeEditBookingModal();
}

function enableEditMode() {
    if (!selectedBooking?.id) {
        alert('Pilih booking dulu sebelum edit pemesanan.');
        return;
    }

    document.getElementById('editBookingForm').action =
        "{{ url('/admin/penjadwalan/booking') }}/" + selectedBooking.id;

    document.getElementById('edit_booking_id').value        = selectedBooking.id;
    document.getElementById('edit_tanggal_booking').value   = selectedBooking.tanggalBooking;
    document.getElementById('edit_jam_booking').value       = selectedBooking.jamBooking;
    document.getElementById('edit_metode_pembayaran').value = selectedBooking.metodePembayaran;
    document.getElementById('edit_status').value            = selectedBooking.status;

    // Set tipe booking (layanan/paket)
    setBookingType(selectedBooking.bookingType || 'layanan', 'edit_');

    if (selectedBooking.bookingType === 'paket') {
        const paketSelect = document.getElementById('edit_paket_cabang_id');
        paketSelect.value = selectedBooking.paketCabangId;
        const paketOpt = paketSelect.querySelector(`option[value="${selectedBooking.paketCabangId}"]`);
        document.getElementById('searchEditPaket').value   = paketOpt ? (paketOpt.dataset.label || paketOpt.textContent.trim()) : '';
        document.getElementById('edit_layanan_cabang_id').value = '';
        document.getElementById('searchEditLayanan').value = '';
    } else {
        const layananSelect = document.getElementById('edit_layanan_cabang_id');
        layananSelect.value = selectedBooking.layananCabangId;
        const layananOpt = layananSelect.querySelector(`option[value="${selectedBooking.layananCabangId}"]`);
        document.getElementById('searchEditLayanan').value = layananOpt ? (layananOpt.dataset.label || layananOpt.textContent.trim()) : '';
        document.getElementById('edit_paket_cabang_id').value = '';
        document.getElementById('searchEditPaket').value  = '';
    }

    // Set search pelanggan
    const pelSelect = document.getElementById('edit_pelanggan_id');
    pelSelect.value = selectedBooking.pelangganId;
    const pelOpt = pelSelect.querySelector(`option[value="${selectedBooking.pelangganId}"]`);
    document.getElementById('searchEditPelanggan').value = pelOpt
        ? (pelOpt.dataset.label || pelOpt.textContent.trim())
        : selectedBooking.pelangganLabel;

    // Set search pegawai
    const pegSelect = document.getElementById('edit_pegawai_id');
    pegSelect.value = selectedBooking.pegawaiId;
    const pegOpt = pegSelect.querySelector(`option[value="${selectedBooking.pegawaiId}"]`);
    document.getElementById('searchEditPegawai').value = pegOpt
        ? (pegOpt.dataset.label || pegOpt.textContent.trim())
        : selectedBooking.pegawaiLabel;

    openEditBookingModal();
}

// ============ MODAL BATAL ============
function validateBookingAction() {
    const cancelAction = document.getElementById('cancelBookingForm').action;
    if (!cancelAction.includes('/admin/penjadwalan/booking/') || !selectedBooking?.id) {
        alert('Pilih data booking terlebih dahulu.');
        return false;
    }
    return true;
}
function openCancelModal() {
    if (!validateBookingAction()) return;
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}
function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}
function submitCancelBooking() {
    document.getElementById('cancelBookingForm').submit();
}

// ============ AUTO-HIDE FLASH ============
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 3000);
    });
});
</script>

</body>
</html>