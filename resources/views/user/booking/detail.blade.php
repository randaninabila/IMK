@extends('user.app')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6">
    <div class="max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-10 mt-14">
            <h1 class="text-7xl font-bold text-[#3E382D]">Rincian Pemesanan</h1>
            <p class="text-sm text-gray-500 mt-2">Informasi lengkap pemesanan kamu</p>
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

        {{-- CARD UTAMA --}}
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden">
            
            {{-- HEADER CARD --}}
            <div class="bg-gradient-to-r from-rose-400 to-pink-400 p-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white text-xs font-semibold opacity-90">No. Pesanan</p>
                        <p class="text-white text-xl font-bold">#{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                
                {{-- BADGE STATUS --}}
                @php
                    $statusConfig = [
                        'pending'     => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu'],
                        'confirmed'   => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Dikonfirmasi'],
                        'in_progress' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Sedang Berlangsung'],
                        'completed'   => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Selesai'],
                        'rescheduled'  => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Jadwal Ulang'],
                    ];
                    $cfg = $statusConfig[$booking->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ucfirst($booking->status)];
                @endphp

                @php
    $isRescheduled = DB::table('booking_reschedule')
        ->where('booking_id', $booking->booking_id)
        ->exists();
@endphp

                @if($isRescheduled)
    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/90 text-orange-500">
        Reschedule
    </span>
@else
<span class="px-4 py-2 rounded-full text-sm font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }}">
    {{ $cfg['label'] }}
