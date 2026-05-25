@extends('user.app')

@section('content')

{{-- HERO --}}
<section class="relative min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white overflow-hidden flex items-center">

    {{-- Dekorasi bulat background --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-rose-100 rounded-full opacity-40 translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[350px] h-[350px] bg-pink-100 rounded-full opacity-30 -translate-x-1/4 translate-y-1/4 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-6 py-24 mt-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">

        {{-- Teks kiri --}}
        <div>
            <span class="inline-block bg-rose-100 text-rose-500 text-xs font-semibold px-4 py-1.5 rounded-full mb-4 tracking-wide">
                ✨ Salon Muslimah Terpercaya
            </span>
            <h1 class="text-5xl lg:text-6xl font-bold text-[#3E382D] leading-tight mb-5">
                Cantik dari<br>
                <span class="text-rose-400">Dalam & Luar</span>
            </h1>
            <p class="text-gray-500 text-base leading-relaxed mb-8 max-w-md">
                Nikmati perawatan rambut, wajah, dan tubuh terbaik di Salon Muslimah Dina — nyaman, amanah, dan khusus untuk perempuan muslimah.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('pelanggan.layanan.index') ?? '/layanan' }}"
                   class="inline-flex items-center gap-2 bg-rose-400 hover:bg-rose-500 text-white font-semibold px-7 py-3.5 rounded-2xl transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Booking Sekarang
                </a>
                <a href="#layanan"
                   class="inline-flex items-center gap-2 border border-pink-200 text-[#3E382D] hover:bg-pink-50 font-semibold px-7 py-3.5 rounded-2xl transition">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </div>

            {{-- Stats kecil --}}
            <div class="flex gap-8 mt-10">
                <div>
                    <p class="text-2xl font-bold text-[#3E382D]">2+</p>
                    <p class="text-xs text-gray-400 mt-0.5">Cabang Aktif</p>
                </div>
                <div class="border-l border-pink-200 pl-8">
                    <p class="text-2xl font-bold text-[#3E382D]">30+</p>
                    <p class="text-xs text-gray-400 mt-0.5">Jenis Layanan</p>
                </div>
                <div class="border-l border-pink-200 pl-8">
                    <p class="text-2xl font-bold text-[#3E382D]">100%</p>
                    <p class="text-xs text-gray-400 mt-0.5">Khusus Muslimah</p>
                </div>
            </div>
        </div>

        {{-- Ilustrasi / visual kanan --}}
        <div class="relative flex justify-center">
            <div class="w-72 h-72 lg:w-96 lg:h-96 bg-white rounded-full shadow-xl border border-pink-100 flex items-center justify-center overflow-hidden relative">
                {{-- Placeholder ilustrasi salon muslimah --}}
                <div class="absolute inset-0 bg-gradient-to-br from-rose-50 to-pink-100 flex flex-col items-center justify-center gap-4">
                    <svg class="w-28 h-28 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-rose-300 text-sm font-medium">Salon Muslimah Dina</p>
                </div>
                {{-- Jika ada gambar utama salon, uncomment ini: --}}
                {{-- <img src="{{ asset('images/hero-salon.jpg') }}" class="w-full h-full object-cover"> --}}
            </div>
            {{-- Badge floating --}}
            <div class="absolute -bottom-2 -left-4 bg-white rounded-2xl shadow-md border border-pink-100 px-4 py-3 flex items-center gap-2">
                <span class="text-xl">⭐</span>
                <div>
                    <p class="text-xs font-bold text-[#3E382D]">Rating 4.9</p>
                    <p class="text-[10px] text-gray-400">Dari pelanggan setia</p>
                </div>
            </div>
            <div class="absolute -top-4 -right-2 bg-rose-400 rounded-2xl shadow-md px-4 py-3 text-white text-center">
                <p class="text-xs font-bold">Buka Setiap Hari</p>
                <p class="text-[10px] opacity-80">09.00 – 19.00 WIB</p>
            </div>
        </div>

    </div>
</section>

