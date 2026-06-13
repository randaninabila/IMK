@extends(
    auth()->user()->role === 'owner'
        ? 'owner.app'
        : 'user.app'
)

@section('content')

@if(auth()->user()->role === 'owner')

<div class="max-w-5xl">

@else

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] to-white pt-28 pb-16 px-4">
    <div class="max-w-4xl mx-auto">

@endif

    <form action="{{ route('profile.update') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

        {{-- HEADER PROFILE --}}
        <div class="flex items-center gap-8 mb-10">

            {{-- FOTO AREA --}}
            <div class="relative shrink-0" x-data="{ open:false }">

                {{-- FOTO --}}
                <button
                    type="button"
                    @click="open = !open"

                    class="
                        relative
                        group
                        cursor-pointer
                    "
                >

                    <img
                        id="preview-foto"
                        src="{{ $user->foto_profile_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=FFE4E6&color=3E382D&size=120' }}"
                        class="
                            w-24 h-24
                            rounded-full
                            border-4 border-pink-200
                            object-cover
                            shadow-md
                            transition
                            group-hover:brightness-75
                        "
                        alt="{{ $user->nama }}"
                    >

                    {{-- OVERLAY --}}
                    <div class="
                        absolute inset-0
                        rounded-full
                        bg-black/40
                        flex items-center justify-center
                        opacity-0
                        group-hover:opacity-100
                        transition
                    ">

                        <span class="
                            text-white
                            text-[11px]
                            font-medium
                        ">
                            Edit Profile
                        </span>

                    </div>

                </button>

                {{-- GALERI --}}
                <input
                    type="file"
                    id="foto_profile"
                    name="foto_profile"
                    accept="image/*"
                    class="hidden"
                >

                {{-- CAMERA --}}
                <input
                    type="file"
                    id="camera_profile"
                    name="camera_profile"
                    accept="image/*"
                    capture="user"
                    class="hidden"
                >

                <input
                    type="hidden"
                    id="hapus_foto"
                    name="hapus_foto"
                    value="0"
                >

                    {{-- FLOATING MENU --}}
                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition

                        class="
                            absolute left-1/2 -translate-x-1/2 top-28
                            w-40
                            bg-white/95
                            backdrop-blur-md
                            rounded-2xl
                            shadow-xl
                            border border-[#F3E3E3]
                            p-1
                            z-50
                            space-y-0
                        "
                    >

                    {{-- CAMERA --}}
                    <button
                        type="button"
                        @click="open = false; document.getElementById('camera_profile').click();"

                        class="
                            w-full
                            flex items-center gap-1.5
                            px-2 py-1.5
                            rounded-xl
                            hover:bg-[#FFF4F4]
                            text-[13px]
                            text-[#3E382D]
                            transition
                            leading-none
                        "
                    >

                        <span class="text-[13px]">
                            📷
                        </span>

                        <span>
                            Ambil Foto
                        </span>

                    </button>

                    {{-- GALERI --}}
                    <button
                        type="button"
                        @click="open = false; document.getElementById('foto_profile').click();"

                        class="
                            w-full
                            flex items-center gap-1.5
                            px-2 py-1.5
                            rounded-xl
                            hover:bg-[#FFF4F4]
                            text-[13px]
                            text-[#3E382D]
                            transition
                            leading-none
                        "
                    >

                        <span class="text-[13px]">
                            🖼️
                        </span>

                        <span>
                            Pilih Galeri
                        </span>

                    </button>
                    
                    {{-- HAPUS --}}
                    @if($user->foto_profile)
                    <button
                        type="button"
                        @click="open = false; hapusFotoProfile();"

                        class="
                            w-full
                            flex items-center gap-1.5
                            px-2 py-1.5
                            rounded-xl
                            hover:bg-red-50
                            text-[13px]
                            text-red-500
                            transition
                            leading-none
                        "
                    >

                        <span class="text-[13px]">
                            🗑️
                        </span>

                        <span>
                            Hapus Foto
                        </span>

                    </button>
                    @endif

                </div>
            </div>


            {{-- INFO --}}
            <div>

                <h1 class="
                    text-3xl
                    font-bold
                    text-[#3E382D]
                ">
                    {{ $user->nama }}
                </h1>

                <p class="
                    text-gray-500
                    text-sm
                    mt-1
                ">
                    {{ $user->email }}
                </p>

                <span class="
                    inline-block mt-2
                    text-[10px]
                    uppercase
                    bg-rose-100
                    text-rose-600
                    px-2 py-1
                    rounded-full
                    font-bold
                ">
                    {{ $user->role }}
                </span>

            </div>

        </div>

        {{-- TAB NAVIGATION --}}
        <div class="flex gap-2 border-b border-gray-200 mb-8" id="tabNav">

            <button type="button" onclick="switchTab('profile')" id="tab-profile"
                class="tab-btn px-5 py-2 text-sm font-semibold border-b-2 border-[#3E382D] text-[#3E382D] transition">
                Data Diri
            </button>

            <button type="button" onclick="switchTab('password')" id="tab-password"
                class="tab-btn px-5 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-[#3E382D] transition">
                Ganti Kata Sandi
            </button>

        </div>

        {{-- ================================
             TAB 1: DATA DIRI
        ================================ --}}
        <div id="tab-content-profile">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-lg font-bold text-[#3E382D] mb-6">Edit Data Diri</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email (readonly) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" value="{{ $user->email }}"
                                class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-400 cursor-not-allowed"
                                readonly>
                            <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah.</p>
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200
                                    @error('no_hp') border-red-400 @enderror">
                            @error('no_hp')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">Format: 08xxx, 628xxx, atau +628xxx</p>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $pelanggan->tanggal_lahir ?? '') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="alamat" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-[#3E382D] text-white px-8 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                            Simpan Perubahan
                        </button>
                    </div>
            </div>
        </div>
    </form>

        {{-- ================================
             TAB 2: GANTI PASSWORD
        ================================ --}}
        <div id="tab-content-password" class="hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-lg font-bold text-[#3E382D] mb-6">Ganti Kata Sandi</h2>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5 max-w-md">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Lama</label>
                            <input type="password" name="password_lama"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                            @error('password_lama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password_baru"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_baru_confirmation"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                            @error('password_baru')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-[#3E382D] text-white px-8 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                            Ubah Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>

@if(auth()->user()->role === 'owner')

</div>

@else

    </div>
</div>

@endif

<script>
function switchTab(tab) {

    ['profile', 'booking', 'password'].forEach(t => {

        const content =
            document.getElementById('tab-content-' + t);

        const btn =
            document.getElementById('tab-' + t);

        if(content) {
            content.classList.add('hidden');
        }

        if(btn) {

            btn.classList.remove(
                'border-[#3E382D]',
                'text-[#3E382D]'
            );

            btn.classList.add(
                'border-transparent',
                'text-gray-400'
            );
        }
    });

    const activeContent =
        document.getElementById('tab-content-' + tab);

    const activeBtn =
        document.getElementById('tab-' + tab);

    if(activeContent) {
        activeContent.classList.remove('hidden');
    }

    if(activeBtn) {

        activeBtn.classList.add(
            'border-[#3E382D]',
            'text-[#3E382D]'
        );

        activeBtn.classList.remove(
            'border-transparent',
            'text-gray-400'
        );
    }
}

// Auto open password tab
window.addEventListener('DOMContentLoaded', () => {

    if (window.location.hash === '#password') {
        switchTab('password');
    }

});

['foto_profile', 'camera_profile']
.forEach(id => {

    document.getElementById(id)
    ?.addEventListener('change', function(e) {

        const file = e.target.files[0];

        if(file) {

            document.getElementById('hapus_foto')
                .value = '0';

            document.getElementById('preview-foto')
                .src = URL.createObjectURL(file);
        }
    });

});

function hapusFotoProfile() {

    document.getElementById('preview-foto')
        .src =
        'https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=FFE4E6&color=3E382D&size=120';

    document.getElementById('hapus_foto')
        .value = '1';
}

@if(
    $errors->has('password_lama') ||
    $errors->has('password_baru')
)
    switchTab('password');
@endif
</script>

@endsection
