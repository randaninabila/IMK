@extends('user.app')

@section('content')

@php
    /*
        Untuk backend nanti:
        Controller tinggal kirim URL poster promo aktif ke view ini.

        Contoh:
        return view('user.dashboard.dashboard', [
            'promoPosterUrl' => asset('storage/' . $promo->poster_path)
        ]);

        Untuk frontend sementara:
        Kalau $promoPosterUrl belum ada, sistem akan ambil poster dari localStorage
        yang sebelumnya disimpan dari halaman admin input promo.
    */
    $promoPosterUrl = $promoPosterUrl ?? null;
@endphp

<div class="bg-white text-[#3A372E]">

    <!-- HERO SECTION -->
    <section class="relative min-h-[760px] bg-gradient-to-b from-[#FFE4E8] via-[#FFF3F5] to-white overflow-hidden">
        <div class="max-w-[1280px] mx-auto px-[72px] min-h-[680px] grid grid-cols-1 lg:grid-cols-2 items-center">

            <!-- LEFT -->
            <div class="pt-16 lg:pt-0 lg:mt-[-30px]">
                <h1 class="text-[58px] font-extrabold tracking-[0.13em] leading-[0.95]">
                    Glow your way,
                </h1>

                <h2 class="text-[28px] font-extrabold tracking-[-0.04em] mt-2 mb-[48px]">
                    The halal way
                </h2>

                <p class="text-[13px] mb-3">
                    <span class="bg-[#F4A1AC] px-[8px] py-[5px] rounded-[3px] text-[15px] font-extrabold">
                        Salon Muslimah Dina
                    </span>
                    <span class="text-[#3A372E]/80"> is your safe space to GLOW.</span>
                </p>

                <p class="max-w-[470px] text-[13px] leading-[1.25] text-[#3A372E]/90 mb-[26px]">
                    Salon khusus muslimah dengan ruang private dan nyaman,
                    menghadirkan perawatan profesional berbahan halal agar membuat
                    cantik, segar, dan percaya diri dengan tenang.
                </p>

                <a href="{{ url('/booking') }}"
                   class="inline-flex items-center bg-[#E8B8BC] rounded-full px-[20px] py-[9px] text-[13px] font-extrabold hover:bg-[#F4A1AC] transition">
                    Book your <em class="italic font-extrabold mx-1">appointment</em> NOW
                    <span class="ml-1 text-[16px]">→</span>
                </a>
            </div>

            <!-- RIGHT -->
            <div class="flex justify-center lg:justify-end mt-16 lg:mt-0">
                <div class="relative w-[500px] h-[470px]">

                    <!-- BACK CARD -->
                    <div class="absolute left-[2px] top-[118px] w-[355px] h-[355px] bg-[#DCDDD9] rounded-[24px] rotate-[-8deg] shadow-[0_22px_35px_rgba(0,0,0,0.22)]"></div>

                    <!-- PROMO BADGE -->
                    <button type="button"
                            onclick="openPromo()"
                            class="absolute left-[-125px] top-[205px] z-30 w-[108px] h-[108px] rounded-full bg-[#F4A1AC] rotate-[12deg] text-[18px] font-extrabold leading-tight tracking-[-0.05em] shadow-sm hover:scale-110 transition">
                        <span class="absolute left-[-25px] top-[0px] w-[46px] h-[46px] rounded-full bg-black text-white flex items-center justify-center text-[28px] font-extrabold rotate-[-12deg]">
                            %
                        </span>
                        Check<br>Discount!
                    </button>

                    <!-- MAIN IMAGE -->
                    <img src="{{ asset('assets/dashboard/hero.jpg') }}"
                         alt="Hijab Beauty"
                         class="absolute right-0 top-[40px] z-20 w-[405px] h-[405px] object-cover rounded-[24px] shadow-[0_18px_30px_rgba(0,0,0,0.20)]">
                </div>
            </div>
        </div>

        <button onclick="scrollToSection('about')"
                class="absolute bottom-[42px] left-1/2 -translate-x-1/2 text-[26px] leading-none hover:scale-110 transition">
            ˅
        </button>
    </section>

    <!-- ABOUT SECTION -->
    <section id="about" class="relative min-h-[670px] bg-gradient-to-b from-white to-[#FFF0F2]">
        <div class="max-w-[1280px] mx-auto px-[64px] pt-[88px] grid grid-cols-1 lg:grid-cols-2 gap-[54px] items-start">

            <!-- LEFT -->
            <div>
                <h2 class="inline-block bg-[#F4A1AC] px-[20px] py-[5px] rounded-[2px] text-[35px] font-extrabold tracking-[-0.05em] mb-[14px]">
                    About Us
                </h2>

                <p class="max-w-[580px] text-[13px] leading-[1.25] text-[#3A372E]/90 mb-[44px]">
                    Salon kami adalah ruang perawatan khusus muslimah yang mengutamakan
                    kenyamanan dan privasi. Dengan tenaga profesional dan produk halal,
                    kami menghadirkan layanan terbaik untuk membantu kamu tampil segar,
                    anggun, dan percaya diri dalam suasana yang tenang dan aman.
                </p>

                <img src="{{ asset('assets/dashboard/about.jpg') }}"
                     alt="Salon Room"
                     class="w-[590px] h-[320px] object-cover rounded-[13px] shadow-[0_12px_25px_rgba(0,0,0,0.18)]">
            </div>

            <!-- RIGHT -->
            <div class="pt-[126px] space-y-[55px]">
                <div class="flex gap-[25px] items-start">
                    <div class="w-[47px] h-[47px] bg-black rounded-full text-white flex items-center justify-center text-[27px] font-extrabold shrink-0">
                        ✓
                    </div>

                    <div>
                        <h3 class="text-[29px] font-extrabold tracking-[-0.055em] leading-none">
                            Muslim-Friendly Environment
                        </h3>
                        <p class="text-[13px] leading-[1.25] mt-[8px] text-[#3A372E]/85">
                            Lingkungan yang mengikuti prinsip syariah untuk kenyamanan dan ketenangan tamu muslimah.
                        </p>
                    </div>
                </div>

                <div class="flex gap-[25px] items-start">
                    <div class="w-[47px] h-[47px] bg-black rounded-full text-white flex items-center justify-center text-[27px] font-extrabold shrink-0">
                        ✓
                    </div>

                    <div>
                        <h3 class="text-[29px] font-extrabold tracking-[-0.055em] leading-none">
                            Privacy & Safety First
                        </h3>
                        <p class="text-[13px] leading-[1.25] mt-[8px] text-[#3A372E]/85">
                            Menjaga privasi penuh dengan area khusus wanita dan sistem keamanan yang terjamin.
                        </p>
                    </div>
                </div>

                <div class="flex gap-[25px] items-start">
                    <div class="w-[47px] h-[47px] bg-black rounded-full text-white flex items-center justify-center text-[27px] font-extrabold shrink-0">
                        ✓
                    </div>

                    <div>
                        <h3 class="text-[29px] font-extrabold tracking-[-0.055em] leading-none">
                            Comfortable & Modern Facilities
                        </h3>
                        <p class="text-[13px] leading-[1.25] mt-[8px] text-[#3A372E]/85">
                            Fasilitas lengkap, bersih, dan modern untuk memastikan pengalaman perawatan yang nyaman.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <button onclick="scrollToSection('treatment')"
                class="absolute bottom-[38px] left-1/2 -translate-x-1/2 text-[26px] leading-none hover:scale-110 transition">
            ˅
        </button>
    </section>

    <!-- TREATMENT SECTION -->
    <section id="treatment" class="bg-gradient-to-b from-[#FFF0F2] to-white pt-[94px] pb-[92px]">
        <div class="max-w-[1280px] mx-auto px-[64px]">

            <div class="flex justify-center mb-[72px]">
                <h2 class="bg-[#F4A1AC] px-[48px] py-[8px] rounded-[3px] text-[57px] font-extrabold tracking-[0.08em] leading-none">
                    Our Signature Treatment
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-[120px] justify-items-center">
                <div class="w-[285px] bg-[#F5EFE9] rounded-[9px] shadow-[0_8px_18px_rgba(0,0,0,0.18)] p-[19px] text-center hover:-translate-y-2 transition">
                    <img src="{{ asset('assets/dashboard/hair-spa.jpg') }}"
                         alt="Hair Spa"
                         class="w-full h-[205px] object-cover rounded-[7px]">

                    <h3 class="italic text-[29px] font-semibold tracking-[-0.05em] mt-[17px]">
                        Hair Spa
                    </h3>
                </div>

                <div class="w-[285px] bg-[#F5EFE9] rounded-[9px] shadow-[0_8px_18px_rgba(0,0,0,0.18)] p-[19px] text-center hover:-translate-y-2 transition">
                    <img src="{{ asset('assets/dashboard/manicure.jpg') }}"
                         alt="Manicure"
                         class="w-full h-[205px] object-cover rounded-[7px]">

                    <h3 class="italic text-[29px] font-semibold tracking-[-0.05em] mt-[17px]">
                        Manicure
                    </h3>
                </div>

                <div class="w-[285px] bg-[#F5EFE9] rounded-[9px] shadow-[0_8px_18px_rgba(0,0,0,0.18)] p-[19px] text-center hover:-translate-y-2 transition">
                    <img src="{{ asset('assets/dashboard/massage.jpg') }}"
                         alt="Massage"
                         class="w-full h-[205px] object-cover rounded-[7px]">

                    <h3 class="italic text-[29px] font-semibold tracking-[-0.05em] mt-[17px]">
                        Massage
                    </h3>
                </div>
            </div>

            <div class="flex justify-center items-center gap-[170px] mt-[66px]">
                <a href="{{ url('/booking') }}"
                   class="w-[235px] text-center bg-[#3A372E] text-white rounded-[11px] py-[12px] text-[13px] font-extrabold tracking-[0.04em] hover:opacity-85 transition">
                    BOOK APPOINTMENT
                </a>

                <a href="{{ url('/testimoni') }}"
                   class="w-[235px] text-center bg-white border border-[#3A372E] text-[#3A372E] rounded-[11px] py-[12px] text-[13px] font-extrabold tracking-[0.04em] hover:bg-[#FFF1F2] transition">
                    TESTIMONI
                </a>
            </div>
        </div>
    </section>

    {{-- TESTIMONI CENTER BUTTON SECTION --}}
    <section class="bg-white pt-[8px] pb-[72px]">
        <div class="max-w-[1280px] mx-auto px-[64px]">
            <div class="flex flex-col items-center justify-center text-center">
                <p class="mb-[14px] text-[13px] font-bold uppercase tracking-[0.18em] text-[#B85C6A]">
                    Cerita Pelanggan
                </p>

                <h2 class="mb-[18px] text-[34px] font-extrabold tracking-[-0.04em] text-[#3A372E]">
                    Lihat Pengalaman Klien Kami
                </h2>

                <p class="mb-[28px] max-w-[520px] text-[14px] leading-[1.55] font-medium text-[#3A372E]/70">
                    Temukan pengalaman pelanggan setelah menikmati layanan Salon Muslimah Dina.
                </p>

                <a href="{{ url('/testimoni') }}"
                   class="inline-flex items-center justify-center rounded-full bg-[#E8A9B4] px-[34px] py-[15px] text-[16px] font-extrabold text-white shadow-[0_14px_30px_rgba(232,169,180,0.28)] hover:bg-[#D995A1] hover:-translate-y-1 transition-all duration-300">
                    Lihat Testimoni
                    <span class="ml-2 text-[18px]">→</span>
                </a>
            </div>
        </div>
    </section>

