@extends('owner.app')

@section('content')

<div class="pt-28 px-10 bg-[#f4e6e6] min-h-screen">

    {{-- BACK --}}
    <a href="/service"
        class="inline-flex items-center gap-2 bg-[#c98f8f] text-white px-6 py-3 rounded-full text-sm mb-10">
        ← Back
    </a>

    {{-- CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

        @foreach([
        ['title'=>'Total Category','val'=>5],
        ['title'=>'Active Service','val'=>55],
        ['title'=>'Cabang Package','val'=>7],
        ] as $item)

        <div class="bg-[#f0eded] rounded-[30px] px-8 py-6 shadow-sm">
            <p class="text-lg font-semibold text-[#3e382d]">
                {{ $item['title'] }}
            </p>

            <h2 class="text-3xl font-bold text-[#e11d48] mt-2">
                {{ $item['val'] }}
            </h2>
        </div>

        @endforeach

    </div>

    {{-- MAIN CARD --}}
    <div class="bg-[#efe3e3] rounded-[35px] px-10 py-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <h2 class="text-4xl font-bold text-[#3e382d]">
                Service Directory
            </h2>

            <button class="bg-[#f45b69] text-white px-5 py-2 rounded-full text-sm">
                Seluruh Cabang ▼
            </button>

        </div>

        {{-- SEARCH + BUTTON --}}
        <div class="flex justify-between items-center mb-6">

            {{-- SEARCH --}}
            <div class="flex items-center bg-[#e7bcbc] px-5 py-2 rounded-full w-[380px]">
                <span class="mr-3 text-[#6b5c5c] text-lg">🔍</span>
                <input type="text" placeholder="Search service"
                    class="bg-transparent outline-none w-full text-sm text-[#3e382d] placeholder:text-[#6b5c5c]">
            </div>

            {{-- ADD --}}
            <button class="bg-[#e7bcbc] text-[#b91c1c] px-5 py-2 rounded-full text-sm flex items-center gap-2">
                👤 + Add Service
            </button>

        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden rounded-xl">

            <table class="w-full text-sm">

                {{-- HEAD --}}
                <thead class="text-[#9f1d2c] text-base">
                    <tr>
                        <th class="py-4 text-left">No</th>
                        <th class="text-left">Services</th>
                        <th class="text-left">Category</th>
                        <th class="text-left">Cabang Laudendang</th>
                        <th class="text-left">Cabang Tuasan</th>
                        <th class="text-left">Revenue</th>
                        <th class="text-left">Action</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="text-[#3e382d]">

                    @foreach([
                    'Hair Spa','Hair Spa','Inai','Inai','Inai'
                    ] as $i => $name)

                    <tr class="border-t border-[#e4caca] hover:bg-white/30 transition">

                        <td class="py-4">{{ $i+1 }}</td>

                        <td class="font-medium">{{ $name }}</td>

                        <td>Hair Treatment</td>

                        <td>
                            <div class="font-medium">102 booking</div>
                            <div class="text-xs text-gray-500">Rp 25jt</div>
                        </td>

                        <td>
                            <div class="font-medium">102 booking</div>
                            <div class="text-xs text-gray-500">Rp 25jt</div>
                        </td>

                        <td class="font-semibold">Rp 50jt</td>

                        <td class="flex gap-4">
                            <button class="hover:scale-110 transition">✏️</button>
                            <button class="hover:scale-110 transition">🗑️</button>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection