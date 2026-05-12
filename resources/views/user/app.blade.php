<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dina Salon Muslimah</title>

    @vite('resources/css/app.css')

    <!-- FONT INTER -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter';
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    @include('user.partials.navbar')
    
    {{-- GLOBAL NOTIFICATION --}}
    <div class="
        fixed top-24 left-0 right-0
        z-[999]
        px-8
        pointer-events-none
    ">

        {{-- SUCCESS --}}
        @if(session('success'))

        <div
            x-data="{ show:true }"

            x-init="
                setTimeout(
                    () => show = false,
                    3000
                )
            "

            x-show="show"

            x-transition.opacity

            class="
                mb-4
                bg-green-100
                text-green-700
                px-5 py-4
                rounded-2xl
                flex items-center justify-between
                shadow-lg
                pointer-events-auto
            "
        >

            <span>
                {{ session('success') }}
            </span>

            <button
                @click="show = false"
                class="
                    ml-4
                    text-lg
                    font-bold
                    hover:opacity-70
                    transition
                "
            >
                ✕
            </button>

        </div>

        @endif


        {{-- ERROR --}}
        @if(session('error'))

        <div
            x-data="{ show:true }"

            x-init="
                setTimeout(
                    () => show = false,
                    3000
                )
            "

            x-show="show"

            x-transition.opacity

            class="
                mb-4
                bg-red-100
                text-red-600
                px-5 py-4
                rounded-2xl
                flex items-center justify-between
                shadow-lg
                pointer-events-auto
            "
        >

            <span>
                {{ session('error') }}
            </span>

            <button
                @click="show = false"
                class="
                    ml-4
                    text-lg
                    font-bold
                    hover:opacity-70
                    transition
                "
            >
                ✕
            </button>

        </div>

        @endif

    </div>

    {{-- Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('user.partials.footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>