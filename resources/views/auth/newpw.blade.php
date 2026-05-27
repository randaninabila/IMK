<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Baru — Dina Salon Muslimah</title>
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
        .anim-left  { animation: fadeLeft 0.65s ease forwards; }
        .anim-card  { animation: fadeUp  0.65s ease 0.1s forwards; opacity: 0; }

        .input-field {
            transition: all 0.2s ease;
        }
        .input-field:focus {
            background: rgba(255,255,255,0.65) !important;
            box-shadow: 0 0 0 3px rgba(61,53,47,0.12);
        }
        .input-field.error-border {
            border-color: #e57373 !important;
        }

        /* Strength bar */
        .strength-bar { transition: width 0.4s ease, background-color 0.4s ease; }

        /* Password rule items */
        .rule-item { transition: color 0.2s ease; }
        .rule-item.met { color: #4caf82; }
        .rule-item.met .rule-icon { color: #4caf82; }

        /* Blob */
        .blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,173,178,0.18);
            filter: blur(32px);
            pointer-events: none;
        }

        /* Match indicator */
        .match-indicator {
            transition: all 0.25s ease;
        }

        @keyframes successPop {
            0%   { transform: scale(0.9); opacity: 0; }
            60%  { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        .success-pop { animation: successPop 0.4s ease forwards; }
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
                <span class="text-[#3d352f] text-xs font-medium tracking-wide">Reset Password</span>
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
                <p class="text-xs text-[#3d352f]/50 uppercase tracking-widest font-medium mb-4">Proses Reset</p>

                @foreach([
                    ['num' => '1', 'label' => 'Masukkan Email', 'state' => 'done'],
                    ['num' => '2', 'label' => 'Verifikasi Kode OTP', 'state' => 'done'],
                    ['num' => '3', 'label' => 'Buat Password Baru', 'state' => 'active'],
                ] as $step)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                        {{ $step['state'] === 'active' ? 'bg-[#3d352f] text-white shadow-md'
                          : 'bg-[#3d352f]/40 text-white' }}">
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

            <!-- Tips -->
            <div class="mt-8 max-w-xs bg-[#3d352f]/05 border border-[#3d352f]/12 rounded-2xl p-4 space-y-2">
                <p class="text-xs font-semibold text-[#3d352f] mb-2 flex items-center gap-1.5">
                    <i data-feather="shield" class="w-3.5 h-3.5"></i>
                    Tips Password Kuat
                </p>
                <p class="text-xs text-[#6b5b4d] leading-relaxed">• Minimal <strong>8 karakter</strong></p>
                <p class="text-xs text-[#6b5b4d] leading-relaxed">• Kombinasi huruf besar, kecil, dan angka</p>
                <p class="text-xs text-[#6b5b4d] leading-relaxed">• Hindari tanggal lahir atau nama sendiri</p>
                <p class="text-xs text-[#6b5b4d] leading-relaxed">• Jangan gunakan password lama</p>
            </div>
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
                <div class="mb-7">
                    <div class="w-14 h-14 rounded-2xl bg-[#3d352f]/08 border border-[#3d352f]/15 flex items-center justify-center mb-5">
                        <i data-feather="key" class="w-6 h-6 text-[#3d352f]"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-[#3d352f]">Password Baru</h2>
                    <p class="text-[#7a6d65] text-sm mt-2 leading-relaxed">
                        Buat password baru yang kuat dan mudah kamu ingat.
                        Password lama tidak akan bisa digunakan lagi.
                    </p>
                </div>

                <!-- Alerts -->
                @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 bg-red-50/70 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm">
                    <i data-feather="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.update') }}" id="newpwForm">
                    @csrf

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-[#3d352f] text-sm font-semibold mb-2">
                            Password Baru <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/45 pointer-events-none">
                                <i data-feather="lock" class="w-4 h-4"></i>
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                oninput="checkStrength(this.value); checkMatch();"
                                class="input-field w-full pl-11 pr-12 py-3.5 rounded-xl
                                    border border-[#3D352F]/45
                                    bg-white/40 backdrop-blur-md
                                    text-[#3d352f] placeholder-[#3d352f]/35
                                    focus:outline-none text-sm">
                            <button type="button" onclick="toggleVis('password', 'eyePass')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3d352f]/45 hover:text-[#3d352f] transition-colors">
                                <i id="eyePass" data-feather="eye" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <!-- Strength bar -->
                        <div class="mt-2.5 h-1.5 bg-[#3d352f]/10 rounded-full overflow-hidden">
                            <div id="strengthBar" class="strength-bar h-full w-0 rounded-full bg-red-400"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <p id="strengthLabel" class="text-xs text-[#3d352f]/50">Masukkan password</p>
                        </div>

                        <!-- Rules -->
                        <ul class="mt-2.5 space-y-1 text-xs text-[#3d352f]/50">
                            <li class="rule-item flex items-center gap-1.5" id="rule-len">
                                <i data-feather="minus" class="rule-icon w-3 h-3 flex-shrink-0"></i>
                                Minimal 8 karakter
                            </li>
                            <li class="rule-item flex items-center gap-1.5" id="rule-upper">
                                <i data-feather="minus" class="rule-icon w-3 h-3 flex-shrink-0"></i>
                                Mengandung huruf kapital
                            </li>
                            <li class="rule-item flex items-center gap-1.5" id="rule-num">
                                <i data-feather="minus" class="rule-icon w-3 h-3 flex-shrink-0"></i>
                                Mengandung angka
                            </li>
                            <li class="rule-item flex items-center gap-1.5" id="rule-special">
                                <i data-feather="minus" class="rule-icon w-3 h-3 flex-shrink-0"></i>
                                Mengandung simbol (!@#$…) — opsional tapi disarankan
                            </li>
                        </ul>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-[#3d352f] text-sm font-semibold mb-2">
                            Konfirmasi Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/45 pointer-events-none">
                                <i data-feather="check-circle" class="w-4 h-4"></i>
                            </span>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                                oninput="checkMatch()"
                                class="input-field w-full pl-11 pr-12 py-3.5 rounded-xl
                                    border border-[#3D352F]/45
                                    bg-white/40 backdrop-blur-md
                                    text-[#3d352f] placeholder-[#3d352f]/35
                                    focus:outline-none text-sm">
                            <button type="button" onclick="toggleVis('password_confirmation', 'eyeConfirm')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3d352f]/45 hover:text-[#3d352f] transition-colors">
                                <i id="eyeConfirm" data-feather="eye" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <!-- Match feedback -->
                        <p class="match-indicator text-xs mt-1.5 hidden text-red-500 flex items-center gap-1" id="noMatch">
                            <i data-feather="x-circle" class="w-3 h-3 inline flex-shrink-0"></i>
                            Password tidak cocok
                        </p>
                        <p class="match-indicator text-xs mt-1.5 hidden text-green-600 flex items-center gap-1" id="yesMatch">
                            <i data-feather="check-circle" class="w-3 h-3 inline flex-shrink-0"></i>
                            Password cocok!
                        </p>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full py-4 rounded-2xl text-white text-base font-semibold
                            bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                            hover:scale-[1.02] active:scale-[0.98]
                            transition duration-300 shadow-md
                            flex items-center justify-center gap-2.5">
                        <i data-feather="save" class="w-4 h-4"></i>
                        Simpan Password Baru
                    </button>
                </form>

                <!-- Back to login -->
                <p class="text-center mt-5 text-sm text-[#3d352f]/55">
                    Batal?
                    <a href="{{ route('login') }}" class="font-semibold text-[#3d352f] underline underline-offset-2 hover:text-[#6b5b4d] transition-colors">
                        Kembali ke Login
                    </a>
                </p>

            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // ========================
        // TOGGLE VISIBILITY
        // ========================
        function toggleVis(inputId, iconId) {
            const inp  = document.getElementById(inputId);
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
            const bar   = document.getElementById('strengthBar');
            const label = document.getElementById('strengthLabel');

            const hasLen     = val.length >= 8;
            const hasUpper   = /[A-Z]/.test(val);
            const hasNum     = /\d/.test(val);
            const hasSpecial = /[^A-Za-z0-9]/.test(val);

            updateRule('rule-len',     hasLen);
            updateRule('rule-upper',   hasUpper);
            updateRule('rule-num',     hasNum);
            updateRule('rule-special', hasSpecial);

            let score = 0;
            if (hasLen)     score++;
            if (hasUpper)   score++;
            if (hasNum)     score++;
            if (hasSpecial) score++;

            const map = [
                { w: '0%',   color: 'bg-red-400',    text: 'Masukkan password',  cls: 'text-[#3d352f]/50' },
                { w: '25%',  color: 'bg-red-400',    text: 'Lemah',              cls: 'text-red-500'      },
                { w: '50%',  color: 'bg-orange-400', text: 'Cukup',              cls: 'text-orange-500'   },
                { w: '75%',  color: 'bg-yellow-400', text: 'Kuat',               cls: 'text-yellow-600'   },
                { w: '100%', color: 'bg-green-500',  text: 'Sangat Kuat 💪',     cls: 'text-green-600'    },
            ];

            const entry = val.length === 0 ? map[0] : map[score];
            bar.style.width = entry.w;
            bar.className   = `strength-bar h-full rounded-full ${entry.color}`;
            label.textContent  = entry.text;
            label.className    = `text-xs ${entry.cls}`;
        }

        function updateRule(id, met) {
            const el   = document.getElementById(id);
            const icon = el.querySelector('.rule-icon');
            if (met) {
                el.classList.add('met');
                icon.setAttribute('data-feather', 'check');
            } else {
                el.classList.remove('met');
                icon.setAttribute('data-feather', 'minus');
            }
            feather.replace();
        }

        // ========================
        // PASSWORD MATCH
        // ========================
        function checkMatch() {
            const pw  = document.getElementById('password').value;
            const cf  = document.getElementById('password_confirmation').value;
            const yes = document.getElementById('yesMatch');
            const no  = document.getElementById('noMatch');

            if (!cf) {
                yes.classList.add('hidden');
                no.classList.add('hidden');
                return;
            }

            if (pw === cf) {
                no.classList.add('hidden');
                yes.classList.remove('hidden');
                document.getElementById('password_confirmation').classList.remove('error-border');
            } else {
                yes.classList.add('hidden');
                no.classList.remove('hidden');
                document.getElementById('password_confirmation').classList.add('error-border');
            }

            feather.replace();
        }

        // ========================
        // FORM SUBMIT
        // ========================
        document.getElementById('newpwForm').addEventListener('submit', function (e) {
            const pw = document.getElementById('password').value;
            const cf = document.getElementById('password_confirmation').value;

            if (pw.length < 8 || pw !== cf) {
                e.preventDefault();
                if (pw !== cf) {
                    document.getElementById('password_confirmation').classList.add('error-border');
                }
                return;
            }

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Menyimpan...`;
        });

        // Enter key nav
        document.getElementById('password').addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('password_confirmation').focus(); }
        });
    </script>
</body>
</html>