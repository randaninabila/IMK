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

<body x-data class="m-0 p-0 bg-[#FFF6F7] text-[#3E382D] antialiased">

    {{-- ===== TOP BAR ===== --}}
    <header class="fixed top-0 left-0 w-full z-50 bg-white border-b border-[#F1DFDF]" style="height:64px;">
        <div class="w-full px-4 xl:px-10 h-full flex justify-between items-center gap-3">

            {{-- HAMBURGER — mobile only --}}
            <button
                @click="$dispatch('open-sidebar')"
                class="lg:hidden p-2 rounded-xl text-[#7A6262] hover:bg-[#FFF4F4] hover:text-[#3E382D] transition shrink-0"
                aria-label="Buka menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- LOGO --}}
            <h2 class="text-[#3E382D] font-semibold text-lg shrink-0 select-none flex-1 lg:flex-none">
                Dina <span class="italic font-light">Salon Muslimah</span>
            </h2>

            {{-- PROFILE — langsung ke halaman profil --}}
            <a href="{{ route('owner.profile') }}"
               class="bg-[#F8D7DC] rounded-full pl-2 pr-4 py-1.5
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
            </a>

        </div>
    </header>


    {{-- GLOBAL NOTIFICATION --}}
    {{-- Mobile: tidak ada ml (sidebar tersembunyi). Desktop: ml sesuai lebar sidebar --}}
    <div class="fixed top-20 left-4 right-4 lg:left-[240px] lg:right-6 z-[999] pointer-events-none">

        @if(session('success') || session('success_password'))
            <div
                x-data="{ show:true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition.opacity
                class="mb-4 bg-green-100 text-green-700 px-5 py-4 rounded-2xl
                       flex items-center justify-between shadow-lg pointer-events-auto"
            >
                <span>{{ session('success') ?? session('success_password') }}</span>
                <button @click="show = false" class="ml-4 text-lg font-bold hover:opacity-70">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div
                x-data="{ show:true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition.opacity
                class="mb-4 bg-red-100 text-red-700 px-5 py-4 rounded-2xl
                       flex items-center justify-between shadow-lg pointer-events-auto"
            >
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-4 text-lg font-bold hover:opacity-70">✕</button>
            </div>
        @endif

        @if($errors->any())
            <div
                x-data="{ show:true }"
                x-init="setTimeout(() => show = false, 5000)"
                x-show="show"
                x-transition.opacity
                class="mb-4 bg-red-100 text-red-700 px-5 py-4 rounded-2xl
                       flex items-center justify-between shadow-lg pointer-events-auto"
            >
                <span>{{ $errors->first() }}</span>
                <button @click="show = false" class="ml-4 text-lg font-bold hover:opacity-70">✕</button>
            </div>
        @endif

    </div>


    {{-- ===== SIDEBAR ===== --}}
    @include('owner.sidebar')


    {{-- ===== MAIN CONTENT ===== --}}
    {{-- Mobile: tidak ada ml (sidebar hidden). Desktop: ml = lebar sidebar (220px) --}}
    <main class="lg:ml-[220px] bg-[#FFF6F7] min-h-screen" style="padding-top:64px;">
        <div class="px-4 lg:px-6 py-5">
            @yield('content')
        </div>
    </main>

</body>

</html>