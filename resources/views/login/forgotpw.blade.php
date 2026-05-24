<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password - Dina Salon Muslimah</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font -->
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap"
    rel="stylesheet"
  />
</head>

<body class="min-h-screen flex items-center justify-center bg-[radial-gradient(circle_at_top_left,_#FFADB2_0%,_transparent_40%),radial-gradient(circle_at_bottom_right,_#FFADB2_0%,_transparent_40%),linear-gradient(to_bottom,_#FFE4E6_0%,_#ffffff_100%)]">

  <div class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 gap-10 items-center px-6">

    <!-- LEFT CONTENT -->
    <div>
      <h1
        class="text-5xl md:text-6xl font-bold text-[#3d352f]"
        style="font-family: 'Playfair Display', serif;"
      >
        Dina <span class="italic">Salon</span><br />
        Muslimah
      </h1>

      <p class="mt-6 text-[#3d352f] text-lg leading-relaxed max-w-md">
        Kecantikan alami dimulai dari perawatan terbaik.
        Kami hadir untuk membuat Anda tampil lebih percaya diri setiap hari.
      </p>
    </div>

    <!-- CARD -->
    <div class="bg-white/30 backdrop-blur-2xl border border-[#3D352F]/40 rounded-[40px] p-10 shadow-[0_8px_32px_rgba(61,53,47,0.25)]">

      <h2 class="text-4xl font-bold text-center text-[#3d352f] mb-3">
        Forgot Password
      </h2>

      <p class="text-center text-[#6b5b4d] mb-8">
        Masukkan email untuk menerima kode reset password
      </p>

      @if (session('status'))
      <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
          {{ session('status') }}
      </div>
      @endif

      @if ($errors->any())
      <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
          {{ $errors->first() }}
      </div>
      @endif

      <!-- FORM -->
      <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
          @csrf
        <!-- EMAIL -->
        <div>
          <label for="email" class="block mb-2 text-[#3d352f] font-medium">
            Email
          </label>

          <input
            id="email"
            name="email"
            type="email"
            required
            placeholder="Enter your email"
            class="w-full px-5 py-3 rounded-xl border border-[#3D352F]/50 bg-white/40 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-[#3D352F]"
          />
        </div>

        <!-- BUTTON -->
        <button
          type="submit"
          class="w-full py-4 rounded-2xl text-white text-lg font-semibold bg-gradient-to-r from-[#3d352f] to-[#6b5b4d] hover:scale-105 transition duration-300"
        >
          Send Code
        </button>

      </form>

      <!-- BACK -->
      <p class="text-center mt-6 text-[#3d352f]">
        Back to
        <a
          href="{{ url('/login') }}"
          class="font-semibold underline hover:text-[#6b5b4d]"
        >
          Log In
        </a>
      </p>

    </div>
  </div>

</body>
</html>