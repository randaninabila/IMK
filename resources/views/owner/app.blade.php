<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Owner
    </title>

    {{-- TAILWIND --}}
    @vite('resources/css/app.css')

    {{-- FONT --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="
            https://fonts.googleapis.com/css2?
            family=Inter:wght@300;400;500;600;700
            &display=swap
        "
        rel="stylesheet"
    >

    {{-- CHART --}}
    <script
        src="https://cdn.jsdelivr.net/npm/chart.js"
    ></script>

    {{-- ALPINE --}}
    <script
        src="//unpkg.com/alpinejs"
        defer
    ></script>

    {{-- GLOBAL STYLE --}}
    <style>

        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

    </style>

</head>


<body class="text-gray-800">

    {{-- NAVBAR --}}
    @include('owner.navbar')


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


    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

</body>

</html>