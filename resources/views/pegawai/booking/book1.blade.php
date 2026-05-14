@extends('pegawai.app')

@section('content')

<div class="w-full px-4 py-4 font-sans text-[#3B302D]">

    {{-- TITLE --}}
    <div class="mb-5">
        <h1 class="text-[26px] font-bold leading-none">
            Booking
        </h1>
        <p class="mt-2 text-[16px]">
             Lihat detail lengkap booking yang masuk
        </p>
    </div>

    {{-- ONGOING --}}
    <div class="mb-7">

        <h2 class="text-[18px] font-bold text-[#3E382D] mb-2">
            Ongoing
        </h2>

        <div class="bg-white border-[3px] border-[#EAB7BF] rounded-[30px] px-8 py-7 flex items-start justify-between gap-8">

            {{-- LEFT --}}
            <div class="flex gap-6 flex-1">

                {{-- DATE --}}
                <div class="w-[68px] h-[68px] rounded-full bg-[#E8B1B6] flex flex-col items-center justify-center shrink-0">
                    <span class="text-[17px] font-semibold text-[#3E382D] leading-none">
                        25
                    </span>
                    <span class="text-[13px] text-[#3E382D]">
                        April
                    </span>
                </div>

                {{-- DETAIL --}}
                <div class="flex-1">

                    <div class="flex items-center gap-3 mb-2">

                        <h3 class="text-[17px] font-semibold text-[#3E382D]">
                            Mbak Andini Malinda
                        </h3>

                        <span class="bg-[#E8B1B6] text-[#3E382D] text-[12px] px-4 py-1 rounded-full font-semibold">
                            Waiting
                        </span>

                    </div>

                    <p class="text-[14px] text-[#3E382D]">
                        ● Gunting Rambut | Perawatan Rambut
                    </p>

                    <p class="text-[14px] text-[#3E382D] mb-2">
                        ● Totok Wajah | Perawatan Kulit
                    </p>

                    <h4 class="text-[17px] font-semibold text-[#3E382D] mb-4">
                        Waktu : 09:00-09:40
                    </h4>

                    <div class="space-y-2 w-[340px]">

                        <button class="w-full h-[40px] rounded-xl bg-[#F5A6AF] text-white font-semibold text-[16x] hover:opacity-90 transition">
                            Start Service
                        </button>

                        <button class="w-full h-[40px] rounded-xl border border-[#C98B93] text-[#3E382D] font-semibold text-[16px] bg-[#FFF9F9] hover:bg-[#FFF1F3] transition">
                            Cancel
                        </button>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="w-[380px] border-l border-[#E5B5BC] pl-8">

                <h3 class="text-[17px] font-semibold text-[#3E382D] mb-4">
                    Informasi Pelanggan
                </h3>

                <div class="flex gap-4 mb-4">

                    <img src="https://i.pravatar.cc/100?img=5" class="w-[70px] h-[70px] rounded-full object-cover">

                    <div>

                        <h4 class="text-[16px] font-semibold text-[#3E382D]">
                            Nama : Andini Zaskia
                        </h4>

                        <p class="text-[13px] text-[#3E382D]">
                            No Telepon : 08XX-XXXX-XXXX
                        </p>

                    </div>

                </div>

                <div>

                    <h4 class="text-[16px] font-semibold text-[#3E382D]">
                        Notes :
                    </h4>

                    <p class="text-[14px] text-[#3E382D] leading-relaxed">
                        Tolong potong gaya wolf cut, jangan terlalu pendek
                    </p>

                </div>

            </div>

        </div>

    </div>

    {{-- UPCOMING --}}
    <div>

        <h2 class="text-[18px] font-bold text-[#3E382D] mb-2">
            Upcoming Events
        </h2>

        <div class="space-y-5">

            {{-- CARD 1 --}}
