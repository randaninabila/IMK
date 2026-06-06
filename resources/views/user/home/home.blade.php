@extends('user.app')

@section('content')

@php
    $activePromo = $activePromo ?? null;

    $promoTitle = $activePromo->judul_promo
        ?? $activePromo->nama_promo
        ?? $activePromo->nama_layanan
        ?? 'Promo Salon Dina';

    $promoDescription = $activePromo->deskripsi_promo
        ?? $activePromo->deskripsi
        ?? 'Promo layanan pilihan khusus untuk pelanggan Salon Muslimah Dina.';

    $promoService = $activePromo->nama_layanan
        ?? $activePromo->layanan
        ?? '-';

    $promoCategory = $activePromo->jenis_layanan
        ?? $activePromo->kategori
        ?? '-';

    $promoBranch = $activePromo->nama_cabang
        ?? $activePromo->cabang
        ?? '-';

    $promoNormalRaw = $activePromo->harga_normal
        ?? $activePromo->harga
        ?? null;

    $promoPriceRaw = $activePromo->harga_promo
        ?? $activePromo->harga_diskon
        ?? null;

    $promoNormal = is_numeric($promoNormalRaw)
        ? 'Rp ' . number_format($promoNormalRaw, 0, ',', '.')
        : ($promoNormalRaw ?? '-');

    $promoPrice = is_numeric($promoPriceRaw)
        ? 'Rp ' . number_format($promoPriceRaw, 0, ',', '.')
        : ($promoPriceRaw ?? '-');

    $hasActivePromo = !empty($activePromo);

    $discountPercent = null;

    if (
        $hasActivePromo &&
        is_numeric($promoNormalRaw) &&
        is_numeric($promoPriceRaw) &&
        (float) $promoNormalRaw > 0 &&
        (float) $promoPriceRaw < (float) $promoNormalRaw
    ) {
        $discountPercent = round((($promoNormalRaw - $promoPriceRaw) / $promoNormalRaw) * 100);
    }
@endphp

