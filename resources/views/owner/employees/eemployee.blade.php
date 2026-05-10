@extends('owner.app')

@section('content')

<div x-data="{ openModal:false }"
     class="pt-24 px-8 pb-8 bg-[#f6eaea] min-h-screen">

    {{-- BACK --}}
    <a
        href="{{ route('owner.employee') }}"
        class="
            inline-flex items-center justify-center gap-2
            bg-white
            border border-[#f1dede]
            px-5 py-2.5
            rounded-full
            text-sm font-medium
            text-[#b04a4a]
            shadow-sm
            hover:bg-pink-50
            transition
            mb-8
        "
    >
        ← Back to Team Performance
    </a>

    {{-- SUMMARY --}}
    <div class=" grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- TOTAL EMPLOYEE --}}
        <div class="bg-white rounded-3xl p-5 shadow-sm">

            <p class="text-sm text-gray-500 mb-2">
                Total Employees
            </p>

            <h2 class="text-3xl font-bold text-[#f45b69]">
                {{ $totalEmployees }}
            </h2>

        </div>

        {{-- ACTIVE --}}
        <div class="bg-white rounded-3xl p-5 shadow-sm">

            <p class="text-sm text-gray-500 mb-2">
                Active This Month
            </p>

            <h2 class="text-3xl font-bold text-[#f45b69]">
                {{ $activeEmployees }}
            </h2>

        </div>

        {{-- CABANG --}}
        @foreach($cabangs as $cabang)

            @if(
                $selectedCabang == 'all'
                || $selectedCabang == $cabang->cabang_id
            )

            <div class="bg-white rounded-3xl p-5 shadow-sm">

                <p class="
                    text-sm
                    text-gray-500
                    mb-2
                    truncate
                ">
                    {{ $cabang->nama_cabang }}
                </p>

                <h2 class="text-3xl font-bold text-[#f45b69]">

                    {{
                        $branchTotals[$cabang->nama_cabang]
                        ?? 0
                    }}

                </h2>

            </div>

            @endif

        @endforeach

    </div>

    {{-- MAIN CARD --}}
    <div class="bg-[#efe3e3] rounded-[35px] px-10 py-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <div>
                <h1 class="text-3xl font-bold text-[#2d2a26]">
                    Staff Directory
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    {{
                        Carbon\Carbon::parse($selectedMonth)
                            ->translatedFormat('F Y')
                    }}
                    • Manage salon specialists and staff
                </p>
            </div>

            <div class="flex gap-3">

                {{-- FILTER CABANG --}}
                <div class="relative" x-data="{ open: false }">

                    <button
                        @click="open = !open"
                        class="
                            bg-[#f45b69]
                            text-white
                            px-5 py-2.5
                            rounded-full
                            text-sm
                            font-medium
                            flex items-center gap-2
                            shadow-sm
                            hover:opacity-90
                            transition
                        "
                    >

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

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />

                        </svg>

                    </button>

                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="
                            absolute
                            top-full
                            left-0
                            mt-2
                            w-64
                            bg-white
                            rounded-2xl
                            shadow-xl
                            border border-pink-100
                            overflow-hidden
                            z-50
                        "
                    >

                        {{-- ALL --}}
                        <a
                            href="
                                {{
                                    route('owner.employee.edit', [
                                        'cabang' => 'all',
                                        'bulan' => $selectedMonth
                                    ])
                                }}
                            "
                            class="
                                flex items-center justify-between
                                px-5 py-3
                                text-sm
                                hover:bg-pink-50
                                transition

                                {{
                                    $selectedCabang == 'all'
                                        ? 'bg-pink-50 font-semibold text-[#f45b69]'
                                        : 'text-gray-700'
                                }}
                            "
                        >

                            <span>Seluruh Cabang</span>

                            @if($selectedCabang == 'all')
                                <span>✓</span>
                            @endif

                        </a>

                        @foreach($cabangs as $cabang)

                        <a
                            href="
                                {{
                                    route('owner.employee.edit', [
                                        'cabang' => $cabang->cabang_id,
                                        'bulan' => $selectedMonth
                                    ])
                                }}
                            "
                            class="
                                flex items-center justify-between
                                px-5 py-3
                                text-sm
                                hover:bg-pink-50
                                transition

                                {{
                                    $selectedCabang == $cabang->cabang_id
                                        ? 'bg-pink-50 font-semibold text-[#f45b69]'
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

                {{-- FILTER BULAN --}}
                <div class="relative" x-data="{ open: false }">

                    <button
                        @click="open = !open"
                        class="
                            bg-white
                            border border-[#f3dede]
                            text-[#2d2a26]
                            px-5 py-2.5
                            rounded-full
                            text-sm
                            font-medium
                            flex items-center gap-2
                            shadow-sm
                            hover:bg-[#fff7f7]
                            transition
                        "
                    >

                        {{
                            collect($months)
                                ->firstWhere(
                                    'value',
                                    $selectedMonth
                                )['label']
                        }}

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />

                        </svg>

                    </button>

                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="
                            absolute
                            top-full
                            right-0
                            mt-2
                            w-52
                            bg-white
                            rounded-2xl
                            shadow-xl
                            border border-pink-100
                            overflow-hidden
                            z-50
                        "
                    >

                        @foreach($months as $month)

                        <a
                            href="
                                {{
                                    route('owner.employee.edit', [
                                        'cabang' => $selectedCabang,
                                        'bulan' => $month['value']
                                    ])
                                }}
                            "
                            class="
                                flex items-center justify-between
                                px-5 py-3
                                text-sm
                                hover:bg-pink-50
                                transition

                                {{
                                    $selectedMonth == $month['value']
                                        ? 'bg-pink-50 font-semibold text-[#f45b69]'
                                        : 'text-gray-700'
                                }}
                            "
                        >

                            <span>
                                {{ $month['label'] }}
                            </span>

                            @if($selectedMonth == $month['value'])
                                <span>✓</span>
                            @endif

                        </a>

                        @endforeach

                    </div>

                </div>

                {{-- ADD --}}
                <button
                    @click="openModal = true"
                    class="
                        bg-[#f8cdd0]
                        text-[#b04a4a]
                        px-5 py-2.5
                        rounded-full
                        text-sm font-medium
                        hover:opacity-90
                        transition
                    "
                >
                    + Add Employee
                </button>

            </div>
        </div>

        {{-- SEARCH --}}
        <div class="mb-6">

            <div class="
                flex items-center
                bg-white
                px-5 py-3
                rounded-2xl
                border border-[#ecd9d9]
                max-w-md
            ">

                <span class="mr-3 text-gray-400">
                    🔍
                </span>

                <input
                    type="text"
                    placeholder="Search employee..."
                    class="
                        bg-transparent
                        outline-none
                        w-full
                        text-sm
                    "
                    id="searchEmployee"
                >

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1100px] text-sm">

                {{-- HEADER --}}
                <thead
                    class="
                        text-left
                        text-[#b04a4a]
                        border-b border-[#d8c6c6]
                    "
                >

                    <tr>

                        <th class="py-4 px-4 text-center w-[70px]">
                            No
                        </th>

                        <th class="px-4 text-left w-[280px]">
                            Specialist
                        </th>

                        <th class="px-4 text-center w-[120px]">
                            Role
                        </th>

                        <th class="px-4 text-left w-[260px]">
                            Cabang
                        </th>

                        <th class="px-4 text-center w-[120px]">
                            Status
                        </th>

                        <th class="px-4 text-center w-[90px]">
                            Clients
                        </th>

                        <th class="px-4 text-center w-[90px]">
                            Services
                        </th>

                        <th class="px-4 text-center w-[120px]">
                            Since
                        </th>

                        <th class="px-4 text-center w-[100px]">
                            Rating
                        </th>

                        <th class="px-4 text-center w-[110px]">
                            Action
                        </th>

                    </tr>

                </thead>

                {{-- BODY --}}
                <tbody class="text-[#3e382d]">

                    @forelse($employees as $i => $employee)

                    <tr class="
                        employee-row
                        border-b border-[#ead7d7]
                        hover:bg-[#fff7f7]
                        transition duration-200
                    ">

                        {{-- NO --}}
                        <td class="py-5 px-4 text-center font-medium">
                            {{ $i + 1 }}
                        </td>

                        {{-- SPECIALIST --}}
                        <td class="px-4 py-5">

                            <div class="flex items-center gap-3">

                                {{-- PHOTO --}}
                                @if($employee['foto_profile'])

                                <img
                                    src="{{ asset('storage/' . $employee['foto_profile']) }}"
                                    alt="{{ $employee['nama'] }}"
                                    class="
                                        w-11 h-11
                                        rounded-full
                                        object-cover
                                        border border-white
                                        shadow-sm
                                        flex-shrink-0
                                    "
                                >

                                @else

                                <div class="
                                    w-11 h-11
                                    rounded-full
                                    bg-gradient-to-br
                                    from-[#f45b69]
                                    to-[#ff8fa3]
                                    text-white
                                    flex items-center justify-center
                                    text-sm font-bold
                                    shadow-sm
                                    flex-shrink-0
                                ">

                                    {{ $employee['initial'] }}

                                </div>

                                @endif

                                {{-- NAME --}}
                                <div class="min-w-0">

                                    <p class="
                                        employee-name
                                        font-semibold
                                        text-[#2d2a26]
                                        truncate
                                    ">
                                        {{ $employee['nama'] }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-0.5">
                                        ID #{{ $employee['pegawai_id'] }}
                                    </p>

                                </div>

                            </div>

                        </td>

                        {{-- ROLE --}}
                        <td class="px-4 text-center">

                            <span class="
                                inline-flex items-center justify-center
                                px-3 py-1
                                rounded-full
                                text-xs font-semibold
                                whitespace-nowrap

                                {{
                                    $employee['role'] == 'admin'
                                    ? 'bg-[#dbeafe] text-[#2563eb]'
                                    : 'bg-[#ffe4e6] text-[#e11d48]'
                                }}
                            ">

                                {{ ucfirst($employee['role']) }}

                            </span>

                        </td>

                        {{-- CABANG --}}
                        <td class="
                            px-4
                            employee-branch
                            text-[#4b4035]
                        ">

                            <div class="truncate max-w-[240px]">
                                {{ $employee['nama_cabang'] }}
                            </div>

                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 text-center">

                            <span class="
                                inline-flex items-center justify-center
                                px-3 py-1
                                rounded-full
                                text-xs font-semibold
                                whitespace-nowrap

                                {{
                                    $employee['status_kerja'] == 'aktif'
                                    ? 'bg-green-100 text-green-600'
                                    : 'bg-red-100 text-red-500'
                                }}
                            ">

                                {{ ucfirst($employee['status_kerja']) }}

                            </span>

                        </td>

                        {{-- CLIENTS --}}
                        <td class="
                            px-4
                            text-center
                            font-medium
                        ">

                            {{ number_format($employee['total_clients']) }}

                        </td>

                        {{-- SERVICES --}}
                        <td class="
                            px-4
                            text-center
                            font-medium
                        ">

                            {{ number_format($employee['total_services']) }}

                        </td>

                        {{-- SINCE --}}
                        <td class="
                            px-4
                            text-center
                            whitespace-nowrap
                            text-[#5f5347]
                        ">

                            {{ $employee['since_joined'] }}

                        </td>

                        {{-- RATING --}}
                        <td class="px-4 text-center">

                            <span class="
                                inline-flex items-center gap-1
                                font-semibold
                                text-[#2d2a26]
                            ">

                                <span>⭐</span>

                                {{ $employee['avg_rating'] }}

                            </span>

                        </td>

                        {{-- ACTION --}}
                        <td class="px-4">

                            <div class="flex justify-center gap-2">

                                {{-- EDIT --}}
                                <button class="
                                    w-9 h-9
                                    rounded-xl
                                    bg-white
                                    border border-[#f3dede]
                                    shadow-sm
                                    hover:bg-pink-100
                                    hover:scale-105
                                    transition
                                ">
                                    ✏️
                                </button>

                                {{-- DELETE --}}
                                <button class="
                                    w-9 h-9
                                    rounded-xl
                                    bg-white
                                    border border-[#f3dede]
                                    shadow-sm
                                    hover:bg-red-100
                                    hover:scale-105
                                    transition
                                ">
                                    🗑️
                                </button>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="py-16 text-center">

                            <div class="flex flex-col items-center">

                                <div class="text-5xl mb-4">
                                    📊
                                </div>

                                <h3 class="
                                    text-xl font-semibold
                                    text-[#2d2a26]
                                    mb-2
                                ">
                                    No employee data
                                </h3>

                                <p class="text-sm text-gray-500">
                                    No employee activity found for this period.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- ================= MODAL ================= --}}
    @include('owner.employees.addemployee')

</div>

<script>
const searchInput =
    document.getElementById('searchEmployee');

const rows =
    document.querySelectorAll('.employee-row');

searchInput.addEventListener('keyup', function () {

    const keyword =
        this.value.toLowerCase();

    rows.forEach(row => {

        const name =
            row.querySelector('.employee-name')
                ?.innerText
                .toLowerCase() || '';

        const branch =
            row.querySelector('.employee-branch')
                ?.innerText
                .toLowerCase() || '';

        row.style.display =
            name.includes(keyword)
            || branch.includes(keyword)
            ? ''
            : 'none';

    });

});
</script>
@endsection