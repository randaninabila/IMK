@extends('user.app')

@section('content')

@php
    $testimonials  = $testimonials  ?? collect();
    $jenisLayanan  = $jenisLayanan  ?? collect();
    $selectedJenis = $selectedJenis ?? null;
    $faqs          = $faqs          ?? [];
@endphp

<div class="min-h-screen bg-gradient-to-b from-[#FBE7EA] via-[#FFF4F5] to-[#FFFFFF] text-[#3A372E] pt-[94px]">

    {{-- ============================================================ --}}
    {{-- SECTION: TESTIMONI --}}
    {{-- ============================================================ --}}
    <section class="px-[42px] pt-[70px] pb-[30px]">
        <div class="max-w-[1180px] mx-auto">

            {{-- Judul --}}
            <div class="ml-[35px]">
                <h1 class="text-[72px] font-extrabold tracking-[-0.04em] leading-none">
                    Kata Klien Kami
                </h1>
                <p class="text-[19px] mt-[18px] text-[#3A372E]/90">
                    Pengalaman nyata dari klien yang telah mempercayakan kecantikannya kepada kami
                </p>
            </div>

            {{-- Filter tombol --}}
@if($jenisLayanan->isNotEmpty())
    <div class="flex flex-wrap justify-center gap-3 mt-10 mb-10 animate-fade-in-up">
 
        <a href="{{ route('testimoni') }}"
           class="filter-btn px-5 py-2.5 rounded-full text-sm font-semibold border-2
                  {{ is_null($selectedJenis)
                     ? 'bg-gradient-to-r from-rose-400 to-pink-400 border-transparent text-white shadow-lg shadow-rose-200'
                     : 'bg-white border-rose-300 text-[#3A372E] hover:bg-rose-50' }}">
            Semua
        </a>

        @foreach($jenisLayanan as $jenis)
            <a href="{{ route('testimoni', ['jenis' => $jenis->jenis_layanan_id]) }}"
               class="filter-btn px-5 py-2.5 rounded-full text-sm font-semibold border-2
                      {{ $selectedJenis == $jenis->jenis_layanan_id
                         ? 'bg-gradient-to-r from-rose-400 to-pink-400 border-transparent text-white shadow-lg shadow-rose-200'
                         : 'bg-white border-rose-300 text-[#3A372E] hover:bg-rose-50' }}">
                {{ $jenis->nama_jenis }}
            </a>
        @endforeach

    </div>
@endif

            {{-- Jumlah ulasan --}}
            <p class="ml-[35px] mt-[16px] text-[13px] text-[#9A6B76] font-medium">
                {{ $testimonials->count() }} ulasan
                @if($selectedJenis)
                    &mdash; {{ $jenisLayanan->firstWhere('jenis_layanan_id', $selectedJenis)?->nama_jenis ?? '' }}
                @endif
            </p>

            {{-- Grid Testimoni --}}
            <div class="mt-[24px]" id="testimonialGrid">

                @if($testimonials->isEmpty())
                    <div class="text-center py-16 bg-[#FCE4E6] rounded-[12px]">
                        <p class="text-[#6B5C5C] text-[16px]">Belum ada ulasan untuk kategori ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[28px]">
                        @foreach($testimonials as $item)
                            <div class="bg-[#FCE4E6] rounded-[12px] px-[26px] py-[30px] shadow-[0_10px_18px_rgba(0,0,0,0.18)] flex flex-col">

                                {{-- Foto ulasan --}}
                                @if(!empty($item['photo']))
                                    <img src="{{ $item['photo'] }}"
                                         alt="Foto ulasan {{ $item['name'] }}"
                                         class="w-full h-[160px] object-cover rounded-[10px] mb-[18px] shadow-[0_8px_14px_rgba(0,0,0,0.12)]"
                                         loading="lazy">
                                @endif

                                {{-- Rating --}}
                                <p class="text-[17px] tracking-[0.1em] font-black text-[#D58A9A] mb-[10px]"
                                   aria-label="Rating {{ $item['rating'] }} dari 5">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $item['rating'] ? '★' : '☆' }}
                                    @endfor
                                </p>

                                {{-- Komentar --}}
                                <p class="text-[16px] leading-[1.45] text-[#6B5C5C] flex-1">
                                    {{ $item['comment'] }}
                                </p>

                                {{-- Identitas --}}
                                <div class="mt-[20px] pt-[16px] border-t border-[#D58A9A]/20">
                                    <p class="font-serif italic text-[17px] text-[#5E5454]">
                                        {{ $item['name'] }}
                                    </p>
                                    <p class="text-[11px] tracking-[0.06em] font-bold text-[#9A6B76] mt-[4px]">
                                        {{ $item['service'] }}
                                    </p>
                                    @if(!empty($item['date']))
                                        <p class="text-[10px] text-gray-400 mt-[3px]">
                                            {{ \Carbon\Carbon::parse($item['date'])->isoFormat('D MMM Y') }}
                                        </p>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </section>


    {{-- ============================================================ --}}
    {{-- SECTION: FAQ --}}
    {{-- ============================================================ --}}
    <section class="px-[34px] pt-[32px] pb-[90px]">
        <div class="max-w-[1120px] mx-auto grid grid-cols-1 lg:grid-cols-[360px_1fr] gap-[42px] items-start">

            {{-- Kolom kiri --}}
            <div class="pt-[42px]">
                <h2 class="text-[48px] font-extrabold tracking-[-0.04em] leading-[1.05]">
                    Pertanyaan<br>Umum
                </h2>
                <p class="text-[16px] leading-[1.35] mt-[18px] text-[#5E5454] max-w-[330px]">
                    Semua yang perlu Anda ketahui sebelum memulai perawatan
                </p>
            </div>

            {{-- Kolom kanan: daftar FAQ --}}
            <div class="pt-[8px] space-y-[8px] max-w-[760px]">
                @foreach($faqs as $faq)
                    <div class="faq-item bg-[#6A604F] rounded-[8px] overflow-hidden shadow-[0_8px_18px_rgba(58,55,46,0.10)]">

                        <button type="button"
                                onclick="toggleFaq(this)"
                                class="faq-question w-full flex items-center justify-between gap-4 px-[16px] py-[12px] text-left">
                            <span class="text-white text-[15px] font-semibold leading-snug">
                                {{ $faq['question'] }}
                            </span>
                            <span class="faq-arrow text-white/80 text-[16px] leading-none shrink-0">▲</span>
                        </button>

                        <div class="faq-answer hidden px-[16px] pb-[14px]">
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
    function toggleFaq(button) {
        const clickedItem   = button.closest('.faq-item');
        const clickedAnswer = clickedItem.querySelector('.faq-answer');
        const clickedArrow  = clickedItem.querySelector('.faq-arrow');
        if (!clickedAnswer || !clickedArrow) return;

        const isOpen = !clickedAnswer.classList.contains('hidden');

        // Tutup semua (accordion)
        document.querySelectorAll('.faq-item').forEach(item => {
            item.querySelector('.faq-answer')?.classList.add('hidden');
            const a = item.querySelector('.faq-arrow');
            if (a) a.textContent = '▲';
        });

        if (!isOpen) {
            clickedAnswer.classList.remove('hidden');
            clickedArrow.textContent = '▼';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const firstBtn = document.querySelector('.faq-question');
        if (firstBtn) firstBtn.click();
    });
</script>

@endsection