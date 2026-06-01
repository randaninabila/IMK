@extends('user.app')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6">
    <div class="max-w-5xl mx-auto">

        {{-- TITLE --}}
        <div class="text-center mb-10 mt-14">
            <h1 class="text-7xl font-bold text-[#3E382D]">Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-2">Pilih metode pembayaran kamu</p>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

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

            {{-- FORM PEMBAYARAN --}}
            <div class="lg:col-span-2 space-y-6">
                <form action="{{ route('pelanggan.payment.process', $booking->booking_id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="paymentForm">
                    @csrf

                    {{-- PILIH METODE --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-pink-100 p-8">
                        <h2 class="text-lg font-bold text-[#3E382D] mb-6">Pilih Metode Pembayaran</h2>
                        <div class="space-y-4 text-left">

                            {{-- QRIS --}}
                            <label class="flex items-center gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 border-pink-100 hover:border-rose-300 has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50" id="label-qris">
                                <input type="radio" name="metode_pembayaran" value="qris" class="accent-rose-400 peer" onchange="togglePaymentMethod('qris')" {{ old('metode_pembayaran') == 'qris' ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xs shrink-0">QRIS</div>
                                        <div>
                                            <p class="font-semibold text-[#3E382D]">QRIS</p>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs bg-blue-100 text-blue-600 font-semibold px-2 py-0.5 rounded-full">Online</span>
                            </label>

                            {{-- TUNAI --}}
                            <label class="flex items-center gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 border-pink-100 hover:border-rose-300 has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50" id="label-cash">
                                <input type="radio" name="metode_pembayaran" value="cash" class="accent-rose-400" onchange="togglePaymentMethod('cash')" {{ old('metode_pembayaran') == 'cash' ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#3E382D]">Tunai (Cash)</p>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs bg-green-100 text-green-600 font-semibold px-2 py-0.5 rounded-full">Di Lokasi</span>
                            </label>

                        </div>
                    </div>

                    {{-- INSTRUKSI QRIS --}}
                    <div id="qrisSection" class="bg-white rounded-3xl shadow-sm border border-pink-100 p-8 hidden">
                        <h2 class="text-lg font-bold text-[#3E382D] mb-4">Instruksi Pembayaran QRIS</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            {{-- QR Code --}}
                            <div class="text-center">
                                <div class="bg-gray-50 border-2 border-dashed border-pink-200 rounded-2xl p-6 inline-block">
                                    {{-- Ganti src dengan path QRIS statis salon kamu --}}
                                    <img src="{{ asset('images/qris-salon.png') }}" alt="QRIS Salon" class="w-48 h-48 object-contain mx-auto rounded-xl bg-white shadow-sm" 
                                         onerror="this.src='https://via.placeholder.com/192x192?text=QR+Salon'">
                                    <p class="text-xs text-gray-400 mt-2">Scan dengan m-banking / e-wallet</p>
                                </div>
                            </div>

                            {{-- Langkah --}}
                            <div class="space-y-3">
                                <p class="font-semibold text-[#3E382D] text-sm mb-3">Cara bayar QRIS:</p>
                                @foreach(['Buka aplikasi m-banking atau e-wallet', 'Pilih menu Scan QR / QRIS', 'Scan Kode QR di samping', 'Masukkan nominal: Rp ' . number_format($total, 0, ',', '.'), 'Konfirmasi pembayaran', 'Screenshot bukti transfer', 'Upload bukti di bawah'] as $i => $step)
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center text-xs font-bold shrink-0">{{ $i + 1 }}</div>
                                        <p class="text-sm text-gray-600">{{ $step }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Upload Bukti --}}
                        <div class="mt-6 border-t border-pink-100 pt-6">
                            <label class="block text-sm font-semibold text-[#3E382D] mb-2">Upload Bukti Pembayaran <span class="text-rose-400">*</span></label>
                            <div class="border-2 border-dashed border-pink-200 rounded-2xl p-6 text-center cursor-pointer hover:border-rose-300 transition" onclick="document.getElementById('buktiInput').click()">
                                <input type="file" id="buktiInput" name="bukti_pembayaran" accept="image/*" class="hidden" onchange="previewBukti(event)">
                                <div id="uploadPlaceholder">
                                    <svg class="w-10 h-10 text-rose-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Klik untuk upload bukti transfer</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP – maks. 2MB</p>
                                </div>
                                <img id="buktiPreview" src="" class="hidden mx-auto max-h-48 rounded-xl object-contain mt-2">
                            </div>
                            @error('bukti_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- INFO TUNAI --}}
                    <div id="cashSection" class="bg-white rounded-3xl shadow-sm border border-pink-100 p-8 hidden">
                        <h2 class="text-lg font-bold text-[#3E382D] mb-4">Bayar di Lokasi</h2>
                        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-green-800 space-y-1">
                                    <p class="font-semibold">Menunggu Verifikasi Admin</p>
                                    <p>Silakan bayar tunai sebesar <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong> saat kedatangan. Admin akan memverifikasi & mengkonfirmasi pesanan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 space-y-1">
                            <p>• Hadir sesuai tanggal & jam pemesanan</p>
                            <p>• Uang pas sangat disarankan</p>
                            <p>• Tunjukkan kode pesanan ke kasir</p>
                        </div>
                    </div>

                    {{-- TOMBOL --}}
                    <div id="submitSection" class="hidden">
                        <button type="submit" id="submitBtn" class="w-full bg-rose-400 hover:bg-rose-500 text-white font-bold py-4 rounded-2xl transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="submitLabel">Konfirmasi Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- RINGKASAN BOOKING --}}
            <div class="space-y-4">
                <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden sticky top-24">
                    <div class="bg-gradient-to-r from-rose-400 to-pink-400 p-5">
                        <p class="text-white text-xs font-semibold opacity-80 uppercase tracking-wide">Ringkasan Pesanan</p>
                        <p class="text-white text-lg font-bold mt-0.5">#{{ str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="p-6 space-y-4 text-sm">

                        {{-- List Layanan --}}
                        @forelse($layananList as $item)
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-[#3E382D]">{{ $item->nama_layanan }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->durasi }} menit</p>
                                </div>
                                {{-- Harga hanya tampil jika ada kolom harga --}}
                                @if(isset($item->harga))
                                    <p class="font-semibold text-[#3E382D] whitespace-nowrap">
                                        Rp {{ number_format($item->harga_promo > 0 ? $item->harga_promo : $item->harga, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">Layanan dalam paket</p>
                        @endforelse
                        
                        {{-- TOTAL --}}
                        <div class="border-t border-pink-100 pt-4 flex items-center justify-between">
                            <p class="font-semibold text-[#3E382D]">Total Pembayaran</p>
                            <p class="text-xl font-bold text-rose-400">
                                Rp {{ number_format($total ?? 0, 0, ',', '.') }} {{-- ✅ Fallback ke 0 jika null --}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function togglePaymentMethod(method) {
    document.getElementById('qrisSection').classList.add('hidden');
    document.getElementById('cashSection').classList.add('hidden');
    document.getElementById('submitSection').classList.remove('hidden');

    if (method === 'qris') {
        document.getElementById('qrisSection').classList.remove('hidden');
        document.getElementById('submitLabel').textContent = 'Upload Bukti & Konfirmasi';
    } else {
        document.getElementById('cashSection').classList.remove('hidden');
        document.getElementById('submitLabel').textContent = 'Konfirmasi Bayar di Lokasi';
    }
}

function previewBukti(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('buktiPreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

@if(old('metode_pembayaran'))
    togglePaymentMethod('{{ old('metode_pembayaran') }}');
@endif

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const method = document.querySelector('input[name="metode_pembayaran"]:checked');
    if (!method) { e.preventDefault(); alert('Pilih metode pembayaran terlebih dahulu!'); return; }
    if (method.value === 'qris' && document.getElementById('buktiInput').files.length === 0) {
        e.preventDefault(); alert('Upload bukti pembayaran QRIS terlebih dahulu!'); return;
    }
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...`;
});
</script>
@endsection