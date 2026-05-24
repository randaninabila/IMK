@extends('user.app')

@section('content')

<div class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white text-[#3E382D]">

    {{-- HERO --}}
<section class="relative h-[450px] flex items-center overflow-hidden w-full">
    <div class="absolute inset-0 w-full">
        <img src="{{ $layanan->cover_foto }}"
             alt="{{ $layanan->nama_layanan }}"
             class="w-full h-full object-cover object-center"
             onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">

        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-black/10"></div>
    </div>

    <div class="container mx-auto px-10 relative z-10 text-white text-right">
        <div class="max-w-xl ml-auto">

            <a href="{{ route('service.detail', $layanan->jenis_layanan_id) }}"
               class="text-xs text-pink-300 hover:text-pink-200 mb-3 flex items-center justify-end gap-1 transition">
                {{ $jenisLayanan->nama_jenis }}

                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <h1 class="text-4xl font-bold mb-3 leading-tight">
                {{ $layanan->nama_layanan }}
            </h1>

            <p class="text-sm opacity-90 max-w-md ml-auto leading-relaxed">
                {{ $layanan->deskripsi ?? 'Layanan profesional untuk hasil terbaik.' }}
            </p>

            <div class="flex items-center justify-end gap-6 mt-4 text-sm opacity-80">

                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                    {{ $layanan->durasi }} menit
                </span>

                @if($layanan->harga_promo)
                    <span class="line-through opacity-60">
                        Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                    </span>

                    <span class="font-bold text-pink-300 text-base">
                        Rp {{ number_format($layanan->harga_promo, 0, ',', '.') }}
                    </span>
                @else
                    <span class="font-bold text-pink-300 text-base">
                        Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}
                    </span>
                @endif

            </div>
        </div>
    </div>
</section>

    {{-- HARGA PER CABANG --}}
@if($layananCabang->isNotEmpty())
<section class="bg-white border-b border-pink-100 py-6 shadow-sm">

    <div class="container mx-auto px-6">

        <div class="flex items-center justify-center mb-6">
            <div class="flex-grow h-px bg-pink-100"></div>

            <h2 class="px-4 text-sm font-bold text-[#3E382D] uppercase tracking-widest whitespace-nowrap">
                Harga Tiap Cabang
            </h2>

            <div class="flex-grow h-px bg-pink-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @foreach($layananCabang as $lc)

            <div class="bg-[#FFF8F8] border border-pink-100 rounded-2xl p-5 flex justify-between items-center">

                <div>
                    <h3 class="font-bold text-[#3E382D] text-sm">
                        {{ $lc->nama_cabang }}
                    </h3>

                    @if($lc->alamat)
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $lc->alamat }}
                    </p>
                    @endif

                    <div class="mt-3">

                        @if($lc->harga_promo)

                            <p class="text-xs text-gray-400 line-through">
                                Rp {{ number_format($lc->harga, 0, ',', '.') }}
                            </p>

                            <p class="text-lg font-bold text-rose-400">
                                Rp {{ number_format($lc->harga_promo, 0, ',', '.') }}
                            </p>

                        @else

                            <p class="text-lg font-bold text-[#3E382D]">
                                Rp {{ number_format($lc->harga, 0, ',', '.') }}
                            </p>

                        @endif

                    </div>
                </div>

                {{-- BUTTON --}}
                @if($lc->layanan_cabang_id)
                    <a href="{{ route('pelanggan.booking.create', $lc->layanan_cabang_id) }}"
                    class="bg-rose-400 hover:bg-rose-500 text-white text-sm font-bold px-5 py-2 rounded-full transition">
                        Booking
                    </a>
                @endif

            </div>

            @endforeach

        </div>

    </div>

</section>
@endif

    {{-- BEFORE & AFTER --}}
    @if($albumFotos->isNotEmpty())
    <section class="py-14 container mx-auto px-6">

        <div class="flex items-center justify-center mb-10">
            <div class="flex-grow h-px bg-gray-200"></div>
            <h2 class="px-4 text-lg font-bold text-[#3E382D] uppercase whitespace-nowrap tracking-wide">
                Hasil Treatment
            </h2>
            <div class="flex-grow h-px bg-gray-200"></div>
        </div>

        {{-- Before & After berdampingan --}}
        @if($fotoByTipe->has('before') || $fotoByTipe->has('after'))
        <div class="max-w-5xl mx-auto mb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Before --}}
                @if($fotoByTipe->has('before'))
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-3 h-3 rounded-full bg-gray-400 inline-block"></span>
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Sebelum</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($fotoByTipe['before'] as $foto)
                        <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden border-2 border-rose-200">
                            <img src="{{ $foto->url_foto }}"
                                 alt="Before {{ $layanan->nama_layanan }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                 onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- After --}}
                @if($fotoByTipe->has('after'))
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span>
                        <span class="text-sm font-bold text-rose-400 uppercase tracking-widest">Sesudah</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($fotoByTipe['after'] as $foto)
                        <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden border-2 border-rose-200">
                            <img src="{{ $foto->url_foto }}"
                                 alt="After {{ $layanan->nama_layanan }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                 onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endif

        {{-- Result / Catalog --}}
        @if($fotoByTipe->has('result') || $fotoByTipe->has('catalog'))
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-3 h-3 rounded-full bg-pink-400 inline-block"></span>
                <span class="text-sm font-bold text-pink-500 uppercase tracking-widest">Hasil Akhir</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($fotoByTipe->only(['result', 'catalog'])->flatten() as $foto)
                <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden border-2 border-pink-100">
                    <img src="{{ $foto->url_foto }}"
                         alt="Result {{ $layanan->nama_layanan }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                         onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </section>
    @else
    <section class="py-20 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-4 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-sm">Foto treatment belum tersedia</p>
    </section>
    @endif

    {{-- ULASAN --}}
    @if($ulasan->isNotEmpty())
    <section class="py-14 bg-pink-50/60">
        <div class="container mx-auto px-6 max-w-5xl">

            <div class="flex items-center justify-center mb-10">
                <div class="flex-grow h-px bg-gray-200"></div>
                <h2 class="px-4 text-lg font-bold text-[#3E382D] uppercase whitespace-nowrap tracking-wide">
                    Ulasan Pelanggan
                </h2>
                <div class="flex-grow h-px bg-gray-200"></div>
            </div>

            {{-- Rating rata-rata --}}
            <div class="text-center mb-10">
                <div class="text-5xl font-bold text-rose-400 mb-1">{{ number_format($avgRating, 1) }}</div>
                <div class="flex justify-center gap-1 mb-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-xs text-gray-400">dari {{ $ulasan->count() }} ulasan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($ulasan as $u)
                <div class="bg-white rounded-2xl p-5 border border-pink-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($u->nama_pelanggan, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#3E382D]">{{ $u->nama_pelanggan }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($u->created_at)->diffForHumans() }}</p>
                        </div>
                        <div class="ml-auto flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $u->rating ? 'text-yellow-400' : 'text-gray-200' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed italic">
                        "{{ $u->komentar ?? 'Tidak ada komentar.' }}"
                    </p>
                </div>
                @endforeach
            </div>

        </div>
    </section>
    @endif

</div>

@endsection