{{-- ✨ GLOBAL STYLES FOR ANIMATIONS --}}
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-pulse-soft { animation: pulse-soft 3s ease-in-out infinite; }

    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }

    html {
        scroll-behavior: smooth;
    }

    .glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(241, 223, 223, 0.5);
    }

    .service-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .service-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(236, 72, 153, 0.25);
    }

    .feature-card {
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -10px rgba(62, 56, 45, 0.15);
    }

    .text-gradient {
        background: linear-gradient(135deg, #3E382D 0%, #F472B6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .pattern-dots {
        background-image: radial-gradient(#F472B6 1px, transparent 1px);
        background-size: 30px 30px;
        opacity: 0.1;
    }
</style>

{{-- 🌟 HERO SECTION - Premium & Elegan --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-[#FFF1F2] via-white to-[#FFE4E6]">
    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 pattern-dots"></div>
    <div class="absolute top-20 right-10 w-72 h-72 bg-gradient-to-br from-rose-200/40 to-pink-200/40 rounded-full blur-3xl animate-pulse-soft"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-gradient-to-tr from-rose-100/30 to-pink-100/30 rounded-full blur-3xl animate-pulse-soft" style="animation-delay: 1s;"></div>

    {{-- Floating Decorative Icons --}}
    <div class="absolute top-32 left-20 text-rose-300/60 animate-float" style="animation-delay: 0s;">
        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
    </div>

    <div class="absolute bottom-40 right-32 text-pink-300/60 animate-float" style="animation-delay: 2s;">
        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
    </div>

    <div class="absolute top-1/2 left-5 text-rose-200/50 animate-float" style="animation-delay: 1.5s;">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
        </svg>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        {{-- Left Content --}}
        <div class="space-y-8 animate-fade-in-up">
            <h1 class="text-5xl lg:text-5xl font-bold leading-tight">
                <span class="text-[#3E382D]">Cantik dari</span><br>
                <span class="text-gradient">Dalam & Luar</span>
            </h1>

            <p class="text-lg text-gray-600 leading-relaxed max-w-lg">
                Nikmati pengalaman perawatan eksklusif di
                <span class="font-semibold text-[#3E382D]">Salon Muslimah Dina</span> —
                ruang aman, nyaman, dan penuh keanggunan khusus untuk perempuan muslimah.
            </p>

            <div class="flex items-center gap-6 pt-6 border-t border-rose-100">
                <div class="flex items-center gap-2">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-200 to-pink-200 border-2 border-white"></div>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-200 to-rose-200 border-2 border-white"></div>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 border-2 border-white flex items-center justify-center text-xs font-medium text-[#3E382D]">
                            +2k
                        </div>
                    </div>

                    <span class="text-sm text-gray-500">Pelanggan Puas</span>
                </div>

                <div class="w-px h-8 bg-rose-200"></div>

                <div>
                    <p class="text-xs text-gray-400">Penilaian</p>
                    <p class="text-sm font-bold text-[#3E382D]">
                        {{ $avgRating }}/5 ⭐
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Content --}}
        <div class="relative flex justify-center lg:justify-end animate-fade-in-up delay-200">
            <div class="relative">
                <div class="absolute -inset-4 bg-gradient-to-r from-rose-200 via-pink-200 to-rose-200 rounded-[2.5rem] blur-2xl opacity-40 animate-pulse-soft"></div>

                <div class="relative w-80 h-80 lg:w-[450px] lg:h-[450px] bg-white rounded-[10px] shadow-2xl border border-rose-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-50 via-pink-50 to-white flex flex-col items-center justify-center p-8">
                        <div class="relative flex h-[150px] w-[150px] items-center justify-center rounded-[30px] bg-white/70 border border-[#F1D6DE] shadow-[0_15px_35px_rgba(232,168,200,0.18)]">
                            @php
                                $salonLogo = $salon->logo ?? null;
                                $logoUrl = $salonLogo
                                    ? (str_starts_with($salonLogo, 'http') ? $salonLogo : asset($salonLogo))
                                    : null;
                            @endphp

                            <div class="flex h-[150px] w-[150px] items-center justify-center overflow-hidden rounded-[22px] bg-[#F3A7D4] border border-white/80 shadow-[0_10px_25px_rgba(232,168,200,0.28)]">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}"
                                         alt="{{ $salon->nama ?? 'Logo Salon' }}"
                                         class="h-[142px] w-[142px] object-contain p-1"
                                         onerror="this.parentElement.innerHTML='<svg class=\'w-[118px] h-[118px] text-rose-300\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'0.5\' d=\'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/><circle cx=\'12\' cy=\'10\' r=\'2.5\' fill=\'currentColor\' class=\'text-rose-200\'/></svg>'">
                                @else
                                    <svg class="w-[118px] h-[118px] text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        <circle cx="12" cy="10" r="2.5" fill="currentColor" class="text-rose-200"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="absolute -top-3 -right-5 bg-gradient-to-r from-rose-400 to-pink-400 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg animate-bounce" style="animation-duration: 3s;">
                                ✨ Unggulan
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-lg font-semibold text-[#3E382D]">Salon Muslimah Dina</p>
                            <p class="text-sm text-gray-400 mt-1">Elegan • Nyaman • Terpercaya</p>
                        </div>

                        <div class="absolute bottom-6 left-6 right-6 flex justify-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-rose-300"></div>
                            <div class="w-2 h-2 rounded-full bg-pink-300"></div>
                            <div class="w-2 h-2 rounded-full bg-rose-200"></div>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-6 -left-8 glass rounded-2xl p-4 shadow-xl animate-fade-in-up delay-300">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Terapis</p>
                            <p class="text-sm font-bold text-[#3E382D]">Berpengalaman</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -top-4 -right-6 glass rounded-2xl p-4 shadow-xl animate-fade-in-up delay-400">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Penilaian</p>
                            <p class="text-sm font-bold text-[#3E382D]">
                                {{ $avgRating }}/5 ⭐
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 🏆 KENAPA PILIH KAMI - Fitur Unggulan --}}
<section class="relative py-24 px-6 bg-gradient-to-b from-[#FFF1F2] to-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full border border-rose-200 mb-4">
                <span class="text-rose-400">✦</span>
                <span class="text-xs font-semibold text-[#3E382D] uppercase tracking-wider">Keunggulan</span>
            </div>

            <h2 class="text-4xl lg:text-5xl font-bold text-[#3E382D] mb-4">
                Kenapa Memilih <span class="text-gradient">Kami?</span>
            </h2>

            <p class="text-gray-500 max-w-2xl mx-auto">
                Komitmen kami untuk memberikan pengalaman terbaik dengan standar premium
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $features = [
                    [
                        'icon' => '🕌',
                        'title' => 'Privat & Aman',
                        'desc' => 'Ruangan tertutup khusus muslimah, bebas hijab friendly, dan lingkungan yang nyaman untuk beribadah.',
                        'bg' => 'from-rose-50 to-pink-50'
                    ],
                    [
                        'icon' => '👩‍⚕️',
                        'title' => 'Terapis Profesional',
                        'desc' => 'Tim bersertifikat dengan pengalaman bertahun-tahun, menggunakan teknik terbaru untuk hasil maksimal.',
                        'bg' => 'from-pink-50 to-rose-50'
                    ],
                    [
                        'icon' => '🌿',
                        'title' => 'Produk Premium',
                        'desc' => 'Menggunakan produk halal, alami, dan berkualitas tinggi yang aman untuk kulit dan rambut.',
                        'bg' => 'from-rose-50 to-pink-50'
                    ],
                ];
            @endphp

            @foreach($features as $index => $feature)
                <div class="feature-card glass rounded-3xl p-8 hover:shadow-xl cursor-default animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $feature['bg'] }} rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm">
                        {{ $feature['icon'] }}
                    </div>

                    <h3 class="font-bold text-xl text-[#3E382D] mb-3">
                        {{ $feature['title'] }}
                    </h3>

                    <p class="text-gray-500 leading-relaxed">
                        {{ $feature['desc'] }}
                    </p>

                    <div class="mt-6 w-12 h-1 bg-gradient-to-r from-rose-300 to-pink-300 rounded-full"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 💬 BUTTON TESTIMONI --}}
