@extends('user.app')

@section('content')

<div class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white text-[#3E382D]">

    {{-- ============================================================
         HERO SECTION — nama & deskripsi dari tabel jenis_layanan
    ============================================================ --}}
    <section class="relative h-[450px] flex items-center overflow-hidden">
        <div class="absolute inset-0">
            {{-- Ambil foto pertama dari layanan di jenis ini, fallback ke unsplash --}}
            @php
                $heroFoto = $layananList->first()?->url_foto;
            @endphp
            <img src="{{ $heroFoto ? asset($heroFoto) : 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?q=80&w=1200' }}"
                 alt="{{ $jenisLayanan->nama_jenis }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="container mx-auto px-10 relative z-10 text-white text-right">
            <div class="max-w-xl ml-auto">
                <h1 class="text-5xl font-bold mb-4">{{ $jenisLayanan->nama_jenis }}</h1>
                <p class="text-sm leading-relaxed opacity-90 text-justify">
                    {{ $jenisLayanan->deskripsi ?? 'Nikmati layanan terbaik kami yang dirancang khusus untuk kenyamanan dan kecantikan Anda.' }}
                </p>
                
                @php
                    $firstLayanan = $layananList->first();
                @endphp

                @if($firstLayanan?->durasi)
                <p class="mt-3 text-sm opacity-75">
                    ⏱ {{ $firstLayanan->durasi }} menit
                </p>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================================================
         SECTION LAYANAN — dari tabel layanan + layanan_cabang
    ============================================================ --}}
    <section class="py-12 bg-pink-50/50">
        <div class="container mx-auto px-6">

            <div class="flex items-center justify-center mb-12">
                <div class="flex-grow h-px bg-gray-300"></div>
                <h2 class="px-4 text-lg font-bold text-tertiary-500 uppercase whitespace-nowrap">
                    {{ $jenisLayanan->nama_jenis }} Treatment
                </h2>
                <div class="flex-grow h-px bg-gray-300"></div>
            </div>

            @if($layananList->isEmpty())
                <p class="text-center text-gray-400 py-10">Belum ada layanan tersedia untuk kategori ini.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach($layananList as $layanan)
                    <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100 flex flex-col">

                        {{-- Foto layanan: dari album_foto, fallback ke placeholder --}}
                        <img src="{{ $layanan->url_foto
                                    ? asset($layanan->url_foto)
                                    : 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?q=80&w=500' }}"
                             class="w-full h-56 object-cover"
                             alt="{{ $layanan->nama_layanan }}">

                        <div class="p-6 flex flex-col flex-grow">

                            {{-- Badge kategori --}}
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] bg-rose-100 text-rose-500 px-2 py-1 rounded-full uppercase font-bold">
                                    {{ $jenisLayanan->nama_jenis }}
                                </span>
                                @if($layanan->kategori_pelanggan === 'anak')
                                    <span class="text-[10px] bg-blue-100 text-blue-500 px-2 py-1 rounded-full uppercase font-bold">
                                        Anak
                                    </span>
                                @endif
                            </div>

                            {{-- Nama + durasi --}}
                            <div class="flex justify-between items-start mt-1">
                                <h3 class="text-lg font-bold leading-snug">{{ $layanan->nama_layanan }}</h3>
                                <span class="text-xs text-gray-400 flex items-center whitespace-nowrap ml-2 mt-1">
                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $layanan->durasi }} min
                                </span>
                            </div>

                            {{-- Deskripsi --}}
                            <p class="text-tertiary-500 text-xs mt-2 line-clamp-2 italic flex-grow">
                                {{ $layanan->deskripsi ?? '-' }}
                            </p>

                            {{-- Harga + tombol --}}
                            <div class="mt-6 flex justify-between items-center border-t pt-4">
                                <div>
                                    @if($layanan->harga_promo)
                                        <span class="text-xs line-through text-gray-400 block">
                                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                        </span>
                                        <span class="font-bold text-rose-500 text-sm">
                                            Rp {{ number_format($layanan->harga_promo, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="font-bold text-tertiary-500 text-sm">
                                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Ganti href sesuai route booking yang kamu buat --}}
                                <a href="#"
                                   class="bg-rose-200 text-rose-800 text-xs font-bold px-4 py-2 rounded-lg hover:bg-rose-300 transition">
                                    Book Now
                                </a>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    {{-- ============================================================
         SECTION PAKET — dari tabel paket_layanan
         Hanya tampil jika ada paket yang relevan
    ============================================================ --}}
    @if($paketList->isNotEmpty())
    <section class="py-16 container mx-auto px-6">

        <div class="flex items-center justify-center mb-10">
            <div class="flex-grow h-px bg-gray-300"></div>
            <h2 class="px-4 text-lg font-bold text-tertiary-500 uppercase whitespace-nowrap">
                Paket {{ $jenisLayanan->nama_jenis }}
            </h2>
            <div class="flex-grow h-px bg-gray-300"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            @foreach($paketList as $paket)
            <div class="border border-gray-200 bg-[#fff8f8] p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-base text-[#3E382D] mb-2">{{ $paket->nama_paket }}</h3>
                <p class="text-xs text-gray-500 italic mb-4">{{ $paket->deskripsi ?? '-' }}</p>
                <a href="#"
                   class="block text-center bg-rose-200 text-rose-800 text-xs font-bold px-4 py-2 rounded-lg hover:bg-rose-300 transition">
                    Pilih Paket
                </a>
            </div>
            @endforeach
        </div>

    </section>
    @endif

</div>

@endsection