<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Dina Salon Muslimah</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Icon (Heroicons) -->
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="min-h-screen flex items-center justify-center 
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
<div class="bg-white/30 backdrop-blur-2xl 
border border-[#3D352F]/40 
rounded-[40px] p-10 
shadow-[0_8px_32px_rgba(61,53,47,0.25)]">

        <h2 class="text-5xl font-semibold text-center text-[#3d352f] mb-8"
            style="font-family: 'Playfair Display', serif;">
            Log In
        </h2>

        <!-- Email -->
        <div class="mb-5">
            <label class="block mb-2 text-[#3d352f]">Email</label>
            <input id="email" placeholder="Enter your Email"
class="w-full px-5 py-3 rounded-xl 
border border-[#3D352F]/50 
bg-white/40 backdrop-blur-md
focus:outline-none focus:ring-2 focus:ring-[#3D352F]">
        </div>

        <!-- Password -->
        <div class="mb-4 relative">
            <label class="block mb-2 text-[#3d352f]">Password</label>

            <input id="password" type="password" placeholder="Enter your Pass"
class="w-full px-5 py-3 rounded-xl 
border border-[#3D352F]/50 
bg-white/40 backdrop-blur-md
focus:outline-none focus:ring-2 focus:ring-[#3D352F]">

            <!-- Eye Icon -->
            <button type="button" onclick="togglePassword()"
                class="absolute right-4 top-[42px] text-gray-700">
                <i id="eyeIcon" data-feather="eye"></i>
            </button>
        </div>

        <!-- Remember + Forgot -->
        <div class="flex justify-between items-center text-sm text-[#3d352f] mb-8">
            <label class="flex items-center gap-2">
                <input type="checkbox" class="w-4 h-4">
                Remember me
            </label>
            <a href="#" class="hover:underline">Forgot Password ?</a>
        </div>

        <!-- LOGIN BUTTON -->
        <button id="loginBtn" onclick="goToDashboard()"
            class="w-full py-4 rounded-2xl text-white text-lg font-semibold
            bg-gradient-to-r from-[#3d352f] to-[#6b5b4d] hover:scale-105 transition">
            Log In
        </button>

        <!-- SIGNUP -->
        <p class="text-center mt-6 text-[#3d352f]">
            Dont have an Account ?
            <span onclick="goToRegister()" class="font-semibold underline cursor-pointer">
                Sign Up Here
            </span>
        </p>

    </div>
</div>

<script>
    feather.replace();

    // 🔒 Toggle Password
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

    const email = document.getElementById("email");
const password = document.getElementById("password");

// 👉 Enter di email → pindah ke password
email.addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        password.focus();
    }
});

// 👉 Enter di password → login otomatis
password.addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        goToDashboard();
    }
});

    // 🚀 Redirect Login
    function goToDashboard() {
        window.location.href = "dashboard.html"; // ganti sesuai route kamu
    }

    // 🚀 Redirect Register
    function goToRegister() {
        window.location.href = "register.html"; // beda halaman
    }
</script>

</body>
</html>