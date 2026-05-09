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
            max-w-[560px]
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
            Select Branch
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Semua --}}
            <button
                @click="selectedBranch='Semua'"
                :class="selectedBranch === 'Semua'
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-2xl px-3 py-2 flex items-center gap-3 transition-all duration-300"
            >
                <div
                    :class="selectedBranch === 'Semua'
                        ? 'border-pink-500'
                        : 'border-gray-300'"
                    class="w-5 h-5 rounded-full border flex items-center justify-center"
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

            {{-- Laudendang --}}
            <button
                @click="selectedBranch='Laudendang'"
                :class="selectedBranch === 'Laudendang'
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-2xl px-3 py-2 flex items-center gap-3 transition-all duration-300"
            >
                <div
                    :class="selectedBranch === 'Laudendang'
                        ? 'border-pink-500'
                        : 'border-gray-300'"
                    class="w-5 h-5 rounded-full border flex items-center justify-center"
                >
                    <div
                        x-show="selectedBranch === 'Laudendang'"
                        class="w-2.5 h-2.5 bg-pink-500 rounded-full"
                    ></div>
                </div>

                <span class="font-medium text-sm text-[#4A3B35]">
                    Laudendang
                </span>
            </button>

            {{-- Tuasan --}}
            <button
                @click="selectedBranch='Tuasan'"
                :class="selectedBranch === 'Tuasan'
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-2xl px-3 py-2 flex items-center gap-3 transition-all duration-300"
            >
                <div
                    :class="selectedBranch === 'Tuasan'
                        ? 'border-pink-500'
                        : 'border-gray-300'"
                    class="w-5 h-5 rounded-full border flex items-center justify-center"
                >
                    <div
                        x-show="selectedBranch === 'Tuasan'"
                        class="w-2.5 h-2.5 bg-pink-500 rounded-full"
                    ></div>
                </div>

                <span class="font-medium text-sm text-[#4A3B35]">
                    Tuasan
                </span>
            </button>

        </div>

        {{-- REPORT TYPE --}}
        <h2 class="text-[20px] leading-none font-bold text-[#3F342D] mt-6 mb-5"
            style="font-family: Playfair Display, serif;">
            Select Report Type
        </h2>

        <div class="grid grid-cols-2 gap-3">

            {{-- Financial --}}
            <button
                @click="toggleReport('Financial')"
                :class="selectedReports.includes('Financial')
                    ? 'border border-pink-500 bg-white'
                    : 'bg-[#FCEDEF] border border-transparent'"
                class="rounded-[24px] p-3 text-left transition-all duration-300 h-[72px]"
            >
                <div class="flex gap-4 items-start">

                    <div class="w-9 h-9 rounded-full bg-pink-200 flex items-center justify-center shrink-0 text-sm">
                        💳
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-[#4A3B35]"
                            style="font-family: Playfair Display, serif;">
                            Financial
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Revenue, expenses, and taxes.
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
                            Services
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Booking trends and popularity.
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
                            Employees
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Staff performance & hours.
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
                            Customers
                        </h3>

                        <p class="text-[10px] text-[#7A6A63] mt-1 leading-tight">
                            Demographics and retention.
                        </p>
                    </div>

                </div>
            </button>

        </div>

        {{-- DATE RANGE --}}
        <h2 class="text-[20px] leading-none font-bold text-[#3F342D] mt-6 mb-4"
            style="font-family: Playfair Display, serif;">
            Date Range
        </h2>

        <div class="flex flex-col md:flex-row items-center gap-4">

            {{-- START DATE --}}
            <div class="w-full">
                <label class="block text-sm text-gray-500 mb-2">
                    Start Date
                </label>

                <div class="relative">

                    <input
                        type="date"
                        x-model="startDate"
                        :max="endDate || ''"
                        class="w-full rounded-full bg-[#FFDDE3] px-4 py-2 outline-none text-[#4A3B35]"
                    >

                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none">
                    </div>

                </div>
            </div>

            {{-- ARROW --}}
            <div class="text-[20px] leading-none text-gray-500 mt-6 hidden md:block">
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
                x-model="endDate"
                :min="startDate || ''"
                class="w-full rounded-full bg-[#FFDDE3] px-4 py-2 outline-none text-[#4A3B35]"
            >

                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none">
                    </div>

                </div>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-4 mt-8">

            <button
                @click="showModal = false"
                class="px-10 py-3 rounded-full bg-[#F7EFEF] hover:opacity-80 transition font-semibold text-sm"
            >
                Cancel
            </button>

            <div class="relative">

                <button
                    @click="
                        if(exportMessage !== '') {
                            showExportError = true

                            setTimeout(() => {
                                showExportError = false
                            }, 3000)

                            return
                        }

                        console.log('Export PDF')
                    "
                    class="
                        px-8 py-3
                        rounded-full
                        text-white
                        font-semibold
                        bg-[#F58C98]
                        hover:opacity-90
                        transition
                    "
                >
                    Export PDF
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
                                Export can't continue
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