Bisa. Aku ubah dari file `inputulasan.blade.php` kamu: **hapus Layanan dan Rating**, jadi formnya sekarang cuma **Nama**, **Komentar**, dan **Upload Foto opsional**. Upload foto disimpan sementara ke `localStorage` sebagai preview frontend, nanti backend bisa masuk ke tabel `ulasan_foto`. 

Paste ke:

```txt
resources/views/user/ulasan/inputulasan.blade.php
```

```blade
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

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-[34px] items-start">

            <form id="reviewForm"
                  onsubmit="submitReview(event)"
                  class="bg-white rounded-[32px] shadow-[0_18px_35px_rgba(0,0,0,0.12)] px-[38px] py-[36px]">

                <div>
                    <label for="reviewName" class="block text-[20px] font-extrabold mb-[10px]">
                        Nama
                    </label>

                    <input type="text"
                           id="reviewName"
                           required
                           placeholder="Masukkan nama Anda"
                           class="w-full h-[54px] rounded-[14px] bg-[#FCE6EA] px-[22px] text-[17px] font-semibold outline-none focus:ring-2 focus:ring-[#F47CA5]">

                    <p class="mt-[7px] text-[12px] font-semibold text-[#8A7A74]">
                        Nanti saat backend, nama bisa otomatis diambil dari akun pelanggan yang login.
                    </p>
                </div>

                <div class="mt-[26px]">
                    <label for="reviewComment" class="block text-[20px] font-extrabold mb-[10px]">
                        Komentar
                    </label>

                    <textarea id="reviewComment"
                              required
                              placeholder="Ceritakan pengalaman Anda..."
                              class="w-full h-[165px] rounded-[14px] bg-[#FCE6EA] px-[22px] py-[18px] text-[17px] font-semibold outline-none resize-none focus:ring-2 focus:ring-[#F47CA5]"></textarea>
                </div>

                <div class="mt-[26px]">
                    <label for="reviewPhoto" class="block text-[20px] font-extrabold mb-[10px]">
                        Upload Foto
                        <span class="text-[14px] font-bold text-[#8A7A74]">
                            opsional
                        </span>
                    </label>

                    <label for="reviewPhoto"
                           class="flex min-h-[138px] cursor-pointer flex-col items-center justify-center rounded-[18px] border-2 border-dashed border-[#F8A9B4] bg-[#FFF2F4] px-[22px] py-[22px] text-center transition hover:bg-[#FFE7EC]">
                        <span class="text-[34px] leading-none">
                            📷
                        </span>

                        <span id="photoLabel" class="mt-[10px] text-[15px] font-extrabold text-[#3A372E]">
                            Klik untuk upload foto
                        </span>

                        <span class="mt-[5px] text-[12px] font-semibold text-[#8A7A74]">
                            JPG, PNG, atau WEBP
                        </span>
                    </label>

                    <input type="file"
                           id="reviewPhoto"
                           accept="image/png, image/jpeg, image/jpg, image/webp"
                           onchange="handlePhotoUpload(event)"
                           class="hidden">

                    <button type="button"
                            id="removePhotoButton"
                            onclick="removePhoto()"
                            class="hidden mt-[10px] text-[13px] font-extrabold text-[#D2365B] hover:opacity-70 transition">
                        Hapus foto
                    </button>
                </div>

                <div class="mt-[34px] flex items-center justify-between gap-[18px]">
                    <a href="{{ url('/testimoni') }}"
                       class="w-[180px] text-center rounded-full bg-[#E4C2C5] text-[#6F5D5D] py-[15px] text-[18px] font-extrabold hover:bg-[#d8b1b5] transition">
                        ← Kembali
                    </a>

                    <button type="submit"
                            class="w-[230px] rounded-full bg-[#F8A9B4] text-[#3A372E] py-[15px] text-[18px] font-extrabold hover:bg-[#F47CA5] transition">
                        Kirim Ulasan →
                    </button>
                </div>

                <p id="successMessage"
                   class="hidden mt-[22px] rounded-[12px] bg-[#E9FFD8] px-[18px] py-[12px] text-[14px] font-extrabold text-[#71925E]">
                    Ulasan berhasil dikirim dan akan tampil di halaman testimoni.
                </p>

            </form>

            <div class="bg-[#FFE1E6] rounded-[32px] shadow-[0_18px_35px_rgba(0,0,0,0.10)] px-[30px] py-[32px]">
                <h2 class="text-[28px] font-extrabold tracking-[-0.04em]">
                    Preview Ulasan
                </h2>

                <div class="mt-[28px] bg-white rounded-[22px] shadow-[0_12px_24px_rgba(0,0,0,0.14)] px-[24px] py-[24px]">

                    <img id="previewPhoto"
                         src=""
                         alt="Preview Foto Ulasan"
                         class="hidden mb-[18px] h-[170px] w-full rounded-[16px] object-cover">

                    <p id="previewComment" class="text-[16px] leading-[1.35] text-[#6B5A55]">
                        Pengalaman Anda akan muncul di sini.
                    </p>

                    <p id="previewName" class="mt-[26px] italic text-[18px] font-semibold text-[#6B5A55]">
                        Nama Anda
                    </p>
                </div>

                <p class="mt-[22px] text-[14px] leading-[1.4] font-semibold text-[#6B5A55]">
                    Setelah dikirim, ulasan akan tersimpan sementara di browser dan muncul di halaman testimoni.
                    Untuk backend nanti, komentar masuk ke tabel ulasan dan foto masuk ke tabel ulasan_foto.
                </p>
            </div>

        </div>

    </section>

</div>

<script>
    let uploadedPhoto = null;

    function updatePreview() {
        const name = document.getElementById('reviewName').value.trim();
        const comment = document.getElementById('reviewComment').value.trim();

        document.getElementById('previewName').textContent = name || 'Nama Anda';
        document.getElementById('previewComment').textContent = comment || 'Pengalaman Anda akan muncul di sini.';
    }

    function handlePhotoUpload(event) {
        const file = event.target.files[0];

        if (!file) {
            removePhoto();
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        if (!allowedTypes.includes(file.type)) {
            alert('Format foto harus JPG, PNG, atau WEBP.');
            event.target.value = '';
            removePhoto();
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            uploadedPhoto = e.target.result;

            const previewPhoto = document.getElementById('previewPhoto');
            previewPhoto.src = uploadedPhoto;
            previewPhoto.classList.remove('hidden');

            document.getElementById('photoLabel').textContent = file.name;
            document.getElementById('removePhotoButton').classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    }

    function removePhoto() {
        uploadedPhoto = null;

        const input = document.getElementById('reviewPhoto');
        const previewPhoto = document.getElementById('previewPhoto');

        input.value = '';
        previewPhoto.src = '';
        previewPhoto.classList.add('hidden');

        document.getElementById('photoLabel').textContent = 'Klik untuk upload foto';
        document.getElementById('removePhotoButton').classList.add('hidden');
    }

    function getSavedReviews() {
        return JSON.parse(localStorage.getItem('dinaSalonReviews') || '[]');
    }

    function submitReview(event) {
        event.preventDefault();

        const name = document.getElementById('reviewName').value.trim();
        const comment = document.getElementById('reviewComment').value.trim();

        const newReview = {
            name: name,
            comment: comment,
            photo: uploadedPhoto,
            rating: 5,
            createdAt: new Date().toISOString()
        };

        const reviews = getSavedReviews();
        reviews.unshift(newReview);

        localStorage.setItem('dinaSalonReviews', JSON.stringify(reviews));

        document.getElementById('successMessage').classList.remove('hidden');

        setTimeout(() => {
            window.location.href = "{{ url('/testimoni') }}";
        }, 900);
    }

    document.getElementById('reviewName').addEventListener('input', updatePreview);
    document.getElementById('reviewComment').addEventListener('input', updatePreview);
</script>

@endsection
```
