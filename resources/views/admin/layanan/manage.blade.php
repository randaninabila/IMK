<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Layanan - Dina Salon Muslimah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { margin: 0; overflow-x: hidden; }
        .soft-card   { box-shadow: 0 10px 24px rgba(58,55,46,.10); }
        .soft-shadow { box-shadow: 0  8px 18px rgba(58,55,46,.10); }
        .modal-bg    { background: rgba(0,0,0,.28); }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

@php $activeTab = session('active_tab', 'layanan'); @endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="ml-[235px] w-[calc(100%-235px)] min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        {{-- HEADER --}}
        <header class="h-[92px] px-[58px] flex items-center justify-between">
            <h2 class="text-[22px] font-extrabold text-[#3F3838] tracking-[-0.03em]">
                Halo, <span class="italic">Admin</span> Salon Dina Muslimah 👋
            </h2>
            <div class="relative flex items-center">
                @include('admin.partial.dropdownadmin')
            </div>
        </header>

        {{-- ALERTS --}}
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

        <section class="px-[42px] mt-[14px] pb-[60px]">

            {{-- MAIN WRAPPER --}}
            <div class="bg-[#FDE7EC] rounded-[18px] soft-card px-[26px] pt-[22px] pb-[36px]">

                {{-- TITLE ROW --}}
                <div class="flex items-center justify-between mb-[22px]">
                    <div>
                        <h1 class="text-[22px] font-extrabold text-[#3F3838]">Kelola Layanan</h1>
                        <p class="mt-[4px] text-[13px] font-semibold text-[#7B6A62]">Tambah, ubah, dan kelola layanan serta paket salon.</p>
                    </div>

                    {{-- TAB BUTTONS --}}
                    <div class="flex items-center gap-[8px]">
                        @foreach(['layanan' => 'Layanan', 'paket' => 'Paket', 'jenis' => 'Jenis Layanan'] as $tabKey => $tabLabel)
                            <button
                                onclick="switchTab('{{ $tabKey }}')"
                                id="tab-{{ $tabKey }}"
                                class="h-[34px] px-[16px] rounded-[8px] text-[13px] font-extrabold transition
                                    {{ $activeTab === $tabKey
                                        ? 'bg-[#E8A9B4] text-white'
                                        : 'bg-white text-[#4B3A36] hover:bg-[#FFE5E9]' }}"
                            >
                                {{ $tabLabel }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ====================================================== --}}
                {{-- TAB: LAYANAN --}}
                {{-- ====================================================== --}}
                <div id="panel-layanan" @class(['hidden' => $activeTab !== 'layanan'])>

                    {{-- TOOLBAR: search + tambah --}}
                    <div class="mb-[18px] bg-white rounded-[14px] px-[18px] py-[15px] soft-shadow">
                        <div class="flex items-center justify-between gap-[16px]">
                            <div>
                                <h3 class="text-[15px] font-extrabold text-[#3F3838]">Cari Layanan</h3>
                                <p class="mt-[3px] text-[12px] font-semibold text-[#8B7777]">Ketik nama layanan, jenis, atau kategori.</p>
                            </div>
                            <div class="flex items-center gap-[10px]">
                                <div class="relative w-[420px] max-w-full">
                                    <svg class="absolute left-[13px] top-1/2 -translate-y-1/2 w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none">
                                        <circle cx="11" cy="11" r="7" stroke="#9A7B7B" stroke-width="2"/>
                                        <path d="M16.5 16.5L21 21" stroke="#9A7B7B" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    <input
                                        type="text" id="search-layanan"
                                        placeholder="Cari berdasarkan nama layanan..."
                                        oninput="filterLayanan(this.value)"
                                        class="w-full h-[44px] rounded-[10px] border border-[#F1D9DD] bg-[#FFF8F9] pl-[38px] pr-[96px] text-[13px] font-semibold text-[#4B4242] outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                    >
                                    <button type="button" onclick="document.getElementById('search-layanan').value='';filterLayanan('')"
                                        class="absolute right-[8px] top-1/2 -translate-y-1/2 h-[30px] rounded-[8px] bg-[#EFE4E4] px-[12px] text-[12px] font-extrabold text-[#6B5A55] hover:bg-[#E8D1D5] transition">
                                        Reset
                                    </button>
                                </div>
                                <button type="button" onclick="openTambahLayananModal()"
                                    class="h-[44px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white rounded-[8px] px-[16px] flex items-center gap-[8px] text-[13px] font-extrabold transition whitespace-nowrap">
                                    Tambah Layanan <span class="text-[20px] leading-none">+</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- TABEL LAYANAN --}}
                    <div class="bg-white rounded-[12px] overflow-hidden soft-shadow">
                        <table class="w-full text-center">
                            <thead>
                                <tr class="h-[45px] text-[14px] font-extrabold text-[#4B4242] border-b border-[#F1C7CE] bg-[#FFF8F9]">
                                    <th class="text-left px-[16px]">Nama</th>
                                    <th>Jenis</th>
                                    <th>Durasi</th>
                                    <th>Kategori</th>
                                    <th class="text-left">Harga & Cabang</th>
                                    <th class="w-[120px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="layanan-tbody">
                                @forelse($layanan as $layananId => $rows)
                                    @php $first = $rows->first(); @endphp
                                    <tr
                                        class="layanan-row h-[58px] border-b border-[#F6E0E4] hover:bg-[#FFF8F9] transition"
                                        data-search="{{ strtolower($first->nama_layanan . ' ' . $first->nama_jenis . ' ' . $first->kategori_pelanggan) }}"
                                    >
                                        <td class="px-[16px] text-left">
                                            <div class="flex items-center gap-[10px]">
                                                @if($first->cover_foto)
                                                    <img src="{{ Storage::url($first->cover_foto) }}" class="w-[38px] h-[38px] rounded-[8px] object-cover shrink-0 border border-[#F1D9DD]" alt="">
                                                @else
                                                    <div class="w-[38px] h-[38px] rounded-[8px] bg-[#FFF0F2] flex items-center justify-center shrink-0 border border-[#F1D9DD]">
                                                        <span class="text-[#B85C6A] text-[12px]">📷</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-[13px] font-extrabold text-[#3F3838]">{{ $first->nama_layanan }}</div>
                                                    @if($first->deskripsi)
                                                        <div class="text-[11px] font-semibold text-[#9A7B7B]">{{ Str::limit($first->deskripsi, 40) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-[13px] font-semibold text-[#4B4242]">{{ $first->nama_jenis }}</td>
                                        <td class="text-[13px] font-semibold text-[#4B4242] whitespace-nowrap">{{ $first->durasi }} mnt</td>
                                        <td>
                                            <span class="bg-[#FFF0F2] text-[#B85C6A] px-[10px] py-[3px] rounded-full text-[11px] font-extrabold">
                                                {{ ucfirst($first->kategori_pelanggan) }}
                                            </span>
                                        </td>
                                        <td class="text-left">
                                            @foreach($rows as $row)
                                                @if($row->cabang_id)
                                                    <div class="text-[11px] font-semibold flex items-center gap-[4px]">
                                                        <span class="text-[#9A7B7B]">{{ Str::after($row->nama_cabang, '- ') }}:</span>
                                                        <span class="font-extrabold text-[#3F3838]">Rp {{ number_format($row->harga ?? 0, 0, ',', '.') }}</span>
                                                        @if($row->status_cabang === 'tidak_tersedia')
                                                            <span class="text-[#B85C6A]">(nonaktif)</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center gap-[6px]">
                                                <button
                                                    onclick="openEditLayananModal({{ json_encode([
                                                        'layanan_id'         => $first->layanan_id,
                                                        'nama_layanan'       => $first->nama_layanan,
                                                        'jenis_layanan_id'   => $first->jenis_layanan_id,
                                                        'deskripsi'          => $first->deskripsi,
                                                        'durasi'             => $first->durasi,
                                                        'kategori_pelanggan' => $first->kategori_pelanggan,
                                                        'harga'              => $rows->first(fn($r) => $r->harga !== null)?->harga,
                                                        'cover_foto'         => $first->cover_foto,
                                                    ]) }})"
                                                    class="w-[28px] h-[28px] rounded-[7px] bg-[#F6DFA8] flex items-center justify-center hover:opacity-80 transition"
                                                    title="Edit Layanan"
                                                >
                                                    <svg class="w-[14px] h-[14px]" viewBox="0 0 24 24" fill="none">
                                                        <path d="M4 20H8L19 9L15 5L4 16V20Z" stroke="#6B4D46" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>

                                                {{-- ALBUM FOTO --}}
                                                <a href="{{ route('admin.album', $first->layanan_id) }}"
                                                    class="w-[28px] h-[28px] rounded-[7px] bg-[#E8F0FF] flex items-center justify-center hover:opacity-80 transition"
                                                    title="Album Foto"
                                                >
                                                    <svg class="w-[14px] h-[14px]" viewBox="0 0 24 24" fill="none">
                                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                              stroke="#5B7EAB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>

                                                <button
                                                    onclick="openStatusModal({{ json_encode([
                                                        'layanan_id' => $first->layanan_id,
                                                        'nama'       => $first->nama_layanan,
                                                        'cabang'     => $rows->filter(fn($r) => $r->cabang_id !== null)->map(fn($r) => [
                                                            'cabang_id'   => $r->cabang_id,
                                                            'nama_cabang' => $r->nama_cabang,
                                                            'status'      => $r->status_cabang,
                                                        ])->values(),
                                                    ]) }})"
                                                    class="h-[28px] px-[10px] rounded-[7px] bg-[#FFF0F2] text-[#B85C6A] text-[11px] font-extrabold hover:bg-[#FFE5E9] transition"
                                                >
                                                    Status
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-[50px] text-center text-[13px] font-semibold text-[#8B7777]">Belum ada layanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- ====================================================== --}}
                {{-- TAB: PAKET --}}
                {{-- ====================================================== --}}
                <div id="panel-paket" @class(['hidden' => $activeTab !== 'paket'])>

                    <div class="mb-[18px] bg-white rounded-[14px] px-[18px] py-[15px] soft-shadow">
                        <div class="flex items-center justify-between gap-[16px]">
                            <div>
                                <h3 class="text-[15px] font-extrabold text-[#3F3838]">Cari Paket</h3>
                                <p class="mt-[3px] text-[12px] font-semibold text-[#8B7777]">Ketik nama paket atau layanan di dalamnya.</p>
                            </div>
                            <div class="flex items-center gap-[10px]">
                                <div class="relative w-[420px] max-w-full">
                                    <svg class="absolute left-[13px] top-1/2 -translate-y-1/2 w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none">
                                        <circle cx="11" cy="11" r="7" stroke="#9A7B7B" stroke-width="2"/>
                                        <path d="M16.5 16.5L21 21" stroke="#9A7B7B" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    <input
                                        type="text" id="search-paket"
                                        placeholder="Cari berdasarkan nama paket..."
                                        oninput="filterPaket(this.value)"
                                        class="w-full h-[44px] rounded-[10px] border border-[#F1D9DD] bg-[#FFF8F9] pl-[38px] pr-[96px] text-[13px] font-semibold text-[#4B4242] outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                                    >
                                    <button type="button" onclick="document.getElementById('search-paket').value='';filterPaket('')"
                                        class="absolute right-[8px] top-1/2 -translate-y-1/2 h-[30px] rounded-[8px] bg-[#EFE4E4] px-[12px] text-[12px] font-extrabold text-[#6B5A55] hover:bg-[#E8D1D5] transition">
                                        Reset
                                    </button>
                                </div>
                                <button type="button" onclick="openTambahPaketModal()"
                                    class="h-[44px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white rounded-[8px] px-[16px] flex items-center gap-[8px] text-[13px] font-extrabold transition whitespace-nowrap">
                                    Tambah Paket <span class="text-[20px] leading-none">+</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[12px] soft-shadow overflow-hidden">
                        <div class="px-[18px] py-[14px] border-b border-[#F1C7CE] bg-[#FFF8F9] flex items-center justify-between">
                            <span class="text-[14px] font-extrabold text-[#4B4242]">Daftar Paket</span>
                            <span class="bg-[#FFF0F2] text-[#B85C6A] px-[12px] py-[3px] rounded-full text-[12px] font-extrabold" id="paket-count">
                                {{ $paketLayanan->count() }} paket
                            </span>
                        </div>

                        <div class="divide-y divide-[#F6E0E4]" id="paket-list">
                            @forelse($paketLayanan as $paket)
                                <div
                                    class="paket-row px-[18px] py-[14px] hover:bg-[#FFF8F9] transition flex items-start justify-between gap-[12px]"
                                    data-search="{{ strtolower($paket->nama_paket . ' ' . ($paket->layanan_list ?? '') . ' ' . $paket->kategori_pelanggan) }}"
                                >
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-[8px] flex-wrap">
                                            <span class="text-[14px] font-extrabold text-[#3F3838]">{{ $paket->nama_paket }}</span>
                                            <span class="bg-[#FFF0F2] text-[#B85C6A] text-[11px] font-extrabold px-[8px] py-[2px] rounded-full">{{ ucfirst($paket->kategori_pelanggan) }}</span>
                                        </div>
                                        @if($paket->deskripsi)
                                            <p class="text-[12px] font-semibold text-[#9A7B7B] mt-[2px]">{{ $paket->deskripsi }}</p>
                                        @endif
                                        <p class="text-[13px] font-extrabold text-[#3F3838] mt-[4px]">
                                            Rp {{ number_format($paket->harga_normal ?? 0, 0, ',', '.') }}
                                            @if($paket->harga_promo)
                                                <span class="ml-[6px] text-[11px] text-green-600 font-semibold">promo Rp {{ number_format($paket->harga_promo, 0, ',', '.') }}</span>
                                            @endif
                                        </p>
                                        <div class="mt-[6px] flex flex-wrap gap-[4px]">
                                            @foreach(explode(', ', $paket->layanan_list ?? '') as $nl)
                                                @if(trim($nl))
                                                    <span class="bg-[#FFF0F2] text-[#B85C6A] text-[11px] font-semibold px-[8px] py-[2px] rounded-full">{{ trim($nl) }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <button
                                        onclick="openEditPaketModal({{ json_encode([
                                            'paket_id'           => $paket->paket_id,
                                            'nama_paket'         => $paket->nama_paket,
                                            'deskripsi'          => $paket->deskripsi,
                                            'kategori_pelanggan' => $paket->kategori_pelanggan,
                                            'harga_normal'       => $paket->harga_normal,
                                        ]) }})"
                                        class="shrink-0 w-[28px] h-[28px] rounded-[7px] bg-[#F6DFA8] flex items-center justify-center hover:opacity-80 transition"
                                    >
                                        <svg class="w-[14px] h-[14px]" viewBox="0 0 24 24" fill="none">
                                            <path d="M4 20H8L19 9L15 5L4 16V20Z" stroke="#6B4D46" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <div class="py-[50px] text-center text-[13px] font-semibold text-[#8B7777]">Belum ada paket.</div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- ====================================================== --}}
                {{-- TAB: JENIS LAYANAN --}}
                {{-- ====================================================== --}}
                <div id="panel-jenis" @class(['hidden' => $activeTab !== 'jenis'])>

                    <div class="mb-[18px] bg-white rounded-[14px] px-[18px] py-[15px] soft-shadow flex items-center justify-between">
                        <div>
                            <h3 class="text-[15px] font-extrabold text-[#3F3838]">Jenis Layanan</h3>
                            <p class="mt-[3px] text-[12px] font-semibold text-[#8B7777]">Kategori pengelompokan layanan.</p>
                        </div>
                        <button type="button" onclick="openTambahJenisModal()"
                            class="h-[44px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white rounded-[8px] px-[16px] flex items-center gap-[8px] text-[13px] font-extrabold transition whitespace-nowrap">
                            Tambah Jenis <span class="text-[20px] leading-none">+</span>
                        </button>
                    </div>

                    <div class="bg-white rounded-[12px] soft-shadow overflow-hidden">
                        <div class="px-[18px] py-[14px] border-b border-[#F1C7CE] bg-[#FFF8F9] flex items-center justify-between">
                            <span class="text-[14px] font-extrabold text-[#4B4242]">Daftar Jenis Layanan</span>
                            <span class="bg-[#FFF0F2] text-[#B85C6A] px-[12px] py-[3px] rounded-full text-[12px] font-extrabold">
                                {{ $jenisLayanan->count() }} jenis
                            </span>
                        </div>
                        <div class="divide-y divide-[#F6E0E4]">
                            @forelse($jenisLayanan as $jenis)
                                <div class="px-[18px] py-[14px] hover:bg-[#FFF8F9] transition flex items-center justify-between gap-[12px]">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-[13px] font-extrabold text-[#3F3838]">{{ $jenis->nama_jenis }}</div>
                                        @if($jenis->deskripsi)
                                            <div class="text-[12px] font-semibold text-[#9A7B7B] mt-[2px]">{{ $jenis->deskripsi }}</div>
                                        @endif
                                    </div>
                                    <button
                                        type="button"
                                        onclick="openEditJenisModal({{ json_encode([
                                            'jenis_layanan_id' => $jenis->jenis_layanan_id,
                                            'nama_jenis'       => $jenis->nama_jenis,
                                            'deskripsi'        => $jenis->deskripsi,
                                        ]) }})"
                                        class="shrink-0 w-[28px] h-[28px] rounded-[7px] bg-[#F6DFA8] flex items-center justify-center hover:opacity-80 transition"
                                    >
                                        <svg class="w-[14px] h-[14px]" viewBox="0 0 24 24" fill="none">
                                            <path d="M4 20H8L19 9L15 5L4 16V20Z" stroke="#6B4D46" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <div class="py-[40px] text-center text-[13px] font-semibold text-[#8B7777]">Belum ada jenis layanan.</div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </main>