<section class="relative bg-white pt-4 pb-20 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col items-center justify-center text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-rose-50 rounded-full mb-5 border border-rose-100">
                <span class="text-rose-400">✦</span>
                <span class="text-xs font-semibold text-rose-500 uppercase tracking-wider">Testimoni</span>
            </div>

            <h2 class="text-3xl lg:text-4xl font-bold text-[#3E382D] mb-4">
                Lihat Cerita <span class="text-gradient">Klien Kami</span>
            </h2>

            <p class="text-gray-500 max-w-2xl mx-auto mb-8">
                Baca pengalaman pelanggan yang sudah menikmati layanan Salon Muslimah Dina.
            </p>

            <a href="{{ url('/testimoni') }}"
               class="inline-flex items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-rose-400 to-pink-400 px-9 py-4 text-base font-bold text-white shadow-xl shadow-rose-200/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-rose-300/60">
                <span>Lihat Testimoni</span>

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- 📍 BRANCHES SECTION - Elegant Locations --}}
<section class="relative py-24 px-6 bg-white">
    <div class="absolute inset-0 pattern-dots opacity-50"></div>

    <div class="relative max-w-7xl mx-auto">
        <div class="text-center mb-16 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-rose-50 rounded-full mb-4">
                <span class="text-rose-400">✦</span>
                <span class="text-xs font-semibold text-rose-500 uppercase tracking-wider">Lokasi</span>
            </div>

            <h2 class="text-4xl lg:text-5xl font-bold text-[#3E382D] mb-4">
                Cabang <span class="text-gradient">Kami</span>
            </h2>

            <p class="text-gray-500 max-w-2xl mx-auto">
                Temukan salon terdekat dari kamu dengan fasilitas lengkap dan nyaman
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @if(isset($cabangList) && $cabangList->count())
                @foreach($cabangList as $index => $cabang)
                    <div class="feature-card group relative bg-gradient-to-br from-white to-rose-50/50 rounded-3xl p-8 border border-rose-100 hover:border-rose-200 hover:shadow-xl transition-all duration-300 animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="absolute top-6 right-6 w-12 h-12 bg-gradient-to-br from-rose-100 to-pink-100 rounded-xl opacity-50 group-hover:opacity-100 transition-opacity"></div>

                        <div class="relative z-10">
                            <div class="flex items-start gap-4 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-pink-400 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>

                                <div>
                                    <h3 class="font-bold text-xl text-[#3E382D]">
                                        {{ $cabang->nama_cabang }}
                                    </h3>
                                    @if($cabang->is_buka_realtime)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-600 text-xs font-semibold rounded-full mt-2">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                            Buka Sekarang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-full mt-2">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Tutup
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-gray-500 leading-relaxed mb-6">
                                {{ $cabang->alamat }}
                            </p>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>

                                    <span class="text-gray-600">09:00 - 19:00 WIB</span>
                                </div>

                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>

                                    <span class="text-gray-600">0878-6959-0802</span>
                                </div>
                            </div>

                            <a href="https://wa.me/6287869590802?text=Halo%20Salon%20Dina,%20saya%20ingin%20booking%20di%20{{ urlencode($cabang->nama_cabang) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-[#3E382D] hover:bg-[#3E382D]/90 text-white font-semibold rounded-xl transition-all duration-300 hover:-translate-y-0.5 shadow-md">
                                Pesan via WhatsApp
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-1 lg:col-span-2 text-center bg-rose-50 rounded-3xl p-10 border border-rose-100">
                    <p class="font-semibold text-[#3E382D]">
                        Belum ada cabang aktif yang tersedia.
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- 🎁 CTA AKHIR - Ajakan Bertindak --}}
<section class="relative py-24 px-6 bg-gradient-to-r from-[#3E382D] via-[#4A4343] to-[#3E382D] overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-rose-300 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-300 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-4xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6 border border-white/20">
            <span class="text-rose-300">✨</span>
            <span class="text-xs font-medium text-rose-100 uppercase tracking-wide">Penawaran Spesial</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
            Siap Tampil <span class="text-rose-300">Cantik</span><br>Hari Ini?
        </h2>

        <p class="text-lg text-rose-100/90 mb-10 max-w-2xl mx-auto leading-relaxed">
            Pesan sekarang dan dapatkan
            <span class="font-semibold text-white">konsultasi gratis</span>
            dengan terapis profesional kami.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/service') }}"
               class="group inline-flex items-center gap-3 bg-white text-[#3E382D] font-bold px-10 py-5 rounded-2xl hover:bg-rose-50 transition-all duration-300 shadow-2xl hover:shadow-white/30 hover:-translate-y-1">
                <span>Pesan Sekarang</span>

                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>

            <a href="https://wa.me/6287869590802"
               target="_blank"
               class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl border-2 border-white/30 text-white font-semibold hover:bg-white/10 transition-all duration-300">
                <span>Hubungi WhatsApp</span>
            </a>
        </div>
    </div>
