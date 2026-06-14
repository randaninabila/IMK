<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dina Salon Muslimah')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>

    {{-- FAVICON --}}
    <link rel="icon" href="{{ asset('storage/salon/salon_logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/salon/salon_logo.png') }}">


    @stack('styles')
</head>

<body class="bg-white text-[#3A372E] overflow-x-hidden">

    @hasSection('navbar')
    @yield('navbar')
    @else
    @include('user.partials.navbar')
    @endif

    <div class="fixed top-24 left-0 right-0 z-[999] px-8 pointer-events-none">
        @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity
            class="mb-4 bg-green-100 text-green-700 px-5 py-4 rounded-2xl flex items-center justify-between shadow-lg pointer-events-auto">
            <span>
                {{ session('success') }}
            </span>

            <button type="button" @click="show = false" class="ml-4 text-lg font-bold hover:opacity-70 transition">
                ✕
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity
            class="mb-4 bg-red-100 text-red-600 px-5 py-4 rounded-2xl flex items-center justify-between shadow-lg pointer-events-auto">
            <span>
                {{ session('error') }}
            </span>

            <button type="button" @click="show = false" class="ml-4 text-lg font-bold hover:opacity-70 transition">
                ✕
            </button>
        </div>
        @endif
    </div>

    <main>
        @yield('content')
    </main>

    @hasSection('footer')
    @yield('footer')
    @else
    @include('user.partials.footer')
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')

</body>

</html>