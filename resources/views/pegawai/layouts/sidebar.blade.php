<aside class="fixed top-[70px] left-0 w-[300px] h-[calc(100vh-70px)] p-6 font-sans overflow-y-auto z-40">

    {{-- MENU --}}
    <div class="bg-white rounded-[22px] border-[3px] border-[#9B6D75] px-3 py-4 shadow-sm mt-16">

        <div class="space-y-1">

            {{-- PROFILE --}}
            <a href="{{ route('pegawai.profile') }}"
                class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
                {{ request()->routeIs('pegawai.profile*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Profile</span>
            </a>

            {{-- DASHBOARD --}}
            <a href="{{ route('pegawai.dashboard') }}"
                class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
                {{ request()->routeIs('pegawai.dashboard*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Dashboard</span>
            </a>

            {{-- HISTORY --}}
            <a href="{{ route('pegawai.history') }}"
                class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
                {{ request()->routeIs('pegawai.history*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>History</span>
            </a>

            {{-- JADWAL KERJA --}}
            <a href="{{ url('/pegawai/jadwal') }}"
                class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
                {{ request()->is('pegawai/jadwal*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Jadwal Kerja</span>
            </a>

            {{-- BOOKING --}}
            <a href="{{ route('pegawai.booking') }}"
                class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
                {{ request()->routeIs('pegawai.booking*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Booking</span>
            </a>

            {{-- NOTIFIKASI --}}
            <a href="{{ route('pegawai.notifikasi') }}"
                class="flex items-center gap-4 px-5 py-2.5 rounded-2xl text-[16px] font-medium transition
                {{ request()->routeIs('pegawai.notifikasi*') ? 'bg-[#F5A6AF] shadow-sm text-white' : 'text-[#3e3a34]' }}">
                <span>Notifikasi</span>
            </a>

        </div>

        {{-- SPACER --}}
        <div class="h-10"></div>

        {{-- LOGOUT  dengan form POST + CSRF --}}
<div class="mt-4 pt-4 border-t-[2px] border-black">
    <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <button type="submit"
                class="w-full flex items-center gap-3 px-3 text-black text-[18px] font-medium hover:text-[#F1A9B1] transition cursor-pointer">
            
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
        </button>
    </form>
</div>

    </div>

</aside>