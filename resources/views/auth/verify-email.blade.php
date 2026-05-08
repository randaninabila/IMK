<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-[#F8F5F2] flex items-center justify-center px-6">

    <div class="w-full max-w-md bg-white rounded-[32px] shadow-xl border border-[#E8DED4] overflow-hidden">

        {{-- TOP ACCENT --}}
        <div class="h-2 bg-[#3E382D]"></div>

        <div class="p-8">

            {{-- ICON --}}
            <div class="w-24 h-24 mx-auto rounded-full bg-[#F5EAEA] flex items-center justify-center mb-6">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-11 h-11 text-[#3E382D]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.7">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 12l2 2 4-4m5-1a9 9 0 11-18 0 9 9 0 0118 0z" />

                </svg>

            </div>

            {{-- TITLE --}}
            <h1 class="text-3xl font-bold text-center text-[#3E382D] mb-3">
                Verifikasi Akun
            </h1>

            {{-- DESCRIPTION --}}
            <p class="text-sm leading-relaxed text-center text-gray-500 mb-8">
                Akun kamu berhasil dibuat dan hampir siap digunakan.
                <br><br>
                Untuk tahap development, verifikasi bisa dilakukan langsung tanpa email asli.
            </p>

            {{-- VERIFY BUTTON --}}
            <form method="POST" action="/fake-verify-email">
                @csrf

                <button type="submit"
                    class="w-full bg-[#3E382D] text-white py-3 rounded-2xl font-medium shadow-md hover:opacity-90 transition duration-200">

                    Verifikasi Sekarang
                </button>
            </form>

            {{-- INFO --}}
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400">
                    Development Mode • Fake Email Verification Enabled
                </p>
            </div>

        </div>

    </div>

</body>
</html>