@extends('user.app')

@section('content')

@php
    $resultFotos = $resultFotos ?? collect();
@endphp

<div class="min-h-screen bg-gradient-to-b from-[#FFF1F2] via-white to-[#faf7f5] text-[#3E382D]">

    {{-- HERO --}}
    <section class="relative pt-32 md:pt-40 pb-12 px-6 text-center">
        <div class="absolute top-10 left-1/4 w-72 h-72 bg-rose-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-20 right-1/4 w-56 h-56 bg-pink-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl mx-auto">
            <a href="{{ route('gallery.index') }}"
               class="inline-flex items-center gap-1.5 text-xs text-[#c47878] hover:text-[#3E382D] transition mb-5 tracking-widest uppercase font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Galeri
            </a>

            <h1 class="text-5xl md:text-7xl font-bold leading-tight text-[#3E382D]">
                {{ $layanan->nama_layanan }}
            </h1>

            <p class="mt-4 text-gray-500 text-sm leading-relaxed max-w-lg mx-auto">
                {{ $layanan->deskripsi }}
            </p>

            <div class="mt-8 mx-auto w-16 h-px bg-gradient-to-r from-transparent via-[#e9bcbc] to-transparent"></div>
        </div>
    </section>

    {{-- BEFORE & AFTER --}}
    <section class="px-6 pb-20 md:pb-28">
        <div class="max-w-3xl mx-auto">
            <p class="text-center text-[11px] text-[#c47878] uppercase tracking-widest mb-7 font-semibold">Transformasi</p>

            <div class="grid grid-cols-2 gap-3 md:gap-5">

                {{-- BEFORE --}}
                <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] border border-[#fce7e7] bg-[#faf7f5] shadow-sm">
                    @php
                        $beforeUrl = $beforeFoto?->url_foto
                            ? asset($beforeFoto->url_foto)
                            : ($layanan->cover_foto ? asset($layanan->cover_foto) : asset('album/default.jpg'));
                    @endphp
                    <img src="{{ $beforeUrl }}"
                         alt="Before {{ $layanan->nama_layanan }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                         onerror="this.onerror=null;this.src='{{ asset('album/default.jpg') }}';">

                    <div class="absolute inset-0 bg-gradient-to-t from-[#3E382D]/60 via-transparent to-transparent opacity-90"></div>
                    <div class="absolute top-3 right-3 bg-[#F472B6] text-white text-[10px] px-3 py-1 rounded-full font-semibold uppercase tracking-wide shadow-sm">
                        Sebelum
                    </div>
                </div>

                {{-- AFTER --}}
                <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] border-2 border-[#F472B6]/40 bg-[#faf7f5] shadow-md">
                    @php
                        $afterUrl = $afterFoto?->url_foto
                            ? asset($afterFoto->url_foto)
                            : ($layanan->cover_foto ? asset($layanan->cover_foto) : asset('album/default.jpg'));
                    @endphp
                    <img src="{{ $afterUrl }}"
                         alt="After {{ $layanan->nama_layanan }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                         onerror="this.onerror=null;this.src='{{ asset('album/default.jpg') }}';">

                    <div class="absolute inset-0 bg-gradient-to-t from-[#3E382D]/70 via-[#F472B6]/10 to-transparent"></div>
                    <div class="absolute top-3 right-3 bg-[#F472B6] text-white text-[10px] px-3 py-1 rounded-full font-semibold uppercase tracking-wide shadow-sm">
                        Setelah
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- HASIL PERAWATAN --}}
    @if($resultFotos->isNotEmpty())
    <section class="px-6 pb-15">
        <div class="max-w-4xl mx-auto">
            <p class="text-center text-[11px] text-[#c47878] uppercase tracking-widest mb-7 font-semibold">Hasil Perawatan</p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($resultFotos as $foto)
                <div class="group relative rounded-xl overflow-hidden border border-[#fce7e7] bg-[#faf7f5] shadow-sm hover:shadow-md transition-shadow aspect-[4/5]">
                    <img src="{{ $foto->url_foto ? asset($foto->url_foto) : asset('album/default.jpg') }}"
                         alt="Hasil perawatan {{ $layanan->nama_layanan }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         onerror="this.onerror=null;this.src='{{ asset('album/default.jpg') }}';">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @else
    {{-- Fallback: cover_foto layanan jika tidak ada result photos --}}
    @if($layanan->cover_foto)
    <section class="px-6 pb-15">
        <div class="max-w-4xl mx-auto">
            <p class="text-center text-[11px] text-[#c47878] uppercase tracking-widest mb-7 font-semibold">Hasil Perawatan</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @for($i = 0; $i < 4; $i++)
                <div class="rounded-xl overflow-hidden border border-[#fce7e7] bg-[#faf7f5] shadow-sm">
                    <img src="{{ asset($layanan->cover_foto) }}"
                         alt="{{ $layanan->nama_layanan }}"
                         class="w-full object-cover"
                         onerror="this.onerror=null;this.src='{{ asset('album/default.jpg') }}';">
                </div>
                @endfor
            </div>
        </div>
    </section>
    @else
    <section class="px-6 pb-15 text-center">
        <p class="text-gray-400 text-sm">Foto hasil perawatan belum tersedia</p>
    </section>
    @endif
    @endif

    {{-- HARGA --}}
    @if($layananCabang->isNotEmpty())
    <section class="px-6 pb-10 ">
        <div class="max-w-xl mx-auto border-t border-[#fce7e7] pt-12">
            <p class="text-center text-[16px] text-[#c47878] uppercase tracking-widest mb-10 font-semibold">Harga Layanan</p>
            <div class="space-y-3">
                @foreach($layananCabang as $lc)
                <div class="flex justify-between items-center py-3 border-b border-[#faf3f3] last:border-0">
                    <div>
                        <p class="text-sm font-medium text-[#3E382D]">{{ $lc->nama_cabang }}</p>
                        @if(!empty($lc->alamat))
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ Str::limit($lc->alamat, 35) }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($lc->harga_promo)
                            <p class="text-[11px] text-gray-400 line-through">Rp {{ number_format($lc->harga, 0, ',', '.') }}</p>
                            <p class="text-sm font-bold" style="color: #F472B6;">Rp {{ number_format($lc->harga_promo, 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm font-bold" style="color: #F472B6;">Rp {{ number_format($lc->harga, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @if(isset($layanan->id))
            <div class="text-center mt-8">
                <a href="{{ route('layanan.detail', $layanan->id) }}"
                   class="inline-flex items-center gap-2 text-xs font-semibold text-[#c47878] hover:text-[#3E382D] transition">
                    Lihat detail & booking layanan ini
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- ULASAN --}}
    @if($ulasan->isNotEmpty())
    <section class="px-6 pb-20 pt-12">
        <div class="max-w-xl mx-auto border-t border-[#fce7e7] pt-12">
            <p class="text-center text-[16px] text-[#c47878] uppercase tracking-widest mb-8 font-semibold">Kata Mereka</p>
            <div class="space-y-6">
                @foreach($ulasan as $review)
                <div class="relative pl-5 border-l-2 border-[#F472B6]/30">
                    <span class="absolute -top-1 -left-2.5 text-[#e9bcbc] text-3xl font-serif leading-none select-none">"</span>
                    @if($review->komentar)
                    <p class="text-gray-600 text-sm leading-relaxed italic mb-2">
                        {{ $review->komentar }}
                    </p>
                    @endif
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-medium text-[#3E382D]">— {{ $review->nama_pelanggan }}</p>
                        <span class="text-xs tracking-widest" style="color: #F472B6;">
                            @for($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($ulasan->isEmpty())
    <p class="pb-16 pt-8 text-center text-xs text-gray-400 uppercase tracking-widest">
        Belum ada ulasan
    </p>
    @endif

    {{-- BACK BUTTON --}}
    <div class="pb-20 text-center">
        <a href="{{ route('gallery.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#b89898] hover:text-[#3E382D] transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 12H5M5 12l7-7M5 12l7 7"/>
            </svg>
            Kembali ke Galeri
        </a>
    </div>

</div>

@endsection