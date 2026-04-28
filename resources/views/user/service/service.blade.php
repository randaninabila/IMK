@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#fdf0f0] to-white py-20 px-8 md:px-16 font-serif">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <div class="lg:col-span-5 space-y-10">
            <h1 class="text-6xl md:text-7xl font-bold text-[#43392f] leading-tight">
                Koleksi <br> Layanan <br> Salon
            </h1>

            <div class="flex flex-wrap gap-3" id="filterBtns">
    <!-- ACTIVE -->
    <button class="filter-btn px-8 py-2 bg-[#43392f] text-white rounded-lg font-medium transition duration-300">
        All
    </button>

    <!-- NON ACTIVE -->
    <button class="filter-btn px-8 py-2 border-2 border-[#43392f] bg-[#f5f1e8] text-[#43392f] rounded-lg transition duration-300">
        Hair
    </button>
    <button class="filter-btn px-8 py-2 border-2 border-[#43392f] bg-[#f5f1e8] text-[#43392f] rounded-lg transition duration-300">
        Facial
    </button>
    <button class="filter-btn px-8 py-2 border-2 border-[#43392f] bg-[#f5f1e8] text-[#43392f] rounded-lg transition duration-300">
        Meni Pedi
    </button>
    <button class="filter-btn px-8 py-2 border-2 border-[#43392f] bg-[#f5f1e8] text-[#43392f] rounded-lg transition duration-300">
        Waxing
    </button>
</div>
        </div>

        <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <a href="/sdetail" class="text-center group block">
        <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
            <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=500" 
                 alt="Reflexology" 
                 class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
        </div>
        <p class="mt-4 text-xl font-bold text-[#43392f] transition-colors duration-300 group-hover:text-pink-600">
            Reflexology
        </p>
    </a>

    <a href="/services/waxing" class="text-center group block">
        <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
            <img src="https://images.unsplash.com/photo-1596178065887-1198b6148b2b?q=80&w=500" 
                 alt="Waxing" 
                 class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
        </div>
        <p class="mt-4 text-xl font-bold text-[#43392f] transition-colors duration-300 group-hover:text-pink-600">
            Waxing
        </p>
    </a>

    <a href="/services/bekam" class="text-center group block">
        <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
            <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?q=80&w=500" 
                 alt="Bekam" 
                 class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
        </div>
        <p class="mt-4 text-xl font-bold text-[#43392f] transition-colors duration-300 group-hover:text-pink-600">
            Bekam
        </p>
    </a>

    <a href="/services/package" class="text-center group block">
        <div class="overflow-hidden rounded-2xl border-4 border-pink-200 shadow-sm transition duration-500 group-hover:shadow-xl group-hover:border-pink-300">
            <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc2069?q=80&w=500" 
                 alt="Package Treatment" 
                 class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
        </div>
        <p class="mt-4 text-xl font-bold text-[#43392f] transition-colors duration-300 group-hover:text-pink-600">
            Package Treatment
        </p>
    </a>

</div>
<div class="col-span-2 flex justify-center mt-6 gap-2 items-center">
    
    <!-- PREV -->
    <button class="px-4 py-1 rounded-full border border-pink-300 text-pink-400 hover:bg-pink-200 transition">
        &lt;
    </button>

    <!-- PAGE 1 (ACTIVE) -->
    <button class="px-4 py-1 rounded-full bg-pink-300 text-white font-semibold">
        1
    </button>

    <!-- PAGE 2 -->
    <button class="px-4 py-1 rounded-full border border-pink-300 text-pink-400 hover:bg-pink-200 transition">
        2
    </button>

    <!-- NEXT -->
    <button class="px-4 py-1 rounded-full border border-pink-300 text-pink-400 hover:bg-pink-200 transition">
        &gt;
    </button>

</div>
    </div>
</div>

<script>
const filterBtns = document.querySelectorAll('.filter-btn');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {

        // reset semua ke style NON-active
        filterBtns.forEach(b => {
            b.classList.remove('bg-[#43392f]', 'text-white');
            b.classList.add('bg-[#f5f1e8]', 'text-[#43392f]', 'border-2', 'border-[#43392f]');
        });

        // aktifkan yg diklik
        btn.classList.remove('bg-[#f5f1e8]', 'text-[#43392f]', 'border-2', 'border-[#43392f]');
        btn.classList.add('bg-[#43392f]', 'text-white');
    });
});
</script>

@endsection