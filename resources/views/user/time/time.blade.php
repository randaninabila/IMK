@extends('user.app')

@section('content')

<style>
    footer {
        display: none !important;
    }

    .calendar-day {
        width: 44px;
        height: 44px;
        margin: 0 auto;
        border-radius: 14px;
        transition: 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        font-size: 13px;
        font-weight: 500;
    }

    .calendar-day:not(.disabled-day):hover {
        background: #FDE0E5;
        transform: translateY(-1px);
    }

    .selected-date {
        background: #FF6376 !important;
        color: white !important;
        font-weight: 800;
        width: 72px;
        border-radius: 14px;
    }

    .today-dot::after {
        content: '';
        width: 4px;
        height: 4px;
        background: #FF5B71;
        border-radius: 999px;
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
    }

    .disabled-day {
        color: #E3C8CC;
        cursor: not-allowed;
    }

    .outside-month {
        color: #E7C7C9;
    }

    .time-btn {
        width: 120px;
        height: 40px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.10);
        font-size: 13px;
        font-weight: 500;
        transition: 0.2s ease;
    }

    .time-btn:not(.unavailable-time):hover {
        transform: translateY(-1px);
        background: #FFF2F3;
    }

    .selected-time {
        background: #FF6376 !important;
        color: white !important;
        font-weight: 800;
    }

    .unavailable-time {
        background: #F2F1F1 !important;
        color: #888 !important;
        cursor: not-allowed;
        box-shadow: inset 0 0 0 1px #ddd;
    }

    .unavailable-time:hover {
        transform: none;
    }

    .radio-circle {
        width: 20px;
        height: 20px;
        border: 2px solid #FF6376;
        border-radius: 999px;
        display: inline-block;
        flex-shrink: 0;
        position: relative;
    }

    .selected-specialist .radio-circle::after {
        content: '';
        width: 9px;
        height: 9px;
        background: #FF6376;
        border-radius: 999px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<div class="min-h-screen bg-gradient-to-b from-[#FFE4E8] via-[#FFF4F6] to-white text-[#3A372E] pb-[150px] pt-[72px]">

    <!-- HEADER -->
    <section class="px-[36px] md:px-[42px] pt-[54px]">
        <div class="flex items-start justify-between">
            <h1 class="text-[54px] md:text-[76px] font-extrabold tracking-[-0.04em] leading-none">
                Choose Your Time
            </h1>

            <!-- PROGRESS -->
            <div class="hidden md:flex items-center gap-[5px] mt-[74px] mr-[0px]">
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F47CA5]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F9B5C7]"></div>
                <div class="w-[74px] h-[8px] rounded-full bg-[#F9B5C7]"></div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="px-[36px] md:px-[52px] mt-[58px]">
        <div class="grid grid-cols-1 lg:grid-cols-[640px_1fr] gap-[95px] items-start">

            <!-- LEFT CONTENT -->
            <div class="space-y-[64px]">

                <!-- CALENDAR -->
                <div class="bg-white rounded-[26px] shadow-[0_20px_35px_rgba(0,0,0,0.05)] px-[30px] pt-[30px] pb-[36px]">
                    <div class="flex items-center justify-between">
                        <h2 id="calendarTitle" class="text-[30px] font-extrabold tracking-[-0.03em]">
                            Month Year
                        </h2>

                        <div class="flex gap-[24px] text-[28px] leading-none">
                            <button type="button"
                                    onclick="changeMonth(-1)"
                                    class="hover:opacity-60 transition">
                                ‹
                            </button>

                            <button type="button"
                                    onclick="changeMonth(1)"
                                    class="hover:opacity-60 transition">
                                ›
                            </button>
                        </div>
                    </div>

                    <!-- DAYS HEADER -->
                    <div class="grid grid-cols-7 mt-[30px] text-center text-[12px] font-extrabold text-[#9C3D3D]">
                        <div>MO</div>
                        <div>TU</div>
                        <div>WE</div>
                        <div>TH</div>
                        <div>FR</div>
                        <div>SA</div>
                        <div>SU</div>
                    </div>

                    <!-- CALENDAR DATES -->
                    <div id="calendarGrid" class="grid grid-cols-7 mt-[24px] gap-y-[24px] text-center">
                        <!-- Calendar generated by JS -->
                    </div>
                </div>

                <!-- SPECIALIST -->
                <div class="bg-white rounded-[26px] shadow-[0_20px_35px_rgba(0,0,0,0.04)] px-[28px] pt-[30px] pb-[34px]">
                    <div class="flex items-center justify-between mb-[18px]">
                        <h2 class="text-[24px] font-extrabold">
                            Specialist
                        </h2>

                        <span class="bg-[#FFF0F3] px-[35px] py-[8px] rounded-full text-[18px] font-semibold">
                            Tuasan
                        </span>
                    </div>

                    <!-- ANY AVAILABLE SPECIALIST -->
                    <button type="button"
                            onclick="selectSpecialist('any')"
                            id="specialist-any"
                            class="specialist-option w-full bg-[#FDE7EA] rounded-[12px] px-[24px] py-[18px] flex items-center justify-between text-left">
                        <div>
                            <h3 class="text-[18px] font-extrabold">
                                Any available specialist
                            </h3>

                            <p class="text-[13px] mt-[4px] text-[#3A372E]/80">
                                We'll assign the best artist for your chosen time
                            </p>
                        </div>

                        <span class="radio-circle"></span>
                    </button>

                    <!-- WARNING -->
                    <div class="mt-[12px] bg-[#FDECEF] rounded-[12px] px-[32px] py-[16px]">
                        <p class="text-[13px] leading-[1.25] text-[#9B4055]">
                            Dina is unable to perform Facial Detox' on this date. Please choose another
                            specialist or select a different time.
                        </p>
                    </div>

                    <!-- CHOOSE SPECIALIST -->
                    <div id="specialist-manual"
                         class="specialist-option selected-specialist w-full mt-[12px] bg-[#FDE0E5] rounded-[12px] px-[24px] py-[18px] text-left">

                        <button type="button"
                                onclick="toggleSpecialistDropdown()"
                                class="w-full flex items-start justify-between text-left">
                            <div>
                                <h3 class="text-[18px] font-extrabold">
                                    Choose specialist
                                </h3>

                                <p class="text-[12px] mt-[3px] text-[#3A372E]/80">
                                    Choose manually
                                </p>
                            </div>

                            <div class="flex items-center gap-[22px]">
                                <span id="specialistChevron" class="text-[24px] leading-none text-[#A45566]">
                                    ⌄
                                </span>

                                <span class="radio-circle mt-[2px]"></span>
                            </div>
                        </button>

                        <div class="mt-[6px] bg-white rounded-[14px] px-[18px] py-[8px] w-[410px] max-w-full flex items-center justify-between">
                            <div>
                                <p id="selectedSpecialistName" class="text-[13px] leading-none">
                                    Natalie
                                </p>

                                <p id="selectedSpecialistSkill" class="text-[10px] mt-[4px] text-[#3A372E]/80">
                                    Hair spa, facial detox
                                </p>
                            </div>

                            <button type="button"
                                    onclick="clearManualSpecialist()"
                                    class="text-[22px] leading-none text-[#3A372E]/80 hover:opacity-60">
                                ×
                            </button>
                        </div>

                        <!-- DROPDOWN -->
                        <div id="specialistDropdown" class="hidden mt-[10px] bg-white rounded-[14px] overflow-hidden shadow-sm">
                            <button type="button"
                                    onclick="chooseManualSpecialist('Natalie', 'Hair spa, facial detox')"
                                    class="w-full px-[18px] py-[10px] text-left hover:bg-[#FFF0F3] transition">
                                <p class="text-[13px] font-semibold">Natalie</p>
                                <p class="text-[10px] text-[#3A372E]/75">Hair spa, facial detox</p>
                            </button>

                            <button type="button"
                                    onclick="chooseManualSpecialist('Dina', 'Facial detox, totok wajah')"
                                    class="w-full px-[18px] py-[10px] text-left hover:bg-[#FFF0F3] transition">
                                <p class="text-[13px] font-semibold">Dina</p>
                                <p class="text-[10px] text-[#3A372E]/75">Facial detox, totok wajah</p>
                            </button>

                            <button type="button"
                                    onclick="chooseManualSpecialist('Aurel', 'Massage, lulur, ratus')"
                                    class="w-full px-[18px] py-[10px] text-left hover:bg-[#FFF0F3] transition">
                                <p class="text-[13px] font-semibold">Aurel</p>
                                <p class="text-[10px] text-[#3A372E]/75">Massage, lulur, ratus</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="pt-[6px]">
                <h2 class="text-[31px] font-extrabold tracking-[-0.03em] mb-[28px]">
                    Available Times
                </h2>

                <!-- MORNING -->
                <div>
                    <div class="flex items-center gap-[12px] mb-[14px]">
                        <span class="text-[24px]">☼</span>
                        <h3 class="text-[20px] font-semibold">
                            Morning
                        </h3>
                    </div>

                    <div class="grid grid-cols-3 gap-[10px] max-w-[410px]">
                        <button type="button" onclick="selectTime('09:00 AM', this)" class="time-btn">09:00 AM</button>
                        <button type="button" onclick="selectTime('09:30 AM', this)" class="time-btn">09:30 AM</button>
                        <button type="button" onclick="selectTime('10:00 AM', this)" class="time-btn">10:00 AM</button>
                        <button type="button" onclick="selectTime('10:30 AM', this)" id="defaultTimeBtn" class="time-btn selected-time">10:30 AM</button>
                        <button type="button" onclick="selectTime('11:00 AM', this)" class="time-btn">11:00 AM</button>
                        <button type="button" onclick="selectTime('11:30 AM', this)" class="time-btn">11:30 AM</button>
                    </div>
                </div>

                <!-- AFTERNOON -->
                <div class="mt-[28px]">
                    <div class="flex items-center gap-[12px] mb-[14px]">
                        <span class="text-[24px]">☀</span>
                        <h3 class="text-[20px] font-semibold">
                            Afternoon
                        </h3>
                    </div>

                    <div class="grid grid-cols-3 gap-[10px] max-w-[410px]">
                        <button type="button" onclick="selectTime('01:00 PM', this)" class="time-btn">01:00 PM</button>
                        <button type="button" onclick="selectTime('02:30 PM', this)" class="time-btn">02:30 PM</button>
                        <button type="button" onclick="selectTime('03:00 PM', this)" class="time-btn">03:00 PM</button>
                        <button type="button" onclick="selectTime('04:30 PM', this)" class="time-btn">04:30 PM</button>
                        <button type="button" onclick="selectTime('05:00 PM', this)" class="time-btn">05:00 PM</button>
                        <button type="button" disabled class="time-btn unavailable-time">06:00 PM</button>
                    </div>
                </div>

                <!-- EVENING -->
                <div class="mt-[28px]">
                    <div class="flex items-center gap-[12px] mb-[14px]">
                        <span class="text-[24px]">♨</span>
                        <h3 class="text-[20px] font-semibold">
                            Evening
                        </h3>
                    </div>

                    <div class="grid grid-cols-3 gap-[10px] max-w-[410px]">
                        <button type="button" onclick="selectTime('07:00 PM', this)" class="time-btn">07:00 PM</button>
                        <button type="button" onclick="selectTime('07:30 PM', this)" class="time-btn">07:30 PM</button>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

