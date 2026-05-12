@extends('user.app')

@section('content')

<section class="min-h-screen bg-gradient-to-b from-[#FFE4E6] to-white flex flex-col items-center py-12 px-4">

    <h1 class="text-5xl md:text-6xl font-bold text-black mb-12 mt-16 text-center">
        {{ $specialist->nama }}
    </h1>

    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-sm flex flex-col md:flex-row overflow-hidden border border-gray-100">

        {{-- FOTO --}}
        <div class="w-full md:w-2/5 h-56 md:h-auto overflow-hidden">
            <img src="{{ $specialist->foto
                        ? asset($specialist->foto)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($specialist->nama) . '&background=FFE4E6&color=3E382D&size=400' }}"
                 alt="{{ $specialist->nama }}"
                 class="w-full h-full object-cover">
        </div>

        {{-- KONTEN --}}
        <div class="w-full md:w-3/5 p-6 flex flex-col justify-between">

            {{-- Nama + Jabatan + Jadwal --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $specialist->nama }}</h2>
                    <p class="text-gray-500 text-sm">{{ $specialist->jabatan ?? 'Specialist' }}</p>
                </div>

                {{-- Jadwal kerja dari database --}}
                <div class="text-right">
                    <div class="inline-flex items-center gap-2 bg-[#FFB3B3] text-white px-4 py-1.5 rounded-md text-sm font-medium mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Jadwal Tersedia
                    </div>

                    @if($jadwal->isEmpty())
                        <p class="text-[10px] text-gray-400 italic">Belum ada jadwal tersedia.</p>
                    @else
                        <div class="text-[10px] text-white space-y-1 max-h-24 overflow-y-auto">
                            @foreach($jadwal as $tanggal => $sesi)
                                <p class="bg-[#FFB3B3] px-2 py-1 rounded">
                                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}:
                                    @foreach($sesi as $s)
                                        {{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}
                                    @endforeach
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Deskripsi --}}
            <p class="text-gray-700 text-sm leading-relaxed mb-3 max-w-sm">
                {{ $specialist->deskripsi ?? 'Tenaga profesional berpengalaman di Salon Muslimah Dina.' }}
            </p>

            {{-- Layanan yang dikerjakan --}}
            @if($layananList->isNotEmpty())
            <ul class="space-y-2 mb-3">
                @foreach($layananList as $layanan)
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $layanan->nama_layanan }}
                </li>
                @endforeach
            </ul>
            @endif

            {{-- Kontak + Tombol --}}
            <div class="flex flex-col gap-4">
                @if($specialist->no_hp)
                <div class="text-[10px] text-gray-500 italic">
                    Untuk jadwal di luar yang tersedia, hubungi:<br>
                    <span class="font-semibold text-gray-700">{{ $specialist->no_hp }}</span>
                </div>
                @endif

                <button class="w-full bg-[#E5B8B8] hover:bg-[#D4A7A7] text-white font-bold py-2 rounded-lg transition-colors text-xl shadow-inner">
                    Book Now
                </button>
            </div>
        </div>

    </div>
</section>

@endsection