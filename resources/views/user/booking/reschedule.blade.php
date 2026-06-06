@extends('user.app')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6">
    <div class="max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-10 mt-14">
            <h1 class="text-7xl font-bold text-[#3E382D]">Jadwal Ulang Pesanan</h1>
            <p class="text-sm text-gray-500 mt-2">Ubah jadwal layanan kamu</p>
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

        {{-- CARD BOOKING --}}
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-rose-400 to-pink-400 p-5">
                <p class="text-white text-xs font-semibold opacity-80 uppercase">Booking #{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-white text-lg font-bold mt-1">{{ $layananList->pluck('nama_layanan')->join(', ') }}</p>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Jadwal Lama</p>
                    <p class="font-semibold text-[#3E382D]">
                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('dddd, D MMM Y') }}<br>
                        {{ substr($booking->jam_booking, 0, 5) }} WIB
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Total</p>
                    <p class="font-bold text-rose-400">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- FORM RESCHEDULE --}}
        <form action="{{ route('pelanggan.booking.reschedule.process', $booking->booking_id) }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-pink-100 p-8">
            @csrf
            
            {{-- Tanggal Baru --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                    Tanggal Baru <span class="text-rose-400">*</span>
                </label>
                <input type="date" name="new_tanggal" id="newTanggal"
                       value="{{ old('new_tanggal') }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200"
                       required>
                <p class="text-xs text-gray-400 mt-1">Pilih tanggal yang tersedia</p>
            </div>

            {{-- Jam Baru --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                    Jam Baru <span class="text-rose-400">*</span>
                </label>
                <select name="new_jam" id="newJam"
                        class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200"
                        required>
                    <option value="" disabled selected>-- Pilih jam --</option>
                    @php
                        $jamOperasional = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
                        $today = date('Y-m-d');
                        $now = date('H:i');
                        $selectedDate = old('new_tanggal') ?? $booking->tanggal_booking;
                        $oldJam = old('new_jam');
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
                <p class="text-xs text-gray-400 mt-1">Jam operasional: 09.00 – 19.00 WIB</p>
            </div>
                
            {{-- Alasan --}}
            <div class="mb-8">
                <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                    Alasan Jadwal Ulang <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="reason" rows="3" maxlength="500"
                          class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200"
                          placeholder="Contoh: ada urusan mendadak, sakit, dll.">{{ old('reason') }}</textarea>
            </div>

            {{-- Info --}}
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl p-4 text-sm text-blue-800">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p>Setelah jadwal ulang, status pesanan akan berubah menjadi <strong>Di Jadwal Ulang</strong>. Kamu akan mendapat notifikasi konfirmasi.</p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('pelanggan.booking.show', $booking->booking_id) }}" 
                   class="flex-1 bg-white border-2 border-pink-200 text-[#3E382D] font-semibold py-3 rounded-xl text-center hover:border-rose-300 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-rose-400 hover:bg-rose-500 text-white font-semibold py-3 rounded-xl transition">
                    Konfirmasi Jadwal Ulang
                </button>
            </div>
        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('newTanggal');
    const jamSelect    = document.getElementById('newJam');
    const jamOperasional = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

    const savedJam      = @json(old('new_jam') ?? '');
    const bookingTanggal = @json($booking->tanggal_booking);           // tanggal lama
    const bookingJam     = @json(substr($booking->jam_booking, 0, 5)); // jam lama format HH:MM

    function updateJamOptions() {
        const selectedDate = tanggalInput.value;
        const today        = new Date().toISOString().split('T')[0];
        const now          = new Date();
        const currentTime  = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

        jamSelect.innerHTML = '<option value="" disabled selected>-- Pilih jam --</option>';

        jamOperasional.forEach(jam => {
            const jamValue     = jam + ':00';
            const isToday      = selectedDate === today;
            const isPast       = isToday && jam <= currentTime;
            const isSameAsOld  = selectedDate === bookingTanggal && jam === bookingJam; // ✅

            const option       = document.createElement('option');
            option.value       = jamValue;

            if (isPast) {
                option.textContent = jam + ' WIB (Sudah lewat)';
                option.disabled    = true;
            } else if (isSameAsOld) {
                option.textContent = jam + ' WIB (Jadwal lama)'; // ✅
                option.disabled    = true;
            } else {
                option.textContent = jam + ' WIB';
                if (savedJam && jamValue === savedJam) option.selected = true;
            }

            jamSelect.appendChild(option);
        });
    }

    updateJamOptions();
    tanggalInput.addEventListener('change', updateJamOptions);
});
</script>
@endsection