@extends('pegawai.app')

@section('content')

<div class="w-full px-4 py-4 font-sans text-[#3B302D]">

    {{-- TITLE --}}
    <div class="mb-5">
        <h1 class="text-[26px] font-bold leading-none">Pesanan</h1>
        <p class="mt-2 text-[16px]">Lihat informasi lengkap dari pesanan yang masuk</p>
    </div>

    {{-- in_progress --}}
    <div class="mb-7">

        <h2 class="text-[18px] font-bold mb-2">Sedang Berlangsung</h2>

        @if ($in_progress)
            @include('pegawai.partial.booking-card', ['booking' => $in_progress, 'isin_progress' => true])
        @else
            <div class="bg-white border-[3px] border-[#EAB7BF] rounded-[30px] px-8 py-10 text-center text-[#C4AAAA] text-[16px]">
                Tidak ada booking yang sedang berjalan.
            </div>
        @endif

    </div>

    {{-- UPCOMING --}}
    <div>

        <h2 class="text-[18px] font-bold mb-2">Pesanan Yang Akan Datang</h2>

        @forelse ($upcoming as $booking)
            @include('pegawai.partial.booking-card', ['booking' => $booking, 'isin_progress' => false])
        @empty
            <div class="bg-white border-[3px] border-[#EAB7BF] rounded-[30px] px-8 py-10 text-center text-[#C4AAAA] text-[16px]">
                Tidak ada upcoming booking hari ini.
            </div>
        @endforelse

    </div>

</div>

@endsection