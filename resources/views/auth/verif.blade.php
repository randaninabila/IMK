<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP — Dina Salon Muslimah</title>
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

        /* OTP input styling */
        .otp-box {
            width: 58px;
            height: 68px;
            border-radius: 16px;
            border: 1.5px solid rgba(61,53,47,0.35);
            background: rgba(255,255,255,0.45);
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #3d352f;
            outline: none;
            transition: all 0.2s ease;
            font-family: 'DM Sans', sans-serif;
            backdrop-filter: blur(8px);
            caret-color: transparent;
        }
        .otp-box:focus {
            border-color: #3d352f;
            box-shadow: 0 0 0 3px rgba(61,53,47,0.15);
            background: rgba(255,255,255,0.70);
            transform: translateY(-2px);
        }
        .otp-box.filled {
            border-color: #3d352f;
            background: rgba(61,53,47,0.06);
        }
        .otp-box.error {
            border-color: #e57373;
            box-shadow: 0 0 0 3px rgba(229,115,115,0.15);
            animation: shake 0.35s ease;
        }
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%,60%  { transform: translateX(-5px); }
            40%,80%  { transform: translateX(5px); }
        }

        /* Countdown */
        .countdown-active { color: #3d352f; font-weight: 600; }
        .countdown-expired { color: #c0392b; }

        /* Resend button */
        .resend-btn {
            transition: all 0.2s ease;
        }
        .resend-btn:not(:disabled):hover {
            text-decoration: underline;
        }
        .resend-btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        /* Blob */
        .blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,173,178,0.18);
            filter: blur(32px);
            pointer-events: none;
        }

        /* Email badge */
        .email-badge {
            background: rgba(61,53,47,0.07);
            border: 1px solid rgba(61,53,47,0.15);
        }

        /* Progress step */
        .step-dot-active { background: #3d352f; }
        .step-dot-done   { background: #3d352f; opacity: 0.4; }
        .step-dot-next   { background: rgba(61,53,47,0.18); }

        @media (max-width: 480px) {
            .otp-box { width: 46px; height: 56px; font-size: 22px; border-radius: 12px; }
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
                    ['num' => '2', 'label' => 'Verifikasi Kode OTP', 'state' => 'active'],
                    ['num' => '3', 'label' => 'Buat Password Baru', 'state' => 'next'],
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

            <!-- Tips -->
            <div class="mt-8 max-w-xs bg-[#3d352f]/05 border border-[#3d352f]/12 rounded-2xl p-4 space-y-2">
                <p class="text-xs font-semibold text-[#3d352f] mb-2 flex items-center gap-1.5">
                    <i data-feather="info" class="w-3.5 h-3.5"></i>
                    Tips
                </p>
                <p class="text-xs text-[#6b5b4d] leading-relaxed">• Periksa folder <strong>Spam</strong> atau <strong>Promosi</strong> jika kode tidak masuk.</p>
                <p class="text-xs text-[#6b5b4d] leading-relaxed">• Jangan tutup halaman ini sebelum selesai verifikasi.</p>
                <p class="text-xs text-[#6b5b4d] leading-relaxed">• Kode hanya bisa digunakan <strong>satu kali</strong>.</p>
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
                <div class="text-center mb-7">
                    <!-- Icon -->
                    <div class="w-16 h-16 rounded-2xl bg-[#3d352f]/08 border border-[#3d352f]/15 flex items-center justify-center mx-auto mb-4">
                        <i data-feather="message-square" class="w-7 h-7 text-[#3d352f]"></i>
                    </div>

                    <h2 class="text-3xl font-bold text-[#3d352f]">Masukkan Kode OTP</h2>
                    <p class="text-[#7a6d65] text-sm mt-2 leading-relaxed">
                        Kode 6 digit telah dikirim ke email kamu
                    </p>

                    <!-- Email badge -->
                    @php $resetEmail = session('reset_email', ''); @endphp
                    @if($resetEmail)
                    <div class="email-badge inline-flex items-center gap-2 rounded-full px-4 py-1.5 mt-3">
                        <i data-feather="mail" class="w-3.5 h-3.5 text-[#6b5b4d]"></i>
                        <span class="text-sm font-semibold text-[#3d352f]">{{ $resetEmail }}</span>
                    </div>
                    @endif
                </div>

                <!-- Alerts -->
                @if (session('success'))
                <div class="mb-5 flex items-start gap-3 bg-green-50/70 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm">
                    <i data-feather="check-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if ($errors->has('otp'))
                <div class="mb-5 flex items-start gap-3 bg-red-50/70 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm">
                    <i data-feather="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>{{ $errors->first('otp') }}</span>
                </div>
                @endif

                <!-- OTP Form -->
                <form method="POST" action="{{ route('password.verify-otp') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">

                    <!-- Hidden assembled OTP -->
                    <input type="hidden" name="otp" id="otpHidden">

                    <!-- OTP Boxes -->
                    <div class="flex items-center justify-center gap-2.5 mb-6" id="otpBoxes">
                        @for ($i = 0; $i < 6; $i++)
                        <input
                            type="text"
                            maxlength="1"
                            inputmode="numeric"
                            pattern="[0-9]"
                            class="otp-box"
                            data-index="{{ $i }}"
                            autocomplete="off">
                        @endfor
                    </div>

                    <!-- Countdown -->
                    <div class="text-center mb-6">
                        <p class="text-sm text-[#7a6d65]">
                            Kode berlaku selama
                            <span id="countdown" class="countdown-active font-semibold">15:00</span>
                        </p>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        id="verifyBtn"
                        class="w-full py-4 rounded-2xl text-white text-base font-semibold
                            bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                            hover:scale-[1.02] active:scale-[0.98]
                            transition duration-300 shadow-md
                            flex items-center justify-center gap-2.5">
                        <i data-feather="check-circle" class="w-4 h-4"></i>
                        Verifikasi Kode
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-5">
                    <div class="flex-1 h-px bg-[#3d352f]/12"></div>
                    <span class="text-xs text-[#3d352f]/40">tidak menerima kode?</span>
                    <div class="flex-1 h-px bg-[#3d352f]/12"></div>
                </div>

                <!-- Resend -->
                <form method="POST" action="{{ route('password.resend-otp') }}" id="resendForm">
                    @csrf
                    <button
                        type="submit"
                        id="resendBtn"
                        disabled
                        class="resend-btn w-full py-3 rounded-2xl text-[#3d352f] text-sm font-semibold
                            border border-[#3d352f]/25 bg-white/15
                            flex items-center justify-center gap-2">
                        <i data-feather="refresh-cw" class="w-4 h-4"></i>
                        <span id="resendText">Kirim Ulang (<span id="resendTimer">60</span>s)</span>
                    </button>
                </form>

                <!-- Back -->
                <p class="text-center mt-5 text-sm text-[#3d352f]/60">
                    Email salah?
                    <a href="{{ route('password.request') }}" class="font-semibold text-[#3d352f] underline underline-offset-2 hover:text-[#6b5b4d] transition-colors">
                        Ubah email
                    </a>
                </p>

            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // ========================
        // OTP INPUT LOGIC
        // ========================
        const boxes = document.querySelectorAll('.otp-box');
        const otpHidden = document.getElementById('otpHidden');

        boxes.forEach((box, i) => {
            // Input
            box.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val.slice(-1);

                // Mark filled
                e.target.classList.toggle('filled', e.target.value !== '');

                // Remove error
                boxes.forEach(b => b.classList.remove('error'));

                // Advance
                if (val && i < boxes.length - 1) {
                    boxes[i + 1].focus();
                }

                syncOtp();
            });

            // Backspace
            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && i > 0) {
                    boxes[i - 1].focus();
                    boxes[i - 1].value = '';
                    boxes[i - 1].classList.remove('filled');
                    syncOtp();
                }

                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitOtp();
                }
            });

            // Paste
            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const data = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6).split('');
                boxes.forEach((b, idx) => {
                    b.value = data[idx] || '';
                    b.classList.toggle('filled', b.value !== '');
                });
                const lastFilled = [...boxes].findLastIndex(b => b.value !== '');
                if (lastFilled < boxes.length - 1) {
                    boxes[lastFilled + 1]?.focus();
                } else {
                    boxes[boxes.length - 1].focus();
                }
                syncOtp();
            });
        });

        function syncOtp() {
            otpHidden.value = [...boxes].map(b => b.value).join('');
        }

        function submitOtp() {
            const otp = [...boxes].map(b => b.value).join('');
            if (otp.length < 6) {
                boxes.forEach(b => b.classList.add('error'));
                setTimeout(() => boxes.forEach(b => b.classList.remove('error')), 400);
                return;
            }
            syncOtp();
            const btn = document.getElementById('verifyBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Memverifikasi...`;
            document.getElementById('otpForm').submit();
        }

        document.getElementById('otpForm').addEventListener('submit', function (e) {
            const otp = [...boxes].map(b => b.value).join('');
            if (otp.length < 6) {
                e.preventDefault();
                boxes.forEach(b => b.classList.add('error'));
                setTimeout(() => boxes.forEach(b => b.classList.remove('error')), 400);
                return;
            }
            syncOtp();
        });

        // Auto focus
        boxes[0].focus();

        // ========================
        // MAIN COUNTDOWN (15 min)
        // ========================
        let mainSeconds = 15 * 60;
        const countdownEl = document.getElementById('countdown');

        const mainTimer = setInterval(() => {
            mainSeconds--;
            if (mainSeconds <= 0) {
                clearInterval(mainTimer);
                countdownEl.textContent = 'Kadaluarsa';
                countdownEl.className = 'countdown-expired font-semibold';
                return;
            }
            const m = String(Math.floor(mainSeconds / 60)).padStart(2, '0');
            const s = String(mainSeconds % 60).padStart(2, '0');
            countdownEl.textContent = `${m}:${s}`;
        }, 1000);

        // ========================
        // RESEND COUNTDOWN (60s)
        // ========================
        let resendSeconds = 60;
        const resendBtn   = document.getElementById('resendBtn');
        const resendTimer = document.getElementById('resendTimer');
        const resendText  = document.getElementById('resendText');

        const resendInterval = setInterval(() => {
            resendSeconds--;
            resendTimer.textContent = resendSeconds;
            if (resendSeconds <= 0) {
                clearInterval(resendInterval);
                resendBtn.disabled = false;
                resendText.innerHTML = `Kirim Ulang Kode OTP`;
            }
        }, 1000);

        document.getElementById('resendForm').addEventListener('submit', function () {
            resendBtn.disabled = true;
            resendBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Mengirim...`;
        });

        // Shake on error from server
        @if($errors->has('otp'))
            boxes.forEach(b => {
                b.classList.add('error');
                setTimeout(() => b.classList.remove('error'), 400);
            });
        @endif
    </script>
</body>
</html>