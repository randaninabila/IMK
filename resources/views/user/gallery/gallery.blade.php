@extends('user.app')

@section('content')

{{-- HERO --}}
<section class="bg-gradient-to-b from-[#FFE4E6] to-white min-h-screen flex items-center justify-center text-center px-4">
    <div>
        <h1 class="text-4xl md:text-5xl font-bold text-[#3E382D] mb-6">
            Real Result, Refined Artistry
        </h1>
        <p class="text-tertiary-500 max-w-xl mx-auto mb-6">
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

{{-- FILTER + SEARCH + GRID --}}
<section class="bg-gradient-to-b from-white via-[#FFF1F2] to-[#FFE4E6] py-10 px-6">
    <div class="max-w-6xl mx-auto">

        @if($albums->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <p class="text-lg">Belum ada galeri yang tersedia.</p>
            </div>
        @else

        @php
            $roles = $albums->pluck('nama_jenis')->unique()->filter()->values();
        @endphp

        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-4">

            {{-- FILTER BUTTONS --}}
            <div class="flex flex-wrap gap-3" id="filterBtns">
                <button data-filter="all"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#3E382D] text-white">
                    All Gallery
                </button>
                @foreach($roles as $role)
                <button data-filter="{{ strtolower($role) }}"
                    class="filter-btn px-5 py-2 rounded-md border border-[#3E382D] bg-[#f5eaea] text-[#3E382D] capitalize">
                    {{ ucfirst($role) }}
                </button>
                @endforeach
            </div>

            {{-- SEARCH --}}
            <div class="relative w-full max-w-xs">
                <input type="text" id="searchInput" placeholder="Search..."
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
        <div class="grid md:grid-cols-3 gap-6" id="galleryGrid">
            @foreach ($albums as $album)
            <div class="gallery-item bg-white rounded-xl p-3 shadow hover:shadow-lg transition"
                data-role="{{ strtolower($album->nama_jenis ?? '') }}"
                data-name="{{ strtolower($album->nama_layanan ?? '') }}"
                data-desc="{{ strtolower($album->layanan_deskripsi ?? $album->album_deskripsi ?? '') }}">

                {{-- Cover foto --}}
                <img
                    src="{{ $album->cover_foto ? asset($album->cover_foto) : asset('images/placeholder.jpg') }}"
                    alt="{{ $album->nama_layanan }}"
                    class="rounded-lg mb-3 w-full h-44 object-cover"
                    onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}'">

                {{-- Badge jenis --}}
                <span class="text-[10px] uppercase px-2 py-1 rounded-full bg-gray-100 text-gray-600 capitalize">
                    {{ $album->nama_jenis ?? '-' }}
                </span>

                {{-- Nama layanan --}}
                <h3 class="font-semibold text-sm text-[#3E382D] mt-2">
                    {{ $album->nama_layanan ?? 'Layanan' }}
                </h3>

                {{-- Deskripsi --}}
                <p class="text-xs text-[#3E382D] mt-1 mb-3 line-clamp-2">
                    {{ $album->layanan_deskripsi ?? $album->album_deskripsi ?? '-' }}
                </p>

                {{-- Tombol detail --}}
                <div class="flex justify-end">
                    <a href="{{ route('gallery.detail', $album->slug) }}"
                        class="bg-[#e9bcbc] hover:bg-[#dca9a9] text-white text-xs py-1 px-3 rounded transition">
                        View Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- EMPTY MESSAGE --}}
        <p id="emptyMsg" class="hidden text-center text-gray-400 py-10 text-sm">
            Tidak ada hasil yang ditemukan.
        </p>

        {{-- PAGINATION --}}
        <div class="flex justify-center items-center gap-3 mt-10">
            <button id="prevBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#3E382D] text-white">«</button>
            <div id="pages" class="flex gap-3"></div>
            <button id="nextBtn"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-[#3E382D] text-white">»</button>
        </div>

        @endif
    </div>
</section>

<script>
const items       = Array.from(document.querySelectorAll('.gallery-item'));
const filterBtns  = document.querySelectorAll('.filter-btn');
const prevBtn     = document.getElementById('prevBtn');
const nextBtn     = document.getElementById('nextBtn');
const pagesDiv    = document.getElementById('pages');
const searchInput = document.getElementById('searchInput');
const emptyMsg    = document.getElementById('emptyMsg');

const ITEMS_PER_PAGE = 6;
let currentPage   = 1;
let currentFilter = localStorage.getItem('gallery_filter') || 'all';
let currentSearch = '';

// ── Helpers ───────────────────────────────────────────
function getFiltered() {
    return items.filter(item => {
        const role = item.dataset.role  ?? '';
        const name = item.dataset.name  ?? '';
        const desc = item.dataset.desc  ?? '';

        const matchFilter = currentFilter === 'all' || role === currentFilter;
        const matchSearch = currentSearch === ''
            || name.includes(currentSearch)
            || desc.includes(currentSearch)
            || role.includes(currentSearch);

        return matchFilter && matchSearch;
    });
}

function setActiveButton() {
    filterBtns.forEach(btn => {
        const active = btn.dataset.filter === currentFilter;
        btn.classList.toggle('bg-[#3E382D]', active);
        btn.classList.toggle('text-white',   active);
        btn.classList.toggle('bg-[#f5eaea]', !active);
        btn.classList.toggle('text-[#3E382D]', !active);
    });
}

// ── Render ────────────────────────────────────────────
function render() {
    const filtered = getFiltered();
    const maxPage  = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));
    if (currentPage > maxPage) currentPage = maxPage;

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end   = start + ITEMS_PER_PAGE;

    items.forEach(item => (item.style.display = 'none'));
    filtered.slice(start, end).forEach(item => (item.style.display = 'block'));

    emptyMsg?.classList.toggle('hidden', filtered.length > 0);
    buildPagination(filtered.length);
}

function buildPagination(total) {
    const maxPage = Math.max(1, Math.ceil(total / ITEMS_PER_PAGE));

    if (pagesDiv) {
        pagesDiv.innerHTML = '';
        for (let i = 1; i <= maxPage; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className   = 'page-btn w-10 h-10 rounded-md border'
                + (i === currentPage ? ' bg-[#3E382D] text-white' : ' text-[#3E382D]');
            btn.addEventListener('click', () => { currentPage = i; render(); });
            pagesDiv.appendChild(btn);
        }
    }

    prevBtn?.classList.toggle('opacity-50',       currentPage === 1);
    prevBtn?.classList.toggle('cursor-not-allowed', currentPage === 1);
    nextBtn?.classList.toggle('opacity-50',       currentPage >= maxPage);
    nextBtn?.classList.toggle('cursor-not-allowed', currentPage >= maxPage);
}

// ── Event Listeners ───────────────────────────────────
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        currentFilter = btn.dataset.filter;
        currentPage   = 1;
        localStorage.setItem('gallery_filter', currentFilter);
        setActiveButton();
        render();
    });
});

searchInput?.addEventListener('input', () => {
    currentSearch = searchInput.value.toLowerCase().trim();
    currentPage   = 1;
    render();
});

prevBtn?.addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; render(); }
});

nextBtn?.addEventListener('click', () => {
    const maxPage = Math.ceil(getFiltered().length / ITEMS_PER_PAGE);
    if (currentPage < maxPage) { currentPage++; render(); }
});

// ── Init ──────────────────────────────────────────────
setActiveButton();
render();
</script>

@endsection