{{-- resources/views/owner/report/export-report.blade.php --}}

@extends('owner.app')

@section('content')

<div 
    x-data="{
        selectedBranch: 'Semua',
        selectedReport: 'Financial'
    }"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4"
>

    <div class="bg-white w-full max-w-4xl rounded-[40px] p-10 shadow-2xl">

        {{-- SELECT BRANCH --}}
        <h2 class="text-[38px] font-bold text-[#3F342D] mb-6"
            style="font-family: Playfair Display, serif;">
            Select Branch
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Semua --}}
            <button
                @click="selectedBranch='Semua'"
                :class="selectedBranch === 'Semua'
                    ? 'border-2 border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-2xl px-6 py-4 flex items-center gap-3 transition-all duration-300"
            >
                <div
                    :class="selectedBranch === 'Semua'
                        ? 'border-pink-500'
                        : 'border-gray-300'"
                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                >
                    <div
                        x-show="selectedBranch === 'Semua'"
                        class="w-2.5 h-2.5 bg-pink-500 rounded-full"
                    ></div>
                </div>

                <span class="font-medium text-[17px] text-[#4A3B35]">
                    Semua
                </span>
            </button>

            {{-- Laudendang --}}
            <button
                @click="selectedBranch='Laudendang'"
                :class="selectedBranch === 'Laudendang'
                    ? 'border-2 border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-2xl px-6 py-4 flex items-center gap-3 transition-all duration-300"
            >
                <div
                    :class="selectedBranch === 'Laudendang'
                        ? 'border-pink-500'
                        : 'border-gray-300'"
                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                >
                    <div
                        x-show="selectedBranch === 'Laudendang'"
                        class="w-2.5 h-2.5 bg-pink-500 rounded-full"
                    ></div>
                </div>

                <span class="font-medium text-[17px] text-[#4A3B35]">
                    Laudendang
                </span>
            </button>

            {{-- Tuasan --}}
            <button
                @click="selectedBranch='Tuasan'"
                :class="selectedBranch === 'Tuasan'
                    ? 'border-2 border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-2xl px-6 py-4 flex items-center gap-3 transition-all duration-300"
            >
                <div
                    :class="selectedBranch === 'Tuasan'
                        ? 'border-pink-500'
                        : 'border-gray-300'"
                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                >
                    <div
                        x-show="selectedBranch === 'Tuasan'"
                        class="w-2.5 h-2.5 bg-pink-500 rounded-full"
                    ></div>
                </div>

                <span class="font-medium text-[17px] text-[#4A3B35]">
                    Tuasan
                </span>
            </button>

        </div>

        {{-- REPORT TYPE --}}
        <h2 class="text-[38px] font-bold text-[#3F342D] mt-14 mb-6"
            style="font-family: Playfair Display, serif;">
            Select Report Type
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Financial --}}
            <button
                @click="selectedReport='Financial'"
                :class="selectedReport === 'Financial'
                    ? 'border-2 border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[28px] p-7 text-left transition-all duration-300"
            >
                <div class="flex gap-5">

                    <div class="w-14 h-14 rounded-full bg-pink-200 flex items-center justify-center shrink-0">
                        💳
                    </div>

                    <div>
                        <h3 class="text-[30px] font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Financial
                        </h3>

                        <p class="text-sm text-[#7A6A63] mt-1">
                            Revenue, expenses, and taxes.
                        </p>
                    </div>

                </div>
            </button>

            {{-- Services --}}
            <button
                @click="selectedReport='Services'"
                :class="selectedReport === 'Services'
                    ? 'border-2 border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[28px] p-7 text-left transition-all duration-300"
            >
                <div class="flex gap-5">

                    <div class="w-14 h-14 rounded-full bg-pink-200 flex items-center justify-center shrink-0">
                        ✂️
                    </div>

                    <div>
                        <h3 class="text-[30px] font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Services
                        </h3>

                        <p class="text-sm text-[#7A6A63] mt-1">
                            Booking trends and popularity.
                        </p>
                    </div>

                </div>
            </button>

            {{-- Employees --}}
            <button
                @click="selectedReport='Employees'"
                :class="selectedReport === 'Employees'
                    ? 'border-2 border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[28px] p-7 text-left transition-all duration-300"
            >
                <div class="flex gap-5">

                    <div class="w-14 h-14 rounded-full bg-pink-200 flex items-center justify-center shrink-0">
                        👨‍💼
                    </div>

                    <div>
                        <h3 class="text-[30px] font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Employees
                        </h3>

                        <p class="text-sm text-[#7A6A63] mt-1">
                            Staff performance & hours.
                        </p>
                    </div>

                </div>
            </button>

            {{-- Customers --}}
            <button
                @click="selectedReport='Customers'"
                :class="selectedReport === 'Customers'
                    ? 'border-2 border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[28px] p-7 text-left transition-all duration-300"
            >
                <div class="flex gap-5">

                    <div class="w-14 h-14 rounded-full bg-pink-200 flex items-center justify-center shrink-0">
                        👥
                    </div>

                    <div>
                        <h3 class="text-[30px] font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Customers
                        </h3>

                        <p class="text-sm text-[#7A6A63] mt-1">
                            Demographics and retention.
                        </p>
                    </div>

                </div>
            </button>

        </div>

        {{-- DATE RANGE --}}
        <h2 class="text-[38px] font-bold text-[#3F342D] mt-14 mb-6"
            style="font-family: Playfair Display, serif;">
            Date Range
        </h2>

        <div class="flex flex-col md:flex-row items-center gap-5">

            {{-- START DATE --}}
            <div class="w-full">
                <label class="block text-sm text-gray-500 mb-2">
                    Start Date
                </label>

                <div class="relative">

                    <input
                        type="date"
                        class="w-full rounded-full bg-[#FFDDE3] px-6 py-4 outline-none text-[#4A3B35]"
                    >

                    <div class="absolute right-5 top-1/2 -translate-y-1/2">
                        📅
                    </div>

                </div>
            </div>

            {{-- ARROW --}}
            <div class="text-3xl text-gray-500 mt-6 hidden md:block">
                →
            </div>

            {{-- END DATE --}}
            <div class="w-full">
                <label class="block text-sm text-gray-500 mb-2">
                    End Date
                </label>

                <div class="relative">

                    <input
                        type="date"
                        class="w-full rounded-full bg-[#FFDDE3] px-6 py-4 outline-none text-[#4A3B35]"
                    >

                    <div class="absolute right-5 top-1/2 -translate-y-1/2">
                        📅
                    </div>

                </div>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-5 mt-16">

            <button
                class="px-10 py-4 rounded-full bg-[#F7EFEF] hover:opacity-80 transition font-semibold text-lg"
            >
                Cancel
            </button>

            <button
                class="px-10 py-4 rounded-full bg-[#F58C98] hover:opacity-80 transition font-semibold text-lg"
            >
                Export PDF
            </button>

        </div>

    </div>

</div>

@endsection