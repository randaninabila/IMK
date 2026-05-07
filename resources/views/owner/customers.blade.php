@extends('owner.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<section class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white px-20 py-20 pt-25">
    <!-- Header Section -->
    <div class="flex justify-between items-start mb-10 text-left"> 
        <div>
            <h2 class="text-6xl font-bold mb-3 tracking-tight">Performance Pulse</h2>
            <p class="text-gray-500 text-lg italic">Strategic Overview</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-[#FF8FA3] text-white px-6 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-sm">
                Seluruh Cabang 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <button class="bg-[#FF5C77] text-white px-6 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-md">
                <span>📥</span> Download PDF Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 text-left">
        @php
            $cards = [
                ['label' => 'Active Customers', 'val' => '87', 'sub' => 'Laudendang : 11 | Tuasan : 76', 'icon' => '👥', 'badge' => '+5%'],
                ['label' => 'Retention Rate', 'val' => '78.9%', 'sub' => 'Cabang Tuasan leading by 4.2%', 'icon' => '🔄', 'badge' => '-1%'],
                ['label' => 'Avg Lifetime Value', 'val' => '2.300k', 'sub' => 'Peningkatan nilai transaksi', 'icon' => '💵', 'badge' => '+8.2%'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-pink-50 relative overflow-hidden">
            <div class="flex justify-between mb-6 text-2xl">
                <span class="p-3 bg-pink-100 rounded-2xl text-pink-500">{{ $card['icon'] }}</span>
                @if($card['badge'])
                <span class="text-xs font-bold {{ str_contains($card['badge'], '+') ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} px-3 py-1.5 rounded-full h-fit">{{ $card['badge'] }}</span>
                @endif
            </div>
            <p class="text-gray-500 font-semibold mb-1">{{ $card['label'] }}</p>
            <h3 class="text-4xl font-bold text-pink-500 mb-1">{{ $card['val'] }}</h3>
            <p class="text-xs text-gray-400 border-t pt-3 mt-2">{{ $card['sub'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Chart & Insight Utama Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-left mb-10">
        <!-- Revenue Trends Chart -->
        <div class="md:col-span-2 bg-white p-10 rounded-[3rem] shadow-sm border border-pink-50">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h4 class="text-2xl font-bold">Customer Growth</h4>
                    <p class="text-gray-400 text-sm">6 bulan terakhir</p>
                </div>
                <div class="flex gap-6 text-xs font-bold">
                    <span class="flex items-center gap-2"><div class="w-4 h-4 bg-[#A00020] rounded-full"></div> Cabang Laudendang</span>
                    <span class="flex items-center gap-2"><div class="w-4 h-4 bg-[#FF7096] rounded-full"></div> Cabang Tuasan</span>
                </div>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Insight Utama Card (Ganti Popular Services) -->
        <div class="bg-[#FFE4E6] p-10 rounded-[3rem] shadow-sm flex flex-col justify-center">
            <h4 class="text-3xl font-bold text-[#A00020] mb-6">Insight Utama</h4>
            <p class="text-[#A00020] text-lg leading-relaxed font-medium">
                Cabang Tuasan mencatat lonjakan pelanggan baru sebesar 18% setelah peluncuran treatment "Ethereal Glow" di bulan April.
            </p>
        </div>
    </div>

    <!-- New Bottom Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
        <!-- Tipe Pelanggan Per Cabang -->
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-pink-50">
            <h4 class="text-2xl font-bold mb-8">Tipe Pelanggan Per Cabang</h4>
            <div class="space-y-10">
                <div>
                    <div class="flex justify-between text-sm font-bold mb-3">
                        <span>Cabang Laudendang</span>
                        <span class="text-gray-400 font-normal">40 Baru | 10 Kembali</span>
                    </div>
                    <div class="w-full bg-pink-50 h-5 rounded-full overflow-hidden flex">
                        <div class="bg-[#FF7096] h-full" style="width: 80%"></div>
                        <div class="bg-[#A00020] h-full" style="width: 20%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm font-bold mb-3">
                        <span>Cabang Tuasan</span>
                        <span class="text-gray-400 font-normal">20 Baru | 20 Kembali</span>
                    </div>
                    <div class="w-full bg-pink-50 h-5 rounded-full overflow-hidden flex">
                        <div class="bg-[#FF7096] h-full" style="width: 50%"></div>
                        <div class="bg-[#A00020] h-full" style="width: 50%"></div>
                    </div>
                </div>
            </div>
            <div class="flex gap-6 mt-10 text-xs font-bold">
                <span class="flex items-center gap-2"><div class="w-3 h-3 bg-[#FF7096] rounded-full"></div> Baru</span>
                <span class="flex items-center gap-2"><div class="w-3 h-3 bg-[#A00020] rounded-full"></div> Lama</span>
            </div>
        </div>

        <!-- Kebiasaan Reservasi -->
        <div class="bg-white p-10 rounded-[3rem] shadow-lg">
    <h4 class="text-2xl font-bold">Kebiasaan Reservasi</h4>
    <p class="text-gray-500 text-sm mb-8">Kapan pelanggan Anda paling aktif?</p>
    
    <div class="grid grid-cols-2 gap-4">
        @php
            $habits = [
                ['label' => 'Morning', 'time' => '09:00 - 11:30', 'val' => '32%'],
                ['label' => 'Afternoon', 'time' => '13:00 - 18:00', 'val' => '32%'],
                ['label' => 'Evening', 'time' => '19:00 - 19:30', 'val' => '32%'],
                ['label' => 'Weekend vs Weekday', 'time' => 'Weekend', 'val' => '32%'],
            ];
        @endphp

        @foreach($habits as $habit)
        <div class="bg-[#FFE4E6] p-5 rounded-3xl shadow-sm border border-pink-100">
            <p class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">{{ $habit['label'] }}</p>
            <p class="text-[10px] text-gray-500 mb-3">{{ $habit['time'] }}</p>
            <p class="text-2xl font-bold text-pink-500">{{ $habit['val'] }}</p>

            <div class="w-full bg-pink-200 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-pink-500 h-full" style="width: 32%"></div>
            </div>
        </div>
        @endforeach
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
                        { label: 'Laudendang', data: [40, 75, 60, 48, 40, 45], backgroundColor: '#A00020', borderRadius: 5, barThickness: 15 },
                        { label: 'Tuasan', data: [70, 55, 75, 25, 75, 55], backgroundColor: '#FF7096', borderRadius: 5, barThickness: 15 }
                    ]
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
</script>
@endsection