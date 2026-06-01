@extends('user.app')

@section('content')

<style>
    footer {
        display: none !important;
    }
</style>

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E8] via-[#FFF6F7] to-white text-[#3A372E] pb-[150px] pt-[72px]">

    <!-- HEADER -->
    <section class="px-[36px] md:px-[42px] pt-[54px]">
        <div class="flex items-start justify-between">
            <h1 class="text-[54px] md:text-[76px] font-extrabold tracking-[-0.04em] leading-none">
                Choose Your Sanctuary
            </h1>

            <!-- PROGRESS -->
            <div class="hidden md:flex items-center gap-[5px] mt-[74px] mr-[0px]">
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F9B5C7]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F9B5C7]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F9B5C7]"></div>
            </div>
        </div>
    </section>

    <!-- LOCATION CARDS -->
    <section class="px-[36px] md:px-[60px] mt-[72px]">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[80px]">

            <!-- CARD 1 -->
            <button type="button"
                    onclick="selectLocation('laudendang')"
                    id="card-laudendang"
                    class="location-card text-left bg-white rounded-[22px] overflow-hidden shadow-sm transition hover:-translate-y-1">

                <div class="h-[220px] bg-[#F47CA5]"></div>

                <div class="px-[50px] pt-[24px] pb-[22px]">
                    <div class="flex items-start justify-between gap-5">
                        <div>
                            <h2 class="text-[26px] font-extrabold leading-tight">
                                Cabang Laudendang
                            </h2>

                            <p class="text-[24px] font-medium leading-[1.08] mt-[4px]">
                                Jl. Perhubungan<br>
                                Laudendang
                            </p>

                            <p class="text-[13px] font-extrabold mt-[6px]">
                                Depan SPBU Laudendang
                            </p>
                        </div>

                        <div class="mt-[4px] bg-[#FFF2F3] rounded-full px-[13px] py-[6px] flex items-center gap-[6px] shrink-0">
                            <span class="w-[12px] h-[12px] bg-[#91B56C] rounded-full"></span>
                            <span class="text-[13px] font-medium">
                                Slots Available Today
                            </span>
                        </div>
                    </div>

                    <div class="h-px bg-[#D7D1CE] mt-[9px] mb-[10px]"></div>

                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-[24px] font-medium leading-[1.05]">
                                Opening<br>
                                Hours
                            </h3>

                            <p class="text-[13px] font-extrabold mt-[9px]">
                                Daily 09 AM - 08 PM
                            </p>
                        </div>

                        <div class="w-[170px]">
                            <h3 class="text-[24px] font-medium leading-none mb-[10px]">
                                Map Preview
                            </h3>

                            <a id="map-link-laudendang"
                               href="https://www.google.com/maps/search/?api=1&query=Salon%20Muslimah%20Dina%20Jl.%20Perhubungan%20Laut%20Dendang%20Komplek%20Laudendang%20Village%20No.%206%20sebelah%20SPBU%20Laudendang"
                               target="_blank"
                               onclick="event.stopPropagation()"
                               class="block w-[130px] h-[90px] rounded-[10px] overflow-hidden relative shadow-sm">
                                <iframe
                                    src="https://maps.google.com/maps?q=Salon%20Muslimah%20Dina%20Jl.%20Perhubungan%20Laut%20Dendang%20Komplek%20Laudendang%20Village%20No.%206%20sebelah%20SPBU%20Laudendang&t=&z=16&ie=UTF8&iwloc=&output=embed"
                                    class="w-full h-full pointer-events-none"
                                    loading="lazy">
                                </iframe>

                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="text-[34px] drop-shadow-md">📍</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </button>

            <!-- CARD 2 -->
            <button type="button"
                    onclick="selectLocation('tuasan')"
                    id="card-tuasan"
                    class="location-card text-left bg-white rounded-[22px] overflow-hidden shadow-sm transition hover:-translate-y-1">

                <div class="h-[220px] bg-[#F47CA5]"></div>

                <div class="px-[50px] pt-[24px] pb-[22px]">
                    <div class="flex items-start justify-between gap-5">
                        <div>
                            <h2 class="text-[26px] font-extrabold leading-tight">
                                Cabang Tuasan
                            </h2>

                            <p class="text-[24px] font-medium leading-[1.08] mt-[4px]">
                                Jl. Tuasan No. 76
                            </p>

                            <p class="text-[13px] font-extrabold mt-[6px]">
                                Sebelah Masjid Nurul Muslimin
                            </p>
                        </div>

                        <div class="mt-[4px] bg-[#FFF2F3] rounded-full px-[13px] py-[6px] flex items-center gap-[6px] shrink-0">
                            <span class="w-[12px] h-[12px] bg-[#91B56C] rounded-full"></span>
                            <span class="text-[13px] font-medium">
                                Slots Available Today
                            </span>
                        </div>
                    </div>

                    <div class="h-px bg-[#D7D1CE] mt-[12px] mb-[10px]"></div>

                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-[24px] font-medium leading-[1.05]">
                                Opening<br>
                                Hours
                            </h3>

                            <p class="text-[13px] font-extrabold mt-[9px]">
                                Daily 09 AM - 08 PM
                            </p>
                        </div>

                        <div class="w-[170px]">
                            <h3 class="text-[24px] font-medium leading-none mb-[10px]">
                                Map Preview
                            </h3>

                            <a id="map-link-tuasan"
                               href="https://www.google.com/maps/search/?api=1&query=Salon%20Muslimah%20Dina%20Jl.%20Tuasan%20No.%2076D%20sebelah%20Masjid%20Nurul%20Muslimin"
                               target="_blank"
                               onclick="event.stopPropagation()"
                               class="block w-[130px] h-[90px] rounded-[10px] overflow-hidden relative shadow-sm">
                                <iframe
                                    src="https://maps.google.com/maps?q=Salon%20Muslimah%20Dina%20Jl.%20Tuasan%20No.%2076D%20sebelah%20Masjid%20Nurul%20Muslimin&t=&z=16&ie=UTF8&iwloc=&output=embed"
                                    class="w-full h-full pointer-events-none"
                                    loading="lazy">
                                </iframe>

                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="text-[34px] drop-shadow-md">📍</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </button>

        </div>
    </section>

