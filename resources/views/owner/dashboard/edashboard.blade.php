<div
    x-show="showModal"
    x-transition
    x-cloak
    x-effect="showModal
        ? document.body.classList.add('overflow-hidden')
        : document.body.classList.remove('overflow-hidden')"
    class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center overflow-hidden"
>

    <div
        @click.outside="showModal = false"
        class="
            bg-white
            w-full
            max-w-[760px]
            max-h-[95vh]
            overflow-y-auto
            rounded-[24px]
            px-5 py-4
            shadow-2xl
        "
    >

        {{-- SELECT BRANCH --}}
        <h2 class="text-[20px] leading-none font-bold text-[#3F342D] mb-6"
            style="font-family: Playfair Display, serif;">
            Pilih Cabang
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

            {{-- Semua --}}
            <button
                @click="selectedBranch='Semua'"
                :class="selectedBranch === 'Semua'
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="
                    rounded-2xl
                    px-4 py-4
                    flex items-center gap-4
                    transition-all duration-300
                    min-h-[72px]
                    w-full
                    text-left
                "
            >
                <div
                    :class="selectedBranch === 'Semua'
                        ? 'border-pink-500'
                        : 'border-gray-300'"
                    class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0"
                >
                    <div
                        x-show="selectedBranch === 'Semua'"
                        class="w-2.5 h-2.5 bg-pink-500 rounded-full"
                    ></div>
                </div>

                <span class="font-medium text-sm text-[#4A3B35]">
                    Semua
                </span>
            </button>

            @foreach($cabangs as $cabang)
                <button
                    @click="selectedBranch='{{ $cabang->nama_cabang }}'"
                    :class="selectedBranch === '{{ $cabang->nama_cabang }}'
                        ? 'border border-pink-500 bg-white'
                        : 'bg-[#FCEDEF] border border-transparent'"
                    class="
                        rounded-2xl
                        px-4 py-4
                        flex items-center gap-4
                        transition-all duration-300
                        min-h-[72px]
                        w-full
                        text-left
                    "
                >
                    <div
                        :class="selectedBranch === '{{ $cabang->nama_cabang }}'
                            ? 'border-pink-500'
                            : 'border-gray-300'"
                        class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0"
                    >
                        <div
                            x-show="selectedBranch === '{{ $cabang->nama_cabang }}'"
                            class="w-2.5 h-2.5 bg-pink-500 rounded-full"
                        ></div>
                    </div>

                    <span class="font-medium text-[15px] leading-snug text-[#4A3B35] break-words">
                        {{ $cabang->nama_cabang }}
                    </span>
                </button>
            @endforeach

        </div>

        {{-- REPORT TYPE --}}
        <h2 class="text-[20px] leading-none font-bold text-[#3F342D] mt-4 mb-4"
            style="font-family: Playfair Display, serif;">
            Pilih Jenis Laporan
        </h2>

        <div class="grid grid-cols-2 gap-3">

            {{-- Financial --}}
            <button
                @click="toggleReport('Financial')"
                :class="selectedReports.includes('Financial')
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[20px] p-3 text-left transition-all duration-300 h-[64px]"
            >
                <div class="flex gap-4 items-start">

                    <div class="w-9 h-9 rounded-full bg-pink-200 flex items-center justify-center shrink-0 text-sm">
                        💳
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Keuangan
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Pendapatan.
                        </p>
                    </div>

                </div>
            </button>

            {{-- Services --}}
            <button
                @click="toggleReport('Services')"
                :class="selectedReports.includes('Services')
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[24px] p-3 text-left transition-all duration-300 h-[72px]"
            >
                <div class="flex gap-4 items-start">

                    <div class="w-9 h-9 rounded-full bg-pink-200 flex items-center justify-center shrink-0 text-sm">
                        ✂️
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Layanan
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Tren pemesanan dan popularitas layanan.
                        </p>
                    </div>

                </div>
            </button>

            {{-- Employees --}}
            <button
                @click="toggleReport('Employees')"
                :class="selectedReports.includes('Employees')
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[24px] p-3 text-left transition-all duration-300 h-[72px]"
            >
                <div class="flex gap-4 items-start">

                    <div class="w-9 h-9 rounded-full bg-pink-200 flex items-center justify-center shrink-0 text-sm">
                        👨‍💼
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Pegawai
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Performa dan jam kerja staf.
                        </p>
                    </div>

                </div>
            </button>

            {{-- Customers --}}
            <button
                @click="toggleReport('Customers')"
                :class="selectedReports.includes('Customers')
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[24px] p-3 text-left transition-all duration-300 h-[72px]"
            >
                <div class="flex gap-4 items-start">

                    <div class="w-9 h-9 rounded-full bg-pink-200 flex items-center justify-center shrink-0 text-sm">
                        👥
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Pelanggan
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Demografi dan retensi pelanggan.
                        </p>
                    </div>

                </div>
            </button>

        </div>

    {{-- DATE RANGE --}}
    <h2 class="text-[20px] leading-none font-bold text-[#3F342D] mt-4 mb-3"
        style="font-family: Playfair Display, serif;">
        Rentang Waktu
    </h2>

    {{-- QUICK FILTER --}}
    <div class="grid grid-cols-5 gap-2 mb-5">

        {{-- Today --}}
        <button
            @click="selectedPeriod='today'"
            :class="selectedPeriod === 'today'
                ? 'bg-[#F58C98] text-white'
                : 'bg-[#FCEDEF] text-[#4A3B35]'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-300"
        >
            Hari Ini
        </button>

        {{-- This Week --}}
        <button
            @click="selectedPeriod='week'"
            :class="selectedPeriod === 'week'
                ? 'bg-[#F58C98] text-white'
                : 'bg-[#FCEDEF] text-[#4A3B35]'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-300"
        >
            Minggu Ini
        </button>

        {{-- This Month --}}
        <button
            @click="selectedPeriod='month'"
            :class="selectedPeriod === 'month'
                ? 'bg-[#F58C98] text-white'
                : 'bg-[#FCEDEF] text-[#4A3B35]'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-300"
        >
            Bulan Ini
        </button>

        {{-- This Year --}}
        <button
            @click="selectedPeriod='year'"
            :class="selectedPeriod === 'year'
                ? 'bg-[#F58C98] text-white'
                : 'bg-[#FCEDEF] text-[#4A3B35]'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-300"
        >
            Tahun Ini
        </button>

        {{-- Custom --}}
        <button
            @click="selectedPeriod='custom'"
            :class="selectedPeriod === 'custom'
                ? 'bg-[#F58C98] text-white'
                : 'bg-[#FCEDEF] text-[#4A3B35]'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-300"
        >
            Kustom
        </button>

    </div>

    {{-- CUSTOM DATE --}}
    <div
        x-show="selectedPeriod === 'custom'"
        x-transition
        class="flex flex-col md:flex-row items-center gap-4"
    >

        {{-- START DATE --}}
        <div class="w-full">
            <label class="block text-sm text-gray-500 mb-2">
                Tanggal Awal
            </label>

            <input
                type="date"
                x-model="startDate"
                :max="endDate || ''"
                class="
                    w-full
                    rounded-full
                    bg-[#FFDDE3]
                    px-5
                    py-2.5
                    outline-none
                    text-[#4A3B35]
                "
            >
        </div>

        {{-- ARROW --}}
        <div class="hidden md:block text-gray-400 mt-6 text-lg">
            →
        </div>

        {{-- END DATE --}}
        <div class="w-full">
            <label class="block text-sm text-gray-500 mb-2">
                Tanggal Akhir
            </label>

            <input
                type="date"
                x-model="endDate"
                :min="startDate || ''"
                class="
                    w-full
                    rounded-full
                    bg-[#FFDDE3]
                    px-5
                    py-2.5
                    outline-none
                    text-[#4A3B35]
                "
            >
        </div>

    </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-4 mt-6">

            <button
                @click="showModal = false"
                class="px-10 py-3 rounded-full bg-[#F7EFEF] hover:opacity-80 transition font-semibold text-sm"
            >
                Batal
            </button>

            <div class="relative">

                <button
                    @click="exportPDF()"
                    class="
                        px-8 py-3
                        rounded-full
                        text-white
                        text-sm
                        font-semibold
                        bg-[#F58C98]
                        hover:opacity-90
                        transition
                    "
                >
                    Ekspor PDF
                </button>

                {{-- Floating Warning --}}
                <div
                    x-show="showExportError"
                    x-transition.opacity.scale
                    class="
                        absolute
                        bottom-[120%]
                        right-0
                        w-[280px]
                        bg-[#3F342D]
                        text-white
                        rounded-2xl
                        px-4
                        py-3
                        shadow-xl
                    "
                >

                    {{-- Close --}}
                    <button
                        @click="showExportError = false"
                        class="
                            absolute
                            top-3
                            right-3
                            text-white/70
                            hover:text-white
                            transition
                            text-sm
                            font-bold
                        "
                    >
                        ✕
                    </button>

                    <div class="flex items-start gap-3 pr-5">

                        <div class="text-lg leading-none">
                            ⚠️
                        </div>

                        <div>
                            <p class="font-semibold text-sm mb-1">
                                Ekspor tidak dapat dilanjutkan
                            </p>

                            <p
                                x-text="exportMessage"
                                class="text-[13px] text-[#F5EAEA] leading-relaxed"
                            ></p>
                        </div>

                    </div>

                    {{-- Arrow --}}
                    <div
                        class="
                            absolute
                            -bottom-2
                            right-6
                            w-4 h-4
                            bg-[#3F342D]
                            rotate-45
                        "
                    ></div>

                </div>

            </div>

        </div>

    </div>

</div>