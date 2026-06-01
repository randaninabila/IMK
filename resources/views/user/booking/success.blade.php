@extends('user.app')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6 flex items-center">
    <div class="max-w-2xl mx-auto w-full">

        {{-- ANIMASI & JUDUL (DINAMIS BERDASARKAN METODE) --}}
        <div class="text-center mb-8 mt-16" id="successAnimation">
            <div class="relative inline-block">
                @if(isset($pembayaran) && $pembayaran->metode_pembayaran === 'qris' && $pembayaran->status === 'Menunggu')
                    <div class="absolute inset-0 rounded-full bg-amber-100 animate-ping opacity-30"></div>
                @endif
                
                {{-- Icon & Warna Dinamis --}}
                <div class="relative w-28 h-28 rounded-full flex items-center justify-center mx-auto
                    {{ isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash' ? 'bg-green-100' : 'bg-amber-100' }}">
                    
                    @if(isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash')
                        {{-- ✅ Cash: Icon Centang Hijau --}}
                        <svg class="w-14 h-14 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-14 h-14 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
            </div>

            {{-- Judul Dinamis --}}
            <h1 class="text-3xl font-bold text-[#3E382D] mt-6 mb-2">
                @if(isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash')
                    Pesanan Telah Dikonfirmasi! 🎉
                @else
                    Bukti Terkirim! ✨
                @endif
            </h1>

            {{-- Deskripsi Dinamis --}}
            <p class="text-gray-500 text-sm max-w-sm mx-auto">
                @if(isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash')
                    Pesanan kamu sudah dikonfirmasi. Siapkan uang tunai dan hadir sesuai jadwal.
                @else
                    Bukti pembayaran kamu sudah kami terima dan sedang diverifikasi oleh tim kami.
                @endif
            </p>
        </div>

        {{-- NOTIFIKASI STATUS --}}
        <div class="mb-6">
            @if(isset($pembayaran))
                @if($pembayaran->status === 'Menunggu')
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-amber-800 font-semibold text-sm">Status: Menunggu Verifikasi</p>
                            <p class="text-amber-700 text-sm mt-0.5">
                                @if($pembayaran->metode_pembayaran === 'cash')
                                    Pembayaran tunai untuk pesanan ini akan diverifikasi saat kamu tiba di salon.
                                @else
                                    Bukti pembayaran QRIS sedang diverifikasi oleh admin. Proses biasanya ≤ 1 jam kerja.
                                @endif
                            </p>
                        </div>
                    </div>

                {{-- ✅ VERIFIED (sudah dikonfirmasi) --}}
                @elseif($pembayaran->status === 'verified')
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-green-800 font-semibold text-sm">✅ Pembayaran Diverifikasi!</p>
                            <p class="text-green-700 text-sm mt-0.5">
                                Pesanan kamu sudah dikonfirmasi. Siapkan diri dan hadir sesuai jadwal ya! 🌸
                            </p>
                        </div>
                    </div>

                {{-- ✅ ON_HOLD / FAILED --}}
                @elseif(in_array($pembayaran->status, ['on_hold', 'failed']))
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-4">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-red-800 font-semibold text-sm">Status: {{ strtoupper($pembayaran->status) }}</p>
                            <p class="text-red-700 text-sm mt-0.5">
                                @if($pembayaran->status === 'on_hold')
                                    Pembayaran ditunda. Silakan hubungi admin untuk informasi lebih lanjut.
                                @else
                                    Pembayaran gagal. Silakan coba lagi atau hubungi admin.
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        {{-- DETAIL BOOKING --}}
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-rose-400 to-pink-400 p-5 flex items-center justify-between">
                <div>
                    <p class="text-white text-xs font-semibold opacity-80 uppercase tracking-wide">Rincian Pemesanan</p>
                    <p class="text-white text-lg font-bold mt-0.5">#{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
@php
    $statusColors = [
        'pending' => 'bg-amber-100 text-amber-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'in_progress' => 'bg-purple-100 text-purple-700',
        'completed' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];

    $statusLabels = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'in_progress' => 'Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
@endphp

<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
    {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
</span>
            </div>

            <div class="p-6 space-y-5">
                
                {{-- List Layanan --}}
                <div>
                    <p class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">
                        {{ $layananList->count() > 1 ? 'Layanan dalam Paket' : 'Layanan' }}
                    </p>
                    <div class="space-y-3">
                        @foreach($layananList as $item)
                            <div class="flex items-start justify-between gap-4 pb-3 border-b border-pink-50 last:border-0 last:pb-0">
                                <div class="flex-1">
                                    <p class="font-semibold text-[#3E382D]">{{ $item->nama_layanan }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $item->durasi }} menit
                                    </p>
                                </div>
                                {{-- ✅ HANYA tampilkan harga jika single layanan (bukan paket) --}}
                                @if($layananList->count() === 1)
                                    <p class="font-bold text-rose-400 whitespace-nowrap">
                                        Rp {{ number_format($item->harga_promo > 0 ? $item->harga_promo : $item->harga, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Info jadwal & lokasi --}}
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-pink-50 rounded-2xl p-4">
                        <p class="text-gray-400 text-xs mb-1">Tanggal</p>
                        <p class="font-semibold text-[#3E382D]">
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('dddd, D MMM Y') }}
                        </p>
                    </div>
                    <div class="bg-pink-50 rounded-2xl p-4">
                        <p class="text-gray-400 text-xs mb-1">Jam</p>
                        <p class="font-semibold text-[#3E382D]">
                            {{ substr($booking->jam_booking, 0, 5) }} WIB
                        </p>
                    </div>
                    @if($cabang?->alamat)
<div class="col-span-2 bg-pink-50 rounded-2xl p-4">
    <p class="text-gray-400 text-xs mb-1">Lokasi</p>
    <p class="font-semibold text-[#3E382D]">{{ $cabang->nama_cabang }}</p>
    <p class="text-xs text-gray-500 mt-0.5">{{ $cabang->alamat }}</p>
</div>
@endif
                    <div class="bg-pink-50 rounded-2xl p-4">
                        <p class="text-gray-400 text-xs mb-1">Metode Bayar</p>
                        <p class="font-semibold text-[#3E382D]">
                            {{ $pembayaran ? strtoupper($pembayaran->metode_pembayaran) : '-' }}
                        </p>
                    </div>
                    <div class="bg-pink-50 rounded-2xl p-4">
                        <p class="text-gray-400 text-xs mb-1">Status Bayar</p>
                        <p class="font-semibold {{ 
                            $pembayaran && $pembayaran->status === 'verified' ? 'text-green-500' : 
                            ($pembayaran && in_array($pembayaran->status, ['failed', 'on_hold']) ? 'text-red-500' : 'text-amber-500') 
                        }}">
                            {{ $pembayaran ? strtoupper($pembayaran->status) : '-' }}
                        </p>
                    </div>
                </div>

                {{-- TOTAL PAKET / LAYANAN --}}
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-2xl p-5 border border-rose-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Pembayaran</p>
                            @if($layananList->count() > 1)
                                <p class="text-xs text-green-600 font-medium mt-0.5">
                                    ✅ Hemat dengan paket combo!
                                </p>
                            @endif
                        </div>
                        <p class="text-2xl font-bold text-rose-500">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ url('/service') }}" 
               class="flex items-center justify-center gap-2 bg-white border-2 border-pink-200 text-[#3E382D] font-semibold py-3 rounded-2xl hover:border-rose-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Lihat Layanan
            </a>
            <a href="{{ route('pelanggan.bookings') }}"
               class="flex items-center justify-center gap-2 bg-rose-400 hover:bg-rose-500 text-white font-semibold py-3 rounded-2xl transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Riwayat Pemesanan
            </a>
        </div>

        {{-- SHARE / INFO TAMBAHAN --}}
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                Ada pertanyaan? Hubungi kami via
                <a href="https://wa.me/6287869590802" target="_blank" class="text-green-500 font-semibold hover:underline">
                    WhatsApp
                </a>
            </p>
        </div>

    </div>