</div>

<!-- BOTTOM BAR -->
<div class="fixed left-0 right-0 bottom-0 z-40 bg-white/95 backdrop-blur-sm border-t border-[#F4ECEE]">
    <div class="px-[36px] md:px-[80px] py-[30px] flex items-center justify-between">

        <div>
            <p class="text-[22px] font-extrabold leading-none">
                Selected Location
            </p>

            <p id="selectedLocationText" class="text-[34px] font-extrabold tracking-[0.01em] mt-[10px] leading-none text-black">
                Jl. Tuasan No. 76
            </p>
        </div>

        <div class="flex items-center gap-[22px]">
            <a href="{{ url('/booking') }}"
               class="w-[165px] bg-[#E4C2C5] text-[#6F5D5D] rounded-full py-[18px] text-center text-[22px] font-extrabold hover:bg-[#d8b1b5] transition">
                <span class="mr-2">←</span>
                Back
            </a>

            <a id="continueLocationBtn"
               href="{{ url('/time') }}"
               class="w-[285px] bg-[#F8A9B4] text-[#3A372E] rounded-full py-[18px] text-center text-[22px] font-extrabold hover:bg-[#F47CA5] transition">
                Continue
                <span class="ml-2">→</span>
            </a>
        </div>

    </div>
</div>

<script>
    const locations = {
        laudendang: {
            label: 'Laudendang',
            name: 'Jl. Perhubungan Laudendang',
            maps: 'https://www.google.com/maps/search/?api=1&query=Salon%20Muslimah%20Dina%20Jl.%20Perhubungan%20Laut%20Dendang%20Komplek%20Laudendang%20Village%20No.%206%20sebelah%20SPBU%20Laudendang'
        },
        tuasan: {
            label: 'Tuasan',
            name: 'Jl. Tuasan No. 76',
            maps: 'https://www.google.com/maps/search/?api=1&query=Salon%20Muslimah%20Dina%20Jl.%20Tuasan%20No.%2076D%20sebelah%20Masjid%20Nurul%20Muslimin'
        }
    };

    let selectedLocation = 'tuasan';

    function selectLocation(key) {
        selectedLocation = key;

        const selectedText = document.getElementById('selectedLocationText');

        selectedText.textContent = locations[key].name;

        const bookingLocation = {
            key: key,
            name: locations[key].label,
            address: locations[key].name,
            maps: locations[key].maps
        };

        localStorage.setItem('booking_location', JSON.stringify(bookingLocation));

        updateActiveLocation();
    }

    function updateActiveLocation() {
        document.querySelectorAll('.location-card').forEach((card) => {
            card.classList.remove('ring-4', 'ring-[#F47CA5]');
        });

        const activeCard = document.getElementById('card-' + selectedLocation);

        if (activeCard) {
            activeCard.classList.add('ring-4', 'ring-[#F47CA5]');
        }
    }

    selectLocation(selectedLocation);
</script>

@endsection