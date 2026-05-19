@extends('pegawai.app')

@section('content')

@php
    $filter = request('filter', 'harian');
    $namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $namaHariPendek = ['Min','Sen','Sel','Rab','Kam','Sab','Min'];
@endphp

<div class="w-full px-4 py-4 font-sans">

    {{-- TITLE --}}
    <div>
        <h1 class="text-[26px] font-bold text-[#3E382D] leading-none">
            Jadwal Kerja
        </h1>
        <p class="mt-2 text-[16px] text-[#4F4545]">
            Lihat dan kelola informasi jadwal kerja Anda
        </p>
    </div>

    {{-- FILTER TABS --}}
    <div class="flex mt-6 border-[2px] border-[#F1A9B1] rounded-[15px] overflow-hidden w-fit">

        @php
            $tabs = [
                'harian'   => 'Hari ini',
                'mingguan' => 'Mingguan',
                'bulanan'  => 'Bulanan',
            ];
        @endphp

        @foreach ($tabs as $key => $label)
            @php
                $params = array_merge(request()->except('filter'), ['filter' => $key]);
                // Reset tanggal-spesifik saat ganti tab
                if ($key === 'bulanan') {
                    unset($params['tanggal']);
                }
            @endphp
            <a href="{{ url()->current() }}?{{ http_build_query($params) }}"
               class="px-10 py-3 text-[16px] font-semibold transition-all duration-200
                      {{ $loop->first ? '' : 'border-l border-[#F1A9B1]' }}
                      {{ $filter === $key ? 'bg-[#F1A9B1] text-white' : 'bg-white text-[#3B302D]' }}">
                {{ $label }}
            </a>
        @endforeach

    </div>

    {{-- ==========================
         HARIAN
    ========================== --}}
    @if ($filter === 'harian')

    <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] p-6">

        {{-- NAVIGASI TANGGAL --}}
        <div class="flex items-center justify-between text-center">

            <a href="{{ url()->current() }}?filter=harian&tanggal={{ $tanggalSebelum }}"
               class="text-[#3B302D] hover:scale-110 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>

            <div>
                <p class="text-[#A14F42] text-[17px] font-semibold leading-none">
                    {{ $carbon->locale('id')->translatedFormat('l') }}
                </p>
                <h2 class="text-[24px] font-bold text-[#3B302D] mt-1.5 leading-none">
                    {{ $carbon->locale('id')->translatedFormat('j F Y') }}
                </h2>
            </div>

            <a href="{{ url()->current() }}?filter=harian&tanggal={{ $tanggalBerikut }}"
               class="text-[#3B302D] hover:scale-110 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5L15.75 12l-7.5 7.5"/>
                </svg>
            </a>

        </div>

       {{-- JADWAL --}}
<div class="mt-8 space-y-3">

    @forelse ($jadwals as $booking)

        @php
            $namaPelanggan = $booking->pelanggan->user->nama ?? '-';

            // ambil layanan pertama (kalau multi service)
            $layanan = $booking->bookingDetails?->first()?->layananCabang?->layanan?->nama_layanan ?? '-' 
        @endphp

        <div class="mb-4">

            {{-- Nama Layanan --}}
            <h3 class="text-[17px] font-semibold text-[#3E382D]">
                {{ $layanan }}
            </h3>

            {{-- Jam + Nama Customer --}}
            <p class="text-[14px] text-[#4F4545]">
                {{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }}
                –
                {{ \Carbon\Carbon::parse($booking->jam_booking)->addHour()->format('H:i') }}
                |
                {{ $namaPelanggan }}
            </p>

        </div>

    @empty

        <div class="text-center py-10 text-[#C4AAAA] text-[17px]">
            Belum ada jadwal booking online untuk hari ini
        </div>

    @endforelse

