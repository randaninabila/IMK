<header class="fixed top-0 left-0 w-full z-50 backdrop-blur-md bg-white/70 border-b border-gray-200">
    <div class="max-w-6xl mx-auto flex justify-between items-center py-4 px-6">

        {{-- LOGO --}}
        <h2 class="text-gray-800 font-semibold">
            Dina <span class="italic font-light">Salon Muslimah</span>
        </h2>

        {{-- MENU --}}
        <nav class="flex items-center gap-8 text-sm text-gray-700">

            @php
                $current = request()->path();
            @endphp

            <a href="/"
               class="relative hover:text-black {{ $current == '/' ? 'font-semibold text-black' : '' }}">
                Home
                @if($current == '/')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-black rounded"></span>
                @endif
            </a>

            <a href="/service"
               class="relative hover:text-black {{ $current == 'service' ? 'font-semibold text-black' : '' }}">
                Service
                @if($current == 'service')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-black rounded"></span>
                @endif
            </a>

            <a href="/specialist"
               class="relative hover:text-black {{ $current == 'specialist' ? 'font-semibold text-black' : '' }}">
                Specialist
                @if($current == 'specialist')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-black rounded"></span>
                @endif
            </a>

            <a href="#"
               class="relative hover:text-black">
                Booking
            </a>

            <a href="/gallery"
               class="relative hover:text-black {{ $current == 'gallery' ? 'font-semibold text-black' : '' }}">
                Gallery
                @if($current == 'gallery')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-black rounded"></span>
                @endif
            </a>

            {{-- LOGIN BUTTON --}}
            <a href="/login"
               class="bg-gray-800 text-white px-4 py-1.5 rounded-md text-sm hover:bg-gray-900 transition">
                Log In
            </a>

        </nav>
    </div>
</header>