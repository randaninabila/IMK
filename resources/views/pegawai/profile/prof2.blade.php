@extends('pegawai.app')

@section('content')

<div class="w-full px-4 py-4 font-sans text-[#3B302D]">

    {{-- TITLE --}}
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
                    <p class="text-[14px] font-normal text-[#3B302D] mt-1">
                        {{ $pegawai?->cabang?->nama_cabang ?? 'Cabang tidak ditemukan' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM 1: PERSONAL INFORMATION --}}
    <form method="POST" action="{{ route('pegawai.profile.update') }}" enctype="multipart/form-data" id="form-personal">
        @csrf
        @method('PUT')

        <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 pb-6 pt-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-[20px] font-bold text-[#2F2A2A]">
                    Informasi Pribadi
                </h2>
                <button type="submit"
                    class="px-5 h-[40px] rounded-xl bg-[#F1A9B1] text-white text-[14px] font-medium hover:bg-[#e0959d] transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

            <div class="space-y-5 max-w-[480px]">
                
                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama', $user->nama) }}"
                        class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#6E6969] outline-none focus:border-[#F1A9B1] transition"
                    >
                    @error('nama')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Address (READONLY) --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                        Alamat Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ $user->email }}"
                        readonly
                        class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#9B8B87] bg-[#F9F9F9] cursor-not-allowed"
                    >
                    <p class="text-[12px] text-[#9B8B87] mt-1">Email tidak dapat diubah. Hubungi admin jika perlu penyesuaian.</p>
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                        No. Telepon
                    </label>
                    <input
                        type="tel"
                        name="no_hp"
                        value="{{ old('no_hp', $user->no_hp) }}"
                        class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 text-[16px] text-[#6E6969] outline-none focus:border-[#F1A9B1] transition"
                    >
                    @error('no_hp')
                        <p class="text-[12px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
    </form>

    {{-- FORM 2: CHANGE PASSWORD --}}
    <form method="POST" action="{{ route('pegawai.profile.password') }}" id="form-password">
        @csrf
        @method('PUT')

        <div class="mt-6 bg-white border-[3px] border-[#F1A9B1] rounded-[20px] px-9 pb-6 pt-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-[20px] font-bold text-[#2F2A2A]">
                    Kata Sandi
                </h2>
                <button type="submit"
                    class="px-5 h-[40px] rounded-xl bg-[#F1A9B1] text-white text-[14px] font-medium hover:bg-[#e0959d] transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                    Ubah Kata Sandi
                </button>
            </div>

            <p class="text-[13px] text-[#9B8B87] mb-6">
                Kosongkan semua field di bawah jika tidak ingin mengubah password.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Current Password --}}
                <div class="md:col-span-2">
                    <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                        Kata Sandi Lama
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 pr-12 text-[16px] text-[#6E6969] outline-none focus:border-[#F1A9B1] transition {{ $errors->has('current_password') ? 'border-red-500' : '' }}"
                            placeholder="••••••••"
                            autocomplete="current-password"
                        >
                        <button type="button" onclick="togglePassword('current_password', this)" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9B8B87] hover:text-[#F1A9B1] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-off-icon hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                        Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            name="new_password"
                            id="new_password"
                            class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 pr-12 text-[16px] text-[#6E6969] outline-none focus:border-[#F1A9B1] transition {{ $errors->has('new_password') ? 'border-red-500' : '' }}"
                            placeholder="••••••••"
                            autocomplete="new-password"
                        >
                        <button type="button" onclick="togglePassword('new_password', this)" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9B8B87] hover:text-[#F1A9B1] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-off-icon hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div>
                    <label class="block text-[16px] font-medium text-[#2F2A2A] mb-2">
                        Konfirmasi Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            name="new_password_confirmation"
                            id="new_password_confirmation"
                            class="w-full h-[56px] rounded-xl border border-[#F3B3BB] px-5 pr-12 text-[16px] text-[#6E6969] outline-none focus:border-[#F1A9B1] transition {{ $errors->has('new_password_confirmation') ? 'border-red-500' : '' }}"
                            placeholder="••••••••"
                            autocomplete="new-password"
                        >
                        <button type="button" onclick="togglePassword('new_password_confirmation', this)" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9B8B87] hover:text-[#F1A9B1] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-off-icon hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('new_password_confirmation')
                        <p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
    </form>

</div>

{{-- JAVASCRIPT: Toggle Password Visibility --}}
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const eyeIcon = btn.querySelector('.eye-icon');
    const eyeOffIcon = btn.querySelector('.eye-off-icon');
    
    if (!input || !eyeIcon || !eyeOffIcon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}

// Optional: Tambahkan feedback visual saat form disubmit
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if(btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
        }
    });
});
</script>

@endsection