@extends('pegawai.app')

@section('content')

<div class="w-full px-4 py-4 font-sans text-[#3B302D]">

    {{-- TITLE --}}
    <div>
    <div>
        <h1 class="text-[26px] font-bold leading-none">
            Notifikasi
        </h1>
        <p class="mt-2 text-[16px]">
             Pusat informasi penting untuk mendukung pekerjaan Anda
        </p>
    </div>
    
<div class="flex mt-6 border-[2px] border-[#F1A9B1] rounded-[15px] overflow-hidden w-fit">

    <a href="{{ url()->current() }}?filter=semua"
       class="px-15 py-3 text-[16px] font-semibold transition-all duration-200
       {{ request('filter', 'semua') == 'semua'
            ? 'bg-[#F1A9B1] text-white'
            : 'bg-white text-[#3B302D]' }}">
        Semua
    </a>

    <a href="{{ url()->current() }}?filter=belum-dibaca"
       class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
       {{ request('filter') == 'belum-dibaca'
            ? 'bg-[#F1A9B1] text-white'
            : 'bg-white text-[#3B302D]' }}">
        Belum dibaca
    </a>

    <a href="{{ url()->current() }}?filter=booking"
       class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
       {{ request('filter') == 'booking'
            ? 'bg-[#F1A9B1] text-white'
            : 'bg-white text-[#3B302D]' }}">
        Booking
    </a>

    <a href="{{ url()->current() }}?filter=jadwal"
       class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
       {{ request('filter') == 'jadwal'
            ? 'bg-[#F1A9B1] text-white'
            : 'bg-white text-[#3B302D]' }}">
        Jadwal
    </a>

    <a href="{{ url()->current() }}?filter=sistem"
       class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
       {{ request('filter') == 'sistem'
            ? 'bg-[#F1A9B1] text-white'
            : 'bg-white text-[#3B302D]' }}">
        Sistem
    </a>

</div>
    

    <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-6">
    {{-- BELUM DIBACA --}}


        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-[18px] font-medium text-[#3B302D]">
                Belum Dibaca
            </h2>

            <span class="w-4 h-4 rounded-full bg-[#FF465F]"></span>
        </div>

        {{-- CARD 1 --}}
        <div
            class="border border-[#F1C9CE] rounded-[24px] px-8 py-5 flex justify-between items-start hover:shadow-md transition bg-white">

            <div class="flex gap-6">

                {{-- ICON --}}
                <div
                    class="w-[80px] h-[80px] rounded-[20px] bg-[#FFF1F3] flex items-center justify-center shrink-0">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="w-11 h-11 text-[#EB2D55]">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8.25 6.75V4.5m7.5 2.25V4.5m-9 6h10.5m-13.5 9h16.5A2.25 2.25 0 0021 17.25V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5z"/>
                    </svg>

                </div>

                {{-- TEXT --}}
                <div>

                    <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
                        Booking Baru
                    </h3>

                    <p class="text-[14px] text-[#3B302D] mt-3">
                        Natali Desi memesan langganan
                    </p>

                    <p class="text-[14px] font-normal text-[#3B302D]">
                        Hair Spa + Creambath
                    </p>

                    <p class="text-[14px] text-[#3B302D]">
                        15 Mei 2026, 09:00 WIB
                    </p>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col items-end justify-between h-full">

                <p class="text-[15px] text-[#3B302D]">
                    5 menit lalu
                </p>

                <span class="w-4 h-4 rounded-full bg-[#FF4B63] mt-10"></span>

            </div>

        </div>

        {{-- CARD 2 --}}
        <div
            class="border border-[#F1C9CE] rounded-[24px] px-6 py-6 flex justify-between items-start hover:shadow-md transition bg-white mt-6">

            <div class="flex gap-6">

                {{-- ICON --}}
                <div
                    class="w-[80px] h-[80px] rounded-[20px] bg-[#FFF4A9] flex items-center justify-center shrink-0">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="w-11 h-11 text-[#FF8A00]">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 00-2.312 6.022c1.733.64 3.56 1.08 5.454 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>

                </div>

                {{-- TEXT --}}
                <div>

                    <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
                        Jadwal 30 Menit Lagi
                    </h3>

                    <p class="text-[14px] text-[#3B302D] mt-3">
                        Anda memiliki jadwal Facial + Waxing
                    </p>

                    <p class="text-[14px] text-[#3B302D]">
                        dengan Amanda Zahra
                    </p>

                    <p class="text-[14px] font-normal text-[#3B302D]">
                        Pukul 10:00 WIB
                    </p>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col items-end justify-between h-full">

                <p class="text-[15px] text-[#3B302D]">
                    25 menit lalu
                </p>

                <span class="w-4 h-4 rounded-full bg-[#FF4B63] mt-10"></span>

            </div>

        </div>

    

    {{-- SEBELUMNYA --}}
    <div class="mt-16">

        <h2 class="text-[18px] font-medium text-[#3B302D] mb-6">
            Sebelumnya
        </h2>

        {{-- CARD --}}
        <div
            class="border border-[#F1C9CE] rounded-[24px] px-6 py-6 flex justify-between items-start hover:shadow-md transition bg-white">

            <div class="flex gap-6">

                {{-- ICON --}}
                <div
                    class="w-[80px] h-[80px] rounded-[20px] bg-[#EFE3FF] flex items-center justify-center shrink-0">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="w-11 h-11 text-[#8D46FF]">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                    </svg>

                </div>

                {{-- TEXT --}}
                <div>

                    <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
                        Reschedule Dikonfirmasi
                    </h3>

                    <p class="text-[14px] text-[#3B302D] mt-3">
                        Dewi Lestari telah menjadwalkan ulang
                    </p>

                    <p class="text-[14px] text-[#3B302D]">
                        layanan ke 29 April 2025, 14:00 WIB
                    </p>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col items-end justify-between h-full">

                <p class="text-[15px] text-[#3B302D]">
                    Kemarin, 18.20
                </p>

                <span class="w-4 h-4 rounded-full bg-[#BFBFBF] mt-10"></span>

            </div>

        </div>
</div>
        {{-- BUTTON --}}
        <button
            class="w-full mt-6 border border-[#E8D5D8] rounded-[22px] py-5 flex items-center justify-center gap-4 hover:bg-[#FFF5F6] transition">

            <span class="text-[18px] font-bold text-[#3B302D]">
                Lihat Semua Notifikasi
            </span>

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="2"
                 stroke="currentColor"
                 class="w-7 h-7 text-[#3B302D]">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>

        </button>

    </div>

</div>

@endsection