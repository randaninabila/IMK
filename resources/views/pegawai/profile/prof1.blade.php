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

                    {{-- FOTO PROFILE --}}
                    <img
                        src="{{ $user->foto_profile ? asset('storage/' . $user->foto_profile) : 'https://i.pravatar.cc/150?u=' . $user->user_id }}"
                        alt="profile"
                        class="w-28 h-28 rounded-full object-cover border-[5px] border-white shadow-sm"
                    >

                    <div>

                        <div class="flex items-center gap-3">

                            <h2 class="text-[18px] font-bold text-[#3B302D]">
                                {{ $user->nama }}
                            </h2>

                            @if($user->status_akun === 'aktif')
                                <span class="px-3 py-1 rounded-full bg-[#F9D5DA] text-[#3B302D] text-[12px] font-normal">
                                    Verified Specialist
                                </span>
                            @endif

                        </div>

                        {{-- CABANG --}}
                        <p class="text-[14px] font-normal text-[#3B302D] mt-1">
                            {{ $pegawai?->cabang?->nama_cabang ?? 'Cabang tidak ditemukan' }}
                        </p>

                        <!-- {{-- EMAIL --}}
                        <p class="text-[14px] font-normal text-[#3B302D] mt-1">
                            {{ $user->email }}
                        </p>

                        {{-- NO HP --}}
                        <p class="text-[14px] font-normal text-[#9B8B87]">
                            {{ $user->no_hp ?? '-' }}
                        </p> -->

                    </div>

                </div>
            </div>

        </div>

        {{-- SETTINGS --}}
        <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 pb-6 pt-6">

             {{-- PERSONAL INFORMATION --}}

                    <h2 class="text-[20px] font-bold text-[#2F2A2A] mb-5">
                        Personal Information
                    </h2>

                    <div class="space-y-5 max-w-[480px]">

                        <div>
                            <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                                Name
                            </label>

                            <input
                                type="text"
                                value="Randani Nabila Desti"
                                class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#6E6969] outline-none"
                            >

                        </div>
                        <div>
                            <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                                Email Address
                            </label>

                            <input
                                type="email"
                                value="randeniy@gmail.com"
                                class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#6E6969] outline-none"
                            >
                        </div>

                    </div>

                </div>

                {{-- CHANGE PASSWORD --}}
                <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 pb-6 pt-6">

                    <h2 class="text-[20px] font-bold text-[#2F2A2A] mb-5">
                        Change Password
                    </h2>

                    <div class="grid grid-cols-2 gap-8">

                        <div class="space-y-5">

                            <div>
                                <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                                    Current Password
                                </label>

                                <input
                                    type="password"
                                    value="password"
                                    class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#6E6969] outline-none"
                                >
                            </div>

                            <div>
                                <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                                    New Password
                                </label>

                                <input
                                    type="password"
                                    value="password"
                                    class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#6E6969] outline-none"
                                >
                            </div>

                        </div>

                        <div class="flex items-end">

                            <div class="w-full">
                                <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                                    Confirm New Password
                                </label>

                                <input
                                    type="password"
                                    value="password"
                                    class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#6E6969] outline-none"
                                >
                            </div>


                </div>

    {{-- BUTTON --}}
                <div>
                    <a href="/prof2">
                    <button
                        class="mt-6 border-[3px] border-[#F1A9B1] rounded-xl px-6 h-[56px] flex items-center gap-3 text-[16px] font-medium text-[#3B302D] hover:bg-[#FFF4F5] transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="w-5 h-5">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                        </svg>

                        Edit Profil
                    </button>
                    </a>
                </div>

    <!-- {{-- LOGOUT --}}
    <form action="{{ route('logout') }}" method="POST" class="mt-10">
        @csrf
        <button type="submit"
            class="w-full bg-white border-[3px] border-[#F1A9B1] rounded-[15px] py-4
                   hover:bg-[#FFF1F3] transition duration-300">
            <h4 class="text-[18px] font-bold text-[#3B302D]">⎋ Keluar Akun</h4>
        </button>
    </form>  -->

</div>

@endsection