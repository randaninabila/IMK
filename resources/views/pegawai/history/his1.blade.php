@extends('pegawai.app')

@section('content')

@php
    $filter = request('filter', 'semua');
    $jenisLayananId = request('jenis_layanan');
    $tanggal = request('tanggal'); // ← Single parameter tanggal

    $judulFilter = match($filter) {
        'hariini' => 'Hari Ini',
        'bulanan' => 'Bulan Ini',
        'tahunan' => 'Tahun Ini',
        default => 'Semua Jadwal'
    };
@endphp

<div class="w-full px-4 py-4 font-sans">

    {{-- TITLE --}}
    <div>
        <h1 class="text-[26px] font-bold text-[#3E382D] leading-none">
            Riwayat Aktivitas
        </h1>
        <p class="mt-2 text-[16px] text-[#4F4545]">
            Lihat dan kelola semua aktivitas yang anda kerjakan
        </p>
    </div>

    {{-- FILTER TAB — Button submit form (pertahankan parameter lain) --}}
    <div class="flex mt-6 border-[2px] border-[#F1A9B1] rounded-[15px] overflow-hidden w-fit">
        @foreach(['semua' => 'Semua', 'hariini' => 'Hari ini', 'bulanan' => 'Bulanan', 'tahunan' => 'Tahunan'] as $val => $label)
        <button type="button"
                onclick="submitFilter('{{ $val }}')"
                class="px-15 py-3 text-[16px] font-semibold transition-all duration-200
                {{ $val !== 'semua' ? 'border-l border-[#F1A9B1]' : '' }}
                {{ $filter == $val ? 'bg-[#F1A9B1] text-white' : 'bg-white text-[#3B302D]' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

   {{-- SEARCH + FILTER BUTTON — 100% Konsisten --}}
<div class="flex gap-4 mt-6 items-stretch">

    <form method="GET" action="{{ url()->current() }}" class="flex gap-4 w-full" id="historyForm">

        {{-- HIDDEN INPUTS --}}
        <input type="hidden" name="filter"         id="inputFilter"       value="{{ $filter }}">
        <input type="hidden" name="jenis_layanan"  id="inputJenisLayanan" value="{{ $jenisLayananId }}">
        <input type="hidden" name="tanggal"        id="inputTanggal"      value="{{ $tanggal }}">

        {{-- SEARCH BAR — Height eksplisit + font sama --}}
        <div class="relative flex items-center flex-1 h-[56px] border border-[#E9B9C0] rounded-2xl bg-white px-7">
            
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#3B302D] flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m21 21-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>

            <input type="text"
                   name="search"
                   id="inputSearch"
                   value="{{ request('search') }}"
                   placeholder="Cari nama klien, layanan, atau nomor pesanan..."
                   class="w-full h-full bg-transparent outline-none text-[16px] font-semibold text-[#3B302D] placeholder:text-[#C4B5B2] ml-4">

            @if(request('search'))
            <button type="button" id="btnClearSearch"
                    class="flex-shrink-0 ml-2 w-7 h-7 flex items-center justify-center rounded-full text-[#C4B5B2] hover:bg-[#fdeef0] hover:text-[#F1A9B1] transition">
                <span class="text-xl leading-none">×</span>
            </button>
            @endif
        </div>

        {{-- KALENDER BUTTON — Height & font sama persis --}}
        <div class="relative" id="calendarWrap">
            <button type="button" id="btnCalendar"
                    class="h-[56px] px-5 border border-[#E9B9C0] rounded-2xl bg-white flex items-center justify-center gap-2 
                    text-[16px] font-semibold transition whitespace-nowrap
                    {{ $tanggal ? 'border-[#F1A9B1] text-[#F1A9B1] bg-[#fff5f6]' : 'text-[#3B302D] hover:border-[#F1A9B1] hover:text-[#F1A9B1]' }}">
                
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                
                <span>Tanggal</span>
                
                @if($tanggal)
                    <span class="w-2 h-2 rounded-full bg-[#F1A9B1]"></span>
                @endif
            </button>

            {{-- POPOVER KALENDER --}}
            <div id="calendarPopover"
                 class="hidden absolute right-0 top-14 z-50 bg-white border border-[#F1A9B1] rounded-2xl shadow-lg p-4 w-[280px]">
                <div class="flex items-center justify-between mb-3">
                    <button type="button" id="prevMonth" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-[#fdeef0] text-[#3B302D]">‹</button>
                    <span id="calMonthLabel" class="text-[14px] font-semibold text-[#3B302D]"></span>
                    <button type="button" id="nextMonth" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-[#fdeef0] text-[#3B302D]">›</button>
                </div>
                <div class="grid grid-cols-7 mb-1">
                    @foreach(['M','S','S','R','K','J','S'] as $h)
                    <div class="text-center text-[11px] font-semibold text-[#C4B5B2]">{{ $h }}</div>
                    @endforeach
                </div>
                <div id="calDays" class="grid grid-cols-7 gap-y-1"></div>
                @if($tanggal)
                <button type="button" id="btnResetTanggal" class="mt-3 w-full text-center text-[12px] text-[#F1A9B1] hover:underline">Hapus filter tanggal</button>
                @endif
            </div>
        </div>

        {{-- FILTER BUTTON — Height & font sama persis --}}
        <div class="relative" id="filterWrap">
            <button type="button" id="btnFilter"
                    class="h-[56px] px-5 border border-[#E9B9C0] rounded-2xl bg-white flex items-center justify-center gap-2 
                    text-[16px] font-semibold transition whitespace-nowrap
                    {{ $jenisLayananId ? 'border-[#F1A9B1] text-[#F1A9B1]' : 'text-[#3B302D] hover:border-[#F1A9B1] hover:text-[#F1A9B1]' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>

                <span>Filter</span>
                
                @if($jenisLayananId)
                    <span class="w-2 h-2 rounded-full bg-[#F1A9B1]"></span>
                @endif
            </button>

            {{-- POPOVER JENIS LAYANAN — Tetap lebar 300px, tapi teks bisa wrap --}}
<div id="filterPopover"
     class="hidden absolute right-0 top-14 z-50 bg-white border border-[#F1A9B1] rounded-2xl shadow-lg py-2 w-[300px]">
    
    <p class="text-[12px] font-semibold text-[#C4B5B2] uppercase tracking-wide px-4 pt-1 pb-2">Jenis Layanan</p>
    
    @foreach($jenisLayananList as $jenis)
    <button type="button"
            data-jenis="{{ $jenis->jenis_layanan_id }}"
            data-label="{{ $jenis->nama_jenis }}"
            class="jenis-option w-full text-left px-4 py-2 text-[14px] text-[#3B302D] hover:bg-[#fdeef0] transition whitespace-normal break-words
            {{ $jenisLayananId == $jenis->jenis_layanan_id ? 'font-semibold text-[#F1A9B1]' : '' }}">
        {{ $jenis->nama_jenis }}
    </button>
    @endforeach
    
</div>
        </div>

    </form>

</div>

    {{-- ACTIVE FILTER BADGES — dengan X close per badge --}}
@if($tanggal || $jenisLayananId)
<div class="flex flex-wrap gap-2 mt-3">

    {{-- Badge Jenis Layanan + X close --}}
    @if($jenisLayananId)
    @php $selectedJenis = $jenisLayananList->firstWhere('jenis_layanan_id', $jenisLayananId); @endphp
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#fdeef0] border border-[#F1A9B1] text-[#3B302D] text-[12px]">
        <svg class="w-3 h-3 text-[#F1A9B1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        {{ $selectedJenis?->nama_jenis ?? '-' }}
        {{-- X close untuk jenis_layanan --}}
        <button type="button" onclick="clearJenis()" 
                class="ml-0.5 w-4 h-4 flex items-center justify-center rounded-full hover:bg-[#F1A9B1] hover:text-white transition text-[#C4B5B2]">
            ×
        </button>
    </span>
    @endif

    {{-- Badge Tanggal + X close --}}
    @if($tanggal)
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#fdeef0] border border-[#F1A9B1] text-[#3B302D] text-[12px]">
        <svg class="w-3 h-3 text-[#F1A9B1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
        {{-- X close untuk tanggal --}}
        <button type="button" onclick="clearTanggal()" 
                class="ml-0.5 w-4 h-4 flex items-center justify-center rounded-full hover:bg-[#F1A9B1] hover:text-white transition text-[#C4B5B2]">
            ×
        </button>
    </span>
    @endif

    {{-- Reset filter utama — hapus keduanya --}}
    <button type="button" onclick="resetFilter()"
            class="inline-flex items-center gap-1 text-[12px] text-[#C4B5B2] hover:text-[#F1A9B1] transition">
        × Reset filter
    </button>

</div>
@endif

    {{-- TITLE FILTER --}}
    <div class="mt-8">
        <h2 class="text-[20px] font-bold text-[#3B302D]">
            {{ $judulFilter }}
        </h2>
    </div>

    {{-- HISTORY --}}
    @forelse($history as $tanggalGroup => $bookings)

    <div class="mt-8 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-7">

        <h3 class="text-[18px] text-[#3B302D] font-bold">
            {{ $tanggalGroup == now()->translatedFormat('d F Y') ? 'Hari ini, ' . $tanggalGroup : $tanggalGroup }}
        </h3>

        <div class="mt-6 space-y-5">

            @foreach($bookings as $booking)

            @php
                $booking_id = $booking->booking_id;
                $pelanggan = $booking->pelanggan->user->nama ?? '-';
                $foto      = $booking->pelanggan->user->foto ?? null;

                $layanan = $booking->details
                    ->map(fn($d) => $d->layanan_cabang_id
    ? optional(optional($d->layananCabang)->layanan)->nama_layanan
    : $d->paketCabang?->paketLayanan?->nama_paket
)
                    ->filter()->unique();

                $jamMulai = Carbon\Carbon::parse($booking->jam_booking)->format('H.i');

                $durasi = $booking->details->sum(function($d) {
    if ($d->layanan_cabang_id) {
        return optional(optional($d->layananCabang)->layanan)->durasi ?? 0;
    } else {
        return $d->paketCabang?->details->sum(fn($pd) => $pd->layanan?->durasi ?? 0) ?? 0;
    }
});

                $jamSelesai = Carbon\Carbon::parse($booking->jam_booking)->addMinutes($durasi)->format('H.i');
                $isCompleted = $booking->status === 'completed';
            @endphp

            <div class="bg-white border border-[#F1C9CF] rounded-[20px] px-6 py-5 flex items-center justify-between">

                {{-- LEFT --}}
                <div class="flex items-center gap-6">

                    @if($foto)
                        <img src="{{ asset('storage/' . $foto) }}" class="w-18 h-18 rounded-full object-cover">
                    @else
                        <div class="w-18 h-18 rounded-full bg-[#F1C9CF] flex items-center justify-center text-[22px] font-bold text-[#EB2D55]">
                            {{ strtoupper(substr($pelanggan, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <p class="text-[#E8B1B6] text-sm font-bold mb-2">
    No Pesanan : #{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }}
</p>
                        <h2 class="text-[17px] font-semibold text-[#3B302D] leading-none">{{ $pelanggan }}</h2>
                        <p class="text-[14px] text-[#3B302D] mt-2 font-normal">
                            {{ $jamMulai }} - {{ $jamSelesai }} ({{ $durasi }} menit)
                        </p>
                        <div class="flex items-center gap-4 text-[14px] mt-2 flex-wrap">
                            @foreach($layanan as $nama)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-[#FF1F57]"></div>
                                <span class="text-[#3B302D] font-normal">{{ $nama }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-10">
                    @if($isCompleted)
                        <div class="px-8 py-2 rounded-full bg-[#D8F5CE] text-[#3B302D] text-[16px] font-semibold">Selesai</div>
                    @else
                        <div class="px-8 py-2 rounded-full bg-[#FFE5E5] text-[#E53E3E] text-[16px] font-semibold">Dibatalkan</div>
                    @endif
                </div>

            </div>

            @endforeach

        </div>

    </div>

    @empty

    <div class="mt-8 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-16 text-center">
        <p class="text-[16px] text-[#9B8B87]">
            @if(request('search') || $jenisLayananId || $tanggal)
                Tidak ada riwayat yang cocok.
            @else
                Belum ada riwayat booking.
            @endif
        </p>
    </div>

    @endforelse

    {{-- SUMMARY --}}
    <div class="mt-8 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-7">

        <h2 class="text-[18px] font-bold text-[#3B302D]">
            Ringkasan {{ $judulFilter }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-5">

    {{-- Total Layanan --}}
    <div class="border border-[#F1C9CF] rounded-[24px] py-6 px-6 bg-white">
        <p class="text-[15px] text-[#3B302D] font-normal mb-3">
            Total Layanan
        </p>
        <h1 class="text-[28px] font-bold text-[#3B302D] leading-tight">
            {{ $totalSesi }}
        </h1>
        <span class="text-[14px] text-[#9B8B87] font-normal block mt-1">
            Sesi
        </span>
    </div>

    {{-- Total Paket --}}
<div class="border border-[#F1C9CF] rounded-[24px] py-6 px-6 bg-white">
    <p class="text-[15px] text-[#3B302D] font-normal mb-3">
        Total Paket
    </p>
    <h1 class="text-[28px] font-bold text-[#3B302D] leading-tight">
        {{ $totalPaket }}
    </h1>
    <span class="text-[14px] text-[#9B8B87] font-normal block mt-1">
        Paket
    </span>
</div>

    {{-- Total Durasi --}}
    <div class="border border-[#F1C9CF] rounded-[24px] py-6 px-6 bg-white">
        <p class="text-[15px] text-[#3B302D] font-normal mb-3">
            Total Durasi
        </p>
        <h1 class="text-[28px] font-bold text-[#3B302D] leading-tight">
            {{ $totalDurasi }}
        </h1>
        <span class="text-[14px] text-[#9B8B87] font-normal block mt-1">
            Menit
        </span>
    </div>

    {{-- Klien Dilayani --}}
    <div class="border border-[#F1C9CF] rounded-[24px] py-6 px-6 bg-white">
        <p class="text-[15px] text-[#3B302D] font-normal mb-3">
            Klien Dilayani
        </p>
        <h1 class="text-[28px] font-bold text-[#3B302D] leading-tight">
            {{ $totalKlien }}
        </h1>
        <span class="text-[14px] text-[#9B8B87] font-normal block mt-1">
            Orang
        </span>
    </div>

</div>

    </div>

</div>

{{-- ===================== JAVASCRIPT ===================== --}}
<script>
(function () {

        // ← TAMBAHAN: Clear hanya search
    window.clearSearch = function() {
        const inputSearch = document.getElementById('inputSearch');
        if (inputSearch) {
            inputSearch.value = '';
            historyForm.submit();
        }
    };

    // ← TAMBAHAN: Event listener untuk X button (jika ada)
    const btnClearSearch = document.getElementById('btnClearSearch');
    if (btnClearSearch) {
        btnClearSearch.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearSearch();
        });
    }

    const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];

    let calYear, calMonth;
    const initTanggal = document.getElementById('inputTanggal').value;

    if (initTanggal) {
        const d = new Date(initTanggal);
        calYear  = d.getFullYear();
        calMonth = d.getMonth();
    } else {
        const now = new Date();
        calYear  = now.getFullYear();
        calMonth = now.getMonth();
    }

    /* ── ELEMENTS ───────────────────────────────────── */
    const btnFilter       = document.getElementById('btnFilter');
    const filterPopover   = document.getElementById('filterPopover');
    const filterWrap      = document.getElementById('filterWrap');

    const btnCalendar     = document.getElementById('btnCalendar');
    const calendarPopover = document.getElementById('calendarPopover');
    const calendarWrap    = document.getElementById('calendarWrap');

    const calMonthLabel   = document.getElementById('calMonthLabel');
    const calDays         = document.getElementById('calDays');
    const prevMonthBtn    = document.getElementById('prevMonth');
    const nextMonthBtn    = document.getElementById('nextMonth');
    const btnResetTanggal = document.getElementById('btnResetTanggal');

    const inputFilter     = document.getElementById('inputFilter');
    const inputJenis      = document.getElementById('inputJenisLayanan');
    const inputTanggal    = document.getElementById('inputTanggal'); // ← Single input
    const historyForm     = document.getElementById('historyForm');

    /* ── SUBMIT HELPERS ───────────────────────────── */

    // Dipanggil tombol tab filter
    window.submitFilter = function(val) {
        inputFilter.value = val;
        historyForm.submit();
    };

        // Reset hanya tanggal & jenis layanan, pertahankan search & filter tab
    window.resetFilter = function() {
        inputJenis.value   = '';
        inputTanggal.value = '';
        historyForm.submit();
    };

    // ← TAMBAHAN: Clear hanya jenis_layanan
    window.clearJenis = function() {
        inputJenis.value = '';
        historyForm.submit();
    };

    // ← TAMBAHAN: Clear hanya tanggal
    window.clearTanggal = function() {
        inputTanggal.value = '';
        historyForm.submit();
    };

    // Reset hanya tanggal & jenis layanan, pertahankan search & filter tab
    window.resetFilter = function() {
        inputJenis.value   = '';
        inputTanggal.value = '';  // ← Hanya clear single tanggal
        historyForm.submit();
    };

       /* ── POPOVER TOGGLE ───────────────────────────── */
    function closeAll() {
        filterPopover.classList.add('hidden');
        calendarPopover.classList.add('hidden');
    }

    // Filter popover (jenis layanan)
    btnFilter.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = !filterPopover.classList.contains('hidden');
        closeAll();
        if (!isOpen) filterPopover.classList.remove('hidden');
    });

    // Calendar popover — sekarang button terpisah
    btnCalendar.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = !calendarPopover.classList.contains('hidden');
        closeAll();
        if (!isOpen) {
            renderCalendar();
            calendarPopover.classList.remove('hidden');
        }
    });

    // Close popover saat klik di luar
    document.addEventListener('click', function(e) {
        // Cek apakah klik di luar kedua popover
        const clickedInsideFilter = filterWrap.contains(e.target);
        const clickedInsideCalendar = calendarWrap.contains(e.target);
        
        if (!clickedInsideFilter && !clickedInsideCalendar) {
            closeAll();
        }
    });

    /* ── JENIS LAYANAN ────────────────────────────── */
    document.querySelectorAll('.jenis-option').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Toggle: klik yang sudah aktif → clear
            inputJenis.value = (inputJenis.value == this.dataset.jenis) ? '' : this.dataset.jenis;
            closeAll();
            historyForm.submit();
        });
    });

    /* ── KALENDER ─────────────────────────────────── */
    function renderCalendar() {
        calMonthLabel.textContent = MONTHS_ID[calMonth] + ' ' + calYear;
        calDays.innerHTML = '';

        const firstDay  = new Date(calYear, calMonth, 1).getDay();
        const daysInMon = new Date(calYear, calMonth + 1, 0).getDate();
        const selected  = inputTanggal.value; // ← Single selected
        const todayStr  = toYMD(new Date());

        for (let i = 0; i < firstDay; i++) {
            calDays.appendChild(document.createElement('div'));
        }

        for (let d = 1; d <= daysInMon; d++) {
            const dateStr = calYear + '-' + pad(calMonth + 1) + '-' + pad(d);
            const cell    = document.createElement('button');
            cell.type     = 'button';
            cell.textContent = d;

            let cls = 'w-full aspect-square flex items-center justify-center rounded-full text-[13px] transition hover:bg-[#fdeef0] ';

            if (dateStr === selected) {
                cls += 'bg-[#F1A9B1] text-white font-semibold hover:bg-[#e8919b] ';
            } else if (dateStr === todayStr) {
                cls += 'border border-[#F1A9B1] text-[#F1A9B1] font-semibold ';
            } else {
                cls += 'text-[#3B302D] ';
            }

            cell.className = cls;
            cell.addEventListener('click', function() {
                inputTanggal.value = dateStr;  // ← Set single tanggal
                closeAll();
                historyForm.submit();
            });

            calDays.appendChild(cell);
        }
    }

    function pad(n) { return n < 10 ? '0' + n : '' + n; }
    function toYMD(d) { return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate()); }

    prevMonthBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        calMonth--;
        if (calMonth < 0) { calMonth = 11; calYear--; }
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        calMonth++;
        if (calMonth > 11) { calMonth = 0; calYear++; }
        renderCalendar();
    });

    if (btnResetTanggal) {
        btnResetTanggal.addEventListener('click', function() {
            inputTanggal.value = '';  // ← Clear single tanggal
            closeAll();
            historyForm.submit();
        });
    }

})();
</script>

@endsection