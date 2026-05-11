@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] to-white flex flex-col items-center">

    {{-- TITLE --}}
    <div class="mt-28 text-center">
        <h1 class="text-5xl md:text-6xl text-[#3e3a34] font-bold">
            {{ $gallery->layanan->nama_layanan }}
        </h1>

        <p class="mt-4 text-gray-600 max-w-2xl">
            {{ $gallery->deskripsi }}
        </p>
    </div>

    {{-- CONTENT --}}
    <div class="flex flex-wrap justify-center gap-20 mt-10">

        {{-- BEFORE --}}
        <div class="text-center">

            @php
                $before = $gallery->fotos->where('tipe', 'before')->first();
            @endphp

            <img src="{{ asset($before->url_foto ?? 'images/default.jpg') }}"
                 class="w-[320px] h-[220px] object-cover rounded-md mx-auto">

            <div class="bg-[#3e3a34] text-white px-6 py-2 rounded-md inline-block mt-4">
                Before
            </div>

        </div>

        {{-- AFTER --}}
        <div class="text-center">

            @php
                $after = $gallery->fotos->where('tipe', 'after')->first();
            @endphp

            <img src="{{ asset($after->url_foto ?? 'images/default.jpg') }}"
                 class="w-[320px] h-[220px] object-cover rounded-md mx-auto">

            <div class="bg-[#3e3a34] text-white px-6 py-2 rounded-md inline-block mt-4">
                After
            </div>

        </div>

    </div>

</div>

@endsection