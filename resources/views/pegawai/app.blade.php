<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dina Salon Muslimah</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
    const FONT_STEPS = [75, 80, 85, 90, 95, 100, 105, 110, 115, 120, 125];
    let currentStep = 5;

    function applyFontScale(step) {
        step = Math.max(0, Math.min(FONT_STEPS.length - 1, step));
        currentStep = step;
        const pct = FONT_STEPS[step];
        document.body.style.zoom = pct / 100;
        localStorage.setItem('fontStep', step);
        ['fontScaleLabel', 'fontScaleLabelUser', 'fontScaleLabelAdmin'].forEach(function(id) {
            const label = document.getElementById(id);
            if (label) label.textContent = pct + '%';
        });
    }

    function changeFontScale(dir) {
        applyFontScale(currentStep + dir);
    }

    (function () {
        const saved = parseInt(localStorage.getItem('fontStep'));
        const step  = (!isNaN(saved) && saved >= 0 && saved < FONT_STEPS.length) ? saved : 5;
        currentStep = step;
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.zoom = FONT_STEPS[step] / 100;
            ['fontScaleLabel', 'fontScaleLabelUser', 'fontScaleLabelAdmin'].forEach(function(id) {
                const label = document.getElementById(id);
                if (label) label.textContent = FONT_STEPS[step] + '%';
            });
        });
    })();
    </script>
</head>

<body x-data class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white font-sans min-h-screen">

    @include('pegawai.layouts.navbar', ['user' => auth()->user()])
    @include('pegawai.layouts.sidebar')

    <main class="lg:ml-[300px] px-4 lg:px-8 pb-15" style="padding-top: 75px;">
        @yield('content')
    </main>

</body>
</html>