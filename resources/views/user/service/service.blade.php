@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#fdf0f0] to-white py-20 px-8 md:px-16 pt-30">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <div class="lg:col-span-5 space-y-8">
            <h1 class="text-6xl md:text-7xl font-bold text-[#3E382D] leading-tight">
                Koleksi <br> Layanan <br> Salon
            </h1>

            <div class="flex flex-wrap gap-3" id="filterBtns">
                <button class="filter-btn px-8 py-2 bg-[#3E382D] text-white rounded-lg font-medium transition duration-300">
                    All
                </button>

                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300">
                    Hair
                </button>

                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300">
                    Facial
                </button>

                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300">
                    Meni Pedi
                </button>

                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300">
                    Waxing
                </button>
            </div>
        </div>

        <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-8">
            <a href="/sdetail" class="text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=500" 
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Reflexology
                </p>
            </a>

            <a href="/services/waxing" class="text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1596178065887-1198b6148b2b?q=80&w=500" 
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Waxing
                </p>
            </a>

            <a href="/services/bekam" class="text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?q=80&w=500" 
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Bekam
                </p>
            </a>

            <a href="/services/package" class="text-center group block">
                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc2069?q=80&w=500" 
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    Package Treatment
                </p>
            </a>
        </div>

        <div class="col-span-2 flex justify-center gap-3 items-center -mt-30">
            <button id="prevBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#43392f] text-white transition duration-300 hover:scale-110 active:scale-95">
                &lt;
            </button>

            <button class="page-btn w-10 h-10 rounded-md border border-[#43392f] text-[#43392f]
                transition duration-300 hover:bg-[#43392f] hover:text-white hover:scale-105">
                1
            </button>

            <button class="page-btn w-10 h-10 rounded-md border border-[#43392f] text-[#43392f]
                transition duration-300 hover:bg-[#43392f] hover:text-white hover:scale-105">
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
/* =========================
   FILTER BUTTON JS UPDATED
========================= */
const filterBtns = document.querySelectorAll('.filter-btn');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => {
            // Reset ke state tidak aktif dengan warna #3E382D
            b.classList.remove('bg-[#3E382D]', 'text-white');
            b.classList.add('bg-[#f5eaea]', 'text-[#3E382D]', 'border-2', 'border-gray-400');
        });

        // Set ke state aktif dengan warna #3E382D
        btn.classList.remove('bg-[#f5eaea]', 'text-[#3E382D]', 'border-2', 'border-gray-400');
        btn.classList.add('bg-[#3E382D]', 'text-white');
    });
});

/* =========================
   PAGINATION JS (Warna tetap #43392f sesuai kode awal)
========================= */
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

    if (currentPage === 1) {
        prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    if (currentPage === pageBtns.length) {
        nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

pageBtns.forEach((btn, index) => {
    btn.addEventListener('click', () => {
        currentPage = index + 1;
        updatePaginationUI();
    });
});

prevBtn.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        updatePaginationUI();
    }
});

nextBtn.addEventListener('click', () => {
    if (currentPage < pageBtns.length) {
        currentPage++;
        updatePaginationUI();
    }
});

updatePaginationUI();
</script>

@endsection