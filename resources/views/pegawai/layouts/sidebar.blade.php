{{--
    SIDEBAR PEGAWAI — responsive drawer
    Desktop (lg+) : fixed 300px, selalu terlihat
    Mobile (<lg)  : tersembunyi di kiri, muncul sebagai drawer saat hamburger diklik
--}}
<div
    x-data="{ open: false }"
    @open-sidebar.window="open = true"
    @keydown.escape.window="open = false"
>

    {{-- OVERLAY — mobile only --}}
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
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-[70px] left-0 w-[300px] h-[calc(100vh-70px)] p-6 font-sans
               overflow-y-hidden z-40 transition-transform duration-300 ease-in-out"
    >

        {{-- MENU CONTAINER --}}
        <div class="bg-white rounded-[22px] border-[3px] border-[#9B6D75] px-3 py-4 shadow-sm flex flex-col h-full relative">

            {{-- TOMBOL TUTUP — mobile only --}}
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

            {{-- NAVIGATION LINKS --}}
            <div class="space-y-1 flex-1">

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

                {{-- PROFILE --}}
                <a href="{{ route('pegawai.profile') }}"
                   @click="open = false"
                   class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                          {{ request()->routeIs('pegawai.profile*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34] hover:bg-[#ffedef]' }}">
                    <span>Profil</span>
                </a>

                {{-- DASHBOARD --}}
                <a href="{{ route('pegawai.dashboard') }}"
                   @click="open = false"
                   class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                          {{ request()->routeIs('pegawai.dashboard*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34] hover:bg-[#ffedef]' }}">
                    <span>Beranda</span>
                </a>

                {{-- HISTORY --}}
                <a href="{{ route('pegawai.history') }}"
                   @click="open = false"
                   class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                          {{ request()->routeIs('pegawai.history*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34] hover:bg-[#ffedef]' }}">
                    <span>Riwayat Aktivitas</span>
                </a>

                {{-- JADWAL KERJA --}}
                <a href="{{ url('/pegawai/jadwal') }}"
                   @click="open = false"
                   class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                          {{ request()->is('pegawai/jadwal*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34] hover:bg-[#ffedef]' }}">
                    <span>Jadwal Kerja</span>
                </a>

                {{-- BOOKING --}}
                <a href="{{ route('pegawai.booking') }}"
                   @click="open = false"
                   class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                          {{ request()->routeIs('pegawai.booking*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34] hover:bg-[#ffedef]' }}">
                    <span>Pesanan</span>
                </a>

                {{-- NOTIFIKASI --}}
                <a href="{{ route('pegawai.notifikasi') }}"
                   @click="open = false"
                   class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                          {{ request()->routeIs('pegawai.notifikasi*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34] hover:bg-[#ffedef]' }}">
                    <span>Notifikasi</span>
                </a>

            </div>

            {{-- LOGOUT SECTION --}}
            <div x-data="{ logoutOpen: false }" class="mt-4 pt-4 border-t-[2px] border-black px-3 pb-3 relative">

                <button type="button"
                        @click="logoutOpen = !logoutOpen"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-[14px]
                               text-red-400 hover:bg-red-50 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-[18px] h-[18px] shrink-0"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                    </svg>
                    Keluar Akun
                </button>

                <div x-show="logoutOpen"
                     x-transition
                     x-cloak
                     @click.outside="logoutOpen = false"
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white border border-[#F1DFDF]
                            rounded-2xl shadow-xl p-4 z-50">

                    <p class="text-sm text-[#3E382D] mb-4">Yakin ingin keluar?</p>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                @click="logoutOpen = false"
                                class="px-3 py-2 rounded-xl text-xs bg-gray-100 hover:bg-gray-200 transition">
                            Batal
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-2 rounded-xl text-xs bg-red-500 hover:bg-red-600 text-white transition">
                                Keluar
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>

    </aside>

</div>