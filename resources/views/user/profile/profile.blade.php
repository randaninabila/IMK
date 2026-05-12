@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] to-white pt-28 pb-16 px-4">
    <div class="max-w-4xl mx-auto">

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
        @endif

        @if(session('success_password'))
        <div class="mb-6 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success_password') }}
        </div>
        @endif

        {{-- HEADER PROFILE --}}
        <div class="flex items-center gap-6 mb-10">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=FFE4E6&color=3E382D&size=120"
                 class="w-20 h-20 rounded-full border-4 border-pink-200 object-cover"
                 alt="{{ $user->nama }}">
            <div>
                <h1 class="text-3xl font-bold text-[#3E382D]">{{ $user->nama }}</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $user->email }}</p>
                <span class="inline-block mt-2 text-[10px] uppercase bg-rose-100 text-rose-600 px-2 py-1 rounded-full font-bold">
                    {{ $user->role }}
                </span>
            </div>
        </div>

        {{-- TAB NAVIGATION --}}
        <div class="flex gap-2 border-b border-gray-200 mb-8" id="tabNav">
            <button onclick="switchTab('profile')" id="tab-profile"
                class="tab-btn px-5 py-2 text-sm font-semibold border-b-2 border-[#3E382D] text-[#3E382D] transition">
                Data Diri
            </button>
            <button onclick="switchTab('booking')" id="tab-booking"
                class="tab-btn px-5 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-[#3E382D] transition">
                Riwayat Booking
            </button>
            <button onclick="switchTab('password')" id="tab-password"
                class="tab-btn px-5 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-[#3E382D] transition">
                Ganti Password
            </button>
        </div>

        {{-- ================================
             TAB 1: DATA DIRI
        ================================ --}}
        <div id="tab-content-profile">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-lg font-bold text-[#3E382D] mb-6">Edit Data Diri</h2>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email (readonly) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" value="{{ $user->email }}"
                                class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-400 cursor-not-allowed"
                                readonly>
                            <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah.</p>
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $pelanggan->tanggal_lahir ?? '') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="alamat" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-[#3E382D] text-white px-8 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ================================
             TAB 2: RIWAYAT BOOKING
        ================================ --}}
        <div id="tab-content-booking" class="hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-lg font-bold text-[#3E382D] mb-6">Riwayat Booking</h2>

                @if($bookings->isEmpty())
                    <p class="text-center text-gray-400 py-10 text-sm">Belum ada riwayat booking.</p>
                @else
                    <div class="space-y-4">
                        @foreach($bookings as $booking)
                        <div class="border border-gray-100 rounded-xl p-5 hover:shadow-sm transition">
                            <div class="flex justify-between items-start flex-wrap gap-2">
                                <div>
                                    <p class="text-sm font-bold text-[#3E382D]">
                                        #{{ $booking->booking_id }} — {{ $booking->layanan ?: '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y') }}
                                        pukul {{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5 capitalize">
                                        Tipe: {{ $booking->tipe_booking }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    {{-- Status booking --}}
                                    @php
                                        $statusColor = match($booking->status) {
                                            'selesai'   => 'bg-green-100 text-green-600',
                                            'batal'     => 'bg-red-100 text-red-500',
                                            'pending'   => 'bg-yellow-100 text-yellow-600',
                                            'confirmed' => 'bg-blue-100 text-blue-600',
                                            'proses'    => 'bg-purple-100 text-purple-600',
                                            default     => 'bg-gray-100 text-gray-500',
                                        };
                                    @endphp
                                    <span class="text-[10px] uppercase font-bold px-2 py-1 rounded-full {{ $statusColor }}">
                                        {{ $booking->status }}
                                    </span>

                                    {{-- Total bayar --}}
                                    @if($booking->jumlah)
                                    <p class="text-sm font-bold text-[#3E382D] mt-2">
                                        Rp {{ number_format($booking->jumlah, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 capitalize">
                                        {{ $booking->metode_pembayaran ?? '-' }} •
                                        <span class="{{ $booking->status_bayar === 'verified' ? 'text-green-500' : 'text-yellow-500' }}">
                                            {{ $booking->status_bayar ?? '-' }}
                                        </span>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ================================
             TAB 3: GANTI PASSWORD
        ================================ --}}
        <div id="tab-content-password" class="hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-lg font-bold text-[#3E382D] mb-6">Ganti Password</h2>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5 max-w-md">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                            <input type="password" name="password_lama"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                            @error('password_lama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password_baru"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_baru_confirmation"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-200"
                                required>
                            @error('password_baru')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-[#3E382D] text-white px-8 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(tab) {
    // Sembunyikan semua konten
    ['profile', 'booking', 'password'].forEach(t => {
        document.getElementById('tab-content-' + t).classList.add('hidden');
        const btn = document.getElementById('tab-' + t);
        btn.classList.remove('border-[#3E382D]', 'text-[#3E382D]');
        btn.classList.add('border-transparent', 'text-gray-400');
    });

    // Tampilkan tab yang dipilih
    document.getElementById('tab-content-' + tab).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-' + tab);
    activeBtn.classList.add('border-[#3E382D]', 'text-[#3E382D]');
    activeBtn.classList.remove('border-transparent', 'text-gray-400');
}

// Buka tab password otomatis jika ada error password
@if(session('success_password') || $errors->has('password_lama') || $errors->has('password_baru'))
    switchTab('password');
@elseif(session('success'))
    switchTab('profile');
@endif
</script>

@endsection
