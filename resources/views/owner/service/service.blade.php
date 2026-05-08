@extends('owner.app')

@section('content')

<div class="pt-24 px-8 bg-[#f6eaea] min-h-screen">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-5xl font-bold text-[#2d2a26]">Service Analytics</h1>
            <p class="text-gray-500 mt-2">Deep dive into your salon's growth.</p>
        </div>

        <div class="flex gap-3">
            <button class="bg-[#f45b69] text-white px-4 py-2 rounded-full text-sm">
                Seluruh Cabang ▼
            </button>
            <button class="bg-[#f8cdd0] text-[#2d2a26] px-4 py-2 rounded-full text-sm">
                Mei 2026 ▼
            </button>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="flex gap-3 flex-wrap mb-8" id="filterBtns">

    <button data-filter="all"
        class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#3E382D] text-white">
        All Services
    </button>

    <button data-filter="hair"
        class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#eadede] text-[#3E382D]">
        Hair Treatment
    </button>

    <button data-filter="facial"
        class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#eadede] text-[#3E382D]">
        Facial Treatment
    </button>

    <button data-filter="body"
        class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#eadede] text-[#3E382D]">
        Body Treatment
    </button>

    <button data-filter="lumiface"
        class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#eadede] text-[#3E382D]">
        Lumiface
    </button>

    <button data-filter="waxing"
        class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#eadede] text-[#3E382D]">
        Waxing
    </button>

    <button data-filter="reflexy"
        class="filter-btn px-4 py-1.5 rounded-md text-sm border border-[#3E382D] bg-[#eadede] text-[#3E382D]">
        Foot Reflexy
    </button>

</div>

    {{-- CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        {{-- CARD ITEM --}}
        @foreach([
            ['no'=>1,'title'=>'Hair Spa','cat'=>'Hair Treatment','total'=>102],
            ['no'=>2,'title'=>'Inai','cat'=>'Nail Treatment','total'=>98],
            ['no'=>3,'title'=>'Lulur','cat'=>'Body Treatment','total'=>92],
            ['no'=>4,'title'=>'Coloring','cat'=>'Hair Treatment','total'=>89],
        ] as $item)

        <div class="relative bg-white rounded-3xl p-6 shadow-md">

            {{-- NUMBER --}}
            <div class="absolute -top-4 -right-4 bg-red-500 text-white w-10 h-10 flex items-center justify-center rounded-full font-bold">
                {{ $item['no'] }}
            </div>

            {{-- IMAGE --}}
            <div class="w-20 h-20 bg-gray-300 rounded-full mb-4"></div>

            <h3 class="font-semibold text-lg">{{ $item['title'] }}</h3>
            <p class="text-gray-500 text-sm">{{ $item['cat'] }}</p>

            <p class="mt-3 text-xl font-bold">
                {{ $item['total'] }} <span class="text-sm font-normal">appointments</span>
            </p>
        </div>

        @endforeach

    </div>

    {{-- TABLE --}}
    <div class="bg-[#eadede] p-6 rounded-3xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-[#2d2a26]">Service Leaderboard</h2>
            <button class="text-sm text-[#b04a4a]">Edit ✏️</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="text-left text-[#b04a4a]">
                    <tr>
                        <th class="py-3">Services</th>
                        <th>Category</th>
                        <th>Cabang Laudendang</th>
                        <th>Cabang Tuasan</th>
                        <th>Revenue</th>
                        <th>Growth</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    @for($i=0; $i<5; $i++)
                    <tr class="border-t">
                        <td class="py-3">Hair Spa</td>
                        <td>Hair treatment</td>
                        <td>
                            102 booking <br>
                            <span class="text-xs text-gray-400">Rp 25jt</span>
                        </td>
                        <td>
                            102 booking <br>
                            <span class="text-xs text-gray-400">Rp 25jt</span>
                        </td>
                        <td>Rp 50 jt</td>
                        <td class="{{ $i % 2 == 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $i % 2 == 0 ? '+8.2%' : '-2.2%' }}
                        </td>
                    </tr>
                    @endfor
                </tbody>

            </table>
        </div>

    </div>

</div>
<script>
const items = document.querySelectorAll('.gallery-item');
const filterBtns = document.querySelectorAll('.filter-btn');

let currentFilter = 'all';

// FILTER
function filterItems() {
    items.forEach(item => {
        let role = item.getAttribute('data-role');

        if (currentFilter === 'all' || role === currentFilter) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// ACTIVE BUTTON STYLE
function setActiveButton() {
    filterBtns.forEach(btn => {
        btn.classList.remove('bg-[#3E382D]', 'text-white');
        btn.classList.add('bg-[#eadede]', 'text-[#3E382D]');

        if (btn.getAttribute('data-filter') === currentFilter) {
            btn.classList.add('bg-[#3E382D]', 'text-white');
            btn.classList.remove('bg-[#eadede]', 'text-[#3E382D]');
        }
    });
}

// CLICK
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        currentFilter = btn.getAttribute('data-filter');

        setActiveButton();
        filterItems();
    });
});

// INIT
setActiveButton();
filterItems();
</script>
@endsection