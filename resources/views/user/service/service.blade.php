@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#fdf0f0] to-white py-20 px-8 md:px-16 pt-30">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        {{-- KIRI: Judul + Filter --}}
        <div class="lg:col-span-5 space-y-8">
            <h1 class="text-6xl md:text-7xl font-bold text-[#3E382D] leading-tight">
                Koleksi <br> Layanan <br> Salon
            </h1>

            <div class="flex flex-wrap gap-3" id="filterBtns">
                <button data-filter="all"
                    class="filter-btn px-8 py-2 bg-[#3E382D] text-white rounded-lg font-medium transition duration-300">
                    All
                </button>

                @foreach($jenisLayanan as $id => $nama)
                <button data-filter="{{ $id }}"
                    class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300">
                    {{ $nama }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- KANAN: Grid Layanan --}}
        <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-8" id="layananGrid">
            @foreach($layanan as $item)
            <a href="{{ route('service.detail', $item->slug) }}"
                class="service-item text-center group block"
                data-jenis="{{ $item->jenis_layanan_id }}">

                <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                    <img
                        src="{{ asset('images/placeholder.jpg') }}"
                        alt="{{ $item->nama_layanan }}"
                        class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}'">
                </div>

                <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                    {{ $item->nama_layanan }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    {{ $item->nama_jenis }} &bull; {{ $item->durasi }} menit
                </p>
            </a>
            @endforeach
        </div>

        {{-- EMPTY --}}
        <p id="emptyMsg" class="lg:col-span-7 hidden text-center text-gray-400 py-10 text-sm">
            Tidak ada layanan ditemukan.
        </p>

        {{-- PAGINATION --}}
        <div class="lg:col-span-12 flex justify-center gap-3 items-center">
            <button id="prevBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#43392f] text-white transition duration-300 hover:scale-110 active:scale-95">
                &lt;
            </button>
            <div id="pages" class="flex gap-3"></div>
            <button id="nextBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#43392f] text-white transition duration-300 hover:scale-110 active:scale-95">
                &gt;
            </button>
        </div>

    </div>
</div>

<script>
const items      = Array.from(document.querySelectorAll('.service-item'));
const filterBtns = document.querySelectorAll('.filter-btn');
const prevBtn    = document.getElementById('prevBtn');
const nextBtn    = document.getElementById('nextBtn');
const pagesDiv   = document.getElementById('pages');
const emptyMsg   = document.getElementById('emptyMsg');

const PER_PAGE = 6;
let currentPage   = 1;
let currentFilter = 'all';

function getFiltered() {
    return items.filter(item => {
        return currentFilter === 'all' || item.dataset.jenis === currentFilter;
    });
}

function setActiveBtn() {
    filterBtns.forEach(btn => {
        const active = btn.dataset.filter == currentFilter;
        btn.classList.toggle('bg-[#3E382D]', active);
        btn.classList.toggle('text-white', active);
        btn.classList.toggle('bg-[#f5eaea]', !active);
        btn.classList.toggle('text-[#3E382D]', !active);
        btn.classList.toggle('border-2', !active);
        btn.classList.toggle('border-gray-400', !active);
    });
}

function render() {
    const filtered = getFiltered();
    const maxPage  = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
    if (currentPage > maxPage) currentPage = maxPage;

    const start = (currentPage - 1) * PER_PAGE;
    const end   = start + PER_PAGE;

    items.forEach(item => item.style.display = 'none');
    filtered.slice(start, end).forEach(item => item.style.display = 'block');

    emptyMsg?.classList.toggle('hidden', filtered.length > 0);
    buildPagination(filtered.length);
}

function buildPagination(total) {
    const maxPage = Math.max(1, Math.ceil(total / PER_PAGE));
    pagesDiv.innerHTML = '';

    for (let i = 1; i <= maxPage; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'page-btn w-10 h-10 rounded-md border border-[#43392f] transition duration-300 '
            + (i === currentPage ? 'bg-[#43392f] text-white shadow-md' : 'text-[#43392f]');
        btn.addEventListener('click', () => { currentPage = i; render(); });
        pagesDiv.appendChild(btn);
    }

    prevBtn.classList.toggle('opacity-50', currentPage === 1);
    prevBtn.classList.toggle('cursor-not-allowed', currentPage === 1);
    nextBtn.classList.toggle('opacity-50', currentPage >= maxPage);
    nextBtn.classList.toggle('cursor-not-allowed', currentPage >= maxPage);
}

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        currentFilter = btn.dataset.filter;
        currentPage   = 1;
        setActiveBtn();
        render();
    });
});

prevBtn.addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; render(); }
});

nextBtn.addEventListener('click', () => {
    const maxPage = Math.ceil(getFiltered().length / PER_PAGE);
    if (currentPage < maxPage) { currentPage++; render(); }
});

setActiveBtn();
render();
</script>

@endsection