<div class="bg-white border-[3px] border-[#EAB7BF] rounded-[30px] px-8 py-7 flex items-start justify-between gap-8">

    {{-- LEFT --}}
    <div class="flex gap-6 flex-1">

        {{-- DATE --}}
        <div class="w-[68px] h-[68px] rounded-full bg-[#E8B1B6] flex flex-col items-center justify-center shrink-0">
            <span class="text-[17px] font-semibold text-[#3E382D] leading-none">
                25
            </span>
            <span class="text-[13px] text-[#3E382D]">
                April
            </span>
        </div>

        {{-- DETAIL --}}
        <div class="flex-1">

            <div class="flex items-center gap-3 mb-2">

                <h3 class="text-[17px] font-semibold text-[#3E382D]">
                    Mbak Putri Kadariah
                </h3>

                <span class="bg-[#E8B1B6] text-[#3E382D] text-[12px] px-4 py-1 rounded-full font-semibold">
                    Waiting
                </span>

            </div>

            <p class="text-[14px] text-[#3E382D]">
                ● Totok Wajah | Perawatan Kulit
            </p>

            <p class="text-[14px] text-[#3E382D] mb-2">
                ● Facial Detox | Perawatan Kulit
            </p>

            <h4 class="text-[17px] font-semibold text-[#3E382D] mb-4">
                Waktu : 10:30-11:20
            </h4>

            <div class="space-y-2 w-[340px]">

                <button class="w-full h-[40px] rounded-xl bg-[#F5A6AF] text-white font-semibold text-[16px] hover:opacity-90 transition">
                    Start Service
                </button>

                <button class="w-full h-[40px] rounded-xl border border-[#C98B93] text-[#3E382D] font-semibold text-[16px] bg-[#FFF9F9] hover:bg-[#FFF1F3] transition">
                    Cancel
                </button>

            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="w-[380px] border-l border-[#E5B5BC] pl-8">

        <h3 class="text-[17px] font-semibold text-[#3E382D] mb-4">
            Informasi Pelanggan
        </h3>

        <div class="flex gap-4 mb-4">

            <img src="https://i.pravatar.cc/100?img=10" class="w-[70px] h-[70px] rounded-full object-cover">

            <div>

                <h4 class="text-[16px] font-semibold text-[#3E382D]">
                    Nama : Putri Kadariah
                </h4>

                <p class="text-[13px] text-[#3E382D]">
                    No Telepon : 08XX-XXXX-XXXX
                </p>

            </div>

        </div>

        <div>

            <h4 class="text-[16px] font-semibold text-[#3E382D]">
                Notes :
            </h4>

            <p class="text-[14px] text-[#3E382D] leading-relaxed">
                Kulit wajahku sensitif tolong kasih handuk yang bersih
            </p>

        </div>

    </div>

</div>


{{-- CARD 2 --}}
<div class="bg-white border-[3px] border-[#EAB7BF] rounded-[30px] px-8 py-7 flex items-start justify-between gap-8">

    {{-- LEFT --}}
    <div class="flex gap-6 flex-1">

        {{-- DATE --}}
        <div class="w-[68px] h-[68px] rounded-full bg-[#E8B1B6] flex flex-col items-center justify-center shrink-0">
            <span class="text-[17px] font-semibold text-[#3E382D] leading-none">
                25
            </span>
            <span class="text-[13px] text-[#3E382D]">
                April
            </span>
        </div>

        {{-- DETAIL --}}
        <div class="flex-1">

            <div class="flex items-center gap-3 mb-2">

                <h3 class="text-[17px] font-semibold text-[#3E382D]">
                    Mbak Sarah Azzahra
                </h3>

                <span class="bg-[#E8B1B6] text-[#3E382D] text-[12px] px-4 py-1 rounded-full font-semibold">
                    Waiting
                </span>

            </div>

            <p class="text-[14px] text-[#3E382D]">
                ● Facial Whitening | Perawatan Kulit
            </p>

            <p class="text-[14px] text-[#3E382D] mb-2">
                ● Creambath | Perawatan Badan
            </p>

            <h4 class="text-[17px] font-semibold text-[#3E382D] mb-4">
                Waktu : 14:10-15:00
            </h4>

            <div class="space-y-2 w-[340px]">

                <button class="w-full h-[40px] rounded-xl bg-[#F5A6AF] text-white font-semibold text-[16px] hover:opacity-90 transition">
                    Start Service
                </button>

                <button class="w-full h-[40px] rounded-xl border border-[#C98B93] text-[#3E382D] font-semibold text-[16px] bg-[#FFF9F9] hover:bg-[#FFF1F3] transition">
                    Cancel
                </button>

            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="w-[380px] border-l border-[#E5B5BC] pl-8">

        <h3 class="text-[17px] font-semibold text-[#3E382D] mb-4">
            Informasi Pelanggan
        </h3>

        <div class="flex gap-4 mb-4">

            <img src="https://i.pravatar.cc/100?img=15" class="w-[70px] h-[70px] rounded-full object-cover">

            <div>

                <h4 class="text-[16px] font-semibold text-[#3E382D]">
                    Nama : Sarah Azzahra
                </h4>

                <p class="text-[13px] text-[#3E382D]">
                    No Telepon : 08XX-XXXX-XXXX
                </p>

            </div>

        </div>

        <div>

            <h4 class="text-[16px] font-semibold text-[#3E382D]">
                Notes :
            </h4>

            <p class="text-[14px] text-[#3E382D] leading-relaxed">
                Tolong sterilkan alat sebelum pemakaian ya
            </p>

        </div>

    </div>

</div>

        </div>

    </div>

</div>

@endsection