</div>

        </div>

    </div>

    @endif

    {{-- ==========================
         MINGGUAN
    ========================== --}}
    @if ($filter === 'mingguan')

    <div class="mt-6 bg-white border-[2px] border-[#F1A9B1] rounded-[28px] p-6">

        {{-- DAFTAR HARI --}}
        <div class="flex items-center gap-3 flex-wrap">

            @foreach ($hariList as $hari)
            @php
                $isAktif = $hari->toDateString() === $carbonAktif->toDateString();
            @endphp
            <a href="{{ url()->current() }}?filter=mingguan&tanggal={{ $hari->toDateString() }}"
               class="px-5 py-2 rounded-[10px] min-w-[90px] flex flex-col items-center justify-center
                      text-center gap-1 transition duration-300
                      {{ $isAktif
                            ? 'bg-[#F5A6AF] shadow-sm'
                            : 'border-[2px] border-[#F1A9B1] hover:bg-[#FFF5F6]' }}">
                <h3 class="text-[17px] font-semibold leading-none
                           {{ $isAktif ? 'text-white' : 'text-[#3B302D]' }}">
                    {{ $hari->locale('id')->translatedFormat('D') }}
                </h3>
                <p class="text-[14px] leading-none
                          {{ $isAktif ? 'text-white' : 'text-[#3B302D]' }}">
                    {{ $hari->format('d M') }}
                </p>
            </a>
            @endforeach

        </div>

        {{-- JADWAL HARI AKTIF --}}
        <div class="mt-8 space-y-6">

            @forelse ($jadwals as $booking)

    @php
        $namaPelanggan = $booking->pelanggan->user->nama ?? '-';
        $layanan = $booking->bookingDetails?->first()?->layananCabang?->layanan?->nama_layanan ?? '-';
    @endphp

    <div class="mb-4">

        <h3 class="text-[17px] font-semibold text-[#3E382D]">
            {{ $layanan }}
        </h3>

        <p class="text-[14px] text-[#4F4545]">
            {{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }}
            –
            {{ \Carbon\Carbon::parse($booking->jam_booking)->addHour()->format('H:i') }}
            |
            {{ $namaPelanggan }}
        </p>

    </div>

@empty

    <div class="text-center py-10 text-[#C4AAAA] text-[17px]">
        Belum ada jadwal booking online untuk hari ini
    </div>

@endforelse

        </div>

    </div>

    @endif

    {{-- ==========================
         BULANAN
    ========================== --}}
    @if ($filter === 'bulanan')

    <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-6">

        {{-- HEADER BULAN --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-[24px] font-bold text-[#3B302D]">
                {{ $bulanLabel }} {{ $tahunKalender }}
            </h2>
            <div class="flex items-center gap-4">
                <a href="{{ url()->current() }}?filter=bulanan&bulan={{ $bulanSebelumnya }}&tahun={{ $tahunSebelumnya }}"
                   class="hover:scale-110 transition text-[#3B302D]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                    </svg>
                </a>
                <a href="{{ url()->current() }}?filter=bulanan&bulan={{ $bulanBerikutnya }}&tahun={{ $tahunBerikutnya }}"
                   class="hover:scale-110 transition text-[#3B302D]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- NAMA HARI --}}
        <div class="grid grid-cols-7 mb-4 text-center font-semibold text-[16px] text-[#A56A6A]">
            <div>Senin</div>
            <div>Selasa</div>
            <div>Rabu</div>
            <div>Kamis</div>
            <div>Jumat</div>
            <div>Sabtu</div>
            <div>Minggu</div>
        </div>

        {{-- GRID KALENDER --}}
        <div class="grid grid-cols-7 gap-y-3 text-center auto-rows-[50px]">

            @foreach ($kalender as $item)
            <div class="flex flex-col items-center justify-center">

                @if ($item['muted'])
                    <span class="text-[17px] text-gray-300">{{ $item['date'] }}</span>

                @elseif ($item['is_active'] ?? false)
                    {{-- Tanggal aktif (dipilih) --}}
                    <a href="{{ url()->current() }}?filter=bulanan&bulan={{ $tahunKalender ? request('bulan', now()->month) : now()->month }}&tahun={{ $tahunKalender }}&tanggal={{ $item['full_date'] }}"
                       class="w-[46px] h-[46px] rounded-[10px] bg-[#FF6678] flex flex-col items-center justify-center shadow-md">
                        <span class="text-white text-[17px] font-bold">{{ $item['date'] }}</span>
                        @if ($item['has_jadwal'])
                        <div class="w-1.5 h-1.5 rounded-full bg-white mt-0.5"></div>
                        @endif
                    </a>

                @else
                    <a href="{{ url()->current() }}?filter=bulanan&bulan={{ request('bulan', now()->month) }}&tahun={{ $tahunKalender }}&tanggal={{ $item['full_date'] }}"
                       class="w-[46px] h-[46px] rounded-[10px] flex flex-col items-center justify-center
                              hover:bg-[#FFF0F1] transition
                              {{ ($item['is_today'] ?? false) ? 'border-2 border-[#FF6678]' : '' }}">
                        <span class="text-[17px] {{ ($item['is_today'] ?? false) ? 'font-bold text-[#FF6678]' : 'text-[#3B302D]' }}">
                            {{ $item['date'] }}
                        </span>
                        @if ($item['has_jadwal'])
                        <div class="w-1.5 h-1.5 rounded-full bg-[#F5A6AF] mt-0.5"></div>
                        @endif
                    </a>
                @endif

            </div>
            @endforeach

        </div>

    </div>

    @endif

</div>

@endsection