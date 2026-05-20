@php
    $pelanggan  = $booking->pelanggan;
    $user       = $pelanggan?->user;
    $details    = $booking->details;

    $totalDurasi = $details->sum(fn($d) => $d->layananCabang?->layanan?->durasi ?? 0);
    $jamMulai    = \Carbon\Carbon::parse($booking->jam_booking);
    $jamSelesai  = $jamMulai->copy()->addMinutes($totalDurasi);

    // Sesuai status DB: pending, confirmed, completed, cancelled
    $statusLabel = match($booking->status) {
        'confirmed'  => 'Ongoing',
        'pending'    => 'Waiting',
        'completed'  => 'Done',
        'cancelled'  => 'Cancelled',
        default      => ucfirst($booking->status),
    };

    $statusColor = match($booking->status) {
        'confirmed'  => 'bg-[#A8D5A2] text-[#2D6A27]',
        'pending'    => 'bg-[#E8B1B6] text-[#3E382D]',
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
                $jenisLayanan = $layanan?->jenisLayanan?->nama_jenis_layanan ?? 'Layanan';
            @endphp
            <p class="text-[14px]">
                ● {{ $layanan?->nama_layanan ?? '-' }} | {{ $jenisLayanan }}
            </p>
            @endforeach

            {{-- Waktu --}}
            <h4 class="text-[17px] font-semibold mt-2 mb-4">
                Waktu : {{ $jamMulai->format('H:i') }} – {{ $jamSelesai->format('H:i') }}
            </h4>

            {{-- Tombol Aksi --}}
            <div class="space-y-2 w-[340px]">
                <button class="w-full h-[40px] rounded-xl bg-[#F5A6AF] text-white font-semibold hover:opacity-90 transition">
                    Start Service
                </button>
                <button class="w-full h-[40px] rounded-xl border border-[#C98B93] text-[#3E382D] font-semibold bg-[#FFF9F9] hover:bg-[#FFF1F3] transition">
                    Cancel
                </button>
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