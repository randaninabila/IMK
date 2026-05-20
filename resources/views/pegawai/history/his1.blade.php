@extends('pegawai.app')

@section('content')

@php
    $filter = request('filter', 'semua');

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

    {{-- FILTER --}}
    <div class="flex mt-6 border-[2px] border-[#F1A9B1] rounded-[15px] overflow-hidden w-fit">

        <a href="{{ url()->current() }}?filter=semua"
           class="px-15 py-3 text-[16px] font-semibold transition-all duration-200
           {{ $filter == 'semua'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Semua
        </a>

        <a href="{{ url()->current() }}?filter=hariini"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'hariini'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Hari ini
        </a>

        <a href="{{ url()->current() }}?filter=bulanan"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'bulanan'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Bulanan
        </a>

        <a href="{{ url()->current() }}?filter=tahunan"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'tahunan'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Tahunan
        </a>

    </div>

    {{-- SEARCH --}}
    <div class="flex gap-4 mt-6">

        <form method="GET" action="{{ url()->current() }}" class="flex gap-4 w-full">

            <input type="hidden" name="filter" value="{{ $filter }}">

            <div class="flex items-center gap-4 flex-1 border border-[#E9B9C0] rounded-2xl px-6 py-3 bg-white">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="2"
                     stroke="currentColor"
                     class="w-6 h-8 text-[#3B302D]">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m21 21-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>

                </svg>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama klien atau layanan..."
                       class="w-full outline-none text-[15px] text-[#3B302D] placeholder:text-[#3B302D] bg-transparent font-normal">

            </div>

            <button type="submit"
                    class="w-[170px] py-3 border border-[#E9B9C0] rounded-2xl bg-white flex items-center justify-center gap-3 text-[18px] text-[#3B302D] font-semibold">

                Filter

            </button>

        </form>

    </div>

    {{-- TITLE FILTER --}}
    <div class="mt-8">

        <h2 class="text-[20px] font-bold text-[#3B302D]">
            {{ $judulFilter }}
        </h2>

    </div>

    {{-- HISTORY --}}
    @forelse($history as $tanggal => $bookings)

    <div class="mt-8 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-7">

        <h3 class="text-[18px] text-[#3B302D] font-bold">

            {{ $tanggal == now()->translatedFormat('d F Y')
                ? 'Hari ini, ' . $tanggal
                : $tanggal }}

        </h3>

        <div class="mt-6 space-y-5">

            @foreach($bookings as $booking)

            @php
                $pelanggan = $booking->pelanggan->user->name ?? '-';

                $foto = $booking->pelanggan->user->foto ?? null;

                $layanan = $booking->details
                    ->map(fn($d) =>
                        optional(optional($d->layananCabang)->layanan)->nama_layanan
                    )
                    ->filter()
                    ->unique();

                $jamMulai = Carbon\Carbon::parse($booking->jam_booking)
                    ->format('H.i');

                $durasi = $booking->details
                    ->sum(fn($d) =>
                        optional(optional($d->layananCabang)->layanan)->durasi ?? 0
                    );

                $jamSelesai = Carbon\Carbon::parse($booking->jam_booking)
                    ->addMinutes($durasi)
                    ->format('H.i');

                $isCompleted = $booking->status === 'completed';
            @endphp

            <div class="bg-white border border-[#F1C9CF] rounded-[20px] px-6 py-5 flex items-center justify-between">

                {{-- LEFT --}}
                <div class="flex items-center gap-6">

                    @if($foto)

                        <img src="{{ asset('storage/' . $foto) }}"
                             class="w-18 h-18 rounded-full object-cover">

                    @else

                        <div class="w-18 h-18 rounded-full bg-[#F1C9CF] flex items-center justify-center text-[22px] font-bold text-[#EB2D55]">

                            {{ strtoupper(substr($pelanggan, 0, 1)) }}

                        </div>

                    @endif

                    <div>

                        <h2 class="text-[17px] font-semibold text-[#3B302D] leading-none">
                            {{ $pelanggan }}
                        </h2>

                        <p class="text-[14px] text-[#3B302D] mt-2 font-normal">
                            {{ $jamMulai }} - {{ $jamSelesai }} ({{ $durasi }} menit)
                        </p>

                        {{-- LAYANAN --}}
                        <div class="flex items-center gap-4 text-[14px] mt-2 flex-wrap">

                            @foreach($layanan as $nama)

                            <div class="flex items-center gap-2">

                                <div class="w-2 h-2 rounded-full bg-[#FF1F57]"></div>

                                <span class="text-[#3B302D] font-normal">
                                    {{ $nama }}
                                </span>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-10">

                    @if($isCompleted)

                        <div class="px-8 py-2 rounded-full bg-[#D8F5CE] text-[#3B302D] text-[16px] font-semibold">
                            Selesai
                        </div>

                    @else

                        <div class="px-8 py-2 rounded-full bg-[#FFE5E5] text-[#E53E3E] text-[16px] font-semibold">
                            Dibatalkan
                        </div>

                    @endif

                </div>

            </div>

            @endforeach

        </div>

    </div>

    @empty

    <div class="mt-8 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-16 text-center">

        <p class="text-[16px] text-[#9B8B87]">
            Belum ada riwayat booking.
        </p>

    </div>

    @endforelse

    {{-- SUMMARY --}}
    <div class="mt-8 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-7">

        <h2 class="text-[18px] font-bold text-[#3B302D']">
            Ringkasan {{ $judulFilter }}
        </h2>

        <div class="grid grid-cols-4 gap-6 mt-5">

            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">
                <p class="text-[15px] text-[#3B302D] font-normal">Total Layanan</p>
                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">{{ $totalSesi }}</h1>
                <span class="text-[15px] text-[#3B302D] font-normal">Sesi</span>
            </div>

            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">
                <p class="text-[15px] text-[#3B302D] font-normal">Total Durasi</p>
                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">{{ $totalDurasi }}</h1>
                <span class="text-[15px] text-[#3B302D] font-normal">Menit</span>
            </div>

            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">
                <p class="text-[15px] text-[#3B302D] font-normal">Klien Dilayani</p>
                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">{{ $totalKlien }}</h1>
                <span class="text-[15px] text-[#3B302D] font-normal">Orang</span>
            </div>

            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">
                <p class="text-[15px] text-[#3B302D] font-normal">Pendapatan</p>
                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h1>
                <span class="text-[15px] text-[#3B302D] font-normal">Estimasi</span>
            </div>

        </div>

    </div>

</div>

@endsection