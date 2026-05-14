<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dina Salon Muslimah</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white font-sans min-h-screen overflow-y-auto">

    {{-- TOP NAVBAR --}}
    @include('pegawai.layouts.navbar')

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @include('pegawai.layouts.sidebar')

        {{-- CONTENT --}}
        <main class="flex-1 px-8 pt-6 pb-15 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</body>
</html>