@extends('owner.app')

@section('content')

<div class="pt-24 px-8 pb-8 bg-[#f6eaea] min-h-screen relative">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-5xl font-bold text-[#2d2a26]">Service Analytics</h1>
            <p class="text-gray-500 mt-2">Deep dive into your salon's growth.</p>
        </div>

        <div class="flex gap-3">

            {{-- CABANG --}}
            <div class="relative" x-data="{ openBranch: false }">

                {{-- BUTTON --}}
                <button
                    @click="openBranch = !openBranch"
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
                        {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                    @endif

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>

                </button>

                {{-- DROPDOWN --}}
                <div
                    x-show="openBranch"
                    @click.outside="openBranch = false"
                    x-transition
                    class="
                        absolute
                        top-full mt-2 left-0
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
                        href="{{ route('owner.service') }}?cabang=all&bulan={{ $selectedMonth }}&kategori=all"
                        class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-sm

                            {{ $selectedCabang == 'all'
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

                    {{-- CABANG --}}
                    @foreach($cabangs as $cabang)

                    <a
                        href="{{ route('owner.service') }}?cabang={{ $cabang->cabang_id }}&bulan={{ $selectedMonth }}&kategori={{ $selectedCategory }}"
                        class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-sm

                            {{ $selectedCabang == $cabang->cabang_id
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

            {{-- BULAN --}}
            <div class="relative" x-data="{ openMonth: false }">

                {{-- BUTTON --}}
                <button
                    @click="openMonth = !openMonth"
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

                    {{ collect($months)->firstWhere('value', $selectedMonth)['label'] }}

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>

                </button>

                {{-- DROPDOWN --}}
                <div
                    x-show="openMonth"
                    @click.outside="openMonth = false"
                    x-transition
                    class="
                        absolute
                        top-full mt-2 right-0
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
                        href="{{ route('owner.service') }}?cabang={{ $selectedCabang }}&bulan={{ $month['value'] }}&kategori={{ $selectedCategory }}"
                        class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-sm

                            {{ $selectedMonth == $month['value']
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

        </div>
    </div>

    {{-- FILTER --}}
    <div class="flex gap-3 flex-wrap mb-8" id="filterBtns">

        <button data-filter="all"
            class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#3E382D] text-white">
            All Services
        </button>

        @foreach($jenisLayanan as $jenis)

        <button data-filter="{{ $jenis->nama_jenis }}"
            class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#eadede] text-[#3E382D]">

            {{ $jenis->nama_jenis }}

        </button>

        @endforeach

    </div>

    {{-- TOP SERVICES --}}
    <div class="mb-10">

        <div class="flex items-center justify-between mb-5">

            <div>
                <h2 class="text-2xl font-bold text-[#2d2a26]">
                    Most Popular Services
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Top booked services this month
                </p>
            </div>

        </div>

        @if($topLayanan->count() > 0)

        <div
            id="serviceCardsContainer"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            @foreach($topLayanan as $item)

            <div
                class="service-card relative bg-white rounded-2xl
                px-5 py-4
                border border-[#f3dede]
                hover:-translate-y-1 hover:shadow-lg
                transition-all duration-300"
                data-category="{{ $item['cat'] }}">

                {{-- RANK --}}
                <div
                    class="
                    service-rank
                    absolute -top-3 -right-3
                    w-12 h-12 rounded-full
                    flex items-center justify-center
                    text-lg font-bold text-white shadow-md
                ">

                    #

                </div>

                {{-- IMAGE --}}
                <div
                    class="w-16 h-16 rounded-2xl overflow-hidden
                    bg-[#f8eded]
                    border border-[#f1dddd]
                    mb-4">

                    @if($item['cover'])

                    <img
                        src="{{ asset('storage/' . $item['cover']) }}"
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <div
                        class="w-full h-full hidden items-center justify-center text-xl">
                        ✨
                    </div>

                    @else

                    <div
                        class="w-full h-full flex items-center justify-center text-xl">
                        ✨
                    </div>

                    @endif

                </div>

                {{-- TITLE --}}
                <h3
                    class="font-semibold text-[16px]
                    text-[#2d2a26]
                    leading-snug line-clamp-1">

                    {{ $item['title'] }}

                </h3>

                {{-- CATEGORY --}}
                <p
                    class="text-xs text-gray-400 mt-1 line-clamp-1">

                    {{ $item['cat'] }}

                </p>

                {{-- APPOINTMENT --}}
                <div
                    class="mt-5 pt-4 border-t border-[#f3e5e5]">

                    <p
                        class="text-[15px] font-semibold text-[#2d2a26]">

                        {{ number_format($item['total']) }}

                        <span
                            class="text-sm font-medium text-gray-500">

                            appointments

                        </span>

                    </p>

                </div>

            </div>

            @endforeach

        </div>

        {{-- EMPTY FILTER STATE --}}
        <div
            id="emptyServiceState"
            class="hidden bg-white rounded-3xl py-12 px-6 text-center shadow-md">

            <div class="text-4xl mb-3">
                📭
            </div>

            <h3 class="text-lg font-semibold text-[#2d2a26] mb-2">
                No services found
            </h3>

            <p class="text-sm text-gray-500">
                No appointments were recorded for this category this month.
            </p>

        </div>

        @else

        {{-- EMPTY MONTH STATE --}}
        <div
            class="bg-white rounded-3xl py-14 px-6 text-center shadow-md">

            <div class="text-5xl mb-4">
                📊
            </div>

            <h3 class="text-lg font-semibold text-[#2d2a26] mb-2">
                No service data yet
            </h3>

            <p class="text-gray-500 text-sm">
                There are no completed bookings for this period.
            </p>

        </div>

        @endif

    </div>

    {{-- TABLE --}}
    <div class="bg-[#eadede] p-6 rounded-3xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-[#2d2a26]">
                Service Leaderboard
            </h2>

            <a
                href="{{ route('owner.service.edit') }}"
                class="
                    text-sm
                    text-[#b04a4a]
                "
            >
                Edit ✏️
            </a>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="text-left text-[#b04a4a] border-b border-[#d8c6c6]">
                    <tr>
                        <th class="py-4 px-4 text-center">No</th>
                        <th class="py-4 px-4">Services</th>
                        <th class="px-4">Category</th>

                        @if($selectedCabang == 'all')

                            @foreach($cabangs as $cabang)
                                <th class="px-4">
                                    {{ $cabang->nama_cabang }}
                                </th>
                            @endforeach

                        @else

                            <th class="px-4">
                                {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                            </th>

                        @endif

                        <th class="px-4">Revenue</th>
                        <th class="px-4">Growth</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    @forelse($leaderboard as $i => $item)

                    <tr
                        class="leaderboard-row border-b border-[#e5d6d6] hover:bg-[#fdf4f4] transition-colors duration-200"
                        data-category="{{ $item['category'] }}">

                        <td class="py-5 px-4 text-center">
                            {{ $i + 1 }}
                        </td>

                        <td class="py-5 px-4 font-semibold">
                            {{ $item['service'] }}
                        </td>

                        <td class="px-4">
                            {{ $item['category'] }}
                        </td>

                        @if($selectedCabang == 'all')

                            @foreach($cabangs as $cabang)

                            <td class="px-4">

                                <div class="leading-6">

                                    <span class="font-medium">

                                        {{
                                            $item['branches'][$cabang->cabang_id]['count']
                                        }} booking

                                    </span>

                                    <br>

                                    <span class="text-xs text-gray-400">

                                        Rp {{
                                            $item['branches'][$cabang->cabang_id]['revenue']
                                        }}

                                    </span>

                                </div>

                            </td>

                            @endforeach

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

                        <td class="px-4 font-semibold text-[#2d2a26]">
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
                            class="py-14 text-center">

                            <div class="flex flex-col items-center">

                                <div class="text-5xl mb-4">
                                    📊
                                </div>

                                <h3 class="text-xl font-semibold text-[#2d2a26] mb-2">
                                    No leaderboard data
                                </h3>

                                <p class="text-sm text-gray-500">
                                    No completed service bookings were found for this period.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

            <div
                id="emptyTableState"
                class="hidden py-14 text-center"
            >

                <div class="text-5xl mb-4">
                    📊
                </div>

                <h3 class="text-xl font-semibold text-[#2d2a26] mb-2">
                    No leaderboard data
                </h3>

                <p class="text-sm text-gray-500">
                    No completed service bookings were found
                    for this category.
                </p>
            </div>

            {{-- FOOTER TABLE --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-8 pt-5 border-t border-[#d8c6c6]">

                {{-- INFO --}}
                <div class="text-sm text-gray-500">

                    @if($leaderboard instanceof \Illuminate\Pagination\LengthAwarePaginator)

                        Showing
                        <span class="font-semibold text-[#2d2a26]">
                            {{ $leaderboard->firstItem() }}
                        </span>

                        -

                        <span class="font-semibold text-[#2d2a26]">
                            {{ $leaderboard->lastItem() }}
                        </span>

                        of

                        <span class="font-semibold text-[#2d2a26]">
                            {{ $leaderboard->total() }}
                        </span>

                        specialists

                    @else

                        Showing all
                        <span class="font-semibold text-[#2d2a26]">
                            {{ $leaderboard->count() }}
                        </span>

                        specialists

                    @endif

                </div>

                {{-- RIGHT CONTROL --}}
                <div class="flex items-center gap-4">

                    {{-- PER PAGE --}}
                    <form method="GET">

                    <div class="relative">

                        <input
                            type="hidden"
                            name="cabang"
                            value="{{ $selectedCabang }}"
                        >

                        <input
                            type="hidden"
                            name="bulan"
                            value="{{ $selectedMonth }}"
                        >

                        <select
                            name="show"
                            onchange="this.form.submit()"
                            class="
                                bg-white
                                border border-[#ecd9d9]
                                rounded-xl
                                pl-4 pr-10 py-2
                                text-sm
                                outline-none
                                shadow-sm
                                appearance-none
                                cursor-pointer
                                hover:border-[#f4b6bc]
                                transition
                            "
                        >

                            <option value="10"
                                {{ $perPage == 10 ? 'selected' : '' }}>
                                10 rows
                            </option>

                            <option value="20"
                                {{ $perPage == 20 ? 'selected' : '' }}>
                                20 rows
                            </option>

                            <option value="50"
                                {{ $perPage == 50 ? 'selected' : '' }}>
                                50 rows
                            </option>

                            <option value="all"
                                {{ $perPage == 'all' ? 'selected' : '' }}>
                                All
                            </option>

                        </select>

                        <div class="
                            pointer-events-none
                            absolute inset-y-0 right-3
                            flex items-center
                            text-gray-400
                        ">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
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

                        </div>

                    </div>

                    </form>

                    {{-- PAGINATION --}}
                    @if($leaderboard instanceof \Illuminate\Pagination\LengthAwarePaginator)

                        <div>
                            {{ $leaderboard->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

<script>
function filterData() {

    const cabang =
        document.getElementById('cabangFilter').value;

    const bulan =
        document.getElementById('monthFilter').value;

    window.location.href =
        `{{ route('owner.service') }}?cabang=${cabang}&bulan=${bulan}`;
}

const cards =
    document.querySelectorAll('.service-card');

const rows =
    document.querySelectorAll('.leaderboard-row');

const filterBtns =
    document.querySelectorAll('.filter-btn');

const emptyState =
    document.getElementById('emptyServiceState');

let currentFilter = 'all';

// FILTER
function filterItems() {

    let visibleCards = [];

    // RESET SEMUA
    cards.forEach(card => {

        card.style.display = 'none';

    });

    // AMBIL CARD SESUAI FILTER
    cards.forEach(card => {

        const category =
            card.getAttribute('data-category');

        const isVisible =
            currentFilter === 'all'
            || category === currentFilter;

        if (isVisible) {

            visibleCards.push(card);

        }

    });

    // AMBIL TOP 4 SAJA
    visibleCards
        .slice(0, 4)
        .forEach((card, index) => {

            card.style.display = 'block';

            const rank =
                card.querySelector('.service-rank');

            rank.innerHTML = `#${index + 1}`;

            rank.classList.remove(
                'bg-amber-400',
                'bg-slate-400',
                'bg-orange-400',
                'bg-rose-400'
            );

            if (index === 0) {

                rank.classList.add('bg-amber-400');

            } else if (index === 1) {

                rank.classList.add('bg-slate-400');

            } else if (index === 2) {

                rank.classList.add('bg-orange-400');

            } else {

                rank.classList.add('bg-rose-400');

            }

        });

    // EMPTY STATE
    if (visibleCards.length === 0) {

        emptyState.classList.remove('hidden');

    } else {

        emptyState.classList.add('hidden');

    }

    // FILTER TABLE
    rows.forEach(row => {

        const category =
            row.getAttribute('data-category');

        const isVisible =
            currentFilter === 'all'
            || category === currentFilter;

        row.style.display =
            isVisible
            ? 'table-row'
            : 'none';

    });

    let visibleRows = 0;

    rows.forEach(row => {

        const category =
            row.getAttribute('data-category');

        const isVisible =
            currentFilter === 'all'
            || category === currentFilter;

        row.style.display =
            isVisible
            ? 'table-row'
            : 'none';

        if (isVisible) {

            visibleRows++;

        }

    });

    const emptyTableState =
        document.getElementById('emptyTableState');

    if (visibleRows === 0) {

        emptyTableState.classList.remove('hidden');

    } else {

        emptyTableState.classList.add('hidden');

    }

}

// ACTIVE BUTTON
function setActiveButton() {

    filterBtns.forEach(btn => {

        btn.classList.remove(
            'bg-[#3E382D]',
            'text-white'
        );

        btn.classList.add(
            'bg-[#eadede]',
            'text-[#3E382D]'
        );

        if (
            btn.getAttribute('data-filter')
            === currentFilter
        ) {

            btn.classList.add(
                'bg-[#3E382D]',
                'text-white'
            );

            btn.classList.remove(
                'bg-[#eadede]',
                'text-[#3E382D]'
            );

        }

    });

}

// CLICK
filterBtns.forEach(btn => {

    btn.addEventListener('click', () => {

        currentFilter =
            btn.getAttribute('data-filter');

        setActiveButton();
        filterItems();

    });

});

// INIT
setActiveButton();
filterItems();
</script>

@endsection