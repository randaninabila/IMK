@extends('user.app')

@section('content')

 
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
 
    .service-page * { font-family: 'Plus Jakarta Sans', sans-serif; }
 
    .sticky-cta {
        position: sticky;
        top: 88px;
    }
 
    .booking-card {
        background: white;
        border-radius: 10px;
        border: 1.5px solid #fce7e7;
        overflow: hidden;
        box-shadow: 0 8px 40px -8px rgba(233, 108, 108, 0.15);
    }
 
    .branch-row {
        border-radius: 10px;
        transition: background 0.2s ease, transform 0.2s ease;
    }
    .branch-row:hover {
        background: #fff5f5;
        transform: translateX(4px);
    }
 
    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: rgba(255,255,255,0.15);
        border-radius: 100px;
        font-size: 12px;
        font-weight: 500;
        backdrop-filter: blur(8px);
    }
 
    .photo-thumb {
        overflow: hidden;
        border-radius: 10px;
    }
    .photo-thumb img {
        transition: transform 0.5s ease;
    }
    .photo-thumb:hover img {
        transform: scale(1.05);
    }
 
    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #e07b7b;
    }
 
    .star-filled { color: #f59e0b; }
    .star-empty  { color: #e5e7eb; }
 
    .rating-box {
        border-radius: 10px;
    }
 
    .review-card {
        border-radius: 10px;
    }
 
    .booking-btn {
        border-radius: 10px;
    }
 
    .wa-btn {
        border-radius: 10px;
    }
</style>

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

  {{-- ═══ MAIN CONTENT: 2 kolom ═══ --}}
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
 
            {{-- ── KOLOM KIRI (2/3): deskripsi + foto + ulasan ── --}}
            <div class="lg:col-span-2 space-y-12">
 
                {{-- Deskripsi --}}
                <div>
                    <p class="section-label mb-3">Tentang Layanan</p>
                    <p class="text-gray-600 leading-relaxed text-[15px]">
                        {{ $layanan->deskripsi ?? 'Layanan profesional untuk hasil terbaik.' }}
                    </p>
                </div>

                @if($albumFotos->isNotEmpty())
                <div>
                    <p class="section-label mb-5">Dokumentasi Hasil</p>
 
                    @if($fotoByTipe->has('before') || $fotoByTipe->has('after'))
                    <div class="grid grid-cols-2 gap-4 mb-6">
 
                        @if($fotoByTipe->has('before'))
                        <div>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Sebelum</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($fotoByTipe['before'] as $foto)
                                <div class="photo-thumb aspect-[3/4] bg-gray-100">
                                    <img src="{{ $foto->url_foto }}" alt="Sebelum"
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
 
                        @if($fotoByTipe->has('after'))
                        <div>
                            <p class="text-[11px] font-semibold text-rose-400 uppercase tracking-widest mb-2">Sesudah</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($fotoByTipe['after'] as $foto)
                                <div class="photo-thumb aspect-[3/4] bg-gray-100">
                                    <img src="{{ $foto->url_foto }}" alt="Sesudah"
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
 
                    </div>
                    @endif
                </div>
 
                @else
                <div class="py-16 text-center text-gray-300 rounded-xl bg-rose-50 border border-rose-100">
                    <svg class="w-12 h-12 mx-auto mb-3 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs text-gray-400">Foto treatment belum tersedia</p>
                </div>
                @endif
 
                {{-- Ulasan --}}
                @if($ulasan->isNotEmpty())
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <p class="section-label">Ulasan Pelanggan</p>
                        <span class="text-xs text-gray-400">{{ $ulasan->count() }} ulasan</span>
                    </div>
 
                    {{-- Rating summary --}}
                    <div class="rating-box flex items-center gap-4 p-5 bg-rose-50 border border-rose-100 mb-6">
                        <div class="text-center">
                            <div class="text-4xl font-extrabold text-rose-400 leading-none">
                                {{ number_format($avgRating, 1) }}
                            </div>
                            <div class="flex gap-0.5 mt-1 justify-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= round($avgRating) ? 'star-filled' : 'star-empty' }}"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <div class="w-px h-10 bg-rose-200"></div>
                        <p class="text-sm text-gray-500">
                            Rata-rata penilaian dari<br>
                            <span class="font-semibold text-[#1a1714]">{{ $ulasan->count() }} pelanggan</span>
                        </p>
                    </div>
 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($ulasan as $u)
                        <div class="review-card bg-white p-4 border border-gray-100 shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($u->nama_pelanggan, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#1a1714] truncate">{{ $u->nama_pelanggan }}</p>
                                    <p class="text-[10px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($u->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex gap-0.5 flex-shrink-0">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $u->rating ? 'star-filled' : 'star-empty' }}"
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
                @endif
 
            </div>
 
            {{-- ── KOLOM KANAN (1/3): sticky booking card ── --}}
            <div class="sticky-cta">
                <div class="booking-card">
 
                    {{-- Header card --}}
                    <div class="bg-gradient-to-br from-rose-400 to-pink-500 px-6 py-5">
                        <p class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-1">
                            Pilih Cabang & Booking
                        </p>
                        <p class="text-white font-bold text-lg leading-tight">
                            {{ $layanan->nama_layanan }}
                        </p>
                        <div class="flex items-center gap-2 mt-2 text-white/90 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $layanan->durasi }} menit
                        </div>
                    </div>
 
                    {{-- Daftar cabang --}}
                    @if($layananCabang->isNotEmpty())
                    <div class="p-4 space-y-2">
                        @foreach($layananCabang as $lc)
                        <div class="branch-row p-4 border border-gray-100">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#1a1714] truncate">{{ $lc->nama_cabang }}</p>
                                    @if($lc->alamat)
                                    <p class="text-[11px] text-gray-400 mt-0.5 leading-snug">{{ $lc->alamat }}</p>
                                    @endif
                                    <div class="mt-2">
                                        @if($lc->harga_promo)
                                        <p class="text-[11px] text-gray-300 line-through">
                                            Rp {{ number_format($lc->harga, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm font-bold text-rose-500">
                                            Rp {{ number_format($lc->harga_promo, 0, ',', '.') }}
                                        </p>
                                        @else
                                        <p class="text-sm font-bold text-[#1a1714]">
                                            Rp {{ number_format($lc->harga, 0, ',', '.') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
 
                                @if($lc->layanan_cabang_id)
                                <a href="{{ route('pelanggan.booking.create', $lc->layanan_cabang_id) }}"
                                   class="booking-btn flex-shrink-0 bg-rose-400 hover:bg-rose-500 active:scale-95 text-white text-xs font-bold px-4 py-2.5 transition-all duration-200">
                                    Booking
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="p-6 text-center text-sm text-gray-400">
                        Tidak ada cabang tersedia
                    </div>
                    @endif
 
                    {{-- Footer: WA --}}
                    <div class="px-4 pb-4">
                        <a href="https://wa.me/6287869590802" target="_blank"
                           class="wa-btn flex items-center justify-center gap-2 w-full py-3 border border-gray-200 text-xs font-semibold text-gray-500 hover:border-green-400 hover:text-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Tanya via WhatsApp
                        </a>
                    </div>
 
                </div>
            </div>
 
        </div>
    </div>
 
</div>
 
@endsection