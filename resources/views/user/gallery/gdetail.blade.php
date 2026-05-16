@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] to-white flex flex-col items-center pb-24">

    {{-- TITLE --}}
    <div class="mt-28 text-center">
        <h1 class="text-5xl md:text-6xl text-[#3e3a34] font-bold">
            {{ $gallery->layanan->nama_layanan }}
        </h1>

        <p class="mt-4 text-gray-600 max-w-2xl">
            {{ $gallery->deskripsi }}
        </p>
    </div>

    {{-- CONTENT --}}
    <div class="flex flex-wrap justify-center gap-20 mt-10">

        {{-- BEFORE --}}
        <div class="text-center">

            @php
                $before = $gallery->fotos->where('tipe', 'before')->first();
            @endphp

            <img src="{{ asset($before->url_foto ?? 'images/default.jpg') }}"
                 class="w-[320px] h-[220px] object-cover rounded-md mx-auto">

            <div class="bg-[#3e3a34] text-white px-6 py-2 rounded-md inline-block mt-4">
                Before
            </div>
        </div>
        @endif

        {{-- AFTER --}}
        <div class="text-center">

            @php
                $after = $gallery->fotos->where('tipe', 'after')->first();
            @endphp

            <img src="{{ asset($after->url_foto ?? 'images/default.jpg') }}"
                 class="w-[320px] h-[220px] object-cover rounded-md mx-auto">

            <div class="bg-[#3e3a34] text-white px-6 py-2 rounded-md inline-block mt-4">
                After
            </div>

        </div>
        @endif

    </div>
    @endif

    {{-- ══════════════════════════════════════════
         FOTO HASIL (tipe = result)
    ══════════════════════════════════════════ --}}
    @if($resultFotos->isNotEmpty())
    <div class="mt-14 w-full max-w-4xl px-6">
        <h2 class="text-base font-semibold text-[#3e3a34] mb-4 text-center tracking-wide uppercase">
            Hasil Perawatan
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($resultFotos as $foto)
            <img src="{{ asset($foto->url_foto) }}"
                 alt="Hasil perawatan {{ $layanan->nama_layanan }}"
                 class="w-full h-44 object-cover rounded-xl shadow hover:scale-105 transition-transform duration-200"
                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}'">
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════
         HARGA PER CABANG
    ══════════════════════════════════════════ --}}
    @if($layananCabang->isNotEmpty())
    <div class="mt-14 w-full max-w-2xl px-6">
        <h2 class="text-base font-semibold text-[#3e3a34] mb-4 text-center tracking-wide uppercase">
            Harga Layanan
        </h2>
        <div class="space-y-3">
            @foreach($layananCabang as $lc)
            <div class="bg-white rounded-xl px-6 py-4 shadow flex justify-between items-center gap-4">

                {{-- Cabang info --}}
                <div>
                    <p class="font-semibold text-[#3e3a34] text-sm">{{ $lc->nama_cabang }}</p>
                    @if(!empty($lc->alamat))
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $lc->alamat }}</p>
                    @endif
                    @if($lc->harga_promo)
                    <p class="text-xs text-gray-400 line-through mt-1">
                        Rp {{ number_format($lc->harga, 0, ',', '.') }}
                    </p>
                    @endif
                </div>

                {{-- Harga --}}
                <div class="text-right shrink-0">
                    @if($lc->harga_promo)
                        <p class="text-[#e9bcbc] font-bold text-lg">
                            Rp {{ number_format($lc->harga_promo, 0, ',', '.') }}
                        </p>
                        <span class="text-[11px] bg-red-100 text-red-500 px-2 py-0.5 rounded-full">PROMO</span>
                    @else
                        <p class="text-[#3e3a34] font-bold text-lg">
                            Rp {{ number_format($lc->harga, 0, ',', '.') }}
                        </p>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════
         ULASAN PELANGGAN
    ══════════════════════════════════════════ --}}
    @if($ulasan->isNotEmpty())
    <div class="mt-14 w-full max-w-2xl px-6">
        <h2 class="text-base font-semibold text-[#3e3a34] mb-4 text-center tracking-wide uppercase">
            Ulasan Pelanggan
        </h2>
        <div class="space-y-4">
            @foreach($ulasan as $review)
            <div class="bg-white rounded-xl px-6 py-4 shadow">
                <div class="flex justify-between items-start">
                    <p class="font-semibold text-sm text-[#3e3a34]">{{ $review->nama_pelanggan }}</p>
                    <span class="text-yellow-400 text-sm tracking-widest">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $review->rating ? '★' : '☆' }}
                        @endfor
                    </span>
                </div>
                @if($review->komentar)
                <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $review->komentar }}</p>
                @endif
                <p class="text-[10px] text-gray-300 mt-2">
                    {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tidak ada ulasan --}}
    @if($ulasan->isEmpty())
    <p class="mt-10 text-sm text-gray-400">Belum ada ulasan untuk layanan ini.</p>
    @endif

    {{-- ══════════════════════════════════════════
         TOMBOL KEMBALI
    ══════════════════════════════════════════ --}}
    <div class="mt-12">
        <a href="{{ route('gallery.index') }}"
            class="bg-[#3e3a34] text-white px-8 py-3 rounded-full text-sm hover:opacity-80 transition">
            ← Kembali ke Galeri
        </a>
    </div>

</div>

@endsection