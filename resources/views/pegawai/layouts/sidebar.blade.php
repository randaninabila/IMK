<aside class="fixed top-[70px] left-0 w-[300px] h-[calc(100vh-70px)] p-6 font-sans overflow-y-auto z-40">

    {{-- MENU --}}
    <div class="bg-white rounded-[22px] border-[3px] border-[#9B6D75] px-3 py-4 shadow-sm mt-16">

        <div class="space-y-1">

            {{-- PROFILE --}}

            <!-- <a href="{{ url('/prof1') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
               {{ request()->is('prof1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}"> -->

            <a href="{{ route('pegawai.profile') }}"
                class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                {{ request()->routeIs('pegawai.profile*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">

                <span>Profile</span>
            </a>

            {{-- DASHBOARD --}}

            <!-- <a href="{{ url('/udin') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
               {{ request()->is('udin') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}"> -->

            <a href="{{ route('pegawai.dashboard') }}"
                class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                {{ request()->routeIs('pegawai.dashboard*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">

                <span>Dashboard</span>
            </a>

            {{-- HISTORY --}}

            <!-- <a href="{{ url('/his1') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
               {{ request()->is('his1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}"> -->

            <a href="{{ route('pegawai.history') }}"
                class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                {{ request()->routeIs('pegawai.history*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">

                <span>History</span>
            </a>

            {{-- JADWAL KERJA --}}

            <!-- <a href="{{ url('/jkb') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
               {{ request()->is('jkb') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}"> -->

            <a href="{{ url('/pegawai/jadwal') }}"
                class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                {{ request()->is('pegawai/jadwal*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">

                <span>Jadwal Kerja</span>

            </a>

            {{-- BOOKING --}}

            <!-- <a href="{{ url('/book1') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
               {{ request()->is('book1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}"> -->

            <a href="{{ route('pegawai.booking') }}"
                class="flex items-center gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                {{ request()->routeIs('pegawai.booking*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">

                <span>Booking</span>
            </a>

            {{-- NOTIFIKASI --}}

            <!-- <a href="{{ url('/not1') }}"
               class="flex items-center mb-32 gap-4 px-5 py-3 rounded-2xl text-[18px] font-medium transition
               {{ request()->is('not1') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}"> -->

            <a href="{{ route('pegawai.notifikasi') }}"
                class="flex items-center gap-4 mb-32 px-5 py-3 rounded-2xl text-[18px] font-medium transition
                {{ request()->routeIs('pegawai.notifikasi*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Notifikasi</span>
            </a>

        </div>

        {{-- LOGOUT --}}
        <div class="mt-4 pt-4 border-t-[2px] border-black">
    
    {{-- FORM LOGOUT dengan POST + CSRF --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        
        <button type="submit"
                class="flex items-center gap-3 px-3 text-black text-[18px] font-medium hover:text-[#FF0040] transition cursor-pointer w-full text-left">

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

            <h2 class="text-[18px] font-semibold text-black leading-tight">
                Keluar Akun
            </h2>

        </button>
    </form>

</div>

    </div>

</aside>