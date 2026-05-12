@extends('user.app')

@section('content')

{{--
    Mapping jenis_layanan_id dari database:
    1 = Perawatan Rambut
    2 = Perawatan Tangan & Kaki (Reflexology, Manicure, Pedicure)
    3 = Perawatan Wajah (Facial)
    4 = Perawatan Tubuh (Body Treatment)
    5 = Waxing
--}}

<div class="min-h-screen bg-gradient-to-b from-[#fdf0f0] to-white py-20 px-8 md:px-16 pt-30">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        {{-- JUDUL + FILTER --}}
        <div class="lg:col-span-5 space-y-8">
            <h1 class="text-6xl md:text-7xl font-bold text-[#3E382D] leading-tight">
                Koleksi <br> Layanan <br> Salon
            </h1>

            <div class="flex flex-wrap gap-3" id="filterBtns">
                <button class="filter-btn px-8 py-2 bg-[#3E382D] text-white rounded-lg font-medium transition duration-300" data-filter="all">
                    All
                </button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="hair">
                    Hair
                </button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="facial">
                    Facial
                </button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="body">
                    Body
                </button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="waxing">
                    Waxing
                </button>
                @endforeach
            </div>
        </div>

        {{-- CARD GRID --}}
        <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-8" id="serviceGrid">

            {{-- Perawatan Rambut - jenis_layanan_id = 1 --}}
            <a href="{{ route('service.detail', 1) }}" data-category="hair" class="service-card text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?q=80&w=500"
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                         alt="Perawatan Rambut">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Perawatan Rambut
                </p>
            </a>

            {{-- Perawatan Wajah - jenis_layanan_id = 3 --}}
            <a href="{{ route('service.detail', 3) }}" data-category="facial" class="service-card text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1596178065887-1198b6148b2b?q=80&w=500"
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                         alt="Perawatan Wajah">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Perawatan Wajah
                </p>
            </a>

            {{-- Perawatan Tangan & Kaki - jenis_layanan_id = 2 --}}
            <a href="{{ route('service.detail', 2) }}" data-category="body" class="service-card text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=500"
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                         alt="Perawatan Tangan & Kaki">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Perawatan Tangan & Kaki
                </p>
            </a>

            {{-- Perawatan Tubuh - jenis_layanan_id = 4 --}}
            <a href="{{ route('service.detail', 4) }}" data-category="body" class="service-card text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?q=80&w=500"
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                         alt="Perawatan Tubuh">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Perawatan Tubuh
                </p>
            </a>

            {{-- Waxing - jenis_layanan_id = 5 --}}
            <a href="{{ route('service.detail', 5) }}" data-category="waxing" class="service-card text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc2069?q=80&w=500"
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                         alt="Waxing">
                </div>
        </div>

        {{-- PAGINATION --}}
        <div class="col-span-2 flex justify-center gap-3 items-center -mt-30">
            <button id="prevBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#43392f] text-white transition duration-300 hover:scale-110 active:scale-95">
                &lt;
            </button>
            <button class="page-btn w-10 h-10 rounded-md border border-[#43392f] text-[#43392f] transition duration-300 hover:bg-[#43392f] hover:text-white hover:scale-105">
                1
            </button>
            <button class="page-btn w-10 h-10 rounded-md border border-[#43392f] text-[#43392f] transition duration-300 hover:bg-[#43392f] hover:text-white hover:scale-105">
                2
            </button>
            <button id="nextBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#43392f] text-white transition duration-300 hover:scale-110 active:scale-95">
                &gt;
            </button>
        </div>

    </div>
</div>

<script>
const filterBtns = document.querySelectorAll('.filter-btn');
const serviceCards = document.querySelectorAll('.service-card');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const filter = btn.getAttribute('data-filter');

        // Update active button style
        filterBtns.forEach(b => {
            b.classList.remove('bg-[#3E382D]', 'text-white');
            b.classList.add('bg-[#f5eaea]', 'text-[#3E382D]', 'border-2', 'border-gray-400');
        });
        btn.classList.remove('bg-[#f5eaea]', 'text-[#3E382D]', 'border-2', 'border-gray-400');
        btn.classList.add('bg-[#3E382D]', 'text-white');

        // Filter cards
        serviceCards.forEach(card => {
            const category = card.getAttribute('data-category');
            if (filter === 'all' || category === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Pagination
const pageBtns = document.querySelectorAll('.page-btn');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
let currentPage = 1;

function updatePaginationUI() {
    pageBtns.forEach((btn, index) => {
        if (index + 1 === currentPage) {
            btn.classList.add('bg-[#43392f]', 'text-white', 'shadow-md');
            btn.classList.remove('border', 'text-[#43392f]');
        } else {
            btn.classList.remove('bg-[#43392f]', 'text-white', 'shadow-md');
            btn.classList.add('border', 'border-[#43392f]', 'text-[#43392f]');
        }
    });

    prevBtn.classList.toggle('opacity-50', currentPage === 1);
    prevBtn.classList.toggle('cursor-not-allowed', currentPage === 1);
    nextBtn.classList.toggle('opacity-50', currentPage === pageBtns.length);
    nextBtn.classList.toggle('cursor-not-allowed', currentPage === pageBtns.length);
}

pageBtns.forEach((btn, index) => {
    btn.addEventListener('click', () => { currentPage = index + 1; updatePaginationUI(); });
});
prevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; updatePaginationUI(); } });
nextBtn.addEventListener('click', () => { if (currentPage < pageBtns.length) { currentPage++; updatePaginationUI(); } });

setActiveBtn();
render();
</script>

@endsection