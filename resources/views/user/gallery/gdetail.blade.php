@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] to-white flex flex-col items-center">

    <!-- Title -->
    <div class="mt-28 text-center">
        <h1 class="text-5xl md:text-6xl text-[#3e3a34] font-bold">
            {{ $gallery['title'] }}
        </h1>
    </div>

    <!-- Content -->
    <div class="flex flex-wrap justify-center gap-20 mt-10">

        <!-- BEFORE -->
        <div class="text-center">

            <img src="{{ $gallery['before'] }}"
                 class="w-[320px] h-[220px] object-cover rounded-md mx-auto">

            <div class="bg-[#3e3a34] text-white px-6 py-2 rounded-md inline-block mt-4">
                Before
            </div>

            <div class="mt-6 space-y-4">
                @foreach ($gallery['before_list'] as $item)
                    <div class="bg-[#e6bcbc] px-6 py-3 rounded-xl shadow">
                        {{ $item }}
                    </div>
                @endforeach
            </div>

        </div>

        <!-- AFTER -->
        <div class="text-center">

            <img src="{{ $gallery['after'] }}"
                 class="w-[320px] h-[220px] object-cover rounded-md mx-auto">

            <div class="bg-[#3e3a34] text-white px-6 py-2 rounded-md inline-block mt-4">
                After
            </div>

            <div class="mt-6 space-y-4">
                @foreach ($gallery['after_list'] as $item)
                    <div class="bg-[#e6bcbc] px-6 py-3 rounded-xl shadow">
                        {{ $item }}
                    </div>
                @endforeach
            </div>

        </div>

    </div>

</div>

@endsection