</div>

{{-- Auto-dismiss toast notifikasi --}}
<div id="toastNotif"
     class="fixed bottom-6 right-6 z-50 bg-white shadow-xl border border-pink-100 rounded-2xl p-4 flex items-center gap-3 max-w-xs transform translate-y-20 opacity-0 transition-all duration-500">
    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
        {{ isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash' ? 'bg-green-100' : 'bg-amber-100' }}">
        <svg class="w-5 h-5 {{ isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash' ? 'text-green-500' : 'text-amber-500' }}"
             fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    </div>
    <div>
        <p class="text-sm font-semibold text-[#3E382D]">
            @if(isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash')
                Pesanan dikonfirmasi!
            @else
                Bukti pembayaran terkirim!
            @endif
        </p>
        <p class="text-xs text-gray-500">
            @if(isset($pembayaran) && $pembayaran->metode_pembayaran === 'cash')
                Bayar saat kunjungan ya 💖
            @else
                Menunggu verifikasi admin 🌸
            @endif
        </p>
    </div>
</div>

<script>
// Tampilkan toast notifikasi saat halaman load
window.addEventListener('load', function() {
    const toast = document.getElementById('toastNotif');
    setTimeout(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 500);

    // Auto-hide setelah 5 detik
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 5500);
});
</script>
@endsection