</section>

{{-- 🎁 BADGE PROMO MENGAMBANG --}}
@php
    // ✅ Fallback jika variabel tidak dikirim dari controller
    $promos = $promos ?? collect();
    $totalPromo = $totalPromo ?? $promos->count();
    $maxDiskon = $promos->max('diskon') ?? 0;
@endphp

<div class="fixed bottom-[96px] right-6 z-50 animate-fade-in-up delay-300 {{ $totalPromo === 0 ? 'hidden' : '' }}">
    <button type="button"
            onclick="openPromoModal()"
            class="group flex items-center gap-3 px-5 py-4 bg-white text-[#3E382D] font-bold rounded-2xl shadow-2xl border border-rose-100 hover:-translate-y-1 hover:shadow-rose-300/40 transition-all duration-300">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-rose-400 to-pink-400 text-white text-xl font-black relative">
            %
            @if($totalPromo > 0)
                <span class="absolute -top-2 -right-2 w-5 h-5 bg-white text-rose-500 text-xs font-bold rounded-full flex items-center justify-center border-2 border-rose-400">
                    {{ $totalPromo }}
                </span>
            @endif
        </span>

        <span class="text-left leading-tight">
            <span class="block text-sm">
                {{ $totalPromo }} Promo Aktif
            </span>
            <span class="block text-xs font-semibold text-rose-500">
                Hemat hingga {{ $maxDiskon }}%
            </span>
        </span>
    </button>
</div>

{{-- ✨ BADGE BAWAH - Tombol Mengambang --}}
<div class="fixed bottom-6 right-6 z-50 animate-fade-in-up delay-400">
    <a href="{{ url('/service') }}"
       class="group flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-rose-400 to-pink-400 text-white font-semibold rounded-2xl shadow-2xl hover:shadow-rose-300/50 transition-all duration-300 hover:-translate-y-1">
        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>

        <span>Pesan Sekarang</span>
    </a>
</div>

    {{-- MODAL PROMO --}}
<div id="promoModal"
     onclick="closePromoModalByOverlay(event)"
     class="hidden fixed inset-0 z-[9999] items-center justify-center bg-black/40 px-4 sm:px-6 overflow-y-auto">

    <div class="relative w-full max-w-[560px] rounded-[28px] bg-white shadow-2xl overflow-hidden mt-[150px] mb-10">
        {{-- Close Button --}}
        <button type="button"
                onclick="closePromoModal()"
                class="absolute right-4 top-4 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="bg-gradient-to-br from-[#FFF1F2] via-white to-[#FFE4E6] px-6 py-8 sm:px-8 sm:py-9">
            {{-- Header --}}
            <div class="flex items-start gap-4 mb-6">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-400 to-pink-400 text-white text-2xl font-black shadow-lg">
                    %
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-500">Promo Salon Dina</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-[#3E382D] leading-tight">
                        @if($promos->isNotEmpty()) Promo Spesial! @else Belum Ada Promo @endif
                    </h2>
                </div>
            </div>

            {{--  FILTER CABANG (PILL BUTTONS) --}}
            @if($cabangList->isNotEmpty())
            <div class="mb-5">
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Pilih Cabang</p>
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide" id="branchFilterBtns">
                    <button class="branch-pill active px-4 py-2 rounded-full text-xs font-bold bg-rose-500 text-white shadow-sm transition whitespace-nowrap" data-value="all">
                        Semua
                    </button>
                    @foreach($cabangList as $cabang)
                        <button class="branch-pill px-4 py-2 rounded-full text-xs font-bold bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 transition whitespace-nowrap" data-value="{{ $cabang->nama_cabang }}">
                            {{ $cabang->nama_cabang }}
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- List Promo (Scrollable) --}}
            <div class="max-h-[350px] overflow-y-auto pr-2 custom-scrollbar" id="promoListContainer">
                @if($promos->isNotEmpty())
                    <div class="space-y-3">
