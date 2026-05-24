@extends('owner.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div 
    x-data="{
        showModal: false,
        openBranch: false,

        selectedBranch: 'Semua',
        selectedReports: [],
        selectedPeriod: 'month',
        startDate: '',
        endDate: '',
        showExportError: false,

        exportPDF() {

            this.showExportError = false;

            // wajib pilih report
            if (this.selectedReports.length === 0) {
                this.showExportError = true;
                return;
            }

            // kalau custom wajib isi tanggal
            if (
                this.selectedPeriod === 'custom' &&
                (!this.startDate || !this.endDate)
            ) {
                this.showExportError = true;
                return;
            }

            // validasi tanggal
            if (
                this.selectedPeriod === 'custom' &&
                this.startDate > this.endDate
            ) {
                this.showExportError = true;
                return;
            }

            const params = new URLSearchParams({
                branch: this.selectedBranch,
                reports: JSON.stringify(this.selectedReports),
                period: this.selectedPeriod,
                start_date: this.startDate,
                end_date: this.endDate
            });

            window.open('/export-pdf?' + params, '_blank');

            this.showModal = false;
        },

        toggleReport(report) {
            if (this.selectedReports.includes(report)) {
                this.selectedReports =
                    this.selectedReports.filter(r => r !== report)
            } else {
                this.selectedReports.push(report)
            }
        },

        get exportMessage() {

            if (this.selectedReports.length === 0) {
                return 'Select at least one report type';
            }

            if (this.selectedPeriod === 'custom') {

                if (!this.startDate || !this.endDate) {
                    return 'Select start and end date';
                }

                if (this.startDate > this.endDate) {
                    return 'Start date cannot exceed end date';
                }
            }

            return '';
        }
    }"
    class="pt-24 px-8 pb-8 bg-[#f6eaea] min-h-screen relative"
