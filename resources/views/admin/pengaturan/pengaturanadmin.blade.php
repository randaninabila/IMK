<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pengaturan - Dina Salon Muslimah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            overflow-x: hidden;
        }

        .soft-card {
            box-shadow: 0 10px 24px rgba(58, 55, 46, 0.10);
        }

        .soft-shadow {
            box-shadow: 0 8px 18px rgba(58, 55, 46, 0.10);
        }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

@php
    $admin = $admin ?? null;

    $adminName = old('nama', $admin->nama ?? 'Admin Salon');
    $adminEmail = old('email', $admin->email ?? 'admin@salon.com');
    $adminPhone = old('no_hp', $admin->no_hp ?? '');
    $adminPhoto = $admin->foto_profile ?? null;
@endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="ml-[235px] w-[calc(100%-235px)] min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        <header class="h-[92px] px-[58px] flex items-center justify-between">

            <h2 class="text-[22px] font-extrabold text-[#3F3838] tracking-[-0.03em]">
                Halo, <span class="italic">{{ $adminName }}</span> 👋
            </h2>

            <div class="flex items-center gap-[22px]">

                {{-- PROFILE DROPDOWN PARTIAL --}}
                <div class="relative flex items-center">
                    @include('admin.partial.dropdownadmin')
                </div>

            </div>
        </header>

        @if(session('success'))
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-green-100 text-green-700 px-5 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="px-[42px] mt-[14px] pb-[45px]">

            <div class="bg-white rounded-[18px] min-h-[610px] px-[24px] pt-[24px] pb-[30px] soft-card border border-[#F1D9DD]">

                <h1 class="text-[34px] font-extrabold text-[#3F3838] mb-[20px]">
                    Pengaturan
                </h1>

                <form action="{{ route('admin.pengaturan.profile.update') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="border border-[#E8D1D5] rounded-[14px] min-h-[280px] px-[18px] pt-[18px] pb-[18px] mb-[14px] bg-[#FFFDFD]">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-[250px_1fr] gap-[20px]">

                        <div class="flex gap-[10px]">
                            <div class="w-[42px] h-[42px] rounded-full bg-[#E8A9B4] text-white flex items-center justify-center text-[28px] font-extrabold leading-none">
                                1
                            </div>

                            <div>
                                <h2 class="text-[22px] font-extrabold text-[#3F3838] leading-none mt-[10px]">
                                    Profil Admin
                                </h2>

                                <p class="text-[12px] text-[#9A8B86] font-semibold mt-[8px]">
                                    Kelola informasi akun admin Anda
                                </p>

                                <p class="text-[13px] font-extrabold text-[#3F3838] mt-[17px]">
                                    Foto Profil
                                </p>

                                <label class="mt-[7px] w-[150px] h-[150px] rounded-full bg-[#F0E7E7] border border-[#E8D1D5] block cursor-pointer overflow-hidden">
                                    <input type="file"
                                           name="foto_profile"
                                           class="hidden"
                                           accept="image/png,image/jpeg,image/jpg"
                                           onchange="previewPhoto(event)">

                                    @if($adminPhoto)
                                        <img id="profilePreview"
                                             src="{{ asset($adminPhoto) }}"
                                             class="w-full h-full object-cover"
                                             alt="Preview">
                                    @else
                                        <img id="profilePreview"
                                             class="w-full h-full object-cover hidden"
                                             alt="Preview">
                                    @endif
                                </label>

                                <p class="text-[12px] text-[#7D7774] font-semibold mt-[10px] ml-[8px]">
                                    JPG, PNG (maks 2MB)
                                </p>
                            </div>
                        </div>

                        <div class="pt-[70px] grid grid-cols-[300px_300px_1fr] gap-[27px]">

                            <div>
                                <label class="text-[17px] font-extrabold text-[#3F3838]">Nama Lengkap</label>
                                <input name="nama"
                                       type="text"
                                       value="{{ $adminName }}"
                                       class="mt-[8px] w-full h-[42px] border border-[#D8C4C7] rounded-[8px] px-[16px] text-[17px] bg-white outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                       required>
                            </div>

                            <div>
                                <label class="text-[17px] font-extrabold text-[#3F3838]">No. Telepon</label>
                                <input name="no_hp"
                                       type="text"
                                       value="{{ $adminPhone }}"
                                       class="mt-[8px] w-full h-[42px] border border-[#D8C4C7] rounded-[8px] px-[16px] text-[17px] bg-white outline-none focus:ring-2 focus:ring-[#E8A9B4]">
                            </div>

                            <div class="row-span-2 flex items-end justify-end pb-[6px]">
                                <button type="submit"
                                        class="w-[200px] h-[50px] rounded-[8px] bg-[#5A4B4B] hover:bg-[#473B3B] text-white text-[19px] font-extrabold transition">
                                    Simpan Profil
                                </button>
                            </div>

                            <div>
                                <label class="text-[17px] font-extrabold text-[#3F3838]">Email</label>
                                <input name="email"
                                       type="email"
                                       value="{{ $adminEmail }}"
                                       class="mt-[8px] w-full h-[42px] border border-[#D8C4C7] rounded-[8px] px-[16px] text-[17px] bg-white outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                       required>
                            </div>

                        </div>
                    </div>

                </form>

                <form action="{{ route('admin.pengaturan.password.update') }}"
                      method="POST"
                      class="border border-[#E8D1D5] rounded-[14px] min-h-[280px] px-[18px] pt-[20px] pb-[18px] bg-[#FFFDFD]">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-[235px_1fr] gap-[28px]">

                        <div class="flex gap-[10px]">
                            <div class="w-[42px] h-[42px] rounded-full bg-[#E8A9B4] text-white flex items-center justify-center text-[28px] font-extrabold leading-none">
                                2
                            </div>

                            <div>
                                <h2 class="text-[22px] font-extrabold text-[#3F3838] leading-none mt-[10px]">
                                    Ganti Password
                                </h2>

                                <p class="text-[12px] text-[#9A8B86] font-semibold mt-[8px] leading-tight">
                                    Ubah password secara berkala<br>
                                    untuk keamanan akun
                                </p>
                            </div>
                        </div>

                        <div class="pt-[85px] grid grid-cols-3 gap-[28px]">

                            <div>
                                <label class="text-[17px] font-extrabold text-[#3F3838]">Password Lama</label>
                                <input name="current_password"
                                       type="password"
                                       placeholder="Masukkan Password Lama"
                                       class="mt-[8px] w-full h-[42px] border border-[#D8C4C7] rounded-[8px] px-[12px] text-[15px] bg-white outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                       required>
                            </div>

                            <div>
                                <label class="text-[17px] font-extrabold text-[#3F3838]">Password Baru</label>
                                <input name="password"
                                       type="password"
                                       placeholder="Masukkan Password Baru"
                                       class="mt-[8px] w-full h-[42px] border border-[#D8C4C7] rounded-[8px] px-[12px] text-[15px] bg-white outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                       required>
                            </div>

                            <div>
                                <label class="text-[17px] font-extrabold text-[#3F3838]">Konfirmasi Password Baru</label>
                                <input name="password_confirmation"
                                       type="password"
                                       placeholder="Konfirmasi Password Baru"
                                       class="mt-[8px] w-full h-[42px] border border-[#D8C4C7] rounded-[8px] px-[12px] text-[15px] bg-white outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                       required>
                            </div>

                            <div class="col-span-3 flex justify-end pt-[26px]">
                                <button type="submit"
                                        class="w-[200px] h-[50px] rounded-[8px] bg-[#5A4B4B] hover:bg-[#473B3B] text-white text-[19px] font-extrabold transition">
                                    Update Password
                                </button>
                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </section>

    </main>
</div>

<script>
    function toggleDropdown(id) {
        const target = document.getElementById(id);
        const dropdowns = [];

        dropdowns.forEach((dropdownId) => {
            const dropdown = document.getElementById(dropdownId);

            if (dropdown && dropdownId !== id) {
                dropdown.classList.add('hidden');
            }
        });

        if (target) {
            target.classList.toggle('hidden');
        }
    }

    function previewPhoto(event) {
        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const preview = document.getElementById('profilePreview');
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    }

    document.addEventListener('click', function(event) {
        const insideDropdown = null;
        const dropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');

        if (!insideDropdown && !dropdownButton) {
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(function (el) {
            setTimeout(function () {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 500);
            }, 3000);
        });
    });
</script>

</body>
</html>