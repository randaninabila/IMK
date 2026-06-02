@extends('user.app')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E8] via-[#FFF6F7] to-white text-[#3A372E] pt-[72px] pb-[90px]">
<section class="max-w-[1180px] mx-auto px-[60px] pt-[72px]">

     <div class="mb-[46px]">
         <h1 class="text-[64px] font-extrabold tracking-[-0.05em] leading-none">
            Beri Ulasan
         </h1>
         <p class="mt-[14px] text-[17px] text-[#3A372E]/75">
            Bagikan pengalaman Anda setelah melakukan perawatan di Dina Salon Muslimah.
         </p>
     </div>

    {{-- ERROR --}}
    @if ($errors->any())
         <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
             <svg class="w-5 h-5 text-red-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
             </svg>
             <div>
                @foreach ($errors->all() as $error)
                     <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
             </div>
         </div>
    @endif

    {{-- INFO PESANAN --}}
     <div class="mb-8 bg-white rounded-[24px] border border-pink-100 px-6 py-4 flex items-center gap-4">
         <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center shrink-0">
             <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
             </svg>
         </div>
         <div>
             <p class="text-xs text-gray-400">Pesanan #{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }} · {{ $isPaket ? 'Paket' : 'Layanan' }}</p>
             <p class="font-semibold text-[#3A372E]">{{ $namaItem }}</p>
             <p class="text-xs text-gray-400">{{ $namaCabang }} · {{ \Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('D MMMM Y') }}</p>
         </div>
     </div>

     <div class="grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-[34px] items-start">

        {{-- FORM --}}
         <form id="reviewForm"
              action="{{ route('pelanggan.booking.ulasan.store', $booking->booking_id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white rounded-[32px] shadow-[0_18px_35px_rgba(0,0,0,0.12)] px-[38px] py-[36px]">
            @csrf

            {{-- ⭐ RATING BINTANG --}}
             <div>
                 <label class="block text-[20px] font-extrabold mb-[10px]">
                    Rating <span class="text-rose-400">*</span>
                 </label>
                 <div class="flex items-center gap-2" id="starRating">
                    @for($i = 1; $i <= 5; $i++)
                         <button type="button"
                                data-value="{{ $i }}"
                                class="star-btn text-[32px] leading-none transition hover:scale-110 focus:outline-none"
                                aria-label="Rating {{ $i }} bintang">
                            ★
                         </button>
                    @endfor
                 </div>
                 <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}" required>
                 <p id="ratingText" class="mt-[7px] text-[12px] font-semibold text-[#8A7A74]">
                    {{ old('rating') ? 'Kamu memilih ' . old('rating') . ' bintang' : 'Pilih rating 1-5 bintang' }}
                 </p>
                @error('rating')
                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
             </div>

            {{-- Nama (readonly dari akun) + Opsi Samarkan --}}
            {{-- Nama (readonly dari akun) + Opsi Samarkan --}}
<div class="mt-[26px]">
    <label class="block text-[20px] font-extrabold mb-[10px]">Nama</label>
    
    {{-- Nama User --}}
    <div class="w-full h-[54px] rounded-[14px] bg-[#FCE6EA] px-[22px] flex items-center mb-3">
        <span class="text-[17px] font-semibold text-[#3A372E]">{{ $user->nama }}</span>
    </div>
    
    {{-- Checkbox Samarkan Nama --}}
    <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" 
               name="nama_samar" 
               id="namaSamar" 
               value="1" 
               {{ old('nama_samar') ? 'checked' : '' }}
               class="accent-rose-400 w-5 h-5">
        <div>
            <span class="text-[17px] font-semibold text-[#3A372E]">Samarkan nama saya</span>
            <p class="text-[12px] text-[#8A7A74] mt-1">
                Jika dicentang, nama kamu akan ditampilkan sebagai "Pelanggan" di halaman testimoni.
            </p>
        </div>
    </label>
    
    <p class="mt-[7px] text-[12px] font-semibold text-[#8A7A74]">
        Jika dicentang, nama kamu akan ditampilkan sebagai "Pelanggan" di ulasan publik.
    </p>
