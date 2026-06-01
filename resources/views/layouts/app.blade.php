<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Dina Salon Muslimah')
    </title>

    {{-- TAILWIND --}}
    @vite('resources/css/app.css')

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap"
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
    @yield('navbar')

    
    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>


    {{-- FOOTER --}}
    @yield('footer')

</body>

</html>