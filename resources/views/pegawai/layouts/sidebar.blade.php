<aside class="w-[300px] p-6 font-sans">

    {{-- MENU --}}
    <div class="bg-white rounded-[22px] border-[3px] border-[#9B6D75] px-3 py-4 shadow-sm mt-4">

        <div class="space-y-1">

            {{-- PROFILE --}}
            <a href="{{ url('/prof1') }}"
               class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
               {{ request()->is('prof1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Profile</span>
            </a>

            {{-- DASHBOARD --}}
            <a href="{{ url('/udin') }}"
               class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
               {{ request()->is('udin') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Dashboard</span>
            </a>

            {{-- HISTORY --}}
            <a href="{{ url('/his1') }}"
               class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
               {{ request()->is('his1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>History</span>
            </a>

            {{-- JADWAL KERJA --}}
            <a href="{{ url('/jkb') }}"
               class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
               {{ request()->is('jkb') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Jadwal Kerja</span>
            </a>

            {{-- BOOKING --}}
            <a href="{{ url('/book1') }}"
               class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
               {{ request()->is('book1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Booking</span>
            </a>

            {{-- NOTIFIKASI --}}
            <a href="{{ url('/not1') }}"
               class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
               {{ request()->is('not1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Notifikasi</span>
            </a>

        </div>

        {{-- HELP CARD --}}
        <div class="mt-12 bg-[#FFDDE2] border-[2px] border-[#F5A6AF] rounded-[28px] p-3">

            <div class="flex items-start gap-2">

                <div class="w-[37px] h-[36px] rounded-full border-[4px] border-[#7A7A7A]
                            flex items-center justify-center text-[#7A7A7A]
                            text-[22px] font-bold">
                    ?
                </div>

                <div>
                    <h2 class="text-[16px] font-semibold text-black leading-tight">
                        Butuh Bantuan?
                    </h2>

                    <p class="text-[11px] text-[#5C5C5C] leading-snug">
                        Pusat bantuan & FAQ
                    </p>
                </div>

            </div>

            <button
                class="w-full mt-3 border-[2px] border-[#FF244D]
                       rounded-[20px] py-1.5 text-[14px]
                       text-[#FF244D] font-semibold
                       hover:bg-white transition">
                Buka Bantuan
            </button>

        </div>

        {{-- LOGOUT --}}
        <div class="mt-4 pt-4 border-t-[2px] border-black">

            <a href="#"
               class="flex items-center gap-3 px-3 text-black text-[18px] font-medium">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-6 h-7 text-[#FF0040]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2.5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
                </svg>

                <h2 class="text-[16px] font-semibold text-black leading-tight">
                    Keluar Akun
                </h2>

            </a>

        </div>

    </div>

</aside>