@php
$current = request()->path();

$navItems = [
    [
        'href'  => '/dashboard',
        'label' => 'Beranda',
        'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    ],
    [
        'href'  => '/serviceo',
        'label' => 'Layanan',
        'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
    ],
    [
        'href'  => '/serviceo/edit',
        'label' => 'Daftar Layanan',
        'icon'  => 'M4 6h16M4 10h16M4 14h16M4 18h16',
    ],
    [
        'href'  => '/service/manage',
        'label' => 'Kelola Layanan',
        'icon'  => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    ],
    [
        'href'  => '/employee',
        'label' => 'Pegawai',
        'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    ],
    [
        'href'  => '/employee/edit',
        'label' => 'Kelola Pegawai',
        'icon'  => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    ],
    [
        'href'  => '/customers',
        'label' => 'Pelanggan',
        'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    ],
    [
        'href'  => '/owner/profile',
        'label' => 'Profil Saya',
        'icon'  => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zM12 2a10 10 0 100 20A10 10 0 0012 2z',
    ],
];
@endphp

{{--
    SIDEBAR — responsive drawer
    Desktop (lg+) : fixed sidebar 220px, selalu terlihat
    Mobile (<lg)  : tersembunyi di kiri, muncul sebagai drawer saat dibuka

    State dikelola Alpine.js dengan x-data di elemen ini.
    Tombol hamburger di topbar mengirim event 'open-sidebar' via $dispatch,
    komponen ini mendengarkan via @open-sidebar.window.
--}}
<div
    x-data="{ open: false }"
    @open-sidebar.window="open = true"
    @keydown.escape.window="open = false"
>

    {{-- OVERLAY — mobile only, gelap di belakang sidebar --}}
    <div
        x-show="open"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-black/40 z-30 lg:hidden"
        x-cloak
    ></div>

    {{-- SIDEBAR PANEL --}}
    <aside
        {{--
            Desktop: selalu visible (translate-x-0), fixed 220px
            Mobile:  tersembunyi (-translate-x-full) kecuali saat open
        --}}
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed left-0 w-[220px] bg-white border-r border-[#F1DFDF]
               flex flex-col z-40
               transition-transform duration-300 ease-in-out"
        style="top:64px; height:calc(100vh - 64px);"
    >

        {{-- TOMBOL TUTUP — mobile only, pojok kanan atas sidebar --}}
        <button
            @click="open = false"
            class="absolute top-3 right-3 p-1.5 rounded-lg text-[#7A6262]
                   hover:bg-[#FFF4F4] transition lg:hidden"
            aria-label="Tutup menu"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- NAV ITEMS --}}
        <nav class="flex flex-col gap-0.5 px-3 pt-4 flex-1 overflow-y-auto">

            {{-- LINK KE HALAMAN PUBLIK --}}
            <a href="{{ route('home') }}"
               @click="open = false"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[14px] transition
                      text-[#7A6262] hover:bg-[#FFF4F4] hover:text-[#3E382D] mb-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-[18px] h-[18px] shrink-0"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 12H9m0 0l3-3m-3 3l3 3M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                </svg>
                <span class="truncate">Lihat Halaman Publik</span>
            </a>

            <div class="h-px bg-[#F1DFDF] mb-2"></div>

            @foreach ($navItems as $item)
                @php
                    $slug     = ltrim($item['href'], '/');
                    $isActive = ($current === $slug);
                @endphp

                <a href="{{ $item['href'] }}"
                   @click="open = false"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[14px] transition
                          {{ $isActive
                                ? 'bg-[#F8D7DC] text-[#3E382D] font-semibold'
                                : 'text-[#7A6262] hover:bg-[#FFF4F4] hover:text-[#3E382D]' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-[18px] h-[18px] shrink-0"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>

                    <span class="truncate">{{ $item['label'] }}</span>

                    @if ($isActive)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-[#D4697A] shrink-0"></span>
                    @endif
                </a>
            @endforeach

        </nav>

        {{-- LOGOUT --}}
        <div x-data="{ logoutOpen: false }" class="px-3 pb-5 relative">

            <div class="h-px bg-[#F1DFDF] mb-3"></div>

            <button
                type="button"
                @click="logoutOpen = !logoutOpen"
                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-[14px]
                       text-red-400 hover:bg-red-50 hover:text-red-600 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-[18px] h-[18px] shrink-0"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
                Keluar Akun
            </button>

            {{-- MINI CONFIRM --}}
            <div
                x-show="logoutOpen"
                x-transition
                x-cloak
                @click.outside="logoutOpen = false"
                class="absolute bottom-20 left-3 right-3 bg-white border border-[#F1DFDF]
                       rounded-2xl shadow-xl p-4 z-50"
            >
                <p class="text-sm text-[#3E382D] mb-4">Yakin ingin keluar?</p>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="logoutOpen = false"
                        class="px-3 py-2 rounded-xl text-xs bg-gray-100 hover:bg-gray-200 transition"
                    >
                        Batal
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="px-3 py-2 rounded-xl text-xs bg-red-500 hover:bg-red-600 text-white transition"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </aside>

</div>