<header class="
    fixed top-0 left-0 w-full z-50
    backdrop-blur-md
    bg-white/70
    border-b border-[#F1DFDF]
">

    <div class="
        w-full
        h-[78px]
        px-10 xl:px-16
        flex items-center justify-between
    ">

        {{-- LOGO --}}
        <h2 class="
            text-[#3E382D]
            text-[20px]
            font-semibold
            shrink-0
        ">
            Dina
            <span class="italic font-light">
                Salon Muslimah
            </span>
        </h2>


        {{-- RIGHT SIDE --}}
        <div class="flex items-center gap-8">

            {{-- MENU --}}
            <nav class="
                flex items-center
                gap-6
                text-[15px]
                text-[#4A4343]
                shrink-0
            ">

                @php
                    $current = request()->path();
                @endphp

                {{-- Home --}}
                <a href="/"
                    class="relative hover:text-[#3E382D] transition
                    {{ $current == '/' ? 'font-semibold text-[#3E382D]' : '' }}">

                    Home

                    @if($current == '/')
                        <span class="
                            absolute left-0 -bottom-1
                            w-full h-[2px]
                            bg-[#3E382D]
                            rounded
                        "></span>
                    @endif

                </a>

                {{-- Service --}}
                <a href="/service"
                    class="relative hover:text-[#3E382D] transition
                    {{ $current == 'service' ? 'font-semibold text-[#3E382D]' : '' }}">

                    Service

                    @if($current == 'service')
                        <span class="
                            absolute left-0 -bottom-1
                            w-full h-[2px]
                            bg-[#3E382D]
                            rounded
                        "></span>
                    @endif

                </a>

                {{-- Specialist --}}
                <a href="/specialist"
                    class="relative hover:text-[#3E382D] transition
                    {{ $current == 'specialist' ? 'font-semibold text-[#3E382D]' : '' }}">

                    Specialist

                    @if($current == 'specialist')
                        <span class="
                            absolute left-0 -bottom-1
                            w-full h-[2px]
                            bg-[#3E382D]
                            rounded
                        "></span>
                    @endif

                </a>

                {{-- Gallery --}}
                <a href="/gallery"
                    class="relative hover:text-[#3E382D] transition
                    {{ $current == 'gallery' ? 'font-semibold text-[#3E382D]' : '' }}">

                    Gallery

                    @if($current == 'gallery')
                        <span class="
                            absolute left-0 -bottom-1
                            w-full h-[2px]
                            bg-[#3E382D]
                            rounded
                        "></span>
                    @endif

                </a>

            </nav>


            {{-- AUTH --}}
            @auth

            <div
                class="relative flex items-center gap-4"
                x-data="{ open:false }"
            >

                {{-- PROFILE --}}
                <button
                    @click="open = !open"

                    class="
                        bg-[#F8D7DC]
                        rounded-full
                        pl-2 pr-4 py-1.5
                        flex items-center gap-2.5
                        border border-[#F1DFDF]
                        hover:bg-[#F5CDD3]
                        transition
                    "
                >

                    {{-- FOTO --}}
                    <img
                        src="{{ auth()->user()->foto_profile_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=FFE4E6&color=3E382D' }}"
                        alt="{{ auth()->user()->nama }}"

                        class="
                            w-9 h-9
                            rounded-full
                            object-cover
                            border-2 border-white
                            shrink-0
                        "
                    >

                    {{-- INFO --}}
                    <div class="text-left leading-tight">

                        <h3 class="
                            text-[14px]
                            font-semibold
                            text-[#2F2A2A]
                            max-w-[110px]
                            truncate
                        ">
                            {{ auth()->user()->nama }}
                        </h3>

                        <p class="
                            text-[11px]
                            tracking-wide
                            uppercase
                            text-[#7A6262]
                            mt-0.5
                        ">
                            {{ auth()->user()->role }}
                        </p>

                    </div>

                </button>


                {{-- DROPDOWN --}}
                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition

                    class="
                        absolute right-0 top-16
                        w-56
                        bg-white
                        rounded-2xl
                        shadow-xl
                        border border-[#F1DFDF]
                        p-2
                        z-50
                    "
                >

                    {{-- PROFILE --}}
                    <a
                        href="{{ route('profile') }}"

                        class="
                            flex items-center gap-3
                            px-4 py-3
                            rounded-xl
                            hover:bg-[#FFF4F4]
                            text-sm
                            text-[#3E382D]
                            transition
                        "
                    >

                        {{-- USER ICON --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />

                        </svg>

                        Edit Profile

                    </a>


                    {{-- PASSWORD --}}
                    <a
                        href="{{ route('profile') }}#password"

                        class="
                            flex items-center gap-3
                            px-4 py-3
                            rounded-xl
                            hover:bg-[#FFF4F4]
                            text-sm
                            text-[#3E382D]
                            transition
                        "
                    >

                        {{-- LOCK ICON --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />

                        </svg>

                        Change Password

                    </a>

                </div>


                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"

                        class="
                            group relative
                            flex items-center justify-center
                            w-9 h-9
                            rounded-full
                            hover:bg-red-50
                            transition
                        "
                    >

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="
                                w-5 h-5
                                text-red-400
                                group-hover:text-red-600
                                transition
                            "
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"
                            />

                        </svg>

                    </button>

                </form>

            </div>

            @else

            {{-- LOGIN --}}
            <a href="/login"
                class="
                    bg-[#3E382D]
                    text-white
                    px-5 py-2
                    rounded-full
                    text-sm
                    hover:opacity-90
                    transition
                ">

                Log In

            </a>

            @endauth

        </div>

    </div>

</header>