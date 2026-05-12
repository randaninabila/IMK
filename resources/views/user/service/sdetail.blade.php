@extends('user.app')

@section('content')

<div class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white text-[#3E382D]">

    {{-- HERO --}}
    <section class="relative h-[450px] flex items-center px-30 overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="{{ $coverFoto ? asset($coverFoto) : asset('images/placeholder.jpg') }}"
                alt="{{ $layanan->nama_layanan }}"
                class="w-full h-full object-cover"
                onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}'">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 text-white text-right">
            <div class="max-w-xl ml-auto">
                <span class="text-xs uppercase tracking-widest opacity-70">{{ $layanan->nama_jenis }}</span>
                <h1 class="text-5xl font-bold mb-4 mt-1">{{ $layanan->nama_layanan }}</h1>
                @if($layanan->deskripsi)
                <p class="text-sm leading-relaxed opacity-90 text-justify">
                    {{ $layanan->deskripsi }}
                </p>
                @endif
                @if($layanan->durasi)
                <p class="mt-3 text-sm opacity-75">⏱ {{ $layanan->durasi }} menit</p>
                @endif
            </div>
        </div>
    </section>

    {{-- HARGA PER CABANG --}}
    @if($layananCabang->isNotEmpty())
    <section class="py-12 container mx-auto px-6 max-w-3xl">
        <div class="flex items-center justify-center mb-8">
            <div class="flex-grow h-px bg-gray-300"></div>
            <h2 class="px-4 text-lg font-bold text-tertiary-500 uppercase">Harga Layanan</h2>
            <div class="flex-grow h-px bg-gray-300"></div>
        </div>

        <div class="space-y-3">
            @foreach($layananCabang as $lc)
            <div class="bg-white rounded-xl px-6 py-4 shadow flex justify-between items-center gap-4">
                <div>
                    <p class="font-semibold text-sm text-[#3e3a34]">{{ $lc->nama_cabang }}</p>
                    @if(!empty($lc->alamat))
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $lc->alamat }}</p>
                    @endif
                    @if($lc->harga_promo)
                    <p class="text-xs text-gray-400 line-through mt-1">
                        Rp {{ number_format($lc->harga, 0, ',', '.') }}
                    </p>
                    @endif
                </div>
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
    </section>
    @endif

    {{-- PAKET YANG MENGANDUNG LAYANAN INI --}}
    @if($paket->isNotEmpty())
    <section class="py-12 bg-pink-50/50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-center mb-10">
                <div class="flex-grow h-px bg-gray-300"></div>
                <h2 class="px-4 text-lg font-bold text-tertiary-500 uppercase">Tersedia Juga Dalam Paket</h2>
                <div class="flex-grow h-px bg-gray-300"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($paket as $p)
                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100">
                    <div class="p-6">
                        <span class="text-[10px] bg-rose-100 text-rose-500 px-2 py-1 rounded-full uppercase font-bold">
                            Paket
                        </span>
                        <h3 class="text-lg font-bold mt-3 leading-snug">{{ $p->nama_paket }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $p->nama_cabang }}</p>
                        @if($p->deskripsi)
                        <p class="text-tertiary-500 text-xs mt-2 italic line-clamp-2">{{ $p->deskripsi }}</p>
                        @endif
                        <div class="mt-6 flex justify-between items-center border-t pt-4">
                            <div>
                                @if($p->harga_promo)
                                    <p class="text-xs text-gray-400 line-through">
                                        Rp {{ number_format($p->harga_normal, 0, ',', '.') }}
                                    </p>
                                    <p class="font-bold text-[#e9bcbc]">
                                        Rp {{ number_format($p->harga_promo, 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="font-bold text-[#43392f]">
                                        Rp {{ number_format($p->harga_normal, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                            <button class="bg-rose-200 text-rose-800 text-xs font-bold px-4 py-2 rounded-lg hover:bg-rose-300 transition">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ULASAN --}}
    @if($ulasan->isNotEmpty())
    <section class="py-12 container mx-auto px-6 max-w-3xl">
        <div class="flex items-center justify-center mb-8">
            <div class="flex-grow h-px bg-gray-300"></div>
            <h2 class="px-4 text-lg font-bold text-tertiary-500 uppercase">Ulasan Pelanggan</h2>
            <div class="flex-grow h-px bg-gray-300"></div>
        </div>

        @if($avgRating)
        <p class="text-center text-sm text-gray-500 mb-6">
            ⭐ {{ number_format($avgRating, 1) }} / 5.0
            <span class="text-gray-400">({{ $ulasan->count() }} ulasan)</span>
        </p>
        @endif

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
    </section>
    @endif

    @if($ulasan->isEmpty())
    <p class="text-center text-sm text-gray-400 py-8">Belum ada ulasan untuk layanan ini.</p>
    @endif

    {{-- TOMBOL KEMBALI --}}
    <div class="flex justify-center pb-16">
        <a href="{{ route('service.index') }}"
            class="bg-[#3e3a34] text-white px-8 py-3 rounded-full text-sm hover:opacity-80 transition">
            ← Kembali ke Layanan
        </a>
    </div>

</div>

@endsection