@extends('user.app')

@section('content')

<style>
/* ── Google Font ── */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap');

:root {
    --cream:   #FFF8F5;
    --blush:   #FFE4E6;
    --rose:    #E99688;
    --rose-dk: #d4806f;
    --sand:    #3E382D;
    --sand-lt: #6b6356;
    --card-bg: #FFFFFF;
}

.gallery-page * { box-sizing: border-box; }

.gallery-page {
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    color: var(--sand);
}

/* ── HERO ── */
.hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 0 1.5rem;
    overflow: hidden;
    background: linear-gradient(160deg, #FFE4E6 0%, #FFF1F0 45%, #FFF8F5 100%);
}

/* decorative blobs */
.hero::before,
.hero::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.hero::before {
    width: 520px; height: 520px;
    background: radial-gradient(circle, #f5c6be55 0%, transparent 70%);
    top: -120px; right: -100px;
}
.hero::after {
    width: 360px; height: 360px;
    background: radial-gradient(circle, #ffd6d955 0%, transparent 70%);
    bottom: -80px; left: -60px;
}

.hero-inner { position: relative; z-index: 1; max-width: 680px; }

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.7rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--rose);
    margin-bottom: 1.6rem;
    font-weight: 500;
}
.hero-eyebrow span {
    display: inline-block;
    width: 28px; height: 1px;
    background: var(--rose);
}

.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.6rem, 6vw, 4.4rem);
    line-height: 1.12;
    color: var(--sand);
    margin: 0 0 1.4rem;
}
.hero-title em {
    font-style: italic;
    color: var(--rose);
}

.hero-sub {
    font-size: 0.9rem;
    color: var(--sand-lt);
    line-height: 1.7;
    max-width: 480px;
    margin: 0 auto 2.2rem;
    font-weight: 300;
}

.hero-badges {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.72rem;
    font-weight: 500;
    padding: 0.45rem 1rem;
    border-radius: 999px;
    border: 1.5px solid #f0d4d1;
    background: rgba(255,255,255,0.7);
    color: var(--sand-lt);
    backdrop-filter: blur(4px);
}
.hero-badge svg { color: var(--rose); }

/* scroll hint */
.scroll-hint {
    position: absolute;
    bottom: 2.5rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    font-size: 0.65rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #c4a8a4;
    z-index: 1;
}
.scroll-dot {
    width: 1px; height: 40px;
    background: linear-gradient(to bottom, var(--rose), transparent);
    animation: scrollPulse 1.8s ease-in-out infinite;
}
@keyframes scrollPulse {
    0%,100% { opacity: 0.3; transform: scaleY(0.6); }
    50%      { opacity: 1;   transform: scaleY(1); }
}

/* ── GALLERY SECTION ── */
.gallery-section {
    background: linear-gradient(180deg, #FFF8F5 0%, #FFF1F2 50%, #FFE4E6 100%);
    padding: 5rem 1.5rem 6rem;
}

.gallery-wrapper { max-width: 1100px; margin: 0 auto; }

/* Section header */
.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2.5rem;
}
.section-header-line { flex: 1; height: 1px; background: #f0d4d1; }
.section-header-label {
    font-size: 0.65rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--rose);
    font-weight: 500;
    white-space: nowrap;
}

/* toolbar */
.toolbar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 3rem;
}

/* filter pills */
.filter-pills { display: flex; flex-wrap: wrap; gap: 0.5rem; }

.filter-btn {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.45rem 1.1rem;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.2s ease;
    letter-spacing: 0.02em;
}
.filter-btn:hover {
    border-color: var(--rose);
    color: var(--rose);
}
.filter-btn.active {
    background: var(--sand);
    border-color: var(--sand);
    color: #fff;
}

/* search */
.search-wrap {
    position: relative;
    width: 100%;
    max-width: 280px;
}
.search-wrap input {
    width: 100%;
    padding: 0.6rem 2.8rem 0.6rem 1.1rem;
    border: 1.5px solid #e8d0cc;
    border-radius: 999px;
    background: rgba(255,255,255,0.8);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.8rem;
    color: var(--sand);
    outline: none;
    transition: border-color 0.2s;
    backdrop-filter: blur(4px);
}
.search-wrap input::placeholder { color: #c4b0ac; }
.search-wrap input:focus { border-color: var(--rose); }
/* .search-wrap svg {
    position: absolute;
    right: 0.9rem; top: 50%;
    transform: translateY(-50%);
    color: var(--rose);
    pointer-events: none;
    width: 16px; height: 16px;
} */

/* ── CARD GRID ── */
#galleryGrid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.gallery-item {
    background: var(--card-bg);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #f5e6e4;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}
.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 48px -8px rgba(62,56,45,0.12);
}

