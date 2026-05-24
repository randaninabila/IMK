@extends('pegawai.app')

@section('content')

<div class="w-full px-4 py-4 font-sans text-[#3B302D]">

    {{-- TITLE --}}
    <div>
        <h1 class="text-[26px] font-bold leading-none">Profile</h1>
        <p class="mt-2 text-[16px]">Kelola informasi profesional pengaturan akun Anda</p>
    </div>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div class="mt-4 px-6 py-3 bg-green-100 border border-green-300 text-green-800 rounded-2xl text-[15px]">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-4 px-6 py-3 bg-red-100 border border-red-300 text-red-800 rounded-2xl text-[15px]">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ===========================
         PROFILE CARD
    ============================ --}}
    <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 py-6">


                    {{-- FOTO PROFILE --}}
                    <img
                        src="{{ $user->foto_profile ? asset('storage/' . $user->foto_profile) : 'https://i.pravatar.cc/150?u=' . $user->user_id }}"
                        alt="profile"
                        class="w-28 h-28 rounded-full object-cover border-[5px] border-white shadow-sm"
                    >


            {{-- LEFT — avatar + info --}}
            <div class="flex items-center gap-6">

                <img
                    src="{{ $user->foto_profile ? asset('storage/'.$user->foto_profile) : 'https://i.pravatar.cc/150?u='.$user->user_id }}"
                    alt="profile"
                    class="w-28 h-28 rounded-full object-cover border-[5px] border-white shadow-sm"
                >


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

                    <p class="text-[14px] font-normal text-[#3B302D] mt-1">
                        Salon & Beauty Specialist
                        @if ($pegawai->status_kerja)
                            · <span class="capitalize">{{ $pegawai->status_kerja }}</span>
                        @endif
                    </p>

                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-yellow-400 text-[18px]">★</span>
                        <p class="text-[14px] font-normal text-[#3B302D]">
                            {{ $ratingAvg ? number_format($ratingAvg, 1) : '-' }}
                            ({{ $ratingCount }} ulasan) · {{ $totalSelesai }} sesi selesai
                        </p>
                    </div>

                    <p class="text-[14px] font-normal text-[#3B302D] mt-1">
                        {{ $user->email }}
                    </p>

                    @if ($user->no_hp)
                        <p class="text-[14px] font-normal text-[#3B302D]">
                            {{ $user->no_hp }}
                        </p>
                    @endif

                </div>

            </div>

            {{-- EDIT BUTTON → buka modal --}}
            <button
                onclick="document.getElementById('modalEditProfil').classList.remove('hidden')"
                class="px-6 py-3 border-[2px] border-[#D9B8BD] rounded-[16px]
                       text-[#3B302D] text-[17px] font-semibold
                       hover:bg-[#FFF1F3] transition duration-300">
                ✎ Edit Profil
            </button>

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

            {{-- Jadwal Kerja --}}
            <a href="{{ route('pegawai.jadwal-kerja') }}"
               class="flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200">
                <div class="flex items-center gap-5">
                    <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2] flex items-center justify-center text-[#3B302D]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75V4.5m7.5 2.25V4.5m-9 6h10.5m-13.5 9h16.5A2.25 2.25 0 0021 17.25V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5z"/>
                        </svg>
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
                <span class="text-[#3B302D] text-[18px]">›</span>
            </a>

            {{-- Notifikasi → buka modal ganti password --}}
            <button
                onclick="document.getElementById('modalNotifikasi').classList.remove('hidden')"
                class="w-full flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200">
                <div class="flex items-center gap-5">
                    <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2] flex items-center justify-center text-[#3B302D]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 00-2.312 6.022c1.733.64 3.56 1.08 5.454 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h4 class="text-[16px] font-semibold text-[#3B302D]">Pengaturan Notifikasi</h4>
                        <p class="text-[14px] font-normal text-[#3B302D]">Atur preferensi notifikasi anda</p>
                    </div>
                </div>
                <span class="text-[#3B302D] text-[18px]">›</span>
            </button>

            {{-- Keamanan → buka modal ganti password --}}
            <button
                onclick="document.getElementById('modalPassword').classList.remove('hidden')"
                class="w-full flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200">
                <div class="flex items-center gap-5">
                    <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2] flex items-center justify-center text-[#3B302D]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h4 class="text-[16px] font-semibold text-[#3B302D]">Keamanan & Privasi</h4>
                        <p class="text-[14px] font-normal text-[#3B302D]">Ubah informasi login dan keamanan anda</p>
                    </div>
                </div>
                <span class="text-[#3B302D] text-[18px]">›</span>
            </button>

            {{-- Layanan Aktif --}}
            <div class="flex items-center justify-between px-6 py-3 hover:bg-[#FFF7F8] transition duration-200 cursor-pointer">
                <div class="flex items-center gap-5">
                    <div class="w-11 h-11 rounded-[14px] bg-[#FFF0F2] flex items-center justify-center text-[#3B302D]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[16px] font-semibold text-[#3B302D]">Layanan Aktif</h4>
                        <p class="text-[14px] font-normal text-[#3B302D]">Kelola layanan yang tersedia untuk klien</p>
                    </div>
                </div>
                <span class="text-[#3B302D] text-[18px]">›</span>
            </div>

        </div>

    </div>

    {{-- LOGOUT --}}
    <form action="{{ route('pegawai.logout') }}" method="POST">
        @csrf
        <button type="submit"
            class="w-full mt-10 bg-white border-[3px] border-[#F1A9B1] rounded-[15px] py-4
                   hover:bg-[#FFF1F3] transition duration-300">
            <h4 class="text-[18px] font-bold text-[#3B302D]">⎋ Keluar Akun</h4>
        </button>
    </form>



</div>

{{-- ===========================
     MODAL — EDIT PROFIL
============================ --}}
<div id="modalEditProfil" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-[24px] w-full max-w-md px-8 py-7 shadow-xl border-[3px] border-[#F1A9B1]">

        <h3 class="text-[20px] font-bold text-[#3B302D] mb-5">Edit Profil</h3>

        <form action="{{ route('pegawai.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Foto --}}
            <div class="flex items-center gap-5 mb-5">
                <img id="previewFoto"
                     src="{{ $user->foto_profile ? asset('storage/'.$user->foto_profile) : 'https://i.pravatar.cc/150?u='.$user->user_id }}"
                     class="w-20 h-20 rounded-full object-cover border-4 border-[#F1A9B1]">
                <div>
                    <label class="cursor-pointer px-4 py-2 rounded-xl border-[2px] border-[#F1A9B1] text-[14px] font-semibold text-[#3B302D] hover:bg-[#FFF1F3] transition">
                        Ganti Foto
                        <input type="file" name="foto_profile" accept="image/*" class="hidden"
                               onchange="previewImage(event)">
                    </label>
                    <p class="text-[12px] text-gray-400 mt-1">JPG, PNG maks 2MB</p>
                </div>
            </div>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-[14px] font-semibold text-[#3B302D] mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                       class="w-full border border-[#E9B9C0] rounded-xl px-4 py-2.5 text-[15px] outline-none focus:border-[#F5A6AF] transition"
                       required>
            </div>

            {{-- No HP --}}
            <div class="mb-6">
                <label class="block text-[14px] font-semibold text-[#3B302D] mb-1">No. Telepon</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                       class="w-full border border-[#E9B9C0] rounded-xl px-4 py-2.5 text-[15px] outline-none focus:border-[#F5A6AF] transition">
            </div>

            <div class="flex gap-3">
                <button type="button"
                        onclick="document.getElementById('modalEditProfil').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-xl border-[2px] border-[#D9B8BD] text-[#3B302D] font-semibold hover:bg-[#FFF1F3] transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-xl bg-[#F5A6AF] text-white font-semibold hover:opacity-90 transition">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- ===========================
     MODAL — GANTI PASSWORD
============================ --}}
<div id="modalPassword" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-[24px] w-full max-w-md px-8 py-7 shadow-xl border-[3px] border-[#F1A9B1]">

        <h3 class="text-[20px] font-bold text-[#3B302D] mb-5">Ganti Password</h3>

        <form action="{{ route('pegawai.profile.password') }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-[14px] font-semibold text-[#3B302D] mb-1">Password Lama</label>
                <input type="password" name="password_lama"
                       class="w-full border border-[#E9B9C0] rounded-xl px-4 py-2.5 text-[15px] outline-none focus:border-[#F5A6AF] transition"
                       required>
                @error('password_lama')
                    <p class="text-red-500 text-[13px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-[14px] font-semibold text-[#3B302D] mb-1">Password Baru</label>
                <input type="password" name="password_baru"
                       class="w-full border border-[#E9B9C0] rounded-xl px-4 py-2.5 text-[15px] outline-none focus:border-[#F5A6AF] transition"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-[14px] font-semibold text-[#3B302D] mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation"
                       class="w-full border border-[#E9B9C0] rounded-xl px-4 py-2.5 text-[15px] outline-none focus:border-[#F5A6AF] transition"
                       required>
            </div>

            <div class="flex gap-3">
                <button type="button"
                        onclick="document.getElementById('modalPassword').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-xl border-[2px] border-[#D9B8BD] text-[#3B302D] font-semibold hover:bg-[#FFF1F3] transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-xl bg-[#F5A6AF] text-white font-semibold hover:opacity-90 transition">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- ===========================
     MODAL — NOTIFIKASI (placeholder)
============================ --}}
<div id="modalNotifikasi" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-[24px] w-full max-w-md px-8 py-7 shadow-xl border-[3px] border-[#F1A9B1]">
        <h3 class="text-[20px] font-bold text-[#3B302D] mb-3">Pengaturan Notifikasi</h3>
        <p class="text-[15px] text-[#3B302D] mb-6">Fitur ini akan segera tersedia.</p>
        <button onclick="document.getElementById('modalNotifikasi').classList.add('hidden')"
                class="w-full py-2.5 rounded-xl bg-[#F5A6AF] text-white font-semibold hover:opacity-90 transition">
            Tutup
        </button>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById('previewFoto').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Buka modal langsung jika ada error validasi (setelah submit)
    @if ($errors->has('password_lama') || $errors->has('password_baru'))
        document.getElementById('modalPassword').classList.remove('hidden');
    @endif
    @if ($errors->has('nama') || $errors->has('no_hp') || $errors->has('foto_profile'))
        document.getElementById('modalEditProfil').classList.remove('hidden');
    @endif
</script>

@endsection