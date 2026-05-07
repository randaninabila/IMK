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
            <a href="/dashboard"
               class="relative hover:text-[#3E382D] {{ $current == '/dashboard' ? 'font-semibold text-[#3E382D]' : '' }}">
                Dashboard
                @if($current == '/dashboard')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>

            {{-- Service --}}
            <a href="/serviceo"
               class="relative hover:text-[#3E382D] {{ $current == 'serviceo' ? 'font-semibold text-[#3E382D]' : '' }}">
                Service
                @if($current == 'serviceo')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>

            {{-- Specialist --}}
            <a href="/employee"
               class="relative hover:text-[#3E382D] {{ $current == 'employee' ? 'font-semibold text-[#3E382D]' : '' }}">
                Employees
                @if($current == 'employee')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>

            {{-- Specialist --}}
            <a href="/customers"
               class="relative hover:text-[#3E382D] {{ $current == 'customers' ? 'font-semibold text-[#3E382D]' : '' }}">
                Customers
                @if($current == 'customers')
                    <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#3E382D] rounded"></span>
                @endif
            </a>



            {{-- LOGIN BUTTON --}}
            {{-- bg-gray-800 ubah ke bg-[#3E382D] --}}
            <a href="/login"
               class="bg-[#3E382D] text-white px-4 py-1.5 rounded-md text-sm hover:opacity-90 transition">
                Log In
            </a>

        </nav>
    </div>
</header>