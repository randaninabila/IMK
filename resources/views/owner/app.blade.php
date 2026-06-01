<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dina Salon Muslimah')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        h1{
            font-family: 'Cormorant Garamond', serif;
        }
        h4{
            font-family: 'Playfair Display', serif;
        }
    </style>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

{{-- body: no margin/padding, bg penuh --}}

<body class="m-0 p-0 bg-[#FFF6F7] text-[#3E382D] antialiased">

    {{-- ===== TOP BAR ===== --}}
    {{-- h tepat 64px, konsisten dipakai di sidebar (top-[64px]) dan main (mt-[64px]) --}}
    <header class="fixed top-0 left-0 w-full z-50 bg-white border-b border-[#F1DFDF]" style="height:64px;">
        <div class="w-full px-6 xl:px-10 h-full flex justify-between items-center">

            {{-- LOGO --}}
            <h2 class="text-[#3E382D] font-semibold text-lg shrink-0 select-none">
                Dina <span class="italic font-light">Salon Muslimah</span>
            </h2>

            {{-- PROFILE DROPDOWN (tanpa tombol logout terpisah) --}}
            <div class="relative" x-data="{ open: false }">

                <button @click="open = !open" class="bg-[#F8D7DC] rounded-full pl-2 pr-4 py-1.5
                           flex items-center gap-2.5 border border-[#F1DFDF]
                           hover:bg-[#F5CDD3] transition">
                    <img src="{{ auth()->user()->foto_profile_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=FFE4E6&color=3E382D' }}"
                        alt="{{ auth()->user()->nama }}"
                        class="w-9 h-9 rounded-full object-cover border-2 border-white shrink-0">
                    <div class="text-left leading-tight">
                        <h3 class="text-[14px] font-semibold text-[#2F2A2A] max-w-[110px] truncate">
                            {{ auth()->user()->nama }}
                        </h3>
                        <p class="text-[11px] tracking-wide uppercase text-[#7A6262] mt-0.5">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                </button>

                {{-- DROPDOWN --}}
                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-[#F1DFDF] p-2 z-50"
                    style="top: 100%;">
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[#FFF4F4] text-sm text-[#3E382D] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Edit Profil
                    </a>

                    <a href="{{ route('profile') }}#password"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[#FFF4F4] text-sm text-[#3E382D] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Kata Sandi
                    </a>

                </div>
            </div>

        </div>
    </header>

    {{-- ===== SIDEBAR ===== --}}
    @include('owner.navbar')

    {{-- ===== MAIN CONTENT ===== --}}
    {{-- mt = tinggi header (64px), ml = lebar sidebar (220px) --}}
    <main class="ml-[220px] bg-[#FFF6F7] min-h-screen" style="padding-top:64px;">
        <div class="px-6 py-5">
            @yield('content')
        </div>
    </main>

</body>

</html>