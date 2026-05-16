@extends('pegawai.app')

@section('content')

<div class="w-full px-4 py-4 font-sans text-[#3B302D]">

    {{-- TITLE --}}
    <div>
    <div>
        <h1 class="text-[26px] font-bold leading-none">
            Profile
        </h1>
        <p class="mt-2 text-[16px]">
             Kelola informasi profesional pengaturan akun Anda
        </p>
    </div>

        {{-- PROFILE CARD --}}
        <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-6">

            <div class="flex items-center justify-between">

                {{-- LEFT --}}
                <div class="flex items-center gap-6">

                    <img
                        src="https://i.pravatar.cc/150"
                        alt="profile"
                        class="w-28 h-28 rounded-full object-cover border-[5px] border-white shadow-sm"
                    >

                    <div>

                        <div class="flex items-center gap-3">

                            <h2 class="text-[18px] font-bold text-[#3B302D]">
                                Siti Aulia, Specialist
                            </h2>

                            <span class="px-3 py-1 rounded-full bg-[#F9D5DA] text-[#3B302D] text-[12px] font-normal">
                                Verified Specialist
                            </span>

                        </div>

                        <p class="text-[14px] font-normal text-[#3B302D] mt-1">
                            Salon & Beauty Specialist
                        </p>

                        <div class="flex items-center gap-2">

                            <span class="text-yellow-400 text-[18px]">
                                ★
                            </span>

                            <p class="text-[14px] font-normal text-[#3B302D]">
                                4.9 (126 ulasan)
                            </p>

                        </div>

                        <p class="text-[14px] font-normal text-[#3B302D]">
                            siti.specialist@salondina.id
                        </p>

                    </div>

                </div>

                {{-- BUTTON --}}
                <button
                    class="px-6 py-3 border-[2px] border-[#D9B8BD] rounded-[16px]
                           text-[#3B302D] text-[17px] font-semibold
                           hover:bg-[#FFF1F3] transition duration-300">

                    ✎ Edit Profil

                </button>

            </div>

        </div>

        {{-- SETTINGS --}}
        <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 pb-6 pt-4">

            {{-- HEADER --}}
            <div class="px-5 py-4 border-b border-[#F3D5D9]">

                <h3 class="text-[18px] font-bold text-[#3B302D]">
                    Pengaturan Akun
                </h3>

            </div>

            <div class="divide-y divide-[#F3D5D9]">

                {{-- ITEM 1 --}}
                <div class="flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200 cursor-pointer">

                    <div class="flex items-center gap-5">

                        <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2]
                                    flex items-center justify-center text-[#3B302D]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke-width="1.8"
                                 stroke="currentColor"
                                 class="w-5 h-5">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8.25 6.75V4.5m7.5 2.25V4.5m-9 6h10.5m-13.5 9h16.5A2.25 2.25 0 0021 17.25V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5z"/>

                            </svg>

                        </div>

                        <div>

                            <h4 class="text-[16px] font-semibold text-[#3B302D]">
                                Jadwal Kerja
                            </h4>

                            <p class="text-[14px] font-normal text-[#3B302D]">
                                Atur hari dan jam kerja anda
                            </p>

                        </div>

                    </div>

                    <span class="text-[#3B302D] text-[18px]">
                        ›
                    </span>

                </div>

                {{-- ITEM 2 --}}
                <div class="flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200 cursor-pointer">

                    <div class="flex items-center gap-5">

                        <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2]
                                    flex items-center justify-center text-[#3B302D]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke-width="1.8"
                                 stroke="currentColor"
                                 class="w-5 h-5">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 00-2.312 6.022c1.733.64 3.56 1.08 5.454 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>

                            </svg>

                        </div>

                        <div>

                            <h4 class="text-[16px] font-semibold text-[#3B302D]">
                                Pengaturan Notifikasi
                            </h4>

                            <p class="text-[14px] font-normal text-[#3B302D]">
                                Atur preferensi notifikasi anda
                            </p>

                        </div>

                    </div>

                    <span class="text-[#3B302D] text-[18px]">
                        ›
                    </span>

                </div>

                {{-- ITEM 3 --}}
                <div class="flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200 cursor-pointer">

                    <div class="flex items-center gap-5">

                        <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2]
                                    flex items-center justify-center text-[#3B302D]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke-width="1.8"
                                 stroke="currentColor"
                                 class="w-5 h-5">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5z"/>

                            </svg>

                        </div>

                        <div>

                            <h4 class="text-[16px] font-semibold text-[#3B302D]">
                                Keamanan & Privasi
                            </h4>

                            <p class="text-[14px] font-normal text-[#3B302D]">
                                Ubah informasi login dan keamanan anda
                            </p>

                        </div>

                    </div>

                    <span class="text-[#3B302D] text-[18px]">
                        ›
                    </span>

                </div>

                {{-- ITEM 4 --}}
                <div class="flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200 cursor-pointer">

                    <div class="flex items-center gap-5">

                        <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2]
                                    flex items-center justify-center text-[#3B302D]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke-width="1.8"
                                 stroke="currentColor"
                                 class="w-5 h-5">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M4.5 12.75l6 6 9-13.5"/>

                            </svg>

                        </div>

                        <div>

                            <h4 class="text-[16px] font-semibold text-[#3B302D]">
                                Layanan Aktif
                            </h4>

                            <p class="text-[14px] font-normal text-[#3B302D]">
                                Kelola layanan yang tersedia untuk klien
                            </p>

                        </div>

                    </div>

                    <span class="text-[#3B302D] text-[18px]">
                        ›
                    </span>

                </div>

            </div>

        </div>

    </div>

    {{-- LOGOUT --}}
    <button
        class=" w-full mt-10 bg-white border-[3px] border-[#F1A9B1] rounded-[15px] py-4
               hover:bg-[#FFF1F3] transition duration-300">
<h4 class="text-[18px] font-bold text-[#3B302D]">
                                ⎋ Keluar Akun
                            </h4>
        

    </button>

</div>

@endsection