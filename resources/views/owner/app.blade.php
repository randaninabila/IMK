<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner</title>

    @vite('resources/css/app.css')

    <!-- FONT INTER -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ALPINE -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- ANTI KEDIP -->
    <style>
        body {
            font-family: 'Inter';
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="text-gray-800">

    {{-- Navbar --}}
    @include('owner.navbar')

    {{-- Content --}}
    <main>
        @yield('content')
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>