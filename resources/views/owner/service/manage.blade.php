@extends('owner.app')

@section('content')

@php $activeTab = session('active_tab', 'layanan'); @endphp

<div class="py-6">

    {{-- KEMBALI + HEADER --}}
    <div class="mb-8">
        <a
            href="{{ route('owner.service.edit') }}"
            class="inline-flex items-center gap-2 bg-white border border-[#f1dede] px-5 py-2.5 rounded-full text-sm font-medium text-[#b04a4a] shadow-sm hover:bg-pink-50 transition mb-6"
        >
            ← Kembali ke Daftar Layanan
        </a>
        <h1 class="text-4xl font-bold text-[#2d2a26]">Kelola Layanan</h1>
        <p class="text-gray-500 mt-2">Tambah, ubah, dan kelola layanan salon.</p>
    </div>

    {{-- TAB NAVIGATION --}}
    <div class="flex gap-2 mb-6 border-b border-[#ecd9d9]">
        @foreach(['layanan' => 'Layanan', 'paket' => 'Paket', 'jenis' => 'Jenis Layanan'] as $tabKey => $tabLabel)
            <button
                onclick="switchTab('{{ $tabKey }}')"
                id="tab-{{ $tabKey }}"
                @class([
                    'tab-btn px-5 py-2.5 text-sm font-medium rounded-t-xl border-b-2 transition',
                    'border-[#f45b69] text-[#f45b69]'           => $activeTab === $tabKey,
                    'border-transparent text-gray-500 hover:text-[#b04a4a]' => $activeTab !== $tabKey,
                ])
            >
                {{ $tabLabel }}
            </button>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- TAB: LAYANAN --}}
    {{-- ============================================================ --}}
    <div id="panel-layanan" @class(['hidden' => $activeTab !== 'layanan'])>
        <div class="grid lg:grid-cols-12 gap-6">

            {{-- FORM TAMBAH LAYANAN --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-50 sticky top-6">

                    <h2 class="text-lg font-semibold mb-5 text-[#2d2a26]">Tambah Layanan</h2>

                    <form
                        action="{{ route('owner.service.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @csrf
                        <input type="hidden" name="active_tab" value="layanan">

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Nama Layanan</label>
                            <input
                                type="text" name="nama_layanan"
                                value="{{ old('nama_layanan') }}"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Jenis Layanan</label>
                            <select
                                name="jenis_layanan_id"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                required
                            >
                                @foreach($jenisLayanan as $jenis)
                                    <option
                                        value="{{ $jenis->jenis_layanan_id }}"
                                        {{ old('jenis_layanan_id') == $jenis->jenis_layanan_id ? 'selected' : '' }}
                                    >
                                        {{ $jenis->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Deskripsi</label>
                            <textarea
                                name="deskripsi" rows="3"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                            >{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Durasi (menit)</label>
                                <input
                                    type="number" name="durasi" min="1"
                                    value="{{ old('durasi') }}"
                                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                    required
                                >
                            </div>
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Harga (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                    <input
                                        type="text" inputmode="numeric"
                                        id="display-harga-tambah"
                                        placeholder="0"
                                        class="w-full border border-[#ecd9d9] rounded-2xl pl-9 pr-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                        oninput="formatHarga(this, 'harga-tambah')"
                                        required
                                    >
                                    <input type="hidden" name="harga" id="harga-tambah" value="{{ old('harga') }}">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Kategori Pelanggan</label>
                            <select
                                name="kategori_pelanggan"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                required
                            >
                                <option value="umum" {{ old('kategori_pelanggan') == 'umum' ? 'selected' : '' }}>Umum</option>
                                <option value="anak" {{ old('kategori_pelanggan') == 'anak' ? 'selected' : '' }}>Anak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">
                                Foto Cover
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input
                                type="file"
                                name="cover_foto"
                                accept="image/jpeg,image/png,image/webp"
                                class="w-full text-sm text-gray-500
                                       file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0
                                       file:text-sm file:font-medium file:bg-pink-50 file:text-[#b04a4a]
                                       hover:file:bg-pink-100 transition"
                            >
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP. Maks 2MB.</p>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#f45b69] text-white py-2.5 rounded-full text-sm font-medium shadow-sm hover:opacity-90 transition"
                        >
                            Tambah Layanan
                        </button>

                    </form>
                </div>
            </div>

            {{-- TABEL LAYANAN --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-50">

                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-[#2d2a26]">Daftar Layanan</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Klik "Status" untuk mengaktifkan atau menonaktifkan per cabang.</p>
                        </div>
                        <span class="bg-[#fff4f4] text-[#b04a4a] px-3 py-1 rounded-full text-xs font-medium" id="layanan-count">
                            {{ $layanan->count() }} layanan
                        </span>
                    </div>

                    {{-- SEARCH LAYANAN --}}
                    <div class="relative mb-4">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none">🔍</span>
                        <input
                            type="text"
                            id="search-layanan"
                            placeholder="Cari nama layanan, jenis, atau kategori..."
                            oninput="filterLayanan(this.value)"
                            class="w-full border border-[#ecd9d9] rounded-2xl pl-10 pr-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                        >
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-sm">
                            <thead class="text-[#b04a4a] border-b border-[#ecd9d9]">
                                <tr>
                                    <th class="text-left py-3 px-3">Nama</th>
                                    <th class="text-left py-3 px-3">Jenis</th>
                                    <th class="text-left py-3 px-3">Durasi</th>
                                    <th class="text-left py-3 px-3">Kategori</th>
                                    <th class="text-left py-3 px-3">Harga & Cabang</th>
                                    <th class="text-center py-3 px-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="layanan-tbody">
                                @forelse($layanan as $layananId => $rows)
                                    @php $first = $rows->first(); @endphp
                                    <tr
                                        class="layanan-row border-b border-[#f3e6e6] hover:bg-[#fff8f8] transition"
                                        data-search="{{ strtolower($first->nama_layanan . ' ' . $first->nama_jenis . ' ' . $first->kategori_pelanggan) }}"
                                    >
                                        <td class="py-3 px-3">
                                            <div class="flex items-center gap-3">
                                                @if($first->cover_foto)
                                                    <img
                                                        src="{{ Storage::url($first->cover_foto) }}"
                                                        class="w-10 h-10 rounded-xl object-cover shrink-0 border border-[#ecd9d9]"
                                                        alt="{{ $first->nama_layanan }}"
                                                    >
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center shrink-0 border border-[#ecd9d9]">
                                                        <span class="text-[#b04a4a] text-xs">📷</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-medium text-[#2d2a26]">{{ $first->nama_layanan }}</div>
                                                    @if($first->deskripsi)
                                                        <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($first->deskripsi, 40) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-3 px-3 text-gray-600">{{ $first->nama_jenis }}</td>

                                        <td class="py-3 px-3 text-gray-600 whitespace-nowrap">{{ $first->durasi }} menit</td>

                                        <td class="py-3 px-3">
                                            <span class="bg-pink-50 text-[#b04a4a] px-2 py-0.5 rounded-full text-xs whitespace-nowrap">
                                                {{ ucfirst($first->kategori_pelanggan) }}
                                            </span>
                                        </td>

                                        <td class="py-3 px-3">
                                            @foreach($rows as $row)
                                                @if($row->cabang_id)
                                                    <div class="text-xs mb-0.5 flex items-center gap-1 flex-wrap">
                                                        <span class="text-gray-400">{{ Str::after($row->nama_cabang, '- ') }}</span>:
                                                        <span class="font-medium text-[#2d2a26]">Rp {{ number_format($row->harga ?? 0, 0, ',', '.') }}</span>
                                                        @if($row->status_cabang === 'tidak_tersedia')
                                                            <span class="text-red-400">(nonaktif)</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>

                                        <td class="py-3 px-3">
                                            <div class="flex justify-center gap-2 flex-wrap">

                                                {{-- EDIT --}}
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
                                                    class="px-3 py-1.5 rounded-full text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                                                >
                                                    Edit
                                                </button>

                                                {{-- STATUS --}}
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
                                                    class="px-3 py-1.5 rounded-full text-xs bg-orange-50 text-orange-600 hover:bg-orange-100 transition"
                                                >
                                                    Status
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-16 text-center text-gray-400">Belum ada layanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TAB: PAKET --}}
    {{-- ============================================================ --}}
    <div id="panel-paket" @class(['hidden' => $activeTab !== 'paket'])>
        <div class="grid lg:grid-cols-12 gap-6">

            {{-- FORM TAMBAH PAKET --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-50 sticky top-6">

                    <h2 class="text-lg font-semibold mb-5 text-[#2d2a26]">Tambah Paket</h2>

                    <form action="{{ route('owner.service.paket.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="active_tab" value="paket">

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Nama Paket</label>
                            <input
                                type="text" name="nama_paket"
                                value="{{ old('nama_paket') }}"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Deskripsi</label>
                            <textarea
                                name="deskripsi" rows="3"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                            >{{ old('deskripsi') }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Kategori Pelanggan</label>
                            <select
                                name="kategori_pelanggan"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                required
                            >
                                <option value="umum">Umum</option>
                                <option value="anak">Anak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Harga Normal (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                <input
                                    type="text" inputmode="numeric"
                                    id="display-harga-paket"
                                    placeholder="0"
                                    class="w-full border border-[#ecd9d9] rounded-2xl pl-9 pr-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                    oninput="formatHarga(this, 'harga-paket')"
                                    required
                                >
                                <input type="hidden" name="harga_normal" id="harga-paket" value="{{ old('harga_normal') }}">
                            </div>
                        </div>

                        {{-- PILIH LAYANAN dengan search + counter --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-[#2d2a26]">Pilih Layanan</label>
                                <span class="text-xs text-gray-400" id="paket-layanan-count">0 dipilih</span>
                            </div>
                            <div class="relative mb-2">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">🔍</span>
                                <input
                                    type="text"
                                    id="search-pilih-layanan"
                                    placeholder="Cari layanan..."
                                    oninput="filterPilihLayanan(this.value)"
                                    class="w-full border border-[#ecd9d9] rounded-xl pl-8 pr-3 py-2 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                >
                            </div>
                            <div class="space-y-2 max-h-52 overflow-y-auto pr-1" id="pilih-layanan-list">
                                @forelse($layananAktif as $lAktif)
                                    <label
                                        class="pilih-layanan-item flex items-center gap-3 cursor-pointer"
                                        data-search="{{ strtolower($lAktif->nama_layanan . ' ' . $lAktif->kategori_pelanggan) }}"
                                    >
                                        <input
                                            type="checkbox"
                                            name="layanan_id[]"
                                            value="{{ $lAktif->layanan_id }}"
                                            class="pilih-layanan-cb w-4 h-4 accent-[#f45b69]"
                                            onchange="updatePaketLayananCount()"
                                            {{ is_array(old('layanan_id')) && in_array($lAktif->layanan_id, old('layanan_id')) ? 'checked' : '' }}
                                        >
                                        <span class="text-sm text-[#2d2a26]">
                                            {{ $lAktif->nama_layanan }}
                                            <span class="text-xs text-gray-400">({{ $lAktif->kategori_pelanggan }})</span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-400 text-center py-4">Belum ada layanan aktif.</p>
                                @endforelse
                            </div>
                            <p id="pilih-layanan-empty" class="hidden text-xs text-gray-400 mt-2 text-center">Tidak ada layanan yang cocok.</p>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#f45b69] text-white py-2.5 rounded-full text-sm font-medium shadow-sm hover:opacity-90 transition"
                        >
                            Tambah Paket
                        </button>

                    </form>
                </div>
            </div>

            {{-- DAFTAR PAKET --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-50">

                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-[#2d2a26]">Daftar Paket</h2>
                        <span class="bg-[#fff4f4] text-[#b04a4a] px-3 py-1 rounded-full text-xs font-medium" id="paket-count">
                            {{ $paketLayanan->count() }} paket
                        </span>
                    </div>

                    {{-- SEARCH PAKET --}}
                    <div class="relative mb-4">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none">🔍</span>
                        <input
                            type="text"
                            id="search-paket"
                            placeholder="Cari nama paket atau layanan di dalamnya..."
                            oninput="filterPaket(this.value)"
                            class="w-full border border-[#ecd9d9] rounded-2xl pl-10 pr-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                        >
                    </div>

                    <div class="space-y-3" id="paket-list">
                        @forelse($paketLayanan as $paket)
                            <div
                                class="paket-row border border-[#ecd9d9] rounded-2xl p-4 hover:bg-[#fff8f8] transition"
                                data-search="{{ strtolower($paket->nama_paket . ' ' . ($paket->layanan_list ?? '') . ' ' . $paket->kategori_pelanggan) }}"
                            >
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-medium text-[#2d2a26]">{{ $paket->nama_paket }}</span>
                                            <span class="bg-pink-50 text-[#b04a4a] text-xs px-2 py-0.5 rounded-full">
                                                {{ ucfirst($paket->kategori_pelanggan) }}
                                            </span>
                                        </div>
                                        @if($paket->deskripsi)
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $paket->deskripsi }}</div>
                                        @endif
                                        <div class="mt-1 text-sm font-medium text-[#2d2a26]">
                                            Rp {{ number_format($paket->harga_normal ?? 0, 0, ',', '.') }}
                                            @if($paket->harga_promo)
                                                <span class="ml-1 text-xs text-green-600 font-normal">
                                                    promo Rp {{ number_format($paket->harga_promo, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach(explode(', ', $paket->layanan_list ?? '') as $namaLayanan)
                                                @if(trim($namaLayanan))
                                                    <span class="bg-pink-50 text-[#b04a4a] text-xs px-2 py-0.5 rounded-full">
                                                        {{ trim($namaLayanan) }}
                                                    </span>
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
                                        class="shrink-0 px-3 py-1.5 rounded-full text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                                    >
                                        Edit
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center text-gray-400">Belum ada paket.</div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TAB: JENIS LAYANAN --}}
    {{-- ============================================================ --}}
    <div id="panel-jenis" @class(['hidden' => $activeTab !== 'jenis'])>
        <div class="grid lg:grid-cols-12 gap-6">

            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-50">

                    <h2 class="text-lg font-semibold mb-5 text-[#2d2a26]">Tambah Jenis Layanan</h2>

                    <form action="{{ route('owner.service.jenis.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="active_tab" value="jenis">

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Nama Jenis</label>
                            <input
                                type="text" name="nama_jenis"
                                value="{{ old('nama_jenis') }}"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Deskripsi</label>
                            <textarea
                                name="deskripsi" rows="3"
                                class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                            >{{ old('deskripsi') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#f45b69] text-white py-2.5 rounded-full text-sm font-medium shadow-sm hover:opacity-90 transition"
                        >
                            Tambah Jenis
                        </button>

                    </form>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-50">

                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold text-[#2d2a26]">Daftar Jenis Layanan</h2>
                        <span class="bg-[#fff4f4] text-[#b04a4a] px-3 py-1 rounded-full text-xs font-medium">
                            {{ $jenisLayanan->count() }} jenis
                        </span>
                    </div>

                    <div class="space-y-2">
                        @forelse($jenisLayanan as $jenis)
                            <div class="border border-[#ecd9d9] rounded-2xl px-4 py-3 hover:bg-[#fff8f8] transition flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-[#2d2a26] text-sm">{{ $jenis->nama_jenis }}</div>
                                    @if($jenis->deskripsi)
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $jenis->deskripsi }}</div>
                                    @endif
                                </div>
                                <button
                                    type="button"
                                    onclick="openEditJenisModal({{ json_encode([
                                        'jenis_layanan_id' => $jenis->jenis_layanan_id,
                                        'nama_jenis'       => $jenis->nama_jenis,
                                        'deskripsi'        => $jenis->deskripsi,
                                    ]) }})"
                                    class="shrink-0 px-3 py-1.5 rounded-full text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                                >
                                    Edit
                                </button>
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-400">Belum ada jenis layanan.</div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>


{{-- ============================================================ --}}
{{-- MODAL EDIT LAYANAN --}}
{{-- ============================================================ --}}
<div id="modal-edit-layanan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">

        <button
            onclick="closeEditLayananModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl leading-none"
        >&times;</button>

        <h2 class="text-lg font-semibold text-[#2d2a26] mb-5">Edit Layanan</h2>

        <form id="form-edit-layanan" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="active_tab" value="layanan">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Nama Layanan</label>
                <input
                    type="text" name="nama_layanan" id="edit-nama"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                    required
                >
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Jenis Layanan</label>
                <select
                    name="jenis_layanan_id" id="edit-jenis"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                    required
                >
                    @foreach($jenisLayanan as $jenis)
                        <option value="{{ $jenis->jenis_layanan_id }}">{{ $jenis->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Deskripsi</label>
                <textarea
                    name="deskripsi" id="edit-deskripsi" rows="2"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Durasi (menit)</label>
                    <input
                        type="number" name="durasi" id="edit-durasi" min="1"
                        class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                        required
                    >
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Harga (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                        <input
                            type="text" inputmode="numeric"
                            id="display-edit-harga"
                            placeholder="0"
                            class="w-full border border-[#ecd9d9] rounded-2xl pl-9 pr-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                            oninput="formatHarga(this, 'edit-harga')"
                            required
                        >
                        <input type="hidden" name="harga" id="edit-harga">
                    </div>
                </div>
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Kategori Pelanggan</label>
                <select
                    name="kategori_pelanggan" id="edit-kategori"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                    required
                >
                    <option value="umum">Umum</option>
                    <option value="anak">Anak</option>
                </select>
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">
                    Foto Cover
                    <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                </label>
                <div id="edit-foto-preview" class="mb-2"></div>
                <input
                    type="file"
                    name="cover_foto"
                    accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500
                           file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0
                           file:text-sm file:font-medium file:bg-pink-50 file:text-[#b04a4a]
                           hover:file:bg-pink-100 transition"
                >
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP. Maks 2MB.</p>
            </div>

            <p class="text-xs text-gray-400">Update harga berlaku untuk semua cabang.</p>

            <button
                type="submit"
                class="w-full bg-[#f45b69] text-white py-2.5 rounded-full text-sm font-medium shadow-sm hover:opacity-90 transition"
            >
                Simpan Perubahan
            </button>

        </form>
    </div>
</div>


{{-- ============================================================ --}}
{{-- MODAL EDIT PAKET --}}
{{-- ============================================================ --}}
<div id="modal-edit-paket" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-6 relative">

        <button
            onclick="closeEditPaketModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl leading-none"
        >&times;</button>

        <h2 class="text-lg font-semibold text-[#2d2a26] mb-5">Edit Paket</h2>

        <form id="form-edit-paket" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="active_tab" value="paket">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Nama Paket</label>
                <input
                    type="text" name="nama_paket" id="edit-paket-nama"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                    required
                >
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Deskripsi</label>
                <textarea
                    name="deskripsi" id="edit-paket-deskripsi" rows="2"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                ></textarea>
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Kategori Pelanggan</label>
                <select
                    name="kategori_pelanggan" id="edit-paket-kategori"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                    required
                >
                    <option value="umum">Umum</option>
                    <option value="anak">Anak</option>
                </select>
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Harga Normal (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                    <input
                        type="text" inputmode="numeric"
                        id="display-edit-harga-paket"
                        placeholder="0"
                        class="w-full border border-[#ecd9d9] rounded-2xl pl-9 pr-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                        oninput="formatHarga(this, 'edit-harga-paket')"
                        required
                    >
                    <input type="hidden" name="harga_normal" id="edit-harga-paket">
                </div>
                <p class="text-xs text-gray-400 mt-1">Update harga berlaku untuk semua cabang.</p>
            </div>

            <button
                type="submit"
                class="w-full bg-[#f45b69] text-white py-2.5 rounded-full text-sm font-medium shadow-sm hover:opacity-90 transition"
            >
                Simpan Perubahan
            </button>

        </form>
    </div>
</div>


{{-- ============================================================ --}}
{{-- MODAL EDIT JENIS LAYANAN --}}
{{-- ============================================================ --}}
<div id="modal-edit-jenis" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-sm p-6 relative">

        <button
            onclick="closeEditJenisModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl leading-none"
        >&times;</button>

        <h2 class="text-lg font-semibold text-[#2d2a26] mb-5">Edit Jenis Layanan</h2>

        <form id="form-edit-jenis" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="active_tab" value="jenis">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Nama Jenis</label>
                <input
                    type="text" name="nama_jenis" id="edit-jenis-nama-owner"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                    required
                >
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-[#2d2a26]">Deskripsi</label>
                <textarea
                    name="deskripsi" id="edit-jenis-deskripsi-owner" rows="3"
                    class="w-full border border-[#ecd9d9] rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-[#f4b6bc] focus:ring-2 focus:ring-pink-100 transition"
                ></textarea>
            </div>

            <button
                type="submit"
                class="w-full bg-[#f45b69] text-white py-2.5 rounded-full text-sm font-medium shadow-sm hover:opacity-90 transition"
            >
                Simpan Perubahan
            </button>
        </form>

    </div>
</div>


{{-- ============================================================ --}}
{{-- MODAL STATUS CABANG --}}
{{-- ============================================================ --}}
<div id="modal-status" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-sm p-6 relative">

        <button
            onclick="closeStatusModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl leading-none"
        >&times;</button>

        <h2 class="text-base font-semibold text-[#2d2a26] mb-1">Ubah Status Cabang</h2>
        <p id="modal-status-nama" class="text-sm text-gray-400 mb-4"></p>
        <p class="text-xs text-gray-500 mb-3">Pilih cabang yang ingin diubah statusnya:</p>

        <div id="modal-status-cabang" class="space-y-3 mb-5"></div>

        <div class="flex gap-2">
            <form id="form-nonaktif" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="active_tab" value="layanan">
                <div id="hidden-nonaktif"></div>
                <button
                    type="submit"
                    onclick="syncHidden('hidden-nonaktif')"
                    class="w-full bg-red-50 text-red-500 py-2.5 rounded-full text-sm font-medium hover:bg-red-100 transition"
                >
                    Nonaktifkan
                </button>
            </form>

            <form id="form-aktif" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="active_tab" value="layanan">
                <div id="hidden-aktif"></div>
                <button
                    type="submit"
                    onclick="syncHidden('hidden-aktif')"
                    class="w-full bg-green-50 text-green-600 py-2.5 rounded-full text-sm font-medium hover:bg-green-100 transition"
                >
                    Aktifkan
                </button>
            </form>
        </div>

    </div>
</div>


<script>
    // ── FORMAT HARGA ──────────────────────────────────────────────────────────
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

    // ── TAB ───────────────────────────────────────────────────────────────────
    function switchTab(tab) {
        ['layanan', 'paket', 'jenis'].forEach(function (t) {
            document.getElementById('panel-' + t).classList.toggle('hidden', t !== tab);
            var btn = document.getElementById('tab-' + t);
            if (t === tab) {
                btn.classList.add('border-[#f45b69]', 'text-[#f45b69]');
                btn.classList.remove('border-transparent', 'text-gray-500');
            } else {
                btn.classList.remove('border-[#f45b69]', 'text-[#f45b69]');
                btn.classList.add('border-transparent', 'text-gray-500');
            }
        });
    }

    // ── MODAL EDIT LAYANAN ────────────────────────────────────────────────────
    function openEditLayananModal(data) {
        document.getElementById('form-edit-layanan').action = '/service/manage/' + data.layanan_id;
        document.getElementById('edit-nama').value      = data.nama_layanan       ?? '';
        document.getElementById('edit-deskripsi').value = data.deskripsi          ?? '';
        document.getElementById('edit-durasi').value    = data.durasi             ?? '';
        document.getElementById('edit-jenis').value     = data.jenis_layanan_id   ?? '';
        document.getElementById('edit-kategori').value  = data.kategori_pelanggan ?? 'umum';
        setHargaDisplay('display-edit-harga', 'edit-harga', data.harga ?? '');

        var preview = document.getElementById('edit-foto-preview');
        preview.innerHTML = data.cover_foto
            ? '<div class="flex items-center gap-3 p-2 bg-pink-50 rounded-2xl">'
                + '<img src="/storage/' + data.cover_foto + '" class="w-12 h-12 object-cover rounded-xl border border-[#ecd9d9]">'
                + '<span class="text-xs text-gray-500">Foto saat ini</span></div>'
            : '<p class="text-xs text-gray-400 mb-1">Belum ada foto.</p>';

        document.getElementById('modal-edit-layanan').classList.remove('hidden');
        document.getElementById('modal-edit-layanan').classList.add('flex');
    }

    function closeEditLayananModal() {
        document.getElementById('modal-edit-layanan').classList.add('hidden');
        document.getElementById('modal-edit-layanan').classList.remove('flex');
    }

    document.getElementById('modal-edit-layanan').addEventListener('click', function (e) {
        if (e.target === this) closeEditLayananModal();
    });

    // ── MODAL EDIT PAKET ──────────────────────────────────────────────────────
    function openEditPaketModal(data) {
        document.getElementById('form-edit-paket').action = '/service/manage/paket/' + data.paket_id;
        document.getElementById('edit-paket-nama').value      = data.nama_paket         ?? '';
        document.getElementById('edit-paket-deskripsi').value = data.deskripsi          ?? '';
        document.getElementById('edit-paket-kategori').value  = data.kategori_pelanggan ?? 'umum';
        setHargaDisplay('display-edit-harga-paket', 'edit-harga-paket', data.harga_normal ?? '');

        document.getElementById('modal-edit-paket').classList.remove('hidden');
        document.getElementById('modal-edit-paket').classList.add('flex');
    }

    function closeEditPaketModal() {
        document.getElementById('modal-edit-paket').classList.add('hidden');
        document.getElementById('modal-edit-paket').classList.remove('flex');
    }

    document.getElementById('modal-edit-paket').addEventListener('click', function (e) {
        if (e.target === this) closeEditPaketModal();
    });

    // ── MODAL STATUS CABANG ───────────────────────────────────────────────────
    function openStatusModal(data) {
        document.getElementById('modal-status-nama').textContent = data.nama;

        document.getElementById('modal-status-cabang').innerHTML = data.cabang.map(function (c) {
            var shortName = c.nama_cabang.replace('Salon Muslimah Dina - ', '');
            var badge = c.status === 'tersedia'
                ? '<span class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-600">Aktif</span>'
                : '<span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-400">Nonaktif</span>';
            return '<label class="flex items-center justify-between cursor-pointer">'
                + '<span class="flex items-center gap-3">'
                + '<input type="checkbox" class="status-cb w-4 h-4 accent-[#f45b69]" value="' + c.cabang_id + '" checked>'
                + '<span class="text-sm text-[#2d2a26]">' + shortName + '</span>'
                + '</span>' + badge + '</label>';
        }).join('');

        document.getElementById('form-nonaktif').action = '/service/manage/' + data.layanan_id + '/deactivate';
        document.getElementById('form-aktif').action    = '/service/manage/' + data.layanan_id + '/activate';

        document.getElementById('modal-status').classList.remove('hidden');
        document.getElementById('modal-status').classList.add('flex');
    }

    function closeStatusModal() {
        document.getElementById('modal-status').classList.add('hidden');
        document.getElementById('modal-status').classList.remove('flex');
    }

    document.getElementById('modal-status').addEventListener('click', function (e) {
        if (e.target === this) closeStatusModal();
    });

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

    // ── SEARCH: TABEL LAYANAN ─────────────────────────────────────────────────
    function filterLayanan(q) {
        var rows    = document.querySelectorAll('.layanan-row');
        var term    = q.trim().toLowerCase();
        var visible = 0;

        rows.forEach(function (row) {
            var match = !term || row.dataset.search.includes(term);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        var counter = document.getElementById('layanan-count');
        if (counter) counter.textContent = visible + ' layanan';

        var empty = document.getElementById('layanan-empty-row');
        if (!empty) {
            empty = document.createElement('tr');
            empty.id = 'layanan-empty-row';
            empty.innerHTML = '<td colspan="6" class="py-10 text-center text-gray-400 text-sm">Tidak ada layanan yang cocok.</td>';
            document.getElementById('layanan-tbody').appendChild(empty);
        }
        empty.style.display = visible === 0 ? '' : 'none';
    }

    // ── SEARCH: DAFTAR PAKET ──────────────────────────────────────────────────
    function filterPaket(q) {
        var cards   = document.querySelectorAll('.paket-row');
        var term    = q.trim().toLowerCase();
        var visible = 0;

        cards.forEach(function (card) {
            var match = !term || card.dataset.search.includes(term);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        var counter = document.getElementById('paket-count');
        if (counter) counter.textContent = visible + ' paket';

        var empty = document.getElementById('paket-empty-msg');
        if (!empty) {
            empty = document.createElement('p');
            empty.id = 'paket-empty-msg';
            empty.className = 'py-10 text-center text-gray-400 text-sm';
            empty.textContent = 'Tidak ada paket yang cocok.';
            document.getElementById('paket-list').appendChild(empty);
        }
        empty.style.display = visible === 0 ? '' : 'none';
    }

    // ── SEARCH + COUNTER: PILIH LAYANAN DI FORM PAKET ────────────────────────
    function filterPilihLayanan(q) {
        var items   = document.querySelectorAll('.pilih-layanan-item');
        var term    = q.trim().toLowerCase();
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

    // ── MODAL EDIT JENIS ──────────────────────────────────────────
    function openEditJenisModal(data) {
        var form = document.getElementById('form-edit-jenis');
        form.action = '/service/manage/jenis/' + data.jenis_layanan_id;
        document.getElementById('edit-jenis-nama-owner').value      = data.nama_jenis ?? '';
        document.getElementById('edit-jenis-deskripsi-owner').value = data.deskripsi  ?? '';
        document.getElementById('modal-edit-jenis').classList.remove('hidden');
        document.getElementById('modal-edit-jenis').classList.add('flex');
    }

    function closeEditJenisModal() {
        document.getElementById('modal-edit-jenis').classList.add('hidden');
        document.getElementById('modal-edit-jenis').classList.remove('flex');
    }

    document.getElementById('modal-edit-jenis').addEventListener('click', function(e) {
        if (e.target === this) closeEditJenisModal();
    });
</script>

@endsection