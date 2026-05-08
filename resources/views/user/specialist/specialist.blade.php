@extends('user.app')

@section('content')

{{-- HERO --}}
<section
    class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white text-center min-h-screen flex flex-col justify-center items-center px-4">
    <span class="bg-[#3E382D] text-white px-4 py-1 rounded text-sm">
        Board-Certified Specialists
    </span>

    <h1 class="text-4xl md:text-5xl font-bold text-[#3E382D] mt-6 mb-4">
        Meet Our Specialists
    </h1>

    <p class="text-tertiary-500 max-w-2xl mb-6">
        Get to know our dedicated professionals who are here to help you feel more confident through friendly, safe, and
        high-quality services.
    </p>

    <div class="flex justify-center gap-8 text-sm text-tertiary-500">
        <span>✔ Certified Experts</span>
        <span>★ 3+ Years Experience</span>
        <span>☺ 1000+ Happy Clients</span>
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
                    All
                </button>
                <button data-filter="senior beautician"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D]">
                    Senior Beautician
                </button>
                <button data-filter="skin specialist"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D]">
                    Skin Specialist
                </button>
                <button data-filter="cosmetic doctor"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D]">
                    Cosmetic Doctor
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

        {{-- CARD --}}
        @php
        $specialists = [
        [
        'name' => 'Dr. Aisyah Rahmawati',
        'slug' => 'aisyah-rahmawati',
        'role' => 'Senior Beautician',
        'desc' => 'Specializing in facial treatments and skin rejuvenation...',
        'img' => 'https://via.placeholder.com/400x300',
        'services' => ['Facial Treatment', 'Skin Brightening', 'Acne Care']
        ],
        [
        'name' => 'Dr. Kevin Pratama',
        'slug' => 'kevin-pratama',
        'role' => 'Skin Specialist',
        'desc' => 'Expert in advanced dermatology...',
        'img' => 'https://via.placeholder.com/400x300',
        'services' => ['Anti Aging', 'Dermatology', 'Laser Therapy']
        ],
        [
        'name' => 'Dr. Maria Siregar',
        'slug' => 'maria-siregar',
        'role' => 'Cosmetic Doctor',
        'desc' => 'Focused on natural beauty enhancement...',
        'img' => 'https://via.placeholder.com/400x300',
        'services' => ['Botox', 'Filler', 'Skin Rejuvenation']
        ],
        ];
        @endphp
        <div class="grid md:grid-cols-3 gap-10" id="specialistGrid">
            @foreach ($specialists as $index => $specialist)
            <div class="specialist-item bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden"
                data-name="{{ strtolower($specialist['name']) }}"
                data-role="{{ strtolower($specialist['role']) }}"
                data-index="{{ $index }}">
                <img src="{{ $specialist['img'] }}" class="w-full h-56 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-[#3E382D]">{{ $specialist['name'] }}</h3>
                    <p class="text-sm text-gray-500 mb-2">{{ $specialist['role'] }}</p>
                    <p class="text-sm text-tertiary-500 mb-3">{{ $specialist['desc'] }}</p>
                    <ul class="text-xs text-tertiary-500 mb-4 space-y-1">
                        @foreach ($specialist['services'] as $service)
                        <li>✔ {{ $service }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ url('/specialist/' . $specialist['slug']) }}"
                        class="block text-center w-full bg-[#e9bcbc] hover:bg-[#dca9a9] text-white py-2 rounded">
                        View Profile
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="flex justify-center items-center gap-3 mt-10" id="pagination">
            {{-- PREV --}}
            <button id="prevBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-white cursor-not-allowed">
                ←
            </button>
            {{-- NUMBER --}}
            <div id="pages" class="flex gap-3">
                <button class="page-btn w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700">1</button>
                <button class="page-btn w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700">2</button>
                <button class="page-btn w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700">3</button>
                <button class="page-btn w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700">4</button>
                <button class="page-btn w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700">5</button>
            </div>
            {{-- NEXT --}}
            <button id="nextBtn" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-800 text-white">
                →
            </button>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-[#e9aeb4] py-16 px-6">
    <div class="max-w-4xl mx-auto text-center text-white">
        <h2 class="text-2xl font-semibold mb-2">
            Ready to meet <span class="font-bold">Our Team?</span>
        </h2>
        <p class="mb-6 text-sm"> Schedule your consultation and let our experts guide you on your beauty journey </p>
        <button class="bg-white text-pink-500 px-6 py-2 rounded font-semibold hover:bg-gray-100"> Book Now </button>
        <p class="text-xs mt-4 opacity-80"> Trusted by 1000+ Happy Clients </p>
    </div>
</section>

<script>
const items = Array.from(document.querySelectorAll('.specialist-item'));
const filterBtns = document.querySelectorAll('.filter-btn');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const searchInput = document.querySelector('input[placeholder="Search..."]');

let currentPage = 1;
const itemsPerPage = 6;
let currentFilter = 'all';
let currentSearch = '';

// =========================
// GET FILTERED ITEMS
// =========================
function getFilteredItems() {
    return items.filter(item => {
        const name = item.getAttribute('data-name') ?? '';
        const role = item.getAttribute('data-role') ?? '';

        const matchFilter = currentFilter === 'all' || role === currentFilter;
        const matchSearch = currentSearch === '' ||
            name.includes(currentSearch) ||
            role.includes(currentSearch);

        return matchFilter && matchSearch;
    });
}

// =========================
// SET ACTIVE FILTER BUTTON
// =========================
function setActiveButton() {
    filterBtns.forEach(btn => {
        const isActive = btn.getAttribute('data-filter') === currentFilter;
        btn.classList.toggle('bg-[#3E382D]', isActive);
        btn.classList.toggle('text-white', isActive);
        btn.classList.toggle('bg-[#f5eaea]', !isActive);
        btn.classList.toggle('text-[#3E382D]', !isActive);
    });
}

// =========================
// UPDATE PAGINATION BUTTONS
// =========================
function updatePagination(totalItems) {
    const maxPage = Math.ceil(totalItems / itemsPerPage);
    const pagesContainer = document.getElementById('pages');

    // Rebuild page buttons dinamis
    pagesContainer.innerHTML = '';
    for (let i = 1; i <= maxPage; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'page-btn w-10 h-10 rounded-md border';

        if (i === currentPage) {
            btn.classList.add('bg-[#3E382D]', 'text-white', 'border-[#3E382D]');
        } else {
            btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
        }

        btn.addEventListener('click', () => {
            currentPage = i;
            render();
        });

        pagesContainer.appendChild(btn);
    }

    // PREV
    if (currentPage <= 1) {
        prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        prevBtn.classList.remove('bg-[#3E382D]');
        prevBtn.classList.add('bg-gray-300');
    } else {
        prevBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-300');
        prevBtn.classList.add('bg-[#3E382D]');
    }

    // NEXT
    if (currentPage >= maxPage) {
        nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        nextBtn.classList.remove('bg-[#3E382D]');
        nextBtn.classList.add('bg-gray-300');
    } else {
        nextBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-300');
        nextBtn.classList.add('bg-[#3E382D]');
    }

    // Sembunyikan pagination kalau cuma 1 halaman
    document.getElementById('pagination').style.display = maxPage <= 1 ? 'none' : 'flex';
}

// =========================
// RENDER
// =========================
function render() {
    const filtered = getFilteredItems();
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    items.forEach(item => item.style.display = 'none');
    filtered.slice(start, end).forEach(item => item.style.display = 'block');

    // Empty state
    let emptyMsg = document.getElementById('emptyMsg');
    if (filtered.length === 0) {
        if (!emptyMsg) {
            emptyMsg = document.createElement('p');
            emptyMsg.id = 'emptyMsg';
            emptyMsg.className = 'col-span-3 text-center text-gray-400 py-10 text-sm';
            emptyMsg.textContent = 'Tidak ada hasil yang ditemukan.';
            document.getElementById('specialistGrid').appendChild(emptyMsg);
        }
    } else {
        emptyMsg?.remove();
    }

    updatePagination(filtered.length);
}

// =========================
// EVENT LISTENERS
// =========================
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        currentFilter = btn.getAttribute('data-filter');
        currentPage = 1;
        setActiveButton();
        render();
    });
});

searchInput.addEventListener('input', () => {
    currentSearch = searchInput.value.toLowerCase().trim();
    currentPage = 1;
    render();
});

prevBtn.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        render();
    }
});

nextBtn.addEventListener('click', () => {
    const maxPage = Math.ceil(getFilteredItems().length / itemsPerPage);
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