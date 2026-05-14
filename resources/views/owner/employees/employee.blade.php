@extends('owner.app')

@section('content')

<div class="pt-24 px-8 pb-8 bg-[#f6eaea] min-h-screen relative">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-5xl font-bold text-[#2d2a26]">
                Team Performance
            </h1>

            <p class="text-gray-500 mt-2">
                Overview of employee efficiency across your luxury network.
            </p>
        </div>

        <div class="flex gap-3">

            {{-- CABANG --}}
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="bg-[#f45b69] text-white px-5 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-sm hover:opacity-90 transition"
                >
                    @if($selectedCabang == 'all')
                        Seluruh Cabang
                    @else
                        {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-pink-100 overflow-hidden z-50">
                    <a href="{{ route('owner.employee', ['cabang' => 'all', 'bulan' => $selectedMonth]) }}"
                        class="flex items-center justify-between px-5 py-3 text-sm hover:bg-pink-50 transition {{ $selectedCabang == 'all' ? 'bg-pink-50 font-semibold text-[#f45b69]' : 'text-gray-700' }}">
                        <span>Seluruh Cabang</span>
                        @if($selectedCabang == 'all') <span>✓</span> @endif
                    </a>
                    @foreach($cabangs as $cabang)
                    <a href="{{ route('owner.employee', ['cabang' => $cabang->cabang_id, 'bulan' => $selectedMonth]) }}"
                        class="flex items-center justify-between px-5 py-3 text-sm hover:bg-pink-50 transition {{ $selectedCabang == $cabang->cabang_id ? 'bg-pink-50 font-semibold text-[#f45b69]' : 'text-gray-700' }}">
                        <span>{{ $cabang->nama_cabang }}</span>
                        @if($selectedCabang == $cabang->cabang_id) <span>✓</span> @endif
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- BULAN --}}
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="bg-white border border-[#f3dede] text-[#2d2a26] px-5 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-sm hover:bg-[#fff7f7] transition"
                >
                    {{ collect($months)->firstWhere('value', $selectedMonth)['label'] }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute top-full right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-pink-100 overflow-hidden z-50">
                    @foreach($months as $month)
                    <a href="{{ route('owner.employee', ['cabang' => $selectedCabang, 'bulan' => $month['value']]) }}"
                        class="flex items-center justify-between px-5 py-3 text-sm hover:bg-pink-50 transition {{ $selectedMonth == $month['value'] ? 'bg-pink-50 font-semibold text-[#f45b69]' : 'text-gray-700' }}">
                        <span>{{ $month['label'] }}</span>
                        @if($selectedMonth == $month['value']) <span>✓</span> @endif
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- TOP PERFORMERS --}}
    <h2 class="text-2xl font-semibold text-[#2d2a26] mb-4">Top Performers</h2>

    @if($topPerformers->count())
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        @foreach($topPerformers as $employee)
        <div class="bg-white rounded-3xl p-5 shadow-md flex items-center gap-4 min-w-0">

            {{-- PROFILE --}}
            @if($employee['foto_profile'])
            <img src="{{ asset('storage/' . $employee['foto_profile']) }}" alt="{{ $employee['nama'] }}"
                class="w-20 h-20 rounded-2xl object-cover flex-shrink-0">
            @else
            <div class="w-20 h-20 rounded-2xl bg-[#f45b69] text-white flex items-center justify-center text-2xl font-bold flex-shrink-0">
                {{ $employee['initial'] }}
            </div>
            @endif

            {{-- CONTENT --}}
            <div class="min-w-0 flex-1">
                <span class="inline-block bg-[#f8cdd0] text-[#7a3037] text-xs px-3 py-1 rounded-full max-w-full break-words">
                    {{ $employee['nama_cabang'] }}
                </span>
                <h3 class="text-lg font-semibold mt-2 text-[#2d2a26] truncate">{{ $employee['nama'] }}</h3>
                <p class="mt-1 text-lg font-bold text-[#f45b69]">
                    {{ $employee['total_clients'] }}
                    <span class="text-gray-500 text-sm font-normal">klien bln ini</span>
                </p>
            </div>

        </div>
        @endforeach
    </div>

    @else
    <div class="bg-white rounded-3xl py-14 px-6 text-center shadow-md mb-10">
        <div class="text-5xl mb-4">📊</div>
        <h3 class="text-lg font-semibold text-[#2d2a26] mb-2">No employee data yet</h3>
        <p class="text-gray-500 text-sm">There are no employee performances for this period.</p>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-[#eadede] p-6 rounded-3xl">

        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-semibold text-[#2d2a26]">Employee Efficiency</h2>
            <a href="{{ route('owner.employee.edit') }}" class="text-sm text-[#b04a4a]">See all →</a>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="text-[#b04a4a] border-b border-[#d8c6c6]">
                    <tr>
                        <th class="py-4 px-4 text-center">No</th>
                        <th class="px-4 text-left">Employee</th>
                        <th class="px-4 text-left">Cabang</th>
                        <th class="px-4 text-center">Today</th>
                        <th class="px-4 text-center">Clients</th>
                        <th class="px-4 text-center">Services</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">

                    @forelse($employees as $i => $employee)
                    <tr class="border-b border-[#e5d6d6] hover:bg-[#fdf4f4] transition">

                        {{-- NO --}}
                        <td class="py-5 px-4 text-center">
                            {{ ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator
                                ? $employees->firstItem()
                                : 1) + $i }}
                        </td>

                        {{-- EMPLOYEE --}}
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-3">
                                @if($employee['foto_profile'])
                                <img src="{{ asset('storage/' . $employee['foto_profile']) }}" alt="{{ $employee['nama'] }}"
                                    class="w-11 h-11 rounded-full object-cover flex-shrink-0">
                                @else
                                <div class="w-11 h-11 rounded-full bg-[#f45b69] text-white flex items-center justify-center font-semibold text-sm flex-shrink-0">
                                    {{ $employee['initial'] }}
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold truncate">{{ $employee['nama'] }}</p>
                                    <p class="text-xs text-gray-500">Since {{ $employee['since_joined'] }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- CABANG --}}
                        <td class="px-4">{{ $employee['nama_cabang'] }}</td>

                        {{-- TODAY --}}
                        <td class="px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $employee['today_status'] == 'tersedia' ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-600' }}">
                                {{ $employee['today_status'] == 'tersedia' ? 'Available' : 'Off Today' }}
                            </span>
                        </td>

                        {{-- CLIENTS --}}
                        <td class="px-4 text-center font-medium">{{ number_format($employee['total_clients']) }}</td>

                        {{-- SERVICES --}}
                        <td class="px-4 text-center">{{ number_format($employee['total_services']) }}</td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="text-5xl mb-4">📊</div>
                                <h3 class="text-xl font-semibold text-[#2d2a26] mb-2">No employee data</h3>
                                <p class="text-sm text-gray-500">No employee performance activity for this period.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

            {{-- FOOTER TABLE --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-8 pt-5 border-t border-[#d8c6c6]">

                {{-- INFO --}}
                <div class="text-sm text-gray-500">
                    @if($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        Showing
                        <span class="font-semibold text-[#2d2a26]">{{ $employees->firstItem() }}</span>
                        -
                        <span class="font-semibold text-[#2d2a26]">{{ $employees->lastItem() }}</span>
                        of
                        <span class="font-semibold text-[#2d2a26]">{{ $employees->total() }}</span>
                        employees
                    @else
                        Showing all
                        <span class="font-semibold text-[#2d2a26]">{{ $employees->count() }}</span>
                        employees
                    @endif
                </div>

                {{-- RIGHT: PER PAGE + PAGINATION --}}
                <div class="flex items-center gap-4">

                    {{-- PER PAGE --}}
                    <form method="GET">
                        <input type="hidden" name="cabang" value="{{ $selectedCabang }}">
                        <input type="hidden" name="bulan" value="{{ $selectedMonth }}">
                        <div class="relative">
                            <select
                                name="show"
                                onchange="this.form.submit()"
                                class="bg-white border border-[#ecd9d9] rounded-xl pl-4 pr-10 py-2 text-sm outline-none shadow-sm appearance-none cursor-pointer hover:border-[#f4b6bc] transition"
                            >
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 rows</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 rows</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 rows</option>
                                <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>All</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </form>

                    {{-- PAGINATION LINKS --}}
                    @if($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div>
                        {{ $employees->links() }}
                    </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection