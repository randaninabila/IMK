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
                Your Details
            </h1>

            <!-- PROGRESS -->
            <div class="hidden md:flex items-center gap-[5px] mt-[74px] mr-[0px]">
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F9B5C7]"></div>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="px-[36px] md:px-[60px] mt-[68px]">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_430px] gap-[28px] items-start">

            <!-- FORM CARD -->
            <form id="detailForm"
                  onsubmit="submitDetails(event)"
                  class="bg-white rounded-[44px] shadow-[0_20px_35px_rgba(0,0,0,0.12)] px-[40px] pt-[36px] pb-[42px]">

                <!-- FULL NAME -->
                <div>
                    <label for="fullName" class="block text-[24px] font-extrabold mb-[14px]">
                        Full Name
                    </label>

                    <input type="text"
                           id="fullName"
                           name="fullName"
                           placeholder="Enter your full name"
                           class="w-full h-[56px] bg-[#FCE6EA] rounded-[15px] px-[30px] text-[22px] font-medium placeholder:text-[#766E6E] focus:outline-none focus:ring-2 focus:ring-[#F47CA5]">
                </div>

                <!-- PHONE + EMAIL -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-[55px] mt-[40px]">
                    <div>
                        <label for="phoneNumber" class="block text-[24px] font-extrabold mb-[14px]">
                            Phone Number
                        </label>

                        <input type="text"
                               id="phoneNumber"
                               name="phoneNumber"
                               placeholder="+62 800 0000 0000"
                               class="w-full h-[56px] bg-[#FCE6EA] rounded-[15px] px-[30px] text-[22px] font-medium placeholder:text-[#766E6E] focus:outline-none focus:ring-2 focus:ring-[#F47CA5]">
                    </div>

                    <div>
                        <label for="emailAddress" class="block text-[24px] font-extrabold mb-[14px]">
                            Email Address
                        </label>

                        <input type="email"
                               id="emailAddress"
                               name="emailAddress"
                               placeholder="your.email@example.com"
                               class="w-full h-[56px] bg-[#FCE6EA] rounded-[15px] px-[30px] text-[22px] font-medium placeholder:text-[#766E6E] focus:outline-none focus:ring-2 focus:ring-[#F47CA5]">
                    </div>
                </div>

                <!-- NOTES -->
                <div class="mt-[40px]">
                    <label for="additionalNotes" class="block text-[24px] font-extrabold mb-[14px]">
                        Additional notes
                    </label>

                    <textarea id="additionalNotes"
                              name="additionalNotes"
                              placeholder="Any preferences, allergies, or special requests..."
                              class="w-full h-[120px] bg-[#FCE6EA] rounded-[15px] px-[30px] py-[16px] text-[22px] font-medium placeholder:text-[#766E6E] resize-none focus:outline-none focus:ring-2 focus:ring-[#F47CA5]"></textarea>
                </div>
            </form>

            <!-- BOOKING PREVIEW -->
            <div class="bg-white rounded-[44px] shadow-[0_20px_35px_rgba(0,0,0,0.14)] px-[68px] pt-[34px] pb-[32px] min-h-[160px]">
                <h2 class="text-[24px] font-extrabold mb-[18px]">
                    Booking Preview
                </h2>

                <p id="bookingPreviewText" class="text-[22px] font-semibold leading-[1.12] text-black">
                    -
                </p>
            </div>

        </div>
    </section>

</div>

<!-- BOTTOM BAR -->
<div class="fixed left-0 right-0 bottom-0 z-40 bg-[#FFF9FA]/95 backdrop-blur-sm border-t border-[#F4ECEE]">
    <div class="px-[36px] md:px-[80px] py-[30px] flex items-center justify-end gap-[22px]">

        <a href="{{ url('/time') }}"
           class="w-[165px] bg-[#E4C2C5] text-[#6F5D5D] rounded-full py-[18px] text-center text-[22px] font-extrabold hover:bg-[#d8b1b5] transition">
            <span class="mr-2">←</span>
            Back
        </a>

        <button type="submit"
                form="detailForm"
                class="w-[285px] bg-[#F8A9B4] text-[#3A372E] rounded-full py-[18px] text-center text-[22px] font-extrabold hover:bg-[#F47CA5] transition">
            Payment
            <span class="ml-2">→</span>
        </button>

    </div>
</div>

<script>
    function safeJsonParse(key) {
        try {
            return JSON.parse(localStorage.getItem(key) || 'null');
        } catch (error) {
            return null;
        }
    }

    function loadBookingPreview() {
        const previewText = document.getElementById('bookingPreviewText');

        const bookingTime = safeJsonParse('booking_time');
        const bookingLocation = safeJsonParse('booking_location');
        const bookingCart = safeJsonParse('booking_cart');

        const locationName = bookingLocation?.name || 'Tuasan';

        const serviceCount = Number(bookingCart?.count || 0);
        const totalPrice = bookingCart?.total || '0k';

        const dateText = bookingTime?.date || 'Belum pilih tanggal';
        const timeText = bookingTime?.time || 'Belum pilih jam';

        const serviceText = serviceCount === 1
            ? '1 service'
            : serviceCount + ' services';

        previewText.textContent = `${locationName} • ${serviceText} • ${dateText} • ${timeText} • ${totalPrice}`;
    }

    function submitDetails(event) {
        event.preventDefault();

        const fullName = document.getElementById('fullName').value.trim();
        const phoneNumber = document.getElementById('phoneNumber').value.trim();
        const emailAddress = document.getElementById('emailAddress').value.trim();
        const additionalNotes = document.getElementById('additionalNotes').value.trim();

        if (!fullName || !phoneNumber || !emailAddress) {
            alert('Full name, phone number, dan email wajib diisi dulu.');
            return;
        }

        const bookingDetails = {
            fullName: fullName,
            phoneNumber: phoneNumber,
            emailAddress: emailAddress,
            additionalNotes: additionalNotes
        };

        localStorage.setItem('booking_details', JSON.stringify(bookingDetails));

        window.location.href = "{{ url('/payment') }}";
    }

    loadBookingPreview();
</script>

@endsection