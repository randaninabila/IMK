@extends('pegawai.app')

@section('content')

@php
    $filter = request('filter', 'semua');
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

    {{-- FILTER --}}
    <div class="flex mt-6 border-[2px] border-[#F1A9B1] rounded-[15px] overflow-hidden w-fit">

        {{-- SEMUA --}}
        <a href="{{ url()->current() }}?filter=semua"
           class="px-15 py-3 text-[16px] font-semibold transition-all duration-200
           {{ $filter == 'semua'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Semua
        </a>

        {{-- HARI INI --}}
        <a href="{{ url()->current() }}?filter=hariini"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'hariini'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Hari ini
        </a>

        {{-- BULANAN --}}
        <a href="{{ url()->current() }}?filter=bulanan"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'bulanan'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Bulanan
        </a>

        {{-- TAHUNAN --}}
        <a href="{{ url()->current() }}?filter=tahunan"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'tahunan'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Tahunan
        </a>

    </div>

    {{-- =========================
        SEMUA
    ========================== --}}
    @if($filter == 'semua')

    {{-- SEARCH --}}
    <div class="flex gap-4 mt-6">

        {{-- SEARCH INPUT --}}
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
                   placeholder="Cari nama klien atau layanan..."
                   class="w-full outline-none text-[15px] text-[#3B302D] placeholder:text-[#3B302D] bg-transparent font-normal">

        </div>

        {{-- FILTER BUTTON --}}
        <button class="w-[170px] py-3 border border-[#E9B9C0] rounded-2xl bg-white flex items-center justify-center gap-3 text-[18px] text-[#3B302D] font-normal">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="2"
                 stroke="currentColor"
                 class="w-6 h-6">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10.5 6h9m-9 6h9m-9 6h9M4.5 6h.008v.008H4.5V6zm0 6h.008v.008H4.5V12zm0 6h.008v.008H4.5V18z"/>

            </svg>

            <h3 class="text-[18px] text-[#3B302D] font-semibold">
                Filter
            </h3>

        </button>

    </div>

    {{-- DATE --}}
    <div class="mt-12 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-7">

        <h3 class="text-[18px] text-[#3B302D] font-bold">
            Hari ini, 24 April 2026
        </h3>

        {{-- CARD LIST --}}
        <div class="mt-6 space-y-5">

            @for ($i = 0; $i < 4; $i++)

            <div class="bg-white border border-[#F1C9CF] rounded-[20px] px-6 py-5 flex items-center justify-between">

                {{-- LEFT --}}
                <div class="flex items-center gap-6">

                    <img
                        src="https://i.pravatar.cc/100?img=12"
                        class="w-18 h-18 rounded-full object-cover"
                        alt=""
                    >

                    <div>

                        <h2 class="text-[17px] font-semibold text-[#3B302D] leading-none">
                            Amanda Putri
                        </h2>

                        {{-- WAKTU --}}
                        <p class="text-[14px] text-[#3B302D] mt-2 font-normal">
                            10.00 - 13.00 (180 menit)
                        </p>

                        {{-- SERVICES --}}
                        <div class="flex items-center gap-6 text-[14px] mt-2">

                            {{-- Facial --}}
                            <div class="flex items-center gap-2">

                                <div class="w-3 h-3 rounded-full bg-[#FF1F57] flex items-center justify-center text-white text-[14px]">
                                    ❤
                                </div>

                                <span class="text-[#3B302D] font-normal">
                                    Facial
                                </span>

                            </div>

                            {{-- Waxing --}}
                            <div class="flex items-center gap-2">

                                <span class="text-[#FF1F57]">
                                    ✂
                                </span>

                                <span class="text-[#3B302D] font-normal">
                                    Waxing
                                </span>

                            </div>

                            {{-- Gunting --}}
                            <div class="flex items-center gap-2">

                                <span class="text-[#FF1F57]">
                                    ✄
                                </span>

                                <span class="text-[#3B302D] leading-tight font-normal">
                                    Gunting Rambut
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-10">

                    <div class="px-8 py-2 rounded-full bg-[#D8F5CE] text-[#3B302D] text-[16px] font-semibold">
                        Selesai
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2"
                         stroke="currentColor"
                         class="w-7 h-7 text-[#3B302D]">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m9 18 6-6-6-6"/>

                    </svg>

                </div>

            </div>

            @endfor

        </div>

        {{-- SEE ALL --}}
        <button class="w-full py-5 border border-[#F1C9CF] rounded-[20px] mt-8 bg-white flex items-center justify-center relative">

            <span class="text-[18px] font-bold text-[#3B302D]">
                Lihat Semua Riwayat
            </span>

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="2"
                 stroke="currentColor"
                 class="w-7 h-7 absolute right-8 text-[#3B302D]">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="m9 18 6-6-6-6"/>

            </svg>

        </button>

    </div>

    {{-- SUMMARY --}}
    <div class="mt-8 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-7">

        <h2 class="text-[18px] font-bold text-[#3B302D]">
            Ringkasan Hari ini
        </h2>

        <div class="grid grid-cols-4 gap-6 mt-5">

            {{-- CARD 1 --}}
            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">

                <p class="text-[15px] text-[#3B302D] font-normal">
                    Total Layanan
                </p>

                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">
                    8
                </h1>

                <span class="text-[15px] text-[#3B302D] font-normal">
                    Sesi
                </span>

            </div>

            {{-- CARD 2 --}}
            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">

                <p class="text-[15px] text-[#3B302D] font-normal">
                    Total Durasi
                </p>

                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">
                    540
                </h1>

                <span class="text-[15px] text-[#3B302D] font-normal">
                    Menit
                </span>

            </div>

            {{-- CARD 3 --}}
            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">

                <p class="text-[15px] text-[#3B302D] font-normal">
                    Klien Dilayani
                </p>

                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">
                    7
                </h1>

                <span class="text-[15px] text-[#3B302D] font-normal">
                    Orang
                </span>

            </div>

            {{-- CARD 4 --}}
            <div class="border border-[#F1C9CF] rounded-[24px] py-4 px-6">

                <p class="text-[15px] text-[#3B302D] font-normal">
                    Pendapatan
                </p>

                <h1 class="text-[18px] font-semibold text-[#3B302D] mt-2 leading-none">
                    Rp. 3.250.000
                </h1>

                <span class="text-[15px] text-[#3B302D] font-normal">
                    Estimasi
                </span>

            </div>

        </div>

    </div>

    @endif

    {{-- =========================
        HARI INI
    ========================== --}}
    @if($filter == 'hariini')

    <div class="mt-10 text-[#3B302D] text-[18px] font-semibold">
        Halaman Hari Ini
    </div>

    @endif

    {{-- =========================
        BULANAN
    ========================== --}}
    @if($filter == 'bulanan')

    <div class="mt-10 text-[#3B302D] text-[18px] font-semibold">
        Halaman Bulanan
    </div>

    @endif

    {{-- =========================
        TAHUNAN
    ========================== --}}
    @if($filter == 'tahunan')

    <div class="mt-10 text-[#3B302D] text-[18px] font-semibold">
        Halaman Tahunan
    </div>

    @endif

</div>

@endsection