</div>

            {{-- Komentar --}}
             <div class="mt-[26px]">
                 <label for="reviewComment" class="block text-[20px] font-extrabold mb-[10px]">Komentar <span class="text-rose-400">*</span></label>
                 <textarea id="reviewComment"
                          name="komentar"
                          required
                          maxlength="1000"
                          placeholder="Ceritakan pengalaman Anda..."
                          class="w-full h-[165px] rounded-[14px] bg-[#FCE6EA] px-[22px] py-[18px] text-[17px] font-semibold outline-none resize-none focus:ring-2 focus:ring-[#F47CA5] @error('komentar') ring-2 ring-red-400 @enderror">{{ old('komentar') }}</textarea>
                 <p class="mt-1 text-xs text-gray-400 text-right">
                     <span id="charCount">{{ strlen(old('komentar', '')) }}</span>/1000
                 </p>
                @error('komentar')
                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
             </div>

            {{-- Upload Foto --}}
             <div class="mt-[26px]">
                 <label class="block text-[20px] font-extrabold mb-[10px]">
                    Upload Foto
                     <span class="text-[14px] font-bold text-[#8A7A74]">opsional</span>
                 </label>

                 <label for="reviewPhoto"
                       class="flex min-h-[138px] cursor-pointer flex-col items-center justify-center rounded-[18px] border-2 border-dashed border-[#F8A9B4] bg-[#FFF2F4] px-[22px] py-[22px] text-center transition hover:bg-[#FFE7EC]">
                     <span class="text-[34px] leading-none">📷</span>
                     <span id="photoLabel" class="mt-[10px] text-[15px] font-extrabold text-[#3A372E]">
                        Klik untuk upload foto
                     </span>
                     <span class="mt-[5px] text-[12px] font-semibold text-[#8A7A74]">
                        JPG, PNG, atau WEBP · Maks 2MB
                     </span>
                 </label>

                 <input type="file"
                       id="reviewPhoto"
                       name="foto"
                       accept="image/png, image/jpeg, image/jpg, image/webp"
                       onchange="handlePhotoUpload(event)"
                       class="hidden">

                 <button type="button"
                        id="removePhotoButton"
                        onclick="removePhoto()"
                        class="hidden mt-[10px] text-[13px] font-extrabold text-[#D2365B] hover:opacity-70 transition">
                    Hapus foto
                 </button>

                @error('foto')
                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
             </div>

             <div class="mt-[34px] flex items-center justify-between gap-[18px]">
                 <a href="{{ route('pelanggan.booking.show', $booking->booking_id) }}"
                   class="w-[180px] text-center rounded-full bg-[#E4C2C5] text-[#6F5D5D] py-[15px] text-[18px] font-extrabold hover:bg-[#d8b1b5] transition">
                    ← Kembali
                 </a>

                 <button type="submit"
                        class="w-[230px] rounded-full bg-[#F8A9B4] text-[#3A372E] py-[15px] text-[18px] font-extrabold hover:bg-[#F47CA5] transition">
                    Kirim Ulasan →
                 </button>
             </div>

         </form>

        {{-- PREVIEW --}}
         <div class="bg-[#FFE1E6] rounded-[32px] shadow-[0_18px_35px_rgba(0,0,0,0.10)] px-[30px] py-[32px]">
             <h2 class="text-[28px] font-extrabold tracking-[-0.04em]">Preview Ulasan</h2>

             <div class="mt-[28px] bg-white rounded-[22px] shadow-[0_12px_24px_rgba(0,0,0,0.14)] px-[24px] py-[24px]">
                 {{-- Preview Rating --}}
                 <div class="flex items-center gap-1 mb-[12px]" id="previewRating">
                    @for($i = 1; $i <= 5; $i++)
                         <span class="text-[20px] text-gray-300">★</span>
                    @endfor
                 </div>

                 <img id="previewPhoto"
                     src="" alt="Preview"
                     class="hidden mb-[18px] h-[170px] w-full rounded-[16px] object-cover">

                 <p id="previewComment" class="text-[16px] leading-[1.35] text-[#6B5A55]">
                    Pengalaman Anda akan muncul di sini.
                 </p>

                 <p class="mt-[26px] italic text-[18px] font-semibold text-[#6B5A55]" id="previewName">
                    {{ $user->nama }}
                 </p>
             </div>

             <p class="mt-[22px] text-[14px] leading-[1.4] font-semibold text-[#6B5A55]">
                Ulasan akan tersimpan dan akan ditampilkan pada halaman Testimoni Pengguna.
             </p>
         </div>

     </div>

 </section>
