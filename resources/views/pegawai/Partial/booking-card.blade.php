@php
    $pelanggan  = $booking->pelanggan;
    $user       = $pelanggan?->user;
    $details    = $booking->details;

    $totalDurasi = $details->sum(fn($d) => $d->layananCabang?->layanan?->durasi ?? 0);
    $jamMulai    = \Carbon\Carbon::parse($booking->jam_booking);
    $jamSelesai  = $jamMulai->copy()->addMinutes($totalDurasi);

    // Status labels & colors
    $statusLabel = match($booking->status) {
        'pending'    => 'Menunggu',
        'confirmed'  => 'Terjadwal',
        'ongoing'    => 'Sedang Berjalan',
        'completed'  => 'Selesai',
        'cancelled'  => 'Dibatalkan',
        default      => ucfirst($booking->status),
    };

    $statusColor = match($booking->status) {
        'pending'    => 'bg-[#FDE68A] text-[#92400E]',
        'confirmed'  => 'bg-[#E8B1B6] text-[#3E382D]',
        'ongoing'    => 'bg-[#A8D5A2] text-[#2D6A27]',
        'completed'  => 'bg-[#B5D5F5] text-[#1D4E89]',
        'cancelled'  => 'bg-[#F5C6CB] text-[#7B2D32]',
        default      => 'bg-[#E8E1E1] text-[#3B302D]',
    };
@endphp

<div class="bg-white border-[3px] border-[#EAB7BF] rounded-[30px] px-8 py-7 flex items-start justify-between gap-8 mb-5">

    {{-- LEFT --}}
    <div class="flex gap-6 flex-1">

        {{-- DATE --}}
        <div class="w-[68px] h-[68px] rounded-full bg-[#E8B1B6] flex flex-col items-center justify-center shrink-0">
            <span class="text-[17px] font-semibold leading-none">
                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d') }}
            </span>
            <span class="text-[13px]">
                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('M') }}
            </span>
        </div>

        {{-- DETAIL --}}
        <div class="flex-1">
            
            <!-- <div class="bg-[#F5A6AF] rounded-xl px-3 py-2 inline-block"> -->
    <p class="text-[#E8B1B6] text-sm font-bold mb-3">
        No Pesanan : #{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }}
    </p>
   
<!-- </div> -->
            {{-- Nama + Status --}}
            <div class="flex items-center gap-3 mb-2">
                <h3 class="text-[17px] font-semibold">
                    {{ $user?->nama ?? '-' }}
                </h3>
                <span class="text-[12px] px-4 py-1 rounded-full font-semibold {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>


            {{-- Daftar Layanan --}}
            @foreach ($details as $detail)
            @php
                $layanan      = $detail->layananCabang?->layanan;
                $jenisLayanan = $layanan?->jenisLayanan?->nama_jenis ?? 'Layanan';
            @endphp
            <p class="text-[14px]">
                ● {{ $layanan?->nama_layanan ?? '-' }} | {{ $jenisLayanan }}
            </p>
            @endforeach

            {{-- Waktu --}}
            <h4 class="text-[17px] font-semibold mt-2 mb-4">
                Waktu : {{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('l, d M') }} | {{ $jamMulai->format('H:i') }} – {{ $jamSelesai->format('H:i') }}
            </h4>

            {{-- Tombol Aksi --}}
            @php
                $nowTime    = \Carbon\Carbon::now();
                $jamBooking = \Carbon\Carbon::parse(
                    $booking->tanggal_booking . ' ' . $booking->jam_booking
                );
                $bisaStart  = $nowTime->gte($jamBooking);
            @endphp

            <div class="space-y-2 w-[340px]">

                @if($booking->status === 'confirmed')

                    {{-- START SERVICE: confirmed → ongoing --}}
                    <form method="POST" action="{{ route('pegawai.booking.updateStatus', $booking) }}">
                        @csrf
                        <input type="hidden" name="status" value="ongoing">
                        
                        @if($bisaStart)
                            <button type="submit"
                                    class="w-full h-[40px] rounded-xl bg-[#F5A6AF] text-white font-semibold hover:bg-[#e8919b] transition flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Mulai Servis
                            </button>
                        @else
                            <button type="button" disabled
                                    title="Layanan bisa dimulai pukul {{ $jamBooking->format('H:i') }}"
                                    class="w-full h-[40px] rounded-xl bg-gray-200 text-gray-500 font-semibold cursor-not-allowed flex items-center justify-center gap-2 opacity-60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Mulai pukul {{ $jamBooking->format('H:i') }}
                            </button>
                        @endif
                    </form>

                    {{-- BATALKAN BOOKING: confirmed → pending --}}
                    <form method="POST" action="{{ route('pegawai.booking.updateStatus', $booking) }}"
                          onsubmit="return confirm('Yakin batalkan booking ini? Booking akan dikembalikan ke antrian.')">
                        @csrf 
                        <input type="hidden" name="status" value="pending">
                        <button type="submit"
                                class="w-full h-[40px] rounded-xl border border-[#C98B93] text-[#3E382D] font-semibold bg-[#FFF9F9] hover:bg-[#FFF1F3] transition">
                            Batalkan Booking
                        </button>
                    </form>

                @elseif($booking->status === 'ongoing')

                    {{-- SELESAI: ongoing → completed --}}
                    <form method="POST" action="{{ route('pegawai.booking.updateStatus', $booking) }}">
                        @csrf 
                        <input type="hidden" name="status" value="completed">
                        <button type="submit"
                                class="w-full h-[40px] rounded-xl bg-[#A8D5A2] text-[#2D6A27] font-semibold hover:opacity-90 transition">
                            Selesai
                        </button>
                    </form>

                @endif

            </div>

        </div>

    </div>

    {{-- RIGHT: Informasi Pelanggan --}}
    <div class="w-[380px] border-l border-[#E5B5BC] pl-8">

        <h3 class="text-[17px] font-semibold mb-4">Informasi Pelanggan</h3>

        <div class="flex gap-4 mb-4">
            {{-- Foto Profile --}}
            <img src="{{ $user?->foto_profile ? asset('storage/' . $user->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($user?->nama ?? 'P') . '&background=E8B1B6&color=3E382D' }}"
                 class="w-[70px] h-[70px] rounded-full object-cover">

            <div>
                <h4 class="text-[16px] font-semibold">
                    Nama : {{ $user?->nama ?? '-' }}
                </h4>
                <p class="text-[13px]">
                    No Telepon : {{ $user?->no_hp ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Notes --}}
        <div>
            <h4 class="text-[16px] font-semibold">Notes :</h4>
            <p class="text-[14px] leading-relaxed">
                {{ $booking->catatan ?? $booking->notes ?? 'Tidak ada catatan.' }}
            </p>
        </div>

    </div>

</div>