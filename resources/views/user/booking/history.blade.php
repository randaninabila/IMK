@extends('user.app')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6">
    <div class="max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-10 mt-14">
            <h1 class="text-7xl font-bold text-[#3E382D]">Riwayat Pemesanan</h1>
            <p class="text-sm text-gray-500 mt-2">Lihat semua pemesanan layanan kamu</p>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-600 text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- TAB FILTER (Opsional) --}}
        <div class="mb-6 flex flex-wrap gap-2 justify-center">
            <a href="{{ route('pelanggan.bookings') }}" 
               class="px-4 py-2 rounded-full text-sm font-semibold transition
                      {{ request('status') == '' ? 'bg-rose-400 text-white' : 'bg-white text-gray-600 hover:bg-rose-50 border border-pink-200' }}">
                Semua
            </a>
            @foreach(['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'in_progress' => 'Berlangsung', 'completed' => 'Selesai',] as $key => $label)
                <a href="{{ route('pelanggan.bookings', ['status' => $key]) }}" 
                   class="px-4 py-2 rounded-full text-sm font-semibold transition
                          {{ request('status') == $key ? 'bg-rose-400 text-white' : 'bg-white text-gray-600 hover:bg-rose-50 border border-pink-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- LIST BOOKING --}}
        <div class="space-y-4">
            @forelse($bookings as $booking)
                <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden hover:shadow-md transition">
                    
                    {{-- HEADER CARD --}}
                    <div class="bg-gradient-to-r from-rose-400 to-pink-400 p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold opacity-90">No. Pesanan</p>
                                <p class="text-white font-bold">#{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                        
                        {{-- BADGE STATUS BOOKING --}}
@php
    $statusColors = [
        'pending'   => 'bg-amber-100 text-amber-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'in_progress'   => 'bg-purple-100 text-purple-700',
        'completed' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];
    $statusLabels = [
        'pending'   => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'in_progress'   => 'Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
@endphp
<div class="flex flex-col items-end gap-1">
    @if($booking->is_rescheduled)
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/90 text-orange-500">
            Jadwal Ulang
        </span>
    @endif
    @if(!$booking->is_rescheduled || $booking->booking_status !== 'pending')
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$booking->booking_status] ?? 'bg-gray-100 text-gray-700' }}">
            {{ $statusLabels[$booking->booking_status] ?? ucfirst($booking->booking_status) }}
        </span>
    @endif
</div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-5 space-y-4">
                        
                        {{-- Layanan --}}
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Layanan</p>
                            <p class="font-semibold text-[#3E382D]">{{ $booking->layanan_nama }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->jumlah_layanan }} item</p>
                        </div>

                        {{-- Jadwal & Lokasi --}}
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('D MMM Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ substr($booking->jam_booking, 0, 5) }} WIB</span>
                            </div>
                            <div class="col-span-2 flex items-start gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-rose-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span>{{ $booking->nama_cabang }}<br><span class="text-xs text-gray-400">{{ $booking->alamat }}</span></span>
                            </div>
                        </div>

                        {{-- Pembayaran --}}
                        <div class="border-t border-pink-100 pt-4">
                            @if($booking->pembayaran_id)
                                @php
                                    $payStatusColors = [
                                        'pending' => 'text-amber-600',
                                        'verified' => 'text-green-600',
                                        'failed' => 'text-red-600',
                                        'on_hold' => 'text-gray-600',
                                    ];
                                    $payStatusLabels = [
                                        'pending' => 'Menunggu Pembayaran',
                                        'verified' => 'Lunas',
                                        'failed' => 'Gagal',
                                        'on_hold' => 'Ditunda',
                                    ];
                                @endphp
                                <div class="flex items-center justify-between">
                                     <div>
                                         <p class="text-xs text-gray-400">Pembayaran</p>
                                         <p class="text-sm font-semibold text-[#3E382D]">
                                             @if($booking->metode_pembayaran === 'qris_lunas')
                                                 QRIS (Lunas)
                                             @elseif($booking->metode_pembayaran === 'qris_panjar')
                                                 QRIS (DP 30%)
                                             @else
                                                 {{ strtoupper($booking->metode_pembayaran) }}
                                             @endif
                                         </p>
                                     </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Status</p>
                                        <p class="text-sm font-semibold {{ $payStatusColors[$booking->payment_status] ?? 'text-gray-600' }}">
                                            {{ $payStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status) }}
                                        </p>
                                    </div>
                                </div>
                                @if($booking->total_bayar)
                                    <p class="text-lg font-bold text-rose-400 mt-2 text-right">
                                        Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}
                                    </p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 italic">Belum ada pembayaran</p>
                            @endif
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="flex gap-3 pt-2">
                            {{-- Lihat Detail --}}
                            <a href="{{ route('pelanggan.booking.show', $booking->booking_id) }}" 
                               class="flex-1 bg-white border-2 border-pink-200 text-[#3E382D] font-semibold py-2.5 rounded-xl text-center text-sm hover:border-rose-300 transition">
                                Lihat Rincian
                            </a>

                            
                            @if($booking->booking_status === 'pending' && !$booking->is_rescheduled)
    <a href="{{ route('pelanggan.booking.reschedule', $booking->booking_id) }}" 
       class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-2.5 rounded-xl text-center text-sm transition border border-blue-200">
        Jadwal Ulang
    </a>
@endif

                            @if($booking->booking_status === 'completed' && !$booking->sudah_ulasan)
                                <a href="{{ route('pelanggan.booking.ulasan', $booking->booking_id) }}" 
                                class="flex-1 bg-green-50 hover:bg-green-100 text-green-600 font-semibold py-2.5 rounded-xl text-center text-sm transition border border-green-200">
                                    Beri Ulasan ⭐
                                </a>
                            @elseif($booking->booking_status === 'completed' && $booking->sudah_ulasan)
                                {{-- Tampilkan badge "Sudah Diulas" jika sudah review --}}
                                <span class="flex-1 bg-gray-100 text-gray-400 font-semibold py-2.5 rounded-xl text-center text-sm cursor-not-allowed">
                                    ✓ Sudah Diulas
                                </span>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                {{-- EMPTY STATE --}}
                <div class="text-center py-16 bg-white rounded-3xl border border-pink-100">
                    <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#3E382D] mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 text-sm mb-6">Kamu belum memiliki riwayat pemesanan layanan.</p>
                    <a href="{{ url('/service') }}"
                       class="inline-flex items-center gap-2 bg-rose-400 hover:bg-rose-500 text-white font-semibold px-6 py-3 rounded-2xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Pesan Sekarang
                    </a>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8">
            {{ $bookings->links('vendor.pagination.tailwind') }}
        </div>

    </div>
</div>
@endsection