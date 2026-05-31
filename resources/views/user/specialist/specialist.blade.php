@extends('user.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .sp-page { font-family: 'Plus Jakarta Sans', sans-serif; }
    .sp-page .display { font-family: 'Playfair Display', serif; }

    /* Hero badge */
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        background: white;
        border: 1.5px solid #f5c6be;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #c47878;
    }

    /* Search */
    .search-wrap {
        position: relative;
        width: 100%;
    }
    .search-wrap input {
        width: 100%;
        padding: 14px 52px 14px 20px;
        background: white;
        border: 2px solid #f5c6be;
        border-radius: 10px;
        font-size: 14px;
        color: #3E382D;
        outline: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-wrap input:focus {
        border-color: #e9bcbc;
        box-shadow: 0 0 0 4px rgba(233,188,188,0.15);
    }
    .search-wrap input::placeholder { color: #c4b8b8; }
    .search-wrap svg {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: #e9bcbc;
        pointer-events: none;
    }

    /* Card */
    .sp-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1.5px solid #fce7e7;
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
    }
    .sp-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(233,108,108,0.18);
    }
    .sp-card .photo-wrap {
        position: relative;
        overflow: hidden;
        height: 220px;
    }
    .sp-card .photo-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .sp-card:hover .photo-wrap img {
        transform: scale(1.05);
    }
    .sp-card .photo-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(62,56,45,0.5) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .sp-card:hover .photo-overlay { opacity: 1; }

    .sp-badge {
        display: inline-block;
        padding: 3px 10px;
        background: #fff0f0;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 600;
        color: #c47878;
        letter-spacing: 0.05em;
    }

    .view-btn {
        display: block;
        text-align: center;
        width: 100%;
        padding: 10px;
        background: #3E382D;
        color: white;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.03em;
        transition: background 0.2s ease, transform 0.15s ease;
    }
    .view-btn:hover {
        background: #5a5347;
        transform: translateY(-1px);
    }

    /* Pagination */
    .page-btn {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: 1.5px solid #fce7e7;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .page-btn:hover { background: #fff0f0; }
    .page-btn.active { background: #3E382D; color: white; border-color: #3E382D; }

    .nav-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
    }
    .nav-btn.active-nav { background: #3E382D; color: white; }
    .nav-btn.disabled-nav { background: #f3f0ee; color: #c4b8b8; cursor: not-allowed; }

    /* Stats bar */
    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        border-radius: 100px;
        border: 1.5px solid #fce7e7;
        font-size: 13px;
        font-weight: 500;
        color: #3E382D;
    }
    .stat-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #e9bcbc;
        flex-shrink: 0;
    }
</style>

<div class="sp-page bg-[#faf7f5] min-h-screen">

    {{-- ═══ HERO ═══ --}}
    <section class="relative bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-[#faf7f5] pt-28 pb-16 px-6 text-center overflow-hidden">

        {{-- Dekorasi lingkaran blur --}}
        <div class="absolute top-10 left-1/4 w-64 h-64 bg-rose-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-pink-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-3xl mx-auto">
            <div class="hero-badge mb-5">
                <span>✦</span> Spesialis Bersertifikat
            </div>

            <h1 class="display text-5xl md:text-6xl text-[#3E382D] mb-4 leading-tight">
                Tim <em>Spesialis</em> Kami
            </h1>

            <p class="text-gray-500 max-w-xl mx-auto text-[15px] leading-relaxed mb-8">
                Kenali para profesional kami yang siap membantu Anda tampil lebih percaya diri melalui layanan yang ramah, aman, dan berkualitas tinggi.
            </p>

            {{-- Stats --}}
            <div class="flex flex-wrap justify-center gap-3">
                <div class="stat-item">
                    <span class="stat-dot"></span> Tenaga Ahli Bersertifikat
                </div>
                <div class="stat-item">
                    <span class="stat-dot"></span> 3+ Tahun Pengalaman
                </div>
                <div class="stat-item">
                    <span class="stat-dot"></span> 1000+ Pelanggan Puas
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ SEARCH + GRID ═══ --}}
    <section class="py-12 px-6">
        <div class="max-w-6xl mx-auto">

            {{-- Search bar full width --}}
            <div class="search-wrap mb-10">
                <input type="text" id="searchInput" placeholder="Cari nama spesialis…">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Card grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="specialistGrid">

                @forelse($specialists as $index => $specialist)
                <div class="sp-card specialist-item"
                     data-name="{{ strtolower($specialist->nama) }}"
                     data-index="{{ $index }}">

                    <div class="photo-wrap">
                        <img src="{{ $specialist->foto
                            ? asset($specialist->foto)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($specialist->nama) . '&background=FFE4E6&color=3E382D&size=400' }}"
                             alt="{{ $specialist->nama }}">
                        <div class="photo-overlay"></div>
                    </div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h3 class="font-bold text-[#3E382D] text-base leading-tight">
                                {{ $specialist->nama }}
                            </h3>
                            <span class="sp-badge flex-shrink-0">Spesialis</span>
                        </div>

                        <p class="text-[13px] text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                            Tenaga profesional berpengalaman di Salon Muslimah Dina.
                        </p>

                        <a href="{{ route('specialist.show', $specialist->pegawai_id) }}"
                           class="view-btn">
                            Lihat Profil
                        </a>
                    </div>
                </div>

                @empty
                <p class="col-span-3 text-center text-gray-400 py-16 text-sm">
                    Belum ada data spesialis.
                </p>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="flex justify-center items-center gap-2 mt-10" id="pagination">
                <button id="prevBtn" class="nav-btn disabled-nav">←</button>
                <div id="pages" class="flex gap-2"></div>
                <button id="nextBtn" class="nav-btn active-nav">→</button>
            </div>

        </div>
    </section>

</div>

<script>
const items = Array.from(document.querySelectorAll('.specialist-item'));
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const searchInput = document.getElementById('searchInput');

let currentPage = 1;
const itemsPerPage = 6;
let currentSearch = '';

function getFilteredItems() {
    return items.filter(item => {
        const name = item.getAttribute('data-name') ?? '';
        return currentSearch === '' || name.includes(currentSearch);
    });
}

function updatePagination(totalItems) {
    const maxPage = Math.ceil(totalItems / itemsPerPage) || 1;
    const pagesContainer = document.getElementById('pages');
    pagesContainer.innerHTML = '';

    for (let i = 1; i <= maxPage; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'page-btn' + (i === currentPage ? ' active' : '');
        btn.addEventListener('click', () => { currentPage = i; render(); });
        pagesContainer.appendChild(btn);
    }

    const isFirst = currentPage <= 1;
    const isLast  = currentPage >= maxPage;

    prevBtn.className = 'nav-btn ' + (isFirst ? 'disabled-nav' : 'active-nav');
    nextBtn.className = 'nav-btn ' + (isLast  ? 'disabled-nav' : 'active-nav');

    document.getElementById('pagination').style.display = maxPage <= 1 ? 'none' : 'flex';
}

function render() {
    const filtered = getFilteredItems();
    const start = (currentPage - 1) * itemsPerPage;
    const end   = start + itemsPerPage;

    items.forEach(item => item.style.display = 'none');
    filtered.slice(start, end).forEach(item => item.style.display = 'block');

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

searchInput.addEventListener('input', () => {
    currentSearch = searchInput.value.toLowerCase().trim();
    currentPage = 1;
    render();
});

prevBtn.addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; render(); }
});

nextBtn.addEventListener('click', () => {
    if (currentPage < Math.ceil(getFilteredItems().length / itemsPerPage)) {
        currentPage++; render();
    }
});

render();
</script>

@endsection