<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Dina Salon Muslimah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeLeft {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .anim-left { animation: fadeLeft 0.65s ease forwards; }
        .anim-card { animation: fadeUp  0.65s ease 0.1s forwards; opacity: 0; }

        .form-input { transition: all 0.25s ease; }
        .form-input:focus {
            background: rgba(255,255,255,0.65) !important;
            box-shadow: 0 0 0 3px rgba(61,53,47,0.12);
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%,60%  { transform: translateX(-6px); }
            40%,80%  { transform: translateX(6px); }
        }
        .shake { animation: shake 0.4s ease; }

        .blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,173,178,0.18);
            filter: blur(32px);
            pointer-events: none;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center py-10 px-6
    bg-[radial-gradient(circle_at_top_left,_#FFADB2_0%,_transparent_40%),radial-gradient(circle_at_bottom_right,_#FFADB2_0%,_transparent_40%),linear-gradient(to_bottom,_#FFE4E6_0%,_#ffffff_100%)]">

<div class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

    <!-- ========================= -->
    <!-- LEFT                      -->
    <!-- ========================= -->
    <div class="anim-left">

        <div class="inline-flex items-center gap-2 border border-[#3d352f]/20 rounded-full px-4 py-1.5 mb-6">
            <div class="w-2 h-2 rounded-full bg-[#c4a89a]"></div>
            <span class="text-[#3d352f] text-xs font-medium tracking-wide">Buat Akun Baru</span>
        </div>

        <h1 class="text-5xl md:text-6xl font-bold text-[#3d352f] leading-tight">
            Dina <span class="italic">Salon</span><br>
            Muslimah
        </h1>

        <p class="mt-5 text-[#5a4e47] text-base leading-relaxed max-w-sm">
            Kecantikan alami dimulai dari perawatan terbaik.
            Kami hadir untuk membuat Anda tampil lebih percaya diri setiap hari.
        </p>

        <!-- Progress steps -->
        <div class="mt-10 space-y-3 max-w-xs">
            <p class="text-xs text-[#3d352f]/50 uppercase tracking-widest font-medium mb-4">Proses Registrasi</p>

            @foreach([
                ['num' => '1', 'label' => 'Daftar Akun',        'state' => 'active'],
                ['num' => '2', 'label' => 'Verifikasi Email',    'state' => 'next'],
                ['num' => '3', 'label' => 'Buat Password',       'state' => 'next'],
            ] as $step)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                    {{ $step['state'] === 'active' ? 'bg-[#3d352f] text-white shadow-md'
                      : ($step['state'] === 'done'   ? 'bg-[#3d352f]/40 text-white'
                      : 'bg-[#3d352f]/15 text-[#3d352f]/50') }}">
                    @if($step['state'] === 'done')
                        <i data-feather="check" class="w-3 h-3"></i>
                    @else
                        {{ $step['num'] }}
                    @endif
                </div>
                <span class="text-sm {{ $step['state'] === 'active' ? 'text-[#3d352f] font-semibold' : 'text-[#3d352f]/45' }}">
                    {{ $step['label'] }}
                </span>
            </div>
            @endforeach
        </div>

        <!-- Feature list -->
        <div class="mt-8 max-w-xs bg-[#3d352f]/05 border border-[#3d352f]/12 rounded-2xl p-4 space-y-2">
            <p class="text-xs font-semibold text-[#3d352f] mb-2 flex items-center gap-1.5">
                <i data-feather="star" class="w-3.5 h-3.5"></i>
                Keuntungan Member
            </p>
            <p class="text-xs text-[#6b5b4d] leading-relaxed">• Booking layanan kapan saja & di mana saja.</p>
            <p class="text-xs text-[#6b5b4d] leading-relaxed">• Riwayat booking tersimpan otomatis.</p>
            <p class="text-xs text-[#6b5b4d] leading-relaxed">• Notifikasi promo & jadwal langsung ke email.</p>
        </div>

        <p class="mt-8 text-sm text-[#3d352f]">
            Sudah punya akun?
            <a href="{{ url('/login') }}" class="font-semibold underline underline-offset-2 hover:text-[#6b5b4d] transition-colors">
                Masuk di sini
            </a>
        </p>
    </div>

    <!-- ========================= -->
    <!-- CARD                      -->
    <!-- ========================= -->
    <div class="anim-card relative">

        <div class="blob w-48 h-48 -top-10 -right-10"></div>
        <div class="blob w-32 h-32 -bottom-8 -left-8"></div>

        <div class="relative bg-white/35 backdrop-blur-2xl border border-[#3D352F]/35
            rounded-[40px] p-8 md:p-10
            shadow-[0_12px_40px_rgba(61,53,47,0.20)]">

            <!-- Header -->
            <div class="text-center mb-7">
                <div class="w-16 h-16 rounded-2xl bg-[#3d352f]/08 border border-[#3d352f]/15 flex items-center justify-center mx-auto mb-4">
                    <i data-feather="user-plus" class="w-7 h-7 text-[#3d352f]"></i>
                </div>
                <h2 class="text-3xl font-bold text-[#3d352f]">Buat Akun</h2>
                <p class="text-[#7a6d65] text-sm mt-2">Bergabung dengan pelanggan Dina Salon</p>
            </div>

            <!-- Error dari Laravel -->
            @if ($errors->any())
            <div class="mb-5 flex items-start gap-3 bg-red-50/70 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm">
                <i data-feather="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" id="registerForm" novalidate>
                @csrf

                <!-- NAMA LENGKAP -->
                <div class="mb-4">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="user" class="w-4 h-4"></i>
                        </span>
                        <input id="nama" name="nama" type="text" value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap Anda"
                            autocomplete="name"
                            class="form-input w-full pl-11 pr-4 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                    </div>
                    <p class="text-red-500 text-xs mt-1 hidden" id="nameError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i> Nama tidak boleh kosong
                    </p>
                </div>

                <!-- EMAIL -->
                <div class="mb-4">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="mail" class="w-4 h-4"></i>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            autocomplete="email"
                            class="form-input w-full pl-11 pr-4 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                    </div>
                    <p class="text-red-500 text-xs mt-1 hidden" id="emailError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i> Masukkan email yang valid
                    </p>
                </div>

                <!-- NO. HP -->
                <div class="mb-6">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        No. WhatsApp
                        <span class="text-[#3d352f]/50 font-normal text-xs">(opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="phone" class="w-4 h-4"></i>
                        </span>
                        <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp') }}"
                            placeholder="08xxxxxxxxxx"
                            autocomplete="tel"
                            maxlength="16"
                            oninput="this.value = this.value.replace(/(?!^\+)[^0-9]/g, '')"
                            class="form-input w-full pl-11 pr-4 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                    </div>

                    <p class="text-red-500 text-xs mt-1 hidden" id="hpError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i> Format nomor tidak valid (contoh: 08xxx, 628xxx, atau +628xxx)
                    </p>
                    <p class="text-[#3d352f]/45 text-xs mt-1">Digunakan untuk konfirmasi booking</p>
                </div>

                <!-- SUBMIT -->
                <button type="submit" id="submitBtn"
                    class="w-full py-4 rounded-2xl text-white text-base font-semibold
                        bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                        hover:scale-[1.02] active:scale-[0.98] transition duration-300
                        shadow-md flex items-center justify-center gap-2.5">
                    <i data-feather="send" class="w-4 h-4"></i>
                    Kirim Kode Verifikasi
                </button>
            </form>

            <!-- DIVIDER -->
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-[#3d352f]/12"></div>
                <span class="text-xs text-[#3d352f]/40">atau daftar dengan</span>
                <div class="flex-1 h-px bg-[#3d352f]/12"></div>
            </div>

            <!-- GOOGLE BUTTON -->
            <a href="{{ route('auth.google') }}"
                class="flex items-center justify-center gap-3 w-full py-3.5 rounded-2xl
                    border border-[#3D352F]/30 bg-white/30 backdrop-blur-md
                    text-[#3d352f] font-semibold text-sm
                    hover:bg-white/50 hover:scale-[1.02] transition duration-300 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Lanjutkan dengan Google
            </a>

        </div>
    </div>
</div>

<script>
feather.replace();

document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;
    const nama    = document.getElementById('nama').value.trim();
    const email   = document.getElementById('email').value.trim();
    const noHpRaw = document.getElementById('no_hp').value.trim();
    const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!nama) {
        showError('nameError', 'nama'); valid = false;
    } else {
        hideError('nameError', 'nama');
    }

    if (!email || !emailRx.test(email)) {
        showError('emailError', 'email'); valid = false;
    } else {
        hideError('emailError', 'email');
    }

    // Validasi no HP
    if (noHpRaw) {
        const noHpClean = noHpRaw.replace(/^\+/, '');
        const noHpRx = /^(08\d{8,11}|628\d{8,11})$/;
        if (!noHpRx.test(noHpClean)) {
            showError('hpError', 'no_hp'); valid = false;
        } else {
            hideError('hpError', 'no_hp');
        }
    } else {
        hideError('hpError', 'no_hp');
    }

    if (!valid) { e.preventDefault(); return; }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
    </svg> Mengirim...`;
});

function showError(errId, inputId) {
    document.getElementById(errId).classList.remove('hidden');
    feather.replace();
    if (inputId) {
        const inp = document.getElementById(inputId);
        inp.classList.add('border-red-400', 'shake');
        setTimeout(() => inp.classList.remove('shake'), 400);
    }
}
function hideError(errId, inputId) {
    document.getElementById(errId).classList.add('hidden');
    if (inputId) document.getElementById(inputId).classList.remove('border-red-400');
}

document.getElementById('nama').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('email').focus(); }
});
document.getElementById('email').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('no_hp').focus(); }
});
document.getElementById('no_hp').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('submitBtn').click(); }
});

@if ($errors->has('nama'))
    document.addEventListener('DOMContentLoaded', () => showError('nameError', 'nama'));
@endif
@if ($errors->has('email'))
    document.addEventListener('DOMContentLoaded', () => showError('emailError', 'email'));
@endif
</script>

</body>
</html>