@foreach($promos as $promo)
    <a href="{{ route('pelanggan.booking.create', $promo->layanan_cabang_id) }}"
   class="promo-item flex items-center justify-between gap-3 p-4 rounded-2xl bg-white border border-gray-100 hover:border-rose-200 hover:shadow-md hover:scale-[1.01] transition-all cursor-pointer block text-left" 
   data-cabang="{{ $promo->cabang }}">
        
        <div class="flex-1 min-w-0">
            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold mb-1.5 bg-blue-100 text-blue-600">
                {{ $promo->kategori }}
            </span>
            <p class="text-sm font-bold text-[#3E382D] truncate">{{ $promo->nama }}</p>
            <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                📍 {{ $promo->cabang }}
            </p>
        </div>

        <div class="text-right shrink-0 pl-2">
            <p class="text-xs text-gray-400 line-through">Rp {{ number_format($promo->harga_normal, 0, ',', '.') }}</p>
            <p class="text-base font-black text-rose-500">Rp {{ number_format($promo->harga_promo, 0, ',', '.') }}</p>
            <span class="inline-block mt-1 px-1.5 py-0.5 rounded bg-rose-100 text-[10px] font-bold text-rose-600">
                -{{ $promo->diskon }}%
            </span>
        </div>
    </a>
@endforeach
                    </div>
                    
                    {{-- Empty State (Hidden by default) --}}
                    <div id="noPromoMsg" class="hidden text-center py-8">
                        <p class="text-gray-400 text-sm">Tidak ada promo di cabang ini saat ini.</p>
                    </div>

                @else
                    <div class="py-8 text-center">
                        <p class="text-4xl mb-2">🎁</p>
                        <p class="text-gray-500 font-medium">Belum ada promo aktif.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-6 py-4 flex justify-end">
            <button onclick="closePromoModal()" class="px-6 py-2 rounded-xl bg-rose-100 text-rose-600 font-bold hover:bg-rose-200 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

{{-- ✨ INTERACTIVE SCRIPTS --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. SMOOTH SCROLL FOR ANCHOR LINKS
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // 2. FILTER CABANG PROMO LOGIC (SUDAH DIPERBAIKI)
    const pills = document.querySelectorAll('.branch-pill');
    const items = document.querySelectorAll('.promo-item');
    const emptyMsg = document.getElementById('noPromoMsg');

    pills.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            // Atur style tombol aktif
            pills.forEach(btn => {
                btn.classList.remove("bg-rose-500", "text-white", "shadow-sm", "active");
                btn.classList.add("bg-white", "border", "border-rose-200", "text-rose-500");
            });
            this.classList.remove("bg-white", "border", "border-rose-200", "text-rose-500");
            this.classList.add("bg-rose-500", "text-white", "shadow-sm", "active");

            // Ambil value filter
            const selectedBranch = this.getAttribute("data-value").trim(); 
            let visibleCount = 0;
            
            items.forEach(item => {
                const itemBranch = item.getAttribute("data-cabang").trim();

                if (selectedBranch === "all" || itemBranch === selectedBranch) {
                    item.style.setProperty('display', 'flex', 'important'); 
                    visibleCount++;
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });

            // Tampilkan pesan jika tidak ada promo di cabang terpilih
            if (visibleCount === 0) {
                if (emptyMsg) emptyMsg.style.setProperty('display', 'block', 'important');
            } else {
                if (emptyMsg) emptyMsg.style.setProperty('display', 'none', 'important');
            }
        });
    });

    // 3. INTERSECTION OBSERVER FOR ANIMATIONS
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-fade-in-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // 4. ESCAPE KEY TO CLOSE MODAL
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePromoModal();
        }
    });
});

// 5. MODAL CONTROL FUNCTIONS (SINKRON & RAPI)
function openPromoModal() {
    const modal = document.getElementById('promoModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closePromoModal() {
    const modal = document.getElementById('promoModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

function closePromoModalByOverlay(event) {
    if (event.target === event.currentTarget) {
        closePromoModal();
    }
}
</script>
@endpush