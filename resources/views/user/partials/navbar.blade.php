<header class="fixed top-0 left-0 w-full z-50 backdrop-blur-md bg-white/70 border-b border-gray-200">
    <div class="max-w-6xl mx-auto flex justify-between items-center py-4 px-6">

        {{-- LOGO --}}
        {{-- text-gray-800 ubah ke text-[#3E382D] --}}
        <h2 class="text-[#3E382D] font-semibold">
            Dina <span class="italic font-light">Salon Muslimah</span>
        </h2>

        {{-- MENU --}}
        {{-- text-gray-700 ubah ke text-tertiary-500 --}}
        <nav class="flex items-center gap-8 text-sm text-tertiary-500">

            @php
            $current = request()->path();
            @endphp

            {{-- Home --}}
            <a href="/"
                class="relative hover:text-[#3E382D] {{ $current == '/' ? 'font-semibold text-[#3E382D]' : '' }}">
                Home
                @if($current == '/')
                <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>

            {{-- Service --}}
            <a href="/service"
                class="relative hover:text-[#3E382D] {{ $current == 'service' ? 'font-semibold text-[#3E382D]' : '' }}">
                Service
                @if($current == 'service')
                <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>

            {{-- Specialist --}}
            <a href="/specialist"
                class="relative hover:text-[#3E382D] {{ $current == 'specialist' ? 'font-semibold text-[#3E382D]' : '' }}">
                Specialist
                @if($current == 'specialist')
                <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>

            <a href="#" class="relative hover:text-[#3E382D]">
                Booking
            </a>

            {{-- Gallery --}}
            <a href="/gallery"
                class="relative hover:text-[#3E382D] {{ $current == 'gallery' ? 'font-semibold text-[#3E382D]' : '' }}">
                Gallery
                @if($current == 'gallery')
                <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>

            {{-- AUTH STATE --}}
            @auth

                {{-- PROFILE + LOGOUT --}}
                <div class="relative flex items-center gap-3" x-data="{ open: false }">

                    {{-- PROFILE PHOTO --}}
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3E382D&color=fff"
                            class="w-9 h-9 rounded-full object-cover border-2 border-[#3E382D]">
                    </button>

                    {{-- DROPDOWN --}}
                    <div x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="absolute right-0 top-12 w-56 bg-white rounded-2xl shadow-lg border border-gray-100 py-3 z-50">

                        {{-- HEADER --}}
                        <div class="px-4 pb-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-[#3E382D]">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-gray-400 capitalize">
                                {{ auth()->user()->role }}
                            </p>
                        </div>

                        {{-- MENU --}}
                        <div class="mt-2 px-2">

                            <a href="/customer/profile"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[#f5eaea] text-sm text-[#3E382D] transition">

                                {{-- USER ICON --}}
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>

                                Profile
                            </a>

                        </div>
                    </div>

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="group relative flex items-center justify-center w-9 h-9 rounded-full hover:bg-red-50 transition">

                            {{-- LOGOUT ICON --}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 text-red-400 group-hover:text-red-600 transition"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                            </svg>

                            {{-- TOOLTIP --}}
                            <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 
                                bg-[#3E382D] text-white text-xs px-2 py-1 rounded 
                                opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">

                                Logout
                            </span>

                        </button>
                    </form>

                </div>

            @else

                {{-- LOGIN BUTTON --}}
                <a href="/login"
                class="bg-[#3E382D] text-white px-4 py-1.5 rounded-md text-sm hover:opacity-90 transition">
                    Log In
                </a>

            @endauth

        </nav>
    </div>
</header>