{{-- LAYANAN UNGGULAN --}}
<section id="layanan" class="bg-white py-20 px-6">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-12">
            <span class="inline-block bg-rose-50 text-rose-400 text-xs font-semibold px-4 py-1.5 rounded-full mb-3">Layanan Kami</span>
            <h2 class="text-4xl font-bold text-[#3E382D]">Layanan Unggulan</h2>
            <p class="text-gray-400 text-sm mt-2">Perawatan terbaik yang kami tawarkan untuk kamu</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $layananUnggulan = [
                    ['icon' => '✂️', 'nama' => 'Gunting Rambut', 'desc' => 'Potongan rapi & modern'],
                    ['icon' => '💆', 'nama' => 'Creambath', 'desc' => 'Perawatan rambut intensif'],
                    ['icon' => '💅', 'nama' => 'Manicure & Pedicure', 'desc' => 'Kuku cantik terawat'],
                    ['icon' => '✨', 'nama' => 'Facial', 'desc' => 'Kulit glowing bersinar'],
                    ['icon' => '🌿', 'nama' => 'Hair Smoothing', 'desc' => 'Rambut lurus berkilau'],
                    ['icon' => '🎨', 'nama' => 'Pewarnaan Rambut', 'desc' => 'Warna impianmu'],
                    ['icon' => '💎', 'nama' => 'Waxing', 'desc' => 'Bersih & nyaman'],
                    ['icon' => '🧴', 'nama' => 'Hair Spa', 'desc' => 'Relaksasi total'],
                ];
            @endphp

            @foreach($layananUnggulan as $item)
            <div class="bg-gradient-to-b from-[#FFF1F2] to-white rounded-3xl border border-pink-100 p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all duration-200 cursor-pointer group">
                <div class="text-4xl mb-3">{{ $item['icon'] }}</div>
                <h3 class="font-semibold text-[#3E382D] text-sm mb-1 group-hover:text-rose-500 transition">{{ $item['nama'] }}</h3>
                <p class="text-xs text-gray-400">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('pelanggan.layanan.index') ?? '/layanan' }}"
               class="inline-flex items-center gap-2 border border-rose-200 text-rose-400 hover:bg-rose-50 font-semibold px-6 py-3 rounded-2xl transition text-sm">
                Lihat Semua Layanan
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>
</section>

{{-- KENAPA PILIH KAMI --}}
<section class="bg-gradient-to-b from-white to-[#FFF1F2] py-20 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-12">
            <span class="inline-block bg-rose-50 text-rose-400 text-xs font-semibold px-4 py-1.5 rounded-full mb-3">Keunggulan</span>
            <h2 class="text-4xl font-bold text-[#3E382D]">Kenapa Pilih Kami?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl border border-pink-100 p-7 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-2xl mb-4">🕌</div>
                <h3 class="font-bold text-[#3E382D] mb-2">Khusus Muslimah</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Ruangan tertutup dan aman, hanya untuk pelanggan wanita. Kamu bisa bebas berpenampilan tanpa khawatir.</p>
            </div>
            <div class="bg-white rounded-3xl border border-pink-100 p-7 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-2xl mb-4">👩‍🔬</div>
                <h3 class="font-bold text-[#3E382D] mb-2">Terapis Profesional</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Tim kami terlatih dan berpengalaman, menggunakan produk berkualitas untuk hasil terbaik.</p>
            </div>
            <div class="bg-white rounded-3xl border border-pink-100 p-7 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-2xl mb-4">📱</div>
                <h3 class="font-bold text-[#3E382D] mb-2">Booking Mudah Online</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Pesan jadwal kapan saja dan di mana saja. Bayar via QRIS atau tunai di tempat.</p>
            </div>
        </div>

    </div>
</section>

{{-- CABANG --}}
<section class="bg-[#FFF1F2] py-20 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-12">
            <span class="inline-block bg-rose-100 text-rose-400 text-xs font-semibold px-4 py-1.5 rounded-full mb-3">Lokasi</span>
            <h2 class="text-4xl font-bold text-[#3E382D]">Cabang Kami</h2>
            <p class="text-gray-400 text-sm mt-2">Temukan salon terdekat dari kamu</p>
        </div>

        @if(isset($cabangList) && $cabangList->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($cabangList as $cabang)
                <div class="bg-white rounded-3xl border border-pink-100 p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#3E382D]">{{ $cabang->nama_cabang }}</h3>
                            <p class="text-gray-400 text-sm mt-1 leading-relaxed">{{ $cabang->alamat }}</p>
                            @if($cabang->no_telp)
                            <p class="text-rose-400 text-sm mt-1 font-medium">📞 {{ $cabang->no_telp }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- Fallback statis jika $cabangList tidak dipass dari controller --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-pink-100 p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#3E382D]">Cabang Utama</h3>
                            <p class="text-gray-400 text-sm mt-1">Jl. Contoh No. 1, Medan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-pink-100 p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#3E382D]">Cabang Percut</h3>
                            <p class="text-gray-400 text-sm mt-1">Jl. Contoh No. 2, Percut</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>

{{-- CTA AKHIR --}}
<section class="bg-gradient-to-r from-rose-400 to-pink-400 py-16 px-6">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-white mb-3">Siap Tampil Cantik Hari Ini?</h2>
        <p class="text-rose-100 text-sm mb-8">Booking sekarang dan dapatkan layanan terbaik dari tim kami.</p>
        <a href="{{ route('pelanggan.layanan.index') ?? '/layanan' }}"
           class="inline-flex items-center gap-2 bg-white text-rose-400 font-bold px-8 py-4 rounded-2xl hover:bg-rose-50 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Booking Sekarang
        </a>
    </div>
</section>

@endsection