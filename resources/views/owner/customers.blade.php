@extends('owner.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="pt-24 px-8 pb-8 bg-[#f6eaea] min-h-screen relative">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">

        <div>

            <h2 class="
                text-5xl
                font-bold
                mb-3
                tracking-tight
            ">
                Customer Insights
            </h2>

            <p class="
                text-gray-500
                text-lg
                italic
            ">
                Strategic overview of customer behavior.
            </p>

        </div>

        <div class="flex gap-3">

            {{-- CABANG --}}
            <div class="relative" x-data="{ openBranch: false }">

                <button @click="openBranch = !openBranch" class="
                        bg-[#f45b69]
                        text-white
                        px-6 py-2.5
                        rounded-full
                        text-sm
                        font-medium
                        flex items-center gap-2
                        shadow-sm
                        hover:opacity-90
                        transition
                    ">

                    @if($selectedCabang == 'all')

                    Seluruh Cabang

                    @else

                    {{
                            $cabangs
                                ->firstWhere(
                                    'cabang_id',
                                    $selectedCabang
                                )?->nama_cabang
                        }}

                    @endif

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />

                    </svg>

                </button>

                <div x-show="openBranch" @click.outside="openBranch = false" x-transition class="
                        absolute
                        top-full mt-2 left-0
                        w-64
                        bg-white
                        rounded-2xl
                        shadow-xl
                        border border-pink-100
                        overflow-hidden
                        z-50
                    ">

                    <a href="
                            {{
                                route('owner.customer', [
                                    'cabang' => 'all',
                                    'bulan' => $selectedMonth
                                ])
                            }}
                        " class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-sm
                        ">

                        Seluruh Cabang

                    </a>

                    @foreach($cabangs as $cabang)

                    <a href="
                            {{
                                route('owner.customer', [
                                    'cabang' => $cabang->cabang_id,
                                    'bulan' => $selectedMonth
                                ])
                            }}
                        " class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-sm
                        ">

                        {{ $cabang->nama_cabang }}

                    </a>

                    @endforeach

                </div>

            </div>

            {{-- BULAN --}}
            <div class="relative" x-data="{ openMonth: false }">

                <button @click="openMonth = !openMonth" class="
                        bg-white
                        border border-[#f3dede]
                        text-[#2d2a26]
                        px-6 py-2.5
                        rounded-full
                        text-sm
                        font-medium
                        flex items-center gap-2
                        shadow-sm
                        hover:bg-[#fff7f7]
                        transition
                    ">

                    {{
                        collect($months)
                            ->firstWhere(
                                'value',
                                $selectedMonth
                            )['label']
                    }}

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />

                    </svg>

                </button>

                <div x-show="openMonth" @click.outside="openMonth = false" x-transition class="
                        absolute
                        top-full mt-2 right-0
                        w-52
                        bg-white
                        rounded-2xl
                        shadow-xl
                        border border-pink-100
                        overflow-hidden
                        z-50
                    ">

                    @foreach($months as $month)

                    <a href="
                            {{
                                route('owner.customer', [
                                    'cabang' => $selectedCabang,
                                    'bulan' => $month['value']
                                ])
                            }}
                        " class="
                            flex items-center justify-between
                            px-5 py-3
                            hover:bg-pink-50
                            transition
                            text-sm
                        ">

                        {{ $month['label'] }}

                    </a>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        @php

        $cards = [

        [
        'title' => 'Active Customers',
        'value' => $stats['active_customers'],
        'desc' => 'Completed bookings',
        'icon' => '👥'
        ],

        [
        'title' => 'Before 12 PM',
        'value' => $reservationHabits['morning'] . '%',
        'desc' => 'Morning activity',
        'icon' => '☀️'
        ],

        [
        'title' => 'After 12 PM',
        'value' =>
        (
        $reservationHabits['afternoon']
        +
        $reservationHabits['evening']
        ) . '%',

        'desc' => 'Afternoon & evening',
        'icon' => '🌙'
        ],

        ];

        @endphp

        @foreach($cards as $card)

        <div class="
            bg-white
            rounded-3xl
            px-5 py-4
            shadow-sm
            border border-pink-50
        ">

            <div class="
                flex items-center
                justify-between
                mb-4
            ">

                <div class="
                    w-10 h-10
                    rounded-xl
                    bg-[#FFE4E6]
                    flex items-center justify-center
                    text-base
                ">

                    {{ $card['icon'] }}

                </div>

            </div>

            <p class="
                text-gray-500
                text-xs
                mb-1
            ">

                {{ $card['title'] }}

            </p>

            <h3 class="
                text-2xl
                font-bold
                text-[#f45b69]
                leading-none
                mb-2
            ">

                {{ $card['value'] }}

            </h3>

            <p class="
                text-xs
                text-gray-400
            ">

                {{ $card['desc'] }}

            </p>

        </div>

        @endforeach

    </div>


    {{-- CHART + INSIGHT --}}
    <div class="
        grid grid-cols-1
        md:grid-cols-3
        gap-6
        text-left
        mb-10
    ">

        {{-- CHART --}}
        <div class="
            md:col-span-2
            bg-white
            p-6
            rounded-3xl
            shadow-sm
            border border-pink-50
        ">

            <div class="
                flex justify-between
                items-center
                mb-8
            ">

                <div>

                    <h4 class="text-xl font-bold">
                        Customer Growth
                    </h4>

                    <div class="mt-3 flex items-center gap-3">

                        {{-- TOGGLE --}}
                        <div class="
                            flex items-center
                            bg-[#f8e4e4]
                            rounded-full
                            p-1
                            gap-1
                        ">

                            {{-- DAILY --}}
                            <a href="
                                    {{
                                        route('owner.customer', [
                                            'cabang' => $selectedCabang,
                                            'bulan' => $selectedMonth,
                                            'view' => 'daily'
                                        ])
                                    }}
                                " class="
                                    px-3 py-1
                                    rounded-full
                                    text-[11px]
                                    font-semibold
                                    transition-all duration-200

                                    {{
                                        $viewType == 'daily'
                                            ? 'bg-[#f45b69] text-white shadow-sm'
                                            : 'text-gray-500 hover:text-[#f45b69]'
                                    }}
                                ">

                                Daily

                            </a>

                            {{-- MONTHLY --}}
                            <a href="
                                    {{
                                        route('owner.customer', [
                                            'cabang' => $selectedCabang,
                                            'bulan' => $selectedMonth,
                                            'view' => 'monthly'
                                        ])
                                    }}
                                " class="
                                    px-3 py-1
                                    rounded-full
                                    text-[11px]
                                    font-semibold
                                    transition-all duration-200

                                    {{
                                        $viewType == 'monthly'
                                            ? 'bg-[#f45b69] text-white shadow-sm'
                                            : 'text-gray-500 hover:text-[#f45b69]'
                                    }}
                                ">

                                Monthly

                            </a>

                        </div>

                        {{-- NOTE --}}
                        <span class="
                            text-[11px]
                            text-gray-400
                            font-medium
                        ">

                            {{
                                $viewType == 'monthly'
                                    ? 'Monthly trend'
                                    : 'Daily activity'
                            }}

                        </span>

                    </div>

                </div>

                <div class="
                    flex flex-wrap
                    gap-4
                    text-xs
                    font-bold
                ">

                    @foreach($customerGrowth['datasets'] as $dataset)

                    <span class="
                        flex items-center gap-2
                    ">

                        <div class="
                                w-4 h-4
                                rounded-full
                            " style="
                                background:
                                {{ $dataset['backgroundColor'] }}
                            "></div>

                        {{ $dataset['label'] }}

                    </span>

                    @endforeach

                </div>

            </div>

            @php

            $totalGrowth =
            collect($customerGrowth['datasets'])
            ->sum(fn ($dataset) =>
            collect($dataset['data'])->sum()
            );

            @endphp

            @if($totalGrowth == 0)

            <div class="
                flex flex-col
                items-center justify-center
                h-[300px]
            ">

                <div class="text-5xl mb-4">
                    📊
                </div>

                <h3 class="
                    text-xl
                    font-semibold
                    text-[#2d2a26]
                    mb-2
                ">

                    No customer growth data

                </h3>

                <p class="
                    text-sm
                    text-gray-500
                ">

                    There is no customer activity for this period.

                </p>

            </div>

            @else

            <div style="
                    height: 260px;
                    position: relative;
                ">

                <canvas id="revenueChart"></canvas>

            </div>

            @endif

        </div>

        {{-- INSIGHT --}}
        <div class="
            bg-white
            p-6
            rounded-3xl
            shadow-sm
            flex flex-col
            justify-center
        ">

            <h4 class="
                text-2xl
                font-bold
                text-[#484746]
                mb-6
            ">

                Insight Utama

            </h4>

            <p class="
                text-[#484648]
                text-lg
                leading-relaxed
                font-medium
            ">

                @if(
                $reservationHabits['morning'] >
                $reservationHabits['evening']
                )

                Customers are more active during
                morning hours, indicating stronger
                daytime booking behavior.

                @elseif(
                $reservationHabits['evening'] >
                $reservationHabits['morning']
                )

                Evening bookings dominate this period,
                showing higher customer activity after
                work hours.

                @else

                Customer activity is balanced between
                morning and evening sessions.

                @endif

            </p>

        </div>

    </div>

    {{-- RESERVATION HABITS --}}
    <div class="
        bg-[#eadede]
        p-6
        rounded-3xl
    ">

        <h4 class="text-xl font-bold">
            Reservation Habits
        </h4>

        <p class="
            text-gray-500
            text-sm
            mb-8
        ">

            When are customers most active?

        </p>

        <div class="
            grid grid-cols-1
            md:grid-cols-3
            gap-4
        ">

            @php

            $habits = [

            [
            'label' => 'Morning',
            'time' => 'Before 12 PM',
            'val' => $reservationHabits['morning']
            ],

            [
            'label' => 'Afternoon',
            'time' => '12 PM - 6 PM',
            'val' => $reservationHabits['afternoon']
            ],

            [
            'label' => 'Evening',
            'time' => 'After 6 PM',
            'val' => $reservationHabits['evening']
            ],

            ];

            @endphp

            @foreach($habits as $habit)

            <div class="
                bg-white
                p-5
                rounded-3xl
                shadow-sm
            ">

                <p class="
                    text-[11px]
                    font-bold
                    text-gray-800
                    uppercase
                    tracking-wider
                ">

                    {{ $habit['label'] }}

                </p>

                <p class="
                    text-[10px]
                    text-gray-500
                    mb-3
                ">

                    {{ $habit['time'] }}

                </p>

                <p class="
                    text-xl
                    font-bold
                    text-pink-500
                ">

                    {{ $habit['val'] }}%

                </p>

                <div class="
                    w-full
                    bg-pink-200
                    h-1.5
                    rounded-full
                    mt-3
                    overflow-hidden
                ">

                    <div class="
                            bg-pink-500
                            h-full
                        " style="
                            width:
                            {{ $habit['val'] }}%
                        "></div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>

<script>
document.addEventListener(
    "DOMContentLoaded",
    function() {

        const ctx =
            document.getElementById(
                'revenueChart'
            );

        if (!ctx) return;

        new Chart(ctx, {

            type: 'bar',

            data: {

                labels: @json(
                    $customerGrowth['labels']
                ),

                datasets: @json(
                    $customerGrowth['datasets']
                )

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
);
</script>

@endsection