@extends('pegawai.app')

@section('content')

@php
    $filter = request('filter', 'harian');
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

        {{-- HARIAN --}}
        <a href="{{ url()->current() }}?filter=harian"
           class="px-15 py-3 text-[16px] font-semibold transition-all duration-200
           {{ $filter == 'harian'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Harian
        </a>

        {{-- MINGGUAN --}}
        <a href="{{ url()->current() }}?filter=mingguan"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'mingguan'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Mingguan
        </a>

        {{-- BULANAN --}}
        <a href="{{ url()->current() }}?filter=bulanan"
           class="px-15 py-3 text-[16px] font-semibold border-l border-[#F1A9B1] transition-all duration-200
           {{ $filter == 'bulanan'
                ? 'bg-[#F1A9B1] text-white'
                : 'bg-white text-[#3B302D]' }}">
            Bulanan
        </a>

    </div>

    {{-- =========================
        HARIAN
    ========================== --}}
    @if($filter == 'harian')

    <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] p-6">

        {{-- DATE --}}
        <div class="flex items-center justify-between text-center">

            {{-- LEFT --}}
            <button class="text-[#3B302D]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </button>

            {{-- DATE TEXT --}}
            <div>
                <p class="text-[#A14F42] text-[17px] font-semibold leading-none">
                    Sabtu
                </p>
                <h2 class="text-[24px] font-bold text-[#3B302D] mt-1.5 leading-none">
                    25 April 2026
                </h2>
            </div>

            {{-- RIGHT --}}
            <button class="text-[#3B302D]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5L15.75 12l-7.5 7.5"/>
                </svg>
            </button>

        </div>

        {{-- SCHEDULE --}}
        <div class="mt-8 space-y-3">

            @php
            $schedule = [
                ['09:00', 'Gunting Rambut', 'Andini | Langganan | 09:00-09:40'],
                ['10:30', 'Totok Wajah', 'Putri | Langganan | 10:30-11:20'],
                ['14:10', 'Facial Whitening & Creambath', 'Sarah | Langganan | 14:10-15:00'],
                ['16:20', 'Pewarnaan Rambut', 'Zulaeka | Langganan | 16:20-17:10'],
            ];
            @endphp

            @foreach ($schedule as $s)
            <div class="flex items-center">

                <div class="w-[90px]">
                    <h3 class="text-[20px] font-semibold text-[#3E382D]">
                        {{ $s[0] }}
                    </h3>
                </div>

                <div class="flex-1 bg-[#FCEBED] rounded-[20px] px-5 py-4 hover:shadow-md transition">
                    <h3 class="text-[17px] font-semibold text-[#3E382D]">
                        {{ $s[1] }}
                    </h3>
                    <p class="text-[14px] text-[#4F4545] mt-0.5">
                        {{ $s[2] }}
                    </p>
                </div>

            </div>
            @endforeach

        </div>

    </div>

    @endif

    {{-- =========================
        MINGGUAN
    ========================== --}}
    @if($filter == 'mingguan')

    <div class="mt-6 bg-white border-[2px] border-[#F1A9B1]
                rounded-[28px] p-6 ">

        {{-- DATE LIST --}}
        <div class="flex items-center gap-4 flex-wrap">

    {{-- ACTIVE --}}
    <div class="px-5 py-2 rounded-[10px]
                bg-[#F5A6AF]
                flex flex-col items-center justify-center
                text-center shadow-sm min-w-[110px]
                gap-1">

        <h3 class="text-[17px] font-semibold text-white leading-none">
            Sab
        </h3>

        <p class="text-[14px] text-white leading-none">
            25 Apr
        </p>

    </div>

    {{-- ITEM --}}
    <div class="px-5 py-2 rounded-[10px]
                border-[2px] border-[#F1A9B1]
                flex flex-col items-center justify-center
                text-center hover:bg-[#FFF5F6]
                transition duration-300 cursor-pointer
                gap-1">

        <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
            Min
        </h3>

        <p class="text-[14px] text-[#3B302D] leading-none">
            26 Apr
        </p>

    </div>

    {{-- ITEM --}}
    <div class="px-5 py-2 rounded-[10px]
                border-[2px] border-[#F1A9B1]
                flex flex-col items-center justify-center
                text-center hover:bg-[#FFF5F6]
                transition duration-300 cursor-pointer
                gap-1">

        <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
            Sen
        </h3>

        <p class="text-[14px] text-[#3B302D] leading-none">
            27 Apr
        </p>

    </div>

    {{-- ITEM --}}
    <div class="px-5 py-2 rounded-[10px]
                border-[2px] border-[#F1A9B1]
                flex flex-col items-center justify-center
                text-center hover:bg-[#FFF5F6]
                transition duration-300 cursor-pointer
                gap-1">

        <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
            Sel
        </h3>

        <p class="text-[14px] text-[#3B302D] leading-none">
            28 Apr
        </p>

    </div>

    {{-- ITEM --}}
    <div class="px-5 py-2 rounded-[10px]
                border-[2px] border-[#F1A9B1]
                flex flex-col items-center justify-center
                text-center hover:bg-[#FFF5F6]
                transition duration-300 cursor-pointer
                gap-1">

        <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
            Rab
        </h3>

        <p class="text-[14px] text-[#3B302D] leading-none">
            29 Apr
        </p>

    </div>

    {{-- ITEM --}}
    <div class="px-5 py-2 rounded-[10px]
                border-[2px] border-[#F1A9B1]
                flex flex-col items-center justify-center
                text-center hover:bg-[#FFF5F6]
                transition duration-300 cursor-pointer
                gap-1">

        <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
            Kam
        </h3>

        <p class="text-[14px] text-[#3B302D] leading-none">
            30 Apr
        </p>

    </div>

</div>
        {{-- SCHEDULE --}}
        <div class="mt-8 space-y-8">

            {{-- ITEM --}}
            <div>

                <div class="flex items-center gap-3">

                    {{-- ICON --}}
                    <div class="w-7 h-7 rounded-full bg-[#3B302D]
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="2.5"
                             stroke="currentColor"
                             class="w-4 h-4 text-white">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 6v6l4 2" />

                        </svg>

                    </div>

                    <h2 class="text-[17px] font-semibold text-[#3B302D]">
                        09:00-09:40
                    </h2>

                </div>

                <h3 class="mt-2 text-[17px] font-semibold text-[#3B302D]">
                    Gunting Rambut
                </h3>

                <p class="text-[15px] text-[#3B302D]">
                    Andini | Langganan | 09:00-09:40
                </p>

            </div>

            {{-- ITEM --}}
            <div>

                <div class="flex items-center gap-3">

                    <div class="w-7 h-7 rounded-full bg-[#3B302D]
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="2.5"
                             stroke="currentColor"
                             class="w-4 h-4 text-white">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 6v6l4 2" />

                        </svg>

                    </div>

                    <h2 class="text-[17px] font-semibold text-[#3B302D]">
                        10:30-11:20
                    </h2>

                </div>

                <h3 class="mt-2 text-[17px] font-semibold text-[#3B302D]">
                    Totok Wajah
                </h3>

                <p class="text-[15px] text-[#3B302D]">
                    Putri | Langganan | 10:30-11:20
                </p>

            </div>

        </div>

    </div>

    @endif

    {{-- =========================
        BULANAN
    ========================== --}}
    @if($filter == 'bulanan')

    <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-6">

        {{-- TOP --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-[24px] font-bold">
                April 2026
            </h2>

            <div class="flex items-center gap-6">

                <button class="hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2.5"
                         stroke="currentColor"
                         class="w-9 h-9">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <button class="hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2.5"
                         stroke="currentColor"
                         class="w-9 h-9">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

            </div>
        </div>

        {{-- DAY NAME --}}
        <div class="grid grid-cols-7 mb-6 text-center font-semibold text-[18px]">
            <div>Senin</div>
            <div>Selasa</div>
            <div>Rabu</div>
            <div>Kamis</div>
            <div>Jumat</div>
            <div>Sabtu</div>
            <div>Minggu</div>
        </div>

        {{-- CALENDAR --}}
        <div class="grid grid-cols-7 gap-y-3 text-center auto-rows-[45px]">

            {{-- ROW 1 --}}
            <div class="flex flex-col items-center justify-center text-[17px] text-gray-400">30</div>
            <div class="flex flex-col items-center justify-center text-[17px] text-gray-400">31</div>
            <div class="flex flex-col items-center justify-center text-[17px]">1</div>
            <div class="flex flex-col items-center justify-center text-[17px]">2</div>

            <div class="flex flex-col items-center justify-center text-[17px]">
                <span>3</span>
                <div class="w-1.5 h-1.5 rounded-full bg-[#F5B6BC] mt-1"></div>
            </div>

            <div class="flex flex-col items-center justify-center text-[17px]">4</div>
            <div class="flex flex-col items-center justify-center text-[17px]">5</div>

            {{-- ROW 2 --}}
            <div class="flex flex-col items-center justify-center text-[17px]">6</div>
            <div class="flex flex-col items-center justify-center text-[17px]">7</div>
            <div class="flex flex-col items-center justify-center text-[17px]">8</div>
            <div class="flex flex-col items-center justify-center text-[17px]">9</div>
            <div class="flex flex-col items-center justify-center text-[17px]">10</div>
            <div class="flex flex-col items-center justify-center text-[17px]">11</div>
            <div class="flex flex-col items-center justify-center text-[17px]">12</div>

            {{-- ROW 3 --}}
            <div class="flex flex-col items-center justify-center text-[17px]">13</div>
            <div class="flex flex-col items-center justify-center text-[17px]">14</div>
            <div class="flex flex-col items-center justify-center text-[17px]">15</div>
            <div class="flex flex-col items-center justify-center text-[17px]">16</div>
            <div class="flex flex-col items-center justify-center text-[17px]">17</div>
            <div class="flex flex-col items-center justify-center text-[17px]">18</div>
            <div class="flex flex-col items-center justify-center text-[17px]">19</div>

            {{-- ROW 4 --}}
            <div class="flex flex-col items-center justify-center text-[17px]">20</div>
            <div class="flex flex-col items-center justify-center text-[17px]">21</div>
            <div class="flex flex-col items-center justify-center text-[17px]">22</div>
            <div class="flex flex-col items-center justify-center text-[17px]">23</div>
            <div class="flex flex-col items-center justify-center text-[17px]">24</div>

            {{-- ACTIVE --}}
            <div class="flex items-center justify-center">
                <div class="w-[50px] h-[50px] rounded-[10px] bg-[#FF6678] flex flex-col items-center justify-center shadow-md">
                    <span class="text-[#3B302D] text-[17px] font-bold">25</span>
                    <div class="w-1.5 h-1.5 rounded-full bg-white mt-1"></div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center text-[17px]">
                <span>26</span>
                <div class="w-1.5 h-1.5 rounded-full bg-[#FF6678] mt-1"></div>
            </div>

            {{-- ROW 5 --}}
            <div class="flex flex-col items-center justify-center text-[17px]">27</div>

            <div class="flex flex-col items-center justify-center text-[17px]">
                <span>28</span>
                <div class="w-1.5 h-1.5 rounded-full bg-[#FF6678] mt-1"></div>
            </div>

            <div class="flex flex-col items-center justify-center text-[17px]">
                <span>29</span>
                <div class="w-1.5 h-1.5 rounded-full bg-[#FF6678] mt-1"></div>
            </div>

            <div class="flex flex-col items-center justify-center text-[17px]">30</div>
            <div class="flex flex-col items-center justify-center text-[17px] text-gray-400">1</div>
            <div class="flex flex-col items-center justify-center text-[17px] text-gray-400">2</div>

        </div>
    </div>

    @endif

</div>

@endsection