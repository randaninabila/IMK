@extends('owner.app')

@section('title', 'Profil Saya')

@section('content')

<div class="w-full font-sans text-[#3B302D]">

    {{-- TITLE --}}
    <div class="mb-6">
        <h1 class="text-4xl lg:text-5xl font-bold text-[#2d2a26]">Profil Saya</h1>
        <p class="mt-2 text-gray-500">Kelola informasi akun dan keamanan login kamu.</p>
    </div>

    {{-- PROFILE CARD --}}
    <div class="bg-white border-[3px] border-[#f3dede] rounded-[20px] px-9 py-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">

                {{-- FOTO dengan menu ganti --}}
                <div class="relative shrink-0" x-data="{ menuOpen: false }">
                    <button type="button" @click="menuOpen = !menuOpen" class="group relative block">
                        <img
                            id="preview-foto"
                            src="{{ auth()->user()->foto_profile
                                ? asset('storage/' . auth()->user()->foto_profile)
                                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=FFE4E6&color=3E382D&size=120' }}"
                            class="w-28 h-28 rounded-full object-cover border-[5px] border-white shadow-sm
                                   transition group-hover:brightness-75"
                            alt="{{ auth()->user()->nama }}"
                        >
                        <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center
                                    opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-[11px] font-medium">Ganti</span>
                        </div>
                    </button>

                    {{-- MENU FOTO --}}
                    <div x-show="menuOpen" @click.outside="menuOpen = false" x-transition x-cloak
                         class="absolute left-1/2 -translate-x-1/2 top-32 w-44 bg-white rounded-2xl
                                shadow-xl border border-pink-100 p-1 z-50 space-y-0.5">
                        <button type="button"
                                @click="menuOpen = false; document.getElementById('input-foto').click()"
                                class="w-full flex items-center gap-2 px-3 py-2 rounded-xl
                                       hover:bg-[#FFF4F4] text-[13px] text-[#3E382D] transition">
                            🖼️ <span>Pilih Galeri</span>
                        </button>
                        @if(auth()->user()->foto_profile)
                        <button type="button"
                                @click="menuOpen = false; hapusFoto()"
                                class="w-full flex items-center gap-2 px-3 py-2 rounded-xl
                                       hover:bg-red-50 text-[13px] text-red-500 transition">
                            🗑️ <span>Hapus Foto</span>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- INFO --}}
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-[18px] font-bold text-[#3B302D]">
                            {{ auth()->user()->nama }}
                        </h2>
                        <span class="px-3 py-1 rounded-full bg-[#F8D7DC] text-[#7a3037] text-[12px] font-normal">
                            Owner
                        </span>
                    </div>
                    <p class="text-[14px] text-[#9B8B87] mt-1">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->no_hp)
                        <p class="text-[14px] text-[#9B8B87]">{{ auth()->user()->no_hp }}</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- FORM 1: INFORMASI PRIBADI --}}
    <form method="POST" action="{{ route('owner.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white border-[3px] border-[#f3dede] rounded-[20px] px-9 pb-6 pt-6 mb-6">

            {{-- input file tersembunyi — di dalam form agar ikut submit --}}
            <input type="file" id="input-foto" name="foto_profile" accept="image/*" class="hidden"
                   onchange="previewFoto(event)">
            <input type="hidden" id="hapus-foto" name="hapus_foto" value="0">

            <div class="flex items-center justify-between mb-5">
                <h2 class="text-[20px] font-bold text-[#2d2a26]">Informasi Pribadi</h2>
                <button type="submit"
                        class="px-5 h-[40px] rounded-xl bg-[#f45b69] text-white text-[14px] font-medium
                               hover:opacity-90 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

            <div class="space-y-5 max-w-[480px]">

                {{-- Nama --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2d2a26] mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', auth()->user()->nama) }}"
                           class="w-full h-[56px] rounded-xl border border-[#f3dede] px-5 text-[16px]
                                  text-[#6E6969] outline-none focus:border-[#f45b69] transition
                                  @error('nama') border-red-400 @enderror">
                    @error('nama')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email (readonly) --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2d2a26] mb-2">Alamat Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" readonly
                           class="w-full h-[56px] rounded-xl border border-[#f3dede] px-5 text-[16px]
                                  text-[#9B8B87] bg-[#F9F9F9] cursor-not-allowed">
                    <p class="text-[12px] text-[#9B8B87] mt-1">Email tidak dapat diubah.</p>
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2d2a26] mb-2">No. Telepon</label>
                    <input type="tel" name="no_hp" value="{{ old('no_hp', auth()->user()->no_hp) }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full h-[56px] rounded-xl border border-[#f3dede] px-5 text-[16px]
                                  text-[#6E6969] outline-none focus:border-[#f45b69] transition
                                  @error('no_hp') border-red-400 @enderror">
                    @error('no_hp')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

    </form>

    {{-- FORM 2: KATA SANDI --}}
    <form method="POST" action="{{ route('owner.profile.password') }}">
        @csrf
        @method('PUT')

        <div class="bg-white border-[3px] border-[#f3dede] rounded-[20px] px-9 pb-6 pt-6">

            <div class="flex items-center justify-between mb-5">
                <h2 class="text-[20px] font-bold text-[#2d2a26]">Kata Sandi</h2>
                <button type="submit"
                        class="px-5 h-[40px] rounded-xl bg-[#f45b69] text-white text-[14px] font-medium
                               hover:opacity-90 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                    Ganti Kata Sandi
                </button>
            </div>

            <p class="text-[13px] text-[#9B8B87] mb-6">
                Kosongkan semua field di bawah jika tidak ingin mengubah kata sandi.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kata Sandi Lama --}}
                <div class="md:col-span-2">
                    <label class="block text-[16px] font-medium text-[#2d2a26] mb-2">Kata Sandi Lama</label>
                    <div class="relative">
                        <input type="password" name="password_lama" id="pw-lama"
                               placeholder="••••••••" autocomplete="current-password"
                               class="w-full h-[56px] rounded-xl border border-[#f3dede] px-5 pr-12
                                      text-[16px] text-[#6E6969] outline-none focus:border-[#f45b69] transition
                                      @error('password_lama') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('pw-lama', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9B8B87] hover:text-[#f45b69] transition">
                            <svg class="w-5 h-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg class="w-5 h-5 eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_lama')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kata Sandi Baru --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2d2a26] mb-2">Kata Sandi Baru</label>
                    <div class="relative">
                        <input type="password" name="password_baru" id="pw-baru"
                               placeholder="••••••••" autocomplete="new-password"
                               class="w-full h-[56px] rounded-xl border border-[#f3dede] px-5 pr-12
                                      text-[16px] text-[#6E6969] outline-none focus:border-[#f45b69] transition
                                      @error('password_baru') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('pw-baru', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9B8B87] hover:text-[#f45b69] transition">
                            <svg class="w-5 h-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg class="w-5 h-5 eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_baru')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2d2a26] mb-2">Konfirmasi Kata Sandi Baru</label>
                    <div class="relative">
                        <input type="password" name="password_baru_confirmation" id="pw-konfirm"
                               placeholder="••••••••" autocomplete="new-password"
                               class="w-full h-[56px] rounded-xl border border-[#f3dede] px-5 pr-12
                                      text-[16px] text-[#6E6969] outline-none focus:border-[#f45b69] transition">
                        <button type="button" onclick="togglePassword('pw-konfirm', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9B8B87] hover:text-[#f45b69] transition">
                            <svg class="w-5 h-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg class="w-5 h-5 eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const eyeOn  = btn.querySelector('.eye-icon');
    const eyeOff = btn.querySelector('.eye-off-icon');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOn.classList.add('hidden');
        eyeOff.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOn.classList.remove('hidden');
        eyeOff.classList.add('hidden');
    }
}

function previewFoto(event) {
    const file = event.target.files[0];
    if (file) {
        document.getElementById('hapus-foto').value = '0';
        document.getElementById('preview-foto').src = URL.createObjectURL(file);
    }
}

function hapusFoto() {
    document.getElementById('preview-foto').src =
        'https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=FFE4E6&color=3E382D&size=120';
    document.getElementById('hapus-foto').value = '1';
}

document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
        }
    });
});
</script>

@endsection