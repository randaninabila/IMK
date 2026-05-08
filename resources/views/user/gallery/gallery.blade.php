@extends('user.app')

@section('content')

{{-- HERO --}}
<section
    class="bg-gradient-to-b from-[#FFE4E6] to-white min-h-screen flex items-center justify-center text-center px-4">
    <div>
        <h1 class="text-4xl md:text-5xl font-bold text-[#3E382D] mb-6">
            Real Result, Refined Artistry
        </h1>
        <p class="text-tertiary-500 max-w-xl mx-auto mb-6 ">
            Tim kami yang terdiri dari tenaga ahli yang memiliki pengalaman bertahun-tahun
            untuk memberikan perawatan yang luar biasa dan dipersonalisasi.
        </p>
        <div class="flex justify-center gap-6 text-sm text-tertiary-500">
            <span>✔ Mengutamakan Keamanan</span>
            <span>✔ Hasil yang Natural</span>
            <span>✔ Terbukti Berpengalaman</span>
        </div>
    </div>
</section>

{{-- FILTER + SEARCH --}}
<section class="bg-gradient-to-b from-white via-[#FFF1F2] to-[#FFE4E6] py-10 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-4">
            {{-- FILTER --}}
            <div class="flex flex-wrap gap-3 mb-6" id="filterBtns">

                <button data-filter="all"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#3E382D] text-white">
                    All Gallery
                </button>

                <button data-filter="hair"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D]">
                    Hair
                </button>

                <button data-filter="facial"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D]">
                    Facial
                </button>

                <button data-filter="nail polish"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D]">
                    Nail Polish
                </button>

                <button data-filter="waxing"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D]">
                    Waxing
                </button>

            </div>
            {{-- SEARCH --}}
            <div class="relative w-full max-w-xs">
                <input type="text" placeholder="Search..."
                    class="w-full pl-6 pr-12 py-3 bg-white border-2 border-[#E99688] rounded-2xl text-[#9CA3AF] placeholder-[#9CA3AF] outline-none transition-all focus:ring-2 focus:ring-[#f5c6be]">
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#E99688]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- CARD GRID --}}
        @php
        $galleries = [
        ['title' => 'Hair Repair Treatment', 'slug' => 'hair-repair-treatment', 'desc' => 'Mengembalikan kesehatan
        rambut rusak dan kering', 'img' => '/images/before.jpg', 'role' => 'hair'],
        ['title' => 'Hair Growth Therapy', 'slug' => 'hair-growth-therapy', 'desc' => 'Merangsang pertumbuhan rambut
        agar lebih tebal', 'img' => '/images/before2.jpg', 'role' => 'hair'],
        ['title' => 'Scalp Detox Hair Spa', 'slug' => 'scalp-detox-hair-spa', 'desc' => 'Membersihkan kulit kepala dari
        minyak dan kotoran', 'img' => '/images/before3.jpg', 'role' => 'hair'],

        ['title' => 'Acne Facial Treatment', 'slug' => 'acne-facial-treatment', 'desc' => 'Mengatasi jerawat dan kulit
        berminyak', 'img' => '/images/before4.jpg', 'role' => 'facial'],
        ['title' => 'Brightening Facial', 'slug' => 'brightening-facial', 'desc' => 'Mencerahkan kulit kusam dan tidak
        merata', 'img' => '/images/before5.jpg', 'role' => 'facial'],
        ['title' => 'Anti Aging Facial', 'slug' => 'anti-aging-facial', 'desc' => 'Mengurangi kerutan dan garis halus
        pada wajah', 'img' => '/images/before6.jpg', 'role' => 'facial'],

        ['title' => 'Classic Nail Polish', 'slug' => 'classic-nail-polish', 'desc' => 'Perawatan kuku dengan warna
        natural elegan', 'img' => '/images/before7.jpg', 'role' => 'nail polish'],
        ['title' => 'Gel Nail Polish', 'slug' => 'gel-nail-polish', 'desc' => 'Kuteks tahan lama dengan hasil glossy',
        'img' => '/images/before8.jpg', 'role' => 'nail polish'],
        ['title' => 'Nail Art Design', 'slug' => 'nail-art-design', 'desc' => 'Desain kuku kreatif dan modern', 'img' =>
        '/images/before9.jpg', 'role' => 'nail polish'],

        ['title' => 'Full Body Waxing', 'slug' => 'full-body-waxing', 'desc' => 'Menghilangkan bulu secara menyeluruh
        pada tubuh', 'img' => '/images/before10.jpg', 'role' => 'waxing'],
        ['title' => 'Brazilian Waxing', 'slug' => 'brazilian-waxing', 'desc' => 'Waxing area sensitif dengan hasil
        bersih maksimal', 'img' => '/images/before11.jpg', 'role' => 'waxing'],
        ['title' => 'Underarm Waxing', 'slug' => 'underarm-waxing', 'desc' => 'Menghilangkan bulu ketiak agar lebih
        bersih dan halus', 'img' => '/images/before12.jpg', 'role' => 'waxing'],
        ];
        @endphp
        <div class="grid md:grid-cols-3 gap-6" id="galleryGrid">
            @foreach ($galleries as $index => $item)
            <div class="gallery-item bg-white rounded-xl p-3 shadow hover:shadow-lg transition"
                data-role="{{ $item['role'] }}" data-index="{{ $index }}">
                <img src="{{ $item['img'] }}" class="rounded-lg mb-3 w-full h-44 object-cover">
                <span
                    class="text-[10px] uppercase px-2 py-1 rounded-full bg-gray-100 text-gray-600">{{ $item['role'] }}</span>
                <h3 class="font-semibold text-sm text-[#3E382D] mt-2">{{ $item['title'] }}</h3>
                <p class="text-xs text-[#3E382D] mt-1 mb-3">{{ $item['desc'] }}</p>
                <div class="flex justify-end">
                    <a href="{{ url('/gallery/' . $item['slug']) }}"
                        class="bg-[#e9bcbc] hover:bg-[#dca9a9] text-white text-xs py-1 px-3 rounded">
                        View Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        {{-- PAGINATION --}}
        <div class="flex justify-center items-center gap-3 mt-10">
            <button id="prevBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#3E382D] text-white"> « </button>
            <div id="pages" class="flex gap-3">
                <button class="page-btn w-10 h-10 rounded-md border">1</button>
                <button class="page-btn w-10 h-10 rounded-md border">2</button>
            </div>
            <button id="nextBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#3E382D] text-white"> » </button>
        </div>
    </div>
</section>

<script>
const items = Array.from(document.querySelectorAll('.gallery-item'));
const filterBtns = document.querySelectorAll('.filter-btn');
const pageBtns = document.querySelectorAll('.page-btn');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const searchInput = document.querySelector('input[placeholder="Search..."]');

let currentPage = 1;
let itemsPerPage = 6;
let currentFilter = 'all';
let currentSearch = '';

// =========================
// LOAD FILTER
// =========================
let savedFilter = localStorage.getItem('gallery_filter');
if (savedFilter) {
    currentFilter = savedFilter;
}

// =========================
// FILTER ITEMS + SEARCH
// =========================
function getFilteredItems() {
    return items.filter(item => {
        let role = item.getAttribute('data-role');
        let title = item.querySelector('h3')?.textContent.toLowerCase() ?? '';
        let desc = item.querySelector('p')?.textContent.toLowerCase() ?? '';

        let matchFilter = currentFilter === 'all' || role === currentFilter;
        let matchSearch = currentSearch === '' ||
            title.includes(currentSearch) ||
            desc.includes(currentSearch) ||
            role.includes(currentSearch);

        return matchFilter && matchSearch;
    });
}

// =========================
// SET BUTTON STYLE (ONLY 2 COLORS)
// =========================
function setActiveButton() {
    filterBtns.forEach(btn => {
        let isActive = btn.getAttribute('data-filter') === currentFilter;

        // RESET (NON ACTIVE)
        btn.classList.remove('bg-[#3E382D]', 'text-white');
        btn.classList.add('bg-[#f5eaea]', 'text-[#3E382D]');

        // ACTIVE
        if (isActive) {
            btn.classList.add('bg-[#3E382D]', 'text-white');
            btn.classList.remove('bg-[#f5eaea]', 'text-[#3E382D]');
        }
    });
}

// =========================
// RENDER GALLERY
// =========================
function render() {
    let filtered = getFilteredItems();

    let start = (currentPage - 1) * itemsPerPage;
    let end = start + itemsPerPage;

    items.forEach(item => item.style.display = 'none');

    filtered.slice(start, end).forEach(item => {
        item.style.display = 'block';
    });

    // Tampilkan pesan jika tidak ada hasil
    let emptyMsg = document.getElementById('emptyMsg');
    if (filtered.length === 0) {
        if (!emptyMsg) {
            emptyMsg = document.createElement('p');
            emptyMsg.id = 'emptyMsg';
            emptyMsg.className = 'col-span-3 text-center text-gray-400 py-10 text-sm';
            emptyMsg.textContent = 'Tidak ada hasil yang ditemukan.';
            document.getElementById('galleryGrid').appendChild(emptyMsg);
        }
    } else {
        if (emptyMsg) emptyMsg.remove();
    }

    updatePagination(filtered.length);
}

// =========================
// PAGINATION UI
// =========================
function updatePagination(totalItems) {

    pageBtns.forEach((btn, index) => {
        if (index + 1 === currentPage) {
            btn.classList.add('bg-[#3E382D]', 'text-white');
        } else {
            btn.classList.remove('bg-[#3E382D]', 'text-white');
        }
    });

    // PREV
    if (currentPage === 1) {
        prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    // NEXT
    let maxPage = Math.ceil(totalItems / itemsPerPage);

    if (currentPage >= maxPage) {
        nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// =========================
// FILTER CLICK
// =========================
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        currentFilter = btn.getAttribute('data-filter');
        currentPage = 1;

        localStorage.setItem('gallery_filter', currentFilter);

        setActiveButton();
        render();
    });
});

// =========================
// SEARCH INPUT
// =========================
searchInput.addEventListener('input', () => {
    currentSearch = searchInput.value.toLowerCase().trim();
    currentPage = 1; // reset ke halaman 1 saat search
    render();
});

// =========================
// PAGE CLICK
// =========================
pageBtns.forEach((btn, index) => {
    btn.addEventListener('click', () => {
        currentPage = index + 1;
        render();
    });
});

// =========================
// PREV
// =========================
prevBtn.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        render();
    }
});

// =========================
// NEXT
// =========================
nextBtn.addEventListener('click', () => {
    let filtered = getFilteredItems();
    let maxPage = Math.ceil(filtered.length / itemsPerPage);

    if (currentPage < maxPage) {
        currentPage++;
        render();
    }
});

// =========================
// INIT
// =========================
setActiveButton();
render();
</script>
@endsection