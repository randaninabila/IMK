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
                <span>Total Booking hari ini : 6 Booking</span>
                <span>|</span>
                <span>2 Selesai</span>
                <span>|</span>
                <span>1 Berjalan</span>
                <span>|</span>
                <span>3 Menunggu</span>
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
                    April 2026
                </h2>

                <div class="flex items-center gap-4 text-4xl text-[#3B302D]">
                    <button>‹</button>
                    <button>›</button>
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
            <div id="calendar"
                class="grid grid-cols-7 gap-y-5">

                @php
                    $dates = [
                        ['date' => 30, 'muted' => true],
                        ['date' => 31, 'muted' => true],
                        ['date' => 1, 'muted' => true],
                        ['date' => 2, 'muted' => true],
                        ['date' => 3, 'muted' => true],
                        ['date' => 4, 'muted' => true],
                        ['date' => 5, 'muted' => true],

                        ['date' => 6],
                        ['date' => 7],
                        ['date' => 8],
                        ['date' => 9],
                        ['date' => 10],
                        ['date' => 11],
                        ['date' => 12],

                        ['date' => 13],
                        ['date' => 14],
                        ['date' => 15],
                        ['date' => 16],
                        ['date' => 17],
                        ['date' => 18],
                        ['date' => 19],

                        ['date' => 20],
                        ['date' => 21],
                        ['date' => 22],
                        ['date' => 23],
                        ['date' => 24],
                        ['date' => 25],
                        ['date' => 26],

                        ['date' => 27],
                        ['date' => 28],
                        ['date' => 29],
                        ['date' => 30],
                        ['date' => 1, 'muted' => true],
                        ['date' => 2, 'muted' => true],
                    ];
                @endphp

                @foreach ($dates as $item)

                    <div class="flex items-center justify-center">

                        <button
                            class="calendar-date w-[54px] h-[54px] rounded-[14px]
                            flex flex-col items-center justify-center
                            transition-all duration-200

                            {{ isset($item['muted']) ? 'text-[#E7D4D4]' : 'text-[#3B302D]' }}"
                        >

                            <span class="text-[17px] font-medium">
                                {{ $item['date'] }}
                            </span>

                            <div class="dot w-1.5 h-1.5 rounded-full bg-white mt-1 hidden"></div>

                        </button>

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

                <div class="bg-[#F5A6AF] text-white rounded-[22px] px-7 py-4 flex items-center gap-5 text-[17px] font-semibold shadow-sm">
                    <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                    Customer sudah check-in
                </div>

                <div class="bg-[#F5A6AF] text-white rounded-[22px] px-7 py-4 flex items-center gap-5 text-[17px] font-semibold shadow-sm">
                    <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                    Next Appointment in 1 Hour and 30 Minutes
                </div>

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

            <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[34px] p-5 shadow-sm">

                <div class="flex gap-5">

                    <div class="w-16 h-16 rounded-full bg-[#F3B5B5] flex flex-col items-center justify-center shrink-0">
                        <span class="text-[20px] font-semibold text-[#3B302D] leading-none">25</span>
                        <span class="text-[12px] text-[#3B302D]">April</span>
                    </div>

                    <div>
                        <h2 class="text-[17px] font-bold text-[#934A4A] leading-none">
                            09:00-09:40
                        </h2>

                        <p class="text-[14px] text-[#B56B6B]">
                            Gunting Rambut
                        </p>

                        <p class="text-[14px] text-[#934A4A] mt-2 font-medium">
                            Mbak Andini | Langganan
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

                    <button class="w-full border border-[#E9E1E1] rounded-2xl py-2.5 text-[#A05B5B] text-[16px]">
                        View Detail
                    </button>

                </div>

            </div>

        </div>

        {{-- UPCOMING --}}
        <div class="mt-8">

            <h3 class="text-[#3E382D] text-[18px] font-bold mb-3">
                Upcoming Events
            </h3>

            <div class="space-y-5">

                {{-- CARD 1 --}}
                <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[30px] p-4 shadow-md flex gap-5">

                    <div class="w-20 h-20 rounded-full bg-[#F4C3C3] flex flex-col items-center justify-center shrink-0">
                        <span class="text-[26px] font-semibold leading-none">25</span>
                        <span class="text-[15px]">April</span>
                    </div>

                    <div>
                        <h3 class="text-[20px] leading-none font-bold text-[#3B302D]">
                            10:30-11:20
                        </h3>

                        <p class="text-[#B56B6B] text-[15px]">
                            Totok Wajah
                        </p>

                        <p class="text-[#3B302D] text-[16px] mt-2 font-medium">
                            Mbak Putri
                        </p>
                    </div>

                </div>

                {{-- CARD 2 --}}
                <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[30px] p-4 shadow-md flex gap-5">

                    <div class="w-20 h-20 rounded-full bg-[#F4C3C3] flex flex-col items-center justify-center shrink-0">
                        <span class="text-[26px] font-semibold leading-none">25</span>
                        <span class="text-[15px]">April</span>
                    </div>

                    <div>
                        <h3 class="text-[20px] leading-none font-bold text-[#3B302D]">
                            10:30-11:20
                        </h3>

                        <p class="text-[#B56B6B] text-[15px]">
                            Totok Wajah
                        </p>

                        <p class="text-[#3B302D] text-[16px] mt-2 font-medium">
                            Mbak Putri
                        </p>
                    </div>

                </div>

                {{-- CARD 3 --}}
                <div class="bg-white border-[3px] border-[#F1A9B1] rounded-[30px] p-4 shadow-md flex gap-5">

                    <div class="w-20 h-20 rounded-full bg-[#F4C3C3] flex flex-col items-center justify-center shrink-0">
                        <span class="text-[26px] font-semibold leading-none">25</span>
                        <span class="text-[15px]">April</span>
                    </div>

                    <div>
                        <h3 class="text-[20px] leading-none font-bold text-[#3B302D]">
                            16:20-17:10
                        </h3>

                        <p class="text-[#B56B6B] text-[15px] ">
                            Pewarnaan Rambut
                        </p>

                        <p class="text-[#3B302D] text-[16px] mt-2 font-medium">
                            Mbak Zulaeka
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- SCRIPT ACTIVE CALENDAR --}}
<script>

    const dates = document.querySelectorAll('.calendar-date');

    dates.forEach(date => {

        date.addEventListener('click', () => {

            // reset semua
            dates.forEach(item => {

                item.classList.remove(
                    'bg-[#FF6678]',
                    'text-white',
                    'shadow-md',
                    'scale-105'
                );

                item.querySelector('.dot').classList.add('hidden');

            });

            // active baru
            date.classList.add(
                'bg-[#FF6678]',
                'text-white',
                'shadow-md',
                'scale-105'
            );

            date.querySelector('.dot').classList.remove('hidden');

        });

    });

</script>

@endsection