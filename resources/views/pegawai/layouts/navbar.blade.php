<nav class="fixed top-0 left-0 right-0 h-[70px] bg-[#FFF9F9] border-b border-[#F1DFDF] px-12 flex items-center justify-between z-50">

    {{-- LOGO --}}
    <h2 class="text-[#3E382D] text-[20px] font-semibold">
        Dina <span class="italic font-light">Salon Muslimah</span>
    </h2>

    {{-- PROFILE SECTION --}}
    @php
        $authUser = auth()->user();
    @endphp

    <a href="{{ route('pegawai.profile') }}" class="block">
        <div class="bg-[#F5A6AF] rounded-full pl-3 pr-5 py-2 flex items-center gap-2 shadow-sm hover:shadow-md transition cursor-pointer">
            
            {{-- FOTO PROFILE - Akses langsung dari users table --}}
            <img
                src="{{ $authUser->foto_profile ? asset('storage/' . $authUser->foto_profile) : 'https://i.pravatar.cc/150?u=' . $authUser->id }}"
                alt="profile"
                class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm"
                onerror="this.src='https://i.pravatar.cc/150?u={{ $authUser->id }}'"
            >
            
            <div>
                <h3 class="text-[15px] font-semibold text-[#2F2A2A] leading-none">
                    {{ $authUser->name }}
                </h3>
                <p class="text-[13px] text-[#6C5555]">
                    Verified Specialist
                </p>
            </div>
            
        </div>
    </a>

</nav>