>

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-5xl font-bold text-[#2d2a26]">
                Welcome Back!
            </h1>

            <p class="text-gray-500 text-base">
                Your salon is humming with activity today. Here's your overview.
            </p>
        </div>

        <div class="flex gap-3">

            {{-- FILTER --}}
            <div class="relative">

                {{-- BUTTON --}}
                <button
                    @click="openBranch = !openBranch"
                    class="
                        bg-[#f45b69]
                        text-white
                        px-5 py-2.5
                        rounded-full
                        text-xs
                        font-medium
                        flex items-center gap-2
                        shadow-sm
                        hover:opacity-90
                        transition
                    "
                >

                    @if(!$selectedCabang)
                        Seluruh Cabang
                    @else
                        {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                    @endif

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

                {{-- DROPDOWN --}}
                <div
                    x-show="openBranch"
                    @click.outside="openBranch = false"
                    x-transition
                    class="
                        absolute
                        top-full
                        mt-2
                        left-0
                        w-64
                        bg-white
                        rounded-2xl
                        shadow-xl
                        border border-pink-100
                        overflow-hidden
                        z-50
                    "
                >

                    {{-- Semua --}}
                    <a
                        href="/dashboard"
                        class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-xs
                            {{ !$selectedCabang ? 'bg-pink-50 font-semibold text-[#FF5C77]' : 'text-gray-700' }}
                        "
                    >
                        <span>Seluruh Cabang</span>

                        @if(!$selectedCabang)
                            <span>✓</span>
                        @endif
                    </a>

                    {{-- Dynamic cabang --}}
                    @foreach($cabangs as $cabang)

                    <a
                        href="/dashboard?cabang={{ $cabang->cabang_id }}"
                        class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-xs
                            {{ $selectedCabang == $cabang->cabang_id
                                ? 'bg-pink-50 font-semibold text-[#FF5C77]'
                                : 'text-gray-700'
                            }}
                        "
                    >

                        <span>
                            {{ $cabang->nama_cabang }}
                        </span>

                        @if($selectedCabang == $cabang->cabang_id)
                            <span>✓</span>
                        @endif
                    </a>
                    @endforeach
                </div>

            </div>

            {{-- BUTTON OPEN MODAL --}}
            <button
                @click="
                    showModal = true;
                    selectedReports = [];
                    selectedPeriod = 'month';
                    startDate = '';
                    endDate = '';
                    showExportError = false;
                    document.body.classList.add('overflow-hidden');
                "
                class="bg-[#f8cdd0] text-[#2d2a26] px-5 py-2.5 rounded-full text-xs font-medium flex items-center gap-2 shadow-sm"
            >
                <span>📥</span>
                Download PDF Report
            </button>

        </div>

    </div>

    {{-- CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10 text-left">

        {{-- TODAY'S REVENUE --}}
        <div class="bg-white px-5 py-4 rounded-3xl shadow-sm border border-pink-50">
            <div class="flex justify-between mb-3 text-xl">
                <span class="p-2.5 bg-pink-100 rounded-xl text-pink-500">💵</span>
                <span class="text-xs font-bold bg-green-100 text-green-600 px-3 py-1.5 rounded-full h-fit">
                    +{{ $stats['todayBookings'] }} Today
                </span>
            </div>
            <p class="text-gray-500 font-semibold mb-1">Today's Revenue</p>
            <h3 class="text-xl font-bold text-pink-500 mb-1">{{ $stats['todayRevenue'] }}</h3>
            <p class="text-xs text-gray-400 mb-2">{{ $stats['selectedCabangName'] }}</p>

            @if(!$selectedCabang && count($stats['cabangBreakdown']) > 0)
                <div class="border-t border-pink-50 pt-2 space-y-1">
                    @foreach($stats['cabangBreakdown'] as $cb)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400 truncate max-w-[65%]">{{ $cb['nama'] }}</span>
                            <span class="font-semibold text-gray-600">{{ $cb['revenue'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- TODAY'S BOOKINGS --}}
        <div class="bg-white px-5 py-4 rounded-3xl shadow-sm border border-pink-50">
            <div class="flex justify-between mb-3 text-xl">
                <span class="p-2.5 bg-pink-100 rounded-xl text-pink-500">📅</span>
            </div>
            <p class="text-gray-500 font-semibold mb-1">Today's Bookings</p>
            <h3 class="text-xl font-bold text-pink-500 mb-1">{{ number_format($stats['todayBookings']) }}</h3>
            <p class="text-xs text-gray-400 mb-2">{{ $stats['selectedCabangName'] }}</p>

            @if(!$selectedCabang && count($stats['cabangBreakdown']) > 0)
                <div class="border-t border-pink-50 pt-2 space-y-1">
                    @foreach($stats['cabangBreakdown'] as $cb)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400 truncate max-w-[65%]">{{ $cb['nama'] }}</span>
                            <span class="font-semibold text-gray-600">{{ $cb['bookings'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- TODAY'S CUSTOMERS --}}
        <div class="bg-white px-5 py-4 rounded-3xl shadow-sm border border-pink-50">
            <div class="flex justify-between mb-3 text-xl">
                <span class="p-2.5 bg-pink-100 rounded-xl text-pink-500">👥</span>
                <span class="text-xs font-bold bg-green-100 text-green-600 px-3 py-1.5 rounded-full h-fit">+12%</span>
            </div>
            <p class="text-gray-500 font-semibold mb-1">Today's Customers</p>
            <h3 class="text-xl font-bold text-pink-500 mb-1">{{ number_format($stats['todayCustomers']) }}</h3>
            <p class="text-xs text-gray-400 mb-2">Member base</p>

            @if(!$selectedCabang && count($stats['cabangBreakdown']) > 0)
                <div class="border-t border-pink-50 pt-2 space-y-1">
                    @foreach($stats['cabangBreakdown'] as $cb)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400 truncate max-w-[65%]">{{ $cb['nama'] }}</span>
                            <span class="font-semibold text-gray-600">{{ $cb['customers'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ACTIVE STAFF --}}
        <div class="bg-white px-5 py-4 rounded-3xl shadow-sm border border-pink-50">
            <div class="flex justify-between mb-3 text-xl">
                <span class="p-2.5 bg-pink-100 rounded-xl text-pink-500">🆔</span>
            </div>
            <p class="text-gray-500 font-semibold mb-1">Active Staff Today</p>
            <h3 class="text-xl font-bold text-pink-500 mb-1">{{ number_format($stats['activeStaff']) }}</h3>
            <p class="text-xs text-gray-400 mb-2">{{ $stats['selectedCabangName'] }}</p>

            @if(!$selectedCabang && count($stats['cabangBreakdown']) > 0)
                <div class="border-t border-pink-50 pt-2 space-y-1">
                    @foreach($stats['cabangBreakdown'] as $cb)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400 truncate max-w-[65%]">{{ $cb['nama'] }}</span>
                            <span class="font-semibold text-gray-600">{{ $cb['staff'] }} staff</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- CHART --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">

        <div class="md:col-span-2 bg-white p-6 rounded-3xl shadow-sm">

            {{-- HEADER --}}
            <div class="flex flex-wrap md:flex-row md:items-center md:justify-between gap-4 mb-8">

                {{-- LEFT --}}
                <div>
                    <h4 class="text-xl font-bold">
                        Revenue Trends
                    </h4>

                    <p class="text-gray-400 text-xs mt-1">
                        Revenue from the last 6 months
                    </p>
                </div>

                {{-- RIGHT --}}
                @if($selectedCabang)

                    <div
                        class="
                            flex items-center gap-2
                            bg-pink-50
                            px-4 py-2
                            rounded-full
                            w-fit
                        "
                    >

                        <div class="w-3 h-2 rounded-full bg-[#FF7096]"></div>

                        <span class="text-xs font-semibold text-[#3F342D]">
                            {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                        </span>

                    </div>

                @else

                    <div class="flex flex-wrap justify-end items-center gap-x-6 gap-y-2 ml-auto">
                        @foreach($cabangs as $index => $cabang)

                            @php
                                $colors = ['#A00020', '#FF7096', '#FF8FA3', '#D63384'];
                            @endphp

                            <div class="flex items-center gap-2">
                                <div
                                    class="w-3 h-2 rounded-full"
                                    style="background-color: {{ $colors[$index % count($colors)] }}"
                                ></div>

                                <span class="text-xs font-semibold text-gray-700 whitespace-nowrap">
                                    {{ $cabang->nama_cabang }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- CANVAS --}}
            <div class="h-[260px] relative">

                <canvas id="revenueChart"></canvas>

                @if(collect($chartData)->sum() == 0)

                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">

                        <div class="text-4xl mb-3">
                            📭
                        </div>

                        <h5 class="font-semibold text-gray-600 text-base">
                            No revenue data yet
                        </h5>

                        <p class="text-xs text-gray-400 mt-1">
                            Completed appointments will appear here
                        </p>

                    </div>

                @endif

            </div>
        </div>

        {{-- SERVICES --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm">

            <h4 class="text-xl font-bold mb-2">
                Popular Services
            </h4>

            <div class="mb-8 flex items-center justify-between flex-wrap">
                {{-- Kiri --}}
                <p class="text-gray-400 text-xs whitespace-nowrap m-0">
                    Most booked services this month
                </p>

                {{-- Kanan --}}
                @if($selectedCabang)
                    <span class="inline-block px-3 py-1 bg-pink-50 rounded-full text-pink-600 font-semibold text-xs whitespace-nowrap ml-auto">
                        {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                    </span>
                @endif
            </div>

            <div class="space-y-5">

                @forelse($popularServices as $service)
                <div>
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span>
                            {{ $service->nama_layanan }}
                        </span>

                        <span class="text-gray-400 font-normal">
                            {{ $service->total }} orders
                        </span>
                    </div>

                    <div class="w-full bg-pink-50 h-2 rounded-full overflow-hidden">
                        <div
                            class="h-full rounded-full bg-[#FF7096]"
                            style="width: {{ min($service->total * 10, 100) }}%"
                        ></div>
                    </div>
                </div>

                @empty

                <div class="flex flex-col items-center justify-center text-center py-10">

                    <div class="text-4xl mb-3">
                        ✂️
                    </div>

                    <h5 class="font-semibold text-gray-600">
                        No services booked yet
                    </h5>

                    <p class="text-xs text-gray-400 mt-1">
                        Service statistics will appear after appointments
                    </p>

                </div>

                @endforelse

            </div>

        </div>

    </div>

    {{-- STAFF PERFORMANCE --}}
    <h4 class="text-xl font-bold mt-10 mb-6 text-left">Staff Performance</h4>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-left">
        @forelse($staffPerformance as $staff)
            <div class="bg-[#FFE4E9]/80 px-5 py-4 rounded-3xl flex items-center gap-4 border border-white shadow-sm">
                @if($staff->foto_profile)
                    <img
                        src="{{ asset('storage/' . $staff->foto_profile) }}"
                        class="w-16 h-16 rounded-full object-cover border-2 border-white"
                    >
                @else
                    <div class="w-16 h-16 rounded-full bg-[#F58C98] flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($staff->nama, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h5 class="font-bold text-xs text-gray-800 truncate">{{ $staff->nama }}</h5>
                    <p class="text-[11px] text-gray-500 mb-1 truncate">{{ $staff->cabang }}</p>
                    <div class="flex items-center text-gray-500 text-xs gap-1">
                        ({{ $staff->total_booking }} bookings)
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400">
                No staff activity recorded yet
            </div>
        @endforelse
    </div>

    @include('owner.dashboard.edashboard')
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const selectedCabangName = @json($selectedCabang 
            ? $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang 
            : 'Seluruh Cabang');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: selectedCabangName,
                    data: @json($chartData),
                    backgroundColor: '#FF7096',
                    borderRadius: 10,
                    barThickness: 20
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false, grid: { display: false } },
                    x: { grid: { display: false }, border: { display: false } }
                }
            }
        });
    }
});


document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardFilter', () => ({
        selectedCabang: @json($selectedCabang ?? null),
        submitFilter() {
            window.location.href = new URL(window.location.href).origin + 
                '/owner/dashboard?cabang=' + (this.selectedCabang || '');
        }
    }))
})
</script>
@endsection