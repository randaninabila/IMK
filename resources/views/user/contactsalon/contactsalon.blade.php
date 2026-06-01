@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E8] via-[#FFF6F7] to-white text-[#3A372E] pt-[94px] pb-[110px]">

    <!-- HEADER -->
    <section class="px-[36px] md:px-[72px] pt-[50px]">
        <h1 class="text-[54px] md:text-[72px] font-extrabold tracking-[-0.04em] leading-none">
            Connect with Us
        </h1>
    </section>

    <!-- CONTENT -->
    <section class="px-[36px] md:px-[90px] mt-[52px]">
        <div class="max-w-[1100px] grid grid-cols-1 lg:grid-cols-[345px_1fr] gap-[40px]">

            <!-- LEFT COLUMN -->
            <div class="space-y-[28px]">

                <!-- PHONE CARD -->
                <div class="bg-white rounded-[28px] px-[40px] pt-[24px] pb-[52px] min-h-[212px]">
                    <div class="mb-[8px] text-[#854152]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-[28px] h-[28px]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5A2.25 2.25 0 0021 19.5v-2.086a1.5 1.5 0 00-1.017-1.42l-3.105-1.035a1.5 1.5 0 00-1.67.47l-.772.965a1.5 1.5 0 01-1.92.33 11.542 11.542 0 01-5.24-5.24 1.5 1.5 0 01.33-1.92l.965-.772a1.5 1.5 0 00.47-1.67L8.006 4.017A1.5 1.5 0 006.586 3H4.5A2.25 2.25 0 002.25 5.25v1.5z" />
                        </svg>
                    </div>

                    <h2 class="text-[23px] font-extrabold leading-none">
                        Phone
                    </h2>

                    <p class="text-[19px] font-extrabold text-[#854152] mt-[12px]">
                        +62 878 6959 0802
                    </p>

                    <p class="text-[16px] font-serif leading-[1.25] mt-[32px] text-[#3A372E]">
                        For immediate bookings & inquiries.
                    </p>
                </div>

                <!-- OPENING HOURS CARD -->
                <div class="bg-[#F8C7CE] rounded-[28px] px-[40px] pt-[20px] pb-[40px] min-h-[172px]">
                    <div class="flex items-center gap-[12px] mb-[14px]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-[28px] h-[28px] text-[#854152]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <h2 class="text-[23px] font-extrabold leading-none">
                            Opening Hours
                        </h2>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-[18px] font-extrabold text-black">
                            Open Daily
                        </p>

                        <p class="text-[19px] font-extrabold text-[#854152]">
                            09 AM — 08 PM
                        </p>
                    </div>

                    <p class="text-[16px] font-serif leading-[1.25] mt-[32px] text-[#3A372E]">
                        Excluding public holidays. Please call for seasonal adjustments.
                    </p>
                </div>

                <!-- INSTAGRAM CARD -->
                <div class="bg-white/80 rounded-[28px] px-[52px] pt-[26px] pb-[30px] min-h-[162px]">
                    <div class="flex items-center gap-[14px] mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-[30px] h-[30px] text-[#854152]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <rect x="3" y="3" width="18" height="18" rx="5" ry="5"></rect>
                            <circle cx="12" cy="12" r="4"></circle>
                            <circle cx="17.5" cy="6.5" r="1"></circle>
                        </svg>

                        <h2 class="text-[23px] font-extrabold leading-none">
                            Instagram
                        </h2>
                    </div>

                    <a href="https://www.instagram.com/dina_salon_muslimah"
                       target="_blank"
                       class="text-[19px] font-extrabold text-[#854152] hover:opacity-75 transition">
                        @dina_salon_muslimah
                    </a>

                    <p class="text-[16px] font-serif leading-[1.25] mt-[32px] text-black">
                        Daily inspiration & gallery.
                    </p>
                </div>

            </div>

            <!-- RIGHT COLUMN -->
            <div class="space-y-[28px]">

                <!-- WHATSAPP CARD -->
                <div class="bg-[#F8C7CE] rounded-[28px] px-[40px] md:px-[42px] py-[38px] min-h-[212px] flex items-center justify-between gap-[30px]">
                    <div>
                        <div class="mb-[12px] text-[#854152]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-[30px] h-[30px]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M7.5 8.25h9m-9 3.75H12m-7.5 7.5V6A2.25 2.25 0 016.75 3.75h10.5A2.25 2.25 0 0119.5 6v7.5a2.25 2.25 0 01-2.25 2.25H9l-4.5 3.75z" />
                            </svg>
                        </div>

                        <h2 class="text-[23px] font-extrabold leading-none">
                            WhatsApp
                        </h2>

                        <p class="text-[16px] font-serif leading-[1.25] mt-[34px] text-[#3A372E]">
                            Chat with our salon concierge.
                        </p>
                    </div>

                    <a href="https://wa.me/6287869590802"
                       target="_blank"
                       class="w-[300px] max-w-full bg-[#E95767] text-white rounded-full py-[15px] text-center text-[19px] font-extrabold hover:bg-[#d94c5b] transition">
                        Message via WhatsApp
                    </a>
                </div>

                <!-- LOCATION CARD -->
                <div class="bg-white rounded-[28px] px-[40px] py-[24px] min-h-[166px]">
                    <div class="flex items-center gap-[14px] mb-[28px]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-[30px] h-[30px] text-[#854152]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>

                        <h2 class="text-[23px] font-extrabold leading-none">
                            Location
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-[46px]">
                        <a href="https://www.google.com/maps/search/?api=1&query=Salon%20Muslimah%20Dina%20Jl.%20Perhubungan%20Laut%20Dendang%20Komplek%20Laudendang%20Village%20No.%206%20sebelah%20SPBU%20Laudendang"
                           target="_blank"
                           class="block hover:opacity-75 transition">
                            <h3 class="text-[18px] font-extrabold text-[#854152] mb-[8px]">
                                Cabang Laudendang
                            </h3>

                            <p class="text-[16px] leading-[1.15] text-black">
                                Jl. Perhubungan Laudendang<br>
                                (Depan SPBU Laudendang)
                            </p>
                        </a>

                        <a href="https://www.google.com/maps/search/?api=1&query=Salon%20Muslimah%20Dina%20Jl.%20Tuasan%20No.%2076D%20sebelah%20Masjid%20Nurul%20Muslimin"
                           target="_blank"
                           class="block hover:opacity-75 transition">
                            <h3 class="text-[18px] font-extrabold text-[#854152] mb-[8px]">
                                Cabang Tuasan
                            </h3>

                            <p class="text-[16px] leading-[1.15] text-black">
                                Jl. Tuasan No. 76 (Sebelah<br>
                                Masjid Nurul Muslimin)
                            </p>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

</div>

@endsection