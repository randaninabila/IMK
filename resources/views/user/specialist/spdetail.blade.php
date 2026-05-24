@extends('user.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .sp-detail * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .sp-detail .display { font-family: 'Playfair Display', serif; }

    .sp-card-wrap {
        border-radius: 20px;
        overflow: hidden;
        border: 1.5px solid #fce7e7;
        box-shadow: 0 12px 40px -8px rgba(233,108,108,0.12);
    }

    .jadwal-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        background: #fff0f0;
        border: 1px solid #fce7e7;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 500;
        color: #c47878;
        white-space: nowrap;
    }
    .jadwal-pill svg { width: 12px; height: 12px; flex-shrink: 0; }

    .book-btn {
        display: block;
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #3E382D 0%, #5a5347 100%);
        color: white;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        letter-spacing: 0.02em;
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }
    .book-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(62,56,45,0.35); }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px 16px;
        font-size: 12px;
    }
    .info-item { display: flex; align-items: flex-start; gap: 8px; color: #3E382D; }
    .info-item svg { width: 15px; height: 15px; color: #e9bcbc; flex-shrink: 0; margin-top: 1px; }
    .info-item .val { font-weight: 600; line-height: 1.3; }
    .info-item .lbl { color: #9ca3af; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 2px; }

    .layanan-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px 12px;
    }
    .layanan-item {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        color: #3E382D;
    }
    .layanan-item svg { width: 14px; height: 14px; color: #e9bcbc; flex-shrink: 0; }

    .section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #c47878;
        margin-bottom: 10px;
    }
</style>

<div class="sp-detail min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-[#faf7f5]">

    {{-- HERO --}}
    <div class="pt-28 md:pt-32 pb-6 px-4 text-center">
        <p class="text-[11px] font-bold tracking-widest text-[#c47878] uppercase mb-2">Profil Spesialis</p>
        <h1 class="display text-3xl md:text-5xl text-[#3E382D] font-semibold leading-tight">
            {{ $specialist->nama }}
        </h1>
        <p class="text-gray-400 text-sm">{{ $specialist->jabatan ?? 'Spesialis' }} · Salon Muslimah Dina</p>
    </div>

    <div class="max-w-4xl mx-auto px-4 pb-16"> 
        <div class="sp-card-wrap bg-white">

            <div class="relative w-full overflow-hidden" style="aspect-ratio: 24/9;"> {{-- ↑ Lebih wide --}}
                <img src="{{ $specialist->foto ? asset($specialist->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($specialist->nama) . '&background=FFE4E6&color=3E382D&size=400' }}"
                     alt="{{ $specialist->nama }}"
                     class="w-full h-full object-cover object-top">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>
                
                {{-- Badge Overlay --}}
                <div class="absolute bottom-4 left-5 right-5 flex items-end justify-between">
                    <div class="flex items-center gap-2">
                        @if($specialist->cabang_id)
                        <span class="inline-flex items-center gap-1 bg-white/90 backdrop-blur rounded-full px-3 py-1 text-xs font-semibold text-[#3E382D] shadow-sm">
                            📍 {{ $specialist->nama_cabang ?? 'Cabang' }}
                        </span>
                        @endif
                        <span class="text-xs bg-white/20 backdrop-blur text-white border border-white/30 px-3 py-1 rounded-full font-medium">
                            {{ $specialist->jabatan ?? 'Spesialis' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- KONTEN --}}
            <div class="p-6 space-y-6">

                {{-- Deskripsi --}}
                @if($specialist->deskripsi)
                <p class="text-gray-500 text-sm leading-relaxed">
                    {{ $specialist->deskripsi }}
                </p>
                @endif

                {{-- Info Kontak - 3 Kolom --}}
                <div class="bg-[#faf7f5] rounded-xl p-5">
                    <div class="info-grid">
                        @if($specialist->no_hp)
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <div><span class="lbl">Kontak</span><span class="val">{{ $specialist->no_hp }}</span></div>
                        </div>
                        @endif
                        @if($specialist->email)
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <div><span class="lbl">Email</span><span class="val">{{ $specialist->email }}</span></div>
                        </div>
                        @endif
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div><span class="lbl">Status</span><span class="val capitalize">{{ $specialist->status_kerja ?? 'Aktif' }}</span></div>
                        </div>
                    </div>
                </div>

                {{-- Jadwal & Layanan Side-by-Side --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    {{-- Jadwal --}}
                    <div>
                        <p class="section-title">Jadwal Tersedia</p>
                        @if($jadwal->isEmpty())
                            <p class="text-xs text-gray-400 italic">Belum ada jadwal tersedia.</p>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($jadwal as $tanggal => $sesi)
                                    @foreach($sesi as $s)
                                        @if($s->status_ketersediaan === 'tersedia')
                                        <div class="jadwal-pill">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M') }}
                                            · {{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}
                                        </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Layanan - GRID 2 KOLOM, SEMUA DITAMPILKAN --}}
                    @if($layananList->isNotEmpty())
                    <div>
                        <p class="section-title">Layanan</p>
                        <div class="layanan-grid"> {{-- ↑ Grid 2 kolom --}}
                            @foreach($layananList as $layanan)
                            <div class="layanan-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $layanan->nama_layanan }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- CTA Buttons - Side by Side --}}
                <div class="flex gap-3 pt-2">
                    @if($layananList->isNotEmpty())
                    <a href="{{ url('/service') }}" class="book-btn flex-1">
                        Pesan Sekarang
                    </a>
                    @endif
                    @if($specialist->no_hp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $specialist->no_hp) }}?text={{ urlencode('Halo ' . $specialist->nama . ', saya ingin booking layanan') }}"
                       target="_blank"
                       class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-[#fce7e7] text-sm font-semibold text-[#c47878] hover:bg-rose-50 transition-colors flex-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Tanya via WhatsApp
                    </a>
                    @endif
                </div>

            </div>
        </div>

        {{-- Back Link --}}
        <div class="mt-6 text-center">
            <a href="{{ url('/specialist') }}"
               class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-[#b89898] hover:text-[#3E382D] transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 12H5M5 12l7-7M5 12l7 7"/>
                </svg>
                Kembali ke Daftar Spesialis
            </a>
        </div>
    </div>

</div>

@endsection