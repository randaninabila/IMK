<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dina Salon Muslimah</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[radial-gradient(circle_at_top_left,_#FFADB2_0%,_transparent_40%),radial-gradient(circle_at_bottom_right,_#FFADB2_0%,_transparent_40%),linear-gradient(to_bottom,_#FFE4E6_0%,_#ffffff_100%)] overflow-hidden">

    <div class="min-h-screen flex items-center justify-center px-6">

        <!-- CONTAINER -->
        <div class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

            <!-- LEFT CONTENT -->
            <div>
                <h1 class="text-5xl md:text-6xl font-bold text-[#3d352f]"
                    style="font-family: 'Playfair Display', serif;">

                    Dina <span class="italic">Salon</span><br>
                    Muslimah
                </h1>

                <p class="mt-6 text-[#3d352f] text-lg leading-relaxed max-w-md">
                    Kecantikan alami dimulai dari perawatan terbaik.
                    Kami hadir untuk membuat Anda tampil lebih percaya diri setiap hari.
                </p>
            </div>

            <!-- SIGN IN CARD -->
            <div class="w-full max-w-xl mx-auto
                bg-white/30 backdrop-blur-2xl
                border border-[#3D352F]/40
                rounded-[40px]
                shadow-[0_8px_32px_rgba(61,53,47,0.25)]
                px-12 py-10">

                <h2 class="text-5xl font-bold text-center text-[#3d352f] mb-8">
                    Sign In
                </h2>

                <!-- FORM -->
                <form class="space-y-4">

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-[16px] text-[#3E3328] mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            placeholder="Enter your Email"

                            class="w-full h-[52px]
                            rounded-[18px]
                            border border-[#5B4A3B]
                            bg-white/40 backdrop-blur-md
                            px-5 text-[14px]
                            outline-none
                            focus:ring-2 focus:ring-[#8B6B61]">
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-[16px] text-[#3E3328] mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            placeholder="Enter your Password"

                            class="w-full h-[52px]
                            rounded-[18px]
                            border border-[#5B4A3B]
                            bg-white/40 backdrop-blur-md
                            px-5 text-[14px]
                            outline-none
                            focus:ring-2 focus:ring-[#8B6B61]">
                    </div>

                    <!-- USERNAME -->
                    <div>
                        <label class="block text-[16px] text-[#3E3328] mb-2">
                            Username
                        </label>

                        <input
                            type="text"
                            placeholder="Enter your Username"

                            class="w-full h-[52px]
                            rounded-[18px]
                            border border-[#5B4A3B]
                            bg-white/40 backdrop-blur-md
                            px-5 text-[14px]
                            outline-none
                            focus:ring-2 focus:ring-[#8B6B61]">
                    </div>

                    <!-- GENDER -->
<div>
    <label class="block text-[16px] text-[#3E3328] mb-2">
        Gender
    </label>

    <select
        class="w-full h-[52px]
        rounded-[18px]
        border border-[#5B4A3B]
        bg-white/40 backdrop-blur-md
        px-5 text-[14px]
        outline-none
        focus:ring-2 focus:ring-[#8B6B61] mb-4">

        <option value="" disabled selected>
            Select Gender
        </option>

        <option value="pria">
            Pria
        </option>

        <option value="wanita">
            Wanita
        </option>

    </select>
</div>

                    <!-- BUTTON -->
                    <button
                        type="button"
                        onclick="goToDashboard()"

                        class="w-full py-4 mt-8
                        rounded-2xl
                        text-white text-lg font-semibold
                        bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                        hover:scale-105 transition duration-300">

                        Sign In
                    </button>

                </form>

            </div>

        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function goToDashboard() {
            window.location.href = "/dashboarde";
        }
    </script>

</body>
</html>