</div>

<!-- SCROLL TOP BUTTON -->
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed right-[35px] bottom-[108px] z-40 w-[43px] h-[43px] rounded-full bg-black text-white text-[24px] flex items-center justify-center leading-none hover:scale-110 transition">
    ↑
</button>

<!-- PROMO POSTER MODAL -->
<div id="promoModal"
     onclick="closePromoByOverlay(event)"
     class="hidden fixed inset-0 z-[999] bg-black/45 items-center justify-center px-6">

    <div class="relative w-full max-w-[470px]">

        <!-- EXIT BUTTON DI LUAR POSTER -->
        <button type="button"
                onclick="closePromo()"
                class="absolute -right-[18px] -top-[18px] z-[1000] w-[42px] h-[42px] rounded-full bg-black text-white text-[28px] leading-none flex items-center justify-center font-medium shadow-lg hover:scale-110 transition">
            ×
        </button>

        <!-- POSTER CARD -->
        <div class="relative overflow-hidden rounded-[24px] bg-white shadow-2xl text-[#3A372E]">

            <!-- POSTER DARI ADMIN -->
            <img id="promoPosterImage"
                 src=""
                 alt="Poster Promo Salon Dina Muslimah"
                 class="hidden w-full max-h-[650px] object-cover">

            <!-- EMPTY STATE JIKA ADMIN BELUM INPUT POSTER -->
            <div id="promoEmptyState"
                 class="hidden px-[34px] py-[65px] text-center bg-gradient-to-b from-[#FFE4E8] via-[#FFF3F5] to-white">
                <div class="mx-auto mb-[20px] w-[88px] h-[88px] rounded-full bg-[#F4A1AC] flex items-center justify-center text-[48px] font-black text-white">
                    %
                </div>

                <h2 class="text-[32px] leading-none font-black tracking-[-0.04em]">
                    Belum Ada Promo
                </h2>

                <p class="mt-[16px] text-[15px] leading-[1.35] font-semibold text-[#3A372E]/80">
                    tidak ada promo untuk saat ini
                </p>

                <a href="{{ url('/booking') }}"
                   class="mt-[28px] inline-flex items-center justify-center rounded-full bg-[#F4A1AC] px-[28px] py-[11px] text-[14px] font-extrabold hover:bg-[#E8B8BC] transition">
                    Booking Sekarang
                    <span class="ml-2">→</span>
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    const backendPromoPosterUrl = @json($promoPosterUrl);

    window.openPromo = function () {
        const modal = document.getElementById('promoModal');
        const posterImage = document.getElementById('promoPosterImage');
        const emptyState = document.getElementById('promoEmptyState');

        if (!modal || !posterImage || !emptyState) return;

        const frontendSavedPoster = localStorage.getItem('dinaSalonPromoPoster');
        const activePoster = backendPromoPosterUrl || frontendSavedPoster;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (activePoster) {
            posterImage.src = activePoster;
            posterImage.classList.remove('hidden');
            emptyState.classList.add('hidden');
        } else {
            posterImage.src = '';
            posterImage.classList.add('hidden');
            emptyState.classList.remove('hidden');
        }
    }

    window.closePromo = function () {
        const modal = document.getElementById('promoModal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    window.closePromoByOverlay = function (event) {
        if (event.target.id === 'promoModal') {
            closePromo();
        }
    }

    window.scrollToSection = function (id) {
        const section = document.getElementById(id);

        if (!section) return;

        section.scrollIntoView({
            behavior: 'smooth'
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePromo();
        }
    });
</script>

@endsection