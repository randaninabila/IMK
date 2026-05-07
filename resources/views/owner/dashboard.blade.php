@extends('owner.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<section 
    x-data="{
        showModal: false,

        selectedBranch: 'Semua',
        selectedReport: 'Financial'
    }"
    class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white px-20 py-20 pt-25 min-h-screen relative"
>

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-10 text-left">

        <div>
            <h2 class="text-6xl font-bold mb-3 tracking-tight">
                Welcome Back!
            </h2>

            <p class="text-gray-500 text-lg">
                Your salon is humming with activity today. Here's your overview.
            </p>
        </div>

        <div class="flex gap-3">

            {{-- FILTER --}}
            <button class="bg-[#FF8FA3] text-white px-6 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-sm">
                Seluruh Cabang 

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- BUTTON OPEN MODAL --}}
            <button
                @click="showModal = true"
                class="bg-[#FF5C77] text-white px-6 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-md"
            >
                <span>📥</span>
                Download PDF Report
            </button>

        </div>

    </div>

    {{-- CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 text-left">

        @php
            $cards = [
                ['label' => 'Total Revenue', 'val' => '2.500k', 'sub' => '+8.5% dari cabang Tuasan', 'icon' => '💵', 'badge' => '+12%'],
                ['label' => 'Total Bookings', 'val' => '148', 'sub' => '-2% dari cabang Tuasan', 'icon' => '📅', 'badge' => 'Today: 12'],
                ['label' => 'Active Customers', 'val' => '87', 'sub' => 'Member base', 'icon' => '👥', 'badge' => '+5%'],
                ['label' => 'Total Staff', 'val' => '15', 'sub' => '7 Laudendang | 8 Tuasan', 'icon' => '🆔', 'badge' => null],
            ];
        @endphp

        @foreach($cards as $card)

        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-pink-50">

            <div class="flex justify-between mb-6 text-2xl">

                <span class="p-3 bg-pink-100 rounded-2xl text-pink-500">
                    {{ $card['icon'] }}
                </span>

                @if($card['badge'])
                <span class="text-xs font-bold {{ str_contains($card['badge'], '+') ? 'bg-green-100 text-green-600' : 'text-gray-400' }} px-3 py-1.5 rounded-full h-fit">
                    {{ $card['badge'] }}
                </span>
                @endif

            </div>

            <p class="text-gray-500 font-semibold mb-1">
                {{ $card['label'] }}
            </p>

            <h3 class="text-4xl font-bold text-pink-500 mb-1">
                {{ $card['val'] }}
            </h3>

            <p class="text-xs text-gray-400">
                {{ $card['sub'] }}
            </p>

        </div>

        @endforeach

    </div>

    {{-- CHART --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-left">

        <div class="md:col-span-2 bg-white p-10 rounded-[3rem] shadow-sm">

            <div class="flex justify-between items-center mb-8">

                <div>
                    <h4 class="text-2xl font-bold">
                        Revenue Trends
                    </h4>

                    <p class="text-gray-400 text-sm">
                        6 bulan terakhir
                    </p>
                </div>

                <div class="flex gap-6 text-xs font-bold">
                    <span class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-[#A00020] rounded-full"></div>
                        Cabang Laudendang
                    </span>

                    <span class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-[#FF7096] rounded-full"></div>
                        Cabang Tuasan
                    </span>
                </div>

            </div>

            <div style="height: 300px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>

        </div>

        {{-- SERVICES --}}
        <div class="bg-white p-10 rounded-[3rem] shadow-sm">

            <h4 class="text-2xl font-bold mb-2">
                Popular Services
            </h4>

            <div class="flex gap-4 text-[11px] mb-8 font-bold">
                <span class="text-[#A00020]">
                    ● Cabang Laudendang
                </span>

                <span class="text-[#FF7096]">
                    ● Cabang Tuasan
                </span>
            </div>

            <div class="space-y-8">

                @foreach([
                    ['name' => 'Hair Spa', 'val' => 62, 'color' => '#A00020'],
                    ['name' => 'Inai', 'val' => 58, 'color' => '#FF7096'],
                    ['name' => 'Body Massage', 'val' => 42, 'color' => '#A00020'],
                    ['name' => 'Lulur', 'val' => 32, 'color' => '#A00020']
                ] as $service)

                <div>

                    <div class="flex justify-between text-sm font-bold mb-2">

                        <span>
                            {{ $service['name'] }}
                        </span>

                        <span class="text-gray-400 font-normal">
                            {{ $service['val'] }} orders
                        </span>

                    </div>

                    <div class="w-full bg-pink-50 h-3 rounded-full overflow-hidden">

                        <div
                            class="h-full rounded-full"
                            style="width: {{ $service['val'] }}%; background-color: {{ $service['color'] }}"
                        ></div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

    {{-- STAFF --}}
    <h4 class="text-3xl font-bold mt-12 mb-6 text-left">
        Staff Performance
    </h4>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-left">

        @for($i=0; $i<4; $i++)

        <div class="bg-[#FFE4E9]/80 p-5 rounded-3xl flex items-center gap-4 border border-white shadow-sm">

            <div class="w-16 h-16 bg-[#FF8FA3] rounded-full flex-shrink-0 border-2 border-white"></div>

            <div>

                <h5 class="font-bold text-sm text-gray-800">
                    Dr. Zahra Khairunnisa
                </h5>

                <p class="text-[11px] text-gray-500 mb-1">
                    Cabang Tuasan
                </p>

                <div class="flex items-center text-yellow-500 text-xs gap-1">
                    ★
                    <span class="text-gray-800 font-bold">
                        5.0
                    </span>
                </div>

            </div>

        </div>

        @endfor

    </div>

    {{-- MODAL --}}
    <div
        x-show="showModal"
        x-transition
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4"
    >

        <div class="bg-white w-full max-w-3xl rounded-[34px] px-6 py-5 shadow-2xl">

            {{-- TITLE --}}
            <h2 class="text-[20px] font-bold text-[#3F342D] mb-3"
                style="font-family: 'Playfair Display', serif;">
                Select Branch
            </h2>

            {{-- BRANCH --}}
            <div class="grid grid-cols-3 gap-3">

                <button
                    @click="selectedBranch='Semua'"
                    :class="selectedBranch === 'Semua'
                        ? 'border-2 border-pink-500 bg-white'
                        : 'bg-[#FCEDEF]'"
                    class="rounded-xl px-3 py-2"
                >
                    Semua
                </button>

                <button
                    @click="selectedBranch='Laudendang'"
                    :class="selectedBranch === 'Laudendang'
                        ? 'border-2 border-pink-500 bg-white'
                        : 'bg-[#FCEDEF]'"
                    class="rounded-xl px-3 py-2"
                >
                    Laudendang
                </button>

                <button
                    @click="selectedBranch='Tuasan'"
                    :class="selectedBranch === 'Tuasan'
                        ? 'border-2 border-pink-500 bg-white'
                        : 'bg-[#FCEDEF]'"
                    class="rounded-xl px-3 py-2"
                >
                    Tuasan
                </button>

            </div>

            {{-- REPORT TYPE --}}
            <h2 class="text-[20px] font-bold text-[#3F342D] mt-5 mb-3"
                style="font-family: 'Playfair Display', serif;">
                Select Report Type
            </h2>

            <div class="grid grid-cols-2 gap-4">

                <button
                    @click="selectedReport='Financial'"
                    :class="selectedReport === 'Financial'
                        ? 'border-2 border-pink-500 bg-white'
                        : 'bg-[#FCEDEF]'"
                    class="rounded-[16px] p-3"
                >
                    Financial <p class="text-[11px] text-[#7A6A63]">
                            Revenue, expenses, and taxes.
                        </p>
                </button>

                <button
                    @click="selectedReport='Services'"
                    :class="selectedReport === 'Services'
                        ? 'border-2 border-pink-500 bg-white'
                        : 'bg-[#FCEDEF]'"
                    class="rounded-[16px] p-3"
                >
                    Services <p class="text-[11px] text-[#7A6A63]">
                            Booking trends and popularity.
                        </p>
                </button>

                <button
                    @click="selectedReport='Employees'"
                    :class="selectedReport === 'Employees'
                        ? 'border-2 border-pink-500 bg-white'
                        : 'bg-[#FCEDEF]'"
                    class="rounded-[16px] p-3"
                >
                    Employees <p class="text-[11px] text-[#7A6A63]">
                            Staff performance & hours.
                        </p>
                </button>

                <button
                    @click="selectedReport='Customers'"
                    :class="selectedReport === 'Customers'
                        ? 'border-2 border-pink-500 bg-white'
                        : 'bg-[#FCEDEF]'"
                    class="rounded-[16px] p-3"
                >
                    Customers <p class="text-[11px] text-[#7A6A63]">
                            Demographics and retention.
                        </p>
                </button>

            </div>

            {{-- DATE --}}
            <h2 class="text-[20px] font-bold text-[#3F342D] mt-5 mb-3">
                Date Range
            </h2>

            <div class="flex items-end gap-3">

                <input
                    type="date"
                    onclick="this.showPicker()"
                    class="w-full rounded-xl bg-[#FFDDE3] px-5 py-3"
                >

                <span class="self-center">→</span>

                <input
                    type="date"
                    onclick="this.showPicker()"
                    class="w-full rounded-xl bg-[#FFDDE3] px-5 py-3"
                >

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 mt-8">

                <button
                    @click="showModal = false"
                    class="px-7 py-2.5 rounded-full bg-[#F6EFEF]"
                >
                    Cancel
                </button>

                <button
                    class="px-7 py-2.5 rounded-full bg-[#F58C98] text-white"
                >
                    Export PDF
                </button>

            </div>

        </div>

    </div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const ctx = document.getElementById('revenueChart');

    if (ctx) {

        new Chart(ctx, {
            type: 'bar',

            data: {
                labels: ['Nov', 'Des', 'Jan', 'Feb', 'March', 'Apr'],

                datasets: [
                    {
                        label: 'Laudendang',
                        data: [40, 75, 60, 48, 40, 45],
                        backgroundColor: '#A00020',
                        borderRadius: 10,
                        barThickness: 20
                    },

                    {
                        label: 'Tuasan',
                        data: [70, 55, 75, 25, 75, 55],
                        backgroundColor: '#FF7096',
                        borderRadius: 10,
                        barThickness: 20
                    }
                ]
            },

            options: {
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        display: false,
                        grid: {
                            display: false
                        }
                    },

                    x: {
                        grid: {
                            display: false
                        },

                        border: {
                            display: false
                        }
                    }
                }
            }
        });

    }

});
</script>

@endsection