</div>


{{-- ================================================================ --}}
{{-- MODAL TAMBAH LAYANAN --}}
{{-- ================================================================ --}}
<div id="modal-tambah-layanan" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <form
        action="{{ route('admin.layanan.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="w-full max-w-[580px] bg-white rounded-[18px] shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
    >
        @csrf
        <input type="hidden" name="active_tab" value="layanan">

        <div class="px-[24px] py-[18px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <h2 class="text-[20px] font-extrabold text-[#4B3A36]">Tambah Layanan</h2>
            <button type="button" onclick="closeTambahLayananModal()" class="w-[34px] h-[34px] rounded-full bg-[#4B3A36] text-white text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <div class="px-[24px] py-[20px] space-y-[13px]">

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">Nama Layanan</label>
                    <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                </div>
                <div>
                    <label class="text-[13px] font-extrabold">Jenis Layanan</label>
                    <select name="jenis_layanan_id"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                        @foreach($jenisLayanan as $jenis)
                            <option value="{{ $jenis->jenis_layanan_id }}" {{ old('jenis_layanan_id') == $jenis->jenis_layanan_id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Deskripsi</label>
                <textarea name="deskripsi" rows="2"
                    class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[13px] py-[10px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4] resize-none">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">Durasi (menit)</label>
                    <input type="number" name="durasi" min="1" value="{{ old('durasi') }}"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                </div>
                <div>
                    <label class="text-[13px] font-extrabold">Harga (Rp)</label>
                    <div class="relative mt-[6px]">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-[#9A7B7B]">Rp</span>
                        <input type="text" inputmode="numeric" id="display-harga-tambah" placeholder="0"
                            class="w-full h-[42px] rounded-[8px] bg-[#FFF0F2] pl-8 pr-3 text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                            oninput="formatHarga(this,'harga-tambah')" required>
                        <input type="hidden" name="harga" id="harga-tambah" value="{{ old('harga') }}">
                    </div>
                </div>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Kategori Pelanggan</label>
                <select name="kategori_pelanggan"
                    class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                    <option value="umum" {{ old('kategori_pelanggan') == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="anak" {{ old('kategori_pelanggan') == 'anak' ? 'selected' : '' }}>Anak</option>
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Foto Cover <span class="font-semibold text-[#9A7B7B]">(opsional)</span></label>
                <input type="file" name="cover_foto" accept="image/jpeg,image/png,image/webp"
                    class="mt-[6px] w-full text-[12px] text-[#7B6A62]
                           file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0
                           file:text-[12px] file:font-extrabold file:bg-[#FFF0F2] file:text-[#B85C6A]
                           hover:file:bg-[#FFE5E9] transition">
                <p class="text-[11px] text-[#9A7B7B] mt-[3px]">JPG, PNG, WebP. Maks 2MB.</p>
            </div>

        </div>

        <div class="px-[24px] pb-[22px] flex justify-end gap-[10px]">
            <button type="button" onclick="closeTambahLayananModal()"
                class="h-[42px] px-[18px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] text-[13px] font-extrabold hover:bg-[#E8D1D5] transition">
                Batal
            </button>
            <button type="submit"
                class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white text-[13px] font-extrabold transition">
                Simpan Layanan
            </button>
        </div>
    </form>
</div>


{{-- ================================================================ --}}
{{-- MODAL EDIT LAYANAN --}}
{{-- ================================================================ --}}
<div id="modal-edit-layanan" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <form id="form-edit-layanan" method="POST" enctype="multipart/form-data"
        class="w-full max-w-[580px] bg-white rounded-[18px] shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        @csrf @method('PUT')
        <input type="hidden" name="active_tab" value="layanan">

        <div class="px-[24px] py-[18px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <h2 class="text-[20px] font-extrabold text-[#4B3A36]">Edit Layanan</h2>
            <button type="button" onclick="closeEditLayananModal()" class="w-[34px] h-[34px] rounded-full bg-[#4B3A36] text-white text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <div class="px-[24px] py-[20px] space-y-[13px]">

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">Nama Layanan</label>
                    <input type="text" name="nama_layanan" id="edit-nama"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                </div>
                <div>
                    <label class="text-[13px] font-extrabold">Jenis Layanan</label>
                    <select name="jenis_layanan_id" id="edit-jenis"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                        @foreach($jenisLayanan as $jenis)
                            <option value="{{ $jenis->jenis_layanan_id }}">{{ $jenis->nama_jenis }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Deskripsi</label>
                <textarea name="deskripsi" id="edit-deskripsi" rows="2"
                    class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[13px] py-[10px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4] resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">Durasi (menit)</label>
                    <input type="number" name="durasi" id="edit-durasi" min="1"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                </div>
                <div>
                    <label class="text-[13px] font-extrabold">Harga (Rp)</label>
                    <div class="relative mt-[6px]">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-[#9A7B7B]">Rp</span>
                        <input type="text" inputmode="numeric" id="display-edit-harga" placeholder="0"
                            class="w-full h-[42px] rounded-[8px] bg-[#FFF0F2] pl-8 pr-3 text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                            oninput="formatHarga(this,'edit-harga')" required>
                        <input type="hidden" name="harga" id="edit-harga">
                    </div>
                </div>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Kategori Pelanggan</label>
                <select name="kategori_pelanggan" id="edit-kategori"
                    class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                    <option value="umum">Umum</option>
                    <option value="anak">Anak</option>
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Foto Cover <span class="font-semibold text-[#9A7B7B]">(kosongkan jika tidak diubah)</span></label>
                <div id="edit-foto-preview" class="mt-[6px] mb-[6px]"></div>
                <input type="file" name="cover_foto" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-[12px] text-[#7B6A62]
                           file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0
                           file:text-[12px] file:font-extrabold file:bg-[#FFF0F2] file:text-[#B85C6A]
                           hover:file:bg-[#FFE5E9] transition">
                <p class="text-[11px] text-[#9A7B7B] mt-[3px]">Update harga berlaku untuk semua cabang.</p>
            </div>

        </div>

        <div class="px-[24px] pb-[22px] flex justify-end gap-[10px]">
            <button type="button" onclick="closeEditLayananModal()"
                class="h-[42px] px-[18px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] text-[13px] font-extrabold hover:bg-[#E8D1D5] transition">
                Batal
            </button>
            <button type="submit"
                class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white text-[13px] font-extrabold transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>


{{-- ================================================================ --}}
{{-- MODAL STATUS CABANG --}}
{{-- ================================================================ --}}
<div id="modal-status" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <div class="w-full max-w-[400px] bg-white rounded-[18px] shadow-2xl overflow-hidden">

        <div class="px-[24px] py-[18px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <div>
                <h2 class="text-[18px] font-extrabold text-[#4B3A36]">Ubah Status Cabang</h2>
                <p id="modal-status-nama" class="text-[12px] font-semibold text-[#9A7B7B] mt-[2px]"></p>
            </div>
            <button type="button" onclick="closeStatusModal()" class="w-[34px] h-[34px] rounded-full bg-[#4B3A36] text-white text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <div class="px-[24px] py-[20px]">
            <p class="text-[13px] font-extrabold text-[#4B3A36] mb-[12px]">Pilih cabang yang ingin diubah:</p>
            <div id="modal-status-cabang" class="space-y-[10px] mb-[18px]"></div>

            <div class="flex gap-[10px]">
                <form id="form-nonaktif" method="POST" class="flex-1">
                    @csrf @method('PATCH')
                    <input type="hidden" name="active_tab" value="layanan">
                    <div id="hidden-nonaktif"></div>
                    <button type="submit" onclick="syncHidden('hidden-nonaktif')"
                        class="w-full h-[42px] rounded-[8px] bg-[#FFE5E9] text-[#B85C6A] text-[13px] font-extrabold hover:bg-[#F8C2CA] transition">
                        Nonaktifkan
                    </button>
                </form>
                <form id="form-aktif" method="POST" class="flex-1">
                    @csrf @method('PATCH')
                    <input type="hidden" name="active_tab" value="layanan">
                    <div id="hidden-aktif"></div>
                    <button type="submit" onclick="syncHidden('hidden-aktif')"
                        class="w-full h-[42px] rounded-[8px] bg-[#EEF7E6] text-[#7E9D62] text-[13px] font-extrabold hover:opacity-80 transition">
                        Aktifkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ================================================================ --}}
{{-- MODAL TAMBAH PAKET --}}
{{-- ================================================================ --}}
<div id="modal-tambah-paket" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <form action="{{ route('admin.layanan.paket.store') }}" method="POST"
        class="w-full max-w-[560px] bg-white rounded-[18px] shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        @csrf
        <input type="hidden" name="active_tab" value="paket">

        <div class="px-[24px] py-[18px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <h2 class="text-[20px] font-extrabold text-[#4B3A36]">Tambah Paket</h2>
            <button type="button" onclick="closeTambahPaketModal()" class="w-[34px] h-[34px] rounded-full bg-[#4B3A36] text-white text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <div class="px-[24px] py-[20px] space-y-[13px]">

            <div>
                <label class="text-[13px] font-extrabold">Nama Paket</label>
                <input type="text" name="nama_paket" value="{{ old('nama_paket') }}"
                    class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Deskripsi</label>
                <textarea name="deskripsi" rows="2"
                    class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[13px] py-[10px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4] resize-none">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">Kategori</label>
                    <select name="kategori_pelanggan"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                        <option value="umum">Umum</option>
                        <option value="anak">Anak</option>
                    </select>
                </div>
                <div>
                    <label class="text-[13px] font-extrabold">Harga Normal (Rp)</label>
                    <div class="relative mt-[6px]">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-[#9A7B7B]">Rp</span>
                        <input type="text" inputmode="numeric" id="display-harga-paket" placeholder="0"
                            class="w-full h-[42px] rounded-[8px] bg-[#FFF0F2] pl-8 pr-3 text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                            oninput="formatHarga(this,'harga-paket')" required>
                        <input type="hidden" name="harga_normal" id="harga-paket" value="{{ old('harga_normal') }}">
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-[8px]">
                    <label class="text-[13px] font-extrabold">Pilih Layanan</label>
                    <span class="text-[12px] font-semibold text-[#9A7B7B]" id="paket-layanan-count">0 dipilih</span>
                </div>
                <div class="relative mb-[6px]">
                    <svg class="absolute left-[10px] top-1/2 -translate-y-1/2 w-[13px] h-[13px] pointer-events-none" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="7" stroke="#9A7B7B" stroke-width="2"/>
                        <path d="M16.5 16.5L21 21" stroke="#9A7B7B" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input type="text" id="search-pilih-layanan" placeholder="Cari layanan..."
                        oninput="filterPilihLayanan(this.value)"
                        class="w-full h-[36px] rounded-[8px] bg-[#FFF8F9] border border-[#F1D9DD] pl-[28px] pr-[10px] text-[12px] font-semibold outline-none focus:ring-2 focus:ring-[#E8A9B4]">
                </div>
                <div class="space-y-[6px] max-h-[160px] overflow-y-auto pr-1" id="pilih-layanan-list">
                    @forelse($layananAktif as $lAktif)
                        <label class="pilih-layanan-item flex items-center gap-[8px] cursor-pointer"
                            data-search="{{ strtolower($lAktif->nama_layanan . ' ' . $lAktif->kategori_pelanggan) }}">
                            <input type="checkbox" name="layanan_id[]" value="{{ $lAktif->layanan_id }}"
                                class="pilih-layanan-cb w-4 h-4 accent-[#E8A9B4]"
                                onchange="updatePaketLayananCount()"
                                {{ is_array(old('layanan_id')) && in_array($lAktif->layanan_id, old('layanan_id')) ? 'checked' : '' }}>
                            <span class="text-[13px] font-semibold text-[#4B3A36]">
                                {{ $lAktif->nama_layanan }}
                                <span class="text-[11px] text-[#9A7B7B]">({{ $lAktif->kategori_pelanggan }})</span>
                            </span>
                        </label>
                    @empty
                        <p class="text-[12px] text-[#9A7B7B] text-center py-[12px]">Belum ada layanan aktif.</p>
                    @endforelse
                </div>
                <p id="pilih-layanan-empty" class="hidden text-[11px] text-[#9A7B7B] mt-[6px] text-center">Tidak ada layanan yang cocok.</p>
            </div>

        </div>

        <div class="px-[24px] pb-[22px] flex justify-end gap-[10px]">
            <button type="button" onclick="closeTambahPaketModal()"
                class="h-[42px] px-[18px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] text-[13px] font-extrabold hover:bg-[#E8D1D5] transition">
                Batal
            </button>
            <button type="submit"
                class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white text-[13px] font-extrabold transition">
                Simpan Paket
            </button>
        </div>
    </form>
</div>


{{-- ================================================================ --}}
{{-- MODAL EDIT PAKET --}}
{{-- ================================================================ --}}
<div id="modal-edit-paket" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <form id="form-edit-paket" method="POST"
        class="w-full max-w-[480px] bg-white rounded-[18px] shadow-2xl overflow-hidden">
        @csrf @method('PUT')
        <input type="hidden" name="active_tab" value="paket">

        <div class="px-[24px] py-[18px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <h2 class="text-[20px] font-extrabold text-[#4B3A36]">Edit Paket</h2>
            <button type="button" onclick="closeEditPaketModal()" class="w-[34px] h-[34px] rounded-full bg-[#4B3A36] text-white text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <div class="px-[24px] py-[20px] space-y-[13px]">

            <div>
                <label class="text-[13px] font-extrabold">Nama Paket</label>
                <input type="text" name="nama_paket" id="edit-paket-nama"
                    class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Deskripsi</label>
                <textarea name="deskripsi" id="edit-paket-deskripsi" rows="2"
                    class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[13px] py-[10px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4] resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">Kategori</label>
                    <select name="kategori_pelanggan" id="edit-paket-kategori"
                        class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                        <option value="umum">Umum</option>
                        <option value="anak">Anak</option>
                    </select>
                </div>
                <div>
                    <label class="text-[13px] font-extrabold">Harga Normal (Rp)</label>
                    <div class="relative mt-[6px]">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12px] text-[#9A7B7B]">Rp</span>
                        <input type="text" inputmode="numeric" id="display-edit-harga-paket" placeholder="0"
                            class="w-full h-[42px] rounded-[8px] bg-[#FFF0F2] pl-8 pr-3 text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]"
                            oninput="formatHarga(this,'edit-harga-paket')" required>
                        <input type="hidden" name="harga_normal" id="edit-harga-paket">
                    </div>
                    <p class="text-[11px] text-[#9A7B7B] mt-[3px]">Berlaku untuk semua cabang.</p>
                </div>
            </div>

        </div>

        <div class="px-[24px] pb-[22px] flex justify-end gap-[10px]">
            <button type="button" onclick="closeEditPaketModal()"
                class="h-[42px] px-[18px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] text-[13px] font-extrabold hover:bg-[#E8D1D5] transition">
                Batal
            </button>
            <button type="submit"
                class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white text-[13px] font-extrabold transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>


{{-- ================================================================ --}}
{{-- MODAL TAMBAH JENIS --}}
{{-- ================================================================ --}}
<div id="modal-tambah-jenis" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <form action="{{ route('admin.layanan.jenis.store') }}" method="POST"
        class="w-full max-w-[460px] bg-white rounded-[18px] shadow-2xl p-[24px]">
        @csrf
        <input type="hidden" name="active_tab" value="jenis">

        <div class="flex items-center justify-between mb-[18px]">
            <h2 class="text-[20px] font-extrabold text-[#3F3838]">Tambah Jenis Layanan</h2>
            <button type="button" onclick="closeTambahJenisModal()" class="w-[32px] h-[32px] bg-[#3F372E] text-white rounded-full text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <div class="space-y-[13px]">
            <div>
                <label class="text-[13px] font-extrabold">Nama Jenis</label>
                <input type="text" name="nama_jenis" value="{{ old('nama_jenis') }}"
                    class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
            </div>
            <div>
                <label class="text-[13px] font-extrabold">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[13px] py-[10px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4] resize-none">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <button type="submit"
            class="mt-[22px] w-full h-[44px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white text-[13px] font-extrabold transition">
            Simpan Jenis
        </button>
    </form>
</div>


{{-- ================================================================ --}}
{{-- MODAL EDIT JENIS LAYANAN --}}
{{-- ================================================================ --}}
<div id="modal-edit-jenis" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <form id="form-edit-jenis" method="POST"
        class="w-full max-w-[460px] bg-white rounded-[18px] shadow-2xl p-[24px]">
        @csrf @method('PUT')
        <input type="hidden" name="active_tab" value="jenis">

        <div class="flex items-center justify-between mb-[18px]">
            <h2 class="text-[20px] font-extrabold text-[#3F3838]">Edit Jenis Layanan</h2>
            <button type="button" onclick="closeEditJenisModal()" class="w-[32px] h-[32px] bg-[#3F372E] text-white rounded-full text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <div class="space-y-[13px]">
            <div>
                <label class="text-[13px] font-extrabold">Nama Jenis</label>
                <input type="text" name="nama_jenis" id="edit-jenis-nama"
                    class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
            </div>
            <div>
                <label class="text-[13px] font-extrabold">Deskripsi</label>
                <textarea name="deskripsi" id="edit-jenis-deskripsi" rows="3"
                    class="mt-[6px] w-full rounded-[8px] bg-[#FFF0F2] px-[13px] py-[10px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4] resize-none"></textarea>
            </div>
        </div>

        <button type="submit"
            class="mt-[22px] w-full h-[44px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white text-[13px] font-extrabold transition">
            Simpan Perubahan
        </button>
    </form>
</div>


<script>
    // ── FORMAT HARGA ──────────────────────────────────────────────
    function formatHarga(displayInput, hiddenId) {
        var raw = displayInput.value.replace(/\D/g, '');
        displayInput.value = raw === '' ? '' : parseInt(raw, 10).toLocaleString('id-ID');
        document.getElementById(hiddenId).value = raw === '' ? '' : raw;
    }

    function setHargaDisplay(displayId, hiddenId, value) {
        var num = parseFloat(value);
        document.getElementById(hiddenId).value  = isNaN(num) ? '' : Math.round(num);
        document.getElementById(displayId).value = isNaN(num) ? '' : Math.round(num).toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', function () {
        setHargaDisplay('display-harga-tambah', 'harga-tambah', document.getElementById('harga-tambah').value);
        setHargaDisplay('display-harga-paket',  'harga-paket',  document.getElementById('harga-paket').value);
        updatePaketLayananCount();
    });

    // ── TAB ───────────────────────────────────────────────────────
    function switchTab(tab) {
        ['layanan', 'paket', 'jenis'].forEach(function (t) {
            document.getElementById('panel-' + t).classList.toggle('hidden', t !== tab);
            var btn = document.getElementById('tab-' + t);
            if (t === tab) {
                btn.classList.add('bg-[#E8A9B4]', 'text-white');
                btn.classList.remove('bg-white', 'text-[#4B3A36]');
            } else {
                btn.classList.remove('bg-[#E8A9B4]', 'text-white');
                btn.classList.add('bg-white', 'text-[#4B3A36]');
            }
        });
    }

    // ── MODAL HELPERS ─────────────────────────────────────────────
    function openModal(id)  { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden');    document.getElementById(id).classList.remove('flex'); }

    // Tambah Layanan
    function openTambahLayananModal()  { openModal('modal-tambah-layanan'); }
    function closeTambahLayananModal() { closeModal('modal-tambah-layanan'); }

    // Edit Layanan
    function openEditLayananModal(data) {
        document.getElementById('form-edit-layanan').action = '/admin/layanan/' + data.layanan_id;
        document.getElementById('edit-nama').value      = data.nama_layanan       ?? '';
        document.getElementById('edit-deskripsi').value = data.deskripsi          ?? '';
        document.getElementById('edit-durasi').value    = data.durasi             ?? '';
        document.getElementById('edit-jenis').value     = data.jenis_layanan_id   ?? '';
        document.getElementById('edit-kategori').value  = data.kategori_pelanggan ?? 'umum';
        setHargaDisplay('display-edit-harga', 'edit-harga', data.harga ?? '');

        var preview = document.getElementById('edit-foto-preview');
        preview.innerHTML = data.cover_foto
            ? '<div class="flex items-center gap-[10px] p-[8px] bg-[#FFF0F2] rounded-[10px]">'
                + '<img src="/storage/' + data.cover_foto + '" class="w-[40px] h-[40px] object-cover rounded-[8px] border border-[#F1D9DD]">'
                + '<span class="text-[12px] font-semibold text-[#7B6A62]">Foto saat ini</span></div>'
            : '<p class="text-[12px] font-semibold text-[#9A7B7B]">Belum ada foto.</p>';

        openModal('modal-edit-layanan');
    }
    function closeEditLayananModal() { closeModal('modal-edit-layanan'); }

    // Tambah Paket
    function openTambahPaketModal()  { openModal('modal-tambah-paket'); }
    function closeTambahPaketModal() { closeModal('modal-tambah-paket'); }

    // Edit Paket
    function openEditPaketModal(data) {
        document.getElementById('form-edit-paket').action = '/admin/layanan/paket/' + data.paket_id;
        document.getElementById('edit-paket-nama').value      = data.nama_paket        ?? '';
        document.getElementById('edit-paket-deskripsi').value = data.deskripsi         ?? '';
        document.getElementById('edit-paket-kategori').value  = data.kategori_pelanggan ?? 'umum';
        setHargaDisplay('display-edit-harga-paket', 'edit-harga-paket', data.harga_normal ?? '');
        openModal('modal-edit-paket');
    }
    function closeEditPaketModal() { closeModal('modal-edit-paket'); }

    // Tambah Jenis
    function openTambahJenisModal()  { openModal('modal-tambah-jenis'); }
    function closeTambahJenisModal() { closeModal('modal-tambah-jenis'); }

    // Edit Jenis
    function openEditJenisModal(data) {
        document.getElementById('form-edit-jenis').action = '/admin/layanan/jenis/' + data.jenis_layanan_id;
        document.getElementById('edit-jenis-nama').value      = data.nama_jenis ?? '';
        document.getElementById('edit-jenis-deskripsi').value = data.deskripsi  ?? '';
        openModal('modal-edit-jenis');
    }
    function closeEditJenisModal() { closeModal('modal-edit-jenis'); }

    // ── MODAL STATUS CABANG ───────────────────────────────────────
    function openStatusModal(data) {
        document.getElementById('modal-status-nama').textContent = data.nama;

        document.getElementById('modal-status-cabang').innerHTML = data.cabang.map(function (c) {
            var shortName = c.nama_cabang.replace('Salon Muslimah Dina - ', '');
            var badge = c.status === 'tersedia'
                ? '<span class="text-[11px] font-extrabold bg-[#EEF7E6] text-[#7E9D62] px-[8px] py-[2px] rounded-full">Aktif</span>'
                : '<span class="text-[11px] font-extrabold bg-[#FFE5E9] text-[#B85C6A] px-[8px] py-[2px] rounded-full">Nonaktif</span>';
            return '<label class="flex items-center justify-between cursor-pointer">'
                + '<span class="flex items-center gap-[8px]">'
                + '<input type="checkbox" class="status-cb w-4 h-4 accent-[#E8A9B4]" value="' + c.cabang_id + '" checked>'
                + '<span class="text-[13px] font-semibold text-[#4B3A36]">' + shortName + '</span>'
                + '</span>' + badge + '</label>';
        }).join('');

        document.getElementById('form-nonaktif').action = '/admin/layanan/' + data.layanan_id + '/deactivate';
        document.getElementById('form-aktif').action    = '/admin/layanan/' + data.layanan_id + '/activate';

        openModal('modal-status');
    }
    function closeStatusModal() { closeModal('modal-status'); }

    function syncHidden(containerId) {
        var container = document.getElementById(containerId);
        container.innerHTML = '';
        document.querySelectorAll('.status-cb:checked').forEach(function (cb) {
            var input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'cabang_id[]';
            input.value = cb.value;
            container.appendChild(input);
        });
    }

    // Tutup modal saat klik backdrop
    ['modal-tambah-layanan','modal-edit-layanan','modal-status',
     'modal-tambah-paket','modal-edit-paket','modal-tambah-jenis','modal-edit-jenis'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function (e) { if (e.target === this) closeModal(id); });
    });

    // ── SEARCH: TABEL LAYANAN ─────────────────────────────────────
    function filterLayanan(q) {
        var rows = document.querySelectorAll('.layanan-row');
        var term = q.trim().toLowerCase();
        var visible = 0;

        rows.forEach(function (row) {
            var match = !term || row.dataset.search.includes(term);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        var empty = document.getElementById('layanan-empty-row');
        if (!empty) {
            empty = document.createElement('tr');
            empty.id = 'layanan-empty-row';
            empty.innerHTML = '<td colspan="6" class="py-[40px] text-center text-[13px] font-semibold text-[#8B7777]">Tidak ada layanan yang cocok.</td>';
            document.getElementById('layanan-tbody').appendChild(empty);
        }
        empty.style.display = visible === 0 ? '' : 'none';
    }

    // ── SEARCH: DAFTAR PAKET ──────────────────────────────────────
    function filterPaket(q) {
        var cards = document.querySelectorAll('.paket-row');
        var term  = q.trim().toLowerCase();
        var visible = 0;

        cards.forEach(function (card) {
            var match = !term || card.dataset.search.includes(term);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        var counter = document.getElementById('paket-count');
        if (counter) counter.textContent = visible + ' paket';
    }

    // ── SEARCH + COUNTER: PILIH LAYANAN ──────────────────────────
    function filterPilihLayanan(q) {
        var items = document.querySelectorAll('.pilih-layanan-item');
        var term  = q.trim().toLowerCase();
        var visible = 0;

        items.forEach(function (item) {
            var match = !term || item.dataset.search.includes(term);
            item.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        var empty = document.getElementById('pilih-layanan-empty');
        if (empty) empty.classList.toggle('hidden', visible > 0);
    }

    function updatePaketLayananCount() {
        var checked = document.querySelectorAll('.pilih-layanan-cb:checked').length;
        var el = document.getElementById('paket-layanan-count');
        if (el) el.textContent = checked + ' dipilih';
    }
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