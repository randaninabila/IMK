<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ulasan & Saran - Dina Salon Muslimah</title>

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

        .modal-bg {
            background: rgba(0, 0, 0, 0.30);
        }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

@php
    $branches = $branches ?? collect();
    $selectedCabangId = $selectedCabangId ?? null;
    $selectedBranch = $selectedBranch ?? null;
    $status = $status ?? 'pending';
    $search = $search ?? '';
    $counts = $counts ?? [
        'pending' => 0,
        'approved' => 0,
        'rejected' => 0,
        'all' => 0,
    ];
    $reviews = $reviews ?? collect();

    $branchButtonText = $selectedBranch
        ? ($selectedBranch->label ?? $selectedBranch->nama_cabang)
        : 'Semua Cabang';

    $statusLabels = [
        'pending' => 'Menunggu',
        'approved' => 'Diterima',
        'rejected' => 'Ditolak',
        'all' => 'Semua',
    ];

    $statusClasses = [
        'pending' => 'bg-[#FFF4D5] text-[#7A6335]',
        'approved' => 'bg-[#EEF7E6] text-[#7E9D62]',
        'rejected' => 'bg-[#F8D7DD] text-[#B85C6A]',
    ];
@endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="ml-[235px] w-[calc(100%-235px)] min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        <header class="h-[92px] px-[58px] flex items-center justify-between">

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
                        <a href="{{ route('admin.ulasan-saran', ['status' => $status, 'search' => $search]) }}"
                           class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ !$selectedCabangId ? 'bg-[#FFF0F2]' : '' }}">
                            Semua Cabang
                        </a>

                        @forelse($branches as $branch)
                            <a href="{{ route('admin.ulasan-saran', ['cabang_id' => $branch->cabang_id, 'status' => $status, 'search' => $search]) }}"
                               class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ (int) $selectedCabangId === (int) $branch->cabang_id ? 'bg-[#FFF0F2]' : '' }}">
                                {{ $branch->label }}
                            </a>
                        @empty
                            <div class="px-4 py-3 text-sm font-bold text-[#8B7777]">
                                Belum ada cabang
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="relative ml-[45px]">
                    <button type="button"
                            onclick="toggleDropdown('profileDropdown')"
                            class="flex items-center gap-[18px]">
                        <div class="w-[58px] h-[58px] bg-white rounded-full flex items-center justify-center text-[25px] shadow-sm">
                            👩🏻‍💼
                        </div>

                        <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="#4B3A36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div id="profileDropdown"
                         class="hidden absolute right-0 top-[64px] w-[190px] bg-white rounded-[12px] shadow-xl border border-[#F1D9DD] overflow-hidden z-50">
                        <a href="{{ url('/admin/pengaturan') }}"
                           class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36]">
                            Profile Admin
                        </a>

                        <a href="{{ url('/admin/pengaturan') }}"
                           class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36]">
                            Pengaturan
                        </a>

                        <a href="{{ url('/') }}"
                           class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#B85C6A]">
                            Keluar
                        </a>
                    </div>
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

            <div class="bg-[#FDE7EC] rounded-[18px] soft-card min-h-[820px] px-[28px] pt-[24px] pb-[36px]">

                <div class="flex items-start justify-between gap-[20px] mb-[24px]">
                    <div>
                        <h1 class="text-[30px] font-extrabold text-[#3F3838]">
                            Ulasan & Saran
                        </h1>

                        <p class="mt-[6px] text-[14px] font-semibold text-[#7A6A63]">
                            Kelola ulasan pelanggan sebelum ditampilkan pada halaman testimoni.
                        </p>
                    </div>

                    <button type="button"
                            onclick="openGuideModal()"
                            class="rounded-full bg-[#E8A9B4] px-[18px] py-[9px] text-[13px] font-extrabold text-white hover:bg-[#D995A1] transition">
                        Moderasi Ulasan
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-[18px] mb-[24px]">
                    <div class="bg-white rounded-[16px] px-[22px] py-[18px] shadow-sm border border-[#F1D9DD]">
                        <p class="text-[13px] font-extrabold text-[#8A7A74]">Menunggu Review</p>
                        <p class="mt-[5px] text-[34px] font-black text-[#D995A1]">{{ $counts['pending'] ?? 0 }}</p>
                    </div>

                    <div class="bg-white rounded-[16px] px-[22px] py-[18px] shadow-sm border border-[#F1D9DD]">
                        <p class="text-[13px] font-extrabold text-[#8A7A74]">Diterima</p>
                        <p class="mt-[5px] text-[34px] font-black text-[#7E9D62]">{{ $counts['approved'] ?? 0 }}</p>
                    </div>

                    <div class="bg-white rounded-[16px] px-[22px] py-[18px] shadow-sm border border-[#F1D9DD]">
                        <p class="text-[13px] font-extrabold text-[#8A7A74]">Ditolak</p>
                        <p class="mt-[5px] text-[34px] font-black text-[#B85C6A]">{{ $counts['rejected'] ?? 0 }}</p>
                    </div>

                    <div class="bg-white rounded-[16px] px-[22px] py-[18px] shadow-sm border border-[#F1D9DD]">
                        <p class="text-[13px] font-extrabold text-[#8A7A74]">Total Ulasan</p>
                        <p class="mt-[5px] text-[34px] font-black text-[#4B3A36]">{{ $counts['all'] ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-[18px] px-[24px] py-[22px] shadow-sm border border-[#F1D9DD] mb-[22px]">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-[18px]">

                        <div class="flex flex-wrap items-center gap-[10px]">
                            @foreach(['pending' => 'Menunggu', 'approved' => 'Diterima', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $key => $label)
                                <a href="{{ route('admin.ulasan-saran', ['status' => $key, 'search' => $search, 'cabang_id' => $selectedCabangId]) }}"
                                   class="rounded-full px-[18px] py-[9px] text-[13px] font-extrabold transition
                                   {{ $status === $key ? 'bg-[#E8A9B4] text-white' : 'bg-[#FFF0F2] text-[#6B5A55] hover:bg-[#FDE7EC]' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>

                        <form method="GET" action="{{ route('admin.ulasan-saran') }}" class="flex gap-[10px]">
                            <input type="hidden" name="status" value="{{ $status }}">

                            @if($selectedCabangId)
                                <input type="hidden" name="cabang_id" value="{{ $selectedCabangId }}">
                            @endif

                            <input
                                name="search"
                                value="{{ $search }}"
                                type="text"
                                placeholder="Cari nama, layanan, atau komentar..."
                                class="h-[42px] w-full lg:w-[330px] rounded-[10px] bg-[#FFF0F2] px-[15px] text-[14px] font-semibold outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                            >

                            <button type="submit"
                                    class="h-[42px] rounded-[10px] bg-[#3F372E] px-[16px] text-[13px] font-extrabold text-white">
                                Cari
                            </button>
                        </form>
                    </div>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-[18px]">
                    @forelse($reviews as $review)
                        @php
                            $fotoList = $review->foto_urls ? array_filter(explode('|||', $review->foto_urls)) : [];
                            $firstPhoto = $fotoList[0] ?? null;
                            $statusClass = $statusClasses[$review->status_moderasi] ?? 'bg-[#FFF4D5] text-[#7A6335]';
                            $statusLabel = $statusLabels[$review->status_moderasi] ?? 'Menunggu';
                            $rating = (int) ($review->rating ?? 0);
                        @endphp

                        <div class="bg-white rounded-[18px] border border-[#F1D9DD] px-[22px] py-[22px] soft-shadow">

                            @if($firstPhoto)
                                <img src="{{ asset($firstPhoto) }}"
                                     alt="Foto Ulasan"
                                     class="h-[160px] w-full rounded-[16px] object-cover mb-[16px]">
                            @endif

                            <div class="flex items-start justify-between gap-[15px] mb-[14px]">
                                <div>
                                    <h3 class="text-[20px] font-extrabold text-[#3F3838]">
                                        {{ $review->pelanggan_nama ?? 'Pelanggan' }}
                                    </h3>

                                    <p class="mt-[4px] text-[12px] font-bold text-[#8A7A74]">
                                        {{ \Carbon\Carbon::parse($review->created_at)->locale('id')->translatedFormat('d F Y') }}
                                    </p>

                                    <p class="mt-[6px] text-[12px] font-bold text-[#7A6A63]">
                                        {{ $review->layanan_nama ?? 'Layanan tidak tercatat' }}
                                    </p>
                                </div>

                                <span class="rounded-full px-[12px] py-[6px] text-[12px] font-extrabold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="mb-[12px] text-[18px] tracking-[2px]">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $rating ? 'text-[#D995A1]' : 'text-[#D9C7C7]' }}">★</span>
                                @endfor
                            </div>

                            <p class="min-h-[80px] text-[15px] leading-[1.45] font-semibold text-[#6B5A55]">
                                {{ $review->komentar ?? '-' }}
                            </p>

                            <div class="mt-[18px] flex flex-wrap items-center gap-[10px]">
                                <button type="button"
                                        onclick="openModerationModal(this)"
                                        data-id="{{ $review->ulasan_id }}"
                                        data-name="{{ e($review->pelanggan_nama ?? 'Pelanggan') }}"
                                        data-email="{{ e($review->pelanggan_email ?? '-') }}"
                                        data-phone="{{ e($review->pelanggan_no_hp ?? '-') }}"
                                        data-service="{{ e($review->layanan_nama ?? '-') }}"
                                        data-rating="{{ $review->rating ?? 0 }}"
                                        data-comment="{{ e($review->komentar ?? '-') }}"
                                        data-status="{{ $review->status_moderasi }}"
                                        data-photo="{{ $firstPhoto ? asset($firstPhoto) : '' }}"
                                        class="rounded-[8px] bg-[#E8A9B4] px-[16px] py-[10px] text-[13px] font-extrabold text-white hover:bg-[#D995A1] transition">
                                    Moderasi
                                </button>

                                @if($review->status_moderasi !== 'approved')
                                    <form method="POST" action="{{ route('admin.ulasan-saran.status', $review->ulasan_id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit"
                                                class="rounded-[8px] bg-[#A8BD8C] px-[16px] py-[10px] text-[13px] font-extrabold text-white hover:opacity-80 transition">
                                            Terima
                                        </button>
                                    </form>
                                @endif

                                @if($review->status_moderasi !== 'rejected')
                                    <form method="POST" action="{{ route('admin.ulasan-saran.status', $review->ulasan_id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit"
                                                class="rounded-[8px] bg-[#B85C6A] px-[16px] py-[10px] text-[13px] font-extrabold text-white hover:opacity-80 transition">
                                            Tolak
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="lg:col-span-2 bg-white rounded-[18px] px-[28px] py-[50px] text-center border border-[#F1D9DD] soft-shadow">
                            <p class="text-[22px] font-extrabold text-[#3F3838]">
                                Belum ada ulasan pada kategori ini.
                            </p>
                            <p class="mt-[8px] text-[14px] font-semibold text-[#8A7A74]">
                                Ulasan dari pelanggan akan muncul di sini setelah pelanggan mengirim form ulasan.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if(method_exists($reviews, 'links'))
                    <div class="mt-[20px]">
                        {{ $reviews->links() }}
                    </div>
                @endif

            </div>

        </section>

    </main>
</div>

<div id="guideModal" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <div class="w-full max-w-[520px] bg-white rounded-[18px] shadow-2xl p-[26px]">
        <div class="flex items-start justify-between gap-[18px]">
            <div>
                <h2 class="text-[24px] font-extrabold text-[#3F3838]">
                    Moderasi Ulasan
                </h2>

                <p class="mt-[8px] text-[14px] font-semibold text-[#7A6A63] leading-relaxed">
                    Ulasan dengan status <b>Diterima</b> bisa ditampilkan pada halaman testimoni.
                    Ulasan yang <b>Ditolak</b> tidak akan tampil ke pelanggan.
                </p>
            </div>

            <button type="button"
                    onclick="closeGuideModal()"
                    class="w-[34px] h-[34px] rounded-full bg-[#3F372E] text-white text-[22px] leading-none">
                ×
            </button>
        </div>

        <div class="mt-[20px] grid grid-cols-3 gap-[10px]">
            <div class="rounded-[14px] bg-[#FFF4D5] px-[14px] py-[14px]">
                <p class="text-[12px] font-extrabold text-[#7A6335]">Menunggu</p>
                <p class="mt-[5px] text-[26px] font-black text-[#7A6335]">{{ $counts['pending'] ?? 0 }}</p>
            </div>

            <div class="rounded-[14px] bg-[#EEF7E6] px-[14px] py-[14px]">
                <p class="text-[12px] font-extrabold text-[#7E9D62]">Diterima</p>
                <p class="mt-[5px] text-[26px] font-black text-[#7E9D62]">{{ $counts['approved'] ?? 0 }}</p>
            </div>

            <div class="rounded-[14px] bg-[#F8D7DD] px-[14px] py-[14px]">
                <p class="text-[12px] font-extrabold text-[#B85C6A]">Ditolak</p>
                <p class="mt-[5px] text-[26px] font-black text-[#B85C6A]">{{ $counts['rejected'] ?? 0 }}</p>
            </div>
        </div>

        <button type="button"
                onclick="closeGuideModal()"
                class="mt-[22px] w-full h-[42px] rounded-[10px] bg-[#E8A9B4] text-white font-extrabold hover:bg-[#D995A1] transition">
            Mengerti
        </button>
    </div>
</div>

<div id="moderationModal" class="hidden fixed inset-0 z-[1000] modal-bg items-center justify-center px-6">
    <form id="moderationForm"
          method="POST"
          action="#"
          class="w-full max-w-[640px] bg-white rounded-[18px] shadow-2xl overflow-hidden">
        @csrf
        @method('PUT')

        <div class="px-[26px] py-[20px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <div>
                <h2 class="text-[24px] font-extrabold text-[#4B3A36]">
                    Detail Moderasi Ulasan
                </h2>
                <p class="text-[13px] font-semibold text-[#7B6A62] mt-[4px]">
                    Periksa isi ulasan sebelum ditampilkan ke halaman testimoni.
                </p>
            </div>

            <button type="button"
                    onclick="closeModerationModal()"
                    class="w-[38px] h-[38px] rounded-full bg-[#4B3A36] text-white text-[26px] leading-none flex items-center justify-center">
                ×
            </button>
        </div>

        <div class="px-[26px] py-[22px]">
            <img id="modalPhoto"
                 src=""
                 alt="Foto Ulasan"
                 class="hidden h-[180px] w-full rounded-[16px] object-cover mb-[18px]">

            <div class="grid grid-cols-2 gap-[14px]">
                <div class="rounded-[12px] bg-[#FFF7F8] px-[14px] py-[12px]">
                    <p class="text-[12px] font-extrabold text-[#8A7A74]">Nama Pelanggan</p>
                    <p id="modalName" class="mt-[4px] text-[15px] font-extrabold text-[#3F3838]">-</p>
                </div>

                <div class="rounded-[12px] bg-[#FFF7F8] px-[14px] py-[12px]">
                    <p class="text-[12px] font-extrabold text-[#8A7A74]">Layanan</p>
                    <p id="modalService" class="mt-[4px] text-[15px] font-extrabold text-[#3F3838]">-</p>
                </div>

                <div class="rounded-[12px] bg-[#FFF7F8] px-[14px] py-[12px]">
                    <p class="text-[12px] font-extrabold text-[#8A7A74]">Email</p>
                    <p id="modalEmail" class="mt-[4px] text-[13px] font-bold text-[#3F3838]">-</p>
                </div>

                <div class="rounded-[12px] bg-[#FFF7F8] px-[14px] py-[12px]">
                    <p class="text-[12px] font-extrabold text-[#8A7A74]">No. HP</p>
                    <p id="modalPhone" class="mt-[4px] text-[13px] font-bold text-[#3F3838]">-</p>
                </div>
            </div>

            <div class="mt-[14px] rounded-[12px] bg-[#FFF7F8] px-[14px] py-[12px]">
                <p class="text-[12px] font-extrabold text-[#8A7A74]">Rating</p>
                <p id="modalRating" class="mt-[4px] text-[20px] tracking-[2px] text-[#D995A1]">-</p>
            </div>

            <div class="mt-[14px] rounded-[12px] bg-[#FFF7F8] px-[14px] py-[12px]">
                <p class="text-[12px] font-extrabold text-[#8A7A74]">Komentar</p>
                <p id="modalComment" class="mt-[4px] text-[14px] font-semibold leading-relaxed text-[#4B4242]">-</p>
            </div>

            <div class="mt-[16px]">
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Status Moderasi</label>
                <select id="modalStatus"
                        name="status"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[12px] outline-none font-semibold"
                        required>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Diterima / Tampilkan di Testimoni</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="px-[26px] pb-[24px] flex justify-end gap-[12px]">
            <button type="button"
                    onclick="closeModerationModal()"
                    class="h-[42px] px-[20px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] font-extrabold">
                Batal
            </button>

            <button type="submit"
                    class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] text-white font-extrabold hover:bg-[#D995A1] transition">
                Simpan Moderasi
            </button>
        </div>
    </form>
</div>

<script>
    function toggleDropdown(id) {
        const target = document.getElementById(id);
        const dropdowns = ['branchDropdown', 'dateDropdown', 'profileDropdown'];

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

    function selectDate(date, day) {
        document.getElementById('dateText').innerHTML = date + '<br>' + day;
        document.getElementById('dateDropdown').classList.add('hidden');
    }

    function openGuideModal() {
        const modal = document.getElementById('guideModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeGuideModal() {
        const modal = document.getElementById('guideModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openModerationModal(button) {
        const reviewId = button.dataset.id;
        const photo = button.dataset.photo || '';
        const rating = Number(button.dataset.rating || 0);

        document.getElementById('moderationForm').action = "{{ url('/admin/ulasan-saran') }}/" + reviewId + "/status";

        document.getElementById('modalName').textContent = button.dataset.name || '-';
        document.getElementById('modalEmail').textContent = button.dataset.email || '-';
        document.getElementById('modalPhone').textContent = button.dataset.phone || '-';
        document.getElementById('modalService').textContent = button.dataset.service || '-';
        document.getElementById('modalComment').textContent = button.dataset.comment || '-';
        document.getElementById('modalStatus').value = button.dataset.status || 'pending';

        let stars = '';

        for (let i = 1; i <= 5; i++) {
            stars += i <= rating ? '★' : '☆';
        }

        document.getElementById('modalRating').textContent = stars;

        const modalPhoto = document.getElementById('modalPhoto');

        if (photo) {
            modalPhoto.src = photo;
            modalPhoto.classList.remove('hidden');
        } else {
            modalPhoto.src = '';
            modalPhoto.classList.add('hidden');
        }

        const modal = document.getElementById('moderationModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModerationModal() {
        const modal = document.getElementById('moderationModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('click', function(event) {
        const insideDropdown = event.target.closest('#branchDropdown, #dateDropdown, #profileDropdown');
        const dropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');

        if (!insideDropdown && !dropdownButton) {
            document.getElementById('branchDropdown')?.classList.add('hidden');
            document.getElementById('dateDropdown')?.classList.add('hidden');
            document.getElementById('profileDropdown')?.classList.add('hidden');
        }

        if (event.target.id === 'guideModal') {
            closeGuideModal();
        }

        if (event.target.id === 'moderationModal') {
            closeModerationModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeGuideModal();
            closeModerationModal();
        }
    });
</script>

</body>
</html>