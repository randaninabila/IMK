@extends('owner.app')

@section('content')

@php
    $sortUrl = function (string $key, $cabangAcuan = null) use (
        $selectedCabang, $selectedMonth, $selectedSort, $selectedDir,
        $selectedSortCabang, $perPage
    ): string {
        $isActive = $selectedSort === $key
            && ($cabangAcuan === null || (string)$cabangAcuan === (string)$selectedSortCabang);

        $newDir = ($isActive && $selectedDir === 'desc') ? 'asc' : 'desc';

        $params = [
            'cabang' => $selectedCabang,
            'bulan'  => $selectedMonth,
            'sort'   => $key,
            'dir'    => $newDir,
        ];

        if ($cabangAcuan !== null) {
            $params['sort_cabang'] = $cabangAcuan;
        }

        return route('owner.employee.edit', $params);
    };

    $sortIcon = function (string $key, $cabangAcuan = null) use ($selectedSort, $selectedDir, $selectedSortCabang): string {
        $isActive = $selectedSort === $key
            && ($cabangAcuan === null || (string)$cabangAcuan === (string)$selectedSortCabang);

        if (!$isActive) return '<span class="sort-icon inactive">↕</span>';
        $arrow = $selectedDir === 'asc' ? '↑' : '↓';
        return '<span class="sort-icon active">' . $arrow . '</span>';
    };

    $isActive = function (string $key, $cabangAcuan = null) use ($selectedSort, $selectedSortCabang): bool {
        return $selectedSort === $key
            && ($cabangAcuan === null || (string)$cabangAcuan === (string)$selectedSortCabang);
    };
@endphp

