@extends('user.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6">
    <div class="max-w-5xl mx-auto">
        
        {{-- HEADER --}}
        <div class="text-center mb-10 mt-14">
            <h1 class="text-7xl font-bold text-[#3E382D]">Rincian Paket</h1>
            <p class="text-sm text-gray-500 mt-2">Informasi lengkap paket perawatan</p>
        </div>

        {{-- BREADCRUMB --}}
        <div class="mb-6">
            <a href="{{ route('service.detail', $jenisLayanan->jenis_layanan_id) }}" 
               class="text-sm text-rose-500 hover:text-rose-600 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke {{ $jenisLayanan->nama_jenis }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI - Detail Paket --}}
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-pink-100 p-8">
                
                {{-- Header Paket --}}
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 bg-rose-100 text-rose-600 rounded-full text-xs font-semibold">
                            Paket Hemat
                        </span>
                        @if($hemat > 0)
                        <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-semibold">
                            Hemat Rp {{ number_format($hemat, 0, ',', '.') }}
                        </span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-[#3E382D] mb-2">{{ $paket->nama_paket }}</h2>
                    <p class="text-gray-500">{{ $paket->deskripsi ?? '-' }}</p>
                </div>

                {{-- Harga --}}
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-2xl p-6 mb-8 border border-rose-100">
                    <div class="flex items-end justify-between">
                        <div>
                            @if($paket->harga_promo > 0)
                                <p class="text-sm text-gray-400 line-through mb-1">
                                    Rp {{ number_format($paket->harga_normal, 0, ',', '.') }}
                                </p>
                                <p class="text-3xl font-bold text-rose-500">
                                    Rp {{ number_format($paket->harga_promo, 0, ',', '.') }}
                                </p>
                            @else
                                <p class="text-3xl font-bold text-[#3E382D]">
                                    Rp {{ number_format($paket->harga_normal, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                        @if($paket->harga_promo > 0)
                        <div class="text-right">
                            <p class="text-sm text-green-600 font-semibold">
                                Hemat {{ round((1 - $paket->harga_promo / $paket->harga_normal) * 100) }}%
                            </p>
                            <p class="text-xs text-gray-400">dari harga normal</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Daftar Layanan dalam Paket --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-[#3E382D] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Layanan Termasuk dalam Paket ({{ $layananDalamPaket->count() }})
                    </h2>

                    <div class="space-y-4">
                        @foreach($layananDalamPaket as $index => $layanan)
                        <div class="flex items-start gap-4 p-4 bg-pink-50 rounded-2xl border border-pink-100">
                            {{-- Foto Layanan --}}
                            <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-rose-500 font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                            <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 border border-pink-100">
                                @if($layanan->cover_foto)
                                    <img src="{{ asset($layanan->cover_foto) }}" 
                                        alt="{{ $layanan->nama_layanan }}" 
                                        class="w-full h-full object-cover"
                                        onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                                @else
                                    <div class="w-full h-full bg-pink-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Nomor & Info Layanan --}}
                            <div class="flex-1">
                                <div class="flex items-start gap-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-[#3E382D] mb-1">{{ $layanan->nama_layanan }}</h3>
                                        <p class="text-sm text-gray-500 mb-1">{{ $layanan->durasi }} menit</p>
                                        @if($layanan->harga_promo > 0)
                                            <p class="text-sm text-rose-500 font-semibold">
                                                Rp {{ number_format($layanan->harga_promo, 0, ',', '.') }}
                                                <span class="text-gray-400 line-through font-normal text-xs ml-2">
                                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                </span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-600">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Perbandingan Harga --}}
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200 mb-8">
                    <h3 class="font-bold text-[#3E382D] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Perbandingan Harga
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total jika beli individual:</span>
                            <span class="font-semibold text-gray-700">Rp {{ number_format($totalIndividual, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Harga paket:</span>
                            <span class="font-bold text-rose-500">Rp {{ number_format($hargaPaket, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-amber-200">
                            <span class="font-bold text-gray-700">Anda Hemat:</span>
                            <span class="font-bold text-green-600 text-lg">Rp {{ number_format($hemat, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Info Tambahan --}}
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-sm text-blue-800">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-semibold mb-1">Keuntungan Memilih Paket:</p>
                            <ul class="list-disc list-inside space-y-1 text-blue-700">
                                <li>Harga lebih hemat dibandingkan beli satuan</li>
                                <li>Perawatan lebih lengkap dan maksimal</li>
                                <li>Cocok untuk perawatan rutin</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN - Booking Card --}}
            <div class="sticky top-24">
                <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden">
                    
                    {{-- Header Card --}}
                    <div class="bg-gradient-to-br from-rose-400 to-pink-500 px-6 py-5">
                        <p class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-1">
                            Pilih Cabang & Pesan
                        </p>
                        <p class="text-white font-bold text-lg leading-tight">
                            {{ $paket->nama_paket }}
                        </p>
                    </div>

                    {{-- Daftar Cabang --}}
                    <div class="p-4 space-y-2">
                        @foreach($cabangList as $cabang)
                        @php
                            $paketCabang = DB::table('paket_cabang')
                                ->where('paket_id', $paket->paket_id)
                                ->where('cabang_id', $cabang->cabang_id)
                                ->first();
                            
                            // Ambil layanan pertama dari paket untuk redirect booking
                            $firstLayanan = $layananDalamPaket->first();
                            $layananCabang = null;
                            if($firstLayanan) {
                                $layananCabang = DB::table('layanan_cabang')
                                    ->where('layanan_id', $firstLayanan->layanan_id)
                                    ->where('cabang_id', $cabang->cabang_id)
                                    ->where('status', 'tersedia')
                                    ->first();
                            }
                        @endphp

                        <div class="branch-row p-4 border border-gray-100 rounded-2xl hover:bg-rose-50 transition">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#1a1714] truncate">{{ $cabang->nama_cabang }}</p>
                                    @if($cabang->alamat)
                                    <p class="text-[11px] text-gray-400 mt-0.5 leading-snug">{{ Str::limit($cabang->alamat, 40) }}</p>
                                    @endif
                                    <div class="mt-2">
                                        @if($paketCabang && $paketCabang->status === 'tersedia')
                                            @if($paketCabang->harga_promo)
                                            <p class="text-[11px] text-gray-300 line-through">
                                                Rp {{ number_format($paketCabang->harga_normal, 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm font-bold text-rose-500">
                                                Rp {{ number_format($paketCabang->harga_promo, 0, ',', '.') }}
                                            </p>
                                            @else
                                            <p class="text-sm font-bold text-[#1a1714]">
                                                Rp {{ number_format($paketCabang->harga_normal, 0, ',', '.') }}
                                            </p>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">Tidak Tersedia</span>
                                        @endif
                                    </div>
                                </div>

                                @if($paketCabang && $paketCabang->status === 'tersedia')
                                <a href="{{ route('pelanggan.booking.paket', [
                                    'paket_id' => $paket->paket_id, 
                                    'cabang_id' => $cabang->cabang_id
                                ]) }}" 
                                class="booking-btn flex-shrink-0 bg-rose-400 hover:bg-rose-500 active:scale-95 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200">
                                    Pesan
                                </a>
                                @else
                                <button disabled class="flex-shrink-0 bg-gray-200 text-gray-400 text-xs font-bold px-4 py-2.5 rounded-xl cursor-not-allowed">
                                    Tidak Tersedia
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer: WhatsApp --}}
                    <div class="px-4 pb-4">
                        <a href="https://wa.me/6287869590802" target="_blank"
                           class="wa-btn flex items-center justify-center gap-2 w-full py-3 border border-gray-200 text-xs font-semibold text-gray-500 hover:border-green-400 hover:text-green-600 transition-colors rounded-xl">
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

<style>
.branch-row {
    transition: background 0.2s ease, transform 0.2s ease;
}
.branch-row:hover {
    background: #fff5f5;
    transform: translateX(4px);
}
</style>
@endsection


