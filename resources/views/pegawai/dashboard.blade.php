@extends('pegawai.app')

@section('content')

<div class="flex gap-8 pt-4 font-sans">

    {{-- LEFT SIDE --}}
    <div class="flex-1">

        {{-- HEADER --}}
        <div>
            <h1 class="text-[26px] font-bold text-[#3E382D] leading-none">
                Selamat Datang, Specialist !
            </h1>

            <div class="mt-2 text-[#5B4D4D] text-[16px] flex gap-4 flex-wrap">
                <span>Total Booking hari ini : {{ $totalBooking }} Booking</span>
                <span>|</span>
                <span>{{ $totalSelesai }} Selesai</span>
                <span>|</span>
                <span>{{ $totalBerjalan }} Berjalan</span>
                <span>|</span>
                <span>{{ $totalMenunggu }} Menunggu</span>
            </div>

            <p class="text-[#5B4D4D] text-[16px]">
                Slot kosong berikutnya: 09:40-10:30
            </p>
        </div>

        {{-- CALENDAR --}}
        <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[40px] p-8 mt-6 shadow-sm max-w-[760px]">

            {{-- HEADER CALENDAR --}}
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-[24px] font-bold text-[#3B302D]">
                    {{ $bulanLabel }} {{ $tahunKalender }}
                </h2>

                <div class="flex items-center gap-4 text-4xl text-[#3B302D]">
                    <a href="{{ route('pegawai.dashboard', ['bulan' => $bulanSebelumnya, 'tahun' => $tahunSebelumnya]) }}">‹</a>
                    <a href="{{ route('pegawai.dashboard', ['bulan' => $bulanBerikutnya, 'tahun' => $tahunBerikutnya]) }}">›</a>
                </div>
            </div>

            {{-- DAY --}}
            <div class="grid grid-cols-7 text-center text-[#A56A6A] font-medium mb-6 text-[17px]">
                <div>MO</div>
                <div>TU</div>
                <div>WE</div>
                <div>TH</div>
                <div>FR</div>
                <div>SA</div>
                <div>SU</div>
            </div>

            {{-- DATE --}}
            <div id="calendar" class="grid grid-cols-7 gap-y-5">

                @foreach ($kalender as $item)
                    <div class="flex items-center justify-center">

                        @if ($item['muted'])
                            <div class="w-[54px] h-[54px] rounded-[14px] flex flex-col items-center justify-center text-[#E7D4D4]">
                                <span class="text-[17px] font-medium">{{ $item['date'] }}</span>
                                <div class="w-1.5 h-1.5 mt-1"></div>
                            </div>
                        @else
                            <a href="{{ route('pegawai.jadwal-kerja', ['tanggal' => $item['full_date']]) }}"
                               class="calendar-date w-[54px] h-[54px] rounded-[14px]
                                      flex flex-col items-center justify-center
                                      transition-all duration-200 text-[#3B302D]
                                      {{ $item['full_date'] === now()->toDateString() ? 'bg-[#FF6678] text-white shadow-md scale-105' : '' }}
                                      hover:bg-[#FF6678] hover:text-white hover:shadow-md hover:scale-105">

                                <span class="text-[17px] font-medium">{{ $item['date'] }}</span>

                                {{-- Titik jika ada jadwal --}}
                                <div class="w-1.5 h-1.5 rounded-full mt-1
                                    {{ $item['has_jadwal'] ? 'bg-[#FF6678]' : 'invisible' }}
                                    {{ $item['full_date'] === now()->toDateString() ? 'bg-white' : '' }}">
                                </div>

                            </a>
                        @endif

                    </div>
                @endforeach

            </div>

        </div>

        {{-- NOTIFICATION --}}
        <div class="max-w-[760px] mt-10">

            <h3 class="text-[#3B302D] text-[24px] font-bold mb-4">
                Notifikasi
            </h3>

            <div class="space-y-4">

    @forelse($notifikasi as $notif)

    <div class="bg-[#F5A6AF] text-white rounded-[22px] px-7 py-4 flex items-center gap-5 text-[17px] font-semibold shadow-sm">

        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>

        <div>
            <p>{{ $notif->pesan }}</p>

            <!-- @if($notif->pesan)
                <p class="text-[14px] font-normal text-white/90 mt-1">
                    {{ $notif->pesan }}
                </p>
            @endif -->
        </div>

    </div>

    @empty

    <div class="bg-white border border-[#F1A9B1] rounded-[22px] px-7 py-6 text-center text-[#B7A4A4] text-[15px]">
        Belum ada notifikasi.
    </div>

    @endforelse

</div>

        </div>

    </div>

    {{-- RIGHT SIDE --}}
<div class="w-[360px] pt-17">

    {{-- ONGOING --}}
    <div>

        <h3 class="text-[#3E382D] text-[18px] font-bold mb-3">
            Ongoing
        </h3>

        @if ($ongoing)
        @php
            $jamMulai   = \Carbon\Carbon::parse($ongoing->jam_booking);
            $totalDurasi = $ongoing->details->sum(
                fn($d) => $d->layananCabang?->layanan?->durasi ?? 0
            );
            $jamSelesai = $jamMulai->copy()->addMinutes($totalDurasi);
        @endphp

        <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[34px] p-5 shadow-sm">

            <div class="flex gap-5">

                {{-- DATE BUBBLE --}}
                <div class="w-16 h-16 rounded-full bg-[#F3B5B5] flex flex-col items-center justify-center shrink-0">
                    <span class="text-[20px] font-semibold text-[#3B302D] leading-none">
                        {{ \Carbon\Carbon::parse($ongoing->tanggal_booking)->format('d') }}
                    </span>
                    <span class="text-[12px] text-[#3B302D]">
                        {{ \Carbon\Carbon::parse($ongoing->tanggal_booking)->locale('id')->translatedFormat('M') }}
                    </span>
                </div>

                <div>
                    <h2 class="text-[17px] font-bold text-[#934A4A] leading-none">
                        {{ $jamMulai->format('H:i') }} – {{ $jamSelesai->format('H:i') }}
                    </h2>

                    {{-- Layanan pertama --}}
                    <p class="text-[14px] text-[#B56B6B]">
                        {{ $ongoing->details->first()?->layananCabang?->layanan?->nama_layanan ?? '-' }}
                    </p>

                    <p class="text-[14px] text-[#934A4A] mt-2 font-medium">
                        {{ $ongoing->pelanggan?->user?->nama ?? '-' }}
                    </p>
                </div>

            </div>

            <div class="space-y-2 mt-6">
                <button class="w-full bg-[#F5A6AF] text-white rounded-2xl py-2.5 text-[16px] font-medium hover:opacity-90 transition">
                    Start Service
                </button>
                <button class="w-full border border-[#E9E1E1] rounded-2xl py-2.5 text-[#B7B1B1] text-[16px]">
                    Mark as Done
                </button>
                <a href="{{ route('pegawai.booking') }}"
                   class="block w-full border border-[#E9E1E1] rounded-2xl py-2.5 text-[#A05B5B] text-[16px] text-center">
                    View Detail
                </a>
            </div>

        </div>

        @else

        <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[34px] p-5 shadow-sm text-center text-[#C4AAAA] text-[15px] py-8">
            Tidak ada booking berjalan.
        </div>

        @endif

    </div>

    {{-- UPCOMING --}}
    <div class="mt-8">

        <h3 class="text-[#3E382D] text-[18px] font-bold mb-3">
            Upcoming Events
        </h3>

        <div class="space-y-5">

            @forelse ($upcoming as $booking)
            @php
                $jamMulaiUp   = \Carbon\Carbon::parse($booking->jam_booking);
                $durasiUp     = $booking->details->sum(
                    fn($d) => $d->layananCabang?->layanan?->durasi ?? 0
                );
                $jamSelesaiUp = $jamMulaiUp->copy()->addMinutes($durasiUp);
            @endphp

            <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[30px] p-4 shadow-md flex gap-5">

                {{-- DATE BUBBLE --}}
                <div class="w-20 h-20 rounded-full bg-[#F4C3C3] flex flex-col items-center justify-center shrink-0">
                    <span class="text-[26px] font-semibold leading-none">
                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d') }}
                    </span>
                    <span class="text-[15px]">
                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('M') }}
                    </span>
                </div>

                <div>
                    <h3 class="text-[20px] leading-none font-bold text-[#3B302D]">
                        {{ $jamMulaiUp->format('H:i') }} – {{ $jamSelesaiUp->format('H:i') }}
                    </h3>

                    <p class="text-[#B56B6B] text-[15px]">
                        {{ $booking->details->first()?->layananCabang?->layanan?->nama_layanan ?? '-' }}
                    </p>

                    <p class="text-[#3B302D] text-[16px] mt-2 font-medium">
                        {{ $booking->pelanggan?->user?->nama ?? '-' }}
                    </p>
                </div>

            </div>

            @empty

            <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[30px] p-6 text-center text-[#C4AAAA] text-[15px]">
                Tidak ada upcoming booking hari ini.
            </div>

            @endforelse

        </div>

    </div>

</div>

</div>

@endsection