<style>
/* ── Sort header ── */
.sort-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 0;
    font-size: 12.5px;
    font-weight: 600;
    white-space: nowrap;
    text-decoration: none;
    color: #b04a4a;
    cursor: pointer;
    background: none;
    border: 1px solid transparent;
    border-radius: 9999px;
    transition: color .15s;
    line-height: 1;
}
.sort-link:hover { color: #f45b69; }
.sort-link.active {
    background: #fff;
    border-color: #f1dede;
    box-shadow: 0 1px 3px rgba(244,91,105,.15);
    color: #f45b69;
    padding: 4px 12px;
}
.sort-icon {
    font-size: 11px;
    flex-shrink: 0;
    color: #d4a8a8;
    transition: color .15s;
}
.sort-link:hover .sort-icon,
.sort-link.active .sort-icon { color: #f45b69; }
</style>

<div class="relative">

    <div x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }} }">

    {{-- BACK --}}
    <a
        href="{{ route('owner.employee') }}"
        class="
            inline-flex items-center justify-center gap-2
            bg-white border border-[#f1dede]
            px-5 py-2.5 rounded-full
            text-sm font-medium text-[#b04a4a]
            shadow-sm hover:bg-pink-50 transition mb-8
        "
    >
        ← Kembali ke Performa Tim
    </a>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- 1. TOTAL EMPLOYEES --}}
        <div class="bg-white rounded-3xl px-5 p-3 shadow-sm border border-pink-50">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-gray-500">Total Karyawan</p>
                <span class="p-2 bg-pink-100 rounded-xl text-base leading-none">🆔</span>
            </div>
            <h2 class="text-3xl font-bold text-[#f45b69] mb-1">{{ $totalEmployees }}</h2>
            <p class="text-xs text-gray-400 mb-4">
                @if($selectedCabang == 'all') Seluruh Cabang
                @else {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                @endif
            </p>

            @if($selectedCabang == 'all')
            <div class="border-t border-pink-50 pt-3 space-y-2.5">
                @foreach($cabangStats as $cs)
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-400 truncate">{{ Str::limit($cs['nama_cabang'], 20) }}</span>
                    <span class="text-xs font-semibold text-[#f45b69] bg-pink-50 px-2.5 py-0.5 rounded-full flex-shrink-0">
                        {{ $cs['total'] }} staf
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- 2. AVAILABLE TODAY --}}
        <div class="bg-white rounded-3xl px-5 p-3 shadow-sm border border-pink-50">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-gray-500">Tersedia Hari Ini</p>
                <span class="p-2 bg-blue-100 rounded-xl text-base leading-none">✅</span>
            </div>
            <h2 class="text-3xl font-bold text-[#f45b69] mb-1">{{ $activeEmployees }}</h2>
            <p class="text-xs text-gray-400 mb-4">
                @if($selectedCabang == 'all') Seluruh Cabang
                @else {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                @endif
            </p>

            @if($selectedCabang == 'all')
            <div class="border-t border-pink-50 pt-3 space-y-2.5">
                @foreach($cabangStats as $cs)
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-400 truncate">{{ Str::limit($cs['nama_cabang'], 18) }}</span>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="w-14 h-1.5 bg-blue-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-400 rounded-full transition-all"
                                style="width: {{ $cs['total'] > 0 ? round(($cs['active'] / $cs['total']) * 100) : 0 }}%">
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full w-6 text-center">
                            {{ $cs['active'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- 3. OFF TODAY --}}
        <div class="bg-white rounded-3xl px-5 p-3 shadow-sm border border-pink-50">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-gray-500">Libur Hari Ini</p>
                <span class="p-2 bg-amber-100 rounded-xl text-base leading-none">⏰</span>
            </div>
            <h2 class="text-3xl font-bold text-[#f45b69] mb-1">{{ $totalEmployees - $activeEmployees }}</h2>
            <p class="text-xs text-gray-400 mb-4">
                @if($selectedCabang == 'all') Seluruh Cabang
                @else {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                @endif
            </p>

            @if($selectedCabang == 'all')
            <div class="border-t border-pink-50 pt-3 space-y-2.5">
                @foreach($cabangStats as $cs)
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-400 truncate">{{ Str::limit($cs['nama_cabang'], 18) }}</span>
                    <span class="text-xs font-semibold flex-shrink-0 px-2.5 py-0.5 rounded-full
                        {{ $cs['off_today'] > 0
                            ? 'text-amber-700 bg-amber-50'
                            : 'text-gray-400 bg-gray-50' }}">
                        {{ $cs['off_today'] }} libur
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- MAIN CARD --}}
    <div>

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <div>
                <h1 class="text-3xl font-bold text-[#2d2a26]">Daftar Pegawai</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
                    • Kelola spesialis dan staf salon
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">

                {{-- FILTER CABANG --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-[#f45b69] text-white px-5 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-sm hover:opacity-90 transition">
                        @if($selectedCabang == 'all') Seluruh Cabang
                        @else {{ $cabangs->firstWhere('cabang_id', $selectedCabang)?->nama_cabang }}
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-pink-100 overflow-hidden z-50">
                        <a href="{{ route('owner.employee.edit', ['cabang' => 'all', 'bulan' => $selectedMonth, 'sort' => $selectedSort, 'dir' => $selectedDir, 'show' => $perPage]) }}"
                           class="flex items-center justify-between px-5 py-3 text-sm hover:bg-pink-50 transition {{ $selectedCabang == 'all' ? 'bg-pink-50 font-semibold text-[#f45b69]' : 'text-gray-700' }}">
                            <span>Seluruh Cabang</span>
                            @if($selectedCabang == 'all') <span>✓</span> @endif
                        </a>
                        @foreach($cabangs as $cabang)
                        <a href="{{ route('owner.employee.edit', ['cabang' => $cabang->cabang_id, 'bulan' => $selectedMonth, 'sort' => $selectedSort, 'dir' => $selectedDir, 'show' => $perPage]) }}"
                           class="flex items-center justify-between px-5 py-3 text-sm hover:bg-pink-50 transition {{ $selectedCabang == $cabang->cabang_id ? 'bg-pink-50 font-semibold text-[#f45b69]' : 'text-gray-700' }}">
                            <span>{{ $cabang->nama_cabang }}</span>
                            @if($selectedCabang == $cabang->cabang_id) <span>✓</span> @endif
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- FILTER BULAN --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-white border border-[#f3dede] text-[#2d2a26] px-5 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-sm hover:bg-[#fff7f7] transition">
                        {{ collect($months)->firstWhere('value', $selectedMonth)['label'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition class="absolute top-full right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-pink-100 overflow-hidden z-50">
                        @foreach($months as $month)
                        <a href="{{ route('owner.employee.edit', ['cabang' => $selectedCabang, 'bulan' => $month['value'], 'sort' => $selectedSort, 'dir' => $selectedDir, 'show' => $perPage]) }}"
                           class="flex items-center justify-between px-5 py-3 text-sm hover:bg-pink-50 transition {{ $selectedMonth == $month['value'] ? 'bg-pink-50 font-semibold text-[#f45b69]' : 'text-gray-700' }}">
                            <span>{{ $month['label'] }}</span>
                            @if($selectedMonth == $month['value']) <span>✓</span> @endif
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- ADD --}}
                <button @click="openModal = true" class="bg-[#f8cdd0] text-[#b04a4a] px-5 py-2.5 rounded-full text-sm font-medium hover:opacity-90 transition">
                    + Tambah Pegawai
                </button>

            </div>
        </div>

        {{-- SEARCH --}}
        <div class="mb-6">
            <div class="flex items-center bg-white px-5 py-3 rounded-2xl border border-[#ecd9d9] max-w-md">
                <span class="mr-3 text-gray-400">🔍</span>
                <input
                    type="text"
                    placeholder="Cari berdasarkan nama, posisi, status, atau tanggal bergabung..."
                    class="bg-transparent outline-none w-full text-sm"
                    id="searchEmployee"
                >
                <button id="clearSearch" class="ml-2 text-gray-300 hover:text-gray-500 transition hidden text-lg leading-none">×</button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-sm" id="employeeTable">

                <thead class="text-left text-[#b04a4a] border-b border-[#d8c6c6] [&_th]:py-3 [&_th]:px-4 [&_th]:align-middle">
                    <tr>
                        <th class="font-semibold text-center w-[60px]">No</th>

                        {{-- EMPLOYEE (sort by name) --}}
                        <th class="text-center px-4 w-[260px]">
                            <a href="{{ $sortUrl('employee') }}" class="sort-link {{ $isActive('employee') ? 'active' : '' }}">
                                Pegawai {!! $sortIcon('employee') !!}
                            </a>
                        </th>

                        <th class="px-4 font-semibold text-center w-[110px]">Role</th>
                        <th class="px-4 font-semibold text-center w-[200px]">Cabang</th>
                        <th class="px-4 font-semibold text-center w-[110px]">Hari Ini</th>

                        {{-- CLIENTS --}}
                        <th class="px-4 text-center w-[100px]">
                            <a href="{{ $sortUrl('clients') }}" class="sort-link {{ $isActive('clients') ? 'active' : '' }}">
                                Klien {!! $sortIcon('clients') !!}
                            </a>
                        </th>

                        {{-- SERVICES --}}
                        <th class="px-4 text-center w-[100px]">
                            <a href="{{ $sortUrl('services') }}" class="sort-link {{ $isActive('services') ? 'active' : '' }}">
                                Layanan {!! $sortIcon('services') !!}
                            </a>
                        </th>

                        {{-- SINCE --}}
                        <th class="px-4 text-center w-[120px]">
                            <a href="{{ $sortUrl('since') }}" class="sort-link {{ $isActive('since') ? 'active' : '' }}">
                                Bergabung {!! $sortIcon('since') !!}
                            </a>
                        </th>

                        <th class="px-4 text-center w-[110px] font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-[#3e382d]" id="employeeBody">

                    @forelse($employees as $i => $employee)
                    <tr class="
                        employee-row
                        border-b border-[#ead7d7]
                        hover:bg-[#fff7f7]
                        transition duration-200
                    "
                        data-name="{{ strtolower($employee['nama']) }}"
                        data-role="{{ strtolower($employee['role'] == 'admin' ? 'admin' : 'spesialis') }}"
                        data-today="{{ strtolower($employee['today_status'] == 'tersedia' ? 'tersedia' : 'libur') }}"
                        data-since="{{ strtolower($employee['since_joined']) }}"
                    >

                        {{-- NO --}}
                        <td class="py-5 px-4 text-center font-medium">
                            {{ method_exists($employees, 'firstItem') ? $employees->firstItem() + $i : $i + 1 }}
                        </td>

                        {{-- EMPLOYEE --}}
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-3">
                                @if($employee['foto_profile'])
                                <img src="{{ asset('storage/' . $employee['foto_profile']) }}" alt="{{ $employee['nama'] }}"
                                    class="w-11 h-11 rounded-full object-cover border border-white shadow-sm flex-shrink-0">
                                @else
                                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#f45b69] to-[#ff8fa3] text-white flex items-center justify-center text-sm font-bold shadow-sm flex-shrink-0">
                                    {{ $employee['initial'] }}
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="employee-name font-semibold text-[#2d2a26] truncate">{{ $employee['nama'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">ID #{{ $employee['pegawai_id'] }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- ROLE --}}
                        <td class="px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $employee['role'] == 'admin' ? 'bg-[#dbeafe] text-[#2563eb]' : 'bg-[#ffe4e6] text-[#e11d48]' }}">
                                {{ $employee['role'] == 'admin' ? 'Admin' : 'Spesialis' }}
                            </span>
                        </td>

                        {{-- CABANG --}}
                        <td class="px-4 employee-branch text-[#4b4035]">
                            <div class="truncate max-w-[180px]">{{ $employee['nama_cabang'] }}</div>
                        </td>

                        {{-- TODAY — derived from status_kerja: aktif=Available, cuti=Off Today --}}
                        <td class="px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $employee['today_status'] == 'tersedia' ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-600' }}">
                                {{ $employee['today_status'] == 'tersedia' ? 'Available' : 'Off Today' }}
                            </span>
                        </td>

                        {{-- CLIENTS --}}
                        <td class="px-4 text-center font-medium">{{ is_numeric($employee['total_clients']) ? number_format($employee['total_clients']) : '-' }}</td>

                        {{-- SERVICES --}}
                        <td class="px-4 text-center font-medium">{{ is_numeric($employee['total_services']) ? number_format($employee['total_services']) : '-' }}</td>

                        {{-- SINCE --}}
                        <td class="px-4 text-center whitespace-nowrap text-[#5f5347]">{{ $employee['since_joined'] }}</td>

                        {{-- ACTION --}}
                        <td class="px-4">
                            <div class="flex justify-center gap-2">

                                {{-- TOGGLE TODAY (aktif ↔ cuti) --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="w-9 h-9 rounded-xl bg-white border border-[#f3dede] shadow-sm hover:bg-blue-50 hover:scale-105 transition flex items-center justify-center text-xs font-medium {{ $employee['today_status'] == 'tersedia' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'text-gray-500' }}"
                                        title="Ubah status ketersediaan hari ini">
                                        {{ $employee['today_status'] == 'tersedia' ? '✅' : '⏰' }}
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition
                                        class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-pink-100 z-50 py-2">
                                        <form action="{{ route('owner.employee.today-status', $employee['pegawai_id']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status_ketersediaan" value="tersedia">
                                            <button type="submit" @click="open = false"
                                                class="w-full text-left px-3 py-2 hover:bg-blue-50 rounded-xl flex items-center gap-2 {{ $employee['today_status'] == 'tersedia' ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                                ✅ Tersedia Hari Ini
                                            </button>
                                        </form>
                                        <form action="{{ route('owner.employee.today-status', $employee['pegawai_id']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status_ketersediaan" value="tidak_tersedia">
                                            <button type="submit" @click="open = false"
                                                class="w-full text-left px-3 py-2 hover:bg-blue-50 rounded-xl flex items-center gap-2 {{ $employee['today_status'] == 'tidak_tersedia' ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                                ⏰ Libur Hari Ini
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- EDIT --}}
                                <a href="{{ route('owner.employee.edit-form', $employee['pegawai_id']) }}"
                                    class="w-9 h-9 rounded-xl bg-white border border-[#f3dede] shadow-sm hover:bg-pink-50 hover:scale-105 transition flex items-center justify-center">
                                    ✏️
                                </a>

                                {{-- RESIGN --}}
                                <form action="{{ route('owner.employee.resign', $employee['pegawai_id']) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menonaktifkan karyawan ini?');">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-[#f3dede] shadow-sm hover:bg-red-100 hover:scale-105 transition">⛔</button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="10" class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="text-5xl mb-4">📊</div>
                                <h3 class="text-xl font-semibold text-[#2d2a26] mb-2">Belum ada data karyawan</h3>
                                <p class="text-sm text-gray-500">Belum ada aktivitas karyawan pada periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    {{-- No-search-result row (hidden by default) --}}
                    <tr id="noResultRow" class="hidden">
                        <td colspan="10" class="py-10 text-center">
                            <div class="flex flex-col items-center">
                                <div class="text-4xl mb-3">🔍</div>
                                <p class="text-sm text-gray-500">No employees match your search.</p>
                            </div>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>

    {{-- MODAL --}}
    @include('owner.employees.addemployee')

    </div>

</div>

<script>
const searchInput  = document.getElementById('searchEmployee');
const clearBtn     = document.getElementById('clearSearch');
const rows         = document.querySelectorAll('.employee-row');
const noResultRow  = document.getElementById('noResultRow');

function filterTable() {
    const keyword = searchInput.value.toLowerCase().trim();

    clearBtn.classList.toggle('hidden', keyword === '');

    let visibleCount = 0;

    rows.forEach(row => {
        const name    = row.dataset.name    || '';
        const role    = row.dataset.role    || '';
        const today   = row.dataset.today   || '';
        const since   = row.dataset.since   || '';

        const match = !keyword
            || name.includes(keyword)
            || role.includes(keyword)
            || today.includes(keyword)
            || since.includes(keyword);

        row.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });

    if (noResultRow) {
        noResultRow.classList.toggle('hidden', visibleCount > 0 || keyword === '');
    }
}

if (searchInput) {
    searchInput.addEventListener('input', filterTable);
}

if (clearBtn) {
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        filterTable();
        searchInput.focus();
    });
}
</script>

@endsection