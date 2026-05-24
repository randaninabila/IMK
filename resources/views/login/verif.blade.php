<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <title>Verification - Dina Salon Muslimah</title>

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

      <h2 class="text-4xl md:text-5xl font-bold text-center text-[#3d352f] mb-3">
        Verification
      </h2>

      <p class="text-center text-[#6b5b4d] mb-8 text-lg">
        Enter the verification code to continue
      </p>

      <!-- FORM -->
      <form id="otpForm" action="/signin" method="GET">

        <!-- EMAIL INFO -->
        <div class="text-center mb-8">
          <p class="text-[#3d352f] text-lg">
            We sent a code to
          </p>

          <p class="text-[#3d352f] text-2xl font-bold mt-1">
            randenaiy@gmail.com
          </p>
        </div>

        <!-- OTP -->
        <div class="flex items-center justify-center gap-3 md:gap-5 mb-8">

          <input
            type="text"
            maxlength="1"
            inputmode="numeric"
            class="otp-input"
          />

          <input
            type="text"
            maxlength="1"
            inputmode="numeric"
            class="otp-input"
          />

          <input
            type="text"
            maxlength="1"
            inputmode="numeric"
            class="otp-input"
          />

          <input
            type="text"
            maxlength="1"
            inputmode="numeric"
            class="otp-input"
          />

          <input
            type="text"
            maxlength="1"
            inputmode="numeric"
            class="otp-input"
          />
        </div>

        <!-- BUTTON -->
        <button
          type="submit"
          class="w-full py-4 rounded-2xl text-white text-lg font-semibold bg-gradient-to-r from-[#3d352f] to-[#6b5b4d] hover:scale-105 transition duration-300"
        >
          Continue
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

  <!-- STYLE -->
  <style>
    .otp-input {
      width: 72px;
      height: 72px;
      border-radius: 18px;
      border: 1px solid #6b5b4d;
      background: #f7ecee;
      text-align: center;
      font-size: 30px;
      font-weight: 600;
      outline: none;
      transition: 0.2s;
      color: #3d352f;
    }

    .otp-input:focus {
      border-color: #3d352f;
      box-shadow: 0 0 0 3px rgba(61, 53, 47, 0.2);
    }

    @media (max-width: 640px) {
      .otp-input {
        width: 55px;
        height: 55px;
        font-size: 24px;
      }
    }
  </style>

  <!-- SCRIPT -->
  <script>
    const inputs = document.querySelectorAll(".otp-input");

    inputs.forEach((input, index) => {

      // AUTO NEXT
      input.addEventListener("input", (e) => {

        e.target.value = e.target.value.replace(/[^0-9]/g, "");

        if (e.target.value.length === 1) {
          if (inputs[index + 1]) {
            inputs[index + 1].focus();
          }
        }
      });

      // BACKSPACE
      input.addEventListener("keydown", (e) => {

        if (e.key === "Backspace" && input.value === "") {
          if (inputs[index - 1]) {
            inputs[index - 1].focus();
          }
        }

        // ENTER SUBMIT
        if (e.key === "Enter") {
          document.getElementById("otpForm").submit();
        }
      });

      // PASTE OTP
      input.addEventListener("paste", (e) => {

        e.preventDefault();

        const data = e.clipboardData
          .getData("text")
          .replace(/\D/g, "")
          .split("");

        inputs.forEach((box, i) => {
          box.value = data[i] || "";
        });

        const lastFilled = [...inputs].findIndex(
          (box) => box.value === ""
        );

        if (lastFilled !== -1) {
          inputs[lastFilled].focus();
        } else {
          inputs[inputs.length - 1].focus();
        }
      });
    });

    // AUTO FOCUS FIRST INPUT
    inputs[0].focus();
  </script>

</body>
</html>