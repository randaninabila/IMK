<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dina Salon Muslimah</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

{{-- x-data di body agar $dispatch('open-sidebar') dari hamburger bisa berjalan --}}
<body x-data class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white font-sans min-h-screen">

    {{-- TOP NAVBAR --}}
    {{--
        PENTING: pastikan di dalam file pegawai/layouts/navbar.blade.php
        ada tombol hamburger berikut di bagian kiri navbar:

        <button
            @click="$dispatch('open-sidebar')"
            class="lg:hidden p-2 rounded-xl text-[#7A6262] hover:bg-[#FFF4F4] transition"
            aria-label="Buka menu"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        Kalau navbar tidak bisa diedit sekarang, hamburger sementara
        bisa dipasang di sini (lihat blok di bawah ini).
    --}}
    @include('pegawai.layouts.navbar', ['user' => auth()->user()])

    {{-- SIDEBAR --}}
    @include('pegawai.layouts.sidebar')

    {{-- MAIN CONTENT --}}
    {{-- Mobile: tidak ada margin kiri (sidebar tersembunyi) --}}
    {{-- Desktop (lg+): margin kiri = lebar sidebar (300px) --}}
    <main class="lg:ml-[300px] px-4 lg:px-8 pb-15" style="padding-top: 75px;">
        @yield('content')
    </main>

</body>
</html>