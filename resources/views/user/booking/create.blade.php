@extends('user.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6">
    <div class="max-w-5xl mx-auto">

        {{-- TITLE --}}
        <div class="text-center mb-10 mt-14">
            <h1 class="text-7xl font-bold text-[#3E382D]">Booking Layanan</h1>
            <p class="text-sm text-gray-500 mt-2">Isi data booking treatment kamu</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- FORM KIRI --}}
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-pink-100 p-8">

                <form action="{{ route('pelanggan.booking.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <input type="hidden" name="layanan_cabang_id" value="{{ $layanan->layanan_cabang_id }}">

                    {{-- Nama (dari akun) --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            Nama Lengkap
                        </label>
                        <div class="w-full rounded-2xl border border-pink-100 bg-pink-50 px-5 py-3 text-[#3E382D] font-medium">
                            {{ $user->nama }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Nama diambil dari akun kamu</p>
                    </div>

                    {{-- No HP --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            No. WhatsApp
                        </label>
                        <div class="w-full rounded-2xl border border-pink-100 bg-pink-50 px-5 py-3 text-[#3E382D] font-medium">
                            {{ $user->no_hp ?? '-' }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Kami akan menghubungi kamu melalui nomor ini</p>
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            Tanggal Booking <span class="text-rose-400">*</span>
                        </label>
                        <input type="date"
                               id="tanggalInput"
                               name="tanggal"
                               value="{{ old('tanggal') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200 @error('tanggal') border-red-300 @enderror"
                               required>
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p id="tanggalHint" class="text-xs text-amber-600 mt-1 hidden">⚠️ Jam yang sudah lewat tidak tersedia untuk hari ini</p>
                    </div>

                    {{-- Jam --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            Jam Booking <span class="text-rose-400">*</span>
                        </label>
                        <select name="jam"
                                id="jamSelect"
                                class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200 @error('jam') border-red-300 @enderror"
                                required>
                            <option value="" disabled selected>-- Pilih jam --</option>
                            @php
                                $jamOperasional = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
                                $today = date('Y-m-d');
                                $now = date('H:i');
                                $selectedDate = old('tanggal') ?? $today;
                                $oldJam = old('jam');
                            @endphp
                            @foreach($jamOperasional as $jam)
                                @php
                                    $jamValue = $jam . ':00';
                                    $isToday = $selectedDate === $today;
                                    $isPast = $isToday && $jam <= $now;
                                    $isSelected = $oldJam === $jamValue && !$isPast;
                                @endphp
                                <option value="{{ $jamValue }}"
                                        {{ $isSelected ? 'selected' : '' }}
                                        {{ $isPast ? 'disabled' : '' }}
                                        class="{{ $isPast ? 'text-gray-300' : '' }}">
                                    {{ $jam }} WIB {{ $isPast ? '(Sudah lewat)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('jam')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1">Jam operasional: 09.00 – 19.00 WIB</p>
                    </div>

                    {{-- Info pembayaran --}}
                    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p>Setelah booking, kamu akan diarahkan ke halaman pembayaran. Pilih metode <strong>QRIS</strong> atau <strong>Tunai (bayar di lokasi)</strong>.</p>
                        </div>
                    </div>

                    <button type="submit"
                            id="submitBtn"
                            class="w-full bg-rose-400 hover:bg-rose-500 text-white font-bold py-4 rounded-2xl transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="submitLabel">Lanjut ke Pembayaran</span>
                    </button>

                </form>

            </div>

            {{-- INFO LAYANAN KANAN --}}
            <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden h-fit sticky top-24">

                <img src="{{ $layanan->cover_foto }}"
                     class="w-full h-48 object-cover"
                     onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">

                <div class="p-6">

                    <h2 class="text-xl font-bold text-[#3E382D] mb-4">{{ $layanan->nama_layanan }}</h2>

                    <div class="space-y-3 text-sm">

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-rose-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-gray-400 text-xs">Cabang</p>
                                <p class="font-semibold text-[#3E382D]">{{ $layanan->nama_cabang }}</p>
                                <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">{{ $layanan->alamat }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-gray-400 text-xs">Durasi</p>
                                <p class="font-semibold text-[#3E382D]">{{ $layanan->durasi }} menit</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <div>
                                <p class="text-gray-400 text-xs">Kategori</p>
                                <p class="font-semibold text-[#3E382D] capitalize">{{ $layanan->nama_jenis }}</p>
                            </div>
                        </div>

                    </div>

                    <div class="border-t border-pink-100 mt-4 pt-4">
                        @if($layanan->harga_promo > 0)
                            <p class="text-xs text-gray-400 line-through">
                                Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                            </p>
                            <p class="text-2xl font-bold text-rose-400">
                                Rp {{ number_format($layanan->harga_promo, 0, ',', '.') }}
                            </p>
                            <span class="inline-block bg-rose-100 text-rose-600 text-xs font-semibold px-2 py-0.5 rounded-full mt-1">
                                Harga Promo
                            </span>
                        @else
                            <p class="text-2xl font-bold text-[#3E382D]">
                                Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

{{-- JAVASCRIPT: Dynamic Jam Disable + Form Handling --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('tanggalInput');
    const jamSelect = document.getElementById('jamSelect');
    const tanggalHint = document.getElementById('tanggalHint');
    const submitBtn = document.getElementById('submitBtn');
    const submitLabel = document.getElementById('submitLabel');
    
    // Jam operasional
    const jamOperasional = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
    
    function updateJamOptions() {
        const selectedDate = tanggalInput.value;
        const today = new Date().toISOString().split('T')[0];
        const now = new Date();
        const currentHour = String(now.getHours()).padStart(2, '0');
        const currentMinute = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${currentHour}:${currentMinute}`;
        
        // Simpan nilai lama jika ada
        const oldJam = @json(old('jam') ?? '');
        
        // Reset options
        jamSelect.innerHTML = '<option value="" disabled selected>-- Pilih jam --</option>';
        
        jamOperasional.forEach(jam => {
            const jamValue = jam + ':00';
            const isToday = selectedDate === today;
            const isPast = isToday && jam <= currentTime;
            
            const option = document.createElement('option');
            option.value = jamValue;
            option.textContent = jam + ' WIB' + (isPast ? ' (Sudah lewat)' : '');
            
            if (isPast) {
                option.disabled = true;
                option.classList.add('text-gray-300');
            }
            
            // Restore old selection if valid
            if (oldJam === jamValue && !isPast) {
                option.selected = true;
            }
            
            jamSelect.appendChild(option);
        });
        
        // Tampilkan hint jika hari ini
        if (selectedDate === today) {
            tanggalHint.classList.remove('hidden');
        } else {
            tanggalHint.classList.add('hidden');
        }
    }
    
    // Jalankan saat halaman load
    updateJamOptions();
    
    // Update saat tanggal berubah
    tanggalInput.addEventListener('change', function() {
        // Reset jam saat tanggal berubah
        jamSelect.value = '';
        updateJamOptions();
    });
    
    // Form submit handling
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const selectedJam = jamSelect.value;
        const selectedDate = tanggalInput.value;
        const today = new Date().toISOString().split('T')[0];
        const now = new Date();
        const currentHour = String(now.getHours()).padStart(2, '0');
        const currentMinute = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${currentHour}:${currentMinute}`;
        
        // Validasi: jam tidak boleh sudah lewat jika tanggal hari ini
        if (selectedDate === today && selectedJam && selectedJam.substring(0,5) <= currentTime) {
            e.preventDefault();
            alert('Jam booking tidak boleh di masa lalu. Silakan pilih jam yang masih tersedia.');
            jamSelect.focus();
            return;
        }
        
        // Disable tombol agar tidak double submit
        submitBtn.disabled = true;
        submitLabel.innerHTML = `
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Memproses...
        `;
    });
});
</script>
@endsection