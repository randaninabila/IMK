@extends('user.app')

@section('content')

@php
    $testimonials = $testimonials ?? collect();
    $faqs = $faqs ?? [];
@endphp

<div class="min-h-screen bg-gradient-to-b from-[#FBE7EA] via-[#FFF4F5] to-[#FFFFFF] text-[#3A372E] pt-[94px]">

    {{-- TESTIMONI --}}
    <section class="px-[42px] pt-[70px] pb-[30px]">
        <div class="max-w-[1180px] mx-auto">

            {{-- TITLE --}}
            <div class="ml-[35px]">
                <h1 class="text-[72px] font-extrabold tracking-[-0.04em] leading-none">
                    Kata Klien Kami
                </h1>

                <p class="text-[19px] mt-[18px] text-[#3A372E]/90">
                    Pengalaman nyata dari klien yang telah mempercayakan kecantikannya kepada kami
                </p>

                <a href="{{ url('/ulasan/inputulasan') }}"
                   class="inline-flex items-center justify-center mt-[22px] bg-[#F8A9B4] text-[#3A372E] rounded-full px-[28px] py-[13px] text-[16px] font-extrabold hover:bg-[#F47CA5] transition">
                    Buat Ulasan
                    <span class="ml-2 text-[19px]">→</span>
                </a>
            </div>

            {{-- TESTIMONI CARDS --}}
            <div class="mt-[34px]">

                {{-- TOP CARDS --}}
                <div id="testimonialTopGrid" class="grid grid-cols-1 md:grid-cols-2 gap-[46px]"></div>

                {{-- BOTTOM CARD --}}
                <div id="testimonialBottomGrid" class="flex justify-center mt-[34px]"></div>

            </div>

        </div>
    </section>


    {{-- FAQ --}}
    <section class="px-[34px] pt-[32px] pb-[90px]">
        <div class="max-w-[1120px] mx-auto grid grid-cols-1 lg:grid-cols-[360px_1fr] gap-[42px] items-start">

            {{-- LEFT --}}
            <div class="pt-[42px]">
                <h2 class="text-[48px] font-extrabold tracking-[-0.04em] leading-[1.05]">
                    Pertanyaan<br>
                    Umum
                </h2>

                <p class="text-[16px] leading-[1.35] mt-[18px] text-[#5E5454] max-w-[330px]">
                    Semua yang perlu Anda ketahui sebelum memulai perawatan
                </p>
            </div>

            {{-- RIGHT FAQ LIST --}}
            <div class="pt-[8px] space-y-[8px] max-w-[760px]">

                @foreach($faqs as $faq)
                    <div class="faq-item bg-[#6A604F] rounded-[8px] overflow-hidden shadow-[0_8px_18px_rgba(58,55,46,0.10)]">
                        <button type="button"
                                onclick="toggleFaq(this)"
                                class="faq-question w-full flex items-center justify-between gap-4 px-[16px] py-[12px] text-left">
                            <span class="text-white text-[15px] font-semibold leading-snug">
                                {{ $faq['question'] }}
                            </span>

                            <span class="faq-arrow text-white/80 text-[16px] leading-none shrink-0">
                                {{ $faq['open'] ? '▼' : '▲' }}
                            </span>
                        </button>

                        <div class="faq-answer {{ $faq['open'] ? '' : 'hidden' }} px-[16px] pb-[14px]">
                            <p class="text-white/95 text-[12px] leading-[1.45] font-medium max-w-[680px]">
                                {{ $faq['answer'] }}
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </section>

</div>

