@extends('owner.app')

@section('title', 'Kelola Cabang')

@section('content')

<div class="w-full font-sans text-[#3B302D]">

    {{-- TITLE --}}
    <div class="mb-6">
        <h1 class="text-4xl lg:text-5xl font-bold text-[#2d2a26]">Kelola Cabang</h1>
        <p class="mt-2 text-gray-500">Tambah, edit, dan kelola status seluruh cabang salon.</p>
    </div>

    {{-- TOMBOL TAMBAH --}}
    <div class="flex justify-end mb-5">
        <button type="button" @click="$dispatch('open-modal-tambah')"
                class="flex items-center gap-2 px-5 h-[42px] rounded-xl bg-[#f45b69] text-white
                       text-[14px] font-medium hover:opacity-90 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Cabang
        </button>
    </div>

    {{-- DAFTAR CABANG --}}
    @forelse($cabangs as $cabang)
    <div class="bg-white border-[3px] border-[#f3dede] rounded-[20px] px-7 py-5 mb-5">

        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

            {{-- INFO CABANG --}}
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-[17px] font-bold text-[#2d2a26]">{{ $cabang->nama_cabang }}</h2>
                    <span class="px-3 py-0.5 rounded-full text-[11px] font-semibold
                        {{ $cabang->status === 'BUKA'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-gray-100 text-gray-500' }}">
                        {{ $cabang->status }}
                    </span>
                </div>

                <p class="text-[13px] text-[#9B8B87] mb-3 flex items-start gap-1.5">
                    <svg class="w-4 h-4 shrink-0 mt-0.5 text-[#d4697a]" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $cabang->alamat }}
                </p>

                {{-- JADWAL OPERASIONAL --}}
                @if($cabang->jadwalOperasional->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($cabang->jadwalOperasional as $j)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#FFF4F4]
                                 text-[11px] text-[#7a3037] font-medium capitalize">
                        {{ $j->hari }}
                        <span class="text-[#9B8B87] font-normal">
                            {{ substr($j->jam_buka, 0, 5) }}–{{ substr($j->jam_tutup, 0, 5) }}
                        </span>
                    </span>
                    @endforeach
                </div>
                @else
                    <p class="text-[12px] text-gray-400 italic">Belum ada jadwal operasional.</p>
                @endif
            </div>

            {{-- AKSI --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- Toggle status --}}
                <form method="POST" action="{{ route('owner.cabang.toggle', $cabang->cabang_id) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="px-4 h-[36px] rounded-xl border text-[13px] font-medium transition
                                {{ $cabang->status === 'BUKA'
                                    ? 'border-gray-300 text-gray-500 hover:bg-gray-50'
                                    : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                        {{ $cabang->status === 'BUKA' ? 'Tutup' : 'Buka' }}
                    </button>
                </form>

                {{-- Edit --}}
                <button type="button"
                        @click="$dispatch('open-modal-edit', {{ json_encode([
                            'cabang_id'   => $cabang->cabang_id,
                            'nama_cabang' => $cabang->nama_cabang,
                            'alamat'      => $cabang->alamat,
                            'status'      => $cabang->status,
                            'jadwal'      => $cabang->jadwalOperasional->map(fn($j) => [
                                'jadwal_id' => $j->jadwal_id,
                                'hari'      => $j->hari,
                                'jam_buka'  => substr($j->jam_buka, 0, 5),
                                'jam_tutup' => substr($j->jam_tutup, 0, 5),
                            ])->values(),
                        ]) }})"
                        class="px-4 h-[36px] rounded-xl bg-[#FFF4F4] text-[#d4697a] text-[13px]
                               font-medium hover:bg-[#F8D7DC] transition">
                    Edit
                </button>

                {{-- Hapus --}}
                <form method="POST"
                      action="{{ route('owner.cabang.destroy', $cabang->cabang_id) }}"
                      onsubmit="return confirm('Yakin ingin menghapus cabang ini? Aksi tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 h-[36px] rounded-xl bg-red-50 text-red-500 text-[13px]
                                   font-medium hover:bg-red-100 transition">
                        Hapus
                    </button>
                </form>

            </div>
        </div>
    </div>
    @empty
    <div class="bg-white border-[3px] border-[#f3dede] rounded-[20px] px-9 py-14 text-center">
        <p class="text-gray-400 text-[15px]">Belum ada cabang. Tambahkan cabang pertama kamu.</p>
    </div>
    @endforelse

</div>


{{-- ===================== MODAL TAMBAH ===================== --}}
<div
    x-data="cabangModal()"
    @open-modal-tambah.window="openTambah()"
    @open-modal-edit.window="openEdit($event.detail)"
    @keydown.escape.window="close()"
>

{{-- MODAL TAMBAH --}}
<template x-if="modeTambah">
<div class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4">
    <div class="absolute inset-0 bg-black/40" @click="close()"></div>
    <div class="relative bg-white rounded-[24px] w-full max-w-[620px] shadow-2xl z-10
                max-h-[85vh] overflow-y-auto">

        <div class="px-8 pt-7 pb-2">
            <h3 class="text-[20px] font-bold text-[#2d2a26]">Tambah Cabang Baru</h3>
            <p class="text-[13px] text-[#9B8B87] mt-1">Isi informasi cabang dan jadwal operasional.</p>
        </div>

        <form method="POST" action="{{ route('owner.cabang.store') }}" @submit="submitting = true">
            @csrf
            <div class="px-8 py-5 space-y-5">

                {{-- Nama Cabang --}}
                <div>
                    <label class="block text-[15px] font-medium text-[#2d2a26] mb-2">Nama Cabang</label>
                    <input type="text" name="nama_cabang" placeholder="Contoh: Salon Dina - Medan Baru"
                           value="{{ old('nama_cabang') }}"
                           class="w-full h-[50px] rounded-xl border border-[#f3dede] px-4 text-[15px]
                                  text-[#6E6969] outline-none focus:border-[#f45b69] transition
                                  @error('nama_cabang') border-red-400 @enderror" required>
                    @error('nama_cabang')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-[15px] font-medium text-[#2d2a26] mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" placeholder="Jl. ..."
                              class="w-full rounded-xl border border-[#f3dede] px-4 py-3 text-[15px]
                                     text-[#6E6969] outline-none focus:border-[#f45b69] transition resize-none
                                     @error('alamat') border-red-400 @enderror"
                              required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-[15px] font-medium text-[#2d2a26] mb-2">Status</label>
                    <select name="status"
                            class="w-full h-[50px] rounded-xl border border-[#f3dede] px-4 text-[15px]
                                   text-[#6E6969] outline-none focus:border-[#f45b69] transition bg-white">
                        <option value="BUKA" {{ old('status') !== 'TUTUP' ? 'selected' : '' }}>BUKA</option>
                        <option value="TUTUP" {{ old('status') === 'TUTUP' ? 'selected' : '' }}>TUTUP</option>
                    </select>
                </div>

                {{-- JADWAL OPERASIONAL --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-[15px] font-medium text-[#2d2a26]">Jadwal Operasional</label>
                        <button type="button" @click="tambahJadwal()"
                                class="text-[13px] text-[#f45b69] hover:underline font-medium">
                            + Tambah Hari
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(j, i) in jadwal" :key="i">
                            <div class="flex items-center gap-3 flex-wrap">
                                <select :name="`jadwal[${i}][hari]`" x-model="j.hari"
                                        class="flex-1 min-w-[120px] h-[44px] rounded-xl border border-[#f3dede]
                                               px-3 text-[14px] text-[#6E6969] outline-none focus:border-[#f45b69]
                                               transition bg-white capitalize">
                                    <template x-for="h in hariList" :key="h">
                                        <option :value="h" :selected="j.hari === h" x-text="h.charAt(0).toUpperCase() + h.slice(1)"></option>
                                    </template>
                                </select>
                                <input type="time" :name="`jadwal[${i}][jam_buka]`" x-model="j.jam_buka"
                                       class="h-[44px] rounded-xl border border-[#f3dede] px-3 text-[14px]
                                              text-[#6E6969] outline-none focus:border-[#f45b69] transition">
                                <span class="text-[#9B8B87] text-[13px]">–</span>
                                <input type="time" :name="`jadwal[${i}][jam_tutup]`" x-model="j.jam_tutup"
                                       class="h-[44px] rounded-xl border border-[#f3dede] px-3 text-[14px]
                                              text-[#6E6969] outline-none focus:border-[#f45b69] transition">
                                <button type="button" @click="hapusJadwal(i)"
                                        class="p-2 rounded-xl text-red-400 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <p x-show="jadwal.length === 0" class="text-[13px] text-gray-400 italic">
                            Belum ada jadwal. Klik "Tambah Hari" untuk menambahkan.
                        </p>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-8 pb-7 flex justify-end gap-3">
                <button type="button" @click="close()"
                        class="px-5 h-[42px] rounded-xl border border-gray-200 text-[14px]
                               text-[#9B8B87] hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" :disabled="submitting"
                        class="px-5 h-[42px] rounded-xl bg-[#f45b69] text-white text-[14px]
                               font-medium hover:opacity-90 transition disabled:opacity-60 flex items-center gap-2">
                    <template x-if="submitting">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </template>
                    Simpan Cabang
                </button>
            </div>
        </form>

    </div>
</div>
</template>


{{-- ===================== MODAL EDIT ===================== --}}
<template x-if="modeEdit">
<div class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4">
    <div class="absolute inset-0 bg-black/40" @click="close()"></div>
    <div class="relative bg-white rounded-[24px] w-full max-w-[620px] shadow-2xl z-10
                max-h-[85vh] overflow-y-auto">

        <div class="px-8 pt-7 pb-2">
            <h3 class="text-[20px] font-bold text-[#2d2a26]">Edit Cabang</h3>
            <p class="text-[13px] text-[#9B8B87] mt-1">Perbarui informasi cabang.</p>
        </div>

        <form method="POST" :action="`/owner/cabang/${editData.cabang_id}`" @submit="submitting = true">
            @csrf
            @method('PUT')
            <div class="px-8 py-5 space-y-5">

                {{-- Nama Cabang --}}
                <div>
                    <label class="block text-[15px] font-medium text-[#2d2a26] mb-2">Nama Cabang</label>
                    <input type="text" name="nama_cabang" x-model="editData.nama_cabang"
                           class="w-full h-[50px] rounded-xl border border-[#f3dede] px-4 text-[15px]
                                  text-[#6E6969] outline-none focus:border-[#f45b69] transition" required>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-[15px] font-medium text-[#2d2a26] mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" x-model="editData.alamat"
                              class="w-full rounded-xl border border-[#f3dede] px-4 py-3 text-[15px]
                                     text-[#6E6969] outline-none focus:border-[#f45b69] transition resize-none"
                              required></textarea>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-[15px] font-medium text-[#2d2a26] mb-2">Status</label>
                    <select name="status" x-model="editData.status"
                            class="w-full h-[50px] rounded-xl border border-[#f3dede] px-4 text-[15px]
                                   text-[#6E6969] outline-none focus:border-[#f45b69] transition bg-white">
                        <option value="BUKA">BUKA</option>
                        <option value="TUTUP">TUTUP</option>
                    </select>
                </div>

                {{-- JADWAL --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-[15px] font-medium text-[#2d2a26]">Jadwal Operasional</label>
                        <button type="button" @click="tambahJadwal()"
                                class="text-[13px] text-[#f45b69] hover:underline font-medium">
                            + Tambah Hari
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(j, i) in jadwal" :key="i">
                            <div class="flex items-center gap-3 flex-wrap">
                                <input type="hidden" :name="`jadwal[${i}][jadwal_id]`" :value="j.jadwal_id ?? ''">
                                <select :name="`jadwal[${i}][hari]`" x-model="j.hari"
                                        class="flex-1 min-w-[120px] h-[44px] rounded-xl border border-[#f3dede]
                                               px-3 text-[14px] text-[#6E6969] outline-none focus:border-[#f45b69]
                                               transition bg-white">
                                    <template x-for="h in hariList" :key="h">
                                        <option :value="h" :selected="j.hari === h" x-text="h.charAt(0).toUpperCase() + h.slice(1)"></option>
                                    </template>
                                </select>
                                <input type="time" :name="`jadwal[${i}][jam_buka]`" x-model="j.jam_buka"
                                       class="h-[44px] rounded-xl border border-[#f3dede] px-3 text-[14px]
                                              text-[#6E6969] outline-none focus:border-[#f45b69] transition">
                                <span class="text-[#9B8B87] text-[13px]">–</span>
                                <input type="time" :name="`jadwal[${i}][jam_tutup]`" x-model="j.jam_tutup"
                                       class="h-[44px] rounded-xl border border-[#f3dede] px-3 text-[14px]
                                              text-[#6E6969] outline-none focus:border-[#f45b69] transition">
                                <button type="button" @click="hapusJadwal(i)"
                                        class="p-2 rounded-xl text-red-400 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <p x-show="jadwal.length === 0" class="text-[13px] text-gray-400 italic">
                            Belum ada jadwal. Klik "Tambah Hari" untuk menambahkan.
                        </p>
                    </div>
                </div>

            </div>

            <div class="px-8 pb-7 flex justify-end gap-3">
                <button type="button" @click="close()"
                        class="px-5 h-[42px] rounded-xl border border-gray-200 text-[14px]
                               text-[#9B8B87] hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" :disabled="submitting"
                        class="px-5 h-[42px] rounded-xl bg-[#f45b69] text-white text-[14px]
                               font-medium hover:opacity-90 transition disabled:opacity-60 flex items-center gap-2">
                    <template x-if="submitting">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </template>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
</template>

</div>{{-- end x-data --}}


<script>
function cabangModal() {
    return {
        modeTambah: false,
        modeEdit: false,
        submitting: false,
        editData: {},
        jadwal: [],
        hariList: ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'],

        openTambah() {
            this.modeTambah = true;
            this.modeEdit   = false;
            this.submitting = false;
            // Default: isi 7 hari
            this.jadwal = this.hariList.map(h => ({
                hari: h, jam_buka: '09:00', jam_tutup: '19:00'
            }));
        },

        openEdit(data) {
            this.modeEdit   = true;
            this.modeTambah = false;
            this.submitting = false;
            this.editData   = { ...data };
            this.jadwal     = data.jadwal ? data.jadwal.map(j => ({ ...j })) : [];
        },

        close() {
            this.modeTambah = false;
            this.modeEdit   = false;
            this.submitting = false;
        },

        tambahJadwal() {
            const usedHari = this.jadwal.map(j => j.hari);
            const sisaHari = this.hariList.find(h => !usedHari.includes(h));
            this.jadwal.push({
                hari: sisaHari || 'senin',
                jam_buka: '09:00',
                jam_tutup: '19:00',
            });
        },

        hapusJadwal(i) {
            this.jadwal.splice(i, 1);
        },
    };
}
</script>

@endsection