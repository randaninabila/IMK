<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Dina Salon Muslimah</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        * { box-sizing: border-box; }

        body { font-family: 'Playfair Display', serif; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c4a89a; border-radius: 10px; }

        /* Input focus transition */
        .form-input {
            transition: all 0.25s ease;
        }
        .form-input:focus {
            background: rgba(255,255,255,0.65) !important;
            box-shadow: 0 0 0 3px rgba(61,53,47,0.12);
        }

        /* Password strength bar animation */
        .strength-bar {
            transition: width 0.4s ease, background-color 0.4s ease;
        }

        /* Step animation */
        .step-panel {
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .step-panel.hidden-panel {
            opacity: 0;
            transform: translateX(20px);
            pointer-events: none;
            position: absolute;
            top: 0; left: 0; right: 0;
        }
        .step-panel.active-panel {
            opacity: 1;
            transform: translateX(0);
            position: relative;
        }

        /* Floating label feel */
        label { font-family: 'Playfair Display', serif; }

        /* Error shake */
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%,60% { transform: translateX(-6px); }
            40%,80% { transform: translateX(6px); }
        }
        .shake { animation: shake 0.4s ease; }

        /* Progress dots */
        .dot {
            transition: all 0.3s ease;
        }

        /* Card entrance animation */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-animate { animation: slideUp 0.6s ease forwards; }

        /* Left content entrance */
        @keyframes fadeLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .left-animate { animation: fadeLeft 0.7s ease forwards; }

        /* Select arrow custom */
        select {
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%233d352f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        /* Check icon for password rules */
        .rule-item { transition: color 0.25s ease; }
        .rule-item.met { color: #6b9e7a; }
        .rule-item.met .rule-icon { color: #6b9e7a; }

    </style>
</head>

<body class="min-h-screen
    bg-[radial-gradient(circle_at_top_left,_#FFADB2_0%,_transparent_40%),radial-gradient(circle_at_bottom_right,_#FFADB2_0%,_transparent_40%),linear-gradient(to_bottom,_#FFE4E6_0%,_#ffffff_100%)]
    py-10 px-6">

<div class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center min-h-[calc(100vh-80px)]">

    <!-- ========================= -->
    <!-- LEFT CONTENT              -->
    <!-- ========================= -->
    <div class="left-animate">
        <h1 class="text-5xl md:text-6xl font-bold text-[#3d352f]"
            style="font-family: 'Playfair Display', serif;">
            Dina <span class="italic">Salon</span><br>
            Muslimah
        </h1>

        <p class="mt-6 text-[#3d352f] text-lg leading-relaxed max-w-md">
            Kecantikan alami dimulai dari perawatan terbaik.
            Kami hadir untuk membuat Anda tampil lebih percaya diri setiap hari.
        </p>

        <!-- Feature list -->
        <ul class="mt-8 space-y-3">
            @foreach([
                ['icon' => 'star',       'text' => 'Layanan khusus muslimah terpercaya'],
                ['icon' => 'calendar',   'text' => 'Booking mudah, kapan saja & di mana saja'],
                ['icon' => 'shield',     'text' => 'Data pribadi Anda aman bersama kami'],
            ] as $item)
            <li class="flex items-center gap-3 text-[#3d352f]">
                <span class="w-8 h-8 rounded-full bg-[#3d352f]/10 flex items-center justify-center flex-shrink-0">
                    <i data-feather="{{ $item['icon'] }}" class="w-4 h-4 text-[#3d352f]"></i>
                </span>
                <span class="text-base">{{ $item['text'] }}</span>
            </li>
            @endforeach
        </ul>

        <!-- Already have account -->
        <p class="mt-10 text-[#3d352f]">
            Sudah punya akun?
            <a href="{{ url('/login') }}"
               class="font-semibold underline underline-offset-2 hover:text-[#6b5b4d] transition-colors">
                Masuk di sini
            </a>
        </p>
    </div>

    <!-- ========================= -->
    <!-- REGISTER CARD             -->
    <!-- ========================= -->
    <div class="card-animate
        bg-white/30 backdrop-blur-2xl
        border border-[#3D352F]/40
        rounded-[40px] p-8 md:p-10
        shadow-[0_8px_32px_rgba(61,53,47,0.25)]
        relative overflow-hidden">

        <!-- Decorative blobs inside card -->
        <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-[#FFADB2]/20 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-[#FFADB2]/20 blur-2xl pointer-events-none"></div>

        <!-- TITLE -->
        <h2 class="text-4xl font-bold text-center text-[#3d352f] mb-2" style="font-family:'Playfair Display',serif;">
            Buat Akun
        </h2>
        <p class="text-center text-[#3d352f]/70 text-sm mb-6">Bergabung dengan ribuan pelanggan kami</p>

        <!-- STEP INDICATORS -->
        <div class="flex items-center justify-center gap-2 mb-7" id="stepIndicators">
            <div class="dot w-8 h-2 rounded-full bg-[#3d352f]" id="dot1"></div>
            <div class="dot w-2 h-2 rounded-full bg-[#3d352f]/25" id="dot2"></div>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ url('/register') }}" id="registerForm" novalidate>
            @csrf

            <!-- ==================== -->
            <!-- STEP 1: DATA DIRI    -->
            <!-- ==================== -->
            <div id="step1" class="step-panel active-panel relative">

                <!-- Error bag dari Laravel -->
                @if ($errors->any())
                <div class="mb-5 bg-red-50/60 border border-red-200 rounded-2xl px-4 py-3">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <i data-feather="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- NAMA LENGKAP -->
                <div class="mb-4">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="user" class="w-4 h-4"></i>
                        </span>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap Anda"
                            autocomplete="name"
                            class="form-input w-full pl-11 pr-4 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                    </div>
                    <p class="error-msg text-red-500 text-xs mt-1 hidden" id="nameError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i>
                        Nama tidak boleh kosong
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
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            autocomplete="email"
                            class="form-input w-full pl-11 pr-4 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                    </div>
                    <p class="error-msg text-red-500 text-xs mt-1 hidden" id="emailError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i>
                        Masukkan email yang valid
                    </p>
                </div>

                <!-- NO. HP -->
                <div class="mb-4">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        No. WhatsApp
                        <span class="text-[#3d352f]/50 font-normal text-xs">(opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="phone" class="w-4 h-4"></i>
                        </span>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            value="{{ old('phone') }}"
                            placeholder="08xxxxxxxxxx"
                            autocomplete="tel"
                            maxlength="15"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="form-input w-full pl-11 pr-4 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                    </div>
                    <p class="text-[#3d352f]/50 text-xs mt-1">
                        Digunakan untuk konfirmasi booking
                    </p>
                </div>

                <!-- NEXT BUTTON -->
                <button
                    type="button"
                    onclick="goToStep2()"
                    class="w-full py-3.5 mt-2 rounded-2xl text-white text-base font-semibold
                        bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                        hover:scale-[1.02] active:scale-[0.98] transition duration-300
                        shadow-md">
                    Selanjutnya &rarr;
                </button>
            </div>

            <!-- ======================== -->
            <!-- STEP 2: PASSWORD & AKUN  -->
            <!-- ======================== -->
            <div id="step2" class="step-panel hidden-panel">

                <!-- PASSWORD -->
                <div class="mb-4">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="lock" class="w-4 h-4"></i>
                        </span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Minimal 6 karakter"
                            autocomplete="new-password"
                            oninput="checkStrength(this.value)"
                            class="form-input w-full pl-11 pr-12 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                        <button type="button" onclick="toggleVis('password','eyePass')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50 hover:text-[#3d352f] transition-colors">
                            <i id="eyePass" data-feather="eye" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Strength bar -->
                    <div class="mt-2 h-1.5 bg-[#3d352f]/10 rounded-full overflow-hidden">
                        <div id="strengthBar" class="strength-bar h-full w-0 rounded-full bg-red-400"></div>
                    </div>
                    <p id="strengthLabel" class="text-xs mt-1 text-[#3d352f]/50">Masukkan password</p>

                    <!-- Rules -->
                    <ul class="mt-2 space-y-0.5 text-xs text-[#3d352f]/50">
                        <li class="rule-item flex items-center gap-1.5" id="rule-len">
                            <i data-feather="minus" class="rule-icon w-3 h-3"></i>
                            Minimal 6 karakter
                        </li>
                        <li class="rule-item flex items-center gap-1.5" id="rule-num">
                            <i data-feather="minus" class="rule-icon w-3 h-3"></i>
                            Mengandung angka
                        </li>
                        <li class="rule-item flex items-center gap-1.5" id="rule-upper">
                            <i data-feather="minus" class="rule-icon w-3 h-3"></i>
                            Mengandung huruf kapital
                        </li>
                    </ul>

                    <p class="error-msg text-red-500 text-xs mt-1 hidden" id="passwordError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i>
                        Password minimal 6 karakter
                    </p>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="mb-5">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        Konfirmasi Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="check-circle" class="w-4 h-4"></i>
                        </span>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="Ulangi password Anda"
                            autocomplete="new-password"
                            oninput="checkMatch()"
                            class="form-input w-full pl-11 pr-12 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                        <button type="button" onclick="toggleVis('password_confirmation','eyeConfirm')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50 hover:text-[#3d352f] transition-colors">
                            <i id="eyeConfirm" data-feather="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="error-msg text-red-500 text-xs mt-1 hidden" id="confirmError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i>
                        Password tidak cocok
                    </p>
                    <p class="match-msg text-green-600 text-xs mt-1 hidden" id="matchMsg">
                        <i data-feather="check" class="w-3 h-3 inline"></i>
                        Password cocok!
                    </p>
                </div>

                <!-- SYARAT & KETENTUAN -->
                <div class="mb-5">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" id="agree" name="agree"
                            class="w-4 h-4 mt-0.5 accent-[#3d352f] flex-shrink-0 cursor-pointer">
                        <span class="text-sm text-[#3d352f]/80 leading-relaxed">
                            Saya menyetujui
                            <button type="button" onclick="openModal('modalTerms')"
                                class="underline underline-offset-1 font-semibold text-[#3d352f] hover:text-[#6b5b4d] transition-colors">
                                Syarat & Ketentuan
                            </button>
                            serta
                            <button type="button" onclick="openModal('modalPrivacy')"
                                class="underline underline-offset-1 font-semibold text-[#3d352f] hover:text-[#6b5b4d] transition-colors">
                                Kebijakan Privasi
                            </button>
                            Dina Salon Muslimah
                        </span>
                    </label>
                    <p class="error-msg text-red-500 text-xs mt-1 hidden" id="agreeError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i>
                        Anda harus menyetujui syarat & ketentuan
                    </p>
                </div>

                <!-- BUTTON ROW -->
                <div class="flex gap-3">
                    <!-- Back -->
                    <button
                        type="button"
                        onclick="goToStep1()"
                        class="flex-none w-12 h-12 rounded-2xl
                            border border-[#3D352F]/40
                            bg-white/30 backdrop-blur-md
                            text-[#3d352f]
                            hover:bg-white/50 transition duration-300
                            flex items-center justify-center
                            shadow-sm">
                        <i data-feather="arrow-left" class="w-5 h-5"></i>
                    </button>

                    <!-- Daftar -->
                    <button
                        type="submit"
                        id="submitBtn"
                        onclick="return validateStep2()"
                        class="flex-1 py-3.5 rounded-2xl text-white text-base font-semibold
                            bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                            hover:scale-[1.02] active:scale-[0.98] transition duration-300
                            shadow-md flex items-center justify-center gap-2">
                        <i data-feather="user-check" class="w-5 h-5"></i>
                        Buat Akun
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<!-- SCRIPT -->
<script>
    feather.replace();

    // ========================
    // STEP NAVIGATION
    // ========================
    function goToStep2() {
        if (!validateStep1()) return;

        const s1 = document.getElementById('step1');
        const s2 = document.getElementById('step2');

        s1.classList.remove('active-panel');
        s1.classList.add('hidden-panel');
        s2.classList.remove('hidden-panel');
        s2.classList.add('active-panel');

        // Update dots
        document.getElementById('dot1').classList.remove('bg-[#3d352f]');
        document.getElementById('dot1').classList.add('w-2', 'bg-[#3d352f]/25');
        document.getElementById('dot2').classList.remove('bg-[#3d352f]/25', 'w-2');
        document.getElementById('dot2').classList.add('bg-[#3d352f]', 'w-8');

        feather.replace();
        document.getElementById('password').focus();
    }

    function goToStep1() {
        const s1 = document.getElementById('step1');
        const s2 = document.getElementById('step2');

        s2.classList.remove('active-panel');
        s2.classList.add('hidden-panel');
        s1.classList.remove('hidden-panel');
        s1.classList.add('active-panel');

        // Reset dots
        document.getElementById('dot1').classList.add('bg-[#3d352f]', 'w-8');
        document.getElementById('dot1').classList.remove('w-2', 'bg-[#3d352f]/25');
        document.getElementById('dot2').classList.remove('bg-[#3d352f]', 'w-8');
        document.getElementById('dot2').classList.add('bg-[#3d352f]/25', 'w-2');

        feather.replace();
    }

    // ========================
    // VALIDATE STEP 1
    // ========================
    function validateStep1() {
        let valid = true;

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Name
        if (!name) {
            showError('nameError', 'name');
            valid = false;
        } else {
            hideError('nameError', 'name');
        }

        // Email
        if (!email || !emailRx.test(email)) {
            showError('emailError', 'email');
            valid = false;
        } else {
            hideError('emailError', 'email');
        }

        return valid;
    }

    // ========================
    // VALIDATE STEP 2
    // ========================
    function validateStep2() {
        let valid = true;

        const pw = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const agree = document.getElementById('agree').checked;

        if (pw.length < 6) {
            showError('passwordError', 'password');
            valid = false;
        } else {
            hideError('passwordError', 'password');
        }

        if (pw !== confirm || confirm === '') {
            showError('confirmError', 'password_confirmation');
            document.getElementById('matchMsg').classList.add('hidden');
            valid = false;
        } else {
            hideError('confirmError', 'password_confirmation');
        }

        if (!agree) {
            showError('agreeError', null);
            valid = false;
        } else {
            hideError('agreeError', null);
        }

        if (!valid) return false;

        // Show loading state
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg> Memproses...`;

        return true;
    }

    // ========================
    // ERROR HELPERS
    // ========================
    function showError(errId, inputId) {
        const errEl = document.getElementById(errId);
        errEl.classList.remove('hidden');
        feather.replace();
        if (inputId) {
            const inp = document.getElementById(inputId);
            inp.classList.add('border-red-400');
            inp.classList.add('shake');
            setTimeout(() => inp.classList.remove('shake'), 400);
        }
    }

    function hideError(errId, inputId) {
        document.getElementById(errId).classList.add('hidden');
        if (inputId) {
            document.getElementById(inputId).classList.remove('border-red-400');
        }
    }

    // ========================
    // TOGGLE PASSWORD VISIBILITY
    // ========================
    function toggleVis(inputId, iconId) {
        const inp = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.setAttribute('data-feather', 'eye-off');
        } else {
            inp.type = 'password';
            icon.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    }

    // ========================
    // PASSWORD STRENGTH
    // ========================
    function checkStrength(val) {
        const bar = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');

        const hasLen    = val.length >= 6;
        const hasNum    = /\d/.test(val);
        const hasUpper  = /[A-Z]/.test(val);
        const hasSpecial = /[^A-Za-z0-9]/.test(val);

        // Update rules UI
        updateRule('rule-len',   hasLen);
        updateRule('rule-num',   hasNum);
        updateRule('rule-upper', hasUpper);

        let score = 0;
        if (hasLen)     score++;
        if (hasNum)     score++;
        if (hasUpper)   score++;
        if (hasSpecial) score++;

        const map = [
            { w: '0%',   color: 'bg-red-400',    text: 'Masukkan password' },
            { w: '25%',  color: 'bg-red-400',    text: 'Lemah' },
            { w: '50%',  color: 'bg-orange-400', text: 'Cukup' },
            { w: '75%',  color: 'bg-yellow-400', text: 'Kuat' },
            { w: '100%', color: 'bg-green-500',  text: 'Sangat Kuat 💪' },
        ];

        const entry = val.length === 0 ? map[0] : map[score];
        bar.style.width = entry.w;
        bar.className = `strength-bar h-full rounded-full ${entry.color}`;
        label.textContent = entry.text;
        label.className = `text-xs mt-1 ${score >= 3 ? 'text-green-600' : score >= 2 ? 'text-orange-500' : 'text-red-400'}`;
    }

    function updateRule(id, met) {
        const el = document.getElementById(id);
        const iconEl = el.querySelector('.rule-icon');
        if (met) {
            el.classList.add('met');
            iconEl.setAttribute('data-feather', 'check');
        } else {
            el.classList.remove('met');
            iconEl.setAttribute('data-feather', 'minus');
        }
        feather.replace();
    }

    // ========================
    // PASSWORD MATCH CHECK
    // ========================
    function checkMatch() {
        const pw = document.getElementById('password').value;
        const cf = document.getElementById('password_confirmation').value;

        if (cf === '') {
            document.getElementById('confirmError').classList.add('hidden');
            document.getElementById('matchMsg').classList.add('hidden');
            return;
        }

        if (pw === cf) {
            document.getElementById('confirmError').classList.add('hidden');
            document.getElementById('matchMsg').classList.remove('hidden');
            document.getElementById('password_confirmation').classList.remove('border-red-400');
        } else {
            document.getElementById('matchMsg').classList.add('hidden');
            document.getElementById('confirmError').classList.remove('hidden');
            feather.replace();
        }
    }

    // ========================
    // ENTER KEY NAVIGATION
    // ========================
    document.getElementById('name').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('email').focus(); }
    });
    document.getElementById('email').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('phone').focus(); }
    });
    document.getElementById('phone').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); goToStep2(); }
    });
    document.getElementById('password').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('password_confirmation').focus(); }
    });

    // If there are Laravel validation errors, go straight to step 2 if needed
    @if ($errors->has('password') || $errors->has('password_confirmation'))
        // Jump to step 2 if password errors
        document.addEventListener('DOMContentLoaded', () => goToStep2());
    @endif

</script>

<!-- ================================ -->
<!-- MODAL: SYARAT & KETENTUAN       -->
<!-- ================================ -->
<div id="modalTerms"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
    onclick="closeOnBackdrop(event, 'modalTerms')">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-[#3d352f]/40 backdrop-blur-sm"></div>

    <!-- Panel -->
    <div class="relative w-full max-w-lg max-h-[85vh] flex flex-col
        bg-white/80 backdrop-blur-2xl
        border border-[#3D352F]/30
        rounded-[32px]
        shadow-[0_16px_48px_rgba(61,53,47,0.3)]
        modal-panel">

        <!-- Header -->
        <div class="flex items-center justify-between px-8 pt-7 pb-4 border-b border-[#3d352f]/10 flex-shrink-0">
            <div>
                <h3 class="text-2xl font-bold text-[#3d352f]" style="font-family:'Playfair Display',serif;">
                    Syarat &amp; Ketentuan
                </h3>
                <p class="text-xs text-[#3d352f]/50 mt-0.5">Berlaku sejak 1 Januari 2025</p>
            </div>
            <button onclick="closeModal('modalTerms')"
                class="w-9 h-9 rounded-full bg-[#3d352f]/10 hover:bg-[#3d352f]/20
                    flex items-center justify-center transition-colors flex-shrink-0">
                <i data-feather="x" class="w-4 h-4 text-[#3d352f]"></i>
            </button>
        </div>

        <!-- Content (scrollable) -->
        <div class="overflow-y-auto px-8 py-5 space-y-5 text-sm text-[#3d352f]/80 leading-relaxed">

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">1. Penerimaan Syarat</h4>
                <p>Dengan mendaftar dan menggunakan layanan Dina Salon Muslimah, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku. Jika Anda tidak setuju, harap tidak melanjutkan proses pendaftaran.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">2. Akun Pengguna</h4>
                <p>Anda bertanggung jawab penuh atas kerahasiaan akun dan kata sandi Anda. Segala aktivitas yang terjadi melalui akun Anda adalah tanggung jawab Anda sepenuhnya. Segera hubungi kami jika Anda menduga terjadi penggunaan akun tanpa izin.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">3. Pemesanan & Pembatalan</h4>
                <p>Booking yang telah dikonfirmasi dapat dibatalkan maksimal <strong>2 jam sebelum</strong> jadwal layanan. Pembatalan mendadak tanpa pemberitahuan dapat memengaruhi prioritas jadwal Anda di masa mendatang.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">4. Pembayaran</h4>
                <p>Dina Salon Muslimah menerima pembayaran secara tunai (cash) dan QRIS. Bukti pembayaran wajib disimpan hingga layanan selesai. Kami tidak bertanggung jawab atas pembayaran yang dilakukan di luar kanal resmi kami.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">5. Hak & Kewajiban Pelanggan</h4>
                <p>Pelanggan berhak mendapatkan layanan sesuai yang telah dipesan dan dikonfirmasi. Pelanggan wajib hadir tepat waktu sesuai jadwal booking. Keterlambatan lebih dari 15 menit dapat menyebabkan jadwal dialihkan.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">6. Larangan</h4>
                <p>Pengguna dilarang menyalahgunakan sistem booking, memberikan informasi palsu, atau melakukan tindakan yang merugikan salon maupun pelanggan lain. Pelanggaran dapat mengakibatkan penonaktifan akun.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">7. Perubahan Syarat</h4>
                <p>Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui aplikasi atau email terdaftar Anda. Penggunaan layanan setelah perubahan dianggap sebagai persetujuan.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">8. Hubungi Kami</h4>
                <p>Jika ada pertanyaan mengenai syarat ini, silakan hubungi kami melalui WhatsApp atau kunjungi cabang kami di Jl. Tuasan No.76, Medan Tembung.</p>
            </section>
        </div>

        <!-- Footer -->
        <div class="px-8 py-5 border-t border-[#3d352f]/10 flex-shrink-0">
            <button onclick="acceptAndClose('modalTerms')"
                class="w-full py-3 rounded-2xl text-white font-semibold text-sm
                    bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                    hover:scale-[1.02] transition duration-300">
                Saya Mengerti &amp; Setuju
            </button>
        </div>
    </div>
</div>

<!-- ================================ -->
<!-- MODAL: KEBIJAKAN PRIVASI         -->
<!-- ================================ -->
<div id="modalPrivacy"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
    onclick="closeOnBackdrop(event, 'modalPrivacy')">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-[#3d352f]/40 backdrop-blur-sm"></div>

    <!-- Panel -->
    <div class="relative w-full max-w-lg max-h-[85vh] flex flex-col
        bg-white/80 backdrop-blur-2xl
        border border-[#3D352F]/30
        rounded-[32px]
        shadow-[0_16px_48px_rgba(61,53,47,0.3)]
        modal-panel">

        <!-- Header -->
        <div class="flex items-center justify-between px-8 pt-7 pb-4 border-b border-[#3d352f]/10 flex-shrink-0">
            <div>
                <h3 class="text-2xl font-bold text-[#3d352f]" style="font-family:'Playfair Display',serif;">
                    Kebijakan Privasi
                </h3>
                <p class="text-xs text-[#3d352f]/50 mt-0.5">Berlaku sejak 1 Januari 2025</p>
            </div>
            <button onclick="closeModal('modalPrivacy')"
                class="w-9 h-9 rounded-full bg-[#3d352f]/10 hover:bg-[#3d352f]/20
                    flex items-center justify-center transition-colors flex-shrink-0">
                <i data-feather="x" class="w-4 h-4 text-[#3d352f]"></i>
            </button>
        </div>

        <!-- Content (scrollable) -->
        <div class="overflow-y-auto px-8 py-5 space-y-5 text-sm text-[#3d352f]/80 leading-relaxed">

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">1. Data yang Kami Kumpulkan</h4>
                <p>Kami mengumpulkan data yang Anda berikan saat mendaftar, meliputi: nama lengkap, alamat email, nomor WhatsApp, dan data terkait riwayat booking layanan Anda.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">2. Tujuan Penggunaan Data</h4>
                <p>Data Anda kami gunakan untuk keperluan berikut:</p>
                <ul class="mt-2 space-y-1 pl-4">
                    <li class="flex items-start gap-2"><span class="text-[#c4a89a] mt-0.5">•</span> Mengelola akun dan riwayat booking Anda</li>
                    <li class="flex items-start gap-2"><span class="text-[#c4a89a] mt-0.5">•</span> Mengirimkan konfirmasi dan pengingat jadwal</li>
                    <li class="flex items-start gap-2"><span class="text-[#c4a89a] mt-0.5">•</span> Meningkatkan kualitas layanan kami</li>
                    <li class="flex items-start gap-2"><span class="text-[#c4a89a] mt-0.5">•</span> Mengirimkan informasi promo (jika Anda menyetujui)</li>
                </ul>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">3. Keamanan Data</h4>
                <p>Kami menerapkan enkripsi dan langkah-langkah keamanan standar industri untuk melindungi data Anda. Kata sandi Anda disimpan dalam bentuk terenkripsi dan tidak dapat dibaca oleh siapapun, termasuk tim kami.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">4. Berbagi Data dengan Pihak Ketiga</h4>
                <p>Kami <strong>tidak menjual</strong> data pribadi Anda kepada pihak manapun. Data hanya dibagikan kepada pihak ketiga yang diperlukan untuk operasional layanan (misalnya gateway pembayaran), dengan perjanjian kerahasiaan yang ketat.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">5. Hak Anda</h4>
                <p>Anda berhak untuk mengakses, memperbarui, atau menghapus data pribadi Anda kapan saja. Hubungi kami melalui WhatsApp atau email untuk mengajukan permintaan tersebut.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">6. Cookie & Data Sesi</h4>
                <p>Aplikasi kami menggunakan cookie untuk menjaga sesi login Anda tetap aktif dan meningkatkan pengalaman penggunaan. Cookie tidak digunakan untuk melacak aktivitas Anda di luar platform kami.</p>
            </section>

            <section>
                <h4 class="font-bold text-[#3d352f] mb-1.5">7. Kontak Privasi</h4>
                <p>Untuk pertanyaan terkait privasi data Anda, hubungi kami di:<br>
                📍 Jl. Tuasan No.76, Medan Tembung<br>
                📱 WhatsApp resmi Dina Salon Muslimah</p>
            </section>
        </div>

        <!-- Footer -->
        <div class="px-8 py-5 border-t border-[#3d352f]/10 flex-shrink-0">
            <button onclick="acceptAndClose('modalPrivacy')"
                class="w-full py-3 rounded-2xl text-white font-semibold text-sm
                    bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                    hover:scale-[1.02] transition duration-300">
                Saya Mengerti &amp; Setuju
            </button>
        </div>
    </div>
</div>

<style>
    /* Modal entrance animation */
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.94) translateY(16px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-panel { animation: modalIn 0.3s ease forwards; }

    /* Prevent body scroll when modal open */
    body.modal-open { overflow: hidden; }
</style>

<script>
    // ========================
    // MODAL FUNCTIONS
    // ========================
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('modal-open');
        feather.replace();
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('modal-open');
    }

    function closeOnBackdrop(event, id) {
        // Only close if clicking the backdrop (not the panel)
        if (event.target === document.getElementById(id) ||
            event.target === document.getElementById(id).querySelector('.absolute')) {
            closeModal(id);
        }
    }

    // Close modal AND check the agree checkbox
    function acceptAndClose(id) {
        closeModal(id);
        document.getElementById('agree').checked = true;
        hideError('agreeError', null);
    }

    // ESC key closes any open modal
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal('modalTerms');
            closeModal('modalPrivacy');
        }
    });
</script>

</body>
</html>