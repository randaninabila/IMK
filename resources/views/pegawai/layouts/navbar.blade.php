<nav class="fixed top-0 left-0 right-0 h-[70px] bg-[#FFF9F9] border-b border-[#F1DFDF] px-4 lg:px-12 flex items-center justify-between z-50">

    {{-- KIRI: hamburger + logo --}}
    <div class="flex items-center gap-3">
        <button
            @click="$dispatch('open-sidebar')"
            class="lg:hidden p-2 rounded-xl text-[#7A6262] hover:bg-[#FFF4F4] hover:text-[#3E382D] transition shrink-0"
            aria-label="Buka menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <h2 class="text-[#3E382D] text-[20px] font-semibold select-none">
            Dina <span class="italic font-light">Salon Muslimah</span>
        </h2>
    </div>

    {{-- KANAN: font control + profile --}}
    <div class="flex items-center gap-4">

        {{-- FONT SIZE CONTROL --}}
        <div class="flex items-center gap-1">
            <button onclick="changeFontScale(-1)"
                    class="w-7 h-7 rounded-lg bg-[#FFF4F4] border border-[#F1DFDF] text-[12px] font-bold text-[#7A6262] hover:bg-[#F8D7DC] transition select-none">
                −
            </button>
            <span id="fontScaleLabel"
                  class="text-[11px] text-[#7A6262] w-8 text-center tabular-nums select-none">
                100%
            </span>
            <button onclick="changeFontScale(1)"
                    class="w-7 h-7 rounded-lg bg-[#FFF4F4] border border-[#F1DFDF] text-[12px] font-bold text-[#7A6262] hover:bg-[#F8D7DC] transition select-none">
                +
            </button>
        </div>

        {{-- PROFILE --}}
        @php $authUser = auth()->user(); @endphp
        <a href="{{ route('pegawai.profile') }}" class="block">
            <div class="bg-[#F5A6AF] rounded-full pl-3 pr-5 py-2 flex items-center gap-2 shadow-sm hover:shadow-md transition cursor-pointer">
                <img
                    src="{{ $authUser->foto_profile ? asset('storage/' . $authUser->foto_profile) : 'https://i.pravatar.cc/150?u=' . $authUser->id }}"
                    alt="profile"
                    class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm"
                    onerror="this.src='https://i.pravatar.cc/150?u={{ $authUser->id }}'">
                <div>
                    <h3 class="text-[15px] font-semibold text-[#2F2A2A] leading-none">
                        {{ $authUser->name }}
                    </h3>
                    <p class="text-[13px] text-[#6C5555]">Spesialis Terverifikasi</p>
                </div>
            </div>
        </a>

    </div>

</nav>