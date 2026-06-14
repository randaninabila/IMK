<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Promo - Dina Salon Muslimah</title>

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

        .preview-shadow {
            box-shadow: 0 10px 26px rgba(58, 55, 46, 0.12);
        }

        .modal-bg {
            background: rgba(0, 0, 0, 0.28);
        }
    </style>

    {{-- FONT SCALE RESTORE --}}
    <script>
    (function () {
        const FONT_STEPS = [75,80,85,90,95,100,105,110,115,120,125];
        const saved = parseInt(localStorage.getItem('fontStep'));
        const step  = (!isNaN(saved) && saved >= 0 && saved < FONT_STEPS.length) ? saved : 5;
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.zoom = FONT_STEPS[step] / 100;
        });
    })();
    </script>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

@php
    $branches = $branches ?? collect();
    $promoServices = $promoServices ?? collect();
    $selectedCabangId = $selectedCabangId ?? null;
    $selectedBranch = $selectedBranch ?? null;
    $selectedService = $selectedService ?? null;
    $activePromo = $activePromo ?? null;

    $branchButtonText = $selectedBranch
        ? ($selectedBranch->label ?? $selectedBranch->nama_cabang)
        : 'Pilih Cabang';

    $serviceOptions = $promoServices->map(function ($service) {
        return [
            'layanan_cabang_id' => (int) $service->layanan_cabang_id,
            'layanan_id' => (int) $service->layanan_id,
            'cabang_id' => (int) $service->cabang_id,
            'nama_layanan' => $service->nama_layanan,
            'nama_jenis' => $service->nama_jenis,
            'harga' => (float) $service->harga,
            'harga_promo' => $service->harga_promo !== null ? (float) $service->harga_promo : null,
            'nama_cabang' => $service->nama_cabang,
            'default_judul' => 'Promo ' . $service->nama_layanan,
            'default_deskripsi' => 'Harga spesial untuk layanan ' . $service->nama_layanan . ' di ' . $service->nama_cabang . '.',
        ];
    })->values();

    $selectedServiceId = $selectedService->layanan_cabang_id ?? null;
    $activePromoId = $activePromo->layanan_cabang_id ?? null;

    $judulValue = old('judul_promo');

    if (!$judulValue) {
        if ($activePromo && $selectedService && (int) $activePromo->layanan_cabang_id === (int) $selectedService->layanan_cabang_id) {
            $judulValue = $activePromo->judul_promo;
        } elseif ($selectedService) {
            $judulValue = 'Promo ' . $selectedService->nama_layanan;
        } else {
            $judulValue = '';
        }
    }

    $deskripsiValue = old('deskripsi_promo');

    if (!$deskripsiValue) {
        if ($activePromo && $selectedService && (int) $activePromo->layanan_cabang_id === (int) $selectedService->layanan_cabang_id) {
            $deskripsiValue = $activePromo->deskripsi_promo;
        } elseif ($selectedService) {
            $deskripsiValue = 'Harga spesial untuk layanan ' . $selectedService->nama_layanan . ' di ' . $selectedService->nama_cabang . '.';
        } else {
            $deskripsiValue = '';
        }
    }
@endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="lg:ml-[235px] lg:w-[calc(100%-235px)] w-full min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        <header class="h-[92px] px-4 lg:px-[58px] flex items-center justify-between gap-3">

            <button type="button"
                    onclick="adminSidebarOpen()"
                    class="lg:hidden p-2 rounded-[8px] text-[#6B4D46] hover:bg-[#FFF1F1] transition shrink-0"
                    aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h2 class="text-[22px] font-extrabold text-[#3F3838] tracking-[-0.03em]">
                Halo, <span class="italic">Admin</span> Salon Dina Muslimah 👋
            </h2>

            <div class="flex items-center gap-[22px]">

                <div class="relative">
                    <button type="button"
                            onclick="toggleDropdown('branchDropdown')"
                            class="h-[50px] min-w-[202px] bg-[#E8A9B4] text-white rounded-[7px] px-[12px] flex items-center justify-between gap-[12px] font-extrabold hover:bg-[#D995A1] transition">
                        <span class="flex items-center gap-[8px]">
                            <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                                <path d="M12 21S5 14.7 5 8.8C5 4.9 8.1 2 12 2C15.9 2 19 4.9 19 8.8C19 14.7 12 21 12 21Z" stroke="white" stroke-width="2"/>
                                <circle cx="12" cy="8.8" r="2.5" stroke="white" stroke-width="2"/>
                            </svg>

                            <span id="branchText" class="text-[13px]">
                                {{ $branchButtonText }}
                            </span>
                        </span>

                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div id="branchDropdown"
                         class="hidden absolute top-[58px] left-0 w-full bg-white rounded-[12px] shadow-xl border border-[#F1D9DD] overflow-hidden z-50">
                        @forelse($branches as $branch)
                            <a href="{{ route('admin.inputpromo', ['cabang_id' => $branch->cabang_id]) }}"
                               class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ (int) $selectedCabangId === (int) $branch->cabang_id ? 'bg-[#FFF0F2]' : '' }}">
                                {{ $branch->label ?? $branch->nama_cabang }}
                            </a>
                        @empty
                            <div class="px-4 py-3 text-sm font-bold text-[#8B7777]">
                                Belum ada cabang
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- PROFILE DROPDOWN PARTIAL --}}
                <div class="relative flex items-center">
                    @include('admin.partial.dropdownadmin')
                </div>

            </div>
        </header>

        @if(session('success'))
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-green-100 text-green-700 px-5 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="px-4 lg:px-[42px] mt-[14px] pb-[45px]">

            <div class="bg-[#FDE7EC] rounded-[18px] soft-card min-h-[850px] px-[28px] pt-[24px] pb-[36px]">

                <div class="flex items-start justify-between mb-[26px]">
                    <div>
                        <h1 class="text-[28px] font-extrabold text-[#3F3838]">
                            Masukkan Promo
                        </h1>

                        <p class="mt-[5px] text-[14px] font-semibold text-[#7A6A63]">
                            Pilih promo layanan dari database. Harga normal dan harga promo otomatis mengikuti cabang yang dipilih.
                        </p>
                    </div>

                    @if($activePromo)
                        <button type="button"
                                onclick="openDeactivateModal()"
                                class="rounded-full bg-[#A8BD8C] px-[18px] py-[8px] text-[13px] font-extrabold text-white hover:opacity-90 transition">
                            Promo Aktif
                        </button>
                    @else
                        <div class="rounded-full bg-[#E8A9B4] px-[18px] py-[8px] text-[13px] font-extrabold text-white">
                            Promo Belum Aktif
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-[430px_minmax(0,1fr)] gap-[32px]">

                    <div class="bg-white rounded-[18px] preview-shadow px-[24px] py-[24px] min-h-[560px]">

                        <h2 class="text-[22px] font-extrabold text-[#3F3838]">
                            Form Promo Layanan
                        </h2>

                        <p class="mt-[6px] text-[13px] font-semibold text-[#8A7A74]">
                            Dropdown hanya menampilkan layanan yang sudah punya harga promo.
                        </p>

                        <form id="promoForm"
                              action="{{ route('admin.inputpromo.activate') }}"
                              method="POST"
                              class="mt-[22px] space-y-[15px]">
                            @csrf

                            <div>
                                <label class="block text-[13px] font-extrabold text-[#3F3838] mb-[7px]">
                                    Cabang Salon
                                </label>

                                <input type="text"
                                       value="{{ $branchButtonText }}"
                                       readonly
                                       class="w-full h-[44px] rounded-[8px] bg-[#F8F1F2] px-[13px] text-[14px] font-bold outline-none text-[#6B5A55]">
                            </div>

                            <div>
                                <label class="block text-[13px] font-extrabold text-[#3F3838] mb-[7px]">
                                    Promo Layanan
                                </label>

                                <select id="layananSelect"
                                        name="layanan_cabang_id"
                                        onchange="handleServiceChange()"
                                        class="w-full h-[44px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[14px] font-bold outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                        required>
                                    <option value="">Pilih promo</option>

                                    @foreach($promoServices as $item)
                                        <option
                                            value="{{ $item->layanan_cabang_id }}"
                                            {{ (int) $selectedServiceId === (int) $item->layanan_cabang_id ? 'selected' : '' }}>
                                            {{ $item->nama_layanan }} — Rp {{ number_format($item->harga_promo, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[13px] font-extrabold text-[#3F3838] mb-[7px]">
                                    Kategori / Jenis Layanan
                                </label>

                                <input id="jenisInput"
                                       type="text"
                                       readonly
                                       placeholder="Otomatis"
                                       class="w-full h-[44px] rounded-[8px] bg-[#F8F1F2] px-[13px] text-[14px] font-bold outline-none text-[#6B5A55]">
                            </div>

                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-[13px] font-extrabold text-[#3F3838] mb-[7px]">
                                        Harga Normal
                                    </label>

                                    <input id="hargaNormalInput"
                                           type="text"
                                           readonly
                                           placeholder="Otomatis"
                                           class="w-full h-[44px] rounded-[8px] bg-[#F8F1F2] px-[13px] text-[14px] font-bold outline-none text-[#6B5A55]">
                                </div>

                                <div>
                                    <label class="block text-[13px] font-extrabold text-[#3F3838] mb-[7px]">
                                        Harga Promo
                                    </label>

                                    <input id="hargaPromoInput"
                                           type="text"
                                           readonly
                                           placeholder="Otomatis"
                                           class="w-full h-[44px] rounded-[8px] bg-[#F8F1F2] px-[13px] text-[14px] font-bold outline-none text-[#B85C6A]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[13px] font-extrabold text-[#3F3838] mb-[7px]">
                                    Judul Promo
                                </label>

                                <input id="judulPromoInput"
                                       name="judul_promo"
                                       type="text"
                                       value="{{ $judulValue }}"
                                       placeholder="Contoh: Promo Facial Akhir Bulan"
                                       oninput="updatePreviewText()"
                                       class="w-full h-[44px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[14px] font-bold outline-none focus:ring-2 focus:ring-[#E8A9B4] text-[#6B5A55]"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[13px] font-extrabold text-[#3F3838] mb-[7px]">
                                    Deskripsi Singkat
                                </label>

                                <textarea id="deskripsiPromoInput"
                                          name="deskripsi_promo"
                                          rows="4"
                                          placeholder="Tulis deskripsi promo yang ingin ditampilkan ke user"
                                          oninput="updatePreviewText()"
                                          class="w-full rounded-[8px] bg-[#FFF0F2] px-[13px] py-[11px] text-[14px] font-bold outline-none resize-none focus:ring-2 focus:ring-[#E8A9B4] text-[#6B5A55]"
                                          required>{{ $deskripsiValue }}</textarea>
                            </div>

                            <p id="promoWarning"
                               class="hidden rounded-[10px] bg-[#FFF4D5] px-[14px] py-[10px] text-[13px] font-extrabold text-[#7A6335]">
                                Belum ada promo yang bisa dipilih pada cabang ini.
                            </p>

                            <div class="grid grid-cols-2 gap-[12px] pt-[6px]">
                                <button id="activateBtn"
                                        type="submit"
                                        class="h-[44px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white text-[15px] font-extrabold transition">
                                    Aktifkan Promo
                                </button>

                                <button type="button"
                                        onclick="resetPreviewNoPromo()"
                                        class="h-[44px] rounded-[8px] bg-[#5A4B4B] hover:bg-[#473B3B] text-white text-[15px] font-extrabold transition">
                                    Reset Preview
                                </button>
                            </div>

                        </form>
                    </div>

                    <div class="bg-white rounded-[18px] preview-shadow px-[28px] py-[24px] min-h-[560px]">

                        <div class="flex items-center justify-between mb-[20px]">
                            <div>
                                <h2 class="text-[22px] font-extrabold text-[#3F3838]">
                                    Preview Promo User
                                </h2>

                                <p class="mt-[5px] text-[13px] font-semibold text-[#8A7A74]">
                                    Preview mengikuti promo yang dipilih dan teks yang diketik admin.
                                </p>
                            </div>

                            <span class="rounded-full bg-[#F8E8E5] px-[15px] py-[7px] text-[12px] font-extrabold text-[#8A4357]">
                                Badge Promo
                            </span>
                        </div>

                        <div class="rounded-[24px] bg-gradient-to-b from-[#FFF0F2] via-[#FFF8F9] to-white px-[30px] py-[32px] shadow-[0_12px_28px_rgba(58,55,46,0.12)]">

                            <div class="flex items-center justify-between">
                                <div class="w-[70px] h-[70px] rounded-full bg-[#E8A9B4] text-white flex items-center justify-center text-[40px] font-black">
                                    %
                                </div>

                                <div class="text-right">
                                    <p class="text-[13px] font-extrabold text-[#8A4357]">
                                        Dina Salon Muslimah
                                    </p>
                                    <p class="text-[12px] font-bold text-[#7A6A63]">
                                        Promo layanan pilihan
                                    </p>
                                </div>
                            </div>

                            <h3 id="previewJudul"
                                class="mt-[26px] text-[34px] font-black leading-[1.05] tracking-[-0.05em] text-[#3F3838]">
                                Promo Belum Dipilih
                            </h3>

                            <p id="previewDeskripsi"
                               class="mt-[12px] text-[14px] leading-[1.45] font-semibold text-[#6B5A55]">
                                Pilih promo untuk melihat preview.
                            </p>

                            <div class="mt-[24px] rounded-[18px] bg-white px-[18px] py-[18px] shadow-sm">
                                <div class="grid grid-cols-2 gap-[14px]">
                                    <div>
                                        <p class="text-[12px] font-extrabold text-[#9A6B76]">
                                            Layanan
                                        </p>
                                        <p id="previewLayanan" class="mt-[4px] text-[16px] font-extrabold text-[#3F3838]">
                                            -
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-[12px] font-extrabold text-[#9A6B76]">
                                            Jenis
                                        </p>
                                        <p id="previewJenis" class="mt-[4px] text-[16px] font-extrabold text-[#3F3838]">
                                            -
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-[12px] font-extrabold text-[#9A6B76]">
                                            Cabang
                                        </p>
                                        <p id="previewCabang" class="mt-[4px] text-[16px] font-extrabold text-[#3F3838]">
                                            -
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-[12px] font-extrabold text-[#9A6B76]">
                                            Harga Promo
                                        </p>
                                        <p id="previewHargaPromo" class="mt-[4px] text-[20px] font-black text-[#B85C6A]">
                                            -
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-[18px] flex items-end justify-between border-t border-[#F4D8DD] pt-[14px]">
                                    <div>
                                        <p class="text-[12px] font-extrabold text-[#9A6B76]">
                                            Harga Normal
                                        </p>
                                        <p id="previewHargaNormal" class="mt-[2px] text-[15px] font-extrabold text-[#6B5A55] line-through">
                                            -
                                        </p>
                                    </div>

                                    <button type="button"
                                            class="rounded-full bg-[#E8A9B4] px-[22px] py-[10px] text-[13px] font-extrabold text-white">
                                        Booking Sekarang
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="mt-[22px]">
                            <div class="flex items-center justify-between mb-[12px]">
                                <h3 class="text-[18px] font-extrabold text-[#3F3838]">
                                    Promo yang Sedang Aktif
                                </h3>

                                <span class="text-[12px] font-extrabold text-[#8A7A74]">
                                    {{ $activePromo ? '1 promo' : '0 promo' }}
                                </span>
                            </div>

                            <div class="space-y-[10px]">
                                @if($activePromo)
                                    <div class="rounded-[12px] bg-[#FFF7F8] border border-[#F1D9DD] px-[16px] py-[13px] flex items-center justify-between gap-[12px]">
                                        <button type="button"
                                                onclick="selectPromoById({{ $activePromo->layanan_cabang_id }}, true)"
                                                class="text-left flex-1">
                                            <p class="text-[14px] font-extrabold text-[#3F3838]">
                                                {{ $activePromo->judul_promo }}
                                            </p>

                                            <p class="text-[12px] font-bold text-[#7A6A63] mt-[3px]">
                                                {{ $activePromo->nama_layanan }} • {{ $activePromo->nama_jenis ?? '-' }} • {{ $branchButtonText }}
                                            </p>

                                            <p class="text-[13px] font-black text-[#B85C6A] mt-[4px]">
                                                Rp {{ number_format($activePromo->harga_promo, 0, ',', '.') }}
                                            </p>
                                        </button>

                                        <button type="button"
                                                onclick="openDeactivateModal()"
                                                class="rounded-full bg-[#A8BD8C] px-[13px] py-[7px] text-[11px] font-extrabold text-white hover:opacity-90 transition">
                                            Aktif
                                        </button>
                                    </div>
                                @else
                                    <div class="rounded-[12px] bg-[#FFF7F8] border border-[#F1D9DD] px-[16px] py-[13px] text-[13px] font-bold text-[#7A6A63]">
                                        Belum ada promo aktif pada cabang ini.
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>
</div>

<div id="deactivateModal" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <div class="w-full max-w-[430px] bg-white rounded-[18px] shadow-2xl p-[26px] text-center">
        <div class="mx-auto w-[70px] h-[70px] rounded-full bg-[#FFF0F2] flex items-center justify-center text-[#B85C6A] text-[38px] font-black mb-[16px]">
            !
        </div>

        <h2 class="text-[23px] font-extrabold text-[#3F3838] leading-tight">
            Nonaktifkan promo ini?
        </h2>

        <p class="mt-[12px] text-[14px] text-[#6F5E5E] leading-relaxed">
            Promo aktif pada cabang ini akan dinonaktifkan dan tidak tampil lagi sebagai promo aktif.
        </p>

        <form method="POST"
              action="{{ route('admin.inputpromo.deactivate') }}"
              class="mt-[24px] flex items-center justify-center gap-[12px]">
            @csrf
            @method('DELETE')

            <input type="hidden" name="cabang_id" value="{{ $selectedCabangId }}">

            <button type="button"
                    onclick="closeDeactivateModal()"
                    class="h-[40px] min-w-[120px] rounded-[8px] bg-[#EFE4E4] text-[#4B4242] font-extrabold">
                Batal
            </button>

            <button type="submit"
                    class="h-[40px] min-w-[120px] rounded-[8px] bg-[#B85C6A] text-white font-extrabold">
                Ya, Nonaktifkan
            </button>
        </form>
    </div>
</div>

<script>
    const services = @json($serviceOptions);
    const selectedServiceId = @json($selectedServiceId);
    const activePromoId = @json($activePromoId);
    const activePromoTitle = @json($activePromo->judul_promo ?? null);
    const activePromoDescription = @json($activePromo->deskripsi_promo ?? null);

    function formatRupiah(value) {
        const number = Number(value || 0);

        if (!number) {
            return '-';
        }

        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function toggleDropdown(id) {
        const target = document.getElementById(id);
        const dropdowns = ['branchDropdown'];

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

    function getServiceById(id) {
        return services.find(function (service) {
            return Number(service.layanan_cabang_id) === Number(id);
        });
    }

    function handleServiceChange() {
        const layananSelect = document.getElementById('layananSelect');
        const service = getServiceById(layananSelect.value);

        if (service) {
            document.getElementById('judulPromoInput').value = service.default_judul;
            document.getElementById('deskripsiPromoInput').value = service.default_deskripsi;
        }

        applyServiceToForm(service);
    }

    function selectPromoById(id, useActiveText = false) {
        document.getElementById('layananSelect').value = id;

        const service = getServiceById(id);

        if (service) {
            if (useActiveText && Number(id) === Number(activePromoId)) {
                document.getElementById('judulPromoInput').value = activePromoTitle || service.default_judul;
                document.getElementById('deskripsiPromoInput').value = activePromoDescription || service.default_deskripsi;
            } else {
                document.getElementById('judulPromoInput').value = service.default_judul;
                document.getElementById('deskripsiPromoInput').value = service.default_deskripsi;
            }
        }

        applyServiceToForm(service);
    }

    function updatePreviewText() {
        const judul = document.getElementById('judulPromoInput').value || 'Promo Belum Dipilih';
        const deskripsi = document.getElementById('deskripsiPromoInput').value || 'Pilih promo untuk melihat preview.';

        document.getElementById('previewJudul').textContent = judul;
        document.getElementById('previewDeskripsi').textContent = deskripsi;
    }

    function applyServiceToForm(service) {
        const activateBtn = document.getElementById('activateBtn');
        const warning = document.getElementById('promoWarning');

        if (!service) {
            document.getElementById('jenisInput').value = '';
            document.getElementById('hargaNormalInput').value = '';
            document.getElementById('hargaPromoInput').value = '';

            resetPreviewNoPromo();

            warning.classList.remove('hidden');
            activateBtn.disabled = true;
            activateBtn.classList.add('opacity-50', 'cursor-not-allowed');

            return;
        }

        document.getElementById('jenisInput').value = service.nama_jenis || '-';
        document.getElementById('hargaNormalInput').value = formatRupiah(service.harga);
        document.getElementById('hargaPromoInput').value = formatRupiah(service.harga_promo);

        updatePreviewText();

        document.getElementById('previewLayanan').textContent = service.nama_layanan || '-';
        document.getElementById('previewJenis').textContent = service.nama_jenis || '-';
        document.getElementById('previewCabang').textContent = service.nama_cabang || '-';
        document.getElementById('previewHargaNormal').textContent = formatRupiah(service.harga);
        document.getElementById('previewHargaPromo').textContent = formatRupiah(service.harga_promo);

        warning.classList.add('hidden');
        activateBtn.disabled = false;
        activateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    function resetPreviewNoPromo() {
        document.getElementById('previewJudul').textContent = 'Tidak Ada Promo Aktif';
        document.getElementById('previewDeskripsi').textContent = 'Pilih promo yang sudah memiliki harga promo di database.';
        document.getElementById('previewLayanan').textContent = '-';
        document.getElementById('previewJenis').textContent = '-';
        document.getElementById('previewCabang').textContent = '-';
        document.getElementById('previewHargaNormal').textContent = '-';
        document.getElementById('previewHargaPromo').textContent = '-';
    }

    function openDeactivateModal() {
        document.getElementById('deactivateModal').classList.remove('hidden');
        document.getElementById('deactivateModal').classList.add('flex');
    }

    function closeDeactivateModal() {
        document.getElementById('deactivateModal').classList.add('hidden');
        document.getElementById('deactivateModal').classList.remove('flex');
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (selectedServiceId) {
            document.getElementById('layananSelect').value = selectedServiceId;

            const service = getServiceById(selectedServiceId);

            if (service) {
                if (Number(selectedServiceId) === Number(activePromoId)) {
                    document.getElementById('judulPromoInput').value = activePromoTitle || service.default_judul;
                    document.getElementById('deskripsiPromoInput').value = activePromoDescription || service.default_deskripsi;
                }

                applyServiceToForm(service);
            }
        } else {
            resetPreviewNoPromo();
        }
    });

    document.addEventListener('click', function(event) {
        const insideDropdown = event.target.closest('#branchDropdown');
        const dropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');
        const deactivateModal = document.getElementById('deactivateModal');

        if (!insideDropdown && !dropdownButton) {
            document.getElementById('branchDropdown')?.classList.add('hidden');
        }

        if (event.target === deactivateModal) {
            closeDeactivateModal();
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