<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Foto - {{ $layanan->nama_layanan }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { margin: 0; overflow-x: hidden; }
        .soft-card   { box-shadow: 0 10px 24px rgba(58,55,46,.10); }
        .soft-shadow { box-shadow: 0  8px 18px rgba(58,55,46,.10); }
        .modal-bg    { background: rgba(0,0,0,.28); }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="lg:ml-[235px] lg:w-[calc(100%-235px)] w-full min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        {{-- HEADER --}}
        <header class="h-[92px] px-4 lg:px-[58px] flex items-center justify-between gap-3">
            
            <button type="button"
                    onclick="adminSidebarOpen()"
                    class="lg:hidden p-2 rounded-[8px] text-[#6B4D46] hover:bg-[#FFF1F1] transition shrink-0"
                    aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h2 class="text-[22px] font-extrabold text-[#3F3838] tracking-[-0.03em]">
                Halo, <span class="italic">Admin</span> Salon Dina Muslimah 👋
            </h2>
            <div class="relative flex items-center">
                @include('admin.partial.dropdownadmin')
            </div>
        </header>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-green-100 text-green-700 px-5 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mx-4 lg:mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="px-4 lg:px-[42px] mt-[14px] pb-[60px]">

            <div class="bg-[#FDE7EC] rounded-[18px] soft-card px-[26px] pt-[22px] pb-[36px]">

                {{-- TITLE ROW --}}
                <div class="flex items-center justify-between mb-[22px]">
                    <div class="flex items-center gap-[14px]">
                        <a href="{{ route('admin.layanan') }}"
                           class="w-[34px] h-[34px] rounded-full bg-white flex items-center justify-center hover:bg-[#FFE5E9] transition soft-shadow">
                            <svg class="w-[16px] h-[16px]" viewBox="0 0 24 24" fill="none">
                                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="#4B3A36" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-[22px] font-extrabold text-[#3F3838]">Album Foto</h1>
                            <p class="mt-[2px] text-[13px] font-semibold text-[#7B6A62]">
                                {{ $layanan->nama_layanan }}
                                <span class="text-[#9A7B7B]">· {{ $layanan->nama_jenis ?? '' }}</span>
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="openUploadModal()"
                        class="h-[40px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white rounded-[8px] px-[18px] flex items-center gap-[8px] text-[13px] font-extrabold transition">
                        Upload Foto <span class="text-[20px] leading-none">+</span>
                    </button>
                </div>

                {{-- FOTO PER TIPE --}}
                @php
                    $tipes = [
                        'before' => ['label' => 'Sebelum', 'color' => 'text-[#7B6A62]', 'bg' => 'bg-[#F6EDE8]'],
                        'after'  => ['label' => 'Sesudah', 'color' => 'text-[#B85C6A]', 'bg' => 'bg-[#FFE5E9]'],
                    ];
                @endphp

                @foreach($tipes as $tipeKey => $tipeInfo)
                    @php $fotoList = $fotos->get($tipeKey, collect()); @endphp

                    <div class="mb-[20px]">
                        <div class="flex items-center gap-[10px] mb-[12px]">
                            <span class="text-[12px] font-extrabold uppercase tracking-widest {{ $tipeInfo['color'] }}">
                                {{ $tipeInfo['label'] }}
                            </span>
                            <span class="{{ $tipeInfo['bg'] }} {{ $tipeInfo['color'] }} text-[11px] font-extrabold px-[8px] py-[2px] rounded-full">
                                {{ $fotoList->count() }} foto
                            </span>
                        </div>

                        @if($fotoList->isEmpty())
                            <div class="bg-white rounded-[12px] border border-[#F1D9DD] border-dashed py-[30px] text-center soft-shadow">
                                <p class="text-[13px] font-semibold text-[#9A7B7B]">Belum ada foto {{ strtolower($tipeInfo['label']) }}</p>
                                <button type="button"
                                    onclick="openUploadModal('{{ $tipeKey }}')"
                                    class="mt-[8px] text-[12px] font-extrabold text-[#B85C6A] hover:underline">
                                    + Upload foto {{ strtolower($tipeInfo['label']) }}
                                </button>
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-[10px]">
                                @foreach($fotoList as $foto)
                                    <div class="group relative aspect-square bg-white rounded-[10px] overflow-hidden soft-shadow border border-[#F1D9DD]">
                                        <img
                                            src="{{ Storage::url($foto->url_foto) }}"
                                            alt="{{ $tipeInfo['label'] }}"
                                            class="w-full h-full object-cover"
                                            onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';"
                                        >
                                        {{-- Overlay hapus --}}
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex items-center justify-center">
                                            <form
                                                action="{{ route('admin.album.foto.destroy', $foto->foto_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus foto ini?')"
                                                class="opacity-0 group-hover:opacity-100 transition-opacity"
                                            >
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-[34px] h-[34px] rounded-full bg-[#B85C6A] flex items-center justify-center hover:opacity-80 transition">
                                                    <svg class="w-[14px] h-[14px]" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 7H19M10 11V17M14 11V17M8 7L9 4H15L16 7M7 7L8 20H16L17 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Tombol tambah di akhir grid --}}
                                <button type="button"
                                    onclick="openUploadModal('{{ $tipeKey }}')"
                                    class="aspect-square bg-white rounded-[10px] border border-[#F1D9DD] border-dashed flex flex-col items-center justify-center gap-[4px] hover:bg-[#FFF8F9] transition soft-shadow">
                                    <span class="text-[24px] text-[#E8A9B4] leading-none">+</span>
                                    <span class="text-[10px] font-extrabold text-[#9A7B7B]">Tambah</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </section>
    </main>
</div>


{{-- ================================================================ --}}
{{-- MODAL UPLOAD FOTO --}}
{{-- ================================================================ --}}
<div id="modal-upload" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <div class="w-full max-w-[500px] bg-white rounded-[18px] shadow-2xl overflow-hidden">

        <div class="px-[24px] py-[18px] bg-[#FFF0F2] border-b border-[#F1D9DD] flex items-center justify-between">
            <h2 class="text-[20px] font-extrabold text-[#4B3A36]">Upload Foto</h2>
            <button type="button" onclick="closeUploadModal()"
                class="w-[34px] h-[34px] rounded-full bg-[#4B3A36] text-white text-[22px] leading-none flex items-center justify-center">&times;</button>
        </div>

        <form
            action="{{ route('admin.album.store', $layanan->layanan_id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="px-[24px] py-[20px] space-y-[14px]"
        >
            @csrf

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">Tipe Foto</label>
                <select name="tipe" id="upload-tipe"
                    class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] text-[13px] outline-none focus:ring-2 focus:ring-[#E8A9B4]" required>
                    <option value="before">Sebelum (Before)</option>
                    <option value="after">Sesudah (After)</option>
                </select>
            </div>

            <div>
                <label class="text-[13px] font-extrabold text-[#4B3A36]">
                    Pilih Foto
                    <span class="font-semibold text-[#9A7B7B]">(bisa pilih lebih dari satu)</span>
                </label>

                {{-- Drop zone --}}
                <div
                    id="drop-zone"
                    class="mt-[6px] border-2 border-dashed border-[#E8A9B4] rounded-[10px] p-[20px] text-center cursor-pointer hover:bg-[#FFF8F9] transition"
                    onclick="document.getElementById('foto-input').click()"
                    ondragover="event.preventDefault();this.classList.add('bg-[#FFF8F9]')"
                    ondragleave="this.classList.remove('bg-[#FFF8F9]')"
                    ondrop="handleDrop(event)"
                >
                    <svg class="w-[32px] h-[32px] mx-auto mb-[8px] text-[#E8A9B4]" viewBox="0 0 24 24" fill="none">
                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-[13px] font-extrabold text-[#4B3A36]">Klik atau drag foto ke sini</p>
                    <p class="text-[11px] text-[#9A7B7B] mt-[2px]">JPG, PNG, WebP · Maks 3MB per foto</p>
                </div>

                <input
                    type="file"
                    name="fotos[]"
                    id="foto-input"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    class="hidden"
                    onchange="previewFotos(this)"
                >
            </div>

            {{-- Preview grid --}}
            <div id="foto-preview" class="grid grid-cols-4 gap-[8px] hidden"></div>

            <div class="flex justify-end gap-[10px] pt-[4px]">
                <button type="button" onclick="closeUploadModal()"
                    class="h-[42px] px-[18px] rounded-[8px] bg-[#EFE4E4] text-[#4B3A36] text-[13px] font-extrabold hover:bg-[#E8D1D5] transition">
                    Batal
                </button>
                <button type="submit" id="upload-btn" disabled
                    class="h-[42px] px-[22px] rounded-[8px] bg-[#E8A9B4] text-white text-[13px] font-extrabold transition disabled:opacity-50 disabled:cursor-not-allowed hover:enabled:bg-[#D995A1]">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    function openUploadModal(tipe) {
        if (tipe) {
            document.getElementById('upload-tipe').value = tipe;
        }
        document.getElementById('modal-upload').classList.remove('hidden');
        document.getElementById('modal-upload').classList.add('flex');
    }

    function closeUploadModal() {
        document.getElementById('modal-upload').classList.add('hidden');
        document.getElementById('modal-upload').classList.remove('flex');
        // Reset form
        document.getElementById('foto-input').value = '';
        document.getElementById('foto-preview').innerHTML = '';
        document.getElementById('foto-preview').classList.add('hidden');
        document.getElementById('upload-btn').disabled = true;
    }

    document.getElementById('modal-upload').addEventListener('click', function(e) {
        if (e.target === this) closeUploadModal();
    });

    function previewFotos(input) {
        var preview = document.getElementById('foto-preview');
        preview.innerHTML = '';

        if (input.files.length === 0) {
            preview.classList.add('hidden');
            document.getElementById('upload-btn').disabled = true;
            return;
        }

        preview.classList.remove('hidden');
        document.getElementById('upload-btn').disabled = false;

        Array.from(input.files).forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var div = document.createElement('div');
                div.className = 'aspect-square rounded-[8px] overflow-hidden border border-[#F1D9DD]';
                div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function handleDrop(event) {
        event.preventDefault();
        document.getElementById('drop-zone').classList.remove('bg-[#FFF8F9]');

        var input = document.getElementById('foto-input');
        var dt = new DataTransfer();

        Array.from(event.dataTransfer.files).forEach(function(file) {
            if (file.type.startsWith('image/')) {
                dt.items.add(file);
            }
        });

        input.files = dt.files;
        previewFotos(input);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(function (el) {
            setTimeout(function () {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 500);
            }, 3000);
        });
    });
</script>

</body>
</html>