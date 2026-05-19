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
    

{{-- BELUM DIBACA --}}
@if($belumDibaca->isNotEmpty())
<div class="flex items-center gap-3 mb-6">
    <h2 class="text-[18px] font-medium text-[#3B302D]">Belum Dibaca</h2>
    <span class="w-4 h-4 rounded-full bg-[#FF465F]"></span>
</div>

@foreach($belumDibaca as $notif)
    @php $icon = $notif->getIconConfig(); @endphp
    <div class="border border-[#F1C9CE] rounded-[24px] px-8 py-5 flex justify-between items-start hover:shadow-md transition bg-white {{ !$loop->first ? 'mt-6' : '' }}">
        <div class="flex gap-6">
            {{-- ICON --}}
            <div class="w-[80px] h-[80px] rounded-[20px] {{ $icon['bg'] }} flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" class="w-11 h-11 {{ $icon['color'] }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
                </svg>
            </div>
            {{-- TEXT --}}
            <div>
                <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
                    {{ $notif->getTitleLabel() }}
                </h3>
                <p class="text-[14px] text-[#3B302D] mt-3">{{ $notif->pesan }}</p>
                <p class="text-[13px] text-[#9B8B87] mt-1">{{ $notif->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>
        {{-- RIGHT --}}
        <div class="flex flex-col items-end justify-between h-full">
            <p class="text-[15px] text-[#3B302D]">{{ $notif->getWaktuLabel() }}</p>
            <span class="w-4 h-4 rounded-full bg-[#FF4B63] mt-10"></span>
        </div>
    </div>
@endforeach
@endif

{{-- SEBELUMNYA --}}
@if($sebelumnya->isNotEmpty())
<div class="mt-16">
    <h2 class="text-[18px] font-medium text-[#3B302D] mb-6">Sebelumnya</h2>

    @foreach($sebelumnya as $notif)
        @php $icon = $notif->getIconConfig(); @endphp
        <div class="border border-[#F1C9CE] rounded-[24px] px-6 py-6 flex justify-between items-start hover:shadow-md transition bg-white {{ !$loop->first ? 'mt-6' : '' }}">
            <div class="flex gap-6">
                <div class="w-[80px] h-[80px] rounded-[20px] {{ $icon['bg'] }} flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" class="w-11 h-11 {{ $icon['color'] }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[17px] font-semibold text-[#3B302D] leading-none">
                        {{ $notif->getTitleLabel() }}
                    </h3>
                    <p class="text-[14px] text-[#3B302D] mt-3">{{ $notif->pesan }}</p>
                    <p class="text-[13px] text-[#9B8B87] mt-1">{{ $notif->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
            <div class="flex flex-col items-end justify-between h-full">
                <p class="text-[15px] text-[#3B302D]">{{ $notif->getWaktuLabel() }}</p>
                <span class="w-4 h-4 rounded-full bg-[#BFBFBF] mt-10"></span>
            </div>
        </div>
    @endforeach
</div>
@endif

{{-- KOSONG --}}
@if($belumDibaca->isEmpty() && $sebelumnya->isEmpty())
<div class="text-center py-16 text-[#9B8B87]">
    <p class="text-[16px]">Tidak ada notifikasi.</p>
</div>
@endif
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