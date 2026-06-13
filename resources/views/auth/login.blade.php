<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Dina Salon Muslimah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body
    class="min-h-screen flex items-center justify-center
    bg-[radial-gradient(circle_at_top_left,_#FFADB2_0%,_transparent_40%),radial-gradient(circle_at_bottom_right,_#FFADB2_0%,_transparent_40%),linear-gradient(to_bottom,_#FFE4E6_0%,_#ffffff_100%)]">

    <div class="w-full max-w-6xl grid grid-cols-2 gap-10 items-center px-6">

        <!-- LEFT -->
        <div>
            <h1 class="text-6xl font-bold text-[#3d352f]" style="font-family: 'Playfair Display', serif;">
                Dina <span class="italic">Salon</span><br>Muslimah
            </h1>
            <p class="mt-6 text-[#3d352f] text-lg leading-relaxed max-w-md">
                Kecantikan alami dimulai dari perawatan terbaik.
                Kami hadir untuk membuat Anda tampil lebih percaya diri setiap hari.
            </p>
        </div>

        <!-- LOGIN CARD -->
        <div class="bg-white/30 backdrop-blur-2xl border border-[#3D352F]/40 rounded-[40px] p-10 shadow-[0_8px_32px_rgba(61,53,47,0.25)]">

            <h2 class="text-5xl font-bold text-center text-[#3d352f] mb-8">Masuk</h2>

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
            <div data-alert class="mb-4 bg-red-100 text-red-600 text-sm px-4 py-3 rounded-xl">
                {{ $errors->first() }}
            </div>
            @endif

            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))
            <div data-alert class="mb-4 bg-green-50 text-green-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
                <i data-feather="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <label class="block mb-2 text-[#3d352f]">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}"
                        placeholder="Enter your Email"
                        class="w-full px-5 py-3 rounded-xl border border-[#3D352F]/50 bg-white/40 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-[#3D352F]">
                </div>

                <!-- Password -->
                <div class="mb-4 relative">
                    <label class="block mb-2 text-[#3d352f]">Password</label>
                    <input id="password" name="password" type="password"
                        placeholder="Enter your Password"
                        class="w-full px-5 py-3 rounded-xl border border-[#3D352F]/50 bg-white/40 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-[#3D352F]">
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-4 top-[42px] text-gray-700">
                        <i id="eyeIcon" data-feather="eye"></i>
                    </button>
                </div>

                <!-- Remember + Forgot -->
                <div class="flex justify-between items-center text-sm text-[#3d352f] mb-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="w-4 h-4">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="hover:underline">
                        Forgot Password ?
                    </a>
                </div>

                <!-- LOGIN BUTTON -->
                <button type="submit"
                    class="w-full py-4 rounded-2xl text-white text-lg font-semibold bg-gradient-to-r from-[#3d352f] to-[#6b5b4d] hover:scale-105 transition">
                    Masuk
                </button>
            </form>

            <!-- DIVIDER -->
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-[#3d352f]/20"></div>
                <span class="text-sm text-[#3d352f]/50">atau</span>
                <div class="flex-1 h-px bg-[#3d352f]/20"></div>
            </div>

            <!-- GOOGLE LOGIN BUTTON -->
            <a href="{{ route('auth.google') }}"
                class="flex items-center justify-center gap-3 w-full py-3.5 rounded-2xl
                    border border-[#3D352F]/40 bg-white/50 backdrop-blur-md
                    text-[#3d352f] font-semibold text-base
                    hover:bg-white/70 hover:scale-[1.02] transition duration-300
                    shadow-sm">
                <!-- Google SVG icon -->
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Lanjutkan dengan Google
            </a>

            <!-- SIGNUP -->
            <p class="text-center mt-5 text-[#3d352f]">
                Belum memiliki akun ?
                <a href="{{ route('register') }}" class="font-semibold underline cursor-pointer">
                    Buat Akun
                </a>
            </p>

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

    function togglePassword() {
        const password = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");
        if (password.type === "password") {
            password.type = "text";
            icon.setAttribute("data-feather", "eye-off");
        } else {
            password.type = "password";
            icon.setAttribute("data-feather", "eye");
        }
        feather.replace();
    }
    </script>

</body>
</html>