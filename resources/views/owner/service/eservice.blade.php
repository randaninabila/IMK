@extends('owner.app')

@section('content')

<div class="pt-24 px-8 pb-8 bg-[#f6eaea] min-h-screen">

    {{-- BACK --}}
    <a
        href="{{ route('owner.service') }}"
        class="
            inline-flex items-center gap-2
            bg-white
            border border-[#f1dede]
            px-5 py-2.5
            rounded-full
            text-sm font-medium
            text-[#b04a4a]
            shadow-sm
            hover:bg-pink-50
            transition
            mb-8
        "
    >
        ← Back to Service Analytics
    </a>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        <div class="bg-white rounded-3xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">
                Total Categories
            </p>

            <h2 class="text-3xl font-bold text-[#f45b69]">
                {{ $jenisLayanan->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">
                Active Services
            </p>

            <h2 class="text-3xl font-bold text-[#f45b69]">
                {{ $leaderboard->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">
                Total Revenue
            </p>

            <h2 class="text-3xl font-bold text-[#f45b69]">
                Rp {{ number_format(
                    $totalRevenue,
                    0,
                    ',',
                    '.'
                ) }}
            </h2>
        </div>

    </div>

    {{-- MAIN --}}
    <div class="bg-[#eadede] rounded-3xl p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-3xl font-bold text-[#2d2a26]">
                    Service Directory
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    {{ Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
                    • Manage and monitor all salon services
                </p>

            </div>

            <div class="flex gap-3">

                {{-- FILTER CABANG --}}
                <div class="relative" x-data="{ open: false }">

                    <button
                        @click="open = !open"
                        class="
                            bg-[#f45b69]
                            text-white
                            px-5 py-2.5
                            rounded-full
                            text-sm
                            font-medium
                            flex items-center gap-2
                            shadow-sm
                            hover:opacity-90
                            transition
                        "
                    >

                        @if($selectedCabang == 'all')

                            Seluruh Cabang

                        @else

                            {{
                                $cabangs
                                    ->firstWhere(
                                        'cabang_id',
                                        $selectedCabang
                                    )?->nama_cabang
                            }}

                        @endif

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />

                        </svg>

                    </button>

                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="
                            absolute
                            top-full
                            left-0
                            mt-2
                            w-64
                            bg-white
                            rounded-2xl
                            shadow-xl
                            border border-pink-100
                            overflow-hidden
                            z-50
                        "
                    >

                        {{-- ALL --}}
                        <a
                            href="
                                {{
                                    route('owner.service.edit', [
                                        'cabang' => 'all',
                                        'bulan' => $selectedMonth
                                    ])
                                }}
                            "
                            class="
                                flex items-center justify-between
                                px-5 py-3
                                text-sm
                                hover:bg-pink-50
                                transition

                                {{
                                    $selectedCabang == 'all'
                                        ? 'bg-pink-50 font-semibold text-[#f45b69]'
                                        : 'text-gray-700'
                                }}
                            "
                        >

                            <span>Seluruh Cabang</span>

                            @if($selectedCabang == 'all')
                                <span>✓</span>
                            @endif

                        </a>

                        @foreach($cabangs as $cabang)

                        <a
                            href="
                                {{
                                    route('owner.service.edit', [
                                        'cabang' => $cabang->cabang_id,
                                        'bulan' => $selectedMonth
                                    ])
                                }}
                            "
                            class="
                                flex items-center justify-between
                                px-5 py-3
                                text-sm
                                hover:bg-pink-50
                                transition

                                {{
                                    $selectedCabang == $cabang->cabang_id
                                        ? 'bg-pink-50 font-semibold text-[#f45b69]'
                                        : 'text-gray-700'
                                }}
                            "
                        >

                            <span>
                                {{ $cabang->nama_cabang }}
                            </span>

                            @if($selectedCabang == $cabang->cabang_id)
                                <span>✓</span>
                            @endif

                        </a>

                        @endforeach

                    </div>

                </div>

                {{-- FILTER BULAN --}}
                <div class="relative" x-data="{ open: false }">

                    <button
                        @click="open = !open"
                        class="
                            bg-white
                            border border-[#f3dede]
                            text-[#2d2a26]
                            px-5 py-2.5
                            rounded-full
                            text-sm
                            font-medium
                            flex items-center gap-2
                            shadow-sm
                            hover:bg-[#fff7f7]
                            transition
                        "
                    >

                        {{
                            collect($months)
                                ->firstWhere(
                                    'value',
                                    $selectedMonth
                                )['label']
                        }}

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />

                        </svg>

                    </button>

                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="
                            absolute
                            top-full
                            right-0
                            mt-2
                            w-52
                            bg-white
                            rounded-2xl
                            shadow-xl
                            border border-pink-100
                            overflow-hidden
                            z-50
                        "
                    >

                        @foreach($months as $month)

                        <a
                            href="
                                {{
                                    route('owner.service.edit', [
                                        'cabang' => $selectedCabang,
                                        'bulan' => $month['value']
                                    ])
                                }}
                            "
                            class="
                                flex items-center justify-between
                                px-5 py-3
                                text-sm
                                hover:bg-pink-50
                                transition

                                {{
                                    $selectedMonth == $month['value']
                                        ? 'bg-pink-50 font-semibold text-[#f45b69]'
                                        : 'text-gray-700'
                                }}
                            "
                        >

                            <span>
                                {{ $month['label'] }}
                            </span>

                            @if($selectedMonth == $month['value'])
                                <span>✓</span>
                            @endif

                        </a>

                        @endforeach

                    </div>

                </div>

                {{-- ADD --}}
                <button
                    class="
                        bg-[#f8cdd0]
                        text-[#b04a4a]
                        px-5 py-2.5
                        rounded-full
                        text-sm font-medium
                        hover:opacity-90
                        transition
                    "
                >
                    + Add Service
                </button>

            </div>

        </div>

        {{-- SEARCH --}}
        <div class="mb-6">

            <div class="
                flex items-center
                bg-white
                px-5 py-3
                rounded-2xl
                border border-[#ecd9d9]
                max-w-md
            ">

                <span class="mr-3 text-gray-400">
                    🔍
                </span>

                <input
                    type="text"
                    placeholder="Search service..."
                    class="
                        bg-transparent
                        outline-none
                        w-full
                        text-sm
                    "
                    id="searchService"
                >

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead
                    class="
                        text-left
                        text-[#b04a4a]
                        border-b border-[#d8c6c6]
                    "
                >

                    <tr>
                        <th class="py-4 px-4">No</th>
                        <th class="px-4">Service</th>
                        <th class="px-4">Category</th>

                        @if($selectedCabang == 'all')

                            @foreach($cabangs as $cabang)

                            <th class="px-4">
                                {{ $cabang->nama_cabang }}
                            </th>

                            @endforeach

                        @else

                            <th class="px-4">
                                Branch Performance
                            </th>

                        @endif

                        <th class="px-4">Revenue</th>
                        <th class="px-4">Growth</th>
                    </tr>

                </thead>

                <tbody class="text-gray-700">

                    @forelse($leaderboard as $i => $item)

                    <tr
                        class="
                            border-b border-[#e5d6d6]
                            hover:bg-[#fdf4f4]
                            transition-colors duration-200
                        "
                    >

                        <td class="py-5 px-4">
                            {{ $i + 1 }}
                        </td>

                        <td class="px-4 font-semibold">
                            {{ $item['service'] }}
                        </td>

                        <td class="px-4">
                            {{ $item['category'] }}
                        </td>

                        @if($selectedCabang == 'all')

                            <td class="px-4">

                                <div class="leading-6">

                                    <span class="font-medium">
                                        {{ $item['cabang1_count'] }} booking
                                    </span>

                                    <br>

                                    <span class="text-xs text-gray-400">
                                        Rp {{ $item['cabang1_revenue'] }}
                                    </span>

                                </div>

                            </td>

                            <td class="px-4">

                                <div class="leading-6">

                                    <span class="font-medium">
                                        {{ $item['cabang2_count'] }} booking
                                    </span>

                                    <br>

                                    <span class="text-xs text-gray-400">
                                        Rp {{ $item['cabang2_revenue'] }}
                                    </span>

                                </div>

                            </td>

                        @else

                            <td class="px-4">

                                <div class="leading-6">

                                    <span class="font-medium">
                                        {{ $item['selected_count'] }} booking
                                    </span>

                                    <br>

                                    <span class="text-xs text-gray-400">
                                        Rp {{ $item['selected_revenue'] }}
                                    </span>

                                </div>

                            </td>

                        @endif

                        <td class="px-4 font-semibold">
                            {{ $item['revenue'] }}
                        </td>

                        <td class="px-4">

                            <span class="{{ $item['growth_class'] }} font-semibold">

                                {{ $item['growth'] >= 0 ? '+' : '' }}
                                {{ $item['growth'] }}%

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="100%"
                            class="py-14 text-center"
                        >

                            <div class="flex flex-col items-center">

                                <div class="text-5xl mb-4">
                                    📊
                                </div>

                                <h3 class="text-xl font-semibold text-[#2d2a26] mb-2">
                                    No service data
                                </h3>

                                <p class="text-sm text-gray-500">
                                    No completed bookings were found.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
const searchInput =
    document.getElementById('searchService');

const tableRows =
    document.querySelectorAll('tbody tr');

searchInput.addEventListener('keyup', function () {

    const keyword =
        this.value.toLowerCase();

    tableRows.forEach(row => {

        const text =
            row.innerText.toLowerCase();

        row.style.display =
            text.includes(keyword)
            ? ''
            : 'none';

    });

});
</script>
@endsection