<!-- BOTTOM BAR -->
<div class="fixed left-0 right-0 bottom-0 z-40 bg-[#FFF9FA]/95 backdrop-blur-sm border-t border-[#F4ECEE]">
    <div class="px-[36px] md:px-[68px] py-[30px] flex items-center justify-between">

        <div>
            <p class="text-[20px] font-extrabold leading-none">
                Selected Time
            </p>

            <p id="selectedTimeText" class="text-[30px] font-extrabold tracking-[-0.02em] mt-[10px] leading-none text-black">
                -
            </p>
        </div>

        <div class="flex items-center gap-[20px]">
            <a href="{{ url('/schedule') }}"
               class="w-[140px] bg-[#E4C2C5] text-[#6F5D5D] rounded-full py-[18px] text-center text-[20px] font-extrabold hover:bg-[#d8b1b5] transition">
                <span class="mr-2">←</span>
                Back
            </a>

            <button type="button"
                    onclick="continueTime()"
                    class="w-[245px] bg-[#F8A9B4] text-[#3A372E] rounded-full py-[18px] text-center text-[20px] font-extrabold hover:bg-[#F47CA5] transition">
                Continue
                <span class="ml-2">→</span>
            </button>
        </div>

    </div>
</div>

<script>
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    let selectedDateObj = new Date(today);
    let selectedTime = '10:30 AM';
    let selectedSpecialist = 'natalie';
    let manualSpecialistName = 'Natalie';
    let manualSpecialistSkill = 'Hair spa, facial detox';

    function dateKey(date) {
        return `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`;
    }

    function isSameDate(a, b) {
        return dateKey(a) === dateKey(b);
    }

    function formatDate(date) {
        const day = dayNames[date.getDay()];
        const dateNumber = date.getDate();
        const month = monthNames[date.getMonth()];

        return `${day}, ${dateNumber} ${month}`;
    }

    function renderCalendar() {
        const calendarTitle = document.getElementById('calendarTitle');
        const calendarGrid = document.getElementById('calendarGrid');

        calendarTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        calendarGrid.innerHTML = '';

        const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
        const lastDayOfMonth = new Date(currentYear, currentMonth + 1, 0);

        const startOffset = (firstDayOfMonth.getDay() + 6) % 7;
        const totalDaysInMonth = lastDayOfMonth.getDate();

        const previousMonthLastDate = new Date(currentYear, currentMonth, 0).getDate();

        const totalCells = 42;

        for (let i = 0; i < totalCells; i++) {
            const cell = document.createElement('button');
            cell.type = 'button';
            cell.className = 'calendar-day';

            let cellDate;
            let dayNumber;
            let isCurrentMonth = true;

            if (i < startOffset) {
                dayNumber = previousMonthLastDate - startOffset + i + 1;
                cellDate = new Date(currentYear, currentMonth - 1, dayNumber);
                isCurrentMonth = false;
            } else if (i >= startOffset + totalDaysInMonth) {
                dayNumber = i - (startOffset + totalDaysInMonth) + 1;
                cellDate = new Date(currentYear, currentMonth + 1, dayNumber);
                isCurrentMonth = false;
            } else {
                dayNumber = i - startOffset + 1;
                cellDate = new Date(currentYear, currentMonth, dayNumber);
            }

            cellDate.setHours(0, 0, 0, 0);
            cell.textContent = dayNumber;

            if (!isCurrentMonth) {
                cell.classList.add('outside-month');
            }

            if (cellDate < today) {
                cell.classList.add('disabled-day');
                cell.disabled = true;
            } else {
                cell.onclick = function () {
                    selectDate(cellDate);
                };
            }

            if (isSameDate(cellDate, today)) {
                cell.classList.add('today-dot');
            }

            if (isSameDate(cellDate, selectedDateObj)) {
                cell.classList.add('selected-date');
            }

            calendarGrid.appendChild(cell);
        }
    }

    function changeMonth(direction) {
        currentMonth += direction;

        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }

        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }

        renderCalendar();
    }

    function selectDate(date) {
        if (date < today) {
            return;
        }

        selectedDateObj = new Date(date);
        selectedDateObj.setHours(0, 0, 0, 0);

        currentMonth = selectedDateObj.getMonth();
        currentYear = selectedDateObj.getFullYear();

        renderCalendar();
        updateSelectedTimeText();
    }

    function selectTime(timeText, button) {
        if (button.classList.contains('unavailable-time')) {
            return;
        }

        selectedTime = timeText;

        document.querySelectorAll('.time-btn').forEach((btn) => {
            btn.classList.remove('selected-time');
        });

        button.classList.add('selected-time');
        updateSelectedTimeText();
    }

    function selectSpecialist(key) {
        selectedSpecialist = key;

        document.querySelectorAll('.specialist-option').forEach((option) => {
            option.classList.remove('selected-specialist');
        });

        if (key === 'any') {
            document.getElementById('specialist-any').classList.add('selected-specialist');
        } else {
            document.getElementById('specialist-manual').classList.add('selected-specialist');
        }
    }

    function toggleSpecialistDropdown() {
        selectSpecialist('manual');

        const dropdown = document.getElementById('specialistDropdown');
        const chevron = document.getElementById('specialistChevron');

        dropdown.classList.toggle('hidden');
        chevron.textContent = dropdown.classList.contains('hidden') ? '⌄' : '⌃';
    }

    function chooseManualSpecialist(name, skill) {
        manualSpecialistName = name;
        manualSpecialistSkill = skill;

        document.getElementById('selectedSpecialistName').textContent = name;
        document.getElementById('selectedSpecialistSkill').textContent = skill;

        document.getElementById('specialistDropdown').classList.add('hidden');
        document.getElementById('specialistChevron').textContent = '⌄';

        selectSpecialist('manual');
    }

    function clearManualSpecialist() {
        manualSpecialistName = 'Choose specialist';
        manualSpecialistSkill = 'Choose manually';

        document.getElementById('selectedSpecialistName').textContent = manualSpecialistName;
        document.getElementById('selectedSpecialistSkill').textContent = manualSpecialistSkill;

        selectSpecialist('any');
    }

    function updateSelectedTimeText() {
        const selectedTimeText = document.getElementById('selectedTimeText');
        selectedTimeText.textContent = `${formatDate(selectedDateObj)} at ${selectedTime}`;
    }

    function continueTime() {
        const bookingTime = {
            date: formatDate(selectedDateObj),
            time: selectedTime,
            specialistType: selectedSpecialist,
            specialistName: selectedSpecialist === 'any' ? 'Any available specialist' : manualSpecialistName
        };

        localStorage.setItem('booking_time', JSON.stringify(bookingTime));

        window.location.href = "{{ url('/details') }}";
    }

    renderCalendar();
    updateSelectedTimeText();
    selectSpecialist('manual');
</script>

@endsection