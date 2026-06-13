<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Dina Salon Muslimah</title>
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

        /* Decorative floating circles */
        .blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,173,178,0.18);
            filter: blur(32px);
            pointer-events: none;
        }

        /* Step bar */
        .step-bar { transition: width 0.4s ease; }

        /* Info panel inside card */
        .info-strip {
            background: linear-gradient(135deg, rgba(61,53,47,0.06) 0%, rgba(107,91,77,0.08) 100%);
            border: 1px solid rgba(61,53,47,0.10);
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

            <div class="inline-flex items-center gap-2 bg-[#3d352f]/08 border border-[#3d352f]/20 rounded-full px-4 py-1.5 mb-6">
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
                    ['num' => '1', 'label' => 'Masukkan Email', 'active' => true],
                    ['num' => '2', 'label' => 'Verifikasi Kode OTP', 'active' => false],
                    ['num' => '3', 'label' => 'Buat Password Baru', 'active' => false],
                ] as $step)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                        {{ $step['active']
                            ? 'bg-[#3d352f] text-white shadow-md'
                            : 'bg-[#3d352f]/15 text-[#3d352f]/50' }}">
                        {{ $step['num'] }}
                    </div>
                    <span class="text-sm {{ $step['active'] ? 'text-[#3d352f] font-semibold' : 'text-[#3d352f]/45' }}">
                        {{ $step['label'] }}
                    </span>
                    @if($step['active'])
                    <div class="ml-auto w-12 h-1 rounded-full bg-[#3d352f]/20 overflow-hidden">
                        <div class="step-bar h-full w-1/3 bg-[#3d352f] rounded-full"></div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <p class="mt-10 text-sm text-[#3d352f]/70">
                Ingat password kamu?
                <a href="{{ route('login') }}" class="font-semibold text-[#3d352f] underline underline-offset-2 hover:text-[#6b5b4d] transition-colors">
                    Masuk di sini
                </a>
            </p>
        </div>

        <!-- ========================= -->
        <!-- CARD                      -->
        <!-- ========================= -->
        <div class="anim-card relative">

            <!-- Blobs inside card area -->
            <div class="blob w-48 h-48 -top-10 -right-10"></div>
            <div class="blob w-32 h-32 -bottom-8 -left-8"></div>

            <div class="relative bg-white/35 backdrop-blur-2xl border border-[#3D352F]/35
                rounded-[40px] p-8 md:p-10
                shadow-[0_12px_40px_rgba(61,53,47,0.20)]">

                <!-- Header -->
                <div class="mb-7">
                    <div class="w-14 h-14 rounded-2xl bg-[#3d352f]/08 border border-[#3d352f]/15 flex items-center justify-center mb-5">
                        <i data-feather="lock" class="w-6 h-6 text-[#3d352f]"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-[#3d352f]">Lupa Password?</h2>
                    <p class="text-[#7a6d65] text-sm mt-2 leading-relaxed">
                        Masukkan email terdaftar kamu. Kami akan kirimkan kode OTP 6 digit untuk verifikasi.
                    </p>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                <div data-alert class="mb-5 flex items-start gap-3 bg-green-50/70 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm">
                    <i data-feather="check-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if (session('info'))
                <div data-alert class="mb-5 flex items-start gap-3 bg-blue-50/70 border border-blue-200 text-blue-700 px-4 py-3 rounded-2xl text-sm">
                    <i data-feather="info" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('info') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div data-alert class="mb-5 flex items-start gap-3 bg-red-50/70 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm">
                    <i data-feather="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.send-otp') }}" id="forgotForm">
                    @csrf

                    <div class="mb-5">
                        <label for="email" class="block text-[#3d352f] text-sm font-semibold mb-2">
                            Alamat Email <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/45 pointer-events-none">
                                <i data-feather="mail" class="w-4 h-4"></i>
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="contoh@email.com"
                                class="input-field w-full pl-11 pr-4 py-3.5 rounded-xl
                                    border border-[#3D352F]/45
                                    bg-white/40 backdrop-blur-md
                                    text-[#3d352f] placeholder-[#3d352f]/35
                                    focus:outline-none text-sm">
                        </div>
                    </div>

                    <!-- Info strip -->
                    <div class="info-strip rounded-2xl px-4 py-3.5 mb-6 flex items-start gap-3">
                        <i data-feather="shield" class="w-4 h-4 text-[#6b5b4d] flex-shrink-0 mt-0.5"></i>
                        <div class="text-xs text-[#6b5b4d] leading-relaxed space-y-1">
                            <p><strong>Kode OTP berlaku 15 menit</strong> setelah dikirim.</p>
                            <p>Cek folder <strong>Spam</strong> jika tidak masuk ke inbox.</p>
                            <p>Maksimal <strong>3 permintaan</strong> per 10 menit per email.</p>
                        </div>
                    </div>

                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full py-4 rounded-2xl text-white text-base font-semibold
                            bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                            hover:scale-[1.02] active:scale-[0.98]
                            transition duration-300 shadow-md
                            flex items-center justify-center gap-2.5">
                        <i data-feather="send" class="w-4 h-4"></i>
                        Kirim Kode OTP
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-5">
                    <div class="flex-1 h-px bg-[#3d352f]/12"></div>
                    <span class="text-xs text-[#3d352f]/40">atau</span>
                    <div class="flex-1 h-px bg-[#3d352f]/12"></div>
                </div>

                <a href="{{ route('login') }}"
                    class="w-full py-3.5 rounded-2xl text-[#3d352f] text-sm font-semibold
                        border border-[#3d352f]/30 bg-white/20
                        hover:bg-white/40 transition duration-300
                        flex items-center justify-center gap-2">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Login
                </a>

            </div>
        </div>
    </div>

    <script>
        feather.replace();

        document.querySelectorAll('[data-alert]').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(() => el.remove(), 500);
            }, 4000);
        });

        document.getElementById('forgotForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Mengirim...`;
        });

        // Animate step bar
        setTimeout(() => {
            document.querySelector('.step-bar').style.width = '100%';
        }, 800);
    </script>
</body>
</html>