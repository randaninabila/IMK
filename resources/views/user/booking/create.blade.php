@extends('user.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white py-16 px-6">

    <div class="max-w-5xl mx-auto">

        {{-- TITLE --}}
        <div class="text-center mb-10 mt-14">
            <h1 class="text-4xl font-bold text-[#3E382D]">
                Booking Layanan
            </h1>

            <p class="text-sm text-gray-500 mt-2">
                Isi data booking treatment kamu
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT --}}
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-pink-100 p-8">

                <form action="{{ route('pelanggan.booking.store') }}" method="POST">
                    @csrf

                    <input type="hidden"
                           name="layanan_cabang_id"
                           value="{{ $layanan->layanan_cabang_id }}">

                    {{-- Nama --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            Nama Lengkap
                        </label>

                        <input type="text"
                               name="nama"
                               class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            Tanggal Booking
                        </label>

                        <input type="date"
                               name="tanggal"
                               class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200">
                    </div>

                    {{-- Jam --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            Jam Booking
                        </label>

                        <input type="time"
                               name="jam"
                               class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200">
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-[#3E382D] mb-2">
                            Catatan
                        </label>

                        <textarea name="catatan"
                                  rows="4"
                                  class="w-full rounded-2xl border border-pink-100 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-rose-200"
                                  placeholder="Tambahkan catatan jika ada"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-rose-400 hover:bg-rose-500 text-white font-bold py-4 rounded-2xl transition">
                        Booking Sekarang
                    </button>

                </form>

            </div>

            {{-- RIGHT --}}
            <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden h-fit">

                <img src="{{ $layanan->cover_foto }}"
                     class="w-full h-56 object-cover"
                     onerror="this.onerror=null;this.src='{{ asset('layanan/default.jpg') }}';">

                <div class="p-6">

                    <h2 class="text-2xl font-bold text-[#3E382D] mb-2">
                        {{ $layanan->nama_layanan }}
                    </h2>

                    <div class="space-y-4 text-sm">

                        <div>
                            <p class="text-gray-400 mb-1">Cabang</p>
                            <p class="font-semibold text-[#3E382D]">
                                {{ $layanan->nama_cabang }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400 mb-1">Alamat</p>
                            <p class="font-semibold text-[#3E382D]">
                                {{ $layanan->alamat }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400 mb-1">Durasi</p>
                            <p class="font-semibold text-[#3E382D]">
                                {{ $layanan->durasi }} menit
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400 mb-1">Harga</p>

                            @if($layanan->harga_promo)
                                <p class="text-xs text-gray-400 line-through">
                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                </p>

                                <p class="text-2xl font-bold text-rose-400">
                                    Rp {{ number_format($layanan->harga_promo, 0, ',', '.') }}
                                </p>
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

</div>

@endsection