<script>
    const databaseTestimonials = @json($testimonials->values());

    const defaultTestimonials = [
        {
            name: 'Alya Safitri',
            comment: 'Tempatnya nyaman, bersih, dan pelayanannya ramah. Saya merasa lebih tenang karena privasi benar-benar dijaga.',
            photo: null,
            rating: 5,
            service: 'Ulasan pelanggan'
        },
        {
            name: 'Zahra Aulia',
            comment: 'Hasil facialnya terasa lebih fresh dan kulit jadi halus. Terapisnya juga menjelaskan setiap proses dengan baik.',
            photo: null,
            rating: 5,
            service: 'Ulasan pelanggan'
        },
        {
            name: 'Nabila Shafira',
            comment: 'Bookingnya mudah dan jadwalnya jelas. Cocok banget untuk muslimah yang ingin perawatan dengan nyaman.',
            photo: null,
            rating: 5,
            service: 'Ulasan pelanggan'
        }
    ];

    function safeJsonParse(key) {
        try {
            return JSON.parse(localStorage.getItem(key) || '[]');
        } catch (error) {
            return [];
        }
    }

    function createStars(rating) {
        const totalRating = Number(rating || 5);
        let stars = '';

        for (let i = 1; i <= 5; i++) {
            stars += i <= totalRating ? '★' : '☆';
        }

        return stars;
    }

    function createTestimonialCard(item, isBottomCard = false) {
        const card = document.createElement('div');

        card.className = isBottomCard
            ? 'bg-[#FCE4E6] rounded-[12px] px-[26px] py-[34px] w-full md:w-[540px] min-h-[182px] shadow-[0_10px_18px_rgba(0,0,0,0.18)]'
            : 'bg-[#FCE4E6] rounded-[12px] px-[26px] py-[34px] min-h-[182px] shadow-[0_10px_18px_rgba(0,0,0,0.18)]';

        if (item.photo) {
            const image = document.createElement('img');
            image.src = item.photo;
            image.alt = 'Foto ulasan pelanggan';
            image.className = 'w-full h-[180px] object-cover rounded-[10px] mb-[22px] shadow-[0_8px_14px_rgba(0,0,0,0.12)]';
            card.appendChild(image);
        }

        const stars = document.createElement('p');
        stars.className = 'text-[18px] tracking-[0.1em] font-black text-[#D58A9A] mb-[14px]';
        stars.textContent = createStars(item.rating);
        card.appendChild(stars);

        const comment = document.createElement('p');
        comment.className = 'text-[19px] leading-[1.15] text-[#6B5C5C]';
        comment.textContent = item.comment || 'Ulasan pelanggan belum tersedia.';
        card.appendChild(comment);

        const identityWrapper = document.createElement('div');
        identityWrapper.className = 'mt-[26px]';

        const name = document.createElement('p');
        name.className = 'font-serif italic text-[18px] text-[#5E5454]';
        name.textContent = item.name || 'Pelanggan Dina';

        const status = document.createElement('p');
        status.className = 'text-[12px] tracking-[0.06em] font-bold text-[#9A6B76] leading-none mt-[6px]';
        status.textContent = item.service || 'Ulasan pelanggan';

        identityWrapper.appendChild(name);
        identityWrapper.appendChild(status);

        card.appendChild(identityWrapper);

        return card;
    }

    function renderTestimonials() {
        const topGrid = document.getElementById('testimonialTopGrid');
        const bottomGrid = document.getElementById('testimonialBottomGrid');

        const localStorageReviews = safeJsonParse('dinaSalonApprovedReviews');

        const testimonials = databaseTestimonials.length > 0
            ? databaseTestimonials.slice(0, 3)
            : (
                localStorageReviews.length > 0
                    ? localStorageReviews.slice(0, 3)
                    : defaultTestimonials
            );

        topGrid.innerHTML = '';
        bottomGrid.innerHTML = '';

        testimonials.forEach((item, index) => {
            const isBottomCard = index === 2;
            const card = createTestimonialCard(item, isBottomCard);

            if (isBottomCard) {
                bottomGrid.appendChild(card);
            } else {
                topGrid.appendChild(card);
            }
        });

        if (testimonials.length === 1) {
            topGrid.classList.remove('md:grid-cols-2');
            topGrid.classList.add('md:grid-cols-1');
        } else {
            topGrid.classList.remove('md:grid-cols-1');
            topGrid.classList.add('md:grid-cols-2');
        }
    }

    function toggleFaq(button) {
        const item = button.closest('.faq-item');
        const answer = item.querySelector('.faq-answer');
        const arrow = item.querySelector('.faq-arrow');

        if (!answer || !arrow) {
            return;
        }

        const isOpen = !answer.classList.contains('hidden');

        if (isOpen) {
            answer.classList.add('hidden');
            arrow.textContent = '▲';
        } else {
            answer.classList.remove('hidden');
            arrow.textContent = '▼';
        }
    }

    renderTestimonials();
</script>

@endsection