</div>

<script>
// ⭐ STAR RATING INTERACTION
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');
    const previewRating = document.getElementById('previewRating');
    const previewName = document.getElementById('previewName');
    const namaSamar = document.getElementById('namaSamar');
    
    // Simpan nama asli user untuk preview
    const originalName = '{{ $user->nama }}';

    // Set initial rating dari old input
    const initialRating = {{ old('rating') ?? 0 }};
    if (initialRating > 0) {
        updateStars(initialRating);
    }

    // Event click untuk bintang
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            ratingInput.value = value;
            updateStars(value);
            ratingText.textContent = `Kamu memilih ${value} bintang`;
        });

        // Hover effect
        star.addEventListener('mouseenter', function() {
            const value = parseInt(this.dataset.value);
            highlightStars(value, true);
        });

        star.addEventListener('mouseleave', function() {
            const current = parseInt(ratingInput.value) || 0;
            highlightStars(current, false);
        });
    });

    // Fungsi update tampilan bintang
    function updateStars(value) {
        stars.forEach((star, index) => {
            star.classList.toggle('text-amber-400', index < value);
            star.classList.toggle('text-gray-300', index >= value);
        });
        updatePreviewRating(value);
    }

    // Fungsi hover bintang
    function highlightStars(value, isHover) {
        stars.forEach((star, index) => {
            if (isHover) {
                star.classList.toggle('text-amber-400', index < value);
                star.classList.toggle('text-gray-300', index >= value);
            } else {
                const current = parseInt(ratingInput.value) || 0;
                star.classList.toggle('text-amber-400', index < current);
                star.classList.toggle('text-gray-300', index >= current);
            }
        });
    }

    // Update preview rating
    function updatePreviewRating(value) {
        const previewStars = previewRating.querySelectorAll('span');
        previewStars.forEach((star, index) => {
            star.classList.toggle('text-amber-400', index < value);
            star.classList.toggle('text-gray-300', index >= value);
        });
    }

    // 👤 Preview Nama Samaran
    if (namaSamar && previewName) {
        namaSamar.addEventListener('change', function() {
            previewName.textContent = this.checked ? 'Pelanggan' : originalName;
        });
    }

    // ✍️ Live Preview Komentar
    const reviewComment = document.getElementById('reviewComment');
    const previewComment = document.getElementById('previewComment');
    const charCount = document.getElementById('charCount');
    
    if (reviewComment && previewComment && charCount) {
        reviewComment.addEventListener('input', function() {
            const val = this.value.trim();
            previewComment.textContent = val || 'Pengalaman Anda akan muncul di sini.';
            charCount.textContent = this.value.length;
        });
    }
});

// 📷 Photo Upload Handler (global function)
function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) { removePhoto(); return; }

    const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!allowed.includes(file.type)) {
        alert('Format foto harus JPG, PNG, atau WEBP.');
        event.target.value = '';
        removePhoto();
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('previewPhoto');
        const photoLabel = document.getElementById('photoLabel');
        const removeBtn = document.getElementById('removePhotoButton');
        
        if (preview && photoLabel && removeBtn) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            photoLabel.textContent = file.name;
            removeBtn.classList.remove('hidden');
        }
    };
    reader.readAsDataURL(file);
}

function removePhoto() {
    const reviewPhoto = document.getElementById('reviewPhoto');
    const preview = document.getElementById('previewPhoto');
    const photoLabel = document.getElementById('photoLabel');
    const removeBtn = document.getElementById('removePhotoButton');
    
    if (reviewPhoto) reviewPhoto.value = '';
    if (preview) {
        preview.src = '';
        preview.classList.add('hidden');
    }
    if (photoLabel) photoLabel.textContent = 'Klik untuk upload foto';
    if (removeBtn) removeBtn.classList.add('hidden');
}
</script>
@endsection