</span>
@endif
            </div>

            {{-- CONTENT --}}
            <div class="p-6 space-y-6">

                {{-- LAYANAN --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">
                        {{ isset($isPaketBooking) && $isPaketBooking ? 'Layanan dalam Paket' : 'Layanan' }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($layananList as $item)
                            <div class="flex items-start justify-between gap-4 p-4 bg-pink-50 rounded-2xl">
                                <div class="flex items-start gap-3">
                                    @if($item->cover_foto)
                                        <img src="{{ asset($item->cover_foto) }}" alt="{{ $item->nama_layanan }}" class="w-16 h-16 rounded-xl object-cover border border-pink-100">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-pink-100 flex items-center justify-center border border-pink-100">
                                            <svg class="w-8 h-8 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-[#3E382D]">{{ $item->nama_layanan }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->durasi }} menit</p>
                                        @if(isset($item->harga_promo) && $item->harga_promo > 0)
                                            <p class="text-xs text-rose-500 font-semibold mt-1">
                                                Rp {{ number_format($item->harga_promo, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- JADWAL & LOKASI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-pink-50 rounded-2xl p-4">
                        <div class="flex items-center gap-2 text-gray-400 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs uppercase">Tanggal</span>
                        </div>
                        <p class="font-semibold text-[#3E382D]">
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('dddd, D MMMM Y') }}
                        </p>
                    </div>
                    
                    <div class="bg-pink-50 rounded-2xl p-4">
                        <div class="flex items-center gap-2 text-gray-400 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs uppercase">Jam</span>
                        </div>
                        <p class="font-semibold text-[#3E382D]">
                            {{ substr($booking->jam_booking, 0, 5) }} WIB
                        </p>
                    </div>
                    
                    <div class="md:col-span-2 bg-pink-50 rounded-2xl p-4">
                        <div class="flex items-start gap-2 text-gray-400 mb-1">
                            <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="text-xs uppercase">Lokasi</span>
                        </div>
                        @php
                            $firstLayanan = $layananList->first();
                        @endphp
                        @if($firstLayanan && $firstLayanan->nama_cabang)
                            <p class="font-semibold text-[#3E382D]">{{ $firstLayanan->nama_cabang }}</p>
                            <p class="text-sm text-gray-500">{{ $firstLayanan->alamat ?? '-' }}</p>
                        @else
                            <p class="text-sm text-gray-400 italic">Lokasi tidak tersedia</p>
                        @endif
                    </div>
                </div>

                {{-- PETUGAS (jika ada) --}}
                @if($booking->nama_pegawai)
                    <div class="bg-blue-50 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Petugas</p>
                            <p class="font-semibold text-[#3E382D]">{{ $booking->nama_pegawai }}</p>
                        </div>
                    </div>
                @endif

                {{-- PEMBAYARAN --}}
                @if($pembayaran)
                    <div class="border-t border-pink-100 pt-6">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Pembayaran</h3>
                        <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Metode</span>
                                <span class="font-semibold text-[#3E382D]">{{ strtoupper($pembayaran->metode_pembayaran) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Status</span>
                                @php
                                    $payStatus = [
                                        'pending' => ['class' => 'text-amber-600', 'label' => 'Menunggu'],
                                        'verified' => ['class' => 'text-green-600', 'label' => 'Lunas'],
                                        'failed' => ['class' => 'text-red-600', 'label' => 'Gagal'],
                                        'on_hold' => ['class' => 'text-gray-600', 'label' => 'Ditunda'],
                                    ];
                                    $ps = $payStatus[$pembayaran->status] ?? ['class' => 'text-gray-600', 'label' => ucfirst($pembayaran->status)];
                                @endphp
                                <span class="font-semibold {{ $ps['class'] }}">{{ $ps['label'] }}</span>
                            </div>
                            @if($pembayaran->bukti_pembayaran)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Bukti</span>
                                    <a href="{{ asset($pembayaran->bukti_pembayaran) }}" target="_blank" class="text-sm text-rose-500 hover:underline">Lihat Bukti</a>
                                </div>
                            @endif
                            <div class="flex items-center justify-between pt-3 border-t border-pink-100">
                                <span class="font-semibold text-[#3E382D]">Total</span>
                                <span class="text-xl font-bold text-rose-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="border-t border-pink-100 pt-6">
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-amber-800 font-semibold text-sm">Belum Ada Pembayaran</p>
                                <p class="text-amber-700 text-sm mt-0.5">Silakan lanjutkan ke halaman pembayaran untuk menyelesaikan pesanan ini.</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            
            {{-- ACTION BUTTONS --}}
            <div class="p-6 border-t border-pink-100 bg-gray-50">
                <div class="flex flex-wrap gap-3">
                    {{-- Kembali --}}
                    <a href="{{ route('pelanggan.bookings') }}" class="flex-1 min-w-[120px] bg-white border-2 border-pink-200 text-[#3E382D] font-semibold py-3 rounded-xl text-center text-sm hover:border-rose-300 transition">
                        ← Kembali
                    </a>

                    @if($booking->status === 'pending' && (!$pembayaran || ($pembayaran->metode_pembayaran === 'qris' && $pembayaran->status !== 'verified')))
                        <a href="{{ route('pelanggan.payment.show', $booking->booking_id) }}" class="flex-1 min-w-[120px] bg-rose-400 hover:bg-rose-500 text-white font-semibold py-3 rounded-xl text-center text-sm transition">
                            Bayar Sekarang
                        </a>
                    @endif

                    @if($booking->status === 'pending' && (!$pembayaran || $pembayaran->status !== 'verified') && !$isRescheduled)
    <a href="{{ route('pelanggan.booking.reschedule', $booking->booking_id) }}" 
       class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-3 rounded-xl text-center text-sm transition border border-blue-200">
        Reschedule
    </a>
@endif

                    @if(in_array($booking->status, ['Menunggu', 'Dikonfirmasi']))
                        <a href="https://wa.me/6287869590802?text=Halo%20Admin,%20saya%20ingin%20tanya%20tentang%20booking%20#{{ $booking->booking_id }}" target="_blank" class="flex-1 min-w-[120px] bg-green-50 hover:bg-green-100 text-green-600 font-semibold py-3 rounded-xl text-center text-sm transition border border-green-200">
                            Hubungi Admin
                        </a>
                    @endif
                </div>
            </div>

        </div>

        {{-- INFO TAMBAHAN --}}
        <div class="mt-6 text-center text-xs text-gray-400">
            <p>Butuh bantuan? <a href="https://wa.me/6287869590802" class="text-green-500 hover:underline">WhatsApp Kami</a></p>
        </div>

    </div>
</div>
@endsection