.card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 220px;
}
.card-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    display: block;
}
.gallery-item:hover .card-img-wrap img { transform: scale(1.05); }

/* category badge on image */
.card-category {
    position: absolute;
    top: 12px; left: 12px;
    font-size: 0.6rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-weight: 600;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    background: rgba(255,255,255,0.88);
    color: var(--sand);
    backdrop-filter: blur(6px);
}

/* card body */
.card-body {
    padding: 1.1rem 1.25rem 1.25rem;
}
.card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--sand);
    margin: 0 0 0.4rem;
    line-height: 1.3;
}
.card-desc {
    font-size: 0.75rem;
    color: var(--sand-lt);
    line-height: 1.6;
    margin: 0 0 1rem;
    font-weight: 300;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.72rem;
    font-weight: 500;
    padding: 0.45rem 1.1rem;
    border-radius: 999px;
    background: var(--sand);
    color: #fff;
    text-decoration: none;
    transition: background 0.2s ease, transform 0.15s ease;
    letter-spacing: 0.03em;
}
.btn-detail:hover { background: var(--rose-dk); transform: translateX(2px); }
.btn-detail svg { width: 12px; height: 12px; transition: transform 0.15s; }
.btn-detail:hover svg { transform: translateX(2px); }

/* ── PAGINATION ── */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 3.5rem;
}
.page-btn, .pg-arrow {
    font-family: 'DM Sans', sans-serif;
    width: 38px; height: 38px;
    border-radius: 10px;
    border: 1.5px solid #e8d0cc;
    background: transparent;
    color: var(--sand-lt);
    font-size: 0.8rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.page-btn:hover, .pg-arrow:hover { border-color: var(--rose); color: var(--rose); }
.page-btn.active { background: var(--sand); border-color: var(--sand); color: #fff; }
.pg-arrow:disabled { opacity: 0.3; cursor: not-allowed; }

/* empty */
#emptyMsg {
    text-align: center;
    padding: 4rem 0;
    font-size: 0.85rem;
    color: #c4b0ac;
}

/* ── ANIMATIONS ── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}
.hero-inner > * {
    animation: fadeUp 0.7s ease both;
}
.hero-eyebrow { animation-delay: 0.05s; }
.hero-title   { animation-delay: 0.15s; }
.hero-sub     { animation-delay: 0.25s; }
.hero-badges  { animation-delay: 0.35s; }

/* ── RESPONSIVE ── */
@media (max-width: 640px) {
    .toolbar { flex-direction: column; align-items: flex-start; }
    .search-wrap { max-width: 100%; }
    #galleryGrid { grid-template-columns: 1fr; }
}
</style>

<div class="gallery-page">

{{-- ══════════════ HERO ══════════════ --}}
<section class="hero">
    <div class="pt-20 md:pt-25 px-4 text-center">
        

        <div class="hero-eyebrow">
            <span></span>
            Galeri Perawatan
            <span></span>
        </div>

        <h1 class="hero-title">
            Hasil Asli,<br><em>Seni yang Disempurnakan</em>
        </h1>

        <p class="hero-sub">
            Tim kami yang terdiri dari tenaga ahli berpengalaman
            memberikan perawatan luar biasa yang dipersonalisasi
            untuk setiap pelanggan.
        </p>

        <div class="hero-badges">
            <span class="hero-badge">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Mengutamakan Keamanan
            </span>
            <span class="hero-badge">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 3l14 9-14 9V3z"/>
                </svg>
                Hasil yang Natural
            </span>
            <span class="hero-badge">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Terbukti Berpengalaman
            </span>
        </div>

    </div>

</section>

{{-- ══════════════ GALLERY ══════════════ --}}
<section class="gallery-section">
    <div class="gallery-wrapper">

        @if($albums->isEmpty())
            <div id="emptyMsg">Belum ada galeri yang tersedia.</div>
        @else

        @php
            $roles = $albums->pluck('nama_jenis')->unique()->filter()->values();
        @endphp

        {{-- Section label --}}
        <div class="section-header">
            <div class="section-header-line"></div>
            <span class="section-header-label">Koleksi Kami</span>
            <div class="section-header-line"></div>
        </div>

        {{-- Toolbar --}}
<div class="toolbar flex flex-wrap justify-between items-center gap-4 mb-8">

    {{-- Filter Pills --}}
    <div class="filter-pills flex flex-wrap gap-2" id="filterBtns">
        {{-- Tombol Aktif (Semua) --}}
        <button data-filter="all"
                class="filter-btn active px-4 py-2 rounded-full text-xs font-semibold border transition-all duration-200
                       bg-gradient-to-r from-rose-400 to-pink-400 border-transparent text-white shadow-md shadow-rose-200/50">
            Semua
        </button>

        {{-- Tombol Lainnya (Inactive) --}}
        @foreach($roles as $role)
            <button data-filter="{{ strtolower($role) }}"
                    class="filter-btn px-4 py-2 rounded-full text-xs font-semibold border border-rose-200 text-rose-500
                           bg-white hover:bg-rose-50 hover:border-rose-300 transition-all duration-200 capitalize">
                {{ ucfirst($role) }}
            </button>
        @endforeach
    </div>

    {{-- Search Input --}}
    <div class="search-wrap relative w-full sm:w-auto max-w-[260px]">
    <input type="text" id="searchInput" placeholder="Cari layanan…"
           class="w-full h-[42px] bg-white border border-rose-200 rounded-full pl-4 pr-11 text-sm text-[#3E382D]
                  placeholder-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-300/50 focus:border-rose-400 transition-all">
    
    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-rose-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
</div>

</div>

        {{-- Cards --}}
        <div id="galleryGrid">
            @foreach ($albums as $index => $item)
            <div class="gallery-item"
                 data-role="{{ strtolower($item->nama_jenis ?? '') }}"
                 data-name="{{ strtolower($item->nama_layanan ?? '') }}"
                 data-desc="{{ strtolower($item->album_deskripsi ?? '') }}"
                 data-index="{{ $index }}">

                <div class="card-img-wrap">
                    <img src="{{ $item->cover_foto ? asset($item->cover_foto) : ($item->layanan_cover ? asset($item->layanan_cover) : asset('layanan/default.jpg')) }}"
                         alt="{{ $item->nama_layanan }}"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                    <span class="card-category">{{ $item->nama_jenis }}</span>
                </div>

                <div class="card-body">
                    <h3 class="card-title">{{ $item->nama_layanan }}</h3>
                    <p class="card-desc">{{ $item->album_deskripsi }}</p>
                    <div class="card-footer">
                        @php
                            $detailId = !empty($item->album_id) ? $item->album_id : $item->layanan_id;
                        @endphp
                        <a href="{{ route('gallery.detail', $item->slug) }}" class="btn-detail">
                            Lihat Detail
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Empty state --}}
        <p id="emptyMsg" class="hidden" style="text-align:center;padding:4rem 0;font-size:.85rem;color:#c4b0ac;">
            Tidak ada hasil yang ditemukan.
        </p>

        {{-- Pagination --}}
        <div class="pagination">
            <button id="prevBtn" class="pg-arrow" aria-label="Sebelumnya">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <div id="pages" style="display:flex;gap:.4rem;"></div>
            <button id="nextBtn" class="pg-arrow" aria-label="Berikutnya">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        @endif
    </div>
</section>

</div>{{-- .gallery-page --}}

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

function getFiltered() {
    return items.filter(item => {
        const role = item.dataset.role ?? '';
        const name = item.dataset.name ?? '';
        const desc = item.dataset.desc ?? '';
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
        btn.classList.toggle('active', active);
    });
}

function render() {
    const filtered = getFiltered();
    const maxPage  = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));
    if (currentPage > maxPage) currentPage = maxPage;

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end   = start + ITEMS_PER_PAGE;

    items.forEach(item => (item.style.display = 'none'));
    filtered.slice(start, end).forEach((item, i) => {
        item.style.display = 'block';
        item.style.animationDelay = `${i * 0.05}s`;
        item.style.animation = 'none';
        void item.offsetWidth; // reflow
        item.style.animation = `fadeUp 0.4s ease ${i * 0.05}s both`;
    });

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
            btn.className = 'page-btn' + (i === currentPage ? ' active' : '');
            btn.addEventListener('click', () => { currentPage = i; render(); });
            pagesDiv.appendChild(btn);
        }
    }

    if (prevBtn) prevBtn.disabled = currentPage === 1;
    if (nextBtn) nextBtn.disabled = currentPage >= maxPage;
}

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

setActiveButton();
render();
</script>

@endsection