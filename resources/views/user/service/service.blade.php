@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#fdf0f0] to-white py-20 px-8 md:px-16 pt-30">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        {{-- JUDUL + FILTER --}}
        <div class="lg:col-span-5 space-y-8">
            <h1 class="text-6xl md:text-7xl font-bold text-[#3E382D] leading-tight">
                Koleksi <br> Layanan <br> Salon
            </h1>

            <div class="flex flex-wrap gap-3" id="filterBtns">
                <button class="filter-btn px-8 py-2 bg-[#3E382D] text-white rounded-lg font-medium transition duration-300" data-filter="all">All</button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="hair">Hair</button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="facial">Facial</button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="body">Body</button>
                <button class="filter-btn px-8 py-2 border-2 border-gray-400 bg-[#f5eaea] text-[#3E382D] rounded-lg transition duration-300" data-filter="waxing">Waxing</button>
            </div>
        </div>

        {{-- CARD GRID --}}
        @php
            $itemsPerPage = 6;
            $totalItems = $jenisLayanan->count();
            $totalPages = max(1, ceil($totalItems / $itemsPerPage));
            $kategoriMap = [1 => 'hair', 2 => 'body', 3 => 'facial', 4 => 'body', 5 => 'waxing'];
        @endphp

        <div id="serviceGrid" class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($jenisLayanan as $index => $jenis)
                @php
                    $page = floor($index / $itemsPerPage) + 1;
                @endphp
                <a href="{{ route('service.detail', $jenis->jenis_layanan_id) }}"
                   data-category="{{ $kategoriMap[$jenis->jenis_layanan_id] ?? 'body' }}"
                   data-page="{{ $page }}"
                   class="service-card text-center group block">
                    <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
                        <img src="{{ $covers[$jenis->jenis_layanan_id] ?? asset('layanan/default.jpg') }}"
                             class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                             alt="{{ $jenis->nama_jenis }}"
                             onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">
                    </div>
                    <p class="mt-4 text-xl font-bold text-[#43392f] group-hover:text-pink-600">
                        {{ $jenis->nama_jenis }}
                    </p>
                </a>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="col-span-2 flex justify-center gap-3 items-center mt-10">
            <button id="prevBtn" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#43392f] text-white transition duration-300 hover:scale-110 active:scale-95">&lt;</button>

            @for($i = 1; $i <= $totalPages; $i++)
                <button class="page-btn w-10 h-10 rounded-md border border-[#43392f] text-[#43392f] transition duration-300 hover:bg-[#43392f] hover:text-white hover:scale-105"
                        data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            <button id="nextBtn" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#43392f] text-white transition duration-300 hover:scale-110 active:scale-95">&gt;</button>
        </div>

    </div>
</div>

<script>
const filterBtns = document.querySelectorAll('.filter-btn');
const serviceCards = document.querySelectorAll('.service-card');
const pageBtns = document.querySelectorAll('.page-btn');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

let currentPage = 1;
const itemsPerPage = {{ $itemsPerPage }};
const totalPages = {{ $totalPages }};

function showPage(page) {
    currentPage = page;
    updatePaginationUI();
    serviceCards.forEach(card => {
        card.style.display = parseInt(card.getAttribute('data-page')) === page ? 'block' : 'none';
    });
}

function updatePaginationUI() {
    pageBtns.forEach(btn => {
        const page = parseInt(btn.getAttribute('data-page'));
        if (page === currentPage) {
            btn.classList.add('bg-[#43392f]', 'text-white', 'shadow-md');
            btn.classList.remove('border', 'text-[#43392f]');
        } else {
            btn.classList.remove('bg-[#43392f]', 'text-white', 'shadow-md');
            btn.classList.add('border', 'border-[#43392f]', 'text-[#43392f]');
        }
    });
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
    prevBtn.classList.toggle('opacity-50', prevBtn.disabled);
    nextBtn.classList.toggle('opacity-50', nextBtn.disabled);
    prevBtn.classList.toggle('cursor-not-allowed', prevBtn.disabled);
    nextBtn.classList.toggle('cursor-not-allowed', nextBtn.disabled);
}

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const filter = btn.getAttribute('data-filter');
        filterBtns.forEach(b => {
            b.classList.remove('bg-[#3E382D]', 'text-white');
            b.classList.add('bg-[#f5eaea]', 'text-[#3E382D]', 'border-2', 'border-gray-400');
        });
        btn.classList.remove('bg-[#f5eaea]', 'text-[#3E382D]', 'border-2', 'border-gray-400');
        btn.classList.add('bg-[#3E382D]', 'text-white');
        currentPage = 1;
        serviceCards.forEach(card => {
            const category = card.getAttribute('data-category');
            if (filter === 'all' || category === filter) {
                card.style.display = card.getAttribute('data-page') == 1 ? 'block' : 'none';
            } else {
                card.style.display = 'none';
            }
        });
        updatePaginationUI();
    });
});

pageBtns.forEach(btn => btn.addEventListener('click', () => showPage(parseInt(btn.getAttribute('data-page')))));
prevBtn.addEventListener('click', () => { if (currentPage > 1) showPage(currentPage - 1); });
nextBtn.addEventListener('click', () => { if (currentPage < totalPages) showPage(currentPage + 1); });

showPage(1);
</script>

@endsection